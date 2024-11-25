<?php

use App\Models\Product;
use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Product::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('price')->default(0);
            $table->float('total_paid')->default(0);
            $table->float('transaction_fees')->default(0);
            $table->enum('payment_status', ['pending', 'failed', 'succeed', 'refunded', 'refunding'])->default('pending');
            $table->enum('order_status', ['pending', 'canceled', 'ordered', 'delivered'])->default('pending');
            $table->text('txn_id')->nullable();
            $table->string('refund_id')->nullable();
            $table->float('refund_amount')->default(0);
            $table->timestamp('refund_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
