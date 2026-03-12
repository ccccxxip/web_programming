<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Таблица умножения</title>

<style>
body{
    background-color:#fff9db;
    font-family: Arial, sans-serif;
}

table {
    border-collapse: collapse;
    margin: 30px auto;
    background-color:#fffde7;
}

td {
    border: 1px solid #e6c200;
    width: 40px;
    height: 35px;
    text-align: center;
    background-color:#fff9c4;
}

.header {
    background-color: #ffd54f;
    font-weight: bold;
}

.diagonal {
    background-color: #fff176;
    font-weight: bold;
}

td:hover{
    background-color:#ffe082;
}
</style>

</head>
<body>

<table>

<?php

for ($i = 0; $i <= 10; $i++) {

    echo "<tr>";

    for ($j = 0; $j <= 10; $j++) {

        if ($i == 0 && $j == 0) {
            echo "<td class='header'></td>";
        }

        elseif ($i == 0) {
            echo "<td class='header'>$j</td>";
        }

        elseif ($j == 0) {
            echo "<td class='header'>$i</td>";
        }

        else {
            $result = $i * $j;

            if ($i == $j) {
                echo "<td class='diagonal'>$result</td>";
            } else {
                echo "<td>$result</td>";
            }
        }

    }

    echo "</tr>";
}

?>

</table>

</body>
</html>