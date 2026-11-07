# E-Commerce Platform API & Web Routes Documentation

Comprehensive API and Web Route documentation for the E-Commerce Platform. This guide details every route defined in `routes/web.php`, including the complete CRUD APIs for **Categories**, **Subcategories**, **Products**, **Public Storefront**, **Shopping Cart**, **Authentication**, and **System Health**.

All controllers support dual-mode execution:
- **Web Interface:** Standard Blade HTML rendered in modern web browsers.
- **RESTful JSON API:** Returns clean, standardized JSON responses when requested with the header `Accept: application/json`.

---

## 1. Core Architecture & Base URLs

### Base URLs
- **Local Development:** `http://localhost:8001`
- **Render Production:** `https://e-commerce-platform-fca1.onrender.com`

### Standard Request Headers
| Header | Required | Value / Description |
|---|---|---|
| `Accept` | **Yes** (for JSON) | `application/json` |
| `Content-Type` | **Yes** (for POST/PUT) | `application/json` or `multipart/form-data` (for file uploads) |
| `X-CSRF-TOKEN` | Web / Browser | CSRF Token for browser sessions |
| `Authorization` | Optional | `Bearer <accessToken>` (for token-based requests) |

### Standard JSON Response Envelope
All API endpoints conform to a standardized JSON response format via the `ApiResponse` trait:

#### Success Response
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Operation completed successfully.",
  "results": { ... }
}
```

#### Paginated Success Response
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Products retrieved successfully.",
  "meta": {
    "currentPage": 1,
    "limit": 10,
    "totalResult": 45,
    "totalPages": 5
  },
  "results": [ ... ]
}
```

#### Validation Error (HTTP 422)
```json
{
  "statusCode": 422,
  "success": false,
  "message": "The given data was invalid.",
  "errorMessages": [
    {
      "field": "subcategory_id",
      "message": "The selected subcategory does not belong to the selected category."
    }
  ]
}
```

---

## 2. Category Management APIs (Admin CRUD)

### 2.1 List All Categories
* **Method & Route:** `GET /admin/categories`
* **Access:** Admin
* **Description:** Retrieves a paginated list of all product categories along with total count of associated subcategories and products.

#### Query Parameters
| Parameter | Type | Required | Description |
|---|---|---|---|
| `page` | integer | Optional | Page number for pagination (Default: 1) |

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Categories retrieved successfully.",
  "results": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics",
        "description": "Smartphones, laptops, audio gear, and electronic gadgets.",
        "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/categories/electronics.jpg",
        "is_active": true,
        "subcategories_count": 4,
        "products_count": 18,
        "created_at": "2026-11-06T12:00:00.000000Z",
        "updated_at": "2026-11-06T12:00:00.000000Z"
      }
    ],
    "total": 5
  }
}
```

---

### 2.2 Create Category
* **Method & Route:** `POST /admin/categories`
* **Access:** Admin
* **Content-Type:** `multipart/form-data`
* **Description:** Creates a new category with an auto-generated SEO slug and optional image upload to Cloudinary/local storage.

#### Request Body (Multipart / Form-Data)
| Field | Type | Required | Validation Rules | Description |
|---|---|---|---|---|
| `name` | string | **Yes** | `required|string|max:255|unique:categories,name` | Name of the category (e.g., "Smart Home") |
| `description` | string | No | `nullable|string` | Detailed category description |
| `image` | file | No | `nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048` | Category icon / banner image (max 2MB) |
| `is_active` | boolean | No | `nullable|boolean` | Category status (Default: `true`) |

#### Example Response (HTTP 201)
```json
{
  "statusCode": 201,
  "success": true,
  "message": "Category created successfully.",
  "results": {
    "id": 6,
    "name": "Smart Home",
    "slug": "smart-home",
    "description": "Smart speakers, security cameras, and automated lighting.",
    "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/categories/smart-home.png",
    "is_active": true,
    "created_at": "2026-11-07T12:00:00.000000Z",
    "updated_at": "2026-11-07T12:00:00.000000Z"
  }
}
```

---

### 2.3 Update Category
* **Method & Route:** `POST /admin/categories/{category}` or `PUT /admin/categories/{category}`
* **Access:** Admin
* **Content-Type:** `multipart/form-data`
* **Description:** Updates category details and replaces the existing image on Cloudinary/storage if a new file is uploaded.

#### Request Parameters
| Parameter | In | Type | Description |
|---|---|---|---|
| `{category}` | Path | integer/slug | Category ID or slug |
| `_method` | Body | string | Pass `PUT` if submitting as standard form POST |
| `name` | Body | string | Category name (unique, ignoring current) |
| `description` | Body | string | Updated description |
| `image` | Body | file | Optional replacement image |
| `is_active` | Body | boolean | Updated active state |

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Category updated successfully.",
  "results": {
    "id": 6,
    "name": "Smart Home & Automation",
    "slug": "smart-home-and-automation",
    "description": "Updated description",
    "is_active": true
  }
}
```

---

### 2.4 Delete Category
* **Method & Route:** `DELETE /admin/categories/{category}`
* **Access:** Admin
* **Description:** Deletes category, automatically cascades deletion to all child subcategories and products, and purges all associated images from Cloudinary/storage.

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Category deleted successfully.",
  "results": null
}
```

---

## 3. Subcategory Management APIs (Admin CRUD & Dependent Dropdown)

### 3.1 List All Subcategories
* **Method & Route:** `GET /admin/subcategories`
* **Access:** Admin
* **Description:** Lists all subcategories with their parent category relation and products count.

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Subcategories retrieved successfully.",
  "results": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "category_id": 1,
        "name": "Smartphones",
        "slug": "smartphones",
        "description": "Android & iOS mobile devices",
        "is_active": true,
        "products_count": 6,
        "category": {
          "id": 1,
          "name": "Electronics",
          "slug": "electronics"
        }
      }
    ],
    "total": 12
  }
}
```

---

### 3.2 Dynamic AJAX Subcategories Endpoint (Dependent Dropdown)
* **Method & Route:** `GET /admin/categories/{category}/subcategories`
* **Access:** Public / Admin
* **Description:** Returns active subcategories belonging strictly to the specified category. This endpoint powers the **real-time dependent dropdown** during product creation and editing.

#### Path Parameters
| Parameter | Type | Required | Description |
|---|---|---|---|
| `{category}` | integer/slug | **Yes** | Parent category ID or slug |

#### Example Response (HTTP 200)
```json
[
  {
    "id": 1,
    "name": "Smartphones"
  },
  {
    "id": 2,
    "name": "Laptops & Computers"
  },
  {
    "id": 3,
    "name": "Audio & Headphones"
  },
  {
    "id": 4,
    "name": "Smartwatches"
  }
]
```

#### Frontend JavaScript Integration
```javascript
document.getElementById('category_id').addEventListener('change', async function() {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('subcategory_id');
    subcategorySelect.innerHTML = '<option value="">Loading subcategories...</option>';
    
    if (!categoryId) {
        subcategorySelect.innerHTML = '<option value="">Select Category First</option>';
        return;
    }

    const res = await fetch(`/admin/categories/${categoryId}/subcategories`);
    const subcategories = await res.json();

    let options = '<option value="">Select Subcategory</option>';
    subcategories.forEach(sub => {
        options += `<option value="${sub.id}">${sub.name}</option>`;
    });
    subcategorySelect.innerHTML = options;
});
```

---

### 3.3 Create Subcategory
* **Method & Route:** `POST /admin/subcategories`
* **Access:** Admin
* **Content-Type:** `multipart/form-data`

#### Request Body
| Field | Type | Required | Validation Rules | Description |
|---|---|---|---|---|
| `category_id` | integer | **Yes** | `required|exists:categories,id` | ID of the parent category |
| `name` | string | **Yes** | `required|string|max:255` | Subcategory name |
| `description` | string | No | `nullable|string` | Description |
| `image` | file | No | `nullable|image|max:2048` | Subcategory banner |
| `is_active` | boolean | No | `nullable|boolean` | Active flag (Default: `true`) |

#### Example Response (HTTP 201)
```json
{
  "statusCode": 201,
  "success": true,
  "message": "Subcategory created successfully.",
  "results": {
    "id": 13,
    "category_id": 1,
    "name": "Gaming Consoles",
    "slug": "gaming-consoles",
    "is_active": true
  }
}
```

---

### 3.4 Update Subcategory
* **Method & Route:** `POST /admin/subcategories/{subcategory}` (with `_method=PUT`) or `PUT /admin/subcategories/{subcategory}`
* **Access:** Admin
* **Description:** Updates subcategory details and parent association.

---

### 3.5 Delete Subcategory
* **Method & Route:** `DELETE /admin/subcategories/{subcategory}`
* **Access:** Admin
* **Description:** Deletes subcategory and cascades deletion to all products assigned to it.

---

## 4. Product Management APIs (Admin CRUD & Multi-Image Upload)

### 4.1 List Products
* **Method & Route:** `GET /admin/products`
* **Access:** Admin
* **Description:** Paginated table of products with filtering and search support.

#### Query Parameters
| Parameter | Type | Required | Description |
|---|---|---|---|
| `search` | string | No | Search products by name (case-insensitive `ilike`) |
| `category_id` | integer | No | Filter products by parent category |
| `page` | integer | No | Pagination page number |

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Products retrieved successfully.",
  "results": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "category_id": 1,
        "subcategory_id": 1,
        "name": "Apple iPhone 16 Pro 256GB",
        "slug": "apple-iphone-16-pro-256gb",
        "description": "Titanium design, A18 Pro chip, and Camera Control.",
        "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/iphone16.jpg",
        "featured_images": [
          "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/featured/iphone16_back.jpg",
          "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/featured/iphone16_side.jpg"
        ],
        "old_price": "145000.00",
        "new_price": "135000.00",
        "stock": 15,
        "is_active": true,
        "category": { "id": 1, "name": "Electronics" },
        "subcategory": { "id": 1, "name": "Smartphones" }
      }
    ],
    "total": 32
  }
}
```

---

### 4.2 Create Product (with Cross-Validation & Image Upload)
* **Method & Route:** `POST /admin/products`
* **Access:** Admin
* **Content-Type:** `multipart/form-data`
* **Description:** Creates a product with thumbnail and gallery images. Enforces **strict cross-validation** ensuring `subcategory_id` belongs to `category_id`.

#### Request Fields
| Field | Type | Required | Validation Rules | Description |
|---|---|---|---|---|
| `category_id` | integer | **Yes** | `required|exists:categories,id` | Parent Category ID |
| `subcategory_id` | integer | **Yes** | `required|exists:subcategories,id` + **Cross-Validation** | Must belong to selected `category_id` |
| `name` | string | **Yes** | `required|string|max:255` | Product title |
| `description` | string | **Yes** | `required|string` | Detailed product specifications |
| `image` | file | **Yes** | `required|image|mimes:jpeg,png,jpg,webp,svg|max:2048` | Primary thumbnail image |
| `featured_images[]` | file(s) | No | `nullable|image|max:2048` | Gallery images array |
| `old_price` | numeric | No | `nullable|numeric|min:0` | Regular retail price (for discount display) |
| `new_price` | numeric | **Yes** | `required|numeric|min:0` | Active selling price |
| `stock` | integer | No | `nullable|integer|min:0` | Available quantity in stock (Default: `0`) |
| `is_active` | boolean | No | `nullable|boolean` | Product visibility (Default: `true`) |

#### Cross-Validation Error Example (HTTP 422)
*Triggered if selecting Category A (e.g. Electronics) with Subcategory of Category B (e.g. Shoes):*
```json
{
  "statusCode": 422,
  "success": false,
  "message": "The given data was invalid.",
  "errorMessages": [
    {
      "field": "subcategory_id",
      "message": "The selected subcategory does not belong to the selected category."
    }
  ]
}
```

#### Success Response (HTTP 201)
```json
{
  "statusCode": 201,
  "success": true,
  "message": "Product created successfully.",
  "results": {
    "id": 33,
    "category_id": 1,
    "subcategory_id": 1,
    "name": "Samsung Galaxy S24 Ultra",
    "slug": "samsung-galaxy-s24-ultra",
    "description": "Galaxy AI, Snapdragon 8 Gen 3, 200MP camera.",
    "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/s24ultra.jpg",
    "old_price": "155000.00",
    "new_price": "142000.00",
    "stock": 20,
    "is_active": true
  }
}
```

---

### 4.3 Update Product
* **Method & Route:** `POST /admin/products/{product}` (with `_method=PUT`) or `PUT /admin/products/{product}`
* **Access:** Admin
* **Content-Type:** `multipart/form-data`
* **Description:** Updates product attributes. The `image` field is optional during updates; if a new file is uploaded, the previous image is deleted from storage/Cloudinary and replaced.

---

### 4.4 Delete Product
* **Method & Route:** `DELETE /admin/products/{product}`
* **Access:** Admin
* **Description:** Permanently deletes product and purges thumbnail and all gallery featured images from storage.

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Product deleted successfully.",
  "results": null
}
```

---

## 5. Customer Storefront APIs (Public Catalog & SEO)

### 5.1 Storefront Homepage
* **Method & Route:** `GET /`
* **Access:** Public
* **Description:** Returns the storefront homepage featuring top categories and active products grouped by category/subcategory.

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Catalog retrieved successfully.",
  "results": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics",
      "products_count": 8,
      "products": [
        {
          "id": 1,
          "name": "Apple iPhone 16 Pro",
          "slug": "apple-iphone-16-pro",
          "old_price": "145000.00",
          "new_price": "135000.00",
          "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/iphone16.jpg",
          "subcategory": { "name": "Smartphones" }
        }
      ]
    }
  ]
}
```

---

### 5.2 Products Catalog Listing
* **Method & Route:** `GET /products`
* **Access:** Public
* **Description:** Paginated catalog of all products with full-text search, category filtering, subcategory filtering, and sorting.

#### Query Parameters
| Parameter | Type | Required | Description |
|---|---|---|---|
| `search` | string | No | Search keyword in product names |
| `category` | string | No | Filter by category slug |
| `subcategory` | string | No | Filter by subcategory slug |
| `sort` | string | No | Sort order: `price_low`, `price_high`, `newest` (Default: `newest`) |
| `page` | integer | No | Pagination page number |

#### Example Request
```http
GET /products?category=electronics&sort=price_low&page=1
Accept: application/json
```

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Products catalog retrieved successfully.",
  "results": {
    "products": {
      "current_page": 1,
      "data": [ ... ],
      "total": 18
    },
    "categories": [ ... ],
    "selected_category": "electronics",
    "selected_subcategory": null
  }
}
```

---

### 5.3 SEO-Friendly Product Details Page
* **Method & Route:** `GET /product/{product:slug}`
* **Access:** Public
* **Description:** Retrieves full details of an individual product using its SEO-friendly slug. Includes price comparisons, discount calculation, and up to 4 related products from the same subcategory.

#### Path Parameters
| Parameter | Type | Required | Description |
|---|---|---|---|
| `product:slug` | string | **Yes** | Unique product slug (e.g., `apple-iphone-16-pro-256gb`) |

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Product retrieved successfully.",
  "results": {
    "id": 1,
    "name": "Apple iPhone 16 Pro 256GB",
    "slug": "apple-iphone-16-pro-256gb",
    "description": "Full product description and technical specifications.",
    "old_price": "145000.00",
    "new_price": "135000.00",
    "stock": 15,
    "is_active": true,
    "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/iphone16.jpg",
    "featured_images": [ ... ],
    "category": { "id": 1, "name": "Electronics", "slug": "electronics" },
    "subcategory": { "id": 1, "name": "Smartphones", "slug": "smartphones" }
  }
}
```

---

### 5.4 About Us & Contact Endpoints
* **`GET /about`** — Company story, mission, and Dhaka headquarters info.
* **`GET /contact`** — Customer support hotlines, office location, and business hours.
* **`POST /contact`** — Submit customer inquiry:
  - `name`: string, required
  - `email`: email, required
  - `subject`: string, required
  - `message`: string, required

---

## 6. Shopping Cart & Checkout APIs

### 6.1 View Shopping Cart
* **Method & Route:** `GET /cart`
* **Access:** Public / Session
* **Description:** Returns items currently stored in the user's shopping session along with calculated subtotal, delivery charges, and final order total.

---

### 6.2 Add Item to Cart
* **Method & Route:** `POST /cart/add`
* **Content-Type:** `application/json`

#### Request Body
```json
{
  "product_id": 1,
  "quantity": 2
}
```

#### Example Response (HTTP 200)
```json
{
  "success": true,
  "message": "Product added to cart successfully.",
  "total_count": 2,
  "cart": {
    "1": {
      "id": 1,
      "name": "Apple iPhone 16 Pro 256GB",
      "slug": "apple-iphone-16-pro-256gb",
      "price": 135000,
      "image": "https://res.cloudinary.com/dz0hdhcn0/image/upload/v1/products/iphone16.jpg",
      "quantity": 2
    }
  }
}
```

---

### 6.3 Update Item Quantity
* **Method & Route:** `POST /cart/update`
* **Content-Type:** `application/json`

#### Request Body
```json
{
  "product_id": 1,
  "quantity": 3
}
```

---

### 6.4 Remove Item from Cart
* **Method & Route:** `POST /cart/remove`
* **Content-Type:** `application/json`

#### Request Body
```json
{
  "product_id": 1
}
```

---

### 6.5 Sync Cart (Bulk)
* **Method & Route:** `POST /cart/sync`
* **Description:** Bulk synchronizes client-side localStorage cart items into the server session.

#### Request Body
```json
{
  "items": [
    { "id": 1, "name": "Apple iPhone 16", "price": 135000, "quantity": 1 },
    { "id": 3, "name": "AirPods Pro", "price": 28000, "quantity": 2 }
  ]
}
```

---

### 6.6 Process Checkout
* **Method & Route:** `POST /cart/checkout`
* **Description:** Places an order, generates a tracking order number (`ORD-XXXXX`), and clears session cart items.

---

## 7. Web Authentication & Profile APIs

### 7.1 User / Admin Login
* **Method & Route:** `POST /login`
* **Content-Type:** `application/json` or `application/x-www-form-urlencoded`

#### Request Body
```json
{
  "email": "admin@example.com",
  "password": "Admin@123456"
}
```

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "Login successful.",
  "results": {
    "user": {
      "id": 1,
      "name": "System Administrator",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
}
```

---

### 7.2 User Registration
* **Method & Route:** `POST /register`
* **Content-Type:** `application/json`

#### Request Body
```json
{
  "name": "Tanvir Ahmed",
  "email": "tanvir@example.com",
  "password": "Password@1234",
  "password_confirmation": "Password@1234"
}
```

---

### 7.3 Logout
* **Method & Route:** `POST /logout` (or `GET /logout`)
* **Access:** Authenticated

---

### 7.4 User Profile & Update
* **`GET /profile`** — View authenticated user profile details.
* **`PUT /profile`** — Update profile name, email, or password:
  ```json
  {
    "name": "Tanvir Ahmed Updated",
    "email": "tanvir.updated@example.com",
    "password": "NewPassword@1234",
    "password_confirmation": "NewPassword@1234"
  }
  ```

---

## 8. Server Health & Monitoring API

### 8.1 Server Status & Database Check
* **Method & Route:** `GET /health`
* **Access:** Public
* **Description:** Centralized health monitor providing server status, real-time database connection state, environment metadata, and PHP/Laravel versions.

#### Example Response (HTTP 200)
```json
{
  "statusCode": 200,
  "success": true,
  "message": "E-Commerce Platform is running smoothly.",
  "results": {
    "name": "E-Commerce Platform",
    "status": "online",
    "environment": "production",
    "version": "1.0.0",
    "php_version": "8.3.16",
    "laravel_version": "11.45.1",
    "timestamp": "2026-11-07T12:00:00+00:00",
    "services": {
      "database": "connected"
    },
    "endpoints": {
      "health": "https://e-commerce-platform-fca1.onrender.com/health",
      "auth": "https://e-commerce-platform-fca1.onrender.com/api/v1/auth"
    }
  }
}
```

---

## 9. Quick cURL Examples for Testing

### Create Category via cURL
```bash
curl -X POST http://localhost:8001/admin/categories \
  -H "Accept: application/json" \
  -F "name=Smart Wearables" \
  -F "description=Fitness trackers and smart watches" \
  -F "image=@/path/to/watch.jpg"
```

### Fetch Dependent Subcategories via cURL
```bash
curl -X GET http://localhost:8001/admin/categories/1/subcategories \
  -H "Accept: application/json"
```

### Create Product with Cross-Validation via cURL
```bash
curl -X POST http://localhost:8001/admin/products \
  -H "Accept: application/json" \
  -F "category_id=1" \
  -F "subcategory_id=1" \
  -F "name=Sony WH-1000XM5" \
  -F "description=Industry leading noise canceling headphones" \
  -F "new_price=38000" \
  -F "old_price=42000" \
  -F "stock=10" \
  -F "image=@/path/to/headphones.jpg"
```

### Query Products Catalog via cURL
```bash
curl -X GET "http://localhost:8001/products?category=electronics&sort=price_low" \
  -H "Accept: application/json"
```

