# Testing Guide: New Customers & Inquired Customers Features

## Prerequisites

1. **Start the development server:**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`

2. **Ensure you're logged in as Admin:**
   - Go to: `http://localhost:8000/admin/login`
   - Login with admin credentials
   - You need the `view_all_customers` permission

## Testing: New Customers Feature

### Step 1: Access the Feature

1. **Navigate to the admin panel**
2. **Click on "Customers" in the sidebar**
3. **Click on "New Customers"** from the dropdown menu
   - Or go directly to: `http://localhost:8000/admin/customers/new`

### Step 2: Create Test Data (if needed)

If you don't have recent customers, create some test data:

**Option A: Using Tinker**
```bash
php artisan tinker
```

```php
use App\Models\User;
use Carbon\Carbon;

// Create customers registered in different time periods
// Customer registered today
User::create([
    'name' => 'New Customer Today',
    'email' => 'newtoday@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'customer',
    'email_verified_at' => now(),
    'created_at' => now()
]);

// Customer registered 5 days ago
User::create([
    'name' => 'New Customer 5 Days',
    'email' => 'new5days@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'customer',
    'email_verified_at' => now(),
    'created_at' => Carbon::now()->subDays(5)
]);

// Customer registered 20 days ago
User::create([
    'name' => 'New Customer 20 Days',
    'email' => 'new20days@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'customer',
    'email_verified_at' => now(),
    'created_at' => Carbon::now()->subDays(20)
]);

// Customer registered 35 days ago (should not appear in default 30-day view)
User::create([
    'name' => 'Old Customer 35 Days',
    'email' => 'old35days@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'customer',
    'email_verified_at' => now(),
    'created_at' => Carbon::now()->subDays(35)
]);

echo "Test customers created!\n";
```

**Option B: Using SQL**
```sql
-- Customer registered today
INSERT INTO users (name, email, password, user_type, email_verified_at, created_at, updated_at)
VALUES ('New Customer Today', 'newtoday@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW(), NOW(), NOW());

-- Customer registered 5 days ago
INSERT INTO users (name, email, password, user_type, email_verified_at, created_at, updated_at)
VALUES ('New Customer 5 Days', 'new5days@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW(), DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY));

-- Customer registered 20 days ago
INSERT INTO users (name, email, password, user_type, email_verified_at, created_at, updated_at)
VALUES ('New Customer 20 Days', 'new20days@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW(), DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY));
```

### Step 3: Test Functionality

#### Test 3.1: Default View (Last 30 Days)
- ✅ Verify that customers registered within the last 30 days are shown
- ✅ Verify that customers older than 30 days are NOT shown
- ✅ Check that customers are sorted by registration date (newest first)

#### Test 3.2: Time Period Filter
1. **Select "Last 7 days"** from the dropdown
   - ✅ Only customers registered in the last 7 days should appear
2. **Select "Last 60 days"** from the dropdown
   - ✅ Customers registered in the last 60 days should appear
3. **Select "Last 90 days"** from the dropdown
   - ✅ Customers registered in the last 90 days should appear

#### Test 3.3: Search Functionality
1. **Search by name:**
   - Type a customer name in the search box
   - ✅ Only matching customers should appear
2. **Search by email:**
   - Type a customer email in the search box
   - ✅ Only matching customers should appear

#### Test 3.4: Verification Status Filter
1. **Select "Verified"** from the verification status dropdown
   - ✅ Only verified customers should appear
2. **Select "Unverified"** from the verification status dropdown
   - ✅ Only unverified customers should appear

#### Test 3.5: Combined Filters
1. **Combine time period + search:**
   - Select "Last 7 days" and search for a name
   - ✅ Results should match both criteria
2. **Combine verification status + search:**
   - Select "Verified" and search for an email
   - ✅ Results should match both criteria

#### Test 3.6: Pagination
- If you have more than 15 customers, verify pagination works
- ✅ Click through pages to ensure all customers are accessible

## Testing: Inquired Customers Feature

### Step 1: Access the Feature

1. **Navigate to the admin panel**
2. **Click on "Customers" in the sidebar**
3. **Click on "Inquired Customers"** from the dropdown menu
   - Or go directly to: `http://localhost:8000/admin/customers/inquired`

### Step 2: Create Test Data (if needed)

You need customers who have submitted product inquiries:

**Option A: Using Tinker**
```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\Category;
use App\Models\Shop;

// Create a seller
$seller = User::firstOrCreate(
    ['email' => 'seller@test.com'],
    [
        'name' => 'Test Seller',
        'password' => bcrypt('password'),
        'user_type' => 'seller',
        'email_verified_at' => now()
    ]
);

// Create shop for seller
if (!$seller->shop) {
    $shop = \App\Models\Shop::create([
        'user_id' => $seller->id,
        'name' => 'Test Shop',
        'slug' => 'test-shop-' . time(),
        'verification_status' => 1,
        'registration_approval' => 1
    ]);
}

// Create a category
$category = \App\Models\Category::first();
if (!$category) {
    $category = \App\Models\Category::create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'parent_id' => 0
    ]);
}

// Create a product
$product = Product::firstOrCreate(
    ['name' => 'Test Product', 'user_id' => $seller->id],
    [
        'added_by' => 'seller',
        'category_id' => $category->id,
        'digital' => 0,
        'auction_product' => 0,
        'published' => 1
    ]
);

// Create customers with inquiries
$customer1 = User::firstOrCreate(
    ['email' => 'inquired1@test.com'],
    [
        'name' => 'Customer With Inquiries',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now()
    ]
);

$customer2 = User::firstOrCreate(
    ['email' => 'inquired2@test.com'],
    [
        'name' => 'Another Inquired Customer',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now()
    ]
);

// Create a customer WITHOUT inquiries
$customer3 = User::firstOrCreate(
    ['email' => 'noinquiry@test.com'],
    [
        'name' => 'Customer Without Inquiries',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now()
    ]
);

// Create inquiries for customer1 (multiple inquiries)
ProductQuery::create([
    'customer_id' => $customer1->id,
    'seller_id' => $seller->id,
    'product_id' => $product->id,
    'category_id' => $category->id,
    'question' => 'First inquiry from customer 1',
    'status' => 'new'
]);

ProductQuery::create([
    'customer_id' => $customer1->id,
    'seller_id' => $seller->id,
    'product_id' => $product->id,
    'category_id' => $category->id,
    'question' => 'Second inquiry from customer 1',
    'status' => 'responded'
]);

// Create inquiry for customer2
ProductQuery::create([
    'customer_id' => $customer2->id,
    'seller_id' => $seller->id,
    'product_id' => $product->id,
    'category_id' => $category->id,
    'question' => 'Inquiry from customer 2',
    'status' => 'new'
]);

echo "Test data created!\n";
echo "Customer 1 has 2 inquiries\n";
echo "Customer 2 has 1 inquiry\n";
echo "Customer 3 has 0 inquiries (should not appear)\n";
```

**Option B: Using SQL**
```sql
-- First, ensure you have a seller, shop, category, and product
-- Then create inquiries

-- Get customer IDs
SET @customer1_id = (SELECT id FROM users WHERE email = 'inquired1@test.com' LIMIT 1);
SET @customer2_id = (SELECT id FROM users WHERE email = 'inquired2@test.com' LIMIT 1);
SET @seller_id = (SELECT id FROM users WHERE email = 'seller@test.com' LIMIT 1);
SET @product_id = (SELECT id FROM products LIMIT 1);
SET @category_id = (SELECT id FROM categories LIMIT 1);

-- Create inquiries
INSERT INTO product_queries (customer_id, seller_id, product_id, category_id, question, status, created_at, updated_at)
VALUES 
(@customer1_id, @seller_id, @product_id, @category_id, 'First inquiry', 'new', NOW(), NOW()),
(@customer1_id, @seller_id, @product_id, @category_id, 'Second inquiry', 'responded', NOW(), NOW()),
(@customer2_id, @seller_id, @product_id, @category_id, 'Inquiry from customer 2', 'new', NOW(), NOW());
```

### Step 3: Test Functionality

#### Test 3.1: Basic Display
- ✅ Verify that only customers who have submitted inquiries are shown
- ✅ Verify that customers without inquiries are NOT shown
- ✅ Check that the "Total Inquiries" column shows the correct count for each customer

#### Test 3.2: Inquiry Count
- ✅ Verify that customers with multiple inquiries show the correct count
- ✅ Check that the count matches the actual number of inquiries in the database

#### Test 3.3: Search Functionality
1. **Search by name:**
   - Type a customer name in the search box
   - ✅ Only matching customers should appear
2. **Search by email:**
   - Type a customer email in the search box
   - ✅ Only matching customers should appear

#### Test 3.4: Verification Status Filter
1. **Select "Verified"** from the verification status dropdown
   - ✅ Only verified customers should appear
2. **Select "Unverified"** from the verification status dropdown
   - ✅ Only unverified customers should appear

#### Test 3.5: Combined Filters
- **Combine verification status + search:**
  - Select "Verified" and search for an email
  - ✅ Results should match both criteria

#### Test 3.6: Pagination
- If you have more than 15 customers, verify pagination works
- ✅ Click through pages to ensure all customers are accessible

## Verification Checklist

### New Customers Feature
- [ ] Can access the page from sidebar menu
- [ ] Default shows last 30 days
- [ ] Time period filter works (7/30/60/90 days)
- [ ] Search by name works
- [ ] Search by email works
- [ ] Verification status filter works
- [ ] Combined filters work
- [ ] Pagination works
- [ ] Customer actions work (login as, ban/unban, delete)
- [ ] Registration dates are displayed correctly

### Inquired Customers Feature
- [ ] Can access the page from sidebar menu
- [ ] Only shows customers with inquiries
- [ ] Inquiry count is displayed correctly
- [ ] Search by name works
- [ ] Search by email works
- [ ] Verification status filter works
- [ ] Combined filters work
- [ ] Pagination works
- [ ] Customer actions work (login as, ban/unban, delete)

## Database Queries for Verification

### Check New Customers
```sql
-- Count customers registered in last 30 days
SELECT COUNT(*) as new_customers_count
FROM users 
WHERE user_type = 'customer' 
AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY);

-- List new customers
SELECT id, name, email, created_at 
FROM users 
WHERE user_type = 'customer' 
AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
ORDER BY created_at DESC;
```

### Check Inquired Customers
```sql
-- Count customers with inquiries
SELECT COUNT(DISTINCT customer_id) as inquired_customers_count
FROM product_queries;

-- List customers with inquiry counts
SELECT u.id, u.name, u.email, COUNT(pq.id) as inquiry_count
FROM users u
INNER JOIN product_queries pq ON u.id = pq.customer_id
WHERE u.user_type = 'customer'
GROUP BY u.id, u.name, u.email
ORDER BY inquiry_count DESC;
```

## Common Issues & Solutions

### Issue: No customers showing in "New Customers"
**Solution:** 
- Check if you have customers registered in the selected time period
- Try changing the time period filter (e.g., to 90 days)
- Verify customers have `user_type = 'customer'` in database

### Issue: No customers showing in "Inquired Customers"
**Solution:**
- Verify that customers have submitted inquiries (check `product_queries` table)
- Ensure inquiries have valid `customer_id` values
- Create test inquiries using the test data scripts above

### Issue: Menu items not showing
**Solution:**
- Verify you're logged in as admin
- Check you have `view_all_customers` permission
- Clear cache: `php artisan optimize:clear`

### Issue: Routes returning 404
**Solution:**
- Clear route cache: `php artisan route:clear`
- Verify routes exist: `php artisan route:list | grep customers`
- Check you're accessing `/admin/customers/new` or `/admin/customers/inquired`

## Quick Test Script

Run this to quickly set up test data:

```bash
php artisan tinker
```

Then paste:
```php
// Quick test data setup
use App\Models\User;
use App\Models\ProductQuery;
use Carbon\Carbon;

// Create new customer (registered today)
$new = User::firstOrCreate(
    ['email' => 'new@test.com'],
    [
        'name' => 'New Customer',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now(),
        'created_at' => now()
    ]
);

// Create customer with inquiry
$inquired = User::firstOrCreate(
    ['email' => 'inquired@test.com'],
    [
        'name' => 'Inquired Customer',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now()
    ]
);

// Get first product query or create one
$pq = ProductQuery::first();
if ($pq) {
    ProductQuery::create([
        'customer_id' => $inquired->id,
        'seller_id' => $pq->seller_id,
        'product_id' => $pq->product_id,
        'category_id' => $pq->category_id,
        'question' => 'Test inquiry',
        'status' => 'new'
    ]);
}

echo "Test data ready!\n";
echo "New customer: new@test.com\n";
echo "Inquired customer: inquired@test.com\n";
```
