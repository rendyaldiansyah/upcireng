-- ============================================================================
-- UP CIRENG - MySQL 8.0+ Setup & Production-Grade Security
-- ============================================================================
-- INSTALLATION:
--   1. Install MySQL 8.0 Community Server
--   2. Set bind-address=127.0.0.1 in /etc/mysql/mysql.cnf (unix) or
--      my.ini (windows) untuk limit ke localhost saja
--   3. Run: mysql -u root -p < database/mysql_setup.sql
--
-- SECURITY CHECKLIST (Manual):
--   ✓ Change root password setelah setup
--   ✓ Disable remote root login: DELETE FROM mysql.user WHERE User='root' AND Host='%';
--   ✓ Set bind-address=127.0.0.1 BEFORE production deployment
--   ✓ Enable SSL/TLS MySQL connection (~/.my.cnf)
--   ✓ Monitor mysql.general_log untuk audit trail
-- ============================================================================

-- ============================================================================
-- STEP 1: Create Database dengan UTF8MB4 (emoji support, production-ready)
-- ============================================================================
CREATE DATABASE IF NOT EXISTS upcireng_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

SHOW CREATE DATABASE upcireng_db;

-- ============================================================================
-- STEP 2: Create Restricted User (LEAST PRIVILEGE - production security)
-- ============================================================================
-- Host restriction: '127.0.0.1' = localhost only (SECURE)
-- Untuk jaringan VPS: ganti '127.0.0.1' dengan server-specific IP atau use SSH tunnel
-- JANGAN GUNAKAN '%' di production!
-- ============================================================================

DROP USER IF EXISTS 'upcireng_user'@'127.0.0.1';

CREATE USER 'upcireng_user'@'127.0.0.1' 
    IDENTIFIED BY 'UPCireng@2024#Secure!'
    PASSWORD EXPIRE INTERVAL 90 DAY;          -- Force password change every 90 days

-- ============================================================================
-- STEP 3: Verify User Created
-- ============================================================================
SELECT User, Host, authentication_string FROM mysql.user 
WHERE User = 'upcireng_user';

-- ============================================================================
-- STEP 4: Grant MINIMAL Privileges (SQLite compatibility + app needs)
-- ============================================================================
-- SELECT    : Read data
-- INSERT    : Create orders, logs
-- UPDATE    : Modify orders, products
-- DELETE    : Soft deletes (handled by app)
-- CREATE    : Auto migrations
-- ALTER     : Schema updates
-- DROP      : Migration down
-- INDEX     : Performance tuning
-- REFERENCES: Foreign keys
-- 
-- DENIED: FILE, LOAD_FILE, INTO OUTFILE, GRANT, SUPER, RELOAD, PROCESS, etc.
-- ============================================================================

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX, REFERENCES
    ON upcireng_db.*
    TO 'upcireng_user'@'127.0.0.1';

-- ============================================================================
-- STEP 5: Verify Privileges (should show exactly what we granted)
-- ============================================================================
SHOW GRANTS FOR 'upcireng_user'@'127.0.0.1';

-- ============================================================================
-- STEP 6: Apply Security Settings
-- ============================================================================
FLUSH PRIVILEGES;

-- ============================================================================
-- STEP 7: Configure Database for Production
-- ============================================================================
USE upcireng_db;

-- Strict mode: catch data corruption early
SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- Connection limits (adjust sesuai expected concurrent users)
SET GLOBAL max_connections = 500;
SET GLOBAL max_user_connections = 100;

-- Timeout settings (prevent hanging connections)
SET GLOBAL wait_timeout = 900;           -- 15 minutes
SET GLOBAL interactive_timeout = 900;
SET GLOBAL net_read_timeout = 30;
SET GLOBAL net_write_timeout = 30;

-- Performance (recommended untuk production)
SET GLOBAL max_allowed_packet = 67108864;  -- 64MB
SET GLOBAL tmp_table_size = 67108864;
SET GLOBAL max_heap_table_size = 67108864;

-- ============================================================================
-- STEP 8: Verify Configuration
-- ============================================================================
SHOW VARIABLES LIKE 'character_set%';
SHOW VARIABLES LIKE 'collation%';
SHOW VARIABLES LIKE 'sql_mode';
SHOW VARIABLES LIKE 'max_connections%';
SHOW VARIABLES LIKE 'wait_timeout';

-- ============================================================================
-- SUCCESS
-- ============================================================================
SELECT '✅ MySQL Setup Complete! Ready for Laravel Migration.' AS Status;
SELECT CONCAT('Database: ', database(), ' | Charset: UTF8MB4 | Collation: utf8mb4_unicode_ci') AS Config;
