<?php

include '../config/db.php';
include '../models/task.php';

$model = new Task($pdo);

$model->delete($_GET['id']);

header("Location: ../index.php");