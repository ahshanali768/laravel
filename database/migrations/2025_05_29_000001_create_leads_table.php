<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending');
            $table->string('agent_name');
            $table->string('verifier_name')->nullable();
            $table->string('did_number');
            $table->string('campaign_name')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('leads');
    }
};
