# 📡 API Reference - Mario Bramy

## Overview

All API endpoints return JSON with standardized response format:
```json
{
  "success": true|false,
  "message": "...",
  "data": {...} | [...]
}
```

---

## Public APIs

### Portfolio/Gallery API

**GET** `/api/portfolio.php`

Fetch portfolio images by category.

**Parameters:**
- `category` (string) - Category slug: `all`, `bramy-przesuwne`, `bramy-dwuskrzydlowe`, `balustrady`, `garażowe`, `automatyka`, `przesla`

**Response:**
```json
{
  "success": true,
  "category": "bramy-przesuwne",
  "count": 6,
  "images": [
    {
      "id": 1,
      "filename": "image_1.webp",
      "alt_text": "Brama aluminiowa 4m",
      "category_slug": "bramy-przesuwne",
      "category_name": "Bramy Przesuwne Aluminiowe",
      "url": "/mario-bramy/uploads/portfolio/bramy-przesuwne/image_1.webp"
    }
  ]
}
```

**Examples:**
```bash
# Get all images
curl http://localhost/mario-bramy/api/portfolio.php?category=all

# Get sliding gates
curl http://localhost/mario-bramy/api/portfolio.php?category=bramy-przesuwne

# In JavaScript
fetch('/mario-bramy/api/portfolio.php?category=bramy-przesuwne')
  .then(r => r.json())
  .then(data => {
    data.images.forEach(img => {
      console.log(img.filename);
    });
  });
```

---

### Products API

**GET** `/api/products.php`

Fetch all products with options and price modifiers.

**Response:**
```json
{
  "success": true,
  "count": 2,
  "products": [
    {
      "id": 1,
      "name": "Brama Przesuwna Modern",
      "base_price": 3500.00,
      "description": "...",
      "image_url": "/mario-bramy/uploads/products/gate-modern.webp",
      "options": [
        {
          "id": 1,
          "key": "width",
          "label": "Szerokość",
          "type": "select",
          "choices": [
            {
              "label": "3.5m",
              "value": "3.5",
              "price_modifier": 500.00
            },
            {
              "label": "4.0m",
              "value": "4.0",
              "price_modifier": 700.00
            },
            {
              "label": "5.0m",
              "value": "5.0",
              "price_modifier": 1000.00
            }
          ]
        },
        {
          "id": 2,
          "key": "color",
          "label": "Kolor",
          "type": "select",
          "choices": [
            {
              "label": "Czarny",
              "value": "black",
              "price_modifier": 0.00
            },
            {
              "label": "Szary",
              "value": "gray",
              "price_modifier": 200.00
            }
          ]
        }
      ]
    }
  ]
}
```

**Examples:**
```bash
# Fetch products
curl http://localhost/mario-bramy/api/products.php

# In JavaScript
fetch('/mario-bramy/api/products.php')
  .then(r => r.json())
  .then(data => {
    data.products.forEach(product => {
      console.log(`${product.name}: ${product.base_price}zł`);
      // Calculate total price
      let total = product.base_price;
      product.options.forEach(opt => {
        // User would select a choice
        const selectedChoice = opt.choices[0]; // Example
        total += selectedChoice.price_modifier;
      });
      console.log(`Total: ${total}zł`);
    });
  });
```

---

## Admin APIs (Requires Authentication)

All admin APIs require:
1. Active session (logged in)
2. Session not expired (30-min timeout)
3. CSRF token in POST/DELETE requests

### Authentication

**POST** `/admin/login.php`

Authenticate user.

**Body:**
```json
{
  "username": "admin",
  "password": "admin123",
  "csrf_token": "..."
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "redirect": "/mario-bramy/admin/dashboard.php"
}
```

**Logout:**
```
GET /admin/logout.php
```

---

### Gallery Management

#### List Images

**GET** `/admin/api/gallery-list.php`

Fetch all portfolio images.

**Response:**
```json
{
  "success": true,
  "count": 3,
  "images": [
    {
      "id": 1,
      "filename": "gate_1.webp",
      "alt_text": "Brama przesuwna 4m",
      "category_slug": "bramy-przesuwne",
      "uploaded_at": "15.01.2024 14:30"
    }
  ]
}
```

#### Upload Image

**POST** `/admin/api/gallery-upload.php`

Upload new gallery image (auto WebP conversion).

**Form Data:**
```
category_id: 1
image: [binary file]
alt_text: "Brama aluminiowa 4m"
csrf_token: "..."
```

**Response:**
```json
{
  "success": true,
  "image_id": 5,
  "message": "Image uploaded and converted to WebP",
  "filename": "gate_5.webp"
}
```

**cURL Example:**
```bash
curl -X POST \
  -H "Cookie: PHPSESSID=..." \
  -F "category_id=1" \
  -F "image=@/path/to/image.jpg" \
  -F "alt_text=Brama przesuwna" \
  -F "csrf_token=..." \
  http://localhost/mario-bramy/admin/api/gallery-upload.php
```

#### Delete Image

**DELETE** `/admin/api/gallery-delete.php`

Remove image (soft delete).

**JSON Body:**
```json
{
  "image_id": 5,
  "csrf_token": "..."
}
```

**Response:**
```json
{
  "success": true,
  "message": "Image deleted successfully"
}
```

---

### Products Management

#### Create Product

**POST** `/admin/api/products-create.php`

Create new product.

**Form Data:**
```
name: "Brama Przesuwna Modern"
description: "Solidna brama aluminiowa..."
base_price: 3500
image: [binary file]  (optional)
csrf_token: "..."
```

**Response:**
```json
{
  "success": true,
  "product_id": 1,
  "message": "Product created successfully"
}
```

#### Update Product

**POST** `/admin/api/products-update.php`

Update product details.

**JSON Body:**
```json
{
  "product_id": 1,
  "name": "Brama Przesuwna Modern XL",
  "description": "...",
  "base_price": 3800,
  "csrf_token": "..."
}
```

#### Delete Product

**DELETE** `/admin/api/products-delete.php`

Delete product (soft delete).

**JSON Body:**
```json
{
  "product_id": 1,
  "csrf_token": "..."
}
```

---

### Options Management (Product Features)

#### Create Option

**POST** `/admin/api/options-create.php`

Add option to product (e.g., "Szerokość", "Kolor").

**Form Data:**
```
product_id: 1
key: "width"
label: "Szerokość"
type: "select"
csrf_token: "..."
```

**Response:**
```json
{
  "success": true,
  "option_id": 1,
  "message": "Option added"
}
```

#### Delete Option

**DELETE** `/admin/api/options-delete.php`

Remove option (cascades to choices).

**JSON Body:**
```json
{
  "option_id": 1,
  "csrf_token": "..."
}
```

---

### Choices Management (Option Values)

#### Create Choice

**POST** `/admin/api/choices-create.php`

Add choice to option (e.g., "4m" with price modifier).

**Form Data:**
```
option_id: 1
label: "4.0m"
value: "4.0"
price_modifier: 700
csrf_token: "..."
```

**Response:**
```json
{
  "success": true,
  "choice_id": 1,
  "message": "Choice added"
}
```

#### Delete Choice

**DELETE** `/admin/api/choices-delete.php`

Remove choice.

**JSON Body:**
```json
{
  "choice_id": 1,
  "csrf_token": "..."
}
```

---

### Messages Management

#### List Messages

**GET** `/admin/api/messages-list.php`

Fetch contact form submissions.

**Response:**
```json
{
  "success": true,
  "count": 5,
  "messages": [
    {
      "id": 1,
      "name": "Jan Kowalski",
      "email": "jan@example.com",
      "phone": "123456789",
      "product_interest": "Bramy przesuwne",
      "message": "Zainteresowany bramą 4m...",
      "status": "pending",
      "submitted_at": "15.01.2024 10:30"
    }
  ]
}
```

---

## Cron Jobs

### Daily Review Sync

**GET** `/cron/sync-reviews-daily.php`

Automatically sync reviews from Google Places API.

**Setup (Hostinger):**
```
Schedule: Daily at 02:00 AM
Command: /usr/bin/php
Path: /public_html/mario-bramy/cron/sync-reviews-daily.php
```

**Manual trigger:**
```bash
curl https://mario-bramy.pl/cron/sync-reviews-daily.php
```

**Log file:** `/logs/reviews-sync-YYYY-MM-DD.log`

**Response (CLI):**
```
=== Starting review sync ===
Fetching from Google Places API...
✓ Inserted review by Jan Kowalski (rating: 5/5)
✓ Inserted review by Maria Nowak (rating: 5/5)
Sync complete: 2 inserted, 1 skipped
=== Sync finished ===
```

---

## Error Responses

### Common HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Not logged in |
| 403 | Forbidden - Session expired |
| 404 | Not Found - Resource doesn't exist |
| 500 | Server Error |

### Error Response Format

```json
{
  "success": false,
  "message": "Error description"
}
```

**Examples:**
```json
// Missing required field
{
  "success": false,
  "message": "Product name and price required"
}

// Session timeout
{
  "success": false,
  "message": "Session expired"
}

// CSRF validation failed
{
  "success": false,
  "message": "CSRF token invalid"
}
```

---

## Rate Limiting

Admin APIs implement rate limiting:
- **Limit**: 5 requests per 15 minutes
- **Per**: IP address + action
- **Response**: HTTP 429 (Too Many Requests)

---

## CORS Configuration

By default, CORS is disabled (single domain).

To enable for mobile app, add to response headers:
```php
header('Access-Control-Allow-Origin: https://app.mario-bramy.pl');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

---

## Testing with Postman

### 1. Get Auth Token
Create login request:
```
POST /admin/login.php
Body (form-data):
- username: admin
- password: admin123
- csrf_token: [from HTML form]
```

### 2. Use PHPSESSID Cookie
Copy `PHPSESSID` from response headers into Postman's Cookie Manager.

### 3. Call Admin APIs
```
GET /admin/api/gallery-list.php
Headers:
- Cookie: PHPSESSID=...
```

---

## Webhook Integration (Future)

When messages are submitted:
```php
// POST to webhook URL
$webhook_url = 'https://external-system.com/webhook';
$payload = [
  'id' => $submission_id,
  'name' => $name,
  'email' => $email,
  'message' => $message,
  'timestamp' => time()
];
wp_remote_post($webhook_url, ['body' => json_encode($payload)]);
```

---

**API Version**: 1.0  
**Last Updated**: 2024-01-15  
**Status**: Stable

