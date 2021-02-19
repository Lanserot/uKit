<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Payment;
use App\Models\SendData;

class User
{
    public $fio;
    public $country;
    protected $balance;
    protected $user_id;
    protected $isPro;

    /**
     * User constructor.
     *
     * @param $fio
     * @param $country
     */
    public function __construct($fio, $country, $user_id)
    {
        $this->fio = $fio;
        $this->country = $country;
        $this->user_id = $user_id;

        $this->balance = 1000;
        $this->isPro = false;
    }

    /**
     * Покупка пользователем PRO.
     * Перенести в Payment можно и было бы лучше, потому что для класса User не свойственно buyPro, это покупка, а не
     * данные о User, класс Payment создан для покупок, поэтому более логично и структурированно было бы перенести
     * в Payment
     */
    public function buyPro()
    {
        $proPayment = new Payment(Payment::PRO_PRICE, $this);
        $paySum = $proPayment->getPaySumm();

        if ($paySum > $this->balance) {
            echo "На счету недостаточно денег <br>";
            return false;
        }

        $this->balance -= $proPayment->getPaySumm();

        $this->isPro = true;

        $suppot = SendData::sendPost([
            'user_id' => $this->user_id,
            'buy_PRO' => $this->isPro
        ]);

        return $suppot;
    }

    public function getBalance()
    {
        return $this->balance;
    }
}
