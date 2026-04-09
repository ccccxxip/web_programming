<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

interface Worker {
    public function work();
}

trait HelloTrait {
    public function sayHello() {
        echo "Привет!<br>";
    }
}

class Person implements Worker {
    public $name;          // public
    protected $age;        // protected
    private $password;     // private

    use HelloTrait;

    public function __construct($name, $age, $password) {
        $this->name = $name;
        $this->age = $age;
        $this->password = $password;
    }

    public function work() {
        echo "Человек работает<br>";
    }

    // методы для private
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // доступ к protected
    public function getAge() {
        return $this->age;
    }
}

class Student extends Person {
    public $university;

    public function __construct($name, $age, $password, $university) {
        parent::__construct($name, $age, $password);
        $this->university = $university;
    }

    public function work() {
        echo "Студент учится<br>";
    }

    public function showInfo() {
        echo "Имя: {$this->name}, Возраст: {$this->age}, ВУЗ: {$this->university}<br>";
    }
}

$person = new Person("Анна", 30, "1234");
$student = new Student("Иван", 20, "abcd", "МГУ");
    
echo "<h2>Person</h2>";

$person->sayHello();
$person->work();
echo "Пароль: " . $person->getPassword() . "<br>";

$person->setPassword("newpass");
echo "Новый пароль: " . $person->getPassword() . "<br>";

echo "Возраст: " . $person->getAge() . "<br>";

echo "<hr>";

echo "<h2>Student</h2>";

$student->sayHello();
$student->work();
$student->showInfo();

echo "Пароль: " . $student->getPassword() . "<br>";

?>