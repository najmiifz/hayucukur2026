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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status')->nullable()->after('status');
            // Menambahkan kolom payment_status untuk menyimpan status pembayaran
            // Kolom ini akan menyimpan status pembayaran seperti 'Pending', 'Completed', 'Failed', dll.
            // Nullable karena mungkin booking dibuat sebelum pembayaran dilakukan
            // dan akan diupdate setelah pembayaran selesai.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
