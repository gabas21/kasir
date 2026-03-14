<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // cash, qris, transfer
            $table->integer('paid_amount')->nullable()->after('payment_method');
            $table->integer('change_amount')->nullable()->after('paid_amount');
            $table->text('notes')->nullable()->after('change_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'paid_amount', 'change_amount', 'notes']);
        });
    }
};
