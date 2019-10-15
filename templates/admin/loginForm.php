<?php include "templates/include/header.php" ?>
<div class="container album py-5 bg-light">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <form action="admin.php?action=login" method="post" style="width: 50%;">
                <input type="hidden" name="login" value="true" />
                <?php if ( isset( $results['errorMessage'] ) ) { ?>
                    <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
                <?php } ?>
                <div class="mb-3">
                    <label for="username">Логин</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Ваш логин" required autofocus maxlength="20" />
                </div>
                <div class="mb-3">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Ваш пароль" required maxlength="20" />
                </div>

                <div class="buttons">
                    <input type="submit" class="btn btn-primary" name="login" value="Авторизоваться" />
                </div>

            </form>
        </div>
    </div>
</div>


<?php include "templates/include/footer.php" ?>

