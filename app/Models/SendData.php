<?php

namespace App\Models;


class SendData
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
