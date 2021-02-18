<?php

use App\Models\Order;
use App\Models\Good;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_order', function (Blueprint $table) {
            $table->foreignIdFor(Order::class)->constrained();
            $table->foreignIdFor(Good::class)->constrained();
            $table->integer('count')->default(1);
            $table->primary(['order_id','good_id']);
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
        Schema::dropIfExists('good_order');
    }
}
