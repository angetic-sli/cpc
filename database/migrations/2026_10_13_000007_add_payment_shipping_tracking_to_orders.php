<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::table('orders', function (Blueprint $t) { $t->string('shipping_zone')->nullable(); $t->string('carrier')->nullable(); $t->string('tracking_number')->nullable(); $t->string('payment_reference')->nullable(); $t->text('payment_url')->nullable(); }); }
    public function down(): void { Schema::table('orders', function (Blueprint $t) { $t->dropColumn(['shipping_zone','carrier','tracking_number','payment_reference','payment_url']); }); }
};
