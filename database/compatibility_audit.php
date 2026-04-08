#!/usr/bin/env php
<?php
/**
 * ============================================================================
 * UP CIRENG: Laravel SQLite → MySQL Compatibility Audit
 * ============================================================================
 * 
 * Identifies potential bugs and edge cases when migrating from SQLite to MySQL.
 * This is an automated audit that scans your codebase for known issues.
 * 
 * KNOWN COMPATIBILITY ISSUES:
 * 1. Foreign Key Constraints (SQLite doesn't enforce by default)
 * 2. Type Coercion (SQLite loosely typed, MySQL strict)
 * 3. String to Number Conversion
 * 4. Boolean Handling (SQLite: 0/1, MySQL: TINYINT(1))
 * 5. JSON Data (SQLite TEXT, MySQL JSON type)
 * 6. DateTime Parsing (SQLite flexible, MySQL strict ISO-8601)
 * 7. NULL Comparisons (IS NULL behavior differs)
 * 8. Group By Behavior (MySQL 5.7+ strict, SQLite lenient)
 * 9. AUTO_INCREMENT gaps (MySQL vs SQLite behavior)
 * ============================================================================
 */

require __DIR__ . '/../vendor/autoload.php';

class CompatibilityAuditor {
    private $issues = [];
    private $warnings = 0;
    private $errors = 0;

    public function __construct() {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
    }

    public function audit() {
        echo "\n" . str_repeat('=', 80) . "\n";
        echo "🔍 LARAVEL COMPATIBILITY AUDIT: SQLite → MySQL\n";
        echo str_repeat('=', 80) . "\n\n";

        $this->auditModels();
        $this->auditControllers();
        $this->auditMigrations();
        $this->auditServiceProviders();

        return $this->printReport();
    }

    // ========================================================================
    // AUDIT 1: MODELS
    // ========================================================================

    private function auditModels() {
        echo "📦 Scanning Models...\n";
        echo str_repeat('-', 80) . "\n";

        $modelPath = app_path('Models');
        if (!is_dir($modelPath)) {
            echo "   ℹ️  No models directory\n\n";
            return;
        }

        $files = glob($modelPath . '/*.php');
        $issues = 0;

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $className = basename($file, '.php');

            // Check 1: Raw queries (potential type issues)
            if (preg_match('/DB::(?:select|insert|update|delete|statement|raw)\(/', $content)) {
                $this->addWarning(
                    "Model: $className",
                    "Uses raw database queries - verify type compatibility with MySQL",
                    "Raw queries may behave differently between SQLite and MySQL"
                );
                $issues++;
            }

            // Check 2: whereRaw or havingRaw
            if (preg_match('/->(?:whereRaw|havingRaw|orderByRaw)\(/', $content)) {
                $this->addWarning(
                    "Model: $className",
                    "Uses raw WHERE/HAVING/ORDER BY - test with MySQL strict mode",
                    "SQLite was lenient with GROUP BY rules, MySQL 5.7+ is strict"
                );
                $issues++;
            }

            // Check 3: Boolean fields
            if (preg_match('/protected \$casts.*?[\'"]boolean[\'"]/', $content, $m)) {
                echo "   ✓ $className: Properly casts boolean fields\n";
            }

            // Check 4: JSON fields
            if (preg_match('/protected \$casts.*?[\'"]json|array[\'"]/', $content)) {
                echo "   ✓ $className: Properly casts JSON/array fields\n";
            }
        }

        if ($issues === 0 && count($files) > 0) {
            echo "   ✅ Models look compatible\n";
        }
        echo "\n";
    }

    // ========================================================================
    // AUDIT 2: CONTROLLERS
    // ========================================================================

    private function auditControllers() {
        echo "🎮 Scanning Controllers...\n";
        echo str_repeat('-', 80) . "\n";

        $controllerPath = app_path('Http/Controllers');
        if (!is_dir($controllerPath)) {
            echo "   ℹ️  No controllers directory\n\n";
            return;
        }

        $files = glob($controllerPath . '/*.php');
        $issues = 0;

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $className = basename($file, '.php');

            // Check 1: Raw queries in controllers
            if (preg_match('/DB::(?:select|insert|update|delete|raw|statement)\(/', $content)) {
                $this->addWarning(
                    "Controller: $className",
                    "Contains raw database queries - ensure compatibility",
                    "Move to models or service classes for better testability"
                );
                $issues++;
            }

            // Check 2: Unvalidated data insertion
            if (preg_match('/->insert\(\$.*?\)/', $content)) {
                $this->addWarning(
                    "Controller: $className",
                    "Direct data insertion - ensure types match MySQL schema",
                    "MySQL is stricter with type validation than SQLite"
                );
                $issues++;
            }
        }

        if ($issues === 0 && count($files) > 0) {
            echo "   ✅ Controllers look compatible\n";
        }
        echo "\n";
    }

    // ========================================================================
    // AUDIT 3: MIGRATIONS
    // ========================================================================

    private function auditMigrations() {
        echo "📝 Scanning Migrations...\n";
        echo str_repeat('-', 80) . "\n";

        $migrationPath = database_path('migrations');
        if (!is_dir($migrationPath)) {
            echo "   ℹ️  No migrations directory\n\n";
            return;
        }

        $files = glob($migrationPath . '/*.php');
        $issues = 0;

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $fileName = basename($file, '.php');

            // Check 1: Foreign key constraints
            if (strpos($content, 'foreign(') !== false) {
                if (strpos($content, 'onDelete') === false) {
                    $this->addWarning(
                        "Migration: $fileName",
                        "Foreign key without onDelete constraint - verify behavior",
                        "MySQL enforces FK constraints more strictly than SQLite"
                    );
                    $issues++;
                }
            }

            // Check 2: Soft deletes
            if (strpos($content, 'softDeletes()') !== false) {
                echo "   ✓ $fileName: Uses softDeletes properly\n";
            }

            // Check 3: Timestamps
            if (strpos($content, 'timestamps()') !== false) {
                echo "   ✓ $fileName: Uses timestamps properly\n";
            }

            // Check 4: Nullable without default
            if (preg_match('/->nullable\(\)(?!->default)/', $content)) {
                // This is often OK, just informational
            }
        }

        if ($issues === 0 && count($files) > 0) {
            echo "   ✅ Migrations look compatible\n";
        }
        echo "\n";
    }

    // ========================================================================
    // AUDIT 4: SERVICE PROVIDERS & CONFIG
    // ========================================================================

    private function auditServiceProviders() {
        echo "⚙️  Scanning Configuration...\n";
        echo str_repeat('-', 80) . "\n";

        // Check 1: Database connections
        $dbConfig = config('database.connections');
        
        if (isset($dbConfig['mysql'])) {
            echo "   ✓ MySQL connection configured\n";
        } else {
            $this->addError(
                "Config: database.php",
                "MySQL connection not configured",
                "Add 'mysql' connection to config/database.php"
            );
        }

        // Check 2: Query logging
        if (config('app.debug') === true) {
            echo "   ⚠️  APP_DEBUG=true - disable in production\n";
            $this->warnings++;
        }

        // Check 3: Strict mode
        $sqlMode = env('DB_SQL_MODE', 'default');
        if ($sqlMode === 'default' || empty($sqlMode)) {
            echo "   ℹ️  DB_SQL_MODE not explicitly set - using MySQL defaults\n";
        }

        echo "\n";
    }

    // ========================================================================
    // ISSUE REPORTING
    // ========================================================================

    private function addWarning($title, $issue, $recommendation) {
        $this->warnings++;
        $this->issues[] = [
            'type' => 'warning',
            'title' => $title,
            'issue' => $issue,
            'recommendation' => $recommendation,
        ];
    }

    private function addError($title, $issue, $recommendation) {
        $this->errors++;
        $this->issues[] = [
            'type' => 'error',
            'title' => $title,
            'issue' => $issue,
            'recommendation' => $recommendation,
        ];
    }

    private function printReport() {
        echo "\n" . str_repeat('=', 80) . "\n";
        echo "📊 AUDIT REPORT\n";
        echo str_repeat('=', 80) . "\n\n";

        if (count($this->issues) === 0) {
            echo "✅ No compatibility issues found!\n";
            echo "Your application appears ready for MySQL.\n";
            echo "\n" . str_repeat('=', 80) . "\n";
            return true;
        }

        echo count($this->issues) . " issues found:\n";
        echo "  • Errors: " . $this->errors . "\n";
        echo "  • Warnings: " . $this->warnings . "\n";

        echo "\n" . str_repeat('-', 80) . "\n\n";

        foreach ($this->issues as $issue) {
            $icon = $issue['type'] === 'error' ? '❌' : '⚠️ ';
            echo "$icon {$issue['title']}\n";
            echo "   Issue: {$issue['issue']}\n";
            echo "   Fix: {$issue['recommendation']}\n\n";
        }

        echo "\n" . str_repeat('=', 80) . "\n";
        if ($this->errors > 0) {
            echo "❌ CRITICAL ISSUES FOUND - Address before migration\n";
        } else {
            echo "⚠️  WARNINGS FOUND - Review before production\n";
        }
        echo str_repeat('=', 80) . "\n";

        return $this->errors === 0;
    }
}

// ============================================================================
// EXECUTE AUDIT
// ============================================================================

try {
    $auditor = new CompatibilityAuditor();
    $success = $auditor->audit();
    exit($success ? 0 : 1);
} catch (Exception $e) {
    echo "\n❌ AUDIT ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}
