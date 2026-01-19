<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','user_id','admin_id','note','status',
        'products_total','categories_total','subtotal',
        'tax','delivery','discount','extra_fees','total',
    ];

    protected $casts = [
        'products_total'   => 'decimal:2',
        'categories_total' => 'decimal:2',
        'subtotal'         => 'decimal:2',
        'tax'              => 'decimal:2',
        'delivery'         => 'decimal:2',
        'discount'         => 'decimal:2',
        'extra_fees'       => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function (Inquiry $inquiry) {
            if (empty($inquiry->code)) {
                $inquiry->code = 'INQ-' . strtoupper(Str::random(10));
            }
            if (empty($inquiry->status)) {
                $inquiry->status = 'draft';
            }
        });
    }

    public function items()
    {
        return $this->hasMany(InquiryItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
