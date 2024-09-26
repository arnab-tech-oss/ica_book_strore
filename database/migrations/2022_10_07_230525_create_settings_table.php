<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('software_title')->nullable();
            $table->text('software_description')->nullable();
            $table->text('software_version')->nullable();
            $table->text('company_name')->nullable();
            $table->text('company_logo')->nullable();
            $table->text('company_intro')->nullable();
            $table->text('company_email')->nullable();
            $table->text('company_alternative_email')->nullable();
            $table->text('company_contact_no')->nullable();
            $table->text('company_alternative_contact_no')->nullable();
            $table->text('company_gst_no')->nullable();
            $table->text('billing_header')->nullable();
            $table->text('billing_footer')->nullable();
            $table->text('email_cc')->nullable();
            $table->text('email_bcc')->nullable();
            $table->text('api_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
