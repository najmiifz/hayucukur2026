<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->json('services')->nullable()->after('barbershop_id');
            $table->string('order_id')->nullable()->unique()->after('payment_method');
            $table->string('snap_token')->nullable()->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->dropColumn(['services', 'order_id', 'snap_token']);
        });
    }
};