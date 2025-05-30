<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('payout_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('campaigns');
    }
};
