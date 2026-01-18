<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InquiryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'type',
        'product_id',
        'category_id',
        'quantity',
        'unit',
        'note',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
