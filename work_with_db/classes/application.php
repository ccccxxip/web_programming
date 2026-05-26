<?php

require_once __DIR__ . '/db.php';

class Application {

    public static $subjects = [
        1 => 'Бизнес',
        2 => 'Технологии',
        3 => 'Реклама и маркетинг'
    ];

    public static $payments = [
        1 => 'WebMoney',
        2 => 'Яндекс.Деньги',
        3 => 'PayPal',
        4 => 'Кредитная карта'
    ];

    public $name;
    public $lastname;
    public $email;
    public $tel;
    public $subject_id;
    public $payment_id;
    public $mailing;

    public function __construct($data = []) {

        $this->name = trim($data['name'] ?? '');
        $this->lastname = trim($data['lastname'] ?? '');
        $this->email = trim($data['email'] ?? '');
        $this->tel = trim($data['tel'] ?? '');

        $this->subject_id = $data['subject_id'] ?? '';
        $this->payment_id = $data['payment_id'] ?? '';

        $this->mailing = isset($data['mailing']) ? 1 : 0;
    }

    // проверка
    public function validate() {

        $errors = [];

        if ($this->name == '') $errors[] = "Введите имя";
        if ($this->lastname == '') $errors[] = "Введите фамилию";

        if ($this->email == '') {
            $errors[] = "Введите email";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный email";
        }

        if ($this->tel == '') $errors[] = "Введите телефон";
        if ($this->subject_id == '') $errors[] = "Выберите тему";
        if ($this->payment_id == '') $errors[] = "Выберите оплату";

        return $errors;
    }

    // сохранение
    public function save() {

        $db = DB::connect();

        $stmt = $db->prepare("
            INSERT INTO participants
            (
                name,
                lastname,
                email,
                tel,
                subject_id,
                payment_id,
                mailing,
                created_at,
                updated_at
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->bind_param(
            "ssssiii",
            $this->name,
            $this->lastname,
            $this->email,
            $this->tel,
            $this->subject_id,
            $this->payment_id,
            $this->mailing
        );

        $stmt->execute();
    }

    // получение всех
    public static function getAll() {

        $db = DB::connect();

        $sql = "
            SELECT *
            FROM participants
            WHERE deleted_at IS NULL
            ORDER BY id DESC
        ";

        $result = $db->query($sql);

        return $result;
    }

    // удаление
    public static function delete($id) {

        $db = DB::connect();

        $stmt = $db->prepare("
            UPDATE participants
            SET deleted_at = NOW()
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);

        $stmt->execute();
    }
}
?>