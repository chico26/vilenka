<?php

/**
 * Controller createdb helps with the creation of the database
 *
 * @author juanjo
 */
class createdb extends CI_Controller {

    var $data = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo anchor('createdb/generate', 'Generar');
    }

    function generate() {
        // prints random to avoid confusion
        echo rand(1, 9999) . "<br />";
        $this->drop();
        echo "<br />Tablas eliminadas<br />";
        echo $this->print_ok() . "Iniciando la creacion de la base de datos...<br />";
        try {
            Doctrine::createTablesFromModels();
            ## This row must exist at least empty
            echo "Tablas creadas desde los modelos sin errores <br />" . $this->print_ok();
        } catch (Exception $e) {

            echo "!Creada con errores<br />";

            $this->print_error($e->getMessage());
            echo "<br />";
            //$this->drop();
            echo "<br />Tablas eliminadas";
        }
    }

    function print_error($message) {
        ?>
        El error es:
        <div style="width: 400px; margin-left: 50px; color: #F00; background-color: #000;"><?php
        echo str_replace(".", "<br /><br />&nbsp;", $message);
        ?>
        </div><?php
    }

    function print_ok() {
        ?>
        <span style="background-color: #0ad101; color: #FFF;">OK</span>
        <?php
    }

    /**
     * Drop all tables in the database, this functions makes a loop trying to drop until there is no constraint problem
     */
    function drop() {
        include APPPATH . '/config/database.php';
        $database_name = $db['default']['database'];
        if (!$link = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'])) {
            die("Could not connect: " . mysql_error());
        }
        $sql = "SHOW TABLES FROM $database_name";
        if ($result = mysql_query($sql)) {
            /* add table name to array */
            while ($row = mysql_fetch_row($result)) {
                $found_tables[] = $row[0];
            }
        } else {
            die("Error, could not list tables. MySQL Error: " . mysql_error());
        }

        if (isset($found_tables)) {
            $existentes = count($found_tables);
            foreach ($found_tables as $table_name) {
                $sql = "DROP TABLE $database_name.$table_name";
                if ($result = mysql_query($sql)) {
                    $existentes--;
                    echo "Success - table $table_name deleted.";
                }
            }
        }else
            $existentes = 0;
        if ($existentes > 0) {
            //$this->drop();
        }
    }

}

/* End of file createdb.php */ 
/* Location: ./application/controllers/createdb.php */ 
