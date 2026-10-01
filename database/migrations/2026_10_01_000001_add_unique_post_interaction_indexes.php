<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_likes', function (Blueprint $table) {
            $table->unique(['post_id', 'user_id']);
        });

        Schema::table('post_views', function (Blueprint $table) {
            $table->unique(['post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('post_likes', function (Blueprint $table) {
            $table->dropUnique('post_likes_post_id_user_id_unique');
        });

        Schema::table('post_views', function (Blueprint $table) {
            $table->dropUnique('post_views_post_id_user_id_unique');
        });
    }
};