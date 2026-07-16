<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->string('father_husband_name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('qualification'); // GNM, ANM, LHV, DPN, B.Sc. Nursing
            $table->unsignedBigInteger('institute_id')->nullable();
            $table->date('registration_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'revoked', 'suspended'])->default('active');
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->default('Assam');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('institute_id')->references('id')->on('institutes')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
