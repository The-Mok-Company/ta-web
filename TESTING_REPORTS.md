# Testing Guide: Reports Features

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

3. **Login as Admin:**
   - Navigate to: `http://localhost:8000/users/login` (or your login page)
   - Login with admin credentials (user_type must be 'admin' or 'staff')
   - After login, you'll be redirected to the dashboard

4. **Set up permissions (if using permission system):**
   - Ensure the admin user has the `inquiries_report` permission
   - You may need to add this permission to the database or assign it to the admin role

## Testing: Inquiries Report

### Step 1: Access the Inquiries Report

1. **Navigate to Reports:**
   - Make sure you're logged in as admin and on the dashboard (`http://localhost:8000/admin`)
   - In the admin sidebar, click on **Reports**
   - Click on **Inquiries Report**
   - Or go directly to: `http://localhost:8000/admin/inquiries-report`

### Step 2: Verify Dashboard Cards

Check that the summary cards display correctly:
- **Total Inquiries** - Should show the total count
- **Response Rate** - Percentage of inquiries with replies
- **Conversion Rate** - Percentage of accepted/deal_closed inquiries
- **Active Categories** - Number of categories with inquiries

### Step 3: Test Filters

1. **Filter by Status:**
   - Select a status from the dropdown (e.g., "New", "Responded")
   - Click **Filter**
   - Verify only inquiries with that status are shown

2. **Filter by Category:**
   - Select a category from the dropdown
   - Click **Filter**
   - Verify only inquiries for that category are shown

3. **Filter by Date Range:**
   - Select a date range using the date picker
   - Click **Filter**
   - Verify only inquiries within that date range are shown

4. **Search Filter:**
   - Enter a search term (product name, customer name, question text)
   - Click **Filter**
   - Verify results match the search term

5. **Combined Filters:**
   - Select multiple filters (status + category + date range)
   - Click **Filter**
   - Verify results match all selected criteria

6. **Clear Filters:**
   - Click **Clear** button
   - Verify all filters are reset and all inquiries are shown

### Step 4: Verify Charts

1. **Status Breakdown Chart (Doughnut):**
   - Should display a doughnut chart showing distribution of inquiries by status
   - Verify colors match status types

2. **Monthly Trend Chart (Line):**
   - Should display a line chart showing inquiry trends over the last 12 months
   - Verify the trend line is visible and data points are correct

### Step 5: Verify Analytics Tables

1. **Top Categories Table:**
   - Should list top 10 categories by number of inquiries
   - Verify counts are accurate

2. **Top Products Table:**
   - Should list top 10 products by number of inquiries
   - Verify counts are accurate

### Step 6: Verify Inquiries List

1. **Table Display:**
   - Verify all columns are visible: #, Customer, Product, Category, Question, Status, Date
   - Check that status badges display with correct colors

2. **Pagination:**
   - If there are more than 20 inquiries, verify pagination works
   - Click through pages to verify data loads correctly

3. **Data Accuracy:**
   - Verify customer names and emails are correct
   - Verify product names are correct
   - Verify category names are correct
   - Verify dates are formatted correctly

## Testing: Search Reports

### Step 1: Access Search Reports

1. **Navigate to Search Reports:**
   - Make sure you're logged in as admin and on the dashboard (`http://localhost:8000/admin`)
   - In the admin sidebar, click on **Reports**
   - Click on **Search Reports**
   - Or go directly to: `http://localhost:8000/admin/search-reports`

### Step 2: Test Search Functionality

1. **Search for Inquiries:**
   - Enter a search term that matches an inquiry question or reply
   - Click **Search**
   - Verify inquiries section shows matching results
   - Verify results include customer name, product name, question preview, status, and date

2. **Search for Products:**
   - Enter a product name
   - Click **Search**
   - Verify products section shows matching results
   - Verify results include product name, category, and price

3. **Search for Users:**
   - Enter a customer name or email
   - Click **Search**
   - Verify users section shows matching results
   - Verify results include name, email, and user type

4. **Search with No Results:**
   - Enter a search term that doesn't match anything (e.g., "xyz123abc")
   - Click **Search**
   - Verify "No results found" message is displayed

5. **Empty Search:**
   - Leave search field empty
   - Click **Search**
   - Verify helpful message is displayed

### Step 3: Verify Search Results Display

1. **Inquiries Results:**
   - Check that status badges display correctly
   - Verify dates are formatted properly
   - Check that question text is truncated appropriately

2. **Products Results:**
   - Verify prices are formatted correctly
   - Check category names are displayed

3. **Users Results:**
   - Verify user types are capitalized correctly
   - Check that all user information is displayed

## Testing: Earnings/Finance Report

### Step 1: Access Earnings Report

1. **Navigate to Earnings Report:**
   - Make sure you're logged in as admin and on the dashboard (`http://localhost:8000/admin`)
   - In the admin sidebar, click on **Reports**
   - Click on **Earning Report**
   - Or go directly to: `http://localhost:8000/admin/reports/earning-payout-report`

### Step 2: Verify Dashboard Metrics

Check that all financial metrics display correctly:
- **Total Sales Alltime** - Should show total sales amount
- **Sales this month** - Should show current month sales
- **Total Payouts** - Should show total payout amount
- **Payouts this month** - Should show current month payouts

### Step 3: Test Analytics

1. **Net Sales Analytics:**
   - Test different time intervals (Day, Week, Month)
   - Verify calculations are correct
   - Check that commission and delivery costs are deducted properly

2. **Payout Analytics:**
   - Test different time intervals
   - Verify seller payouts, refunds, and delivery boy payments are included

3. **Sale Analytics:**
   - Verify charts display correctly
   - Check that data points match the selected time period

4. **Category-wise Reports:**
   - Verify top categories are listed
   - Check that sales amounts are accurate

## Creating Test Data

If you need to create test data for testing, you can use Tinker:

```bash
php artisan tinker
```

### Create Test Inquiries:

```php
// Get or create test users
$customer = App\Models\User::firstOrCreate(
    ['email' => 'testcustomer@example.com'],
    [
        'name' => 'Test Customer',
        'password' => bcrypt('password'),
        'user_type' => 'customer',
        'email_verified_at' => now()
    ]
);

$seller = App\Models\User::where('user_type', 'seller')->first();
if (!$seller) {
    $seller = App\Models\User::create([
        'name' => 'Test Seller',
        'email' => 'testseller@example.com',
        'password' => bcrypt('password'),
        'user_type' => 'seller',
        'email_verified_at' => now()
    ]);
}

// Get a product
$product = App\Models\Product::first();
if (!$product) {
    $category = App\Models\Category::firstOrCreate(
        ['name' => 'Test Category'],
        ['slug' => 'test-category', 'parent_id' => 0]
    );
    
    $product = App\Models\Product::create([
        'name' => 'Test Product',
        'added_by' => 'seller',
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'digital' => 0,
        'auction_product' => 0,
        'published' => 1
    ]);
}

// Create inquiries with different statuses
$statuses = ['new', 'pending', 'responded', 'offer_sent', 'accepted', 'rejected', 'on_hold'];
foreach ($statuses as $index => $status) {
    App\Models\ProductQuery::create([
        'customer_id' => $customer->id,
        'seller_id' => $seller->id,
        'product_id' => $product->id,
        'category_id' => $product->category_id,
        'question' => "Test inquiry question {$index} with status {$status}",
        'reply' => $status == 'responded' ? 'This is a test reply' : null,
        'status' => $status,
        'expires_at' => now()->addMonth(),
        'created_at' => now()->subDays($index)
    ]);
}

echo "Test inquiries created successfully!\n";
```

## Common Issues & Solutions

### Issue: Inquiries Report shows "No inquiries found"
**Solution:**
- Create test data using the Tinker commands above
- Check database connection
- Verify `product_queries` table exists

### Issue: Charts not displaying
**Solution:**
- Check browser console for JavaScript errors
- Verify Chart.js library is loading (check network tab)
- Ensure internet connection is available (Chart.js loads from CDN)

### Issue: Filters not working
**Solution:**
- Clear browser cache
- Check that form is submitting correctly (check network tab)
- Verify route exists: `php artisan route:list | grep inquiries`

### Issue: Permission denied errors
**Solution:**
- Add `inquiries_report` permission to the database
- Assign permission to admin role
- Or temporarily remove permission check in `ReportController.php` constructor

### Issue: Search returns no results
**Solution:**
- Verify there is data in the database
- Check that search term matches actual data
- Verify database relationships are set up correctly

## Quick Test Checklist

### Inquiries Report
- [ ] Can access the report page
- [ ] Summary cards display correct numbers
- [ ] Can filter by status
- [ ] Can filter by category
- [ ] Can filter by date range
- [ ] Can search by text
- [ ] Can combine multiple filters
- [ ] Can clear filters
- [ ] Status chart displays correctly
- [ ] Monthly trend chart displays correctly
- [ ] Top categories table shows data
- [ ] Top products table shows data
- [ ] Inquiries list displays correctly
- [ ] Pagination works (if applicable)
- [ ] Status badges show correct colors

### Search Reports
- [ ] Can access the search page
- [ ] Can search for inquiries
- [ ] Can search for products
- [ ] Can search for users
- [ ] No results message displays correctly
- [ ] Search results are accurate
- [ ] All sections display properly

### Earnings Report
- [ ] Can access the earnings report
- [ ] All financial metrics display
- [ ] Charts render correctly
- [ ] Time interval filters work
- [ ] Category reports show data
- [ ] Calculations are accurate

## Database Queries for Verification

```sql
-- Check total inquiries
SELECT COUNT(*) as total FROM product_queries;

-- Check inquiries by status
SELECT status, COUNT(*) as count 
FROM product_queries 
GROUP BY status;

-- Check inquiries with replies (response rate)
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN reply IS NOT NULL THEN 1 ELSE 0 END) as responded,
    (SUM(CASE WHEN reply IS NOT NULL THEN 1 ELSE 0 END) / COUNT(*) * 100) as response_rate
FROM product_queries;

-- Check conversion rate
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status IN ('accepted', 'deal_closed') THEN 1 ELSE 0 END) as converted,
    (SUM(CASE WHEN status IN ('accepted', 'deal_closed') THEN 1 ELSE 0 END) / COUNT(*) * 100) as conversion_rate
FROM product_queries;

-- Check monthly trend
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    COUNT(*) as count
FROM product_queries
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
GROUP BY month
ORDER BY month ASC;

-- Top categories by inquiries
SELECT 
    c.name,
    COUNT(pq.id) as count
FROM product_queries pq
JOIN categories c ON pq.category_id = c.id
GROUP BY c.id, c.name
ORDER BY count DESC
LIMIT 10;
```

## Next Steps

1. Test all features thoroughly
2. Verify permissions are set up correctly
3. Test with real production data (if available)
4. Check performance with large datasets
5. Verify all translations work correctly
6. Test on different browsers and devices
