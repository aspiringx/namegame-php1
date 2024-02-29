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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('Unique hash used for QR code URL.');
            $table->unsignedBigInteger('group_id')->comment('ID of group or sub-group.');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID of inviter or null for anon group invitation.');
            $table->timestamp('expires_at')->comment('Timestamp when this QR code expires.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
