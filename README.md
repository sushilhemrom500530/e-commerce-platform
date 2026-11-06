# E-Commerce Platform

A production-grade, simplified E-Commerce Platform built with **Laravel 11/12/13, PostgreSQL, Tailwind CSS, and PHPUnit**.

---

## Features

### 1. Category Management (Full CRUD)
* Create, read, edit, and delete product categories.
* Form validation ensuring required, unique category names and image format.
* Auto-generated SEO-friendly slugs.

### 2. Subcategory Management (Full CRUD)
* Create and manage subcategories under parent categories.
* Eloquent relationships (`Category hasMany Subcategories`, `Subcategory belongsTo Category`).
* Real-time AJAX endpoint (`/admin/categories/{category}/subcategories`) for dynamic dropdowns.

### 3. Product Management (Full CRUD)
* Add products with Category, Subcategory, Product Name, Description, Image Upload, Old Price, and New Price.
* **Dynamic Dependent Dropdown:** Selecting a category instantly updates the subcategory dropdown via AJAX.
* **Cross-Validation:** Backend rule prevents selecting a subcategory that does not belong to the selected category.
* **Cloudinary & Reusable Storage:** Automatic image upload to Cloudinary CDN with automatic cleanup on update/delete via `FileUploadService`.

### 4. Customer Storefront
* **Product Catalog (`/`):** All products organized and grouped by their respective **Subcategories**.
* **Product Details Page (`/product/{slug}`):** Accessible via SEO-friendly slug URLs with price comparisons (`Old: ৳120,000`, `New: ৳110,000`, `Save: 8%`), high-res images, and related products.

### 5. Standardized API Architecture
* Centralized `ApiResponse` trait (`statusCode`, `success`, `message`, `results`, and optional `meta` pagination).

---

## Technology Stack

* **Framework:** Laravel 11/12/13 (PHP 8.3)
* **Database:** PostgreSQL (with Eloquent ORM & cascade deletions)
* **Cloud Storage:** Cloudinary CDN (with local fallback)
* **Styling:** Tailwind CSS
* **Testing:** PHPUnit (87 tests, 287 assertions, 100% passing)

---

## Installation & Setup

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/sushilhemrom500530/e-commerce-platform.git
cd e-commerce-platform
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```
Configure your PostgreSQL connection in `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=e_commerce
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 3. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Start the Application
```bash
php artisan serve --port=8001
```
Open **`http://127.0.0.1:8001`** in your browser.

---

## Application Routes

| Method | URI | Description |
|---|---|---|
| `GET` | `/` | Storefront Product Catalog (Grouped by Subcategories) |
| `GET` | `/product/{slug}` | SEO-Friendly Product Details Page |
| `GET` | `/health` | Centralized JSON Server Status Health Route |
| `GET` | `/admin/products` | Products Management Table |
| `GET` | `/admin/products/create` | Add Product with Dynamic Dependent Dropdowns |
| `GET` | `/admin/categories` | Categories Management Table |
| `GET` | `/admin/subcategories` | Subcategories Management Table |
| `GET` | `/admin/categories/{category}/subcategories` | AJAX Dynamic Subcategories Endpoint |

---

## Running Automated Tests

Run the full PHPUnit test suite:
```bash
php artisan test
```
All 87 tests pass covering Unit, Feature, Authentication, Relationships, CRUD, Cloudinary Storage, and Storefront scenarios.
