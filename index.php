<?php

require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

switch ( $action ) {
  case 'newTask':
      newTask();
      break;
  default:
    homepage();
}

function newTask() {

    $results = array();
    $results['pageTitle'] = "New Task";
    $results['formAction'] = "newTask";

    if ( isset( $_POST['saveChanges'] ) ) {

        // Пользователь получает форму редактирования статьи: сохраняем новый таск
        $task = new Task;
        $task->storeFormValues( $_POST );
        $task->insert();
        header( "Location: /?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросид результаты редактирования: возвращаемся к списку тасков
        header( "Location: index.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['task'] = new Task;
        require( TEMPLATE_PATH . "/admin/editTask.php" );
    }

}


function homepage() {
  $results = array();
  $data = Task::getList( HOMEPAGE_NUM_TASKS);
  $results['tasks'] = $data['results'];
  $results['totalResults'] = $data['totalResults'];
  $results['totalPages'] = $data['totalPages'];
  $results['pageTitle'] = "Tasks";
  $results['formAction'] = "newTask";
  require( TEMPLATE_PATH . "/homepage.php" );
}

?>
