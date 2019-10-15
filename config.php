<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Europe/Moscow" );
define( "DB_DSN", "mysql:host=localhost;dbname=custom-cms;charset=utf8" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_TASKS", 3 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "123" );
require( CLASS_PATH . "/Task.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
  echo $exception->getMessage() ;
}

set_exception_handler( 'handleException' );
?>
