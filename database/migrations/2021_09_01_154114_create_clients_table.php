<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    private string $table = 'clients';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table): void {
            $table->id();
            $table->string('client_name', 100);
            $table->text('address1');
            $table->text('address2');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);
            $table->double('latitude');
            $table->double('longitude');
            $table->string('phone_no1', 20);
            $table->string('phone_no2', 20);
            $table->string('zip', 20);
            $table->date('start_validity');
            $table->date('end_validity');
            $table->enum('status', Status::getValues());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
}
