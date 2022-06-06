<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class);
            $table->enum('status', ['purchased', 'unpurchased'])->default('unpurchased');
            $table->double('price')->nullable();
            $table->double('discount')->nullable();
            $table->double('current_price')->nullable();
            $table->integer('count')->nullable();
            $table->timestamp('ordered_at');
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
        Schema::dropIfExists('baskets');
    }
};
