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
        Schema::create('user_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id1')->comment('ID of the inviter.');
            $table->unsignedBigInteger('user_id2')->comment('ID of the invitee.');
            $table->string('relationship_id')->comment('Relationship between users. Friend, sibling, parent, etc.');

            // created_at, updated_at
            $table->timestamps();

            // Foreign keys and indexes.
            $table->foreign('user_id1')->references('id')->on('users');
            $table->foreign('user_id2')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_users');
    }
};
