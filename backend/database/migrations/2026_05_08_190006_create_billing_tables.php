<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rent_runs', function (Blueprint $table) {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['queued', 'running', 'completed', 'failed'])->default('queued');
            $table->unsignedInteger('invoices_generated')->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->json('errors')->nullable();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('facility_id')->constrained();
            $table->foreignId('rent_run_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->date('due_date');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('paid_total', 14, 2)->default(0);
            $table->decimal('balance', 14, 2)->default(0);
            $table->enum('status', ['draft', 'issued', 'partially_paid', 'paid', 'cancelled', 'overdue'])->default('draft');
            $table->string('pdf_path')->nullable();
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'customer_id']);
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained();
            $table->string('description');
            $table->string('hsn_code')->nullable();
            $table->decimal('qty', 12, 2);
            $table->decimal('rate', 12, 2);
            $table->decimal('amount', 14, 2);
            $table->decimal('cgst_pct', 5, 2)->default(0);
            $table->decimal('sgst_pct', 5, 2)->default(0);
            $table->decimal('igst_pct', 5, 2)->default(0);
            $table->decimal('cgst_amount', 12, 2)->default(0);
            $table->decimal('sgst_amount', 12, 2)->default(0);
            $table->decimal('igst_amount', 12, 2)->default(0);
            $table->decimal('total', 14, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique();
            $table->string('reference')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->enum('mode', ['cash', 'cheque', 'neft', 'rtgs', 'upi', 'razorpay', 'card', 'other'])->default('cash');
            $table->string('gateway_reference')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->decimal('amount', 14, 2);
            $table->dateTime('paid_at');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('success');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['status']);
        });

        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->date('date');
            $table->decimal('amount', 14, 2);
            $table->string('reason');
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->nullable()->constrained();
            $table->string('category');
            $table->date('date');
            $table->decimal('amount', 14, 2);
            $table->string('vendor')->nullable();
            $table->string('reference')->nullable();
            $table->string('attachment_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['facility_id', 'category', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('rent_runs');
    }
};
