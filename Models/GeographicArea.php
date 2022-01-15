<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 10/12/2021
 * Time: 6:46
 */


class GeographicArea{

    # avantio credential
    private $avantio_credential = "servidor";

    private $vector_found = array(
        "Not s",
        "Non s",
        "Sin e",
        "Not specified",
        "Sin especificar",
    );


    private $db = "";


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



    public function add_meta_info()
    {
        //create a custom meta box
        add_meta_box( 'geo-info', 'Set Image', 'geo_info_function', 'post', 'normal', 'high' );
    } // end function

    function geo_info_function( $post ) {

        // retrieve the meta data value if it exists
        $boj_mbe_image = get_post_meta( $post->ID, '_boj_mbe_image', true );
        ?>
        Image <input id="boj_mbe_image" type="text" size="75" name="boj_mbe_image" value="<?php echo esc_url( $boj_mbe_image ); ?>" />
        <input id="upload_image_button" type="button" value="Media Library Image" class="button-secondary"  />
        <p>Enter an image URL or use an image from the Media Library</p>
        <?php
    }


    public function insertGeographicAreas($avantio_credentials)
    {
        # vectors
        $post_vector = array();

        # identifiers
        $id_anterior = 0;

        # vectors terms | term_vector
        $term_vector = array();
        $term_vector_region = array();
        $term_vector_city = array();
        $term_vector_locality = array();
        $term_vector_district = array();

        # max elements
        $max_elements_region = 0;
        $max_elements_city = 0;
        $max_elements_locality = 0;
        $max_elements_district = 0;
        $max_elements_ = 0;

        # counters
        $counter_property = -1;
        $counter_property_create = 0;
        $counter_element_region = -1;
        $counter_element_city = -1;
        $counter_element_locality = -1;
        $counter_element_district = -1;
        $counter_element_ = -1;
        $counter = 0;


        # queries get geo
        $countries  = $this->getGeocountry($avantio_credentials);
        $regions    = $this->getGeoregion($avantio_credentials);
        $cities     = $this->getGeocity($avantio_credentials);
        $localities = $this->getGeolocality($avantio_credentials);
        $districts  = $this->getGeodistrict($avantio_credentials);

        // p_($regions);

        # region
        foreach ($regions as $region) {

            # properties
            $id = (int)$region->id;
            $text_title = (string)$region->text_title;
            $lang = (string)$region->language;

            # new id
            if ( $id_anterior != $id ){
                $counter_element_region++;
                $id_anterior = $id;
            }// end if


            # name and id
            $mkind_id = intval($region->id);
            $mking_name = (String)$region->text_title . "-" . $lang;
            $my_cat = array(
                'description' => $mking_name,
                'slug' => sanitize_title($mking_name),
                'parent' => 0
            );

            $term_vector_region[$lang][$counter_element_region] = term_exists($mking_name, 'property_city', $my_cat);

            if (!$term_vector_region[$lang][$counter_element_region]) {
                $term_vector_region[$lang][$counter_element_region] = wp_insert_term($mking_name, 'property_city', $my_cat);
                //my_print($term_vector_region[$lang][$counter_element_region]);
                pll_set_term_language($term_vector_region[$lang][$counter_element_region]["term_id"], $lang);
            } else {
                pll_set_term_language($term_vector_region[$lang][$counter_element_region]["term_id"], $lang);
            }

            # change name
            $word = "-" . $lang;
            $final_name = str_replace($word, "", $mking_name);
            $args = array('name' => $final_name, 'description' => $final_name);
            //print_r($args);
            wp_update_term($term_vector_region[$lang][$counter_element_region]["term_id"], 'property_city', $args);

            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "id" => $id,
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "taxonomy" => "property_city",
                "destacado_menu" => false,
            );

            if (is_array($term_vector_region[$lang][$counter_element_region]))
                $taxonomy = "region_" . $term_vector_region[$lang][$counter_element_region]["term_id"];

            if (is_object($term_vector_region[$lang][$counter_element_region]))
                $taxonomy = "region_" . $term_vector_region[$lang][$counter_element_region]->term_id;

            $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

            if($counter_element_region > $max_elements_region)
                $max_elements_region = $counter_element_region;

        }// end foreach


        # identifiers
        $id_anterior = 0;

        # city
        foreach ($cities as $city) {

            //if($counter_element_city <= 0 ){

                # properties
                $id = (int)$city->id;
                $text_title = (string)$city->text_title;
                $lang = (string)$city->language;

                # new id
                if ( $id_anterior != $id ){
                    $counter_element_city++;
                    $id_anterior = $id;
                }// end if

                # name and id
                $mkind_id = intval($city->id);
                $mking_name = (string)$city->text_title . "-" . $lang;

                # get region term
                $region_name = $this->getRegionNameByIdLnag($city->dynamic_georegion,$lang);
                $term_id = $this->getRegionTermId($region_name , $lang);

                $my_cat = array(
                    'description' => $mking_name,
                    'slug' => sanitize_title($mking_name),
                    'parent' => $term_id
                );


                $term_vector_city[$lang][$counter_element_city] = term_exists($mking_name, 'property_city', $my_cat);
                if (!$term_vector_city[$lang][$counter_element_city]) {
                    $term_vector_city[$lang][$counter_element_city] = wp_insert_term($mking_name, 'property_city', $my_cat);
                    //my_print($term_vector_city[$lang][$counter_element_city]);
                    pll_set_term_language($term_vector_city[$lang][$counter_element_city]["term_id"], $lang);
                } else {
                    pll_set_term_language($term_vector_city[$lang][$counter_element_city]["term_id"], $lang);
                }

                # change name
                $word = "-" . $lang;
                $final_name = str_replace($word, "", $mking_name);
                $args = array('name' => $final_name, 'description' => $final_name);
                //print_r($args);
                wp_update_term($term_vector_city[$lang][$counter_element_city]["term_id"], 'property_city', $args);

                # add option save cityparent category_fetured_image and others
                $term_data = array(
                    "id" => $id,
                    "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                    "category" => "property_city",
                    "destacado_menu" => false,
                );

                if (is_array($term_vector_city[$lang][$counter_element_city]))
                    $taxonomy = "city_" . $term_vector_city[$lang][$counter_element_city]["term_id"];

                if (is_object($term_vector_city[$lang][$counter_element_city]))
                    $taxonomy = "city_" . $term_vector_city[$lang][$counter_element_city]->term_id;

                $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');


                if($counter_element_city > $max_elements_city)
                    $max_elements_city = $counter_element_city;

            // } // end if counter_element_city

        }// end foreach

        # identifiers
        $id_anterior = 0;


         # locality
        foreach ($localities as $locality) {

            # properties
            $id = (int)$locality->id;
            $text_title = (string)$locality->text_title;
            $lang = (string)$locality->language;

            # new id
            if ( $id_anterior != $id ){
                $counter_element_locality++;
                $id_anterior = $id;
            }// end if

            # name and id
            $mkind_id = intval($locality->id);
            $mking_name = (string)$locality->text_title . "-" . $lang;
            $my_cat = array(
                'description' => $mking_name,
                'slug' => sanitize_title($mking_name),
                'parent' => 0
            );

            $term_vector_locality[$lang][$counter_element_locality] = term_exists($mking_name, 'property_area', $my_cat);

            if (!$term_vector_locality[$lang][$counter_element_locality]) {
                $term_vector_locality[$lang][$counter_element_locality] = wp_insert_term($mking_name, 'property_area', $my_cat);
                //my_print($term_vector_locality[$lang][$counter_element_locality]);
                pll_set_term_language($term_vector_locality[$lang][$counter_element_locality]["term_id"], $lang);
            } else {
                pll_set_term_language($term_vector_locality[$lang][$counter_element_locality]["term_id"], $lang);
            }

            # change name
            $word = "-" . $lang;
            $final_name = str_replace($word, "", $mking_name);
            $args = array('name' => $final_name, 'description' => $final_name);
            //print_r($args);
            wp_update_term($term_vector_locality[$lang][$counter_element_locality]["term_id"], 'property_area', $args);

            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "id" => $id,
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category" => "property_area",
                "destacado_menu" => false,
            );

            if (is_array($term_vector_locality[$lang][$counter_element_locality]))
                $taxonomy = "locality_" . $term_vector_locality[$lang][$counter_element_locality]["term_id"];

            if (is_object($term_vector_locality[$lang][$counter_element_locality]))
                $taxonomy = "locality_" . $term_vector_locality[$lang][$counter_element_locality]->term_id;

            $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

            if($counter_element_locality > $max_elements_locality)
                $max_elements_locality = $counter_element_locality;

        }// end foreach

        # identifiers
        $id_anterior = 0;


        # district
        foreach ($districts as $district) {

            # properties
            $id = (int)$district->id;
            $text_title = (string)$district->text_title;
            $lang = (string)$district->language;

            # new id
            if ( $id_anterior != $id ){
                $counter_element_district++;
                $id_anterior = $id;
            }// end if

            # name and id
            $mkind_id = intval($district->id);
            $mking_name = (string)$district->text_title . "-" . $lang;


            # get region term
            $locality_name = $this->getLocalityNameByIdLnag($district->dynamic_geolocality , $lang);
            $term_id = $this->getLocalityTermId($locality_name , $lang);

            $my_cat = array(
                'description' => $mking_name,
                'slug' => sanitize_title($mking_name),
                'parent' => $term_id
            );

            // my_print($my_cat);

            $term_vector_district[$lang][$counter_element_district] = term_exists($mking_name, 'property_area', $my_cat);
            if (!$term_vector_district[$lang][$counter_element_district]) {
                $term_vector_district[$lang][$counter_element_district] = wp_insert_term($mking_name, 'property_area', $my_cat);
                //my_print($term_vector_district[$lang][$counter_element_district]);
                pll_set_term_language($term_vector_district[$lang][$counter_element_district]["term_id"], $lang);
            } else {
                pll_set_term_language($term_vector_district[$lang][$counter_element_district]["term_id"], $lang);
            }

            # change name
            $word = "-" . $lang;
            $final_name = str_replace($word, "", $mking_name);
            $args = array('name' => $final_name, 'description' => $final_name);
            //print_r($args);
            wp_update_term($term_vector_district[$lang][$counter_element_district]["term_id"], 'property_area', $args);


            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "id" => $id,
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category" => "property_area",
                "destacado_menu" => false,
            );


            if (is_array($term_vector_district[$lang][$counter_element_district]))
                $taxonomy = "district_" . $term_vector_district[$lang][$counter_element_district]["term_id"];

            if (is_object($term_vector_district[$lang][$counter_element_district]))
                $taxonomy = "district_" . $term_vector_district[$lang][$counter_element_district]->term_id;

            $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

            if($counter_element_district > $max_elements_district)
                $max_elements_district = $counter_element_district;

        }// end foreach


        # region
        for($i = 0; $i < $max_elements_region; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector_region[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for

        # city
        for($i = 0; $i < $max_elements_city; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector_city[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for


        # locality
        for($i = 0; $i < $max_elements_locality; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector_locality[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for

        # district
        for($i = 0; $i < $max_elements_district; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector_district[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for


    }// end function



    private function getRegionTermId($region_name , $lang)
    {
        # name
        $mking_name = (String)$region_name . "-" . $lang;
        $my_cat = array(
            'description' => $mking_name,
            'slug' => sanitize_title($mking_name),
            'parent' => 0
        );

        $term = term_exists($mking_name, 'property_city', $my_cat);

        return ($term["term_id"]) ? $term["term_id"] : false ;


    } // end function


    private function getLocalityTermId($locality_name , $lang)
    {
        # name
        $mking_name = (String)$locality_name . "-" . $lang;
        $my_cat = array(
            'description' => $mking_name,
            'slug' => sanitize_title($mking_name),
            'parent' => 0
        );

        $term = term_exists($mking_name, 'property_area', $my_cat);

        return ($term["term_id"]) ? $term["term_id"] : false ;


    } // end function


    private function getLocalityNameByIdLnag($id_locality,$lang)
    {
        $sql = "select * from dynamic_geolocality where id = '".$id_locality."' and language = '".$lang."'  ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations->text_title) ? $acommodations->text_title : false;
    } // end function


    private function getRegionNameByIdLnag($id_region,$lang)
    {
        $sql = "select * from dynamic_georegion where id = '".$id_region."' and language = '".$lang."'  ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations->text_title) ? $acommodations->text_title : false;
    } // end function



    private function getGeocountry($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select * from dynamic_geocountry where language IN('".$languages."') order  by id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function


    private function getGeoregion($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select * from dynamic_georegion where language IN('".$languages."') order  by id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function

    private function getGeocity($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select * from dynamic_geocity where language IN('".$languages."') order  by id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function
    
    private function getGeolocality($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select * from dynamic_geolocality where language IN('".$languages."') order  by id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function

    private function getGeodistrict($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select * from dynamic_geodistrict where language IN('".$languages."') order  by id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function
    
} // end class

?>


