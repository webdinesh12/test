<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('photo')->nullable();
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_at')->nullable();
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->enum('type', ['user', 'admin'])->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('first_login', [1, 0])->default(1);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_details', function(Blueprint $table){
            $table->id();
            $table->foreignIdFor(User::class)->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('address')->nullable();
            $table->enum('address_visibility', ['everyone', 'noone', 'connections', 'connection_and_requested'])->default('everyone');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
