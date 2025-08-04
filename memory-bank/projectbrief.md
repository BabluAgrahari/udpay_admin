# UniPay Project Brief

## Project Overview
UniPay is a comprehensive financial services and e-commerce platform built with Laravel 10.x, designed to provide multi-platform services including mobile payments, wallet management, recharge services, and e-commerce functionality.

## Core Requirements

### Primary Goals
1. **Multi-Platform Financial Services Platform**
   - Mobile API backend for mobile applications
   - Web interface for distributors and merchants
   - Admin panel for platform management

2. **Financial Services Core**
   - Digital wallet management
   - Mobile/DTH recharge services
   - Money transfer capabilities
   - Payment gateway integration (Razorpay)
   - KYC verification system

3. **E-commerce Integration**
   - Product catalog management
   - Shopping cart functionality
   - Order processing and tracking
   - Inventory management
   - Multi-vendor support

4. **User Management System**
   - Multi-level user hierarchy
   - Referral system with QR codes
   - Affiliate marketing capabilities
   - Merchant onboarding
   - Distributor management

### Technical Requirements
- **Framework**: Laravel 10.x with PHP 8.1+
- **Database**: MySQL (primary), MongoDB (configured but not active)
- **Authentication**: JWT-based authentication
- **File Storage**: Local file system with organized uploads
- **Queue System**: Background job processing
- **API**: RESTful API with standardized responses

### Business Requirements
- **Multi-level Marketing**: User hierarchy with commission tracking
- **Financial Compliance**: KYC verification for users
- **Payment Processing**: Multiple payment methods and gateways
- **Reporting**: Transaction history and analytics
- **Security**: IP validation, CSRF protection, JWT tokens

## Success Criteria
1. Seamless mobile app integration
2. Secure financial transactions
3. Scalable user management
4. Comprehensive e-commerce features
5. Robust admin and distributor interfaces

## Project Scope
- **In Scope**: Core financial services, e-commerce, user management, API development
- **Out of Scope**: Mobile app development (separate project), third-party integrations beyond payment gateways 