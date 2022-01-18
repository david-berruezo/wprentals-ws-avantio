<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 04/11/2021
 * Time: 9:00
 */

class InsertDeleteTermsTaxonomies
{

    # avantio credential
    private $avantio_credential = "servidor";
    private $avantio_credential_local = "servidor";

    # db
    private $db = "";
    private $dblocal = "";

    public function __construct()
    {
        // construct
    } // end function


    public function connectDb()
    {
        # db
        $connector = new DB();
        $connector->setCredential($this->getAvantioCredential());
        $this->db = $connector::getInstance();
        # dblocal
        $connector = new DBLocal();
        $connector->setCredential($this->getAvantioCredentialLocal());
        $this->dblocal = $connector::getInstance();

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

    /**
     * @return string
     */
    public function getAvantioCredentialLocal(): string
    {
        return $this->avantio_credential_local;
    }

    /**
     * @param string $avantio_credential_local
     */
    public function setAvantioCredentialLocal(string $avantio_credential_local): void
    {
        $this->avantio_credential_local = $avantio_credential_local;
    }

    /**
     * @return string
     */
    public function getDb(): string
    {
        return $this->db;
    }

    /**
     * @param string $db
     */
    public function setDb(string $db): void
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getDblocal(): string
    {
        return $this->dblocal;
    }

    /**
     * @param string $dblocal
     */
    public function setDblocal(string $dblocal): void
    {
        $this->dblocal = $dblocal;
    }

    function insert_all(){
        $this->insert_features();
        $this->insert_geolocations();
        $this->insert_kind();
        $this->insert_kindAlquiler();
        $this->insert_status();
        //$this->insert_taxononomy_property_swimming_pool();
        //$this->insert_term_taxonomy_multilanguage();
        $this->insert_services();

    } // end function


    function insert_features(){

        # language
        # active languanges
        $language = new Language();
        $language->setDb($this->db);
        $avantio_credentials = array(
            'ACTIVED_LANGUAGES' => $language->getAll(),
        );

        # feature
        $feature = new Feature();
        $feature->createFeatures($avantio_credentials);
    }



    function insert_geolocations(){
        global $avantio_credential;

        # language
        # active languanges
        $language = new Language();
        $language->setDb($this->db);
        $avantio_credentials = array(
            'ACTIVED_LANGUAGES' => $language->getAll(),
        );

        # areas db
        $geo = new GeographicArea();
        $geo->setAvantioCredential($avantio_credential);
        $geo->setDb($this->db);
        # areas inserts
        $geo->insertGeographicAreas($avantio_credentials);

    }


    function insert_kind(){
        global $avantio_credential;

        # language
        # active languanges
        $language = new Language();
        $language->setDb($this->db);
        $avantio_credentials = array(
            'ACTIVED_LANGUAGES' => $language->getAll(),
        );

        # kind
        $kind = new Kind();
        $kind->setAvantioCredential($avantio_credential);
        $kind->setDb($this->db);
        # inserts kinds
        $kind->insertKind($avantio_credentials);

    }

    function insert_kindAlquiler(){

        # language
        # active languanges
        $language = new Language();
        $language->setDb($this->db);
        $avantio_credentials = array(
            'ACTIVED_LANGUAGES' => $language->getAll(),
        );

        # kind
        $kindAlquiler = new KindAlquiler();
        $kindAlquiler->insertKindsAlquiler($avantio_credentials);

    }

    function insert_status(){

        # language
        # active languanges
        $language = new Language();
        $language->setDb($this->db);
        $avantio_credentials = array(
            'ACTIVED_LANGUAGES' => $language->getAll(),
        );

        # kind
        $status = new Status();
        $status->insertStatus($avantio_credentials);

    }


    function insert_services(){

        # db of avantio
        global $avantio_credential , $db;

        # language
        # active languanges
        $language = new Language();
        $language->setDb($db);
        $avantio_credentials = array(
            'ACTIVED_LANGUAGES' => $language->getAll(),
        );

        # service
        # credential and database
        $service = new Service();
        $service->setAvantioCredential($avantio_credential);
        $service->setDb($this->db);
        //$service->connectDb();
        # insert
        $service->insertServices($avantio_credentials);

    }


    function insert_taxononomy_property_swimming_pool($avantio_credentials)
    {
        $total = 3;
        $max_elements = 0;
        $counter_element = 0;
        $term_vector = array();

        for ($var = 0; $var < $total; $var++) {

            foreach($avantio_credentials['ACTIVED_LANGUAGES'] as $lang){

                $term_vector[$lang][$counter_element] = array();
                // echo "taxonomia: ".$mking_name."<br>";
                $mking_name = "";

                switch($var){

                    case 0:

                        switch($lang){

                            case "es": $mking_name = "Piscina comunitaria";
                                break;

                            case "en": $mking_name = "Communal swimming pool";
                                break;

                            case "ca": $mking_name = "Piscina comunitaria";
                                break;

                            case "fr": $mking_name = "piscine communautaire";
                                break;

                        }// end switch

                        break;
                    case 1:

                        switch($lang){

                            case "es": $mking_name = "Piscina privada";
                                break;

                            case "en": $mking_name = "Private swimming pool";
                                break;

                            case "ca": $mking_name = "Piscina privada";
                                break;

                            case "fr": $mking_name = "Piscine privée";
                                break;

                        }// end switch

                        break;
                    case 2:

                        switch($lang){

                            case "es": $mking_name = "Sin piscina";
                                break;

                            case "en": $mking_name = "No swimming pool";
                                break;

                            case "ca": $mking_name = "No piscina";
                                break;

                            case "fr": $mking_name = "pas de piscine";
                                break;

                        }// end switch

                        break;
                }



                # es | en  | others
                $my_cat = array(
                    'description' => $mking_name,
                    'slug' =>sanitize_title($mking_name),
                    'parent' => 0
                );

                $term_vector[$lang][$counter_element] = term_exists($mking_name, 'property_service_piscina',$my_cat);
                if(!$term_vector[$lang][$counter_element] ){
                    $term_vector[$lang][$counter_element] = wp_insert_term($mking_name, 'property_service_piscina',$my_cat);
                    //my_print($term_vector[$lang][$counter_element]);
                    pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
                }else{
                    pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
                }


            }// end foreach

            $counter_element++;

            if($counter_element > $max_elements)
                $max_elements = $counter_element;

        } // end for

        # save associations language
        for($i = 0; $i < $max_elements; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
        }// end for

        //p_($vector_plantilla);

        /*
        Array
        (
            [ca] => 3005
    [en] => 3007
    [es] => 3009
    [fr] => 3011
    )
    */

    } // end function



    function insert_term_taxonomy_multilanguage($taxonomy , $terms)
    {
        $term_vector = array();
        $term_final_vector = array();

        foreach ($terms as $lang => $term_value) {
            $name = sanitize_title($term_value);
            $my_cat = array(
                'description' => $term_value,
                'slug' =>sanitize_title($term_value)
            );
            $term_vector[$lang] = term_exists($name, $taxonomy);
            if(!$term_vector ){
                $term_vector[$lang] = wp_insert_term($name, $taxonomy);
                pll_set_term_language($term_vector[$lang]["term_id"], $lang);
                $term_final_vector[$lang] = $term_vector[$lang]["term_id"];
            }else{
                pll_set_term_language($term_vector[$lang]["term_id"], $lang);
                $term_final_vector[$lang] = $term_vector[$lang]["term_id"];
            }// end if
        } // end foreach

        //my_print($term_final_vector);
        pll_save_term_translations($term_final_vector);

    } // end function


    # delete functions

    function delete_all(){
        # delete thumbnail post type and attachments start blogmedia
        $this->delete_thumbnail_post_type("estate_property");
        $this->delete_attachments_starts_blog();
        # delete all posts of custom post type
        $this->delete_all_posts_of_custom_type();
        # delete all taxonomies
        //$this->delete_all_taxonomies();
        # delete terms
        $this->delete_terms_of_taxonomies();
        # delete options
        $this->delete_all_options_taxonomies();
    }

    function delete_thumbnail_post_type($post_type){
        $sql = " DELETE m FROM wp_postmeta m JOIN wp_posts p ON m.post_id = p.id WHERE p.post_type = '".$post_type."'  AND meta_key = '_thumbnail_id' ";
        $response = $this->dblocal->query($sql);

        return $response;
    }


    function delete_attachments_starts_blog(){
        $sql = "delete from wp_posts 
where post_type = 'attachment' and post_title like 'blogmedia%' ";
        $response = $this->dblocal->query($sql);

        return $response;
    }

    # delete all post types
    function delete_all_posts_of_custom_type(){
        //$this->delete_posts_by_post_type("estate_property");
    }


    function delete_posts_by_post_type($post_type){
        $args = array(
            'post_type' => $post_type
        );
        $posts = get_posts($args);
        $ids = array_column($posts,"ID");
        clean_object_term_cache( $ids, $post_type );
        foreach ($posts as $post) {
            $delete = wp_delete_post($post->ID,true);
        } // end foreach
    }

    function delete_posts_by_post_type_sql($post_type){

        $sql = " DELETE FROM wp_posts WHERE post_type = '".$post_type."' ";
        $response = $this->dblocal->query($sql);

        //$sql = " DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts) ";
        //$response = $this->dblocal->query($sql);

        //$sql = " DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts) ";
        //$response = $this->dblocal->query($sql);

        //$this->dblocal
    }


    # delete taxonomies
    function delete_all_taxonomies(){
        $this->delete_taxonomy( 'property_category');
        $this->delete_taxonomy( 'property_action_category');
        $this->delete_taxonomy( 'property_city');
        $this->delete_taxonomy( 'property_area');
        $this->delete_taxonomy( 'property_features');
        $this->delete_taxonomy( 'property_status');
        //$this->delete_taxonomy( 'property_service_piscina');
        $this->delete_taxonomy( 'extra_services');
    }

    function delete_taxonomy($taxonomy){
        unregister_taxonomy( $taxonomy);
    }

    # delete terms of taxonomies
    function delete_terms_of_taxonomies(){
        $this->delete_all_terms( 'property_category');
        $this->delete_all_terms( 'property_action_category');
        $this->delete_all_terms( 'property_city');
        $this->delete_all_terms( 'property_area');
        $this->delete_all_terms( 'property_features');
        $this->delete_all_terms( 'property_status');
        $this->delete_all_terms( 'extra_services');
        //delete_all_terms( 'property_service_piscina');

    }

    function delete_all_terms($taxonomy_name){
        $terms = get_terms( array(
            'taxonomy' => $taxonomy_name,
            'hide_empty' => false
        ));
        foreach ( $terms as $term ) {
            wp_delete_term($term->term_id, $taxonomy_name);
        }
    }

    # delete options taxonomies
    function delete_all_options_taxonomies(){
        $this->delete_option_by_taxonomy("taxonomy");
        $this->delete_option_by_taxonomy("locality");
        $this->delete_option_by_taxonomy("city");
        $this->delete_option_by_taxonomy("region");
        $this->delete_option_by_taxonomy("service");
    }// end function


    function delete_option_by_taxonomy($string_taxonomy){

        # db
        $connector = new DB();
        $connector->setCredential("wordpress_servidor");
        $db = $connector::getInstance();

        $text = $string_taxonomy . "%";
        $sql = "delete from wp_options where option_name like '".$text."' ";
        $response = $db->query($sql);

        return $response;

    }



}// end class