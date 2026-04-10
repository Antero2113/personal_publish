<?php
return function ($config)
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === 'localhost' || $host === '127.0.0.1')
    {
        define('DEV_MODE', true);
        $config->db = [
            'host' => '127.0.0.1',
            'name' => 'тут название бд',
            'user' => 'тут имя пользователя',
            'password' => 'тут пароль'
        ];
    }
    else
    {
        define('DEV_MODE', false);
        $config->db = [
            'host' => 'хост, где бд расположена',
            'name' => 'тут название бд',
            'user' => 'тут имя пользователя',
            'password' => 'тут пароль'
        ];
    }
};
