<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 10/12/2021
 * Time: 18:02
 */


class KindAlquiler
{

    # db
    private $db = "";

    # name of kindAlquiler
    private $name = "Alquiler toda la Villa";

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

    public function insertKindsAlquiler($avantio_credentials)
    {

        # term_vector
        $term_vector = array();

        # counters
        $counter_element = 0;
        $max_elements = 0;

        foreach($avantio_credentials['ACTIVED_LANGUAGES'] as $lang){

            $counter_element = 0;

            $term_vector[$lang][$counter_element] = array();

            $mking_name = "";

            switch($lang){

                case "es": $mking_name = "Alquiler toda la Villa";
                    break;

                case "en": $mking_name = "Rent all Villa";
                    break;

                case "ca": $mking_name = "Lloguer de tota la Villa";
                    break;

                case "fr": $mking_name = "Louer toute la Villa";
                    break;

            }// end switch

            # es | en  | others
            $my_cat = array(
                'description' => $mking_name,
                'slug' =>sanitize_title($mking_name),
                'parent' => 0
            );

            $term_vector[$lang][$counter_element] = term_exists($mking_name, 'property_action_category',$my_cat);
            if(!$term_vector[$lang][$counter_element] ){
                $term_vector[$lang][$counter_element] = wp_insert_term($mking_name, 'property_action_category',$my_cat);
                //my_print($term_vector[$lang][$counter_element]);
                pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
            }else{
                pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
            }


            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "pagetax" => "",
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category_tax" => "property_action_category"
            );

            if (is_array($term_vector[$lang][$counter_element]))
                $taxonomy = "kindalquiler_".$term_vector[$lang][$counter_element]["term_id"];

            if(is_object($term_vector[$lang][$counter_element]))
                $taxonomy = "kindalquiler_".$term_vector[$lang][$counter_element]->term_id;

            $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

            $counter_element++;

            if($counter_element > $max_elements)
                $max_elements = $counter_element;

        }// end foreach

        for($i = 0; $i < $max_elements; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for


    }// en function

}// end class

?>