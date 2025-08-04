# UniPay System Patterns

## Architecture Overview

### **Layered Architecture**
```
┌─────────────────────────────────────┐
│           Presentation Layer        │
│  ┌─────────────┐ ┌──────────────┐   │
│  │   Web UI    │ │  Mobile API  │   │
│  └─────────────┘ └──────────────┘   │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│           Application Layer         │
│  ┌─────────────┐ ┌──────────────┐   │
│  │ Controllers │ │   Services   │   │
│  └─────────────┘ └──────────────┘   │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│            Domain Layer             │
│  ┌─────────────┐ ┌──────────────┐   │
│  │   Models    │ │   Traits     │   │
│  └─────────────┘ └──────────────┘   │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│         Infrastructure Layer        │
│  ┌─────────────┐ ┌──────────────┐   │
│  │  Database   │ │   Storage    │   │
│  └─────────────┘ └──────────────┘   │
└─────────────────────────────────────┘
```

## Design Patterns

### **1. MVC Pattern**
- **Models**: Eloquent models with relationships (`User`, `Wallet`, `Product`, etc.)
- **Views**: Blade templates for web interface
- **Controllers**: API and web controllers with clear separation

### **2. Repository Pattern**
- **BaseModel**: Abstract base class with common functionality
- **Model Relationships**: Well-defined Eloquent relationships
- **Query Scopes**: Reusable query methods in models

### **3. Service Layer Pattern**
- **SmsService**: SMS integration abstraction
- **Courier Services**: Shipping service integration
- **Business Logic**: Complex operations in service classes

### **4. Trait Pattern**
- **Response Trait**: Standardized API response formatting
- **WebResponse Trait**: Web-specific response handling
- **Reusable Code**: Common functionality across controllers

### **5. Middleware Pattern**
- **Authentication**: JWT-based authentication
- **Authorization**: Permission-based access control
- **Security**: IP validation, CSRF protection

## Component Relationships

### **User Management System**
```
User Model
├── Wallet (1:1)
├── UserKyc (1:1)
├── Royalty (1:1)
├── Orders (1:many)
└── Referrals (1:many)
```

### **E-commerce System**
```
Product Model
├── ProductVariant (1:many)
├── ProductImage (1:many)
├── Category (belongs to)
├── Brand (belongs to)
└── Stock (1:1)
```

### **Financial System**
```
Wallet Model
├── WalletHistory (1:many)
├── WalletTransition (1:many)
└── User (belongs to)
```

## Technical Decisions

### **Database Design**
- **Primary**: MySQL for relational data
- **Migrations**: Laravel migration system
- **Soft Deletes**: Implemented in BaseModel
- **Note**: MongoDB configuration exists but is unused - project uses MySQL exclusively

### **Authentication Strategy**
- **JWT Tokens**: Primary authentication method
- **Session-based**: Web interface authentication
- **Multi-factor**: OTP verification for sensitive operations

### **File Management**
- **Local Storage**: File uploads to public directory
- **Organized Structure**: Separate folders for different file types
- **Helper Functions**: `singleFile()` and `multiFile()` utilities

### **API Design**
- **RESTful**: Standard REST API patterns
- **Response Format**: Consistent JSON response structure
- **Validation**: Form request validation classes
- **Error Handling**: Standardized error responses

### **Queue System**
- **Background Jobs**: Async processing for heavy operations
- **Job Classes**: `RegisterJob`, `RequestUpdateJob`
- **Queue Monitoring**: Laravel Queue Monitor integration

## Security Patterns

### **Input Validation**
- **Form Requests**: Dedicated validation classes
- **Middleware**: Request filtering and sanitization
- **Helper Functions**: Custom validation utilities

### **Authentication & Authorization**
- **JWT Middleware**: Token-based API authentication
- **Permission Middleware**: Role-based access control
- **IP Validation**: Request source verification

### **Data Protection**
- **CSRF Protection**: Web form security
- **Encryption**: Sensitive data encryption
- **Audit Trail**: Transaction logging

## Performance Patterns

### **Database Optimization**
- **Query Scopes**: Reusable query filters
- **Eager Loading**: Relationship optimization
- **Indexing**: Strategic database indexing

### **Caching Strategy**
- **Route Caching**: Optimized routing
- **Config Caching**: Configuration optimization
- **View Caching**: Template compilation

### **Asset Management**
- **Vite Integration**: Modern asset bundling
- **CDN Ready**: Asset optimization for delivery
- **Image Optimization**: Organized image storage

## Code Organization Patterns

### **Directory Structure**
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/          # Mobile API controllers
│   │   └── Website/      # Web interface controllers
│   ├── Middleware/       # Request filtering
│   ├── Requests/         # Form validation
│   └── Validation/       # Custom validation
├── Models/               # Eloquent models
├── Services/             # Business logic services
├── Helper/               # Utility functions
├── Traits/               # Reusable traits
├── Jobs/                 # Background jobs
├── Observers/            # Model event observers
└── Casts/                # Custom attribute casting
```

### **Naming Conventions**
- **Controllers**: `{Feature}Controller.php`
- **Models**: Singular, PascalCase
- **Services**: `{Service}Service.php`
- **Traits**: `{Feature}.php`
- **Helpers**: Descriptive function names

### **Response Patterns**
- **Success Response**: `{status: true, data: {...}}`
- **Error Response**: `{status: false, message: "..."}`
- **Validation Response**: `{status: false, validation: [...]}`
- **Pagination**: `{status: true, count: N, records: [...]}`

## Integration Patterns

### **Third-Party Services**
- **Payment Gateway**: Razorpay integration
- **SMS Service**: Custom SMS service abstraction
- **Courier Service**: iCarry integration
- **QR Code**: Simple QR code generation

### **External APIs**
- **IFSC Lookup**: Bank code validation
- **Pincode Service**: Address validation
- **Payment Webhooks**: Transaction callbacks

## Monitoring & Logging

### **Application Monitoring**
- **Queue Monitor**: Background job tracking
- **Log Viewer**: Laravel log viewing interface
- **Error Tracking**: Exception handling and logging

### **Performance Monitoring**
- **Database Queries**: Query optimization tracking
- **Response Times**: API performance monitoring
- **User Activity**: Usage analytics tracking 