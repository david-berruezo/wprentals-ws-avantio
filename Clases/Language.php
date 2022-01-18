<?php

class Language
{

    # database connection
    private $db = "";


    public function __construct()
    {
        // empty constructor
    }


    /**
     * @return array|bool
     */
    public function getAll()
    {
        $sql = "select id from languages";
        $registros = $this->db->get_col( $sql );

        return ($registros) ?  $registros : false;

    } // end function


    function separar_comas($vector){
        $langs = implode("," , $vector);
        return $langs;
    }


    /**
     * @return string
     */
    public function getDb()
    {
        return $this->db;
    }


    /**
     * @param string $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }


}// end class

