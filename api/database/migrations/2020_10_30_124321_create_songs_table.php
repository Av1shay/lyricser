<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->index();
            $table->text('writer');
            $table->longText('composers');
            $table->longText('performers');
            $table->date('published_at');
            $table->string('text_filename')->unique();
            $table->text('text_file_format');
            $table->text('stanzas_delimiter');
            $table->unsignedBigInteger('upload_by')->index();
            $table->timestamps();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('upload_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
