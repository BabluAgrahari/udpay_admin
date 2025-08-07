# Technical Context - UniPay E-commerce Platform

## Technology Stack

### Backend Framework
- **Laravel**: 10.x (PHP 8.1+)
- **PHP Version**: 8.1 or higher
- **Composer**: Dependency management
- **Artisan**: Command-line interface

### Database
- **Primary**: MySQL 8.0+
- **Document Store**: MongoDB (for flexible data)
- **ORM**: Eloquent (Laravel's ORM)
- **Migrations**: Database version control
- **Seeders**: Test data generation

### Frontend
- **Template Engine**: Blade (Laravel)
- **CSS Framework**: Bootstrap 5
- **JavaScript**: Vanilla JS + jQuery
- **Icons**: Boxicons, FontAwesome
- **Charts**: ApexCharts
- **UI Components**: Custom components

### Authentication & Security
- **Web Auth**: Laravel's built-in session authentication
- **API Auth**: JWT (tymon/jwt-auth)
- **CSRF Protection**: Laravel's built-in CSRF tokens
- **Password Hashing**: bcrypt
- **Role-based Access**: Laravel Gates and Policies

### File Storage
- **Local Storage**: Public directory structure
- **File Organization**: Entity-based folders (products/, users/, etc.)
- **Image Processing**: PHP GD library
- **File Upload**: Laravel's file handling

### External Services

#### Payment Gateway
- **Provider**: CashFree
- **Integration**: REST API
- **Features**: 
  - Order creation
  - Payment processing
  - Refund handling
  - Webhook notifications

#### SMS Service
- **Provider**: Custom SMS API
- **Features**:
  - OTP delivery
  - Order notifications
  - Registration confirmations
  - Password reset

#### Courier Service
- **Provider**: iCarry
- **Features**:
  - Shipping cost calculation
  - Order tracking
  - Delivery status updates

### Development Tools
- **IDE**: VS Code, PHPStorm
- **Version Control**: Git
- **Package Manager**: Composer
- **Task Runner**: Laravel Mix (Vite)
- **Testing**: PHPUnit
- **Debugging**: Laravel Telescope (optional)

## Development Setup

### Prerequisites
```bash
# System Requirements
- PHP >= 8.1
- MySQL >= 8.0
- Composer
- Node.js (for asset compilation)
- Git
```

### Installation Steps
```bash
# 1. Clone repository
git clone <repository-url>
cd unipay

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database setup
php artisan migrate
php artisan db:seed

# 6. Storage setup
php artisan storage:link

# 7. Asset compilation
npm run dev
```

### Environment Configuration
```env
# Application
APP_NAME=UniPay
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unipay
DB_USERNAME=root
DB_PASSWORD=

# Payment Gateway
CASHFREE_ENV=sandbox
CASHFREE_CLIENT_ID=your_client_id
CASHFREE_CLIENT_SECRET=your_client_secret

# SMS Service
SMS_API_KEY=your_api_key
SMS_API_SECRET=your_api_secret

# Courier Service
ICARRY_USERNAME=your_username
ICARRY_KEY=your_api_key
```

## Technical Constraints

### Performance Constraints
- **Page Load Time**: < 3 seconds
- **Database Queries**: Optimized to prevent N+1 problems
- **Image Sizes**: Compressed and optimized
- **Memory Usage**: Efficient resource utilization

### Security Constraints
- **File Upload**: Restricted file types and sizes
- **SQL Injection**: Prevented through Eloquent ORM
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: All forms protected
- **Authentication**: Secure session management

### Scalability Constraints
- **Database**: Proper indexing for large datasets
- **File Storage**: Organized structure for easy backup
- **Caching**: Strategic caching for performance
- **Code Structure**: Modular design for maintainability

### Browser Compatibility
- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **Mobile Responsive**: Bootstrap 5 responsive design
- **JavaScript**: Progressive enhancement approach

## Dependencies

### PHP Dependencies (composer.json)
```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.10",
        "laravel/sanctum": "^3.3",
        "tymon/jwt-auth": "^2.1",
        "barryvdh/laravel-dompdf": "^3.0",
        "cashfree/cashfree-pg": "^5.0",
        "maatwebsite/excel": "^3.1",
        "rap2hpoutre/laravel-log-viewer": "^2.4"
    }
}
```

### JavaScript Dependencies (package.json)
```json
{
    "devDependencies": {
        "laravel-vite-plugin": "^0.7.0",
        "vite": "^4.0.0"
    }
}
```

### Key Package Purposes

#### Laravel Sanctum
- **Purpose**: API authentication
- **Usage**: Stateless API authentication
- **Configuration**: Token-based authentication

#### JWT Auth
- **Purpose**: JSON Web Token authentication
- **Usage**: Mobile app authentication
- **Configuration**: Token generation and validation

#### DomPDF
- **Purpose**: PDF generation
- **Usage**: Invoice and report generation
- **Configuration**: PDF templates and styling

#### CashFree PG
- **Purpose**: Payment gateway integration
- **Usage**: Payment processing
- **Configuration**: API credentials and webhooks

#### Excel Package
- **Purpose**: Excel file handling
- **Usage**: Import/export functionality
- **Configuration**: File format support

#### Log Viewer
- **Purpose**: Application log viewing
- **Usage**: Debugging and monitoring
- **Configuration**: Log file access

## File Structure

### Application Structure
```
app/
├── Console/           # Artisan commands
├── Exceptions/        # Exception handlers
├── Helper/           # Helper functions
├── Http/
│   ├── Controllers/  # Application controllers
│   ├── Middleware/   # Custom middleware
│   ├── Requests/     # Form request validation
│   └── Validation/   # Custom validation rules
├── Jobs/             # Queue jobs
├── Models/           # Eloquent models
├── Observers/        # Model observers
├── Providers/        # Service providers
└── Services/         # Business logic services
```

### Public Assets Structure
```
public/
├── assets/           # Admin panel assets
├── front_assets/     # Website frontend assets
├── products/         # Product images
├── user/            # User profile images
├── brands/          # Brand logos
├── category/        # Category images
├── kyc/             # KYC documents
└── product_reels/   # Product videos
```

### Views Structure
```
resources/views/
├── CRM/             # Admin panel views
├── Website/         # Customer-facing views
├── components/      # Reusable components
├── vendor/          # Third-party package views
└── layouts/         # Base layouts
```

## Database Schema

### Core Tables
- `uni_users` - User accounts
- `uni_products` - Product catalog
- `uni_categories` - Product categories
- `uni_brands` - Product brands
- `uni_orders` - Customer orders
- `uni_order_items` - Order line items
- `uni_cart` - Shopping cart
- `uni_wishlist` - User wishlists
- `uni_wallets` - User wallets
- `uni_wallet_history` - Wallet transactions

### Supporting Tables
- `uni_product_images` - Product gallery
- `uni_product_variants` - Product variants
- `uni_product_details` - Product specifications
- `uni_product_reels` - Product videos
- `uni_kyc` - KYC documents
- `uni_coupons` - Discount coupons
- `uni_stock` - Inventory management
- `uni_pickup_addresses` - Shipping addresses

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `POST /api/auth/refresh` - Token refresh

### Products
- `GET /api/products` - List products
- `GET /api/products/{id}` - Get product details
- `GET /api/categories` - List categories
- `GET /api/brands` - List brands

### Cart & Orders
- `POST /api/cart/add` - Add to cart
- `GET /api/cart` - Get cart items
- `POST /api/cart/update` - Update cart
- `POST /api/orders` - Create order
- `GET /api/orders` - List orders

### User Management
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update profile
- `POST /api/kyc` - Submit KYC
- `GET /api/wallet` - Get wallet balance

## Configuration Files

### Laravel Configuration
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/auth.php` - Authentication settings
- `config/services.php` - External service credentials
- `config/filesystems.php` - File storage settings

### Custom Configuration
- `config/global.php` - Global application settings
- `.env` - Environment-specific variables

## Deployment Considerations

### Server Requirements
- **Web Server**: Apache/Nginx
- **PHP**: 8.1+ with required extensions
- **MySQL**: 8.0+ with proper configuration
- **SSL Certificate**: HTTPS required for production
- **File Permissions**: Proper directory permissions

### Production Optimizations
- **Caching**: Route, config, and view caching
- **Asset Optimization**: Minified CSS/JS
- **Image Optimization**: Compressed images
- **Database Optimization**: Proper indexing
- **Error Handling**: Production error pages

### Monitoring
- **Error Logging**: Laravel log files
- **Performance Monitoring**: Response time tracking
- **Database Monitoring**: Query performance
- **Security Monitoring**: Failed login attempts

## Development Workflow

### Code Standards
- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Framework-specific patterns
- **Git Flow**: Feature branch workflow
- **Code Review**: Pull request reviews

### Testing Strategy
- **Unit Tests**: PHPUnit for business logic
- **Feature Tests**: End-to-end testing
- **Browser Tests**: User interface testing
- **API Tests**: REST API testing

### Documentation
- **Code Comments**: Inline documentation
- **API Documentation**: Endpoint documentation
- **User Manuals**: Feature documentation
- **Deployment Guides**: Setup instructions 