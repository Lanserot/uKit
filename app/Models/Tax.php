<?php

namespace App\Models;

class Tax
{
    /**
     * Получние суммы налога в зависимости от пользователя и суммы оплаты.
     *
     * @param User $user
     * @param $paySumm
     * @return float|int
     */
    public function getTaxByUserAndSumm(User $user, $paySumm)
    {
        $tax = 0;

        switch ($user->country) {
            case 'Россия':
                $tax = ($paySumm * 5) / 100;
                break;

            case 'Франция':
                $tax = ($paySumm * 10) / 100;
                break;

            default:
                break;
        }

        return $tax;
    }
}
