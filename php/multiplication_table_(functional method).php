<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Таблица умножения (функция)</title>

<style>

body{
    background:#eef6ff;
    font-family: Arial, sans-serif;
}

table{
    border-collapse: collapse;
    margin: 30px auto;
    background:#f7fbff;
}

td{
    border:1px solid #7fb3ff;
    width:40px;
    height:35px;
    text-align:center;
    background:#dcecff;
}

.header{
    background:#6fa8ff;
    font-weight:bold;
    color:white;
}

.diagonal{
    background:#9ec5ff;
    font-weight:bold;
}

td:hover{
    background:#c6e0ff;
}

</style>

</head>
<body>

<?php

function multiplicationTable($x = 10, $y = 10){

    $table = "<table>";

    for($i = 0; $i <= $y; $i++){

        $table .= "<tr>";

        for($j = 0; $j <= $x; $j++){

            if($i == 0 && $j == 0){
                $table .= "<td class='header'></td>";
            }

            elseif($i == 0){
                $table .= "<td class='header'>$j</td>";
            }

            elseif($j == 0){
                $table .= "<td class='header'>$i</td>";
            }

            else{

                $result = $i * $j;

                if($i == $j){
                    $table .= "<td class='diagonal'>$result</td>";
                } else {
                    $table .= "<td>$result</td>";
                }

            }

        }

        $table .= "</tr>";
    }

    $table .= "</table>";

    return $table;
}

echo multiplicationTable(); // здесь меняем размер таблицы 

?>

</body>
</html>