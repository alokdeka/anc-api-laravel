<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('district');
            $table->string('affiliation_type')->nullable(); // GNM, ANM, etc.
            $table->string('affiliation_number')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->integer('seats_gnm')->default(0);
            $table->integer('seats_anm')->default(0);
            $table->string('principal_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};
