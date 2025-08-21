# System Patterns - UniPay E-commerce Platform

## System Architecture

### Overall Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   External      │
│   (Blade)       │◄──►│   (Laravel)     │◄──►│   Services      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Public        │    │   Database      │    │   Payment       │
│   Assets        │    │   (MySQL)       │    │   Gateway       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Multi-Tier Architecture
1. **Presentation Layer**: Blade templates with Bootstrap 5
2. **Application Layer**: Laravel controllers and services
3. **Domain Layer**: Models and business logic
4. **Data Layer**: Database and external service integrations

## Key Technical Decisions

### Framework Choice: Laravel 10.x
- **Rationale**: Rapid development, robust ecosystem, built-in security
- **Benefits**: Eloquent ORM, authentication, validation, caching
- **Trade-offs**: Learning curve, performance overhead

### Database Design: MySQL + MongoDB
- **MySQL**: Primary database for transactional data
- **MongoDB**: Document storage for flexible data structures
- **Rationale**: ACID compliance for transactions, flexibility for documents

### Authentication: Multi-Strategy
- **Web**: Session-based authentication with middleware
- **API**: JWT tokens for mobile applications
- **Rationale**: Security for web, stateless for APIs

### File Storage: Local Filesystem
- **Structure**: Organized by entity type (products, users, etc.)
- **Naming**: Timestamp-based unique filenames
- **Rationale**: Simplicity, direct access, cost-effective

## Design Patterns

### MVC Pattern
```
Model (Eloquent) ←→ Controller ←→ View (Blade)
     ↓                ↓              ↓
Database         Business Logic   Presentation
```

### Repository Pattern (Partial)
- **Models**: Direct Eloquent usage for simplicity
- **Services**: Business logic encapsulation
- **Controllers**: Thin controllers with service delegation

### Service Layer Pattern
```php
// Example: CartService
class CartService
{
    public function addToCart($productId, $userId, $quantity)
    {
        // Business logic for cart operations
    }
}
```

### Middleware Pattern
```php
// Authentication middleware
Route::group(['middleware' => 'crm.auth'], function () {
    // Protected routes
});
```

### Observer Pattern
```php
// Model events for automatic actions
class Timestamp extends Observer
{
    public function creating($model)
    {
        // Auto-set timestamps
    }
}
```

## Component Relationships

### Core Modules

#### 1. User Management Module
```
User Model ←→ UserController ←→ AuthMiddleware
     ↓              ↓                ↓
UserWallet    PanelUserController  RolePermission
     ↓              ↓                ↓
KYC Model     UserService         Gate Definitions
```

#### 2. Product Management Module
```
Product Model ←→ ProductController ←→ ProductRequest
     ↓              ↓                    ↓
ProductImage   ProductDetailController  Validation
     ↓              ↓                    ↓
ProductVariant ProductReelController   File Upload
     ↓              ↓                    ↓
Category Model Brand Model            Image Processing
```

#### 3. Order Management Module
```
Order Model ←→ OrderController ←→ CartController
     ↓              ↓                ↓
OrderItem     CheckoutController   CartService
     ↓              ↓                ↓
Payment       ShippingService     InventoryService
     ↓              ↓                ↓
Wallet        CourierService      Stock Management
```

#### 4. Financial Module
```
Wallet Model ←→ WalletController ←→ TransactionService
     ↓              ↓                    ↓
WalletHistory PaymentController        CashFreeService
     ↓              ↓                    ↓
KYC Model     RefundService           SMS Service
```

### Data Flow Patterns

#### Product Creation Flow
```
1. ProductController::store()
   ↓
2. ProductRequest validation
   ↓
3. File upload (singleFile/multiFile helpers)
   ↓
4. Product model creation
   ↓
5. ProductImage creation (if multiple images)
   ↓
6. ProductVariant creation (if variants)
   ↓
7. Database transaction commit
```

#### Order Processing Flow
```
1. CartController::addToCart()
   ↓
2. Stock validation
   ↓
3. Cart model creation/update
   ↓
4. CheckoutController::process()
   ↓
5. Address validation
   ↓
6. Payment processing (CashFree)
   ↓
7. Order creation
   ↓
8. Inventory update
   ↓
9. Email/SMS notifications
```

#### User Authentication Flow
```
1. LoginController::login()
   ↓
2. Credential validation
   ↓
3. User model authentication
   ↓
4. Session creation
   ↓
5. Role-based redirect
   ↓
6. Middleware protection
```

## Database Patterns

### Table Naming Convention
- **Prefix**: `uni_` for all tables
- **Plural**: Table names in plural form
- **Snake_case**: Column names in snake_case
- **Foreign Keys**: `entity_id` format

### Relationship Patterns
```php
// One-to-Many
Product → ProductImage
Product → ProductVariant
User → Order

// Many-to-One
ProductImage → Product
Order → User
Product → Category

// One-to-One
Product → ProductDetail
User → KYC
```

### Indexing Strategy
- **Primary Keys**: Auto-incrementing IDs
- **Foreign Keys**: Indexed for join performance
- **Search Fields**: Full-text indexes on product names
- **Status Fields**: Indexed for filtering
- **Timestamps**: Indexed for sorting and date queries

## Security Patterns

### Authentication & Authorization
```php
// Role-based access control
Gate::define('isAdmin', function (User $user) {
    return $user->role == 'admin';
});

// Permission-based middleware
Route::middleware('permission:product')->group(function () {
    // Product management routes
});
```

### Input Validation
```php
// Form Request validation
class ProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric|min:0',
        ];
    }
}
```

### File Upload Security
```php
// Secure file upload helper
function singleFile($file, $folder)
{
    // File type validation
    // Size limits
    // Secure filename generation
    // Path validation
}
```

### SQL Injection Prevention
- **Eloquent ORM**: Parameterized queries
- **Query Builder**: Automatic escaping
- **Raw Queries**: Manual parameter binding

## Performance Patterns

### Caching Strategy
- **Route Caching**: `php artisan route:cache`
- **Config Caching**: `php artisan config:cache`
- **View Caching**: `php artisan view:cache`
- **Query Caching**: Redis for frequently accessed data

### Database Optimization
- **Eager Loading**: Prevent N+1 queries
- **Pagination**: Limit result sets
- **Indexing**: Strategic database indexes
- **Query Optimization**: Efficient joins and subqueries

### Frontend Optimization
- **Asset Minification**: CSS/JS compression
- **Image Optimization**: WebP format, lazy loading
- **CDN Integration**: Static asset delivery
- **Browser Caching**: Cache headers for static content

## Error Handling Patterns

### Exception Handling
```php
try {
    // Business logic
} catch (Exception $e) {
    DB::rollBack();
    return $this->failMsg($e->getMessage());
}
```

### Validation Errors
```php
if ($validator->fails()) {
    return $this->validationMsg($validator->errors());
}
```

### Logging Strategy
- **Error Logging**: Laravel's built-in logging
- **Activity Logging**: User actions and system events
- **Payment Logging**: Transaction details and responses
- **Debug Logging**: Development and troubleshooting

## Integration Patterns

### External Services
```php
// Payment Gateway Integration
class CashFree
{
    public function createOrder($amount, $currency, $customerData)
    {
        // API integration with error handling
    }
}

// SMS Service Integration
class SmsService
{
    public function sendMessage($type, $number, $otp)
    {
        // SMS API integration
    }
}

// Courier Service Integration
class Icarry
{
    public function calculateShippingCost($data)
    {
        // Shipping API integration
    }
}
```

### API Design Patterns
- **RESTful Routes**: Standard HTTP methods
- **JSON Responses**: Consistent response format
- **Status Codes**: Proper HTTP status codes
- **Rate Limiting**: API usage restrictions

## Scalability Considerations

### Horizontal Scaling
- **Load Balancing**: Multiple server instances
- **Database Sharding**: Partition data across databases
- **CDN Integration**: Global content delivery
- **Microservices**: Break down into smaller services

### Vertical Scaling
- **Server Resources**: CPU, RAM, storage upgrades
- **Database Optimization**: Query tuning, indexing
- **Caching Layers**: Multiple caching strategies
- **Code Optimization**: Performance improvements

## Monitoring & Maintenance

### Health Checks
- **Database Connectivity**: Regular connection tests
- **External Services**: API endpoint monitoring
- **File System**: Storage space and permissions
- **Application Logs**: Error rate monitoring

### Backup Strategy
- **Database Backups**: Daily automated backups
- **File Backups**: User uploads and assets
- **Configuration Backups**: Environment settings
- **Disaster Recovery**: Complete system restoration 

## PDF Generation Patterns

### DOMPDF-Compatible Templates
- Use table-based layouts; avoid flexbox, CSS grid, complex gradients, and box shadows.
- Keep backgrounds minimal; prefer transparent/white headers to avoid rendering artifacts.
- Limit CSS to basic properties (margin, padding, borders, font styles).

### Fonts and Currency Symbols
- Default PDF font set to DejaVu Sans to ensure the Indian rupee symbol renders.
- Use HTML entity for rupee: `&#8377;` instead of the literal ₹ character.

### Images
- Prefer local public assets (via `asset()` paths) to ensure availability during PDF rendering.
- If external images are used, ensure `isRemoteEnabled` is true; however, local images are more reliable.

### Controller Options
- Paper: A4 portrait.
- Options: `isRemoteEnabled = true`, `isHtml5ParserEnabled = true`, `defaultFont = 'DejaVu Sans'`.

### Templates
- Website invoice template: `resources/views/Website/order_invoice.blade.php` (currently static by business request).
- CRM invoice template remains separate under `resources/views/CRM/Order/invoice.blade.php`.