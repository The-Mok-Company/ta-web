<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'subject',
        'category_id',
        'message'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
