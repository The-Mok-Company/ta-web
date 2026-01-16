<?php
// Quick test script for creating inquiry test data
// Run: php test_inquiries.php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Shop;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Enums\InquiryStatus;

echo "Creating test data...\n";

// Create seller
$seller = User::firstOrCreate(
    ['email' => 'seller@test.com'],
    [
        'name' => 'Test Seller',
        'password' => bcrypt('password'),
        'user_type' => 'seller',
        'email_verified_at' => now()
    ]
);

// Create shop
$shop = Shop::where('user_id', $seller->id)->first();
if (!$shop) {
    $shop = new Shop();
    $shop->user_id = $seller->id;
    $shop->name = 'Test Shop';
    $shop->slug = 'test-shop-' . time();
    $shop->verification_status = 1;
    $shop->registration_approval = 1;
    $shop->save();
}

// Create customer
$customer = User::firstOrCreate(
    ['email' => 'customer@test.com'],
    [
        'name' => 'Test Customer',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now()
    ]
);

// Get or create category
$category = Category::first();
if (!$category) {
    $category = Category::create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'parent_id' => 0
    ]);
}

// Create product
$product = Product::where('name', 'Test Product')
    ->where('user_id', $seller->id)
    ->first();
if (!$product) {
    $product = new Product();
    $product->name = 'Test Product';
    $product->added_by = 'seller';
    $product->user_id = $seller->id;
    $product->category_id = $category->id;
    $product->digital = 0;
    $product->auction_product = 0;
    $product->published = 1;
    $product->save();
}

// Create inquiries with different statuses
$statuses = InquiryStatus::cases();
foreach ($statuses as $index => $status) {
    $existing = ProductQuery::where('customer_id', $customer->id)
        ->where('seller_id', $seller->id)
        ->where('product_id', $product->id)
        ->where('question', "Test inquiry with status: {$status->label()}")
        ->first();
    
    if (!$existing) {
        $query = new ProductQuery();
        $query->customer_id = $customer->id;
        $query->seller_id = $seller->id;
        $query->product_id = $product->id;
        $query->category_id = $category->id;
        $query->question = "Test inquiry with status: {$status->label()}";
        $query->reply = in_array($status, [InquiryStatus::Responded, InquiryStatus::OfferSent]) ? 'Test reply' : null;
        $query->status = $status;
        $query->expires_at = now()->addMonth();
        $query->created_at = now()->subDays($index);
        $query->save();
    }
}

// Create expired inquiry
$expiredExists = ProductQuery::where('customer_id', $customer->id)
    ->where('seller_id', $seller->id)
    ->where('product_id', $product->id)
    ->where('question', 'Expired inquiry test')
    ->first();

if (!$expiredExists) {
    $expiredQuery = new ProductQuery();
    $expiredQuery->customer_id = $customer->id;
    $expiredQuery->seller_id = $seller->id;
    $expiredQuery->product_id = $product->id;
    $expiredQuery->category_id = $category->id;
    $expiredQuery->question = 'Expired inquiry test';
    $expiredQuery->status = InquiryStatus::New;
    $expiredQuery->expires_at = now()->subMonth()->subDay();
    $expiredQuery->created_at = now()->subMonths(2);
    $expiredQuery->save();
}

echo "✓ Test data created successfully!\n";
echo "Seller Login: seller@test.com / password\n";
echo "Customer Login: customer@test.com / password\n";
echo "Access: http://localhost:8000/seller/product-queries\n";
