<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('reference')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained();
            $table->foreignId('chamber_id')->nullable()->constrained();
            $table->foreignId('commodity_id')->constrained();
            $table->foreignId('packing_id')->nullable()->constrained();
            $table->foreignId('season_id')->nullable()->constrained();
            $table->decimal('planned_qty', 12, 2);
            $table->date('expected_intake_from')->nullable();
            $table->date('expected_intake_to')->nullable();
            $table->date('expected_release_by')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'partially_received', 'received', 'cancelled', 'closed'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'facility_id']);
        });

        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('lot_number')->unique();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('facility_id')->constrained();
            $table->foreignId('commodity_id')->constrained();
            $table->foreignId('packing_id')->nullable()->constrained();
            $table->foreignId('season_id')->nullable()->constrained();
            $table->date('intake_date');
            $table->decimal('intake_qty', 12, 2);
            $table->decimal('balance_qty', 12, 2);
            $table->decimal('gross_weight_kg', 12, 3)->nullable();
            $table->decimal('tare_weight_kg', 12, 3)->nullable();
            $table->decimal('net_weight_kg', 12, 3)->nullable();
            $table->string('grade')->nullable();
            $table->enum('status', ['active', 'partially_released', 'released', 'damaged', 'closed'])->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'customer_id']);
            $table->index(['facility_id', 'commodity_id']);
        });

        Schema::create('lot_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('storage_location_id')->constrained();
            $table->decimal('qty', 12, 2);
            $table->timestamps();
            $table->unique(['lot_id', 'storage_location_id']);
        });

        Schema::create('inward_notes', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('reference')->unique();
            $table->foreignId('lot_id')->constrained();
            $table->foreignId('booking_id')->nullable()->constrained();
            $table->dateTime('gate_in_at');
            $table->string('vehicle_no')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->decimal('weighbridge_in_kg', 12, 3)->nullable();
            $table->decimal('weighbridge_out_kg', 12, 3)->nullable();
            $table->json('photos')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('outward_notes', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('reference')->unique();
            $table->foreignId('lot_id')->constrained();
            $table->dateTime('gate_out_at');
            $table->decimal('qty', 12, 2);
            $table->string('vehicle_no')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->string('e_way_bill_no')->nullable();
            $table->json('photos')->nullable();
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained();
            $table->foreignId('from_location_id')->nullable()->constrained('storage_locations');
            $table->foreignId('to_location_id')->nullable()->constrained('storage_locations');
            $table->decimal('qty', 12, 2);
            $table->string('reason');
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('performed_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained();
            $table->dateTime('checked_at');
            $table->string('grade')->nullable();
            $table->decimal('sample_qty', 10, 2)->nullable();
            $table->decimal('rejected_qty', 10, 2)->default(0);
            $table->json('parameters')->nullable();
            $table->json('photos')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('gate_passes', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('number')->unique();
            $table->enum('type', ['in', 'out']);
            $table->foreignId('lot_id')->nullable()->constrained();
            $table->string('vehicle_no')->nullable();
            $table->string('pdf_path')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_passes');
        Schema::dropIfExists('quality_checks');
        Schema::dropIfExists('movements');
        Schema::dropIfExists('outward_notes');
        Schema::dropIfExists('inward_notes');
        Schema::dropIfExists('lot_locations');
        Schema::dropIfExists('lots');
        Schema::dropIfExists('bookings');
    }
};
