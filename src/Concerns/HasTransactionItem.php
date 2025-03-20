<?php

namespace Hanafalah\ModuleTransaction\Concerns;

trait HasTransactionItem
{
    public function transactionItem()
    {
        return $this->morphOneModel('Transaction', 'item');
    }
}
