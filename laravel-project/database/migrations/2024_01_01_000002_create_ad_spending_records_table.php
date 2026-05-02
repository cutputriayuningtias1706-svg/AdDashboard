<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_spending_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->date('record_date');
            $table->bigInteger('impressions')->default(0);
            $table->bigInteger('clicks')->default(0);
            $table->bigInteger('conversions')->default(0);
            $table->decimal('spend', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['campaign_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_spending_records');
    }
};
