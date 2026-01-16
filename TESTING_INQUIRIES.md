# Testing Guide: Inquiry Management System

## Prerequisites

1. **Start the development server:**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`

2. **Ensure database is set up:**
   ```bash
   php artisan migrate
   ```

## Step 1: Create Test Data

### Option A: Using Tinker (Quick Method)

```bash
php artisan tinker
```

Then run:
```php
// Create a seller user
$seller = App\Models\User::create([
    'name' => 'Test Seller',
    'email' => 'seller@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'seller',
    'email_verified_at' => now()
]);

// Create a shop for the seller
$shop = App\Models\Shop::create([
    'user_id' => $seller->id,
    'name' => 'Test Shop',
    'slug' => 'test-shop',
    'verification_status' => 1,
    'registration_approval' => 1
]);

// Create a customer user
$customer = App\Models\User::create([
    'name' => 'Test Customer',
    'email' => 'customer@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'customer',
    'email_verified_at' => now()
]);

// Get or create a category
$category = App\Models\Category::first();
if (!$category) {
    $category = App\Models\Category::create([
        'name' => 'Test Category',
        'slug' => 'test-category',
        'parent_id' => 0
    ]);
}

// Create a product
$product = App\Models\Product::create([
    'name' => 'Test Product',
    'added_by' => 'seller',
    'user_id' => $seller->id,
    'category_id' => $category->id,
    'digital' => 0,
    'auction_product' => 0,
    'published' => 1
]);

// Create multiple inquiries with different statuses
$statuses = ['new', 'pending', 'responded', 'offer_sent', 'accepted', 'rejected', 'on_hold'];
foreach ($statuses as $index => $status) {
    App\Models\ProductQuery::create([
        'customer_id' => $customer->id,
        'seller_id' => $seller->id,
        'product_id' => $product->id,
        'category_id' => $category->id,
        'question' => "Test inquiry question {$index} with status {$status}",
        'reply' => $status == 'responded' ? 'This is a test reply' : null,
        'status' => $status,
        'expires_at' => now()->addMonth(),
        'created_at' => now()->subDays($index)
    ]);
}

// Create an expired inquiry
App\Models\ProductQuery::create([
    'customer_id' => $customer->id,
    'seller_id' => $seller->id,
    'product_id' => $product->id,
    'category_id' => $category->id,
    'question' => 'This inquiry should be expired',
    'status' => 'new',
    'expires_at' => now()->subMonth()->subDay(),
    'created_at' => now()->subMonths(2)
]);

echo "Test data created successfully!\n";
echo "Seller: seller@test.com / password\n";
echo "Customer: customer@test.com / password\n";
```

### Option B: Using SQL (Direct Database)

```sql
-- Insert test seller
INSERT INTO users (name, email, password, user_type, email_verified_at, created_at, updated_at)
VALUES ('Test Seller', 'seller@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'seller', NOW(), NOW(), NOW());

-- Insert shop
INSERT INTO shops (user_id, name, slug, verification_status, registration_approval, created_at, updated_at)
VALUES (LAST_INSERT_ID(), 'Test Shop', 'test-shop', 1, 1, NOW(), NOW());

-- Insert test customer
INSERT INTO users (name, email, password, user_type, email_verified_at, created_at, updated_at)
VALUES ('Test Customer', 'customer@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW(), NOW(), NOW());
```

## Step 2: Access Seller Panel

1. **Navigate to seller login:**
   ```
   http://localhost:8000/seller/login
   ```

2. **Login with test seller credentials:**
   - Email: `seller@test.com`
   - Password: `password`

3. **Navigate to Product Queries:**
   - Look for "Product Queries" or "Inquiries" in the seller menu
   - Or go directly to: `http://localhost:8000/seller/product-queries`

## Step 3: Test Filtering Functionality

### Test Status Filter:
1. Use the **Status** dropdown
2. Select different statuses (New, Pending, Responded, etc.)
3. Click **Filter**
4. Verify only inquiries with selected status are shown

### Test Category Filter:
1. Use the **Category** dropdown
2. Select a category
3. Click **Filter**
4. Verify only inquiries for products in that category are shown

### Test Product Filter:
1. Use the **Product** dropdown
2. Select a specific product
3. Click **Filter**
4. Verify only inquiries for that product are shown

### Test Combined Filters:
1. Select both Status and Category
2. Click **Filter**
3. Verify results match both criteria

### Test Clear Filter:
1. Click **Clear** button
2. Verify all inquiries are shown again

## Step 4: Test Status Management

1. **View an inquiry:**
   - Click the eye icon on any inquiry
   - You'll see the inquiry detail page

2. **Update Status:**
   - Use the "Change Status" dropdown
   - Select a new status (e.g., "Pending" → "Responded")
   - Click **Update Status**
   - Verify the status badge updates

3. **Test Status Flow:**
   - New → Pending → Responded → Offer Sent → Accepted
   - Try different status transitions

## Step 5: Test Reply Functionality

1. **Reply to an inquiry:**
   - On the inquiry detail page
   - Type a reply in the textarea
   - Click **Send** or **Update**
   - Verify:
     - Reply is saved
     - Status auto-updates to "Responded" (if it was New or Pending)

## Step 6: Test Auto-Expiration

### Manual Test:
```bash
# Run the expiration command manually
php artisan inquiries:expire
```

### Verify:
1. Check inquiries with `expires_at` older than 1 month
2. They should have status changed to "Expired"
3. Check the database:
```sql
SELECT * FROM product_queries WHERE status = 'expired';
```

### Test Scheduled Task:
```bash
# Test the scheduler
php artisan schedule:list

# Run scheduler manually (for testing)
php artisan schedule:run
```

## Step 7: Test Status Badge Colors

Verify that status badges display with correct colors:
- **New**: Blue (badge-info)
- **Pending**: Yellow (badge-warning)
- **Responded**: Primary blue (badge-primary)
- **Offer Sent**: Blue (badge-info)
- **Accepted**: Green (badge-success)
- **Rejected**: Red (badge-danger)
- **Deal Closed**: Green (badge-success)
- **Cancelled**: Red (badge-danger)
- **On Hold**: Yellow (badge-warning)
- **Expired**: Gray (badge-secondary)

## Step 8: Test from Customer Side

1. **Login as customer:**
   - Go to: `http://localhost:8000/login`
   - Email: `customer@test.com`
   - Password: `password`

2. **Create a new inquiry:**
   - Browse products
   - Click on a product
   - Find the inquiry/question section
   - Submit a question
   - Verify it appears in seller panel with status "New"

## Common Issues & Solutions

### Issue: No inquiries showing
**Solution:** Create test data using Step 1

### Issue: Filters not working
**Solution:** 
- Clear cache: `php artisan cache:clear`
- Check browser console for JavaScript errors

### Issue: Status not updating
**Solution:**
- Check route exists: Verify `seller.product_query.update_status` route
- Check permissions: Ensure seller is logged in
- Check database: Verify `status` column exists

### Issue: Auto-expiration not working
**Solution:**
- Run manually: `php artisan inquiries:expire`
- Check scheduler: `php artisan schedule:list`
- Verify cron is set up (for production)

## Quick Test Checklist

- [ ] Can view all inquiries
- [ ] Can filter by status
- [ ] Can filter by category
- [ ] Can filter by product
- [ ] Can combine multiple filters
- [ ] Can clear filters
- [ ] Can view inquiry details
- [ ] Can update inquiry status
- [ ] Can reply to inquiry
- [ ] Status auto-updates when replying
- [ ] Status badges show correct colors
- [ ] Expired inquiries are marked correctly
- [ ] Auto-expiration command works

## Database Queries for Verification

```sql
-- Check all inquiries with their statuses
SELECT id, customer_id, seller_id, product_id, status, created_at, expires_at 
FROM product_queries 
ORDER BY created_at DESC;

-- Count inquiries by status
SELECT status, COUNT(*) as count 
FROM product_queries 
GROUP BY status;

-- Check expired inquiries
SELECT * FROM product_queries 
WHERE expires_at < NOW() 
AND status != 'expired';
```

## Next Steps

1. Set up cron job for auto-expiration (production)
2. Customize status colors if needed
3. Add email notifications for status changes (optional)
4. Add activity logs for status changes (optional)
