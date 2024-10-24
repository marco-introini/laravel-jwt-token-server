<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jwt_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('uuid');
            $table->string('subject');
            $table->string('audience');
            $table->dateTime('expiration_time')->nullable();
            $table->dateTime('iat')->nullable();
            $table->json('custom_claims')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jwt_tokens');
    }
};
