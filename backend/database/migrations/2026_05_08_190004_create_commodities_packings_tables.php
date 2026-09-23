<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commodity_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->foreignId('category_id')->nullable()->constrained('commodity_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('hsn_code')->nullable();
            $table->decimal('gst_percent', 5, 2)->default(0);
            $table->decimal('recommended_temp_min_c', 5, 2)->nullable();
            $table->decimal('recommended_temp_max_c', 5, 2)->nullable();
            $table->decimal('recommended_humidity_min', 5, 2)->nullable();
            $table->decimal('recommended_humidity_max', 5, 2)->nullable();
            $table->unsignedInteger('shelf_life_days')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('body_html')->nullable();
            $table->json('faqs')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['is_published', 'is_featured']);
        });

        Schema::create('packings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('default_weight_kg', 10, 3)->default(0);
            $table->decimal('default_volume_m3', 10, 3)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('commodity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('packing_id')->nullable()->constrained()->nullOnDelete();
            $table->string('chamber_type')->nullable();
            $table->decimal('rate', 12, 2);
            $table->enum('basis', ['per_bag_per_month', 'per_mt_per_day', 'per_mt_per_month', 'flat'])->default('per_bag_per_month');
            $table->decimal('min_charge', 12, 2)->default(0);
            $table->decimal('advance_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['commodity_id', 'season_id', 'chamber_type']);
        });

        Schema::create('tariff_surcharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tariff_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['flat', 'percent'])->default('flat');
            $table->boolean('mandatory')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariff_surcharges');
        Schema::dropIfExists('tariffs');
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('packings');
        Schema::dropIfExists('commodities');
        Schema::dropIfExists('commodity_categories');
    }
};
