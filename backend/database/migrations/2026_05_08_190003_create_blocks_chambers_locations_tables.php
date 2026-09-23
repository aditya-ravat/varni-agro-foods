<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['facility_id', 'code']);
        });

        Schema::create('chambers', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->foreignId('block_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['cold_room', 'freezer', 'ca', 'ripening', 'blast', 'ambient'])->default('cold_room');
            $table->decimal('temp_min_c', 5, 2)->nullable();
            $table->decimal('temp_max_c', 5, 2)->nullable();
            $table->decimal('humidity_min_pct', 5, 2)->nullable();
            $table->decimal('humidity_max_pct', 5, 2)->nullable();
            $table->decimal('gross_capacity_mt', 12, 2)->default(0);
            $table->decimal('net_capacity_mt', 12, 2)->default(0);
            $table->decimal('current_occupancy_mt', 12, 2)->default(0);
            $table->string('refrigeration_unit')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['facility_id', 'code']);
            $table->index(['type', 'status']);
        });

        Schema::create('storage_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamber_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->unsignedInteger('row')->default(0);
            $table->unsignedInteger('level')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->decimal('capacity_units', 10, 2)->default(0);
            $table->decimal('used_units', 10, 2)->default(0);
            $table->string('qr_token')->unique()->nullable();
            $table->enum('status', ['empty', 'partial', 'full', 'blocked'])->default('empty');
            $table->timestamps();
            $table->unique(['chamber_id', 'code']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_locations');
        Schema::dropIfExists('chambers');
        Schema::dropIfExists('blocks');
    }
};
