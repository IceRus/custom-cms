<?php

/**
 * Класс для обработки тасков
 */

class Task
{
    // Свойства

    /**
     * @var int ID таска из базы данных
     */
    public $id = null;

    /**
     * @var string Имя пользователя
     */
    public $name = null;

    /**
     * @var string Email пользователя
     */
    public $email = null;

    /**
     * @var string HTML содержание таска
     */
    public $content = null;

    /**
     * @var int Статус таска
     */
    public $status = null;

    /**
     * @var int админ поменял текст
     */
    public $update_admin = null;

    /**
     * Устанавливаем свойства с помощью значений в заданном массиве
     *
     * @param assoc Значения свойств
     */

    public function __construct($data = array())
    {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['name'])) $this->name = preg_replace("/[^\w_]+/u", "", $data['name']);
        if (isset($data['email']) && preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $data['email'])) $this->email = $data['email'];
        if (isset($data['content'])) $this->content = $data['content'];
        if ($_SESSION['username'] == ADMIN_USERNAME) {
            if (isset($data['status'])) $this->status = (int)$data['status'];
        } else {
            $this->status = 0;
        }
        if (isset($data['update_admin'])) $this->update_admin = $data['update_admin'];
    }


    /**
     * Устанавливаем свойств с помощью значений формы редактирования таска в заданном массиве
     *
     * @param assoc Значения таска формы
     */

    public function storeFormValues($params)
    {
        // Сохраняем все параметры
        $this->__construct($params);
    }


    /**
     * Возвращаем объект таска соответствующий заданному ID
     *
     * @param int ID таска
     * @return Tasks|false Объект таска или false, если таск не найдена или возникли проблемы
     */

    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) return new Task($row);
    }


    /**
     * Возвращает все (или диапазон) объектов тасков в базе данных
     *
     * @param int Optional Количество строк (по умолчанию все)
     * @param string Optional Столбец по которому производится сортировка  тасков (по умолчанию "id DESC")
     * @return Array|false Двух элементный массив: results => массив, список объектов тасков; totalRows => общее количество тасков
     */

    public static function getList()
    {
        $order = '';
        if(isset( $_GET['order'] )) {
            $order .= "ORDER BY ". $_GET['order'];
            if(isset( $_GET['order_2'] )) {
                $order .= ' ' . $_GET['order_2'];
            }
            else {
                $order .= ' DESC';
            }
        }
        else {
            $order = "ORDER BY id DESC";
        }

        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM tasks";
        $st = $conn->prepare($sql);
        $st->execute();
        $total_results = $st->rowCount();
        $total_pages = ceil($total_results/HOMEPAGE_NUM_TASKS);


        if (!isset($_GET['page'])) {
            $page = 1;
        } else{
            $page = $_GET['page'];
        }

        $starting_limit = ($page-1)*HOMEPAGE_NUM_TASKS;

        $show  = "SELECT * FROM tasks " . $order ." LIMIT ".$starting_limit.",". HOMEPAGE_NUM_TASKS;

        $r = $conn->prepare($show);
        $r->execute();

        $list = array();

        while ($row = $r->fetch()) {
            $task = new Task($row);
            $list[] = $task;
        }

        $conn = null;
        return (array("results" => $list, "totalResults" => $total_results, "totalPages" => $total_pages));
    }


    /**
     * Вставляем текущий объект таска в базу данных, устанавливаем его свойства.
     */

    public function insert()
    {

        // Есть у объекта таска ID?
        if (!is_null($this->id)) trigger_error("Task::insert(): Attempt to insert an Task object that already has its ID property set (to $this->id).", E_USER_ERROR);

        // Вставляем статью
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

        $sql = "INSERT INTO tasks ( name, email, content, status ) VALUES ( :name, :email, :content, :status )";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
        $st->bindValue(":email", $this->email, PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, PDO::PARAM_STR);
        $st->bindValue(":status", $this->status, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }


    /**
     * Обновляем текущий объект таска в базе данных
     */

    public function update()
    {

        // Есть ли у объекта таска ID?
        if (is_null($this->id)) trigger_error("Task::update(): Attempt to update an Task object that does not have its ID property set.", E_USER_ERROR);

        // Обновляем таск
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

        $sql_v = 'name=:name, email=:email, content=:content, status=:status';

        if($this->update_admin == true){
            $sql_v .= ', update_admin=:update_admin';
        }

        $sql = "UPDATE tasks SET $sql_v WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
        $st->bindValue(":email", $this->email, PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->bindValue(":status", $this->status, PDO::PARAM_INT);
        if($this->update_admin == true) {
            $st->bindValue(":update_admin", $this->update_admin, PDO::PARAM_BOOL);
        }
        $st->execute();
        $conn = null;
    }


    /**
     * Удаляем текущий объект таска из базы данных
     */

    public function delete()
    {

        // Есть ли у объекта таска ID?
        if (is_null($this->id)) trigger_error("Task::delete(): Attempt to delete an Task object that does not have its ID property set.", E_USER_ERROR);

        // Удаляем таска
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $st = $conn->prepare("DELETE FROM tasks WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }

}

?>
