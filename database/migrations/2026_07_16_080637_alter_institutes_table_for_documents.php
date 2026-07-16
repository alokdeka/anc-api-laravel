<?php

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
        Schema::table('institutes', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'name',
                'district',
                'affiliation_type',
                'affiliation_number',
                'seats_gnm',
                'seats_anm',
                'principal_name',
                'contact_email',
                'contact_phone',
                'address',
                'approved_date',
                'remarks'
            ]);

            // Add new document-based columns
            $table->string('title')->after('id');
            $table->string('file_path')->nullable()->after('title');
            $table->string('external_url')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['title', 'file_path', 'external_url']);
            
            $table->string('name');
            $table->string('district')->nullable();
            $table->string('affiliation_type')->nullable();
            $table->string('affiliation_number')->nullable();
            $table->integer('seats_gnm')->default(0);
            $table->integer('seats_anm')->default(0);
            $table->string('principal_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('remarks')->nullable();
        });
    }
};
