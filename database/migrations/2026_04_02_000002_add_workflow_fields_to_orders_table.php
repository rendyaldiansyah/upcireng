<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'reference')) {
                $table->string('reference')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('orders', 'items')) {
                $table->json('items')->nullable()->after('total_price');
            }

            if (!Schema::hasColumn('orders', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'sync_status')) {
                $table->string('sync_status')->default('pending')->after('status');
            }

            if (!Schema::hasColumn('orders', 'sync_error')) {
                $table->text('sync_error')->nullable()->after('sync_status');
            }

            if (!Schema::hasColumn('orders', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('orders', 'deleted_by_customer_at')) {
                $table->timestamp('deleted_by_customer_at')->nullable()->after('completed_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            foreach ([
                'reference',
                'items',
                'payment_proof_path',
                'sync_status',
                'sync_error',
                'cancel_reason',
                'deleted_by_customer_at',
            ] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
