<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Accounts
        |--------------------------------------------------------------------------
        */
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            // User who originally created/owns the account.
            // The account still owns all saved data.
            $table->foreignId('owner_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Account users
        |--------------------------------------------------------------------------
        | Users can belong to multiple accounts and switch between them.
        */
        Schema::create('account_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('role')->default('member');
            // owner, admin, member

            $table->timestamps();

            $table->unique(['account_id', 'user_id']);
            $table->index(['user_id', 'account_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        | Categories belong to an account, not to a user.
        */
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug');

            $table->string('color')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['account_id', 'slug']);
            $table->index(['account_id', 'parent_id']);
            $table->index(['account_id', 'sort_order']);
        });

        /*
        |--------------------------------------------------------------------------
        | Saved items
        |--------------------------------------------------------------------------
        | Links, videos and images belong to an account.
        |
        | created_by_user_id is optional audit info only.
        | It does not define ownership.
        */
        Schema::create('saved_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type');
            // link, video, image

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // Original URL submitted by the user
            $table->text('source_url')->nullable();

            // Final URL after redirects
            $table->text('final_url')->nullable();

            // Preview image / thumbnail
            $table->text('image_url')->nullable();

            // Site favicon
            $table->text('favicon_url')->nullable();

            $table->string('site_name')->nullable();
            $table->string('provider_name')->nullable();

            // For uploaded images/files
            $table->string('file_path')->nullable();
            $table->string('mime_type')->nullable();

            // Extra metadata:
            // width, height, duration, oEmbed data, OpenGraph data, etc.
            $table->json('metadata')->nullable();

            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_archived')->default(false);

            $table->timestamp('fetched_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['account_id', 'type']);
            $table->index(['account_id', 'category_id']);
            $table->index(['account_id', 'created_at']);
            $table->index(['account_id', 'is_favorite']);
            $table->index(['account_id', 'is_archived']);
        });

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        | Optional but useful for filtering saved items.
        | Tags also belong to an account.
        */
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');

            $table->timestamps();

            $table->unique(['account_id', 'slug']);
            $table->index(['account_id', 'name']);
        });

        /*
        |--------------------------------------------------------------------------
        | Saved item tags
        |--------------------------------------------------------------------------
        */
        Schema::create('saved_item_tag', function (Blueprint $table) {
            $table->id();

            $table->foreignId('saved_item_id')
                ->constrained('saved_items')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['saved_item_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_item_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('saved_items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('account_user');
        Schema::dropIfExists('accounts');
    }
};
