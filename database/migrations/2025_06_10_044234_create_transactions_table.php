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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference_id'); // untuk mengelompokkan baris transaksi
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('reference')->nullable(); // catatan referensi, seperti "Setoran ATM"
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->enum('status', ['draft', 'posted'])->default('draft'); // status transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
