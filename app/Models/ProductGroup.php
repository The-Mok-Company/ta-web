<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Support\Str;

class ProductGroup extends Model
{
    use HasFactory, PreventDemoModeChanges;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'icon',
        'order_level',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productGroup) {
            if (empty($productGroup->slug)) {
                $productGroup->slug = Str::slug($productGroup->name) . '-' . Str::random(5);
            }
        });
    }

    /**
     * Get the sub-category that owns the product group.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the main category through sub-category.
     */
    public function mainCategory()
    {
        return $this->hasOneThrough(
            Category::class,
            Category::class,
            'id', // Foreign key on categories table (sub-category)
            'id', // Foreign key on categories table (main category)
            'category_id', // Local key on product_groups table
            'parent_id' // Local key on categories table (sub-category's parent)
        );
    }

    /**
     * Get all products in this group.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active product groups.
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
