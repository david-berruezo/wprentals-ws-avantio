<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 10/12/2021
 * Time: 16:56
 */

class Kind{

    # avantio credential
    private $avantio_credential = "servidor";

    private $db = "";

    private $vector_selected_kinds = array(1,2);

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


    public function insertKind($avantio_credentials)
    {

        # identifiers
        $id_anterior = 0;

        # term_vector
        $term_vector = array();
        $terms_luxury = array();

        # counters
        $max_elements = 0;
        $counter_element = -1;

        # kinds of tasonomy
        $kinds = $this->getKinds($avantio_credentials);

        foreach ($kinds as $kind) {

            # name and id
            $id = intval($kind->id);
            $lang = (string)$kind->language;
            $mking_name = (string)$kind->text_title  . "-" . $lang;

            # new id
            if ( $id_anterior != $id ){
                $counter_element++;
                $id_anterior = $id;
            }// end if

            $term_vector[$lang][$counter_element] = array();

            //echo "taxonomia: ".$mking_name."<br>";

            # es | en  | others
            $my_cat = array(
                'description' => $mking_name,
                'slug' =>sanitize_title($mking_name),
                'parent' => 0
            );

            $term_vector[$lang][$counter_element] = term_exists($mking_name, 'property_category',$my_cat);
            if(!$term_vector[$lang][$counter_element] ){
                $term_vector[$lang][$counter_element] = wp_insert_term($mking_name, 'property_category',$my_cat);
                //my_print($term_vector[$lang][$counter_element]);
                pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
            }else{
                pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
            }

            # change name
            $word = "-" . $lang;
            $final_name = str_replace($word, "",$mking_name);
            $args = array('name' => $final_name , 'description' => $final_name);
            //print_r($args);
            wp_update_term( $term_vector[$lang][$counter_element]["term_id"], 'property_category', $args );


            // my_print($term_vector[$lang][$counter_element]);

            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "pagetax" => "",
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category_tax" => "property_category"
            );

            if (is_array($term_vector[$lang][$counter_element]))
                $taxonomy = "kind_".$term_vector[$lang][$counter_element]["term_id"];

            if(is_object($term_vector[$lang][$counter_element]))
                $taxonomy = "kind_".$term_vector[$lang][$counter_element]->term_id;

            $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

            if($counter_element > $max_elements)
                $max_elements = $counter_element;

        } // end foreach


        # Villa de lujo
        foreach($avantio_credentials['ACTIVED_LANGUAGES'] as $lang){

            # luxury term
            switch($lang){
                case "es": $mking_name =  "Villa de lujo" .  "-" . $lang;
                    break;
                case "en": $mking_name =  "Luxury Village" .  "-" . $lang;
                    break;
                case "ca": $mking_name =  "Vila de luxe" .  "-" . $lang;
                    break;
                case "fr": $mking_name =  "Villa de luxe" .  "-" . $lang;
                    break;
            }

            # es | en  | others
            $my_cat = array(
                'description' => $mking_name,
                'slug' =>sanitize_title($mking_name),
                'parent' => 0
                //'name' => $mking_name . "-" . $lang
            );

            $terms_luxury[$lang][0] = term_exists($mking_name, 'property_category',$my_cat);
            if(!$terms_luxury[$lang][0] ){
                $terms_luxury[$lang][0] = wp_insert_term($mking_name, 'property_category',$my_cat);
                pll_set_term_language($terms_luxury[$lang][0]["term_id"], $lang);
            }else{
                pll_set_term_language($terms_luxury[$lang][0]["term_id"], $lang);
            }


            # change name
            $word = "-" . $lang;
            $final_name = str_replace($word, "",$mking_name);
            $args = array('name' => $final_name , 'description' => $final_name);
            //print_r($args);
            wp_update_term( $terms_luxury[$lang][0]["term_id"], 'property_category', $args );


            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "pagetax" => "",
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category_tax" => "property_category"
            );

            if (is_array($terms_luxury[$lang][0]))
                $taxonomy = "kind_".$terms_luxury[$lang][0]["term_id"];

            if(is_object($terms_luxury[$lang][0]))
                $taxonomy = "kind_".$terms_luxury[$lang][0]->term_id;

            $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

        } // end foreach avantio credentials


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

        # save relationship luxury
        $vector_plantilla = array();
        for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
            $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
            $vector_plantilla[$lang] = $terms_luxury[$lang][0]["term_id"];
        }// end for
        pll_save_term_translations($vector_plantilla);


    }// end function

    private function getKinds($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select * from dynamic_taxonomy_group where language IN('".$languages."') order  by id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function

}// end class


?>