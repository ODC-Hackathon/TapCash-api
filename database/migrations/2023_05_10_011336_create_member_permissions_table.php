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
        Schema::create('member_permissions', function (Blueprint $table) {
            $table->id();
            $table->json('permissions');
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('family_members','id')->cascadeOnDelete();
            $table->unique('member_id');

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
        Schema::dropIfExists('member_permissions');
    }
};
