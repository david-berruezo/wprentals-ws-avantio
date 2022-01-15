<?php

class Service{

    # db
    private $db = "";

    # avantio credential
    private $avantio_credential = "servidor";



    public function __construct()
    {
        // empty
    } // end function


    public function connectDb()
    {
        $connector = new Database();
        $connector->setCredential($this->getAvantioCredential());
        $this->db = $connector::getInstance();
    } // end function



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


    public function insertServices($avantio_credentials)
    {

        # term_vector
        $term_vector = array();

        # counters
        $max_elements = 0;
        $counter_element = -1;
        $counter_service = -1;

        # identifiers
        $id_anterior = 0;

        # get accommodations
        $xml =  $this->getServices($avantio_credentials);
        //p_($xml);

        foreach ($xml as $service) {

            if($counter_service <= 10 ){

                # data
                $service_code = intval($service->id);
                $service_name = (string)$service->text_title;
                $lang = (string)$service->language;

                if ( $id_anterior != $service_code ){
                    $id_anterior = $service_code;
                    $counter_element++;
                    $counter_service++;
                } // end if

                $term_vector[$lang][$counter_element] = array();
                $name_total_service = $service_name . "-" . $lang;

                # es | en  | others
                $my_cat = array(
                    'description' => $name_total_service,
                    'slug' => sanitize_title($name_total_service),
                    'parent' => 0
                );

                $term_vector[$lang][$counter_element] = term_exists($name_total_service, 'extra_services', $my_cat);

                //p_($my_cat);
                //p_($term_vector);

                if (!$term_vector[$lang][$counter_element]) {
                    $term_vector[$lang][$counter_element] = wp_insert_term($name_total_service, 'extra_services', $my_cat);
                    //p_($term_vector);
                    pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
                } else {
                    pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
                }

                # change name
                $word = "-" . $lang;
                $final_name = str_replace($word, "", $name_total_service);
                $args = array('name' => $final_name, 'description' => $final_name);
                //print_r($args);
                wp_update_term($term_vector[$lang][$counter_element]["term_id"], 'extra_services', $args);

                if (is_array($term_vector[$lang][$counter_element]))
                    $term_id = $term_vector[$lang][$counter_element]["term_id"];

                if(is_object($term_vector[$lang][$counter_element]))
                    $term_id = $term_vector[$lang][$counter_element]->term_id;

                # add option save cityparent category_fetured_image and others
                $term_data = array(
                    "category" => "extra_services",
                    //"id-servicio" => $service_code,
                    "lang" => $lang,
                    "term_id" => $term_id
                );

                $taxonomy = "service_".$service_code;

                $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

                if ($counter_element > $max_elements)
                    $max_elements = $counter_element;


            }// end counter service


        }// end foreach

        # save relationship polylang languages
        for($i = 0; $i < $max_elements; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for

    }// end function

    private function create_post($id, $title , $description , $action){

        $current_user  =   wp_get_current_user();
        $new_user_id   = $current_user->ID;
        $new_status = 'publish';
        $post = "";

        if($action == "insert"){

            $post = array(
                'import_id'    => $id,
                'post_title'	=> sanitize_title($title),
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property',
                'post_author'   => $new_user_id ,
                'post_content'  => $description
            );

        }else if($action == "update" || $action == "test" || $action == "new"){

            $post = array(
                'ID'            => $id,
                'post_title'	=> sanitize_title($title),
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property',
                'post_author'   => $new_user_id ,
                'post_content'  => $description
            );
        }else if($action == "nada"){

            $post = array(
                'post_title'	=> sanitize_title($title),
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property',
                'post_author'   => $new_user_id ,
                'post_content'  => $description
            );

        }// end if

        //my_print($post);
        return $post;
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



    public function selectService()
    {
        $selected_services = $this->getServiceSelected();
        foreach ($selected_services as $service) {

        } // end foreach
        $term_meta =  get_option( "taxonomy_36373");
        print_r($term_meta);
        /*
         * [id-servicio] => 1 [lang] => en )
         */
    } // end function


    /**
     * @return bool
     */
    private function getServices($avantio_credentials)
    {
        $languages = implode("','" , $avantio_credentials['ACTIVED_LANGUAGES']);

        $sql = "select * from dynamic_services
where language IN('".$languages."');";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;

    } // end function



    /**
     * @return bool
     */
    private function getServiceSelected()
    {

        $sql = "select * from avantio_accomodations_extras";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;

    } // end function


}


