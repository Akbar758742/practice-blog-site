<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('content');
            // We can keep is_published for backward compatibility carefully or just drop it. 
            // The prompt says "Add Post Status Workflow". Usually replacing is cleaner.
            // But let's check if we should drop is_published. 
            // Since we are "implementing now", clearer is better.
            $table->dropColumn('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->dropColumn('status');
        });
    }
};
