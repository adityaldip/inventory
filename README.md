# Inventory Management System API

A Laravel-based REST API for managing inventory, posts, and transactions with support for image uploads, tagging, and bulk imports.

## Features

- 🏷️ Polymorphic Relations (Tags & Images)
- 📦 Product Management
- 📝 Post Management
- 🔄 Transaction Processing
- 🖼️ S3 Image Storage
- 📨 Background Email Processing
- 🔍 Advanced Search Functionality
- 📥 Bulk Data Import (800k+ rows)
- 🔒 Race Condition Prevention

## Requirements

- PHP 8.1+
- MySQL 8.0+
- Composer
- Redis (for queues)
- AWS S3 or Compatible Storage
- Supervisor

## Installation

1. Clone the repository

bash
git clone https://github.com/yourusername/inventory-system.git
cd inventory-system
bash
composer install
bash
cp .env.example .env
bash
php artisan key:generate
bash
php artisan migrate --seed
bash
php artisan storage:link
bash
sudo apt-get install supervisor
bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:

## API Endpoints

### Posts
- `GET /api/v1/posts` - List all posts
- `POST /api/v1/posts` - Create a post
- `GET /api/v1/posts/{id}` - Get single post
- `PUT /api/v1/posts/{id}` - Update post
- `DELETE /api/v1/posts/{id}` - Delete post

### Products
- `GET /api/v1/products` - List all products
- `POST /api/v1/products` - Create a product
- `GET /api/v1/products/{id}` - Get single product
- `PUT /api/v1/products/{id}` - Update product
- `DELETE /api/v1/products/{id}` - Delete product

### Transactions
- `GET /api/v1/transactions` - List all transactions
- `POST /api/v1/transactions` - Create a transaction
- `GET /api/v1/transactions/{id}` - Get single transaction

### Images
- `POST /api/v1/images` - Upload image
- `DELETE /api/v1/images/{id}` - Delete image

### Tags
- `GET /api/v1/tags` - List all tags

### Search
- `GET /api/v1/search/posts` - Search posts
- `GET /api/v1/search/products` - Search products

### Bulk Import
- `POST /api/v1/imports` - Start bulk import
- `GET /api/v1/imports/{importId}/progress` - Check import progress

## Example Requests

### Create a Post

bash
curl -X POST http://your-domain/api/v1/posts \
-H "Accept: application/json" \
-H "Content-Type: application/json" \
-d '{
"title": "Test Post",
"content": "Content here",
"tags": []
}'
bash
curl -X POST http://your-domain/api/v1/products \
-H "Accept: application/json" \
-H "Content-Type: application/json" \
-d '{
"name": "Product Name",
"price": 99.99,
"unit": "pcs",
"quantity": 100,
"tags": []
}'
bash
curl -X POST http://your-domain/api/v1/images \
-H "Accept: application/json" \
-F "image=@/path/to/image.jpg" \
-F "type=product" \
-F "id=product-uuid-here"
bash
curl -X POST http://your-domain/api/v1/imports \
-H "Accept: application/json" \
-F "file=@/path/to/products.csv"
bash
php artisan test
