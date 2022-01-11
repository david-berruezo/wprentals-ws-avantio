<?php

/**
 * Plugin Name: WpRentals - Avantio Api Websites Core Functionality
 * Plugin URI: http://www.davidberruezo.com/
 * Description: This plugin connect with Avantio Api Websites Webservice
 * Author: David Berruezo
 * Version: 1.0
 * Author URI: http://www.davidberruezo.com/
 * License:     GPL2
 * Text Domain: wprentals-ws-avantio
 * Domain Path: /languages
 *
 */


# constants
define( 'AVANTIO_VERSION', '4.1.9' );
define( 'AVANTIO__MINIMUM_WP_VERSION', '4.0' );
define( 'AVANTIO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AVANTIO_DELETE_LIMIT', 100000 );

# includes

# clases utils
# db
include( AVANTIO__PLUGIN_DIR . 'Clases/DB.php' );
# taxonomies terms post_type
include( AVANTIO__PLUGIN_DIR . 'Clases/CreatePostsAndTaxonomies.php' );
include(AVANTIO__PLUGIN_DIR . 'Clases/InsertDeleteTermsTaxonomies.php');

# avantio controller
include( AVANTIO__PLUGIN_DIR . 'Controllers/Avantio.php' );

# avantio models
include( AVANTIO__PLUGIN_DIR . 'Clases/Language.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/EstateProperty.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Kind.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/KindAlquiler.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Status.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Accomodation.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Feature.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Entorno.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Service.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/GeographicArea.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Picture.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/Description.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/AvailabilityRates.php' );
include( AVANTIO__PLUGIN_DIR . 'Models/AvantioPms.php' );
# helpers
include( AVANTIO__PLUGIN_DIR . 'Helpers/funciones.php' );
include( AVANTIO__PLUGIN_DIR . 'Helpers/funciones_wordpress.php' );


# var
$avantio_credential = "servidor_tiendapisos";
$post_and_taxonomies = new CreatePostsAndTaxonomies();
$insert_delete_terms_and_taxonomies = new InsertDeleteTermsTaxonomies();


# primer paso activar delete all , create_all , avantio
# segundo paso activar create_all

function load_plugin() {
    if(get_option('avantio') != 'initialised'){
        //create_all();
        // echo "<script>alert('llamamos al a función');</script>";
        // add_option( 'avantio', 'initialised' );
        // your code here
        // $avantio = new Avantio("all");
    }else{
        //echo "ejecución plugin";
        # create taxonomies
        //create_all();
        # delete taxonomies
        // delete_all();
        # insert all taxonimies
        // insert_all();
        # fill data to taxonomies
        # webservices websites
        // $avantio = new Avantio("all");
        # webservices pms
        // $avantioPms = new AvantioPms();
        // getAllAvailavility();
        // print_date();
    }// end if
}// end function

# create register custom post type
add_action('setup_theme', 'create_all',20);

function create_all(){
    global $post_and_taxonomies;
    //$post_and_taxonomies->create_all();
    $post_and_taxonomies->create_taxonomy_property_extra_services();
}// end function

# install terms of taxonomies
add_action('setup_theme', 'insert_all',20);

function insert_all(){
    /*
    insert_features();
    insert_geolocations();
    insert_kind();
    insert_kindAlquiler();
    insert_status();
    insert_taxononomy_property_swimming_pool();
    insert_term_taxonomy_multilanguage();
    */
    //insert_services();
}

# delete terms of taxonomies
add_action('setup_theme', 'delete_all',20);


function delete_all(){

    # delete posts and terms
    //delete_posts_by_custom_post_type();
    //delete_terms_of_taxonomies();

    # delete post type and taxonomies
    //delete_custom_post_type_estate();
    //delete_taxonomies();
}

function delete_terms_of_taxonomies(){
    /*
    delete_all_terms( 'property_category');
    delete_all_terms( 'property_action_category');
    delete_all_terms( 'property_city');
    delete_all_terms( 'property_area');
    delete_all_terms( 'property_features');
    delete_all_terms( 'property_status');
    delete_all_terms( 'property_service_piscina');
    */
    //delete_all_terms( 'extra_services');
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

# delete terms of taxonomies
add_action('setup_theme', 'call_to_avantio',20);

function call_to_avantio(){
    $avantio = new Avantio();
}



function insert_features(){

    # db
    $connector = new Database();
    $connector->setCredential("automocion");
    $db = $connector::getInstance();

    # language
    # active languanges
    $language = new Language();
    $language->setDb($db);
    $avantio_credentials = array(
        'ACTIVED_LANGUAGES' => $language->getAll(),
    );

    # feature
    $feature = new Feature();
    $feature->createFeatures($avantio_credentials);
}


function insert_services(){

    global $avantio_credential;

    # db
    $connector = new Database();
    $db = $connector::getInstance();

    # language
    # active languanges
    $language = new Language();
    $language->setDb($db);
    $avantio_credentials = array(
        'ACTIVED_LANGUAGES' => $language->getAll(),
    );

    # service
    $service = new Service();
    $service->setAvantioCredential($avantio_credential);
    $service->connectDb();
    $service->insertServices($avantio_credentials);


}



function insert_geolocations(){

    # db
    $connector = new Database();
    $connector->setCredential("automocion");
    $db = $connector::getInstance();

    # language
    # active languanges
    $language = new Language();
    $language->setDb($db);
    $avantio_credentials = array(
        'ACTIVED_LANGUAGES' => $language->getAll(),
    );

    # service
    $geo = new GeographicArea();
    $geo->insertGeographicAreas($avantio_credentials);

}


function insert_kind(){

    # db
    $connector = new Database();
    $connector->setCredential("automocion");
    $db = $connector::getInstance();

    # language
    # active languanges
    $language = new Language();
    $language->setDb($db);
    $avantio_credentials = array(
        'ACTIVED_LANGUAGES' => $language->getAll(),
    );

    # kind
    $kind = new Kind();
    $kind->insertKind($avantio_credentials);

}

function insert_kindAlquiler(){

    # db
    $connector = new Database();
    $connector->setCredential("automocion");
    $db = $connector::getInstance();

    # language
    # active languanges
    $language = new Language();
    $language->setDb($db);
    $avantio_credentials = array(
        'ACTIVED_LANGUAGES' => $language->getAll(),
    );

    # kind
    $kindAlquiler = new KindAlquiler();
    $kindAlquiler->insertKindsAlquiler($avantio_credentials);

}

function insert_status(){

    # db
    $connector = new Database();
    $connector->setCredential("automocion");
    $db = $connector::getInstance();

    # language
    # active languanges
    $language = new Language();
    $language->setDb($db);
    $avantio_credentials = array(
        'ACTIVED_LANGUAGES' => $language->getAll(),
    );

    # kind
    $status = new Status();
    $status->insertStatus($avantio_credentials);

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




/* ******************************************** Descatalogado ************************************ */

/*
function getAllAvailavility(){

    $args = array(
        'post_type' => 'estate_property',
        'numberposts' => -1
    );

    $posts = get_posts($args);
    foreach ($posts as $post) {
        echo "id"  . $post->ID . "<br>";
    } // end foreach

}// end function
*/

function print_date(){
    //echo "fecha: " . date("Y-m-d",946684800) . "<br>";
}

function pms_queries(){
    global $avantioPms;
    //connect_pms();
    //query_reservatons();
    //is_available();
}

function connect_pms(){
    global $avantioPms;
    $avantioPms->setClientsCredentials(null);
    //$avantioPms->connect("test");
    $avantioPms->connect("portvillas");
}


function query_reservatons(){
    global $avantioPms;
    $avantioPms = new AvantioPms();
    $fecha_entrada = "2021-01-01";
    $fecha_salida  = "2022-01-01";
    $reservations = $avantioPms->get_booking_list($fecha_entrada,$fecha_salida);
    //p_($reservations);
}// end function


function is_available(){
    global $avantioPms;
    $apartment_id = "297527";
    //$avantio_model->is_available($apartment_id,$info[$apartment_id]['text_userid'] , $info[$apartment_id]['text_company'] , $fecha_in , $fecha_out);
    //$avantio_model->get_info($apartment_id,'es');
    //is_available($accommodation_id , $user_id , $company , $fecha_entrada , $fecha_salida , $adult_number)
}


//add_action( 'plugins_loaded', 'load_plugin' );
//add_action( 'init', 'load_plugin' );
//add_action('plugins_loaded', 'load_plugin');


/*
function delete_all(){

    # delete posts and terms
    delete_posts_by_custom_post_type();
    //delete_terms_of_taxonomies();

    # delete post type and taxonomies
    //delete_custom_post_type_estate();
    //delete_taxonomies();
}


function delete_posts_by_custom_post_type(){
    $args = array(
        'post_type' => 'estate_property'
    );
    $posts = get_posts($args);
    $ids = array_column($posts,"ID");
    clean_object_term_cache( $ids, 'estate_property' );
    foreach ($posts as $post) {
        $delete = wp_delete_post($post->ID,true);
    } // end foreach
}


function delete_custom_post_type_estate(){
    unregister_post_type( 'estate_property' );
}


function delete_taxonomies(){
    unregister_taxonomy( 'property_category');
    unregister_taxonomy( 'property_action_category');
    unregister_taxonomy( 'property_city');
    unregister_taxonomy( 'property_area');
    unregister_taxonomy( 'property_features');
    unregister_taxonomy( 'property_status');
    unregister_taxonomy( 'property_service_piscina');
    unregister_taxonomy( 'extra_services');
}


function delete_terms_of_taxonomies(){
    delete_all_terms( 'property_category');
    delete_all_terms( 'property_action_category');
    delete_all_terms( 'property_city');
    delete_all_terms( 'property_area');
    delete_all_terms( 'property_features');
    delete_all_terms( 'property_status');
    delete_all_terms( 'property_service_piscina');
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
*/


