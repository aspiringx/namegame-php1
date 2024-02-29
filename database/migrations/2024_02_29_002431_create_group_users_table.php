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
        Schema::create('group_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role')->nullable()->comment('Role of user for permission purposes.');
            $table->string('title')->nullable()->comment('Title of user in group. Leader, president, etc.');
            $table->timestamp('member_since')->nullable()->comment('When user joined group. May be before created_at.');
            $table->boolean('is_leader')->nullable()->comment('Leaders are visible to group members before personal connections.');
            $table->boolean('is_active')->default(true)->comment('Must be active to be visible.');

            // created_at, updated_at
            $table->timestamps();

            // Foreign keys and indexes.
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_users');
    }
};
