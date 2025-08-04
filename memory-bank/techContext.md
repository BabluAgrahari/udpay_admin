# UniPay Technical Context

## Technology Stack

### **Backend Framework**
- **Laravel**: Version 10.x (Latest LTS)
- **PHP**: Version 8.1+ (Required minimum)
- **Composer**: Dependency management

### **Database**
- **Primary**: MySQL (Relational database)
- **Migration System**: Laravel migrations
- **ORM**: Eloquent ORM
- **Note**: MongoDB was initially configured but never used - project uses MySQL exclusively

### **Authentication & Security**
- **JWT**: `tymon/jwt-auth` for API authentication
- **Sanctum**: Laravel Sanctum for SPA authentication
- **CSRF**: Built-in CSRF protection
- **Encryption**: Laravel's encryption services

### **Frontend Technologies**
- **Blade Templates**: Server-side rendering
- **Vite**: Asset bundling and compilation
- **Bootstrap**: CSS framework
- **jQuery**: JavaScript library
- **FontAwesome**: Icon library

### **File Management**
- **Local Storage**: File uploads to public directory
- **Image Processing**: Basic file upload handling
- **QR Code Generation**: `simplesoftwareio/simple-qrcode`

### **External Services**
- **Payment Gateway**: Razorpay integration
- **SMS Service**: Custom SMS service abstraction
- **Courier Service**: iCarry integration
- **PDF Generation**: `barryvdh/laravel-dompdf`
- **Excel Export**: `maatwebsite/excel`

## Development Setup

### **System Requirements**
```bash
# PHP Requirements
PHP >= 8.1
BCMath PHP Extension
Ctype PHP Extension
cURL PHP Extension
DOM PHP Extension
Fileinfo PHP Extension
JSON PHP Extension
Mbstring PHP Extension
OpenSSL PHP Extension
PCRE PHP Extension
PDO PHP Extension
Tokenizer PHP Extension
XML PHP Extension
```

### **Installation Steps**
```bash
# Clone repository
git clone [repository-url]
cd unipay

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Asset compilation
npm run dev
```

### **Environment Configuration**
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

# JWT Configuration
JWT_SECRET=your-jwt-secret
JWT_TTL=60

# File Storage
FILESYSTEM_DISK=local

# External Services
RAZORPAY_KEY=your-razorpay-key
RAZORPAY_SECRET=your-razorpay-secret
SMS_API_KEY=your-sms-api-key
```

## Project Structure

### **Core Directories**
```
unipay/
├── app/                    # Application logic
│   ├── Http/              # HTTP layer
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business services
│   ├── Helper/            # Utility functions
│   ├── Traits/            # Reusable traits
│   ├── Jobs/              # Background jobs
│   └── Observers/         # Model observers
├── config/                # Configuration files
├── database/              # Migrations & seeders
├── public/                # Web root & assets
├── resources/             # Views & assets
├── routes/                # Route definitions
└── storage/               # Application storage
```

### **Key Configuration Files**
- `config/app.php` - Application configuration
- `config/database.php` - Database connections
- `config/jwt.php` - JWT authentication
- `config/auth.php` - Authentication guards
- `config/queue.php` - Queue configuration

## Dependencies

### **Core Laravel Packages**
```json
{
    "laravel/framework": "^10.10",
    "laravel/sanctum": "^3.3",
    "laravel/tinker": "^2.8"
}
```

### **Authentication & Security**
```json
{
    "tymon/jwt-auth": "^2.1"
}
```

### **File Processing**
```json
{
    "barryvdh/laravel-dompdf": "^3.0",
    "maatwebsite/excel": "^3.1",
    "simplesoftwareio/simple-qrcode": "^4.2"
}
```

### **Development & Monitoring**
```json
{
    "rap2hpoutre/laravel-log-viewer": "^2.4",
    "romanzipp/laravel-queue-monitor": "^5.4"
}
```

### **HTTP Client**
```json
{
    "guzzlehttp/guzzle": "^7.2"
}
```

## Technical Constraints

### **Performance Constraints**
- **Database**: MySQL performance limitations
- **File Uploads**: Local storage limitations
- **Memory**: PHP memory limits for large operations
- **Queue Processing**: Background job processing limits

### **Security Constraints**
- **JWT Token Expiry**: 60-minute default TTL
- **File Upload Size**: Server upload limits
- **API Rate Limiting**: No built-in rate limiting
- **IP Validation**: Custom IP validation middleware

### **Scalability Constraints**
- **Single Server**: No load balancing configuration
- **Database**: Single MySQL database instance
- **File Storage**: Local file system only
- **Caching**: No Redis/memcached configuration

## Development Workflow

### **Code Standards**
- **PSR-4**: Autoloading standards
- **Laravel Conventions**: Framework-specific patterns
- **Naming**: Descriptive naming conventions
- **Documentation**: Limited inline documentation

### **Testing Strategy**
- **Unit Tests**: PHPUnit framework available
- **Feature Tests**: Laravel testing utilities
- **Current Status**: No visible test files

### **Deployment Considerations**
- **Environment**: Production-ready configuration
- **Optimization**: Route and config caching
- **Monitoring**: Queue monitoring enabled
- **Logging**: Comprehensive logging system

## API Architecture

### **RESTful Endpoints**
- **Authentication**: JWT-based token system
- **Response Format**: Standardized JSON responses
- **Error Handling**: Consistent error responses
- **Validation**: Form request validation

### **API Versioning**
- **Current**: No versioning strategy
- **Future**: Consider API versioning for scalability

### **Documentation**
- **Current**: No API documentation
- **Recommendation**: Implement OpenAPI/Swagger

## Database Schema

### **Core Tables**
- `users_lvl` - User management
- `wallets` - Financial transactions
- `products` - E-commerce catalog
- `orders` - Order management
- `user_kyc` - KYC verification
- `categories` - Product categorization

### **Relationships**
- **One-to-One**: User-Wallet, User-KYC
- **One-to-Many**: User-Orders, Product-Variants
- **Many-to-Many**: Products-Categories (via pivot)

### **Indexing Strategy**
- **Primary Keys**: Auto-incrementing IDs
- **Foreign Keys**: Referential integrity
- **Performance**: Strategic indexing on frequently queried columns

## Security Implementation

### **Authentication Flow**
1. **Mobile API**: JWT token authentication
2. **Web Interface**: Session-based authentication
3. **Admin Panel**: Role-based access control

### **Data Protection**
- **Encryption**: Sensitive data encryption
- **Validation**: Input sanitization and validation
- **CSRF**: Cross-site request forgery protection
- **XSS**: Cross-site scripting prevention

### **API Security**
- **Token Validation**: JWT token verification
- **IP Validation**: Request source verification
- **Rate Limiting**: No built-in rate limiting
- **CORS**: Cross-origin resource sharing configuration 