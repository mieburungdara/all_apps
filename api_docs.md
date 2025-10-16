# API Documentation

This document provides details on how to use the application's API.

---

## Authentication

All API endpoints require authentication via a Bearer Token. You must provide your personal API key in the `Authorization` header of your request.

**Header Format:**
```
Authorization: Bearer <YOUR_API_KEY>
```

You can find your API key on your user profile page (feature coming soon). New keys are automatically generated upon registration.

### Example Request with `curl`:
```bash
curl -H "Authorization: Bearer your_api_key_here" http://your.domain/sekolah/api/me
```

### Error Response (401 Unauthorized)
If the API key is missing, malformed, or invalid, you will receive a `401 Unauthorized` response.

```json
{
  "error": "Unauthorized",
  "message": "Invalid API key."
}
```

---

## Endpoints

### 1. Get Current User

Returns the details of the user associated with the provided API key.

- **Method:** `GET`
- **URL:** `/sekolah/api/me`
- **Permissions:** Any authenticated user.

#### Successful Response (200 OK)
```json
{
  "id": 1,
  "nama": "Admin User",
  "email": "admin@example.com",
  "api_key": "your_personal_api_key_here",
  "created_at": "2025-10-16 12:00:00"
}
```

### 2. Get All Users

Returns a list of all users in the system.

- **Method:** `GET`
- **URL:** `/sekolah/api/users`
- **Permissions:** **Admin only**.

#### Successful Response (200 OK)
```json
[
  {
    "id": 1,
    "nama": "Admin User",
    "email": "admin@example.com",
    "created_at": "2025-10-16 12:00:00"
  },
  {
    "id": 2,
    "nama": "Jane Doe",
    "email": "jane@example.com",
    "created_at": "2025-10-16 12:05:00"
  }
]
```

#### Error Response (403 Forbidden)
If a non-admin user attempts to access this endpoint.
```json
{
  "error": "Forbidden",
  "message": "You do not have permission to access this resource."
}
```

### 3. Get Specific User

Returns the details for a single user specified by their ID.

- **Method:** `GET`
- **URL:** `/sekolah/api/user/{id}`
- **Permissions:** Admin (can access any user) or a regular user accessing their own data.

#### URL Parameters
- `{id}` (required): The ID of the user to retrieve.

#### Successful Response (200 OK)

**Case 1: User requests their own data.** Note that the `api_key` is visible.
```json
{
  "id": 5,
  "nama": "Regular User",
  "email": "user@example.com",
  "api_key": "the_users_own_api_key",
  "created_at": "2025-10-16 12:10:00"
}
```

**Case 2: Admin requests another user's data.** Note that the `api_key` is hidden for security.
```json
{
  "id": 5,
  "nama": "Regular User",
  "email": "user@example.com",
  "created_at": "2025-10-16 12:10:00"
}
```

#### Error Responses

- **403 Forbidden:** If a user tries to access another user's data.
- **404 Not Found:** If the user with the specified ID does not exist.

```json
{
  "error": "Not Found",
  "message": "User not found."
}
```
