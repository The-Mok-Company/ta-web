<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQuery;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductQueryReplyNotification;
use App\Enums\InquiryStatus;

class ProductQueryController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_product_queries'])->only('admin_index');
    }

    /**
     * Retrieve queries that belongs to current seller
     */
    public function index()
    {
        $admin_id = get_admin()->id;
        $queries = ProductQuery::where('seller_id', $admin_id)->latest()->paginate(20);
        return view('backend.support.product_query.index', compact('queries'));
    }

    /**
     * Retrieve specific query using query id.
     */
    public function show($id)
    {
        $query = ProductQuery::find(decrypt($id));
        return view('backend.support.product_query.show', compact('query'));
    }

    /**
     * store products queries through the ProductQuery model
     * data comes from product details page
     * authenticated user can leave queries about the product
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|string',
        ]);
        $product = Product::find($request->product);

        $query = new ProductQuery();
        $query->customer_id = Auth::id();
        $query->seller_id = $product->user_id;
        $query->product_id = $product->id;
        $query->category_id = $product->category_id; // Set category for filtering
        $query->question = $request->question;
        $query->save();
        flash(translate('Your query has been submittes successfully'))->success();
        return redirect()->back();
    }

    /**
     * Store reply against the question from Admin panel
     */

    public function reply(Request $request, $id)
    {
        $this->validate($request, [
            'reply' => 'required',
        ]);
        $query = ProductQuery::find($id);
        $query->reply = $request->reply;
        // Auto-update status to Responded if it was New or Pending
        if (in_array($query->status, [InquiryStatus::New, InquiryStatus::Pending])) {
            $query->status = InquiryStatus::Responded;
        }
        $query->save();

        // Notify customer about reply
        $query->loadMissing('product', 'user');
        $notificationType = get_notification_type('product_query_replied_customer', 'type');
        if ($notificationType && $notificationType->status == 1 && $query->user) {
            $product = $query->product;
            $link = $product ? (route('product', $product->slug) . '#product_query') : null;
            $statusLabel = $query->status instanceof InquiryStatus ? $query->status->label() : (string) $query->status;
            $data = [
                'notification_type_id' => $notificationType->id,
                'product_query_id' => $query->id,
                'product_id' => $product?->id,
                'product_slug' => $product?->slug,
                'product_name' => $product ? $product->getTranslation('name') : null,
                'status' => $query->status instanceof InquiryStatus ? $query->status->value : (string) $query->status,
                'status_label' => $statusLabel,
                'link' => $link,
            ];
            Notification::send($query->user, new ProductQueryReplyNotification($data));
        }
        flash(translate('Replied successfully!'))->success();
        return redirect()->route('product_query.index');
    }
}
