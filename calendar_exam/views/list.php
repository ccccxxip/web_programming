<h2>Задачи</h2>

<div class="filters"> 
    <a href="?filter=current">Текущие</a>
    <a href="?filter=expired">Просроченные</a>
    <a href="?filter=completed">Выполненные</a>
    <a href="?filter=all">Все задачи</a>
    
    <!-- поиск по конкретн дате -->
    <form method="GET">
        <input type="date" name="date"> 
        <button type="submit">Найти</button>
    </form>
</div>

<?php 
if (empty($tasks)): 
?>
    <!-- если нет задач  -->
    <p>Нет задач</p>
    
<?php else: ?>
    <!-- если есть -->
    <?php 
    foreach ($tasks as $t): 
    ?>
   
    <div class="task">
        
        <h3><?= htmlspecialchars($t['theme']) ?></h3>
      
        <p>Тип: <?= $t['type'] ?></p>

        <p>Место: <?= htmlspecialchars($t['place'] ?: 'Не указано') ?></p>
        
        <p>Дата: <?= date('d.m.Y H:i', strtotime($t['task_datetime'])) ?></p>
        
        <!-- ссылка на карточку задачи -->
        <a href="views/task.php?id=<?= $t['id'] ?>">Открыть</a>
        
        <a href="actions/delete.php?id=<?= $t['id'] ?>" onclick="return confirm('Удалить?')">Удалить</a>
        
    </div>
    
    <?php endforeach; ?>
    
<?php endif; ?>