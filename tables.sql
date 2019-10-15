DROP TABLE IF EXISTS tasks;
CREATE TABLE tasks
(
  id              smallint unsigned NOT NULL auto_increment,
  name            varchar(255) NOT NULL,                      # Имя
  email           varchar(255) NOT NULL,                      # email
  content         text NOT NULL,                              # HTML содержание таска
  status          tinyint(1) NOT NULL,                        # Статус выполнения
  update_admin    tinyint(1) NOT NULL,                        # Обновлял ли админ

  PRIMARY KEY     (id)
);
