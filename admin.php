<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'editTask':
        editTask();
        break;
    case 'deleteTask':
        deleteTask();
        break;
    default:
        header("Location: /");
}


function login()
{

    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    if (isset($_POST['login'])) {

        // Пользователь получает форму входа: попытка авторизировать пользователя

        if ($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD) {

            // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
            $_SESSION['username'] = ADMIN_USERNAME;
            header("Location: admin.php");

        } else {

            // Ошибка входа: выводим сообщение об ошибке для пользователя
            $results['errorMessage'] = "Не правильный логин или пароль. Попробуйте ещё.";
            require(TEMPLATE_PATH . "/admin/loginForm.php");
        }

    } else {

        // Пользователь еще не получил форму: выводим форму
        require(TEMPLATE_PATH . "/admin/loginForm.php");
    }

}


function logout()
{
    unset($_SESSION['username']);
    header("Location: admin.php");
}


function editTask()
{

    $results = array();
    $results['pageTitle'] = "Изменить задачу";
    $results['formAction'] = "editTask";

    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования таска: сохраняем изменения

        if (!$task = Task::getById((int)$_POST['taskId'])) {
            header("Location: admin.php?error=taskNotFound");
            return;
        }

        if($task->content != $_POST['content']){
            $_POST['update_admin'] = true;
        }

        $task->storeFormValues($_POST);
        $task->update();
        header("Location: /");

    } elseif (isset($_POST['cancel'])) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку тасков
        header("Location: /");
    } else {

        // Пользвоатель еще не получил форму редактирования: выводим форму
        if($results['task'] = Task::getById((int)$_GET['taskId'])){
            require(TEMPLATE_PATH . "/admin/editTask.php");
        }
        else {
            // Пользователь зашёл на не существующий таск: перекидываем на списк тасков
            header("Location: /");
        }

    }

}


function deleteTask()
{

    if (!$task = Task::getById((int)$_GET['taskId'])) {
        header("Location: ?error=taskNotFound");
        return;
    }

    $task->delete();
    header("Location: ?status=ataskDeleted");
}

?>
