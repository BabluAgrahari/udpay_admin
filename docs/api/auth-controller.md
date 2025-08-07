# Auth Controller API Documentation

## Overview
The Auth Controller handles user authentication, registration, profile management, and OTP verification for the UniPay application.

## Base URL
```
{{ config('app.url') }}/api
```

## Authentication
Most endpoints require JWT Bearer token authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your_jwt_token}
```

---

## Endpoints

### 1. Send OTP

**POST** `/send-otp`

Sends a 6-digit OTP to the provided mobile number for verification.

#### Request Body
```json
{
    "mobile_no": "9876543210"
}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| mobile_no | string | Yes | 10-digit mobile number |

#### Response
**Success (200)**
```json
{
    "status": true,
    "message": "OTP Sent on 9876543210",
    "record": {
        "expire_time": 1703123456,
        "time": 5,
        "new_user": true,
        "otp": 123456
    }
}
```

**Error (422)**
```json
{
    "status": false,
    "message": "Validation failed",
    "validation": {
        "mobile_no": ["The mobile no field is required."]
    }
}
```

---

### 2. Verify OTP

**POST** `/verify-otp`

Verifies the OTP and registers/logs in the user.

#### Request Body
```json
{
    "otp": "123456",
    "mobile_no": "9876543210",
    "ref_id": "UNI123"
}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| otp | numeric | Yes | 6-digit OTP received via SMS |
| mobile_no | string | Yes | 10-digit mobile number |
| ref_id | string | No | Referral ID (optional) |

#### Response
**Success (200)**
```json
{
    "status": true,
    "message": "Otp Verified, Registered Successfully!",
    "record": {
        "user_num": 12345678,
        "user_id": 123,
        "alpha_user_id": "UNI123",
        "full_name": "",
        "email": "",
        "mobile_no": "9876543210",
        "gender": "",
        "dob": "",
        "city": "",
        "state": "",
        "pincode": "",
        "address": "",
        "landmark": "",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "authorisation": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
            "type": "bearer"
        }
    }
}
```

**Error (422)**
```json
{
    "status": false,
    "message": "OTP Mismatch, OTP not Verified."
}
```

---

### 3. Get Profile

**GET** `/profile`

Retrieves the current user's profile information.

#### Headers
```
Authorization: Bearer {jwt_token}
```

#### Response
**Success (200)**
```json
{
    "status": true,
    "message": "Profile Fetched Successfully!",
    "record": {
        "user_num": 12345678,
        "user_id": 123,
        "alpha_user_id": "UNI123",
        "full_name": "John Doe",
        "email": "john@example.com",
        "mobile_no": "9876543210",
        "gender": "male",
        "dob": "1990-01-01",
        "city": "Mumbai",
        "state": "Maharashtra",
        "pincode": "400001",
        "address": "123 Main Street",
        "landmark": "Near Station",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 4. Update Profile

**POST** `/update-profile`

Updates the current user's profile information.

#### Headers
```
Authorization: Bearer {jwt_token}
```

#### Request Body
```json
{
    "full_name": "John Doe",
    "gender": "male",
    "dob": "1990-01-01",
    "email": "john@example.com",
    "city": "Mumbai",
    "state": "Maharashtra",
    "pincode": "400001",
    "address": "123 Main Street",
    "landmark": "Near Station"
}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| full_name | string | Yes | User's full name |
| gender | string | Yes | Gender (male/female/other) |
| dob | date | Yes | Date of birth (YYYY-MM-DD) |
| email | email | Yes | Valid email address |
| city | string | Yes | City name |
| state | string | Yes | State name |
| pincode | numeric | Yes | 6-digit pincode |
| address | string | Yes | Complete address |
| landmark | string | Yes | Landmark or locality |

#### Response
**Success (200)**
```json
{
    "status": true,
    "message": "Profile Updated Successfully!",
    "record": {
        "user_num": 12345678,
        "user_id": 123,
        "alpha_user_id": "UNI123",
        "full_name": "John Doe",
        "email": "john@example.com",
        "mobile_no": "9876543210",
        "gender": "male",
        "dob": "1990-01-01",
        "city": "Mumbai",
        "state": "Maharashtra",
        "pincode": "400001",
        "address": "123 Main Street",
        "landmark": "Near Station",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

**Error (422)**
```json
{
    "status": false,
    "message": "Email already exists."
}
```

---

### 5. Current User Details

**GET** `/current-user-details`

Retrieves comprehensive user details including wallet, KYC, and royalty information.

#### Headers
```
Authorization: Bearer {jwt_token}
```

#### Response
**Success (200)**
```json
{
    "status": true,
    "message": "Profile Fetched Successfully!",
    "record": {
        "user_num": 12345678,
        "user_id": 123,
        "full_name": "John Doe",
        "user_email": "john@example.com",
        "user_phone": "9876543210",
        "alpha_user_id": "UNI123",
        "epin": 0,
        "is_active": 1,
        "post": "distributor",
        "user_count": 5,
        "wallet_balance": 1500.50,
        "bp": 100,
        "post_icon": "https://example.com/assets/images/post/user.jpg",
        "total_unicash": 500.00,
        "available_unicash": 300.00,
        "qrcode": "https://example.com/qrcode/12345678.svg",
        "post_membership": "Active",
        "kyc_flag": 1,
        "pan_flag": 1,
        "aadhar_flag": 1,
        "self_flag": 1,
        "personal_details": {
            "name": "John Doe",
            "address": "123 Main Street",
            "mobile": "9876543210",
            "gender": "male",
            "dob": "1990-01-01",
            "state": "Maharashtra",
            "district": "Mumbai",
            "locality": "Near Station",
            "pincode": 400001,
            "work": "Software Engineer",
            "nominee": "Jane Doe",
            "relation": "Spouse"
        },
        "bank_details": {
            "name": "John Doe",
            "bank": "HDFC Bank",
            "branch": "Mumbai Main",
            "ifsc_code": "HDFC0001234",
            "ac_number": "1234567890",
            "id_proof": "pan_card.jpg",
            "bank_doc": "passbook.jpg"
        },
        "docs": {
            "pan_number": "ABCDE1234F",
            "aadhar_number": "123456789012",
            "pan": "pan_card.jpg",
            "doc_type": "aadhar",
            "aadhar1": "aadhar_front.jpg",
            "aadhar2": "aadhar_back.jpg",
            "selfie": "selfie.jpg"
        }
    }
}
```

---

## Error Responses

### Common Error Codes

**400 Bad Request**
```json
{
    "status": false,
    "message": "Invalid request parameters"
}
```

**401 Unauthorized**
```json
{
    "status": false,
    "message": "Unauthorized access"
}
```

**422 Validation Error**
```json
{
    "status": false,
    "message": "Validation failed",
    "validation": {
        "field_name": ["Error message"]
    }
}
```

**500 Internal Server Error**
```json
{
    "status": false,
    "message": "Something went wrong"
}
```

---

## Data Models

### User Model
```json
{
    "user_num": "integer",
    "user_id": "integer",
    "alpha_user_id": "string",
    "name": "string",
    "email": "string",
    "mobile": "string",
    "gender": "string",
    "isactive": "boolean",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### Wallet Model
```json
{
    "userid": "integer",
    "unm": "integer",
    "amount": "decimal",
    "earning": "decimal",
    "bp": "decimal",
    "sp": "decimal",
    "unicash": "decimal",
    "sec_val": "decimal"
}
```

### UserKYC Model
```json
{
    "userId": "integer",
    "name": "string",
    "address": "string",
    "mobile": "string",
    "gender": "string",
    "dob": "date",
    "state": "string",
    "district": "string",
    "locality": "string",
    "pincode": "integer",
    "work": "string",
    "nominee": "string",
    "relation": "string",
    "kyc_flag": "boolean",
    "pan_flag": "boolean",
    "aadhar_flag": "boolean",
    "self_flag": "boolean"
}
```

---

## Notes

1. **OTP Expiration**: OTPs expire after 5 minutes by default
2. **JWT Tokens**: Tokens are required for authenticated endpoints
3. **Referral System**: Users can register with referral IDs
4. **KYC Status**: KYC verification is tracked through various flags
5. **Wallet Integration**: User details include comprehensive wallet information
6. **QR Code Generation**: QR codes are automatically generated for user identification

---

## Rate Limiting

- OTP requests are limited to prevent abuse
- API calls are rate-limited based on user authentication status
- Excessive requests may result in temporary blocking

---

## Security Considerations

1. **OTP Security**: OTPs are hashed using MD5 before storage
2. **JWT Tokens**: Use secure token storage and transmission
3. **Input Validation**: All inputs are validated and sanitized
4. **Database Transactions**: Critical operations use database transactions
5. **Error Handling**: Sensitive information is not exposed in error messages 