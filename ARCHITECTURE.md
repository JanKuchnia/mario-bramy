# 🏗️ Architektura Mario Bramy

## Diagram Systemowy

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONT-END (Public)                        │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  index.php      nasze-projekty.php    opinie.php           │
│  (Homepage)     (Gallery + Tabs)      (Reviews)             │
│      ↓               ↓                    ↓                  │
│  Top 3 Reviews  Lazy Loading +      All Reviews            │
│  + Services     Lightbox             from DB               │
│                                                               │
│  kontakt.php    sklep.php            wkrotce.php           │
│  (Contact)      (Configurator)       (Coming Soon)         │
│      ↓               ↓                    ↓                  │
│  Form Submit    Real-time Prices     Static Page           │
│  Rate Limited   AJAX Loading         Redirect              │
│                                                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                     PUBLIC APIs                              │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  /api/portfolio.php?category=X     /api/products.php       │
│  ↓                                  ↓                        │
│  GET portfolio_images           GET products +             │
│  + category_slug                options + choices           │
│  JSON response                  with price_modifier         │
│                                                               │
└─────────────────────────────────────────────────────────────┘
                           ↓↑
┌─────────────────────────────────────────────────────────────┐
│                    DATABASE (MariaDB)                        │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  portfolio_categories ─────┬─ portfolio_images              │
│                             ↓                                │
│  products ────────┬── product_options                       │
│                   └─ product_option_choices (price_mod)     │
│                                                               │
│  admin_users                review (source: google|manual)  │
│  contact_submissions        company_info                    │
│  login_attempts             categories                      │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## Admin Panel Architecture

```
┌────────────────────────────────────────────────────────┐
│              /admin/login.php                           │
├────────────────────────────────────────────────────────┤
│  Username + Password + CSRF Token                      │
│  ↓                                                      │
│  Rate Limiting Check (5 per 900s)                      │
│  ↓                                                      │
│  Bcrypt verify_password()                              │
│  ↓                                                      │
│  create_session() + IP tracking                        │
│  ↓                                                      │
│  redirect → /admin/dashboard.php                       │
└────────────────────────────────────────────────────────┘
                    ↓ Session Active
┌────────────────────────────────────────────────────────┐
│         /admin/dashboard.php?section=X                 │
├────────────────────────────────────────────────────────┤
│  Session Timeout Check (30 min)                        │
│                                                         │
│  ┌─ Dashboard (default)                                │
│  ├─ Gallery Tab                                         │
│  │   ├─ Upload Form                                     │
│  │   │  → /admin/api/gallery-upload.php               │
│  │   │    ├─ File validation                            │
│  │   │    ├─ WebP conversion                            │
│  │   │    ├─ Thumbnail creation                         │
│  │   │    └─ INSERT portfolio_images                    │
│  │   │                                                   │
│  │   └─ Images Grid                                     │
│  │      ← /admin/api/gallery-list.php                 │
│  │      ← FETCH images + category                       │
│  │                                                       │
│  │      Delete Button                                   │
│  │      → /admin/api/gallery-delete.php               │
│  │      → Soft-delete + remove files                    │
│  │                                                       │
│  ├─ Products Tab                                        │
│  │   ├─ Products List ← /api/products.php             │
│  │   │   └─ Fetch all products                          │
│  │   │                                                   │
│  │   ├─ Create Product                                  │
│  │   │  → /admin/api/products-create.php              │
│  │   │   ├─ Upload image                                │
│  │   │   ├─ WebP conversion                             │
│  │   │   └─ INSERT products                             │
│  │   │                                                   │
│  │   ├─ Edit Product                                    │
│  │   │  → /admin/api/products-update.php              │
│  │   │   └─ UPDATE products fields                      │
│  │   │                                                   │
│  │   ├─ Delete Product                                  │
│  │   │  → /admin/api/products-delete.php              │
│  │   │   └─ Soft-delete                                 │
│  │   │                                                   │
│  │   ├─ Add Option (Szerokość, Kolor, etc)            │
│  │   │  → /admin/api/options-create.php               │
│  │   │   └─ INSERT product_options                      │
│  │   │                                                   │
│  │   ├─ Delete Option                                   │
│  │   │  → /admin/api/options-delete.php               │
│  │   │   └─ DELETE + cascade choices                    │
│  │   │                                                   │
│  │   ├─ Add Choice (4m, 5m, +500zł)                   │
│  │   │  → /admin/api/choices-create.php               │
│  │   │   └─ INSERT product_option_choices               │
│  │   │                                                   │
│  │   └─ Delete Choice                                   │
│  │      → /admin/api/choices-delete.php               │
│  │      └─ DELETE choice                                │
│  │                                                       │
│  └─ Messages Tab                                        │
│     └─ Messages List ← /admin/api/messages-list.php  │
│        ├─ Fetch all contact_submissions                 │
│        ├─ Status badge (pending/replied)                │
│        └─ Reply button (future)                          │
│                                                         │
│  Logout Button → /admin/logout.php                     │
│     └─ destroy_session() + redirect                     │
│                                                         │
└────────────────────────────────────────────────────────┘
```

## WebP Conversion Pipeline

```
User Upload (JPG/PNG)
    ↓
File Validation
├─ MIME type check
├─ Size check (20MB max)
└─ Name sanitization
    ↓
Move to /uploads/
    ↓
Try ImageMagick
│  convert image.jpg image.webp
│  └─ Success? ✓ Done
    ↓ Failed or not available
Fallback: GD Library
│  imagecreatefromjpeg()
│  imagewebp()
│  imagedestroy()
│  └─ Success? ✓ Done
    ↓
Generate Thumbnail (400x300)
│  imagecrop()
│  imagewebp() → image-thumb.webp
│  └─ (Optional, continues if fails)
    ↓
Save DB record
│  filename: image.webp
│  category_id: X
│  alt_text: "..."
```

## Security Flow

```
Request
  ↓
Session Validation
├─ require_login()
├─ check_session_timeout(30)
└─ IP verification
  ↓
CSRF Token Check
├─ Validate $_POST['csrf_token']
├─ Constant-time comparison
└─ Regenerate if needed
  ↓
Rate Limiting
├─ Check $_SESSION['rate_limits']
├─ Count attempts in time window
└─ Reject if threshold exceeded
  ↓
Input Validation
├─ Type check
├─ Length check
├─ Format validation (email, etc)
└─ Sanitization (htmlspecialchars, etc)
  ↓
Database Query
├─ Prepared statement
├─ Parameter binding
└─ No string concatenation
  ↓
Response
├─ JSON output
└─ No sensitive data leaked
```

## Google Places API Integration (Cron)

```
Daily @ 2:00 AM (Hostinger Cron)
    ↓
/cron/sync-reviews-daily.php
    ↓
Load config/secrets.php
│  GOOGLE_PLACES_API_KEY
│  GOOGLE_PLACES_ID
    ↓
cURL to Google Places API
│  /place/details/json?place_id=X&key=Y
    ↓
Parse JSON Response
├─ Check status=OK
├─ Extract reviews[]
└─ Iterate each review
    ↓
Deduplication
│  SELECT * FROM reviews
│  WHERE google_review_id = X
│  ↓ If exists: SKIP
│  ↓ If new: INSERT
    ↓
INSERT into reviews table
├─ author_name
├─ rating
├─ text
├─ source: 'google'
├─ google_review_id: dedup key
└─ submitted_at: FROM_UNIXTIME()
    ↓
Log Result
│  /logs/reviews-sync-2024-01-15.log
├─ Timestamp
├─ Count inserted
├─ Count skipped
└─ Errors
```

## Session & Authentication Timeline

```
User logs in
    ↓ (15 min)
Page load → check_session_timeout(30)
│  Session active? ✓ Continue
│  Reset activity timer
    ↓ (30 min total)
No user activity for 30 min
    ↓
check_session_timeout() detects
│  Now - last_activity > 30 min
│  ↓ Redirect to login?timeout=1
    ↓
Show "Session expired" message
    ↓
User logs in again
│  ✓ New session created
│  ✓ IP tracked
│  ✓ last_login updated
```

## Data Relationships

```
admin_users (1)
    ↓
    └─ (manages many)
        ├─ portfolio_images
        ├─ products
        ├─ company_info
        └─ contact_submissions

portfolio_categories (1)
    ↓
    └─ (has many)
        └─ portfolio_images

products (1)
    ↓
    └─ (has many)
        └─ product_options (1)
            ↓
            └─ (has many)
                └─ product_option_choices

reviews (all)
    ├─ source: 'google'
    │  └─ auto-fetched from Places API
    └─ source: 'manual'
       └─ entered via admin panel

contact_submissions (all)
    ├─ status: 'pending'
    ├─ status: 'replied'
    └─ email notifications sent
```

---

## File Upload Locations

```
/uploads/
├── portfolio/
│   ├── bramy-przesuwne/
│   │   ├── image_1.jpg (original)
│   │   ├── image_1.webp (converted)
│   │   └── image_1-thumb.webp (thumbnail)
│   ├── bramy-dwuskrzydlowe/
│   ├── balustrady/
│   ├── garażowe/
│   ├── automatyka/
│   └── przesla/
│
└── products/
    ├── product_1.jpg (original)
    ├── product_1.webp (converted)
    └── product_2.webp

/logs/
├── reviews-sync-2024-01-15.log
├── reviews-sync-2024-01-16.log
└── error.log
```

---

Generated with PHP 7.4+ architecture in mind.

