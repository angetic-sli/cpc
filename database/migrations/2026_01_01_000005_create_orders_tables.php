<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void {
Schema::create('orders',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->string('number')->unique();$t->string('status')->default('pending');$t->string('payment_status')->default('unpaid');$t->string('payment_method')->default('cash_on_delivery');$t->decimal('subtotal',12,2);$t->decimal('shipping_cost',12,2)->default(0);$t->decimal('total',12,2);$t->string('customer_name');$t->string('customer_email');$t->string('customer_phone');$t->text('shipping_address');$t->text('notes')->nullable();$t->timestamps();});
Schema::create('order_items',function(Blueprint $t){$t->id();$t->foreignId('order_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();$t->string('product_name');$t->unsignedInteger('quantity');$t->decimal('unit_price',12,2);$t->decimal('line_total',12,2);$t->timestamps();});
} public function down(): void {Schema::dropIfExists('order_items');Schema::dropIfExists('orders');} };
