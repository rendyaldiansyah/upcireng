# 🔄 SQLite → MySQL Migration Guide

## UP CIRENG Order System

---

## 📋 Prerequisites

Sebelum memulai migration, pastikan:

✅ MySQL Server terinstall & running  
✅ PHP dengan MySQL extension (sudah ada)  
✅ Laravel project sudah update `.env`

---

## 🚀 Step-by-Step Migration

### **Step 1: Install MySQL Server**

**Windows:**

```
1. Download: https://dev.mysql.com/downloads/mysql/
2. Run installer MySQL 8.0 (Community Edition)
3. Custom Setup → pilih MySQL Server
4. Configure MySQL Server Setup Wizard
   - Config Type: Development Machine
   - MySQL Port: 3306
   - MySQL Root Password: (catat password!)
5. Selesai install
```

**Verify Installation:**

```powershell
mysql --version
```

### **Step 2: Setup Database & User**

Buka MySQL Command Line Client atau MySQL Workbench:

```bash
# Login dengan root
mysql -u root -p

# Paste isi file ini:
# database/mysql_setup.sql
```

**Atau via Command Line:**

```bash
mysql -u root -p < database/mysql_setup.sql
```

Ketika diminta password, masukkan password root MySQL Anda.

**Output yang diharapkan:**

```
MySQL Setup Completed!
character_set_database | utf8mb4
collation_database | utf8mb4_unicode_ci
```

### **Step 3: Verify MySQL Connection**

Edit `.env` file sudah otomatis, verifikasi:

```
DB_CONNECTION=mysql
DB_HOST=0.0.0.0
DB_PORT=3306
DB_DATABASE=upcireng_db
DB_USERNAME=upcireng_user
DB_PASSWORD=UPCireng@2024#Secure!
```

Test connection:

```bash
php artisan tinker
>>> DB::connection('mysql')->select('SELECT 1')
=> [
     0 => {#4
       0 => 1,
     },
   ]
```

Jika results ada, connection ✅ OK!

### **Step 4: Run Fresh Migrations**

Database Laravel structure di MySQL:

```bash
php artisan migrate:fresh
```

**⚠️ WARNING:** Ini akan drop & recreate semua tables. Pastikan sudah backup!

### **Step 5: Migrate Data dari SQLite ke MySQL**

```bash
php artisan tinker
>>> include('database/migrate_sqlite_to_mysql.php')
```

**Atau langsung:**

```bash
php database/migrate_sqlite_to_mysql.php
```

Output yang diharapkan:

```
🔄 UP CIRENG: SQLite → MySQL Data Migration
======================================================================
✓ SQLite Connection: OK
✓ MySQL Connection: OK
✓ MySQL Database: READY

📋 Migrating Tables...
----------------------------------------------------------------------
✓ Users (admin + customer): X records migrated
✓ Products (menu items): X records migrated
✓ Orders (pesanan): X records migrated
✓ Settings (konfigurasi): X records migrated
✓ Testimonials (review): X records migrated

----------------------------------------------------------------------
✅ Migration Successful!
📊 Total Records: XXX

🔍 Verifying Data Integrity...
----------------------------------------------------------------------
✓ Users: SQLite=X, MySQL=X
✓ Products: SQLite=X, MySQL=X
✓ Orders: SQLite=X, MySQL=X
✓ Settings: SQLite=X, MySQL=X
✓ Testimonials: SQLite=X, MySQL=X

🎉 Semua data berhasil dimigrate ke MySQL!
======================================================================
```

### **Step 6: Clear Cache & Verify**

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

Test aplikasi:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Buka: `http://192.168.1.4:8000`

---

## ✅ Verification Checklist

- [ ] MySQL database created successfully
- [ ] All data migrated from SQLite
- [ ] Users dapat login dengan account lama
- [ ] Products tampil di storefront
- [ ] Dapat add to cart & checkout
- [ ] Orders tersimpan di MySQL
- [ ] Admin dashboard berjalan normal
- [ ] Real-time notifications working
- [ ] Payment methods tetap berfungsi

---

## 🔒 Security Features (Sudah Terintegrasi)

✅ **SQL Injection Prevention**

- Semua queries pakai parameterized statements (Laravel Eloquent)
- User input di-sanitize otomatis

✅ **XSS Prevention**

- Security headers `X-XSS-Protection`
- Content Security Policy (CSP) strict

✅ **CSRF Protection**

- Laravel CSRF tokens di semua forms
- Middleware protection active

✅ **Password Security**

- MySQL user password: `UPCireng@2024#Secure!`
- BCRYPT hashing untuk user passwords (BCRYPT_ROUNDS=12)

✅ **Database User Permissions**

- User tidak punya admin/drop privileges
- Only CRUD operations yang diizinkan

✅ **Security Headers**

```
X-Frame-Options: SAMEORIGIN (prevent clickjacking)
X-Content-Type-Options: nosniff (prevent MIME sniffing)
X-XSS-Protection: 1; mode=block
Content-Security-Policy: strict rules
Strict-Transport-Security: HTTPS only (production)
```

---

## 🐛 Troubleshooting

### **Error: "Can't connect to MySQL server"**

```bash
# Check MySQL service status
Get-Service MySQL80  # or MySQL57, MySQL56

# Start service jika stopped
Start-Service MySQL80
```

### **Error: "Access denied for user 'upcireng_user'"**

```bash
# Verify user & password di .env match database/mysql_setup.sql
# Re-run setup script
mysql -u root -p < database/mysql_setup.sql
```

### **Data ada di SQLite tapi tidak ter-migrate**

```bash
# Check current connection
php artisan tinker
>>> DB::connection('sqlite')->table('users')->count()
>>> DB::connection('mysql')->table('users')->count()

# Re-run migration dengan verbose
php database/migrate_sqlite_to_mysql.php
```

---

## 📊 Performance Tips

Setelah migration, MySQL akan jauh lebih cepat!

**Optimization yang sudah built-in:**

- Indexed columns di important fields
- Connection pooling ready
- Query caching di application layer

**Real-time Features:**

- Polling untuk order updates: 5 detik
- Admin dashboard real-time: 5 detik

---

## 🔄 Backup & Recovery

### **Backup MySQL Database**

```bash
# Full backup
mysqldump -u upcireng_user -p upcireng_db > backup_upcireng_$(date +%Y%m%d).sql

# Restore backup
mysql -u upcireng_user -p upcireng_db < backup_upcireng_YYYYMMDD.sql
```

### **SQLite Database**

SQLite backup sudah ada di: `database/database_backup_*.sqlite`

Jangan hapus dulu sebelum 100% yakin MySQL berjalan!

---

## ❓ Questions?

Jika ada masalah:

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Test database connection
php artisan tinker
>>> DB::connection('mysql')->select('SELECT VERSION()')
```

---

**Selamat! Aplikasi Anda sekarang siap untuk production dengan MySQL! 🚀**
