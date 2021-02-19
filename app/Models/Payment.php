<?php

namespace App\Models;

use App\Models\Tax;

class Payment
{
    const PRO_PRICE = 200;
    protected $summ;

    /**
     * Payment constructor.
     *
     * @param $summ
     * @param \User $user
     */
    public function __construct($summ, User $user)
    {
        $this->summ = $summ;
        $this->user = $user;
    }

    /**
     * Получение суммы оплаты.
     *
     * @return float
     */
    public function getPaySumm()
    {
        $tax = (new Tax())->getTaxByUserAndSumm($this->user, $this->summ);

        if ($this->summ > 5000) {
            $tax = $tax / 2;
        }

        return $this->summ + $tax;
    }
}
