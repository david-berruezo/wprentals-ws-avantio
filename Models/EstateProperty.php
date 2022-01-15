<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 29/11/2021
 * Time: 20:05
 */

class EstateProperty{

    // avantio
    private $avantio_credentials;
    private $avantio_files;

    # avantio credential
    private $avantio_credential = "servidor";

    // post_id
    private $post_id = 0;

    # Objects Nuevo and KindAlquiler
    private $status = "";
    private $kindAlquiler = "";

    # db wpdb connection
    private $db = "";


    public function __construct()
    {
        // constructor
    }


    /**
     * @return string
     */
    public function getAvantioCredential(): string
    {
        return $this->avantio_credential;
    }


    /**
     * @param string $avantio_credential
     */
    public function setAvantioCredential(string $avantio_credential): void
    {
        $this->avantio_credential = $avantio_credential;
    }


    public function connectDb()
    {
        $connector = new Database();
        $connector->setCredential($this->getAvantioCredential());
        $this->db = $connector::getInstance();
    } // end function


    public function insertEstateProperty()
    {

        $sql = "select * from avantio_accomodations as ac
left join dynamic_rooms as dr on dr.id = ac.id order by ac.id asc;";
        $xml = $this->db->get_results( $sql, OBJECT );
        foreach ($xml as $accommodation) {

            $id = (int)$accommodation->id;
            $text_title = (string)$accommodation->text_title;

            # kind | taxonomy
            /*
            $dynamic_taxonomy = intval($accommodation->MasterKind->MasterKindCode);
            $dynamic_taxonomy_group = intval($accommodation->MasterKind->MasterKindCode);
            $taxonomy_name = (string)$accommodation->MasterKind->MasterKindName;
            */

            # insert
            if (get_post_status($id) && $id == 60505) {

            # update
            } else if($id == 60505){


            }// end if

        } // end foreach


        /*
        # create cutom post type and insert and update
        # $this->create_post_type_estate_property();
        //$this->insert_estate_property();
        //$this->update_estate_property();

        # vectors
        $post_vector = array();

        # counters
        $counter_property = 0;
        $counter_property_create = 0;
        $counter_language = 0;


        foreach ($xml->Accommodation as $accommodation) {

            //if ($counter_property < 1) {

            //echo "entra aqui<br>";

            # normal fields
            $accion = "insert_or_update";
            $id = (int)$accommodation->AccommodationId;
            $text_title = (string)$accommodation->AccommodationName;
            # kind | taxonomy
            $dynamic_taxonomy = intval($accommodation->MasterKind->MasterKindCode);
            $dynamic_taxonomy_group = intval($accommodation->MasterKind->MasterKindCode);
            $taxonomy_name = (string)$accommodation->MasterKind->MasterKindName;
            // echo "identificador: " .$id. "<br>";

            # villa o villa de lujo
            if ($accommodation->Labels) {
                foreach ($accommodation->Labels->children() as $mylabel) {
                    if (stripos($mylabel, "villa_de_lujo") !== false) {
                        $this->tipo_de_villa = "villa_de_lujo";
                    }// end if
                }// end foreach
            }// end if

            # update
            if (get_post_status($id) && $id == 60505) {

                echo "entra edit<br>";
                $my_post_translation = pll_get_post_translations($id);
                my_print($my_post_translation);
                foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
                    $post_vector[$lang][$counter_property]["post_id"] = $my_post_translation[$lang];
                }// end foreach


                $lang_property = array_search($id , $my_post_translation);
                p_($lang_property);
                echo "lang property " .$lang_property. "<br>";

                //$this->guardar_caracteristicas($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang_property);

                # insert
            } else if($id == 60505){

                echo "entra insert<br>";

                $accion = "insert";
                $counter_language = 0;
                $my_post_es = 0;

                // insertar en todos los idiomas
                foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {

                    # primer lenguage que llega se crea el post con el id del xml
                    if ($counter_language == 0) {
                        $post = $this->create_post($id, $text_title, $description = "<p>Sin contenido</p>", "insert");
                        # otros lenguages se crean con el id 0
                    } else {
                        $post = $this->create_post(0, $text_title, $description = "<p>Sin contenido</p>", "new");
                    }// end if

                    # insert post and save into vector
                    $post_id = wp_insert_post($post, true);
                    my_print($post_id);

                    $post_vector[$lang][$counter_property]["post_id"] = $post_id;
                    my_print($post_vector);

                    # save language
                    pll_set_post_language($post_vector[$lang][$counter_property]["post_id"], $lang);

                    if($lang == "es")
                        $my_post_es = $post_id;

                    $counter_language++;
                    $counter_property_create++;

                    //echo "cotnador_create: ".$counter_property_create."<br>";

                    $this->guardar_caracteristicas($taxonomy_name, $post_id, $avantio_credentials , $accommodation , $lang);

                }// end foreach actived languages


            }// end if get_post_status

            $counter_property++;
        */

    } // end function

    private function getTaxonomyById($id)
    {
        $sql = "select * from dynamic_taxonomy as dt
where dt.id";
        $xml = $this->db->get_results( $sql, OBJECT );

    } // end function

    /**
     * @return mixed
     */
    public function getAvantioCredentials()
    {
        return $this->avantio_credentials;
    }

    /**
     * @param mixed $avantio_credentials
     */
    public function setAvantioCredentials($avantio_credentials)
    {
        $this->avantio_credentials = $avantio_credentials;
    }

    /**
     * @return mixed
     */
    public function getAvantioFiles()
    {
        return $this->avantio_files;
    }

    /**
     * @param mixed $avantio_files
     */
    public function setAvantioFiles($avantio_files)
    {
        $this->avantio_files = $avantio_files;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getKindAlquiler()
    {
        return $this->kindAlquiler;
    }

    /**
     * @param string $kindAlquiler
     */
    public function setKindAlquiler($kindAlquiler)
    {
        $this->kindAlquiler = $kindAlquiler;
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
    } // end function



}// end class
?>