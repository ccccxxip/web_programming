<h2>Добавить задачу</h2>

<!-- отправл в create.php -->
<form method="POST" action="actions/create.php">
    
    <input type="text" name="theme" placeholder="Тема" required> <!-- поле для темы -->
    
    <!-- выпадающ список -->
    <select name="type" required>
        <option value="встреча">встреча</option>
        <option value="звонок">звонок</option>
        <option value="совещание">совещание</option>
        <option value="дело">дело</option>
    </select>
    
    <input type="text" name="place" placeholder="Место"> 
    
    <input type="datetime-local" name="task_datetime" required> 
    
    <input type="number" name="duration" placeholder="Длительность (минуты)"> 
    
    <textarea name="comment" placeholder="Комментарий"></textarea> 
    
    <button>Добавить</button> 
</form>

<hr>