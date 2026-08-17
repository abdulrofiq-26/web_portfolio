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
       Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->text('bio')->nullable()->after('username');
            $table->string('primary_color')->default('#3b82f6')->after('bio'); // Default biru Tailwind
            $table->string('secondary_color')->default('#1e293b')->after('primary_color'); // Default slate gelap
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'bio', 'primary_color', 'secondary_color']);
        });
    }
};
