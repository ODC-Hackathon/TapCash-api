<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->text('type');
            $table->foreignId('user_id')->nullable()->constrained('users','id')->cascadeOnDelete();
            $table->foreignId('family_id')->nullable()->constrained('family_members','id')->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions','id')->cascadeOnDelete();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }
};
