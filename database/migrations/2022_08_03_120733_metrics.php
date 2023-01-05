<?php

use App\Metric;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Metrics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('metricName');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('metricCode');
            $table->string('metricType');
            $table->unsignedBigInteger('value');
            $table->string('comment');
            $table->string('reason')->nullable();
            $table->string('status');
            $table->string('metricEntryType');
            $table->string('item_status')->default(Metric::SAVED);
            $table->dateTime('entryDate');
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
        Schema::dropIfExists('metrics');
    }
}
