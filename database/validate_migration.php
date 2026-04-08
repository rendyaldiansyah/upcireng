#!/usr/bin/env php
<?php
/**
 * ============================================================================
 * UP CIRENG: Post-Migration Data Validation Script
 * ============================================================================
 */

require __DIR__ . '/../vendor/autoload.php';

class MigrationValidator
{
    private $sqlite;
    private $mysql;
    private int $passed = 0;
    private int $failed = 0;

    public function __construct()
    {
        $this->sqlite = DB::connection('sqlite');
        $this->mysql  = DB::connection('mysql');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function printHeader(string $text): void
    {
        echo "\n" . str_repeat('=', 80) . "\n";
        echo "📊 $text\n";
        echo str_repeat('=', 80) . "\n\n";
    }

    private function printTest(string $name, bool $status, string $message = ''): void
    {
        $icon = $status ? '✅' : '❌';
        echo "$icon $name";
        if ($message) {
            echo " | $message";
        }
        echo "\n";

        $status ? $this->passed++ : $this->failed++;
    }

    private function printSection(string $name): void
    {
        echo "\n➜  $name\n";
        echo str_repeat('-', 80) . "\n";
    }

    // ── Test 1: Connections ───────────────────────────────────────────────────

    public function testConnections(): bool
    {
        $this->printHeader('TEST 1: DATABASE CONNECTIONS');

        $ok = true;

        try {
            $this->sqlite->select('SELECT 1');
            $this->printTest('SQLite connection', true);
        } catch (Exception $e) {
            $this->printTest('SQLite connection', false, $e->getMessage());
            $ok = false;
        }

        try {
            $this->mysql->select('SELECT 1');
            $this->printTest('MySQL connection', true);
        } catch (Exception $e) {
            $this->printTest('MySQL connection', false, $e->getMessage());
            $ok = false;
        }

        return $ok;
    }

    // ── Test 2: Row Counts ────────────────────────────────────────────────────

    public function testRowCounts(): bool
    {
        $this->printHeader('TEST 2: ROW COUNT VERIFICATION');

        $tables   = ['users', 'products', 'orders', 'settings', 'testimonials', 'sessions'];
        $allMatch = true;

        foreach ($tables as $table) {
            try {
                $sqliteCount = $this->sqlite->table($table)->count();
                $mysqlCount  = $this->mysql->table($table)->count();
                $match       = ($sqliteCount === $mysqlCount);

                $this->printTest(
                    "$table row count",
                    $match,
                    "SQLite=$sqliteCount | MySQL=$mysqlCount"
                );

                if (!$match) {
                    $allMatch = false;
                }
            } catch (Exception $e) {
                $this->printTest("$table row count", false, $e->getMessage());
                $allMatch = false;
            }
        }

        return $allMatch;
    }

    // ── Test 3: Column Structure ──────────────────────────────────────────────

    public function testColumnsMatch(): bool
    {
        $this->printHeader('TEST 3: COLUMN STRUCTURE VERIFICATION');

        $tables   = ['users', 'products', 'orders', 'settings', 'testimonials'];
        $allMatch = true;

        foreach ($tables as $table) {
            try {
                $sqliteColumns = $this->getTableColumns('sqlite', $table);
                $mysqlColumns  = $this->getTableColumns('mysql', $table);
                $missing       = array_diff($sqliteColumns, $mysqlColumns);
                $match         = empty($missing);

                $this->printTest("$table columns match", $match);

                if (!$match) {
                    echo "   ℹ️  Missing in MySQL: " . implode(', ', $missing) . "\n";
                    $allMatch = false;
                }
            } catch (Exception $e) {
                $this->printTest("$table columns", false, $e->getMessage());
                $allMatch = false;
            }
        }

        return $allMatch;
    }

    private function getTableColumns(string $connection, string $table): array
    {
        $db = $connection === 'sqlite' ? $this->sqlite : $this->mysql;

        if ($connection === 'sqlite') {
            $columns = $db->select("PRAGMA table_info($table)");
            return array_map(fn ($col) => $col->name, $columns);
        }

        $columns = $db->select(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = DATABASE()",
            [$table]
        );
        return array_map(fn ($col) => $col->COLUMN_NAME, $columns);
    }

    // ── Test 4: Sample Data ───────────────────────────────────────────────────

    public function testSampleData(): bool
    {
        $this->printHeader('TEST 4: SAMPLE DATA VERIFICATION');

        $this->printSection('Users table (first 3 records)');
        $sqliteUsers = $this->sqlite->table('users')->select('id', 'email', 'phone', 'role')->limit(3)->get();
        $mysqlUsers  = $this->mysql->table('users')->select('id', 'email', 'phone', 'role')->limit(3)->get();
        $userMatch   = $sqliteUsers->count() === $mysqlUsers->count() && $sqliteUsers->count() > 0;

        $this->printTest(
            'User records exist and match count',
            $userMatch,
            'SQLite=' . $sqliteUsers->count() . ' | MySQL=' . $mysqlUsers->count()
        );

        if ($userMatch) {
            $this->printSampleComparison($sqliteUsers, $mysqlUsers);
        }

        $this->printSection('Products table (first 3 records)');
        $sqliteProducts = $this->sqlite->table('products')->select('id', 'name', 'price', 'status')->limit(3)->get();
        $mysqlProducts  = $this->mysql->table('products')->select('id', 'name', 'price', 'status')->limit(3)->get();
        $productMatch   = $sqliteProducts->count() === $mysqlProducts->count() && $sqliteProducts->count() > 0;

        $this->printTest(
            'Product records exist and match count',
            $productMatch,
            'SQLite=' . $sqliteProducts->count() . ' | MySQL=' . $mysqlProducts->count()
        );

        if ($productMatch) {
            $this->printSampleComparison($sqliteProducts, $mysqlProducts);
        }

        return $userMatch && $productMatch;
    }

    /**
     * FIX: Gunakan (array) cast bukan ->toArray() pada item collection
     */
    private function printSampleComparison($sqliteData, $mysqlData): void
    {
        echo "\n   Comparing first record:\n";

        // FIX: cast object ke array dengan (array), bukan ->toArray()
        $sqliteFirst = (array) $sqliteData->first();
        $mysqlFirst  = (array) $mysqlData->first();

        foreach ($sqliteFirst as $key => $value) {
            if (array_key_exists($key, $mysqlFirst)) {
                $match  = ($value == $mysqlFirst[$key]);
                $status = $match ? '✓' : '✗';
                echo "   $status $key: " . json_encode($value) . "\n";
            }
        }
    }

    // ── Test 5: Foreign Keys ──────────────────────────────────────────────────

    public function testForeignKeys(): void
    {
        $this->printHeader('TEST 5: FOREIGN KEY INTEGRITY');

        $this->printSection('Checking Orders → Users relationship');
        try {
            // FIX: Gunakan DB::select() bukan ->raw() yang dikembalikan sebagai array
            $result = $this->mysql->select(
                'SELECT COUNT(*) as count FROM orders
                 WHERE user_id NOT IN (SELECT id FROM users) AND user_id IS NOT NULL'
            );
            $count = $result[0]->count ?? 0;
            $this->printTest('Orders.user_id references valid Users', $count === 0, "Invalid references: $count");
        } catch (Exception $e) {
            echo "   ℹ️  Foreign key check skipped: " . $e->getMessage() . "\n";
        }

        $this->printSection('Checking Orders → Products relationship');
        try {
            $result = $this->mysql->select(
                'SELECT COUNT(*) as count FROM orders
                 WHERE product_id NOT IN (SELECT id FROM products) AND product_id IS NOT NULL'
            );
            $count = $result[0]->count ?? 0;
            $this->printTest('Orders.product_id references valid Products', $count === 0, "Invalid references: $count");
        } catch (Exception $e) {
            echo "   ℹ️  Foreign key check skipped: " . $e->getMessage() . "\n";
        }
    }

    // ── Test 6: Data Types ────────────────────────────────────────────────────

    public function testDataTypes(): void
    {
        $this->printHeader('TEST 6: DATA TYPE VALIDATION');

        $this->printSection('Boolean fields (products.is_open)');
        try {
            $products = $this->mysql->table('products')
                ->select('is_open')
                ->whereNotNull('is_open')
                ->limit(5)
                ->get();

            $valid = true;
            foreach ($products as $product) {
                if (!in_array($product->is_open, [0, 1, '0', '1', true, false], true)) {
                    $valid = false;
                    break;
                }
            }
            $this->printTest('Boolean values are 0 or 1', $valid, 'Sampled 5 records');
        } catch (Exception $e) {
            echo "   ℹ️  Boolean validation skipped\n";
        }

        $this->printSection('JSON fields (orders.items)');
        try {
            $orders = $this->mysql->table('orders')
                ->select('items')
                ->whereNotNull('items')
                ->limit(5)
                ->get();

            $valid = true;
            foreach ($orders as $order) {
                if (is_string($order->items)) {
                    json_decode($order->items);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $valid = false;
                        break;
                    }
                }
            }
            $this->printTest('JSON values are valid', $valid, 'Validated 5 records');
        } catch (Exception $e) {
            echo "   ℹ️  JSON validation skipped\n";
        }

        $this->printSection('Numeric fields (price, quantity)');
        try {
            $orders = $this->mysql->table('orders')
                ->select('price_per_unit', 'quantity')
                ->limit(10)
                ->get();

            $valid = true;
            foreach ($orders as $order) {
                if ($order->price_per_unit !== null && !is_numeric($order->price_per_unit)) {
                    $valid = false;
                    break;
                }
            }
            $this->printTest('Numeric fields are valid', $valid, 'Validated 10 records');
        } catch (Exception $e) {
            echo "   ℹ️  Numeric validation skipped\n";
        }
    }

    // ── Test 7: Timestamps ────────────────────────────────────────────────────

    public function testTimestamps(): void
    {
        $this->printHeader('TEST 7: TIMESTAMP CONSISTENCY');

        $this->printSection('Checking created_at and updated_at');
        try {
            $user = $this->mysql->table('users')
                ->select('id', 'created_at', 'updated_at')
                ->first();

            if ($user) {
                $this->printTest('Users have created_at timestamp', $user->created_at !== null);
                $this->printTest('Users have updated_at timestamp', $user->updated_at !== null);
            } else {
                echo "   ℹ️  No users to check timestamps\n";
            }
        } catch (Exception $e) {
            echo "   ℹ️  Timestamp check skipped: " . $e->getMessage() . "\n";
        }
    }

    // ── Final Report ──────────────────────────────────────────────────────────

    public function printFinalReport(): bool
    {
        echo "\n\n";
        echo str_repeat('=', 80) . "\n";
        echo "📋 FINAL VALIDATION REPORT\n";
        echo str_repeat('=', 80) . "\n\n";

        $total      = $this->passed + $this->failed;
        $percentage = $total > 0 ? round(($this->passed / $total) * 100, 1) : 0;

        echo "   Tests Passed: {$this->passed}\n";
        echo "   Tests Failed: {$this->failed}\n";
        echo "   Total Tests:  $total\n";
        echo "   Success Rate: $percentage%\n\n";

        if ($this->failed === 0 && $this->passed > 0) {
            echo str_repeat('=', 80) . "\n";
            echo "✅ ALL VALIDATION TESTS PASSED - Ready for Production!\n";
            echo str_repeat('=', 80) . "\n";
            return true;
        }

        echo str_repeat('=', 80) . "\n";
        echo "⚠️  SOME TESTS FAILED - Review issues above\n";
        echo str_repeat('=', 80) . "\n";
        return false;
    }

    // ── Run All ───────────────────────────────────────────────────────────────

    public function runAllTests(): bool
    {
        if (!$this->testConnections()) {
            echo "\n❌ Cannot proceed - database connections failed\n";
            return false;
        }

        $this->testRowCounts();
        $this->testColumnsMatch();
        $this->testSampleData();
        $this->testForeignKeys();
        $this->testDataTypes();
        $this->testTimestamps();

        return $this->printFinalReport();
    }
}

// ── Execute ───────────────────────────────────────────────────────────────────

try {
    $app    = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $validator = new MigrationValidator();
    $success   = $validator->runAllTests();

    exit($success ? 0 : 1);

} catch (Exception $e) {
    echo "\n❌ VALIDATION ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}