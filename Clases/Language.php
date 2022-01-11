<?php

class Language
{

    # database connection
    private $db = "";


    /**
     * @return array|bool
     */
    public function getAll()
    {
        $sql = "select * from avantio_accomodations";
        $registros = $this->db->get_results( $sql, ARRAY_N  );

        return ($registros) ?  $registros : false;

    } // end function


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

