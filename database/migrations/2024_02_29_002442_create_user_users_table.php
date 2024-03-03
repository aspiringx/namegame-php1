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
        Schema::create('user_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('ID of the user that was the inviter.');
            $table->unsignedBigInteger('user_id_invitee')->comment('ID of the user that was invited.');
            $table->unsignedBigInteger('group_id');
            $table->string('relationship_id')->comment('Relationship between users. Friend, sibling, parent, etc.');

            // created_at, updated_at
            $table->timestamps();

            // Foreign keys and indexes.
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('user_id_invitee')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unique(['group_id', 'user_id', 'user_id_invitee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_user');
    }
};
