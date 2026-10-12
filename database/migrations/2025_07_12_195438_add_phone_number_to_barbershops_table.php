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
        Schema::table('barbershops', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('description'); // Menambahkan kolom phone_number setelah kolom location
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbershops', function (Blueprint $table) {
            //
        });
    }
};
