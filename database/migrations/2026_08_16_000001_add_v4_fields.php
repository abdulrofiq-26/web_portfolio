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
            $table->text('home_bio')->nullable();
            $table->string('hero_experience_years')->nullable();
            $table->string('hero_icon')->nullable();
            $table->json('section_visibility')->nullable();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('github_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['home_bio', 'hero_experience_years', 'hero_icon', 'section_visibility']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('github_url');
        });
    }
};
