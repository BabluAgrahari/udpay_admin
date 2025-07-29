# Login Functionality Task

## Overview
Implement mobile-based OTP login functionality for the website frontend.

## Requirements

### Backend Requirements
1. **Create AuthController for Website**
   - `sendOtp()` function
   - `verifyOtp()` function
   - Store OTP and expiry time in session
   - Handle first-time users (don't store in table initially)
   - Handle returning users (validate and login)

2. **Routes**
   - Add routes for send OTP and verify OTP
   - Add to website.php routes file

### Frontend Requirements
1. **Create Login Layout File**
   - Create `resources/views/Website/Layout/login.blade.php`
   - Include in `app.blade.php`
   - Follow customer modal design pattern

2. **JavaScript/AJAX Implementation**
   - jQuery and AJAX for OTP functionality
   - Handle mobile number validation
   - Handle OTP input and verification
   - Show/hide appropriate modals
   - Handle success/error responses

## Technical Specifications

### OTP Functionality
- OTP expiry: 5 minutes
- OTP length: 4 digits
- Store in session, not database
- First-time users: Send OTP without storing user data
- Returning users: Validate existing user and login

### User Model
- Use existing User model with role 'customer'
- Mobile field for authentication
- Handle user creation on first successful login

### Session Management
- Store OTP in session with expiry
- Store mobile number in session
- Clear session data after successful login

## Files to Create/Modify

### New Files
1. `app/Http/Controllers/Website/AuthController.php`
2. `resources/views/Website/Layout/login.blade.php`

### Files to Modify
1. `routes/website.php` - Add auth routes
2. `resources/views/Website/Layout/app.blade.php` - Include login layout

## Implementation Steps
1. ✅ Create AuthController with sendOtp and verifyOtp methods
2. ✅ Add routes to website.php
3. ✅ Create login.blade.php layout file
4. ✅ Update app.blade.php to include login functionality
5. ✅ Implement JavaScript/AJAX functionality
6. ⏳ Test complete flow

## Success Criteria
- Users can enter mobile number and receive OTP
- OTP verification works for both new and existing users
- New users are created in database after successful verification
- Existing users are logged in after successful verification
- Session management works correctly
- UI follows existing design patterns

## Important Notes
- **SMS Integration**: Currently, OTP is returned in the API response for testing. In production, integrate with an SMS service (like Twilio, MSG91, etc.) and remove the OTP from the response.
- **Testing**: Use the OTP returned in the API response to test the functionality.
- **Security**: OTP expires after 5 minutes and is stored in session only.
- **Modal Fix**: Fixed modal opening issue by adding proper event handlers and CSS for popup overlay display.
- **Code Improvements**: 
  - Removed CSS from login.blade.php and included directly in app.blade.php
  - Enhanced AuthController with better validation, security, and error handling
  - Added rate limiting (1 minute cooldown between OTP requests)
  - Added mobile number blocking after 5 failed attempts
  - Added comprehensive logging for security monitoring
  - Improved Indian mobile number validation (starts with 6,7,8,9)
  - Better error messages with HTTP status codes
  - **Code Organization**: Moved all login-related jQuery code to login.blade.php using @push('scripts') to eliminate duplicate code
  - **Duplicate Code Fix**: Removed the duplicate @push('scripts') block that was causing code duplication in login.blade.php 