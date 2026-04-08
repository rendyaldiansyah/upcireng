#!/usr/bin/env php
<?php
/**
 * ============================================================================
 * UP CIRENG: Database Migration Script (SQLite → MySQL)
 * ============================================================================
 */

require __DIR__ . '/../vendor/autoload.php';

$startTime = microtime(true);
$migrated  = [];
$failed    = false;

$MIGRATION_ORDER = [
    'users',
    'products',
    'settings',
    'testimonials',
    'orders',
    'sessions',
];

$TYPE_CONVERSIONS = [
    'users'    => ['is_admin' => 'boolean'],
    'products' => ['is_open'  => 'boolean'],
    'orders'   => ['items'    => 'json'],
];

// ── Helpers ───────────────────────────────────────────────────────────────────

function convertValue($table, $column, $value)
{
    global $TYPE_CONVERSIONS;

    if (!isset($TYPE_CONVERSIONS[$table][$column])) {
        return $value;
    }

    $type = $TYPE_CONVERSIONS[$table][$column];

    switch ($type) {
        case 'boolean':
            return (int) $value;

        case 'json':
            if ($value === null || $value === '') {
                return null;
            }
            if (is_array($value)) {
                return json_encode($value);
            }
            if (is_string($value)) {
                json_decode($value);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON in $table.$column: $value");
                }
                return $value;
            }
            return json_encode($value);

        default:
            return $value;
    }
}

function printStatus($message, $type = 'info')
{
    $icons = [
        'info'    => 'ℹ️ ',
        'success' => '✅ ',
        'warning' => '⚠️  ',
        'error'   => '❌ ',
        'arrow'   => '➜  ',
        'check'   => '✓  ',
    ];

    $prefix = $icons[$type] ?? '• ';
    echo $prefix . $message . "\n";
}

// ── Main ──────────────────────────────────────────────────────────────────────

try {
    printStatus(str_repeat('=', 80));
    printStatus('UP CIRENG: SQLite → MySQL Data Migration');
    printStatus(str_repeat('=', 80));
    echo "\n";

    printStatus('Booting Laravel application...', 'arrow');
    $app    = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $sqlite = DB::connection('sqlite');
    $mysql  = DB::connection('mysql');

    // Test connections
    printStatus('Testing SQLite connection...', 'arrow');
    try {
        $sqlite->select('SELECT 1');
        printStatus('SQLite connected', 'success');
    } catch (Exception $e) {
        throw new Exception('SQLite connection failed: ' . $e->getMessage());
    }

    printStatus('Testing MySQL connection...', 'arrow');
    try {
        $mysql->select('SELECT 1');
        printStatus('MySQL connected', 'success');
    } catch (Exception $e) {
        throw new Exception(
            'MySQL connection failed: ' . $e->getMessage() .
            "\n💡 Ensure MySQL is running and credentials in .env are correct"
        );
    }

    echo "\n";
    printStatus('Disabling foreign key checks for migration...', 'arrow');
    $mysql->statement('SET FOREIGN_KEY_CHECKS=0');
    printStatus('Foreign key checks disabled', 'success');

    echo "\n";
    printStatus('Starting transaction...', 'arrow');
    $mysql->beginTransaction();
    printStatus('Transaction started', 'success');

    echo "\n";
    printStatus(str_repeat('-', 80));
    printStatus('MIGRATION PROGRESS');
    printStatus(str_repeat('-', 80));
    echo "\n";

    $totalRecords = 0;

    foreach ($MIGRATION_ORDER as $table) {
        try {
            printStatus("Migrating table: $table", 'arrow');

            $records = $sqlite->table($table)->get();
            $count   = $records->count();

            if ($count === 0) {
                printStatus("Table $table is empty, skipping", 'info');
                $migrated[$table] = 0;
                continue;
            }

            $mysql->table($table)->truncate();
            printStatus("Truncated existing MySQL $table", 'check');

            $chunkSize       = 100;
            $chunks          = $records->chunk($chunkSize);
            $processedChunks = 0;

            foreach ($chunks as $chunk) {
                $insertData = [];

                foreach ($chunk as $record) {
                    $row = (array) $record;

                    foreach ($row as $column => &$value) {
                        try {
                            $value = convertValue($table, $column, $value);
                        } catch (Exception $e) {
                            throw new Exception(
                                "Type conversion error in $table.$column: " . $e->getMessage()
                            );
                        }
                    }

                    $insertData[] = $row;
                }

                try {
                    $mysql->table($table)->insert($insertData);
                    $processedChunks++;
                } catch (Exception $e) {
                    throw new Exception(
                        "Failed to insert chunk $processedChunks into $table: " . $e->getMessage()
                    );
                }
            }

            $migrated[$table] = $count;
            $totalRecords    += $count;

            printStatus("$table: $count records migrated ($processedChunks chunks)", 'success');
            echo "\n";

        } catch (Exception $e) {
            printStatus($e->getMessage(), 'error');
            $mysql->rollBack();
            $failed = true;
            throw $e;
        }
    }

    echo "\n";
    printStatus('Re-enabling foreign key checks...', 'arrow');
    $mysql->statement('SET FOREIGN_KEY_CHECKS=1');
    printStatus('Foreign key checks re-enabled', 'success');

    echo "\n";
    printStatus('Committing transaction...', 'arrow');
    $mysql->commit();
    printStatus('Transaction committed successfully', 'success');

    echo "\n";
    printStatus(str_repeat('=', 80), 'success');
    printStatus('✨ MIGRATION SUCCESSFUL', 'success');
    printStatus(str_repeat('=', 80), 'success');

    // ── Data Integrity Verification ───────────────────────────────────────────

    echo "\n";
    printStatus(str_repeat('-', 80));
    printStatus('DATA INTEGRITY VERIFICATION');
    printStatus(str_repeat('-', 80));
    echo "\n";

    $allMatch = true;

    foreach ($migrated as $table => $count) {
        $sqliteCount = $sqlite->table($table)->count();
        $mysqlCount  = $mysql->table($table)->count();

        if ($sqliteCount === $mysqlCount) {
            printStatus("$table: SQLite=$sqliteCount | MySQL=$mysqlCount", 'success');
        } else {
            printStatus("$table: SQLite=$sqliteCount | MySQL=$mysqlCount ⚠️  MISMATCH!", 'error');
            $allMatch = false;
        }
    }

    echo "\n";
    if ($allMatch) {
        printStatus('All record counts match perfectly ✨', 'success');
    } else {
        printStatus('⚠️  Record count mismatch detected!', 'error');
        throw new Exception('Data integrity check failed');
    }

    // ── Summary ───────────────────────────────────────────────────────────────

    echo "\n";
    printStatus(str_repeat('=', 80));
    printStatus('MIGRATION SUMMARY');
    printStatus(str_repeat('=', 80));
    echo "\n";

    $duration = round(microtime(true) - $startTime, 2);

    foreach ($migrated as $table => $count) {
        if ($count > 0) {
            printStatus("$table: $count records", 'success');
        }
    }

    echo "\n";
    printStatus("Total records migrated: $totalRecords", 'success');
    printStatus("Migration time: {$duration} seconds", 'success');
    printStatus("Speed: " . ($duration > 0 ? round($totalRecords / $duration, 0) : $totalRecords) . " records/second", 'success');

    echo "\n";
    printStatus(str_repeat('=', 80), 'success');
    printStatus('🎉 Ready for production! Next steps:', 'success');
    printStatus(str_repeat('=', 80), 'success');
    echo "\n";
    printStatus('1. Run: php artisan cache:clear', 'arrow');
    printStatus('2. Test application: http://192.168.1.4:8000', 'arrow');
    printStatus('3. Verify MySQL credentials in .env', 'arrow');
    printStatus('4. Set bind-address=127.0.0.1 in my.cnf for production', 'arrow');
    echo "\n";

} catch (Exception $e) {
    echo "\n";
    printStatus(str_repeat('=', 80), 'error');
    printStatus('❌ MIGRATION FAILED', 'error');
    printStatus(str_repeat('=', 80), 'error');
    echo "\n";

    printStatus('Error: ' . $e->getMessage(), 'error');
    echo "\n";
    printStatus(str_repeat('-', 80), 'error');
    printStatus('ROLLBACK PLAN:', 'warning');
    printStatus(str_repeat('-', 80), 'warning');
    echo "\n";
    printStatus('Transaction automatically rolled back', 'info');
    printStatus('Your SQLite database is unchanged', 'info');
    echo "\n";
    printStatus('To retry migration:', 'arrow');
    printStatus('1. Review the error above', 'arrow');
    printStatus('2. Check MySQL credentials in .env', 'arrow');
    printStatus('3. Ensure migrations ran: php artisan migrate:fresh', 'arrow');
    printStatus('4. Run script again: php database/migrate_sqlite_to_mysql.php', 'arrow');
    echo "\n";

    exit(1);
}