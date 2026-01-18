<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $guarded = [];
    protected $fillable = ['address_id','price','category_id','tax','shipping_cost','discount','product_referral_code','coupon_code','coupon_applied','quantity','user_id','temp_user_id','owner_id','product_id','category_id','variation'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
