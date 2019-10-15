<?php include "templates/include/header.php" ?>
<div class="container album py-5 bg-light">

    <h4 class="mb-3"><?php echo $results['pageTitle']?> № <?php echo $results['task']->id ?></h4>

    <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="taskId" value="<?php echo $results['task']->id ?>"/>
        <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
        <?php } ?>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Имя" required autofocus
                           maxlength="255" value="<?php echo htmlspecialchars($results['task']->name) ?>"/>
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email" required
                           maxlength="255" value="<?php echo htmlspecialchars($results['task']->email) ?>"/>
                </div>
                <div class="mb-3">
                    <label for="status">Статус</label>
                    <select name="status" class="form-control" id="status">
                        <option value="0"<?php if($results['task']->status == 0) echo ' selected' ?>>Не выполнен</option>
                        <option value="1"<?php if($results['task']->status == 1) echo ' selected' ?>>Выполнен</option>
                    </select>
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <label for="content">Задача</label>
                <textarea name="content" class="form-control" id="content" placeholder="Описание задачи" required
                          maxlength="100000"
                          style="height: 100%;"><?php echo htmlspecialchars($results['task']->content) ?></textarea>
            </div>
        </div>

        <div class="buttons mb-3">
            <input type="submit" class="btn btn-danger" formnovalidate name="cancel" value="Отмена"/>
            <input type="submit" class="btn btn-primary" name="saveChanges" value="Сохранить"/>
        </div>
        <?php if ( $results['task']->id ) { ?>
            <p><a class="btn btn-danger" href="admin.php?action=deleteTask&amp;taskId=<?php echo $results['task']->id ?>" onclick="return confirm('Задача удалена?')">Удалить</a></p>
        <?php } ?>
    </form>

</div>

<?php include "templates/include/footer.php" ?>

