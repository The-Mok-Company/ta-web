<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class ProductQuery extends Model
{
    use HasFactory,PreventDemoModeChanges;

    protected $fillable = [
        'customer_id',
        'seller_id',
        'product_id',
        'category_id',
        'question',
        'reply',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'status' => InquiryStatus::class,
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            if (empty($query->status)) {
                $query->status = InquiryStatus::New;
            }
            if (empty($query->expires_at)) {
                $query->expires_at = Carbon::now()->addMonth();
            }
        });
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
  
    public function user(){
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Scopes for filtering
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeByProduct($query, $productId)
    {
        if ($productId) {
            return $query->where('product_id', $productId);
        }
        return $query;
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', Carbon::now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now())
                     ->where('status', '!=', InquiryStatus::Expired);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast() && $this->status !== InquiryStatus::Expired;
    }
}
