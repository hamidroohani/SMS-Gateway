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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Provider::class)->constrained()->onDelete('cascade');
            $table->string('from');
            $table->string('receiver_number')->index();
            $table->string('body')->nullable();
            $table->string('status');
            $table->dateTime('sent_at')->nullable();
            $table->string('ref_code')->nullable();
            $table->string('err_msg')->nullable();
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
        Schema::dropIfExists('messages');
    }
};
