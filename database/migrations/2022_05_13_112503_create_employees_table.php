<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 13)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'support']);
            $table->timestamps();
        });

        Employee::create([
            'name'=> 'Tom',
            'phone'=> '+998900957117',
            'password'=> Hash::make('123456'),
            'role'=> 'admin'
        ]);

        Employee::create([
            'name'=> 'Jim',
            'phone'=> '+998900957118',
            'password'=> Hash::make('123456'),
            'role'=> 'support'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }

};
