<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDomainDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_domain_data', function (Blueprint $table) {
            $table->id();
            $table->integer('list_id');
            $table->string('domain_name', 50);
            $table->tinyInteger('dmarc_flag')->default(0)->comment('0=Not found, 1= Found');
            $table->tinyInteger('dkim_flag')->default(0)->comment('0=Not found, 1= Found');
            $table->tinyInteger('process_status')->default(0)->comment('0=Not Process, 1= Processed');
            $table->timestamp('added_at')->useCurrent();
            $table->timestamp('updated_date')->useCurrent();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_domain_data');
    }
}
