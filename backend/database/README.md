# 🗄️ Database Installation Guide

## Production Database Information

**Domain:** adilcreator.com  
**Database Name:** u720615217_adil_db  
**MySQL User:** u720615217_adil_user  
**Password:** admin123  

---

## 📋 Database Overview

### Complete Schema File
- **File:** `complete_schema.sql`
- **Lines:** 1,398
- **Version:** 2.0.0 (Production Ready)
- **Size:** ~55KB

### What's Included

✅ **42 Database Tables:**
- User Management (7 tables) - with RBAC
- Content Management (4 tables)
- Dynamic CMS (4 tables)
- Notifications & Communications (6 tables)
- Orders & Transactions (3 tables)
- Gamification (2 tables)
- API Integrations & Funnel Testing (8 tables)
- Translation System (4 tables) - 12 languages
- System & Security (2 tables)

✅ **5 Database Views** - For reporting and dashboards

✅ **7 Stored Procedures** - Business logic automation

✅ **5 Triggers** - Automatic data updates

✅ **Complete Initial Data:**
- Default admin user (admin@adilgfx.com / admin123)
- Default settings
- Default achievements
- 10 API integrations configured
- 12 supported languages
- Sample UI translations

---

## 🚀 Installation Methods

### Method 1: Direct Import (Recommended)

**Via phpMyAdmin (Hostinger):**

1. Login to **Hostinger Control Panel**
2. Go to **Databases** → **phpMyAdmin**
3. Select database: `u720615217_adil_db`
4. Click **Import** tab
5. Choose file: `complete_schema.sql`
6. Click **Go**
7. ✅ Done! (2-3 minutes)

**Via MySQL Command Line:**

```bash
mysql -u u720615217_adil_user -p u720615217_adil_db < complete_schema.sql
# Enter password: admin123
```

---

### Method 2: Via Terminal/SSH

```bash
# Connect to your server
ssh your-server

# Navigate to database folder
cd /path/to/your/backend/database

# Import schema
mysql -u u720615217_adil_user -p u720615217_adil_db < complete_schema.sql

# Verify tables created
mysql -u u720615217_adil_user -p u720615217_adil_db -e "SHOW TABLES;"
```

---

### Method 3: PHP Script (Auto-Install)

Create a file: `install_database.php` (then delete it after use!)

```php
<?php
require_once '../config/database.php';

$sql = file_get_contents('complete_schema.sql');
$statements = array_filter(array_map('trim', explode(';', $sql)));

$success = 0;
$errors = 0;

foreach ($statements as $statement) {
    if (empty($statement) || strpos($statement, '--') === 0) continue;
    
    try {
        $pdo->exec($statement);
        $success++;
    } catch (PDOException $e) {
        $errors++;
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "✅ Successfully executed: $success statements\n";
echo "❌ Errors: $errors\n";
?>
```

Run once: `php install_database.php`  
Then **DELETE** the file for security!

---

## ✅ Verification

### Check Tables Created:

```sql
SELECT TABLE_NAME, TABLE_ROWS, CREATE_TIME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'u720615217_adil_db'
ORDER BY TABLE_NAME;
```

**Expected:** 42 tables

### Test Admin Login:

```sql
SELECT id, email, name, role
FROM users
WHERE email = 'admin@adilgfx.com';
```

**Expected:** 1 row (Admin user)

### Check Default Settings:

```sql
SELECT setting_key, setting_value
FROM settings;
```

**Expected:** 6 rows (site_name, primary_color, etc.)

---

## 🎯 Database Structure

### User Management & RBAC (7 tables)
```
users                  → Core user data with roles (user, viewer, editor, admin)
user_tokens            → Gamification token balances
token_history          → Token transaction log
user_streaks           → Login streak tracking
referrals              → Referral codes and stats
referral_tracking      → Individual referral records
user_sessions          → Session management
```

### Content Management (4 tables)
```
blogs                  → Blog posts
portfolio              → Project showcase
services               → Service offerings
testimonials           → Client reviews
```

### Dynamic CMS (4 tables)
```
settings               → Global site configuration
pages                  → Dynamic page builder
carousel_slides        → Homepage sliders
media_uploads          → File/image storage
```

### Notifications & Communications (6 tables)
```
notifications          → User notifications
contact_submissions    → Contact form data
newsletter_subscribers → Email list
email_campaigns        → Campaign tracking
whatsapp_messages      → WhatsApp queue
telegram_notifications → Telegram bot messages
```

### Orders & Transactions (3 tables)
```
orders                 → Project orders
order_revisions        → Revision requests
payment_transactions   → Payment tracking (Stripe, Coinbase)
```

### Gamification (2 tables)
```
achievements           → Achievement definitions
user_achievements      → User progress
```

### API Integrations & Funnel (8 tables)
```
api_integrations       → API configuration
api_logs               → API request/response logs
funnel_simulations     → Funnel tests
funnel_steps           → Individual funnel steps
funnel_metrics         → Aggregated metrics
webhook_events         → Webhook log
seo_metrics            → Google Search Console data
pagespeed_results      → PageSpeed Insights results
```

### Translation System (4 tables)
```
translations           → All translated content
supported_languages    → 12 language configurations
translation_cache      → API cache (cost reduction)
translation_stats      → Translation completion stats
```

### System & Security (2 tables)
```
rate_limits            → API rate limiting
activity_logs          → System activity tracking
```

---

## 🔧 Maintenance

### Cleanup Scripts (Run Periodically)

```sql
-- Clean expired sessions (run daily)
DELETE FROM user_sessions WHERE expires_at < NOW();

-- Clean old rate limits (run hourly)
DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL 2 HOUR);

-- Clean old activity logs (run weekly)
DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Clean old API logs (run monthly)
DELETE FROM api_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Reset daily API counters (run at midnight)
UPDATE api_integrations SET requests_today = 0;
```

### Backup Command

```bash
# Full backup
mysqldump -u u720615217_adil_user -p u720615217_adil_db > backup_$(date +%Y%m%d).sql

# Compressed backup
mysqldump -u u720615217_adil_user -p u720615217_adil_db | gzip > backup_$(date +%Y%m%d).sql.gz
```

---

## 🎯 Quick Start Checklist

After installation, verify these:

- [ ] All 42 tables created
- [ ] Admin user exists (admin@adilgfx.com)
- [ ] 6 default settings inserted
- [ ] 5 achievements created
- [ ] 10 API integrations configured
- [ ] 12 languages supported
- [ ] Sample UI translations inserted
- [ ] All views created
- [ ] All stored procedures created
- [ ] All triggers created

---

## 🆘 Troubleshooting

### Error: "Table already exists"
**Solution:** Database already has tables. Either:
1. Drop all tables first (⚠️ deletes data!)
2. Use `IF NOT EXISTS` (already in schema)

### Error: "Access denied"
**Solution:** Check credentials in `.env`:
```env
DB_HOST=localhost
DB_NAME=u720615217_adil_db
DB_USER=u720615217_adil_user
DB_PASS=admin123
```

### Error: "Syntax error near DELIMITER"
**Solution:** phpMyAdmin may not support DELIMITER. Use command line:
```bash
mysql -u u720615217_adil_user -p u720615217_adil_db < complete_schema.sql
```

### Error: "Unknown column in foreign key"
**Solution:** Import failed midway. Drop database and reimport:
```sql
DROP DATABASE u720615217_adil_db;
CREATE DATABASE u720615217_adil_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Then re-import
```

---

## 📊 Database Statistics

**Total Size:** ~55KB (schema only, empty tables)  
**Expected Size After Use:** 10-100MB (with content)  
**Indexes:** 80+ for performance  
**Foreign Keys:** 25+ for data integrity  
**Character Set:** utf8mb4 (full Unicode support)  
**Collation:** utf8mb4_unicode_ci  
**Engine:** InnoDB (ACID compliant)  

---

## 🎉 Success!

After installation, your database is ready for:
- ✅ User authentication (JWT)
- ✅ RBAC (4 roles)
- ✅ Blog management
- ✅ Portfolio management
- ✅ Service management
- ✅ Testimonial management
- ✅ Order processing
- ✅ Payment tracking
- ✅ API integrations
- ✅ Funnel testing
- ✅ 12-language support
- ✅ Gamification system
- ✅ Complete CMS

**Next Steps:**
1. Access admin panel: https://adilcreator.com/backend/admin/index.php
2. Login: admin@adilgfx.com / admin123
3. Start managing content!

---

**Generated:** October 21, 2025  
**Status:** ✅ Production Ready  
**Support:** Single consolidated schema file
