# Sculpt API Documentation - Quick Start Guide

## Installation & Setup

### Step 1: Install Package
```bash
composer require sculpt/apidoc
```

### Step 2: Configure (Optional)
Publish the configuration file:
```bash
php artisan vendor:publish --provider="Sculpt\ApiDoc\ApiDocServiceProvider"
```

Update `config/sculpt.php` as needed:
```php
return [
    'enabled' => env('SCULPT_APIDOC_ENABLED', true),
    'title' => 'My API',
    'description' => 'API Documentation',
    'version' => '1.0.0',
    'route_prefix' => 'docs', // Access at /docs
];
```

### Step 3: Define API Routes
Create routes in `routes/api.php`:
```php
Route::prefix('api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
```

### Step 4: Visit Documentation
Navigate to: `http://your-app.local/docs`

---

## Using the Interactive Documentation

### 1. **Browse Endpoints**
- All endpoints are listed in the left sidebar
- Click any endpoint to scroll to it
- Method badge shows the HTTP method color:
  - 🟢 Green = GET
  - 🔵 Blue = POST
  - 🟡 Yellow = PUT
  - 🟠 Orange = PATCH
  - 🔴 Red = DELETE

### 2. **Test an Endpoint**
Click the **"Try it out"** button on any endpoint:

#### For GET/DELETE Requests:
1. Add query parameters (optional):
   - Click **"+ Add Parameter"**
   - Enter parameter name and value
   - Click **"Remove"** to delete

2. Add custom headers (optional):
   - Click **"+ Add Header"**
   - Enter header name and value

3. Click **"Send Request"**

#### For POST/PUT/PATCH Requests:
1. Add query parameters (optional) - same as above
2. Edit **Request Body** - JSON format
3. Add custom headers (optional) - same as above
4. Click **"Send Request"**

### 3. **View Response**
After sending a request:
- **Status Code** - HTTP response code
- **Response Time** - Time taken in milliseconds
- **Response Body** - Formatted JSON with syntax highlighting

---

## Example Requests

### Get All Users (GET)
1. Find endpoint: `GET /api/users`
2. Click "Try it out"
3. Click "Send Request"
4. View users list in response

### Create User (POST)
1. Find endpoint: `POST /api/users`
2. Click "Try it out"
3. Edit Request Body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secure_password"
}
```
4. Click "Send Request"
5. View created user in response

### Search Users (GET with Query Parameter)
1. Find endpoint: `GET /api/users`
2. Click "Try it out"
3. Click "+ Add Parameter"
4. Enter: `name` = `John`
5. Click "Send Request"
6. View filtered results

### Delete User (DELETE)
1. Find endpoint: `DELETE /api/users/{id}`
2. Click "Try it out"
3. Add query parameter: `id` = `1`
4. Click "Send Request"
5. View deletion confirmation in response

---

## Adding Authentication

If your API requires authentication:

### Bearer Token
1. Open any endpoint
2. Click "Try it out"
3. Click "+ Add Header"
4. Enter Header: `Authorization`
5. Enter Value: `Bearer YOUR_TOKEN_HERE`
6. Send request

### API Key
1. Click "+ Add Header"
2. Enter Header: `X-API-Key`
3. Enter Value: `your_api_key_here`
4. Send request

### Basic Auth
1. Click "+ Add Header"
2. Enter Header: `Authorization`
3. Enter Value: `Basic base64(username:password)`
4. Send request

---

## Common Issues & Solutions

### Issue: "Cannot reach API"
**Solution**: Check if:
- API server is running
- URL is correct in browser
- CORS is properly configured
- Firewall isn't blocking requests

### Issue: "JSON Syntax Error"
**Solution**:
- Validate JSON in Request Body editor
- Use proper JSON format (quotes around strings)
- Check for trailing commas

### Issue: "401 Unauthorized"
**Solution**:
- Add proper Authorization header
- Check if token is expired
- Verify authentication credentials

### Issue: "Headers not being sent"
**Solution**:
- Ensure CORS allows custom headers
- Check browser console for CORS errors
- Verify server accepts the headers

---

## Best Practices

1. **Test Regularly** - Use the interactive testing to verify endpoints
2. **Document Responses** - Add descriptions to endpoints
3. **Use Type Hints** - Help Sculpt generate accurate schemas
4. **Version Your API** - Use `/api/v1/` prefix for versioning
5. **Consistent Naming** - Use RESTful conventions
6. **Error Handling** - Include error response examples

---

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Esc` | Close request modal |
| `Click outside modal` | Close request modal |
| `Tab` | Navigate form fields |
| `Enter` | (in query params) Add parameter |

---

## API Response Examples

### Success Response (200)
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Error Response (400)
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"]
  }
}
```

### Paginated Response (200)
```json
{
  "data": [
    {"id": 1, "name": "John Doe"},
    {"id": 2, "name": "Jane Doe"}
  ],
  "total": 2,
  "per_page": 15,
  "current_page": 1
}
```

---

## Performance Tips

1. **Cache Responses** - Reuse successful request bodies
2. **Use Query Filters** - Test pagination and filtering
3. **Monitor Response Times** - Check if API is slow
4. **Batch Requests** - Test bulk operations when available
5. **Clear Headers** - Remove unused headers before sending

---

## Support & Documentation

- 📖 Full Documentation: See [README.md](README.md)
- 🐛 Report Issues: GitHub Issues
- 💬 Ask Questions: Create Discussion
- 🤝 Contribute: See CONTRIBUTING.md

---

**Happy API Testing! 🚀**

