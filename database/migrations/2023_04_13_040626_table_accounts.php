<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 15);
            $table->float('amount', 10, 2);
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('accounts_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->string('concepts');
            $table->float('amount', 10, 2);
            $table->string('who');
            $table->integer('msi')->nullable();
            $table->integer('no_payment')->nullable();
            $table->date('bought_at')->nullable();
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
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('accounts_charges');
    }
}
