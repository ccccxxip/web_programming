<?php

$patterns = [

    // Числа
    'int' => '/^-?\d+$/',

    // Латиница + цифры
    'latin_alnum' => '/^[A-Za-z0-9]+$/',

    // Латиница + кириллица + цифры
    'ru_en_alnum' => '/^[A-Za-zА-Яа-яЁё0-9]+$/u',

    // Домен
    'domain' => '/^(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/',

    // Username
    'username' => '/^[A-Za-z][A-Za-z0-9]{2,24}$/',

    // Пароль (простые)
    'password_simple' => '/^[A-Za-z0-9]+$/',

    // Пароль сложный
    'password_strong' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',

    // Даты
    'date_ymd' => '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/',
    'date_dmy_slash' => '/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/',
    'date_dmy_dot' => '/^(0[1-9]|[12]\d|3[01])\.(0[1-9]|1[0-2])\.\d{4}$/',

    // Время
    'time_hms' => '/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/',
    'time_hm'   => '/^([01]\d|2[0-3]):[0-5]\d$/',

    // URL
    'url' => '/^https?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/.*)?$/',

    // Email
    'email' => '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',

    // IP
    'ipv4' => '/^((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)\.){3}(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)$/',
    'ipv6' => '/^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$/',

    // MAC
    'mac' => '/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/',

    // Телефон РФ
    'phone_ru' => '/^\+7\d{10}$/',

    // Карта
    'card' => '/^(\d{4} ?){3}\d{4}$/',

    // ИНН
    'inn' => '/^(\d{10}|\d{12})$/',

    // Индекс
    'postcode' => '/^\d{6}$/',

    // Цена рубли
    'price_rub' => '/^\d+(,\d{2})?\s?руб\.?$/',

    // Цена $
    'price_usd' => '/^\$\d+(\.\d{2})?$/',
];

function validate($type, $value, $patterns) {
    if (!isset($patterns[$type])) {
        return "Неизвестный тип: $type";
    }

    return preg_match($patterns[$type], $value) ? "OK" : "FAIL";
}

//тесты
$tests = [
    ['int', '-123'],
    ['email', 'test@mail.com'],
    ['ipv4', '192.168.0.1'],
    ['username', 'A12user'],
    ['date_ymd', '2026-05-06'],
];

foreach ($tests as $test) {
    [$type, $value] = $test;
    echo "$type => $value : " . validate($type, $value, $patterns) . PHP_EOL;
}