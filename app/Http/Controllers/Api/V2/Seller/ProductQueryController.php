<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Models\ProductQuery;
use Illuminate\Http\Request;
use App\Http\Resources\V2\Seller\ProductQueryResource;
use App\Enums\InquiryStatus;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductQueryReplyNotification;

class ProductQueryController extends Controller
{

    public function product_queries()
    {
        $queries = ProductQuery::where('seller_id', auth()->user()->id)->latest()->paginate(20);
        return ProductQueryResource::collection($queries);
    }

    public function product_queries_show($id)
    {
        $product_query = ProductQuery::findOrFail($id);
        if (auth()->user()->id != $product_query->seller_id) {
            return $this->failed(translate('This Query is not yours'));
        }
        
        return new ProductQueryResource($product_query);
    }

    public function product_queries_reply(Request $request, $id)
    {
        $this->validate($request, [
            'reply' => 'required',
        ]);
        
        $product_query = ProductQuery::findOrFail($id);
        if (auth()->user()->id != $product_query->seller_id) {
            return $this->failed(translate('You cannot reply to this query'));
        }

        $product_query->reply = $request->reply;
        // Auto-update status to Responded if it was New or Pending
        if (in_array($product_query->status, [InquiryStatus::New, InquiryStatus::Pending])) {
            $product_query->status = InquiryStatus::Responded;
        }
        $product_query->save();

        // Notify customer about reply
        $product_query->loadMissing('product', 'user');
        $notificationType = get_notification_type('product_query_replied_customer', 'type');
        if ($notificationType && $notificationType->status == 1 && $product_query->user) {
            $product = $product_query->product;
            $link = $product ? (route('product', $product->slug) . '#product_query') : null;
            $statusLabel = $product_query->status instanceof InquiryStatus ? $product_query->status->label() : (string) $product_query->status;
            $data = [
                'notification_type_id' => $notificationType->id,
                'product_query_id' => $product_query->id,
                'product_id' => $product?->id,
                'product_slug' => $product?->slug,
                'product_name' => $product ? $product->getTranslation('name') : null,
                'status' => $product_query->status instanceof InquiryStatus ? $product_query->status->value : (string) $product_query->status,
                'status_label' => $statusLabel,
                'link' => $link,
            ];
            Notification::send($product_query->user, new ProductQueryReplyNotification($data));
        }
        return $this->success(translate('Replied successfully'));
    }
}
