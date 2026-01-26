<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Adds soft deletes to posts, comments, and pages tables for data recovery
     */
    public function up(): void
    {
        // Add soft deletes to posts table
        if (!Schema::hasColumn('posts', 'deleted_at')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to comments table
        if (!Schema::hasColumn('comments', 'deleted_at')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to pages table
        if (!Schema::hasColumn('pages', 'deleted_at')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
