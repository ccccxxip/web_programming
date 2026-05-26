<?php
$host = 'localhost';
$dbname = 'study_calendar22';
$username = 'study_calendar22';
$password = 'j84QYwl0';

$pdo = new PDO(
    'mysql:host=' . $host . ';dbname=' . $dbname,
    $username,
    $password
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);