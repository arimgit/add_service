<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePopupFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('popup_form_data', function (Blueprint $table) {
            $table->json('form_data'); // Add the JSON column for form data
            $table->text('host_name'); // Add the text column for host name

            // Drop columns that are no longer needed
            $table->dropColumn(['name', 'email', 'mobile', 'user_id', 'website_name']);

            // Define foreign key relationship
            $table->foreign('popup_id')->references('id')->on('table_popup_content')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('popup_form_data', function (Blueprint $table) {
            $table->dropColumn(['form_data', 'host_name']); // Remove added columns
            // Re-add the columns dropped in the up() method
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('website_name')->nullable();
        });
    }
}
