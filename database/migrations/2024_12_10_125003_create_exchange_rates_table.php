<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CurrencyEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            // using enums here adds security and improves performance, but also limits flexibility,
            // depending on how dynamic the currencies will be, can be changed to strings
            $table->enum('base_currency', CurrencyEnum::values());
            $table->enum('target_currency', CurrencyEnum::values());
            // consider total for decimal if any plans for rare currencies with xch rates e.g. 1:1000000 (with extremely high inflation)
            $table->decimal('rate', 12, 6);
            $table->timestamps();

            // NOT TESTED, but adding below constraint could be useful, anyway validation should be done at the application level,
            // so we don't end up with e.g. USD - USD pairs unless needed
            // DB::statement('ALTER TABLE exchange_rates ADD CONSTRAINT check_different CHECK (base_currency != target_currency)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
