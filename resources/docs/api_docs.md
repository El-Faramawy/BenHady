# BenHady API Documentation

Welcome to the **BenHady Car Rental API** documentation. This document provides a complete reference for all available API endpoints, request specifications, headers, authentication flows, and response structures.

---

## Table of Contents

- [Overview & Architecture](#overview--architecture)
    - [Base URL](#base-url)
    - [Standard Response Structure](#standard-response-structure)
    - [Authentication & JWT](#authentication--jwt)
    - [Localization & Bilingual Responses](#localization--bilingual-responses)
    - [Error Handling & Status Codes](#error-handling--status-codes)
- [1. Authentication & Registration](#1-authentication--registration)
    - [1.1 Register Step 1 (Initiate)](#11-register-step-1-initiate)
    - [1.2 Verify Phone OTP](#12-verify-phone-otp)
    - [1.3 Resend Phone OTP](#13-resend-phone-otp)
    - [1.4 Register Step 2 (Complete)](#14-register-step-2-complete)
    - [1.5 Login](#15-login)
    - [1.6 Logout](#16-logout)
    - [1.7 Reset Password](#17-reset-password)
- [2. User Profile](#2-user-profile)
    - [2.1 Get Current User (Me)](#21-get-current-user-me)
    - [2.2 Update Profile](#22-update-profile)
    - [2.3 Delete Account](#23-delete-account)
- [3. Home Screen](#3-home-screen)
    - [3.1 Home Aggregation](#31-home-aggregation)
- [4. Cars Catalog](#4-cars-catalog)
    - [4.1 List & Filter Cars](#41-list--filter-cars)
    - [4.2 Car Details](#42-car-details)
- [5. Lookups & Rental Policies](#5-lookups--rental-policies)
    - [5.1 Categories](#51-categories)
    - [5.2 Brands](#52-brands)
    - [5.3 Cities & Branches](#53-cities--branches)
    - [5.4 Model Years](#54-model-years)
    - [5.5 Transmissions](#55-transmissions)
    - [5.6 Fuel Types](#56-fuel-types)
    - [5.7 Rental Policies](#57-rental-policies)
- [6. Application Settings](#6-application-settings)
    - [6.1 Get Settings](#61-get-settings)
- [7. Frequently Asked Questions (FAQs)](#7-frequently-asked-questions-faqs)
    - [7.1 Get FAQs](#71-get-faqs)
- [8. Contact Us](#8-contact-us)
    - [8.1 Send Message](#81-send-message)
- [9. Notifications & Device Tokens](#9-notifications--device-tokens)
    - [9.1 Get Notifications](#91-get-notifications)
    - [9.2 Get Unread Count](#92-get-unread-count)
    - [9.3 Delete All Notifications](#93-delete-all-notifications)
    - [9.4 Save FCM Phone Token](#94-save-fcm-phone-token)
    - [9.5 Delete FCM Phone Token](#95-delete-fcm-phone-token)
- [10. Bookings](#10-bookings)
    - [10.1 Create Booking](#101-create-booking)
    - [10.2 Active Bookings](#102-active-bookings)
    - [10.3 Closed Bookings (History)](#103-closed-bookings-history)
    - [10.4 Booking Details](#104-booking-details)
- [11. Coupons & Discounts](#11-coupons--discounts)
    - [11.1 Apply Discount Coupon](#111-apply-discount-coupon)

---

## Overview & Architecture

### Base URL

```
Production: https://benhady.hayah.click/
Local / Dev: http://benhady.test/ (or http://localhost:8000/)
```

> **Note on Endpoints:** The application API prefix is root (empty prefix). Endpoints are accessed directly without a leading `/api/` prefix (e.g., `http://benhady.test/home`, `http://benhady.test/auth/login`).

### Standard Response Structure

All API responses return a unified, stateless JSON structure:

```json
{
    "code": 200,
    "data": {},
    "messages": ["رسالة نجاح العملية"],
    "errors": []
}
```

### Authentication & JWT

- Protected endpoints require the **Bearer JWT Token** in the `Authorization` header:
    ```http
    Authorization: Bearer <your_access_token>
    ```
- Tokens are returned upon successful **Login** (`POST /auth/login`) and **Register Step 2** (`POST /auth/register-step-two`).

### Localization & Bilingual Responses

The API supports both **Arabic (`ar`)** (default) and **English (`en`)**. Pass the desired language in the HTTP header:

```http
Accept-Language: ar
# or
Accept-Language: en
```

- Multi-lingual model attributes return explicit fields (`name_ar`, `name_en`, `description_ar`, `description_en`, `question_ar`, `question_en`, `answer_ar`, `answer_en`).
- Models also automatically append **dynamic localized accessors** (`name`, `description`, `question`, `answer`) resolved dynamically according to the requested `Accept-Language`.

### Error Handling & Status Codes

| HTTP Status                | Meaning              | Typical Usage                                                             |
| -------------------------- | -------------------- | ------------------------------------------------------------------------- |
| `200 OK`                   | Success              | Successful GET, PUT, or POST action.                                      |
| `201 Created`              | Resource Created     | Successful user creation, message submission.                             |
| `400 Bad Request`          | Business Logic Error | Invalid OTP code, phone not verified before Step 2, OTP cooldown active.  |
| `401 Unauthorized`         | Unauthenticated      | Missing, expired, or invalid JWT Bearer token; wrong login credentials.   |
| `403 Forbidden`            | Access Denied        | Inactive user account or restricted permission.                           |
| `404 Not Found`            | Resource Not Found   | Car, city, or user not found in database.                                 |
| `422 Unprocessable Entity` | Validation Error     | Form validation failure (`{"code": 422, "message": "حقل الاسم مطلوب."}`). |
| `500 Server Error`         | Server Exception     | Internal unhandled server error.                                          |

---

## 1. Authentication & Registration

BenHady uses a secure **Two-Step Registration** flow:

1. **Step 1 (`POST /auth/register-step-one`)**: Submits basic personal information. The user is created/updated with `phone_verified = false`, and an SMS verification code (OTP) is dispatched.
2. **Verify Phone (`POST /auth/verify-phone`)**: Verifies the received OTP. The code is single-use and wiped immediately upon verification.
3. **Step 2 (`POST /auth/register-step-two`)**: Sets the account password and identity/driving license credentials. If the phone is verified, the account becomes active and the JWT token is issued.

---

### 1.1 Register Step 1 (Initiate)

Creates or updates a user record by phone number, sets `phone_verified` to `false`, and sends an OTP SMS.

- **Method**: `POST`
- **URL**: `auth/register-step-one`
- **Auth Required**: No
- **Content-Type**: `multipart/form-data`

#### Headers

| Header            | Value              | Description              |
| ----------------- | ------------------ | ------------------------ |
| `Accept`          | `application/json` | Required                 |
| `Accept-Language` | `ar` or `en`       | Optional (default: `ar`) |

#### Request Body

| Parameter       | Type   | Required | Description / Validation       | Example                   |
| --------------- | ------ | -------- | ------------------------------ | ------------------------- |
| `name`          | String | Yes      | Full user name (max: 255)      | `أحمد محمد السديري`       |
| `email`         | String | Yes      | Valid email address (max: 255) | `ahmed.samir@example.com` |
| `phone`         | String | Yes      | Mobile phone number            | `966500000000`            |
| `date_of_birth` | Date   | Yes      | Format: `Y-m-d`                | `1992-05-15`              |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "id": 1,
        "name": "أحمد محمد السديري",
        "email": "ahmed.samir@example.com",
        "phone": "966500000000",
        "date_of_birth": "1992-05-15",
        "phone_verified": false
    },
    "messages": ["تم إرسال كود التحقق إلى هاتفك بنجاح"],
    "errors": []
}
```

---

### 1.2 Verify Phone OTP

Verifies the SMS OTP code and sets `phone_verified = true`.

- **Method**: `POST`
- **URL**: `auth/verify-phone`
- **Auth Required**: No
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter | Type   | Required | Description                                    | Example        |
| --------- | ------ | -------- | ---------------------------------------------- | -------------- |
| `phone`   | String | Yes      | Registered phone number (`exists:users,phone`) | `966500000000` |
| `code`    | String | Yes      | Verification code                              | `1234`         |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {
            "id": 1,
            "name": "أحمد محمد السديري",
            "email": "ahmed.samir@example.com",
            "phone": "966500000000",
            "date_of_birth": "1992-05-15",
            "type": "national_id",
            "status": "active"
        }
    },
    "messages": ["تم التحقق من رقم الهاتف بنجاح"],
    "errors": []
}
```

#### Error Response (`400 Bad Request` - Invalid Code)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["رمز التحقق غير صحيح أو تم استخدامه مسبقاً"]
}
```

---

### 1.3 Resend Phone OTP

Resends a new verification OTP code with cooldown protection (default 60s).

- **Method**: `POST`
- **URL**: `auth/resend-phone-otp`
- **Auth Required**: No
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter | Type   | Required | Description             | Example        |
| --------- | ------ | -------- | ----------------------- | -------------- |
| `phone`   | String | Yes      | Registered phone number | `966500000000` |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": null,
    "messages": ["تم إعادة إرسال رمز التحقق بنجاح"],
    "errors": []
}
```

#### Error Response (`400 Bad Request` - Cooldown Active)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["يرجى الانتظار قبل طلب رمز جديد"]
}
```

---

### 1.4 Register Step 2 (Complete)

Completes the registration process. Requires `phone_verified == true`. Sets the password and identity documents according to user type (`national_id`, `resident_id`, or `visitor`), and returns the JWT Bearer token.

- **Method**: `POST`
- **URL**: `auth/register-step-two`
- **Auth Required**: No
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter                | Type   | Required    | Description / Rules                                                           | Example         |
| ------------------------ | ------ | ----------- | ----------------------------------------------------------------------------- | --------------- |
| `phone`                  | String | Yes         | Must be verified in step 1 (`exists:users,phone`)                             | `966500000000`  |
| `type`                   | Enum   | Yes         | User type: `national_id`, `resident_id`, or `visitor`                         | `national_id`   |
| `password`               | String | Yes         | Min 8 chars, must match confirmation (`confirmed`)                            | `Secret@123`    |
| `password_confirmation`  | String | Yes         | Password confirmation                                                         | `Secret@123`    |
| `driving_license_number` | String | Yes         | Driving license number                                                        | `DL-987654321`  |
| `license_expiry_date`    | Date   | Yes         | Format `Y-m-d` (must be `after:today`)                                        | `2032-12-31`    |
| `id_number`              | String | Conditional | Required if type is `national_id` or `resident_id` (`unique:users,id_number`) | `1020304050`    |
| `id_number_end_date`     | Date   | Conditional | Required if type is `national_id` or `resident_id` (`Y-m-d`)                  | `2032-12-31`    |
| `version_number`         | String | Conditional | Required if type is `national_id` or `resident_id`                            | `1`             |
| `license_number`         | String | Conditional | Required if type is `visitor` (Visitor driving license number)                | `VIS-LIC-12345` |
| `border_entry_number`    | String | Conditional | Required if type is `visitor` (`unique:users,border_entry_number`)            | `9988776655`    |

#### Success Response (`201 Created` - National ID / Resident)

```json
{
    "code": 201,
    "data": {
        "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {
            "id": 1,
            "name": "أحمد محمد السديري",
            "email": "ahmed.samir@example.com",
            "phone": "966500000000",
            "date_of_birth": "1992-05-15",
            "type": "national_id",
            "status": "active"
        }
    },
    "messages": ["تم تسجيل حسابك بنجاح"],
    "errors": []
}
```

#### Success Response (`201 Created` - Visitor)

```json
{
    "code": 201,
    "data": {
        "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {
            "id": 2,
            "name": "John Doe",
            "email": "john.doe@example.com",
            "phone": "966511111111",
            "date_of_birth": "1990-01-10",
            "type": "visitor",
            "status": "active"
        }
    },
    "messages": ["تم تسجيل حسابك بنجاح"],
    "errors": []
}
```

#### Error Response (`400 Bad Request` - Phone Not Verified)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["يجب تأكيد رقم الهاتف أولاً لإتمام عملية التسجيل"]
}
```

---

### 1.5 Login

Authenticates user using their National ID / Resident ID (`id_number`) or Visitor Border Entry Number (`border_entry_number`) as `identifier`, along with account password.

- **Method**: `POST`
- **URL**: `auth/login`
- **Auth Required**: No
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter    | Type   | Required | Description                                                                                     | Example      |
| ------------ | ------ | -------- | ----------------------------------------------------------------------------------------------- | ------------ |
| `identifier` | String | Yes      | National ID, Resident ID, or Visitor Border Entry Number (`id_number` or `border_entry_number`) | `1020304050` |
| `password`   | String | Yes      | Account password                                                                                | `Secret@123` |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {
            "id": 1,
            "name": "أحمد محمد السديري",
            "email": "ahmed.samir@example.com",
            "phone": "966500000000",
            "date_of_birth": "1992-05-15",
            "type": "national_id",
            "status": "active"
        }
    },
    "messages": ["تم تسجيل الدخول بنجاح"],
    "errors": []
}
```

---

### 1.6 Logout

Revokes the authenticated user's JWT token.

- **Method**: `POST`
- **URL**: `auth/logout`
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": null,
    "messages": ["تم تسجيل الخروج بنجاح"],
    "errors": []
}
```

---

### 1.7 Reset Password

Resets the user account password using their registered mobile phone number.

> **Security & OTP Verification Guard:** This endpoint strictly requires the user to have performed a successful phone verification via `POST /auth/verify-phone` within the last **15 minutes**. If the phone has not been verified or the verification window has elapsed, the request is rejected with `400 Bad Request`. Upon a successful password update, the verification status is consumed immediately to prevent replay attacks.

- **Method**: `POST`
- **URL**: `auth/reset-password`
- **Auth Required**: No (Public guest endpoint)
- **Content-Type**: `multipart/form-data`

#### Headers

| Header            | Value              | Description              |
| ----------------- | ------------------ | ------------------------ |
| `Accept`          | `application/json` | Required                 |
| `Accept-Language` | `ar` or `en`       | Optional (default: `ar`) |

#### Request Body

| Parameter               | Type   | Required | Description / Rules                            | Example        |
| ----------------------- | ------ | -------- | ---------------------------------------------- | -------------- |
| `phone`                 | String | Yes      | Registered phone number (`exists:users,phone`) | `966500000000` |
| `password`              | String | Yes      | New password (min: 8 characters)               | `Secret@1234`  |
| `password_confirmation` | String | Yes      | Confirmation matching `password`               | `Secret@1234`  |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": null,
    "messages": ["تم تغيير كلمة المرور بنجاح"],
    "errors": []
}
```

#### Error Response (`400 Bad Request` - OTP Not Verified)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": [
        "يجب التحقق من رقم الهاتف عبر رمز التحقق أولاً قبل تغيير كلمة المرور"
    ]
}
```

#### Error Response (`422 Unprocessable Content` - Validation Failure)

```json
{
    "code": 422,
    "message": "حقل تأكيد كلمة المرور غير متطابق.",
    "errors": {
        "password": ["حقل تأكيد كلمة المرور غير متطابق."]
    }
}
```

---

## 2. User Profile

### 2.1 Get Current User (Me)

Retrieves current profile details of the authenticated user.

- **Method**: `GET`
- **URL**: `auth/me` (alias: `user/me`)
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "id": 1,
        "name": "أحمد محمد السديري",
        "email": "ahmed.samir@example.com",
        "phone": "966500000000",
        "date_of_birth": "1992-05-15",
        "type": "national_id",
        "id_number": "1020304050",
        "id_number_end_date": "2032-12-31",
        "version_number": "1",
        "driving_license_number": "DL-987654321",
        "license_expiry_date": "2032-12-31",
        "status": "active",
        "created_at": "2026-09-17T15:00:00.000000Z"
    },
    "messages": [],
    "errors": []
}
```

---

### 2.2 Update Profile

Updates authenticated user profile fields. Allows updating personal data, identity attributes, password, driving license details, and preferred language.

- **Method**: `PUT`
- **URL**: `user/profile`
- **Auth Required**: Yes (`Bearer <token>`)
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter                | Type   | Required    | Description / Rules                                      | Example                     |
| ------------------------ | ------ | ----------- | -------------------------------------------------------- | --------------------------- |
| `name`                   | String | No          | Updated display name (max: 255)                          | `أحمد محمد المحدث`          |
| `email`                  | String | No          | Updated email (unique except current user)               | `ahmed.updated@example.com` |
| `phone`                  | String | No          | Updated phone number (unique except current user)        | `966500000001`              |
| `date_of_birth`          | Date   | No          | Format `Y-m-d`                                           | `1992-05-15`                |
| `type`                   | Enum   | No          | `national_id`, `resident_id`, or `visitor`               | `national_id`               |
| `password`               | String | No          | New password (min: 8 characters, confirmed)              | `NewSecret@123`             |
| `password_confirmation`  | String | Conditional | Required if `password` is provided                       | `NewSecret@123`             |
| `driving_license_number` | String | No          | Driving license number                                   | `DL-987654321`              |
| `license_expiry_date`    | Date   | No          | Format `Y-m-d`                                           | `2032-12-31`                |
| `id_number`              | String | No          | National ID / Resident ID (unique except current user)   | `1020304050`                |
| `id_number_end_date`     | Date   | No          | Format `Y-m-d`                                           | `2032-12-31`                |
| `version_number`         | String | No          | ID version number                                        | `1`                         |
| `license_number`         | String | No          | Visitor driving license number                           | `VIS-LIC-12345`             |
| `border_entry_number`    | String | No          | Visitor Border Entry Number (unique except current user) | `9988776655`                |
| `language`               | String | No          | Preferred locale (`ar` or `en`)                          | `ar`                        |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "id": 1,
        "name": "أحمد محمد المحدث",
        "email": "ahmed.updated@example.com",
        "phone": "966500000000",
        "date_of_birth": "1992-05-15",
        "type": "national_id",
        "id_number": "1020304050",
        "id_number_end_date": "2032-12-31",
        "version_number": "1",
        "license_number": null,
        "border_entry_number": null,
        "driving_license_number": "DL-987654321",
        "license_expiry_date": "2032-12-31",
        "language": "ar",
        "status": "active"
    },
    "messages": ["تم تحديث الملف الشخصي بنجاح"],
    "errors": []
}
```

---

### 2.3 Delete Account

Permanently deletes the authenticated user's account, removes all associated device and FCM tokens from the database, and invalidates active JWT session tokens.

- **Method**: `DELETE`
- **URL**: `auth/account`
- **Auth Required**: Yes (`Bearer <token>`)

#### Headers

| Header            | Value              | Description               |
| ----------------- | ------------------ | ------------------------- |
| `Authorization`   | `Bearer <token>`   | Required JWT access token |
| `Accept`          | `application/json` | Required                  |
| `Accept-Language` | `ar` or `en`       | Optional (default: `ar`)  |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": null,
    "messages": ["تم حذف الحساب بنجاح"],
    "errors": []
}
```

#### Error Response (`401 Unauthorized`)

```json
{
    "code": 401,
    "data": null,
    "messages": [],
    "errors": ["غير مصرح"]
}
```

---

## 3. Home Screen

### 3.1 Home Aggregation

Returns unified aggregated data for the home screen including slider banners, categories, featured available cars (filtered by branch working hours), promotional offers, and brands.

- **Method**: `GET`
- **URL**: `home`
- **Auth Required**: No

#### Query Parameters

| Parameter     | Type    | Required | Description                                                    | Example      |
| ------------- | ------- | -------- | -------------------------------------------------------------- | ------------ |
| `city_id`     | Integer | No       | City ID for branch availability filtering (`exists:cities,id`) | `1`          |
| `pickup_date` | Date    | No       | Pickup date (`Y-m-d`) for working hours evaluation             | `2026-09-20` |
| `pickup_time` | Time    | No       | Pickup time (`H:i` format: `14:00`)                            | `14:30`      |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "banners": [
            {
                "id": 1,
                "title_ar": "عروض اليوم الوطني",
                "title_en": "National Day Offers",
                "image": "https://api.benhady.com/storage/banners/offer1.png"
            }
        ],
        "categories": [
            {
                "id": 1,
                "name_ar": "اقتصادية",
                "name_en": "Economy",
                "name": "اقتصادية"
            }
        ],
        "featured_cars": [
            {
                "id": 1,
                "name": "تويوتا كامري 2024",
                "daily_price": 180,
                "branch": {
                    "id": 1,
                    "name": "فرع المطار - الرياض"
                }
            }
        ],
        "offers": [],
        "brands": []
    },
    "messages": [],
    "errors": []
}
```

---

## 4. Cars Catalog

### 4.1 List & Filter Cars

Retrieves a paginated catalog of cars with multi-criteria filtering and sorting.

- **Method**: `GET`
- **URL**: `cars`
- **Auth Required**: No

#### Query Parameters

| Parameter         | Type    | Default      | Description                                  |
| ----------------- | ------- | ------------ | -------------------------------------------- |
| `category_id`     | Integer | -            | Filter by vehicle category                   |
| `brand_id`        | Integer | -            | Filter by manufacturer brand                 |
| `city_id`         | Integer | -            | Filter by branch city                        |
| `branch_id`       | Integer | -            | Filter by specific branch                    |
| `model_year_id`   | Integer | -            | Filter by model year                         |
| `transmission_id` | Integer | -            | Filter by transmission type (Auto / Manual)  |
| `fuel_type_id`    | Integer | -            | Filter by fuel type (Gasoline, Hybrid, etc.) |
| `min_price`       | Numeric | -            | Minimum daily rental price                   |
| `max_price`       | Numeric | -            | Maximum daily rental price                   |
| `seats`           | Integer | -            | Number of passenger seats                    |
| `search`          | String  | -            | Text search in car name or description       |
| `sort_by`         | String  | `created_at` | Sort field: `price`, `year`, `created_at`    |
| `sort_order`      | String  | `desc`       | `asc` or `desc`                              |
| `per_page`        | Integer | `15`         | Results per page                             |
| `page`            | Integer | `1`          | Page number                                  |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name_ar": "تويوتا كامري",
                "name_en": "Toyota Camry",
                "name": "تويوتا كامري",
                "daily_price": 180,
                "seats": 5,
                "image": "https://api.benhady.com/storage/cars/camry.png",
                "brand": {
                    "id": 1,
                    "name": "Toyota"
                },
                "category": {
                    "id": 1,
                    "name": "Sedan"
                }
            }
        ],
        "total": 45,
        "per_page": 15
    },
    "messages": [],
    "errors": []
}
```

---

### 4.2 Car Details

Retrieves all details for a single vehicle, including specifications, rental policies, pricing tiers, and branch contact.

- **Method**: `GET`
- **URL**: `cars/{id}`
- **Auth Required**: No

#### Query Parameters

| Parameter | Type    | Required | Description                          |
| --------- | ------- | -------- | ------------------------------------ |
| `city_id` | Integer | No       | City context for branch verification |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "id": 1,
        "name_ar": "تويوتا كامري",
        "name_en": "Toyota Camry",
        "name": "تويوتا كامري",
        "daily_price": 180,
        "weekly_price": 1150,
        "monthly_price": 4200,
        "branch": {
            "id": 1,
            "name_ar": "فرع طريق الملك فهد",
            "name_en": "King Fahd Road Branch",
            "phone": "966110000000"
        },
        "policies": [
            {
                "id": 1,
                "name_ar": "سياسة الوقود",
                "name_en": "Fuel Policy",
                "description_ar": "استلام كامل وتسليم كامل",
                "description_en": "Full to full"
            }
        ]
    },
    "messages": [],
    "errors": []
}
```

---

## 5. Lookups & Rental Policies

All lookup endpoints return localized collections with dynamic attributes (`name`, `description`).

### 5.1 Categories

- **`GET categories`**: All vehicle categories (Sedan, SUV, Luxury, Economy, Family).

### 5.2 Brands

- **`GET brands`**: All vehicle brands (Toyota, Nissan, Hyundai, Mercedes, BMW, etc.).

### 5.3 Cities & Branches

- **`GET cities`**: All cities with nested branches and their active working hours.

### 5.4 Model Years

- **`GET model-years`**: Available car model manufacture years (e.g. 2023, 2024, 2025).

### 5.5 Transmissions

- **`GET transmissions`**: Transmission types (`Automatic`, `Manual`).

### 5.6 Fuel Types

- **`GET fuel-types`**: Fuel configurations (`Gasoline 91`, `Gasoline 95`, `Diesel`, `Hybrid`, `Electric`).

### 5.7 Rental Policies

- **`GET policies`**: General rental policies (Cancellation, Insurance, Mileage limits, Fuel policies).

---

## 6. Application Settings

### 6.1 Get Settings

Retrieves application configuration information (About Us, Terms & Conditions, Privacy Policy, Customer Service Phone, Email, WhatsApp, Social Media Links).

- **Method**: `GET`
- **URL**: `settings`
- **Auth Required**: No
- **Headers**: `Accept-Language: ar` / `en`

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "about_us": "شركة بن هادي لتأجير السيارات هي إحدى الشركات الرائدة...",
        "about_us_ar": "شركة بن هادي لتأجير السيارات...",
        "about_us_en": "BenHady Car Rental is a leading car rental company...",
        "terms_conditions": "شروط وأحكام استخدام الخدمة...",
        "privacy_policy": "سياسة الخصوصية وحماية بيانات المستخدمين...",
        "phone": "920000000",
        "email": "support@benhady.com",
        "whatsapp": "966500000000"
    },
    "messages": [],
    "errors": []
}
```

---

## 7. Frequently Asked Questions (FAQs)

### 7.1 Get FAQs

Retrieves all general frequently asked questions ordered by `sort_order`.

- **Method**: `GET`
- **URL**: `faqs`
- **Auth Required**: No
- **Headers**: `Accept-Language: ar` / `en`

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": [
        {
            "id": 1,
            "question_ar": "ما هي متطلبات استئجار سيارة من بن هادي؟",
            "question_en": "What are the requirements to rent a car from BenHady?",
            "answer_ar": "يتطلب استئجار سيارة هوية وطنية أو إقامة سارية المفعول ورخصة قيادة سارية...",
            "answer_en": "Renting a car requires a valid National ID or Iqama and a valid driving license...",
            "sort_order": 1,
            "question": "ما هي متطلبات استئجار سيارة من بن هادي؟",
            "answer": "يتطلب استئجار سيارة هوية وطنية أو إقامة سارية المفعول ورخصة قيادة سارية..."
        }
    ],
    "messages": [],
    "errors": []
}
```

---

## 8. Contact Us

### 8.1 Send Message

Sends an inquiry, complaint, or contact message. Can be called by guests or authenticated users. The `reply` field is nullable and will be populated when an administrator replies from the dashboard.

- **Method**: `POST`
- **URL**: `contact-us`
- **Auth Required**: Optional (`Bearer <token>` will associate `user_id`)
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter        | Type   | Required | Description                 | Example                                   |
| ---------------- | ------ | -------- | --------------------------- | ----------------------------------------- |
| `name`           | String | Yes      | Sender name                 | `سعد المنصور`                             |
| `email`          | String | Yes      | Sender email                | `saad@example.com`                        |
| `subject`        | String | Yes      | Inquiry subject / category  | `استفسار عن حجز`                          |
| `booking_number` | String | No       | Relevant reservation number | `BK-2026-991`                             |
| `message`        | String | Yes      | Message text content        | `أود الاستفسار عن إمكانية تمديد الحجز...` |

#### Success Response (`201 Created`)

```json
{
    "code": 201,
    "data": {
        "id": 1,
        "user_id": null,
        "name": "سعد المنصور",
        "email": "saad@example.com",
        "subject": "استفسار عن حجز",
        "booking_number": "BK-2026-991",
        "message": "أود الاستفسار عن إمكانية تمديد الحجز...",
        "reply": null,
        "created_at": "2026-09-17T16:16:03.000000Z",
        "updated_at": "2026-09-17T16:16:03.000000Z"
    },
    "messages": ["تم إرسال رسالتك بنجاح، سنتواصل معك قريباً"],
    "errors": []
}
```

---

## 9. Notifications & Device Tokens

### 9.1 Get Notifications

Retrieves paginated notifications for the authenticated user. Automatically marks fetched notifications as read (`is_read = true`).

- **Method**: `GET`
- **URL**: `notifications`
- **Auth Required**: Yes (`Bearer <token>`)

#### Query Parameters

| Parameter  | Type    | Default | Description                           |
| ---------- | ------- | ------- | ------------------------------------- |
| `page`     | Integer | `1`     | Page number                           |
| `per_page` | Integer | `15`    | Limit per page (1 - 50)               |
| `limit`    | Integer | -       | Optional maximum items limit (1 - 50) |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title_ar": "تأكيد الحجز",
                "title_en": "Booking Confirmation",
                "title": "تأكيد الحجز",
                "body_ar": "تم تأكيد حجز سيارتك بنجاح",
                "body_en": "Your car rental has been confirmed",
                "body": "تم تأكيد حجز سيارتك بنجاح",
                "is_read": true,
                "created_at": "2026-09-17T18:00:00.000000Z"
            }
        ],
        "total": 1
    },
    "messages": [],
    "errors": []
}
```

---

### 9.2 Get Unread Count

Returns the count of unread notifications for badge counters.

- **Method**: `GET`
- **URL**: `notifications/unread-count`
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "unread_count": 3
    },
    "messages": [],
    "errors": []
}
```

---

### 9.3 Delete All Notifications

Deletes all notifications for the authenticated user.

- **Method**: `DELETE`
- **URL**: `notifications`
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": null,
    "messages": ["تم حذف جميع الإشعارات بنجاح"],
    "errors": []
}
```

---

### 9.4 Save FCM Phone Token

Registers or updates the user device token for Firebase Cloud Messaging (FCM). Handles device reassignment automatically.

- **Method**: `POST`
- **URL**: `phone-tokens`
- **Auth Required**: Yes (`Bearer <token>`)
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter     | Type   | Required | Description            | Example                     |
| ------------- | ------ | -------- | ---------------------- | --------------------------- |
| `phone_token` | String | Yes      | FCM Registration Token | `eKz8_d9Fm4...device_token` |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "id": 1,
        "user_id": 1,
        "phone_token": "eKz8_d9Fm4...device_token",
        "created_at": "2026-09-17 19:00:00",
        "updated_at": "2026-09-17 19:00:00"
    },
    "messages": ["تم حفظ رمز الجهاز بنجاح"],
    "errors": []
}
```

---

### 9.5 Delete FCM Phone Token

Deletes a specific device token when the user logs out of their device.

- **Method**: `DELETE`
- **URL**: `phone-tokens`
- **Auth Required**: Yes (`Bearer <token>`)
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter     | Type   | Required | Description                      | Example                     |
| ------------- | ------ | -------- | -------------------------------- | --------------------------- |
| `phone_token` | String | Yes      | FCM Registration Token to delete | `eKz8_d9Fm4...device_token` |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": null,
    "messages": ["تم حذف رمز الجهاز بنجاح"],
    "errors": []
}
```

---

## 10. Bookings

### 10.1 Create Booking

Creates a new car rental reservation for the authenticated user. Automatically validates car availability, branch working hours at pickup/return times, prevents overlapping bookings, and applies coupon discount if provided.

- **Method**: `POST`
- **URL**: `bookings`
- **Auth Required**: Yes (`Bearer <token>`)
- **Content-Type**: `multipart/form-data`

#### Headers

| Header            | Value              | Description               |
| ----------------- | ------------------ | ------------------------- |
| `Authorization`   | `Bearer <token>`   | Required JWT access token |
| `Accept`          | `application/json` | Required                  |
| `Accept-Language` | `ar` or `en`       | Optional (default: `ar`)  |

#### Request Body

| Parameter          | Type     | Required | Description / Rules                              | Example                 |
| ------------------ | -------- | -------- | ------------------------------------------------ | ----------------------- |
| `car_id`           | Integer  | Yes      | Car ID (`exists:cars,id`)                        | `1`                     |
| `pickup_branch_id` | Integer  | Yes      | Pickup branch ID (`exists:branches,id`)          | `1`                     |
| `return_branch_id` | Integer  | Yes      | Return branch ID (`exists:branches,id`)          | `2`                     |
| `pickup_at`        | DateTime | Yes      | Format `Y-m-d H:i:s` (must be `after:now`)       | `2026-10-20 10:00:00`   |
| `return_at`        | DateTime | Yes      | Format `Y-m-d H:i:s` (must be `after:pickup_at`) | `2026-10-23 10:00:00`   |
| `notes`            | String   | No       | Additional customer notes (max: 1000)            | `يرجى تجهيز مقعد أطفال` |
| `coupon_code`      | String   | No       | Promotional discount coupon code                 | `SAVE10`                |

#### Success Response (`201 Created`)

```json
{
    "code": 201,
    "data": {
        "id": 1,
        "booking_number": "BH-20261020-0001",
        "user_id": 1,
        "car_id": 1,
        "pickup_branch_id": 1,
        "return_branch_id": 2,
        "pickup_at": "2026-10-20T10:00:00.000000Z",
        "return_at": "2026-10-23T10:00:00.000000Z",
        "daily_price": "250.00",
        "total_days": 3,
        "subtotal": "750.00",
        "discount": "75.00",
        "total": "675.00",
        "notes": "يرجى تجهيز مقعد أطفال",
        "status": "pending",
        "status_label": "قيد الانتظار"
    },
    "messages": ["تم إنشاء الحجز بنجاح"],
    "errors": []
}
```

#### Error Response (`400 Bad Request` - Branch Closed)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["الفرع مغلق في الوقت المطلوب"]
}
```

#### Error Response (`400 Bad Request` - Overlapping Booking)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["السيارة محجوزة بالفعل في هذه الفترة"]
}
```

---

### 10.2 Active Bookings

Retrieves current and ongoing bookings (`pending`, `active`) for the authenticated user, with car details, primary image, and branches.

- **Method**: `GET`
- **URL**: `bookings/active`
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": [
        {
            "id": 1,
            "booking_number": "BH-20261020-0001",
            "pickup_at": "2026-10-20T10:00:00.000000Z",
            "return_at": "2026-10-23T10:00:00.000000Z",
            "total_days": 3,
            "total": "675.00",
            "status": "active",
            "status_label": "نشطة",
            "car": {
                "id": 1,
                "name": "تويوتا كامري 2024",
                "primary_image": "cars/camry_front.jpg"
            },
            "pickup_branch": {
                "id": 1,
                "name": "فرع الرياض الرئيسي"
            },
            "return_branch": {
                "id": 2,
                "name": "فرع مطار الملك خالد"
            }
        }
    ],
    "messages": [],
    "errors": []
}
```

---

### 10.3 Closed Bookings (History)

Retrieves completed and cancelled booking history (`completed`, `cancelled`) for the authenticated user.

- **Method**: `GET`
- **URL**: `bookings/closed`
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": [
        {
            "id": 2,
            "booking_number": "BH-20260901-0012",
            "pickup_at": "2026-09-01T09:00:00.000000Z",
            "return_at": "2026-09-04T09:00:00.000000Z",
            "total_days": 3,
            "total": "600.00",
            "status": "completed",
            "status_label": "مكتمل",
            "car": {
                "id": 2,
                "name": "هيونداي إلنترا 2023",
                "primary_image": "cars/elantra.jpg"
            }
        }
    ],
    "messages": [],
    "errors": []
}
```

---

### 10.4 Booking Details

Fetches full details of a specific booking owned by the authenticated user.

- **Method**: `GET`
- **URL**: `bookings/{id}`
- **Auth Required**: Yes (`Bearer <token>`)

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "id": 1,
        "booking_number": "BH-20261020-0001",
        "pickup_at": "2026-10-20T10:00:00.000000Z",
        "return_at": "2026-10-23T10:00:00.000000Z",
        "daily_price": "250.00",
        "total_days": 3,
        "subtotal": "750.00",
        "discount": "75.00",
        "total": "675.00",
        "coupon_code": "SAVE10",
        "status": "active",
        "status_label": "نشطة",
        "notes": "يرجى تجهيز مقعد أطفال",
        "car": {
            "id": 1,
            "name": "تويوتا كامري 2024",
            "daily_price": "250.00",
            "primary_image": "cars/camry_front.jpg"
        },
        "pickup_branch": {
            "id": 1,
            "name": "فرع الرياض الرئيسي",
            "address": "طريق الملك فهد، الرياض"
        },
        "return_branch": {
            "id": 2,
            "name": "فرع مطار الملك خالد",
            "address": "صالة 2، مطار الملك خالد"
        }
    },
    "messages": [],
    "errors": []
}
```

---

## 11. Coupons & Discounts

### 11.1 Apply Discount Coupon

Validates a discount coupon code against booking subtotal, user eligibility, date window, and usage limits, and returns the calculated discount and final total.

- **Method**: `POST`
- **URL**: `coupons/apply`
- **Auth Required**: Yes (`Bearer <token>`)
- **Content-Type**: `multipart/form-data`

#### Request Body

| Parameter  | Type    | Required | Description / Rules              | Example      |
| ---------- | ------- | -------- | -------------------------------- | ------------ |
| `code`     | String  | Yes      | Coupon code (max: 50)            | `DISCOUNT15` |
| `subtotal` | Numeric | Yes      | Subtotal booking amount (min: 0) | `1000`       |

#### Success Response (`200 OK`)

```json
{
    "code": 200,
    "data": {
        "coupon_id": 1,
        "coupon_code": "DISCOUNT15",
        "discount_type": "percentage",
        "discount_value": 15.0,
        "subtotal": 1000.0,
        "discount_amount": 150.0,
        "total": 850.0
    },
    "messages": ["تم تطبيق كوبون الخصم بنجاح"],
    "errors": []
}
```

#### Error Response (`400 Bad Request` - Expired Coupon)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["كوبون الخصم منتهي الصلاحية"]
}
```

#### Error Response (`400 Bad Request` - Minimum Amount Not Met)

```json
{
    "code": 400,
    "data": null,
    "messages": [],
    "errors": ["قيمة الحجز أقل من الحد الأدنى لاستخدام الكوبون"]
}
```
