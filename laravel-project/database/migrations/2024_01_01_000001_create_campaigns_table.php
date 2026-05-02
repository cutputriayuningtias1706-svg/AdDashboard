<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_account_id')->constrained()->onDelete('cascade');
            $table->string('campaign_name');
            $table->string('campaign_id')->unique();
            $table->decimal('budget_daily', 15, 2)->default(0);
            $table->decimal('budget_total', 15, 2)->default(0);
            $table->enum('status', ['active', 'paused', 'ended'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
