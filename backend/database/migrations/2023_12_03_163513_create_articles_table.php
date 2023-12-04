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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('from_api', 32);
            $table->string('title', 128);
            $table->string('category', 32);
            $table->string('author', 64);
            $table->string('source', 64);
            $table->string('description', 512);
            $table->text('body');
            $table->timestamp('published_at');

            $table->index(['title', 'source', 'author', 'category', 'published_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
