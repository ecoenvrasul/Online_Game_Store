<?php

use App\Models\Genre;
use App\Models\Developer;
use App\Models\Publisher;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_img');
            $table->double('rating');
            $table->double('price');
            $table->double('discount')->nullable();
            $table->double('current_price')->nullable();
            $table->bigInteger('purchased_games')->nullable();
            $table->mediumText('about');
            $table->json('minimal_system');
            $table->json('recommend_system');
            $table->boolean('warn')->dafault(true);
            $table->text('warn_text');
            $table->json('screenshots');
            $table->json('trailers');
            $table->string('language');
            $table->string('active_location');
            $table->json('genre');
            $table->foreignIdFor(Developer::class);
            $table->foreignIdFor(Publisher::class);
            $table->string('platform');
            $table->json('producted');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
