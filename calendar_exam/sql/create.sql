CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    theme VARCHAR(255) NOT NULL,
    type ENUM('встреча','звонок','совещание','дело') NOT NULL,
    place VARCHAR(255),
    task_datetime DATETIME NOT NULL,
    duration INT,
    comment TEXT,
    status ENUM('текущая','выполненная') DEFAULT 'текущая'
);