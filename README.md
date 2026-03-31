# Sculpt API Documentation

A powerful and elegant API documentation generator for Laravel applications with an interactive request testing interface.

## 🎯 About the Project

Sculpt is a modern Laravel package that generates comprehensive, interactive API documentation from your application's routes. It provides developers with a clean, user-friendly interface to explore, understand, and test all available API endpoints without leaving the browser.

Key features:
- **Automatic Route Scanning** - Discovers and documents all API routes
- **Interactive Testing** - Send GET, POST, PUT, PATCH, DELETE requests directly from the documentation
- **Smart Parameter Handling** - Support for query parameters, request body, and custom headers
- **Real-time Response Display** - View API responses with syntax highlighting and response timing metrics
- **Beautiful UI** - Modern dark theme interface built with Tailwind CSS
- **Zero Configuration** - Works out of the box with sensible defaults

---

## 🏗 Architecture

Sculpt is built following clean architecture principles with clear separation of concerns:

### Core Components

#### 1. **ApiDocService**
- Main service orchestrating documentation generation
- Collects route information from the Laravel router
- Generates OpenAPI 3.1.0 specification

#### 2. **RouteCollector** (Collectors)
- Scans Laravel routes and extracts metadata
- Filters API routes from the application
- Implements `CollectorInterface` for extensibility

#### 3. **DtoExtractor** (Extractors)
- Extracts request/response schema from DTO classes
- Analyzes controller methods for documentation metadata
- Generates JSON Schema representations

#### 4. **OpenApiGenerator** (Generators)
- Converts collected data into OpenAPI specification
- Supports OpenAPI 3.1.0 format
- Includes server configuration and metadata

#### 5. **ApiDocServiceProvider**
- Laravel service provider for package registration
- Registers routes and publishes configuration
- Handles package bootstrap

#### 6. **ApiDocController** (Http/Controllers)
- Handles documentation page requests
- Renders the interactive documentation blade view
- Passes OpenAPI spec to frontend

---

## 🛠 Technologies & Stack

- **Laravel** - ^10.0 | ^11.0 | ^12.0
- **PHP** - ^8.2
- **Tailwind CSS** - Modern styling framework
- **Highlight.js** - Code syntax highlighting
- **OpenAPI** - 3.1.0 specification standard
- **Spatie Laravel Data** - DTO validation and transformation
- **PHPUnit** - ^10.0 | ^11.0 (for testing)

---

## 📦 Installation

### 1. Install via Composer
```bash
composer require sculpt/apidoc
```

The package will be automatically registered via Laravel's package auto-discovery.

### 2. Publish Configuration (Optional)
```bash
php artisan vendor:publish --provider="Sculpt\ApiDoc\ApiDocServiceProvider"
```

This publishes the configuration file to `config/sculpt.php` where you can customize:
- Documentation title
- Description
- Version number
- Route prefix
- Server URL

### 3. Access Documentation
Navigate to `http://your-app.local/docs` (or custom route prefix) to view your API documentation.

---

## 🚀 Quick Start

### Basic Usage

The package automatically scans your `api/` routes. Just make sure your API routes are properly defined:

```php
// routes/api.php
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
```

### Adding Documentation Metadata

Use Spatie Laravel Data for request/response DTO's:

```php
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $created_at,
    ) {}
}

// In your controller
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(UserData::collect(User::all()));
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        return response()->json(UserData::from($user));
    }
}
```

### Configuration Example

```php
// config/sculpt.php
return [
    'title' => 'My API',
    'description' => 'My awesome API documentation',
    'version' => '1.0.0',
    'route_prefix' => 'docs',
    'enabled' => env('SCULPT_ENABLED', true),
];
```

---

## 📖 Features Explained

### 1. **Interactive API Testing**

Each endpoint displays a "Try it out" button that opens a modal with:
- **Request Builder** - Construct requests with custom parameters
- **Headers Section** - Add/remove custom HTTP headers
- **Query Parameters** - For GET, DELETE, and other query-based requests
- **Request Body** - JSON editor for POST, PUT, PATCH methods
- **Response Display** - View status codes, response time, and formatted response body

### 2. **Auto-detection of HTTP Methods**

The documentation intelligently displays form fields based on HTTP method:
- **GET/DELETE** - Query parameters and headers
- **POST/PUT/PATCH** - Request body, query parameters, and headers

### 3. **Syntax Highlighting**

All JSON examples and responses are automatically syntax-highlighted using Highlight.js for better readability.

### 4. **Responsive Design**

The documentation interface is fully responsive:
- Desktop: Side navigation + main content
- Mobile: Collapsible sidebar for better space utilization

### 5. **Dark Theme**

Beautiful dark theme optimized for reduced eye strain during development.

---

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run tests with code coverage:

```bash
composer test -- --coverage
```

---

## 📝 Example Use Cases

### E-commerce API

```php
// GET /api/products - List all products
// POST /api/products - Create new product
// GET /api/products/{id} - Get product details
// PUT /api/products/{id} - Update product
// DELETE /api/products/{id} - Delete product

// GET /api/orders - List orders
// POST /api/orders - Place new order
// GET /api/orders/{id} - Get order details
```

### User Management API

```php
// GET /api/users - List users
// POST /api/users - Create user
// GET /api/users/{id} - Get user profile
// PUT /api/users/{id} - Update profile
// DELETE /api/users/{id} - Delete user
// POST /api/users/{id}/avatar - Upload avatar
```

---

## 🔄 How It Works

1. **Route Discovery** - Sculpt scans your `api/` routes at documentation request time
2. **Metadata Extraction** - Analyzes controller methods for documentation hints
3. **DTO Analysis** - Examines DTO classes to generate request/response schemas
4. **OpenAPI Generation** - Compiles everything into OpenAPI 3.1.0 format
5. **Frontend Rendering** - Blade template renders interactive documentation UI
6. **Request Handling** - JavaScript intercepts requests and displays responses in real-time

---

## 🎨 Customization

### Custom Routes

Modify the route prefix in config:

```php
'route_prefix' => 'api-docs', // Now accessible at /api-docs
```

### Custom Blade View

Publish assets and customize the Blade template:

```bash
php artisan vendor:publish --provider="Sculpt\ApiDoc\ApiDocServiceProvider" --tag=views
```

### API Server URL

Configure the API server URL for testing:

```php
// config/sculpt.php
'servers' => [
    ['url' => env('API_URL', 'http://localhost:8000')],
],
```

---

## 🚨 Best Practices

1. **Use DTOs** - Leverage Spatie Laravel Data for type-safe request/response handling
2. **Add Descriptions** - Use docblock comments on controller methods
3. **Type Hints** - Always type-hint parameters and return types
4. **Consistent Naming** - Use RESTful naming conventions for routes
5. **Version Your API** - Use API versioning (e.g., `/api/v1/users`)
6. **Validation Rules** - Define comprehensive validation rules in Form Requests

---

## 🐛 Troubleshooting

### Documentation not showing routes

- Ensure routes are in `api/` prefix
- Check that `SCULPT_ENABLED` environment variable is true
- Verify routes are defined in `routes/api.php`

### JSON syntax highlighting not working

- Clear browser cache
- Verify CDN is accessible
- Check browser console for JavaScript errors

### Headers not being sent in requests

- Verify CORS configuration allows the headers
- Check if headers require special Laravel middleware
- Ensure Authorization header if needed

---

## 📄 License

Sculpt API Documentation is open-sourced software licensed under the [MIT license](LICENSE).

---

## 👨‍💻 Author

**Imran Imranov** - Full-stack developer passionate about clean architecture and developer experience.

---

## 🙏 Contributing

Contributions are welcome! Please feel free to submit a Pull Request or open an issue for bugs and feature requests.

### Development Setup

```bash
# Clone repository
git clone https://github.com/yourusername/sculpt-apidoc.git
cd sculpt-apidoc

# Install dependencies
composer install

# Run tests
composer test

# Run code analysis
composer analyse
```

---

## 📊 Project Statistics

- **PHP Version Required**: 8.2+
- **Laravel Version**: 10.0+
- **Package Size**: Minimal footprint
- **Dependencies**: Spatie Laravel Data only
- **Code Coverage**: 85%+

---

## 🔗 Related Projects

- [Laravel](https://laravel.com) - The PHP web framework
- [Spatie Laravel Data](https://github.com/spatie/laravel-data) - DTO management
- [OpenAPI Specification](https://spec.openapis.org/) - API documentation standard
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework

---

## 📞 Support

For support, issues, or questions:
- Open an issue on GitHub
- Check existing documentation
- Review test files for usage examples

---

**Happy Documenting! 🎉**
