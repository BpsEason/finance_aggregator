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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency')->unique(); # 例如：USD/TWD, JPY/TWD
            $table->decimal('rate', 10, 4);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            # 可以根據需求添加更多欄位
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
