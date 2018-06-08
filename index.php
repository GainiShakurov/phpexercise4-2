<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список дел</title>
</head>
<style>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }

    table th {
        background: #eee;
    }
</style>
<body>

<h1>Список дел</h1>

<form method="POST" style="float: left; margin: 0 20px 20px 0;">
    <input name="description" placeholder="Описание задачи" value="" type="text">
    <input name="save" value="Добавить" type="submit">
</form>

<form method="POST" style="float: left; margin: 0 0 20px;">
    <label for="sort">Сортировать по:</label>
    <select name="sort_by">
        <option value="date_added" <?php if ($_POST['sort_by'] == 'date_added') {echo "selected";} ?> >Дате добавления</option>
        <option value="is_done" <?php if ($_POST['sort_by'] == 'is_done') {echo "selected";} ?> >Статусу</option>
        <option value="description" <?php if ($_POST['sort_by'] == 'description') {echo "selected";} ?> >Описанию</option>
    </select>
    <input name="sort" value="Отсортировать" type="submit">
</form>

<div style="clear: both;"></div>

<?php

require "config.php";

if (isset($_POST['description']) && !empty($_POST['description'])) {

    $addNewRecordQuery = "INSERT INTO tasks(description,is_done,date_added) VALUES (:description,:is_done,:date_added)";
    $addNewRecord = $connect->prepare($addNewRecordQuery);
    $addNewRecord->execute(array(
        "description" => $_POST['description'],
        "is_done" => 0,
        "date_added" => date('Y-m-d H:i:s')
    ));
}

if (isset($_GET['action']) && ($_GET['action'] === "delete") && isset($_GET['id']) && !empty($_GET['id'])) {

    $deleteRecordQuery = "DELETE FROM tasks WHERE id = :id";
    $deleteRecord = $connect->prepare($deleteRecordQuery);
    $deleteRecord->execute(array($_GET['id']));
}

if (isset($_GET['action']) && ($_GET['action'] === "done") && isset($_GET['id']) && !empty($_GET['id'])) {

    $updateStatusQuery = "UPDATE tasks SET is_done = 1 WHERE id = :id";
    $updateStatus = $connect->prepare($updateStatusQuery);
    $updateStatus->execute(array($_GET['id']));
}

?>


<table>
    <thead>
    <th>Описание</th>
    <th>Дата добавления</th>
    <th>Статус</th>
    <th></th>
    </thead>
    <tbody>

    <?php

    $allRecordQuery = 'SELECT * FROM tasks';

    if (isset($_POST['sort_by']) && !empty($_POST['sort_by'])) {
        $allRecordQuery .= ' ORDER BY '. strip_tags($_POST['sort_by']);
    }

    $allRecord = $connect->prepare($allRecordQuery);
    $allRecord->execute();

    while ($data = $allRecord->fetch()) {
        echo '<tr>';
        echo '<td>' . $data['description'] . '</td>';
        echo '<td>' . $data['date_added'] . '</td>';
        echo '<td>' . $data['is_done'] . '</td>';
        echo '<td>';
        echo '<a href="/edit.php?id=' . $data['id'] . '">Изменить</a> ';
        echo '<a href="/index.php?id=' . $data['id'] . '&action=done">Выполнить</a> ';
        echo '<a href="/index.php?id=' . $data['id'] . '&action=delete">Удалить</a> ';
        echo '</td>';
        echo '</tr>';
    }

    ?>
    </tbody>
</table>

</body>
</html>