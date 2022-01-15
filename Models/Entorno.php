<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 03/11/2021
 * Time: 19:10
 */

class Entorno
{

    # avantio credential
    private $avantio_credential = "servidor";

    # post_id
    private $post_id;

    # tipo de villa
    private $tipo_de_villa = "villa";


    private $tipo_de_villa_languages = array(
        "es" => "Villa de lujo",
        "en" => "Luxury Village",
        "ca" => "Vila de luxe",
        "fr" => "Villa de luxe"
    );



    # name of objects Nuevo and KindAlquiler
    private $status = array(
        "es" => "Nuevo",
        "en" => "New",
        "ca" => "Nou",
        "fr" => "Nouveau"
    );


    private $kindAlquiler = array(
        "es" => "Alquiler toda la Villa",
        "en" => "Rent all Villa",
        "ca" => "Lloguer de tota la Villa",
        "fr" => "Louer toute la Villa"
    );


    private $db = "";


    public function __construct()
    {
        // empty
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


    public function insertEntorno()
    {

        # get accommodations
        $xml =  $this->getLoations();
        //p_($xml[0]);
        //p_($xml[1]);

        $counter_property = 0;

        if($xml){

            foreach ($xml as $accommodation) {
    
                if ($counter_property < 1) {
    
                    $my_post_translation = pll_get_post_translations($accommodation->avantio_accomodations);
    
                    foreach($my_post_translation as $post_id){
    
                        $loc_where = (string)$accommodation->loc_where;
                        $loc_howto = (string)$accommodation->loc_howto;
                        $loc_desc1 = (string)$accommodation->loc_desc1;
                        $loc_desc2 = (string)$accommodation->loc_desc2;
    
                        //echo "where: " . $loc_where . "<br>";
                        update_post_meta($post_id, 'loc_where', $loc_where, $prev_value = '');
                        //echo "where: ".get_post_meta($post_id, 'loc_where'). "<br>";
                        //p_(get_post_meta($post_id, 'loc_where'));
    
                        update_post_meta($post_id, 'loc_howto', $loc_howto, $prev_value = '');
                        update_post_meta($post_id, 'loc_desc1', $loc_desc1, $prev_value = '');
                        update_post_meta($post_id, 'loc_desc2', $loc_desc2, $prev_value = '');
    
    
                        $beach_name = (string)$accommodation->beach_ame;
                        $beach_dist = floatval($accommodation->beach_dist);
                        $beach_unit = (string)$accommodation->beach_dist_unit;
                        //$beach_dist=(strtoupper($beach_unit)=='KM')? intval( 1000*$beach_dist ) : intval($beach_dist);
    
    
                        update_post_meta($post_id, 'beach_name', $beach_name, $prev_value = '');
                        update_post_meta($post_id, 'beach_dist', $beach_dist, $prev_value = '');
                        update_post_meta($post_id, 'beach_unit', $beach_unit, $prev_value = '');
    
    
                        $golf_name = (string)$accommodation->golf_name;
                        $golf_dist = floatval($accommodation->golf_dist);
                        $golf_unit = (string)$accommodation->golf_dist_unit;
                        //$golf_dist=(strtoupper($golf_unit)=='KM')? intval( 1000*$golf_dist ) : intval($golf_dist);
    
                        update_post_meta($post_id, 'golf_name', $golf_name, $prev_value = '');
                        update_post_meta($post_id, 'golf_dist', $golf_dist, $prev_value = '');
                        update_post_meta($post_id, 'golf_unit', $golf_unit, $prev_value = '');
    
    
                        $city_name = (string)$accommodation->city__name;
                        $city_dist = floatval($accommodation->city_dist);
                        $city_unit = (string)$accommodation->city_dist_unit;
                        //$city_dist=(strtoupper($city_unit)=='KM')? intval( 1000*$city_dist ) : intval($city_dist);
    
    
                        update_post_meta($post_id, 'city_name', $city_name, $prev_value = '');
                        update_post_meta($post_id, 'city_dist', $city_dist, $prev_value = '');
                        update_post_meta($post_id, 'city_unit', $city_unit, $prev_value = '');
    
    
                        $super_name = (string)$accommodation->super_name;
                        $super_dist = floatval($accommodation->super_dist);
                        $super_unit = (string)$accommodation->super_dist_unit;
                        //$super_dist=(strtoupper($super_unit)=='KM')? intval( 1000*$super_dist ) : intval($super_dist);
    
                        update_post_meta($post_id, 'super_name', $super_name, $prev_value = '');
                        update_post_meta($post_id, 'super_dist', $super_dist, $prev_value = '');
                        update_post_meta($post_id, 'super_unit', $super_unit, $prev_value = '');
    
    
                        $airport_name = (string)$accommodation->airport_name;
                        $airport_dist = floatval($accommodation->airport_dist);
                        $airport_unit = (string)$accommodation->airport_dist_unit;
                        //$airport_dist=(strtoupper($airport_unit)=='KM')? intval( 1000*$airport_dist ) : intval($airport_dist);
    
                        update_post_meta($post_id, 'airport_name', $airport_name, $prev_value = '');
                        update_post_meta($post_id, 'airport_dist', $airport_dist, $prev_value = '');
                        update_post_meta($post_id, 'airport_unit', $airport_unit, $prev_value = '');
    
    
                        $train_name = (string)$accommodation->train_name;
                        $train_dist = floatval($accommodation->train_dist);
                        $train_unit = (string)$accommodation->train_dist_unit;
                        //$train_dist=(strtoupper($train_unit)=='KM')? intval( 1000*$train_dist ) : intval($train_dist);
    
                        update_post_meta($post_id, 'train_name', $train_name, $prev_value = '');
                        update_post_meta($post_id, 'train_dist', $train_dist, $prev_value = '');
                        update_post_meta($post_id, 'train_unit', $train_unit, $prev_value = '');
    
    
                        $bus_name=(string)$accommodation->bus_name;
                        $bus_dist=floatval($accommodation->bus_dist);
                        $bus_unit=(string)$accommodation->bus_dist_unit;
                        //$bus_dist=(strtoupper($bus_unit)=='KM')? intval( 1000*$bus_dist ) : intval($bus_dist);
    
                        update_post_meta($post_id, 'bus_name', $bus_name, $prev_value = '');
                        update_post_meta($post_id, 'bus_dist', $bus_dist, $prev_value = '');
                        update_post_meta($post_id, 'bus_unit', $bus_unit, $prev_value = '');
    
    
                        # views
                        $view_to_beach=( (string)$accommodation->view_to_beach == 'true' ) ? 1:0;
                        $view_to_swimming_pool=( (string)$accommodation->view_to_swimmingPool == 'true' ) ? 1:0;
                        $view_to_golf=( (string)$accommodation->view_to_golf == 'true' ) ? 1:0;
                        $view_to_garden=( (string)$accommodation->view_to_garden == 'true' ) ? 1:0;
                        $view_to_river=( (string)$accommodation->view_to_river == 'true' ) ? 1:0;
                        $view_to_mountain=( (string)$accommodation->view_to_mountain == 'true' ) ? 1:0;
                        $view_to_lake=( (string)$accommodation->view_to_lake == 'true' ) ? 1:0;
    
                        # first line
                        $first_line_beach =( (string)$accommodation->first_line_beach == 'true' ) ? 1:0;
                        $first_line_golf =( (string)$accommodation->first_line_golf == 'true' ) ? 1:0;
    
    
                        update_post_meta($post_id, 'view_to_beach', $view_to_beach, $prev_value = '');
                        update_post_meta($post_id, 'view_to_swimming_pool', $view_to_swimming_pool, $prev_value = '');
                        update_post_meta($post_id, 'view_to_golf', $view_to_golf, $prev_value = '');
                        update_post_meta($post_id, 'view_to_garden', $view_to_garden, $prev_value = '');
                        update_post_meta($post_id, 'view_to_river', $view_to_river, $prev_value = '');
                        update_post_meta($post_id, 'view_to_mountain', $view_to_mountain, $prev_value = '');
                        update_post_meta($post_id, 'view_to_lake', $view_to_lake, $prev_value = '');
    
                        update_post_meta($post_id, 'first_line_beach', $first_line_beach, $prev_value = '');
                        update_post_meta($post_id, 'first_line_golf', $first_line_golf, $prev_value = '');
    
                    } // end foreach translation
    
                } // end if counter property
    
                $counter_property++;
    
             } // end foreach

        } // end if

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


    public function connectDb()
    {

        $connector = new Database();
        $connector->setCredential($this->getAvantioCredential());
        $this->db = $connector::getInstance();


    } // end function


    /**
     * @return string
     */
    public function getStatus($lang)
    {
        return $this->status[$lang];
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
    public function getKindAlquiler($lang)
    {
        return $this->kindAlquiler[$lang];
    }

    /**
     * @param string $kindAlquiler
     */
    public function setKindAlquiler($kindAlquiler)
    {
        $this->kindAlquiler = $kindAlquiler;
    }



    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }


    /**
     * @param mixed $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }


    /**
     * @return array
     */
    public function getTipoDeVillaLanguages($lang)
    {
        return $this->tipo_de_villa_languages[$lang];
    }


    /**
     * @param array $tipo_de_villa_languages
     */
    public function setTipoDeVillaLanguages($tipo_de_villa_languages)
    {
        $this->tipo_de_villa_languages = $tipo_de_villa_languages;
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




    private function getLoations()
    {
        $sql = "select * from hshv_avantio_accomodations_locations order by avantio_accomodations asc;";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;

    } // end function


}// end class

?>