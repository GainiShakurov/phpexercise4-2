<?php
require "config.php";

if (isset($_POST['description']) && !empty($_POST['description'])) {
    $currentUpdateQuery = "UPDATE tasks SET description = :description, is_done = :is_done WHERE id = :id";
    $currentUpdate = $connect->prepare($currentUpdateQuery);
    $currentUpdate->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $currentUpdate->bindValue(':is_done', $_POST['status'], PDO::PARAM_INT);
    $currentUpdate->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
    $currentUpdate->execute();
    header('Location: /');
}

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $currentRecordQuery = 'SELECT * FROM tasks WHERE id = :id';

    $currentRecord = $connect->prepare($currentRecordQuery);
    $currentRecord->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $currentRecord->execute();

    while ($data = $currentRecord->fetch()) {
?>

    <h1>Редактирование</h1>

    <form method="POST" style="float: left; margin: 0 20px 20px 0;">
        <input name="description" placeholder="Описание задачи" value="<?php echo $data['description'] ?>" type="text">
        <label for="status">Статус:</label>
        <select name="status">
            <option value="0" <?php if ($data['is_done'] == '0') { echo "selected"; } ?>>Не выполнено
            </option>
            <option value="1" <?php if ($data['is_done'] == '1') { echo "selected"; } ?>>Выполнено
            </option>
        </select>

        <input name="save" value="Изменить" type="submit" onclick="window.location = 'http://www.google.com/'">
    </form>
<?php
    }
}
?>