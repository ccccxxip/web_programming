<?php

class Application {

    private $separator = "|";
    private $file = "applications/data.txt";

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $topic;
    public $payment;
    public $newsletter;
    public $date;
    public $ip;
    public $status;

    public function __construct($data = []) {

        $this->first_name = trim($data['first_name'] ?? '');
        $this->last_name = trim($data['last_name'] ?? '');
        $this->email = trim($data['email'] ?? '');
        $this->phone = trim($data['phone'] ?? '');
        $this->topic = trim($data['topic'] ?? '');
        $this->payment = trim($data['payment'] ?? '');
        $this->newsletter = isset($data['newsletter']) ? 1 : 0;

        $this->date = date("Y-m-d H:i:s");
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->status = "active";
    }

    // проверка
    public function validate() {

        $errors = [];

        $fields = [
            $this->first_name,
            $this->last_name,
            $this->email,
            $this->phone,
            $this->topic,
            $this->payment
        ];

        foreach ($fields as $field) {
            if (strpos($field, $this->separator) !== false) {
                $errors[] = "Нельзя использовать символ |";
            }
        }

        if ($this->first_name == '') $errors[] = "Введите имя";
        if ($this->last_name == '') $errors[] = "Введите фамилию";
        if ($this->email == '') $errors[] = "Введите email";

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный email";
        }

        if ($this->phone == '') $errors[] = "Введите телефон";
        if ($this->topic == '') $errors[] = "Выберите тему";
        if ($this->payment == '') $errors[] = "Выберите оплату";

        return $errors;
    }

    // сохранение
    public function save() {

        if (!is_dir("applications")) {
            mkdir("applications");
        }

        if (!file_exists($this->file)) {
            file_put_contents($this->file, "");
        }

        $line = implode($this->separator, [
            $this->first_name,
            $this->last_name,
            $this->email,
            $this->phone,
            $this->topic,
            $this->payment,
            $this->newsletter,
            $this->date,
            $this->ip,
            $this->status
        ]) . PHP_EOL;

        file_put_contents($this->file, $line, FILE_APPEND);
    }

    // чтение
    public static function getAll() {

        $file = "applications/data.txt";

        if (!file_exists($file)) {
            return [];
        }

        return file($file, FILE_IGNORE_NEW_LINES);
    }
}
?>