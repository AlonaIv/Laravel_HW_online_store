<?php

namespace App\Helpers;

class TransactionAdapter
{
    public function __construct(public string $payment_system, public int $user_id, public string $status)
    {
    }
}
