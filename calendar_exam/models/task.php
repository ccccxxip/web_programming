<?php

class Task
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll() // получаем все задачи кроме выполненных 
    {
        return $this->pdo->query("SELECT * FROM tasks ORDER BY task_datetime DESC")
            ->fetchAll(PDO::FETCH_ASSOC); // массив на выходе 
    }

    public function getCurrent() // текущ задачи 
    {
        return $this->pdo->query("
            SELECT * FROM tasks
            WHERE DATE(task_datetime) >= CURDATE() AND status = 'текущая'
            ORDER BY task_datetime
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExpired() // просрочились задачи
    {
        return $this->pdo->query("
            SELECT * FROM tasks
            WHERE DATE(task_datetime) < CURDATE() AND status = 'текущая'
            ORDER BY task_datetime
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompleted() // выполненные 
    {
        return $this->pdo->query("
            SELECT * FROM tasks
            WHERE status = 'выполненная'
            ORDER BY task_datetime DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDate($date) // задачи конкретн дата 
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tasks
            WHERE DATE(task_datetime) = ?
            ORDER BY task_datetime
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) // получение задачи по id 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) // добавить задачу 
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tasks (theme,type,place,task_datetime,duration,comment,status)
            VALUES (:theme,:type,:place,:task_datetime,:duration,:comment,'текущая')
        ");
        return $stmt->execute($data); // вернет успех или провал 
    }

    public function update($data) // обновить 
    {
        $stmt = $this->pdo->prepare("
            UPDATE tasks SET
            theme=:theme,
            type=:type,
            place=:place,
            task_datetime=:task_datetime,
            duration=:duration,
            comment=:comment,
            status=:status
            WHERE id=:id
        ");
        return $stmt->execute($data);
    }

    public function delete($id) // удалить 
    {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id=?");
        return $stmt->execute([$id]);
    }
}