<?php

class DBLocal {

    protected static $instance;

    private static $credential = "servidor";

    # server
    private static $vector_configuration_database = array(

    );

    public function __construct() {
        // empty constructor
    }


    public static function getInstance() {

        if(empty(self::$instance)) {

            $db_info = self::$vector_configuration_database[self::getCredential()];

            try {
                self::$instance = new wpdb($db_info['db_user'],$db_info['db_pass'],$db_info['db_name'],'localhost');
            } catch(PDOException $error) {
                echo $error->getMessage();
            }

        }

        return self::$instance;

    }// end function


    private function connectPDO()
    {

        $db_info = self::$vector_configuration_database[self::getCredential()];

        self::$instance = new PDO("mysql:host=".$db_info['db_host'].';port='.$db_info['db_port'].';dbname='.$db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        self::$instance->query('SET NAMES utf8');
        self::$instance->query('SET CHARACTER SET utf8');

    } // end function


    /**
     * @return string
     */
    public static function getCredential(): string
    {
        return self::$credential;
    }


    /**
     * @param string $credential
     */
    public static function setCredential(string $credential): void
    {
        self::$credential = $credential;
    }

    /**
     * @return string[][]
     */
    public static function getVectorConfigurationDatabaseSelected(): array
    {
        return self::$vector_configuration_database[self::getCredential()];
    }

    /**
     * @return string[][]
     */
    public static function getVectorConfigurationDatabase(): array
    {
        return self::$vector_configuration_database;
    }


    /**
     * @param string[][] $vector_configuration_database
     */
    public static function setVectorConfigurationDatabase(array $vector_configuration_database): void
    {
        self::$vector_configuration_database = $vector_configuration_database;
    }



    public static function setCharsetEncoding() {

        if (self::$instance == null) {
            self::connect();
        }

        self::$instance->exec(
            "SET NAMES 'utf8';
            SET character_set_connection=utf8;
            SET character_set_client=utf8;
            SET character_set_results=utf8"
        );

    }// end function

}// end class
?>