<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewCountToCarlistTable extends Migration
{
    public function up()
    {
        Schema::table('carlists', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')->default(0)->after('powertrain_type');
        });
    }

    public function down()
    {
        Schema::table('carlists', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
}
