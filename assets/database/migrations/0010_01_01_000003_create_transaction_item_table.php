<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Hanafalah\ModuleTransaction\Models\Transaction\TransactionItem;
use Hanafalah\ModuleTransaction\Enums\Transaction\TransactionStatus;
use Hanafalah\ModuleTransaction\Models\Transaction\Transaction;

return new class extends Migration
{
    use Hanafalah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct()
    {
        $this->__table = app(config('database.models.TransactionItem', TransactionItem::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()) {
            Schema::create($table_name, function (Blueprint $table) {
                $transaction = app(config('database.models.Transaction', Transaction::class));

                $table->ulid('id')->primary();
                $table->foreignIdFor($transaction::class)->nullable()->index()
                    ->constrained()->cascadeOnUpdate()->restrictOnDelete();
                $table->string('item_type', 50)->nullable(false);
                $table->string('item_id', 36)->nullable(false);
                $table->string('item_name', 255)->nullable();
                $table->unsignedTinyInteger('status')->default(TransactionStatus::DRAFT->value)->nullable(false);
                $table->json('props')->nullable();
                $table->integer('total_tax')->nullable()->default(0);
                $table->integer('total_additional')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['item_type', 'item_id'], 'item_ref');
            });

            Schema::table($table_name, function (Blueprint $table) {
                $table->foreignIdFor($this->__table::class, 'parent_id')
                    ->nullable()->after('id')
                    ->index()->constrained()
                    ->cascadeOnUpdate()->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->__table->getTable());
    }
};
