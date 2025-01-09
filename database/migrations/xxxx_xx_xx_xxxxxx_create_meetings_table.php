<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('meeting_id');
            $table->string('password');
            $table->dateTime('start_time');
            $table->integer('duration');
            $table->string('timezone')->default('UTC+7');
            $table->text('agenda')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
