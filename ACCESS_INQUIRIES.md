# How to Access the Inquiry Management System

## The 404 Error

The route `seller/product-queries` is properly registered, but you're getting a 404 because:

1. **You're not logged in as a seller**, OR
2. **Your seller account is not approved**, OR  
3. **You're accessing the wrong URL**

## Solution: Login as Seller

### Step 1: Create a Seller Account (if you don't have one)

**Option A: Using the test script**
```bash
php test_inquiries.php
```
This creates a seller with email: `seller@test.com` and password: `password`

**Option B: Manual creation via Tinker**
```bash
php artisan tinker
```

```php
$seller = App\Models\User::create([
    'name' => 'Test Seller',
    'email' => 'seller@test.com',
    'password' => bcrypt('password'),
    'user_type' => 'seller',
    'email_verified_at' => now()
]);

$shop = App\Models\Shop::create([
    'user_id' => $seller->id,
    'name' => 'Test Shop',
    'slug' => 'test-shop-' . time(),
    'verification_status' => 1,
    'registration_approval' => 1  // IMPORTANT: Must be 1 for seller to access panel
]);

echo "Seller created! Login with: seller@test.com / password\n";
```

### Step 2: Login as Seller

1. **Go to seller login page:**
   ```
   http://ta-web.test/seller/login
   ```
   OR
   ```
   http://localhost:8000/seller/login
   ```

2. **Login with seller credentials:**
   - Email: `seller@test.com`
   - Password: `password`

3. **After login, you'll be redirected to seller dashboard**

### Step 3: Access Product Queries

**Option A: Via Menu**
- Look for "Product Queries" or "Inquiries" in the seller sidebar menu
- Click on it

**Option B: Direct URL**
- After logging in as seller, go to:
  ```
  http://ta-web.test/seller/product-queries
  ```
  OR
  ```
  http://localhost:8000/seller/product-queries
  ```

## Important Notes

1. **Seller Account Must Be Approved:**
   - `registration_approval` must be `1` in the `shops` table
   - If it's `0`, you'll be logged out immediately after login

2. **Seller Must Not Be Banned:**
   - `banned` must be `0` in the `users` table

3. **Check Your Current User Type:**
   ```sql
   SELECT id, name, email, user_type, banned FROM users WHERE email = 'your-email@test.com';
   ```

4. **Check Shop Status:**
   ```sql
   SELECT id, user_id, name, registration_approval, verification_status 
   FROM shops 
   WHERE user_id = (SELECT id FROM users WHERE email = 'seller@test.com');
   ```

## Quick Fix: Approve Existing Seller

If you already have a seller account but it's not approved:

```sql
-- Approve seller shop
UPDATE shops 
SET registration_approval = 1, verification_status = 1 
WHERE user_id = (SELECT id FROM users WHERE email = 'your-seller-email@example.com');
```

Or via Tinker:
```bash
php artisan tinker
```

```php
$seller = App\Models\User::where('email', 'your-seller-email@example.com')->first();
if ($seller && $seller->shop) {
    $seller->shop->registration_approval = 1;
    $seller->shop->verification_status = 1;
    $seller->shop->save();
    echo "Seller approved!\n";
}
```

## Troubleshooting

### Still Getting 404?

1. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   php artisan route:clear
   php artisan cache:clear
   ```

2. **Verify you're logged in:**
   - Check the top right corner - you should see your seller name/avatar
   - If not, you're not logged in

3. **Check middleware:**
   - The route requires: `seller`, `verified`, `user`, `prevent-back-history` middleware
   - Make sure your seller account is verified (`email_verified_at` is not null)

4. **Check route exists:**
   ```bash
   php artisan route:list | grep product-queries
   ```
   You should see: `seller/product-queries`

### Getting "Account Under Review" Message?

Your shop's `registration_approval` is `0`. Update it:
```sql
UPDATE shops SET registration_approval = 1 WHERE user_id = YOUR_SELLER_ID;
```

## Test the Complete Flow

1. ✅ Create seller account
2. ✅ Login as seller
3. ✅ Access `/seller/product-queries`
4. ✅ View all inquiries
5. ✅ Test filtering
6. ✅ Test status updates
7. ✅ Test replies
