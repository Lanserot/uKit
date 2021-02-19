
## Второе задание, вариант кода Без laravel

<?php

/**
 * Пользователь.
 *
 * Class User
 */
class User
{
    public $fio;
    public $country;
    protected $balance;
    protected $user_id = 0;
    protected $isPro;

    /**
     * User constructor.
     *
     * @param $fio
     * @param $country
     */
    public function __construct($fio, $country)
    {
        $this->fio = $fio;
        $this->country = $country;

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
/**
 * Отправка данных.
 *
 * Class SendData
 */
Class SendData
{

    /**
     * Отправка данных через post
     *
     * @return mixed
     */
    public static function sendPost($post, $Url = 'www.test.ru')
    {
        $myCurl = curl_init();
        curl_setopt_array($myCurl, array(
                CURLOPT_URL => $Url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($post))
        );
        $response = curl_exec($myCurl);
        curl_close($myCurl);
        return $response;
    }
}

/**
 * Платеж.
 *
 * Class Payment
 */
class Payment
{
    const PRO_PRICE = 200;
    protected $summ;

    /**
     * Payment constructor.
     *
     * @param $summ
     * @param User $user
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

/**
 * Налог.
 *
 * Class Tax
 */
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

$user = new User('Васильев Василий Васильевич', 'Россия');

echo $user->fio . '<br>';
echo 'Баланс до покупки - ' .  $user->getBalance() . '<br>';

$user->buyPro();

echo 'Баланс после покупки - ' . $user->getBalance();


