<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['label', 'link', 'sort_order', 'parent_id'];

    protected $casts = [
        'sort_order' => 'integer',
        'parent_id'  => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order', 'desc')->orderBy('id');
    }

    /** Children with nested children (for dropdown) */
    public function childrenWithNested()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->with('childrenWithNested')->orderBy('sort_order', 'desc')->orderBy('id');
    }
}
