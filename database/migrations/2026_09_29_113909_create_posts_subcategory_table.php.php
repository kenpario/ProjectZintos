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
        Schema::create('post_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_category_id')->constrained('post_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 30);
            $table->string('description', 50);
            $table->timestamps();
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('post_subcategory_id')->constrained('post_subcategories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('post_subcategory_id');
        });
        Schema::dropIfExists('post_subcategories');
    }
};
