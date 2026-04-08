# 🚀 UP CIRENG: MySQL Production Deployment Checklist

**READ FIRST:** This checklist ensures zero-downtime migration and production readiness.
**Estimated Duration:** 30-45 minutes
**Risk Level:** ⚠️ MEDIUM (reversible if backup exists)

---

## 📋 PRE-MIGRATION CHECKLIST

### ✅ BACKUP SQLITE DATABASE

Before ANY changes:

```bash
# Create timestamped backup
cp database/database.sqlite database/database_backup_$(date +%Y%m%d_%H%M%S).sqlite

# Verify backup
ls -lh database/database_backup_*.sqlite
```

**Important:** Keep this backup safe for 7+ days.

---

### ✅ VERIFY CURRENT STATE

```bash
# Check SQLite connection works
php artisan tinker
>>> DB::table('users')->count()  // Should return user count
>>> exit

# Check Laravel migrations are applied
php artisan migrate:status
// All migrations should show "Ran" status
```

---

### ✅ RECORD CURRENT STATISTICS

```bash
# Document current row counts for comparison
php artisan tinker
>>> echo "Users: " . DB::table('users')->count() . "\n";
>>> echo "Products: " . DB::table('products')->count() . "\n";
>>> echo "Orders: " . DB::table('orders')->count() . "\n";
>>> echo "Testimonials: " . DB::table('testimonials')->count() . "\n";
>>> echo "Settings: " . DB::table('settings')->count() . "\n";
>>> exit
```

**Save these numbers** - you'll verify them after migration.

---

## 🔧 STEP 1: INSTALL MYSQL SERVER

### Windows (Recommended: MySQL 8.0 Community Edition)

```powershell
# Option 1: Download installer from https://dev.mysql.com/downloads/mysql/
# Run: mysql-8.0.36-winx64.msi

# Option 2: Using Chocolatey
choco install mysql -y

# Verify installation
mysql --version
# Output: mysql  Ver 8.0.36 for Win64 on x86_64 (MySQL Community Server - GPL)

# Verify service running
Get-Service | Select-Object Name, Status | Where-Object {$_.Name -like "*MySQL*"}
# Output: Name                Status
#         MySQL80             Running
```

### Linux (Ubuntu/Debian)

```bash
sudo apt-get update
sudo apt-get install mysql-server mysql-client -y
sudo systemctl status mysql
# Output: ● mysql.service - MySQL Community Server
#            Active: active (running)
```

### macOS (using Homebrew)

```bash
brew install mysql
brew services start mysql
mysql --version
```

---

## 🔐 STEP 2: SECURE MYSQL INSTALLATION

### Windows/macOS/Linux

```bash
# Connect as root (no password initially - MySQL default)
mysql -u root

# Inside MySQL client:
ALTER USER 'root'@'localhost' IDENTIFIED BY 'NewRootPassword123!';
FLUSH PRIVILEGES;
EXIT;

# Verify connection with new password
mysql -u root -p
// Enter: NewRootPassword123!
```

✅ **CRITICAL:** Write down this root password - you'll need it for setup script.

---

## 📦 STEP 3: CREATE DATABASE & USER

```bash
# Execute the production-grade setup script
mysql -u root -p < database/mysql_setup.sql

# When prompted for password, enter: NewRootPassword123! (from Step 2)
```

**Expected output:**

```
Query OK - Database created
Query OK - User created
Query OK - Privileges granted
Query OK - Flushed privileges
✅ MySQL Setup Complete!
```

---

## 🔒 STEP 4: SECURE MySQL BIND ADDRESS (Production Only)

### Why: Restrict MySQL to localhost only (security best practice)

#### Windows (my.ini)

```ini
# Find: C:\ProgramData\MySQL\MySQL Server 8.0\my.ini

# Find line:
# bind-address=127.0.0.1

# Make sure it reads (uncomment if needed):
bind-address=127.0.0.1

# Save and restart MySQL:
```

```powershell
Restart-Service MySQL80
```

#### Linux (mysqld.cnf)

```bash
# Edit:
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Find and modify:
bind-address = 127.0.0.1

# Save (Ctrl+O, Enter, Ctrl+X)

# Restart:
sudo systemctl restart mysql
```

#### macOS

```bash
# Edit:
nano /usr/local/etc/my.cnf

# Add if not present:
bind-address=127.0.0.1

# Restart:
brew services restart mysql
```

---

## ⚙️ STEP 5: CONFIGURE LARAVEL (.env)

### Current State:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Change To:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upcireng_db
DB_USERNAME=upcireng_user
DB_PASSWORD=UPCireng@2024#Secure!
```

### Command:

```bash
cd /path/to/upcireng

# File: .env (line 18-24, modify if needed)
# Make sure these lines are ACTIVE (not commented):

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upcireng_db
DB_USERNAME=upcireng_user
DB_PASSWORD=UPCireng@2024#Secure!
```

✅ **IMPORTANT:** No spaces around `=` sign, no quotes around values.

---

## 📚 STEP 6: FRESH MIGRATIONS TO MYSQL

Creates all tables in MySQL matching your Laravel schema:

```bash
cd /path/to/upcireng

# Clear cache
php artisan cache:clear

# Run fresh migrations (creates tables in MySQL)
php artisan migrate:fresh

# Expected output:
# Migration: 2024_01_01_000000_create_users_table
# Migration: 2024_01_02_000000_create_orders_table
# ...
# ✓ 15 migrations executed
```

---

## 🔄 STEP 7: MIGRATE DATA FROM SQLite → MySQL

Safely transfers all existing data:

```bash
# Make the script executable
chmod +x database/migrate_sqlite_to_mysql.php

# Run migration
php database/migrate_sqlite_to_mysql.php

# Expected output:
# ✅ UP CIRENG: SQLite → MySQL Data Migration
# ✓ SQLite Connection: OK
# ✓ MySQL Connection: OK
#
# users: 12 records migrated
# products: 8 records migrated
# orders: 45 records migrated
# ...
#
# ✅ MIGRATION SUCCESSFUL!
# Total Records: 65
```

---

## ✅ STEP 8: VALIDATE DATA INTEGRITY

Verifies all data migrated correctly:

```bash
# Make executable
chmod +x database/validate_migration.php

# Run validation
php database/validate_migration.php

# Expected output:
# 📊 ROW COUNT VERIFICATION
# ✅ users: SQLite=12 | MySQL=12
# ✅ products: SQLite=8 | MySQL=8
# ✅ orders: SQLite=45 | MySQL=45
# ...
#
# ✅ ALL VALIDATION TESTS PASSED
```

**If any test FAILS:**

1. Stop here
2. Check error message
3. Review [Troubleshooting](#troubleshooting) section
4. Do NOT proceed until all tests pass

---

## 🔍 STEP 9: COMPATIBILITY AUDIT

Scans your code for potential MySQL issues:

```bash
# Make executable
chmod +x database/compatibility_audit.php

# Run audit
php database/compatibility_audit.php

# Expected output:
# 🔍 LARAVEL COMPATIBILITY AUDIT: SQLite → MySQL
#
# 📦 Scanning Models...
# ✓ Models look compatible
#
# 🎮 Scanning Controllers...
# ✓ Controllers look compatible
#
# ✅ No compatibility issues found!
```

---

## 🧪 STEP 10: TEST APPLICATION

### Start Development Server

```bash
php artisan serve --host=0.0.0.0 --port=8000

# Output:
# INFO  Server running on [http://0.0.0.0:8000]
```

### Test Each Feature

#### 1. **Homepage & Product Listing**

- Open: http://192.168.1.4:8000
- See products load? ✅
- Images display? ✅

#### 2. **User Login**

- Login with existing credentials
- Redirects to menu? ✅
- Shows correct user name? ✅

#### 3. **Place Order**

- Add items to cart
- Select variant (matang/mentah)
- Submit order
- Receives order number? ✅
- Payment methods appear? ✅

#### 4. **Admin Dashboard**

- Login as admin
- See all orders in MySQL? ✅
- Can mark orders as processing/completing? ✅
- Can sync to Google Sheets? ✅

#### 5. **Database Operations**

- Create new product (admin panel)
- Product appears on storefront? ✅
- Verify in MySQL: `mysql -u root -p`

```sql
USE upcireng_db;
SELECT COUNT(*) FROM products;
SELECT name FROM products ORDER BY created_at DESC LIMIT 1;
```

---

## 📊 STEP 11: FINAL VERIFICATION

### Compare Statistics

```bash
# Record final statistics
php artisan tinker
>>> $tables = ['users', 'products', 'orders', 'testimonials', 'settings'];
>>> foreach ($tables as $t) { echo $t . ': ' . DB::table($t)->count() . "\n"; }

# Compare with values from PRE-MIGRATION CHECKLIST
# All counts must match!
```

### Database Size

```sql
-- Connect to MySQL
mysql -u root -p

-- Check database size
USE information_schema;
SELECT table_schema,
       ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
FROM tables
WHERE table_schema = 'upcireng_db'
GROUP BY table_schema;

-- Output example:
-- table_schema  | size_mb
-- upcireng_db  | 2.45

EXIT;
```

### Performance Check

```bash
# Test query performance
php artisan tinker

>>> $start = microtime(true);
>>> DB::table('orders')->where('status', 'completed')->count();
>>> echo "Query time: " . round((microtime(true) - $start) * 1000) . "ms\n";
// Output: Query time: 45ms (acceptable)

>>> exit
```

---

## 🔐 STEP 12: SECURITY VERIFICATION

### Check Security Headers

```bash
# Open browser DevTools (F12)
# Go to Network tab
# Refresh page
# Click on main HTML request
# Check Response Headers contain:

# X-Frame-Options: SAMEORIGIN          ✅
# X-Content-Type-Options: nosniff       ✅
# X-XSS-Protection: 1; mode=block       ✅
# Content-Security-Policy: ...          ✅
# Strict-Transport-Security: ...        ✅
```

### Verify MySQL User Permissions

```sql
-- MySQL - verify restricted user
mysql -u root -p

-- Check what upcireng_user can do:
SHOW GRANTS FOR 'upcireng_user'@'127.0.0.1';

-- Should show ONLY:
-- SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX, REFERENCES

-- NOT: FILE, SUPER, GRANT, RELOAD, PROCESS
EXIT;
```

---

## 💾 STEP 13: BACKUP STRATEGY

### Daily Backup (Local Development)

```bash
# Backup MySQL database
mysqldump -u root -p upcireng_db > backups/upcireng_db_$(date +%Y%m%d_%H%M%S).sql
```

### Weekly Offsite Backup

```bash
# For production on VPS: setup automated backups to cloud storage
# Example: AWS S3, Google Cloud Storage, or Digital Ocean Spaces
# Command: mysqldump -u root -p upcireng_db | gzip | aws s3 cp - s3://your-bucket/upcireng_db_latest.sql.gz
```

---

## 🚨 TROUBLESHOOTING

### Problem: "Can't connect to MySQL server"

```
Error: SQLSTATE[HY000] [2002] Connection refused
```

**Solution:**

```bash
# Check MySQL is running
sudo systemctl status mysql     # Linux
Get-Service MySQL80            # Windows
brew services list             # macOS

# Start if not running
sudo systemctl start mysql      # Linux
Restart-Service MySQL80        # Windows
brew services start mysql      # macOS

# Verify credentials in .env
cat .env | grep DB_
```

---

### Problem: "Access denied for user 'upcireng_user'"

```
Error: SQLSTATE[HY000] [1045] Access denied for user 'upcireng_user'@'127.0.0.1'
```

**Solution:**

```sql
-- Connect as root
mysql -u root -p

-- Check if user exists
SELECT User, Host FROM mysql.user WHERE User='upcireng_user';

-- If not, recreate:
CREATE USER 'upcireng_user'@'127.0.0.1' IDENTIFIED BY 'UPCireng@2024#Secure!';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX, REFERENCES
  ON upcireng_db.* TO 'upcireng_user'@'127.0.0.1';
FLUSH PRIVILEGES;
EXIT;

-- Restart Laravel, test again
```

---

### Problem: "Table doesn't exist" migrations failed

```
Error: SQLSTATE[42S02] [1146] Table 'upcireng_db.users' doesn't exist
```

**Solution:**

```bash
# Ensure migrations ran
php artisan migrate:status

# If not, run:
php artisan migrate:fresh

# Verify tables created
mysql -u root -p -e "USE upcireng_db; SHOW TABLES;"
```

---

### Problem: Data didn't migrate

```
Migration script shows 0 records
```

**Solution:**

```bash
# Check SQLite still has data
php -r "
  \$db = new PDO('sqlite:database/database.sqlite');
  echo 'Users: ' . \$db->query('SELECT COUNT(*) FROM users')->fetchColumn() . \"\n\";
"

# If SQLite has data but migration shows 0, check MySQL isn't empty
mysql -u root -p -e "USE upcireng_db; SELECT COUNT(*) FROM users;"

# If data exists in MySQL, migration worked but validation script has issue
```

---

## ✨ MIGRATION COMPLETE!

### Summary

✅ **SQLite database:** ~3-4 MB, single connection, development only
✅ **MySQL database:** Production-ready, multi-user, secure, scalable
✅ **Data integrity:** 100% validated, zero losses
✅ **Security:** Least-privilege user, bind address restricted
✅ **Backups:** SQLite backup preserved for 7+ days

### Next Steps

1. **Test thoroughly** for 2-3 days in development
2. **Document** any custom queries that needed adjustment
3. **Monitor** database performance in production
4. **Update** CI/CD pipelines if using automated deployment
5. **Schedule** regular backups (daily minimum)

### Support

If issues arise:

```bash
# Check logs
tail -f storage/logs/laravel.log

# Database test
php artisan tinker
>>> DB::connection('mysql')->getPdo();  // Should not error

# Artisan migrate status
php artisan migrate:status
```

---

**Migration Date:** ******\_\_\_\_******
**Executed By:** ******\_\_\_\_******
**Approved By:** ******\_\_\_\_******

**Document Version:** 1.0 | Production-Grade MySQL Migration for UP CIRENG
