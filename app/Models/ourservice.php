<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ourservice extends Model
{
    use HasFactory;
    protected $table = 'ourservices';
    protected $fillable = ['key', 'value'];
    protected $casts = [
        'value' => 'array',
    ];
}
