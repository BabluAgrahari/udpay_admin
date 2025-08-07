# Auth Controller

APIs for managing user authentication, registration, and profile management.

## Send OTP

Sends a 6-digit OTP to the provided mobile number for verification.

<aside class="notice">
This endpoint does not require authentication.
</aside>

### Endpoint

<aside class="endpoint">
<code>POST</code> <code>/api/send-otp</code>
</aside>

### Body Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| mobile_no | string | Yes | The 10-digit mobile number |

### Example Request

```bash
curl -X POST "{{ config('app.url') }}/api/send-otp" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "mobile_no": "9876543210"
     }'
```

### Example Response

<aside class="success">
<strong>200</strong> - OTP sent successfully
</aside>

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

<aside class="error">
<strong>422</strong> - Validation error
</aside>

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

## Verify OTP

Verifies the OTP and registers/logs in the user. If the user doesn't exist, a new account is created.

<aside class="notice">
This endpoint does not require authentication.
</aside>

### Endpoint

<aside class="endpoint">
<code>POST</code> <code>/api/verify-otp</code>
</aside>

### Body Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| otp | numeric | Yes | The 6-digit OTP received via SMS |
| mobile_no | string | Yes | The 10-digit mobile number |
| ref_id | string | No | Referral ID for new user registration |

### Example Request

```bash
curl -X POST "{{ config('app.url') }}/api/verify-otp" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "otp": "123456",
       "mobile_no": "9876543210",
       "ref_id": "UNI123"
     }'
```

### Example Response

<aside class="success">
<strong>200</strong> - OTP verified successfully
</aside>

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

<aside class="error">
<strong>422</strong> - OTP mismatch
</aside>

```json
{
  "status": false,
  "message": "OTP Mismatch, OTP not Verified."
}
```

<aside class="error">
<strong>422</strong> - OTP expired
</aside>

```json
{
  "status": false,
  "message": "OTP is Expired."
}
```

---

## Get Profile

Retrieves the current user's profile information.

<aside class="notice">
This endpoint requires authentication.
</aside>

### Endpoint

<aside class="endpoint">
<code>GET</code> <code>/api/profile</code>
</aside>

### Headers

| Header | Value |
|--------|-------|
| Authorization | Bearer {YOUR_JWT_TOKEN} |

### Example Request

```bash
curl -X GET "{{ config('app.url') }}/api/profile" \
     -H "Authorization: Bearer {YOUR_JWT_TOKEN}" \
     -H "Accept: application/json"
```

### Example Response

<aside class="success">
<strong>200</strong> - Profile retrieved successfully
</aside>

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

## Update Profile

Updates the current user's profile information.

<aside class="notice">
This endpoint requires authentication.
</aside>

### Endpoint

<aside class="endpoint">
<code>POST</code> <code>/api/update-profile</code>
</aside>

### Headers

| Header | Value |
|--------|-------|
| Authorization | Bearer {YOUR_JWT_TOKEN} |

### Body Parameters

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

### Example Request

```bash
curl -X POST "{{ config('app.url') }}/api/update-profile" \
     -H "Authorization: Bearer {YOUR_JWT_TOKEN}" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
       "full_name": "John Doe",
       "gender": "male",
       "dob": "1990-01-01",
       "email": "john@example.com",
       "city": "Mumbai",
       "state": "Maharashtra",
       "pincode": "400001",
       "address": "123 Main Street",
       "landmark": "Near Station"
     }'
```

### Example Response

<aside class="success">
<strong>200</strong> - Profile updated successfully
</aside>

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

<aside class="error">
<strong>422</strong> - Email already exists
</aside>

```json
{
  "status": false,
  "message": "Email already exists."
}
```

---

## Current User Details

Retrieves comprehensive user details including wallet, KYC, and royalty information.

<aside class="notice">
This endpoint requires authentication.
</aside>

### Endpoint

<aside class="endpoint">
<code>GET</code> <code>/api/current-user-details</code>
</aside>

### Headers

| Header | Value |
|--------|-------|
| Authorization | Bearer {YOUR_JWT_TOKEN} |

### Example Request

```bash
curl -X GET "{{ config('app.url') }}/api/current-user-details" \
     -H "Authorization: Bearer {YOUR_JWT_TOKEN}" \
     -H "Accept: application/json"
```

### Example Response

<aside class="success">
<strong>200</strong> - User details retrieved successfully
</aside>

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

<aside class="error">
<strong>400</strong> - Bad Request
</aside>

```json
{
  "status": false,
  "message": "Invalid request parameters"
}
```

<aside class="error">
<strong>401</strong> - Unauthorized
</aside>

```json
{
  "status": false,
  "message": "Unauthorized access"
}
```

<aside class="error">
<strong>422</strong> - Validation Error
</aside>

```json
{
  "status": false,
  "message": "Validation failed",
  "validation": {
    "field_name": ["Error message"]
  }
}
```

<aside class="error">
<strong>500</strong> - Internal Server Error
</aside>

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