<?php include "templates/include/header.php" ?>

<div class="container album py-5 bg-light">
    <h5 class="mb-3">Сортировать</h5>

    <div class="mb-3">
        <select name="order" class="form-control" id="order">
            <?php
            $order_arr = [
                'id' => [['DESC', 'По id возрастанию'], ['ASC', 'По id убыванию']],
                'name' => [['DESC', 'По имени возрастанию'], ['ASC', 'По имени убыванию']],
                'email' => [['DESC', 'По Email возрастанию'], ['ASC', 'По Email убыванию']],
                'status' => [['DESC', 'По статусу возрастанию'], ['ASC', 'По статусу убыванию']],
            ];

            foreach ($order_arr as $order_key => $order_val) {
                foreach ($order_val as $order_val2) {
                    echo "<option value='";
                    echo "?order=$order_key&order_2=$order_val2[0]";
                    if (!empty($_GET['page'])) {
                        echo "&page=" . $_GET['page'];
                    }
                    echo "'";
                    if ($_GET['order'] == $order_key && (isset($_GET['order_2']) && $_GET['order_2'] == $order_val2[0])) {
                        echo ' selected';
                    }
                    echo ">" . $order_val2[1] . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <table class="table">
        <thead>
        <tr>
            <?php if ($_SESSION['username'] == ADMIN_USERNAME) { ?>
                <th scope="col">ID</th>
            <?php } ?>
            <th scope="col">Имя</th>
            <th scope="col">Email</th>
            <th scope="col">Задача</th>
            <th scope="col">Статус</th>
            <th scope="col">Обновалено Админом</th>
            <?php if ($_SESSION['username'] == ADMIN_USERNAME) { ?>
                <th scope="col">Обновить</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results['tasks'] as $task) { ?>

            <tr>
                <?php if ($_SESSION['username'] == ADMIN_USERNAME) { ?>
                <th scope="row"><a
                            href="admin.php?action=editTask&amp;taskId=<?php echo $task->id ?>"><?php echo htmlspecialchars($task->id) ?></a>
                    <?php } ?>
                </th>
                <td><?php echo htmlspecialchars($task->name) ?></td>
                <td><?php echo htmlspecialchars($task->email) ?></td>
                <td><?php echo htmlspecialchars($task->content) ?></td>
                <td><?php switch (htmlspecialchars($task->status)) {
                        case 0:
                            echo "Не выполнен";
                            break;
                        case 1:
                            echo "Выполнен";
                            break;
                    } ?>
                </td>
                <td><?php switch (htmlspecialchars($task->update_admin)) {
                        case 0:
                            echo "Нет";
                            break;
                        case 1:
                            echo "Да";
                            break;
                    } ?>
                </td>
                <?php if ($_SESSION['username'] == ADMIN_USERNAME) { ?>
                    <td scope="col">
                        <a class="btn btn-sm btn-outline-secondary"
                           href="admin.php?action=editTask&amp;taskId=<?php echo $task->id ?>">Изменить</a>
                        <a class="btn btn-sm btn-outline-secondary" onclick="return confirm('Задача удалена?')"
                           href="admin.php?action=deleteTask&amp;taskId=<?php echo $task->id ?>">Удалить</a>
                    </td>
                <?php } ?>

            </tr>

        <?php } ?>
        </tbody>
    </table>

    <?php if ($results['totalPages'] > 1) { ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($_GET['page'] = 1; $_GET['page'] <= $results['totalPages']; $_GET['page']++) { ?>

                    <li class="page-item">
                        <a class="page-link" href='<?php if (!empty($_GET['order'])) {
                            echo "?order=" . $_GET['order'];
                            if (!empty($_GET['order_2'])) {
                                echo "&order_2=" . $_GET['order_2'] . "&page=" . $_GET['page'];
                            }
                        } else {
                            echo "?page=" . $_GET['page'];
                        } ?>' class="links"><?php echo $_GET['page']; ?>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </nav>
    <?php } ?>


    <p><?= $results['totalResults'] ?> Заданий всего.</p>


    <h4 class="mb-3">Добавить новую задачу</h4>

    <form action="?action=<?php echo $results['formAction'] ?>" method="post">

        <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
        <?php } ?>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Имя" required
                           maxlength="255" value="<?php echo htmlspecialchars($results['task']->name) ?>"/>
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email" required
                           maxlength="255" value="<?php echo htmlspecialchars($results['task']->email) ?>"/>
                </div>
                <?php if ($_SESSION['username'] == ADMIN_USERNAME) { ?>
                    <div class="mb-3">
                        <label for="status">Статус</label>
                        <select name="status" class="form-control" id="status">
                            <option value="0">Не выполнен</option>
                            <option value="1">Выполнен</option>
                        </select>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-8 mb-3">
                <label for="content">Задача</label>
                <textarea name="content" class="form-control" id="content" placeholder="Описание задачи" required
                          maxlength="100000"
                          style="height: 100%;"><?php echo htmlspecialchars($results['task']->content) ?></textarea>
            </div>
        </div>

        <div class="buttons">
            <input type="submit" class="btn btn-danger" formnovalidate name="cancel" value="Отмена"/>
            <input type="submit" class="btn btn-primary" name="saveChanges" value="Сохранить"/>
        </div>

    </form>

</div>

<?php include "templates/include/footer.php" ?>

