<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 04/11/2021
 * Time: 9:00
 */

class CreatePostsAndTaxonomies{


    public function __construct()
    {
        // construct
    } // end function


    public function create_all(){
        $this->create_post_type_estate_property();
        $this->create_taxonomy_property_category();
        $this->create_taxonomy_property_action_category();
        $this->create_taxonomy_property_city();
        $this->create_taxonomy_property_area();
        $this->create_taxonomy_property_features();
        $this->create_taxonomy_property_extra_services();
        $this->create_taxonomy_property_status();
        $this->create_taxonomy_property_extra_services();
    }


    public function create_post_type_estate_property()
    {

        $slug = 'properties';

        register_post_type('estate_property', array(
                'labels' => array(
                    'name' => esc_html__('Listings', 'wprentals-core'),
                    'singular_name' => esc_html__('Listing', 'wprentals-core'),
                    'add_new' => esc_html__('Add New Listing', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add Listing', 'wprentals-core'),
                    'edit' => esc_html__('Edit', 'wprentals-core'),
                    'edit_item' => esc_html__('Edit Listings', 'wprentals-core'),
                    'new_item' => esc_html__('New Listing', 'wprentals-core'),
                    'view' => esc_html__('View', 'wprentals-core'),
                    'view_item' => esc_html__('View Listings', 'wprentals-core'),
                    'search_items' => esc_html__('Search Listings', 'wprentals-core'),
                    'not_found' => esc_html__('No Listings found', 'wprentals-core'),
                    'not_found_in_trash' => esc_html__('No Listings found in Trash', 'wprentals-core'),
                    'parent' => esc_html__('Parent Listings', 'wprentals-core')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $slug),
                'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
                'can_export' => true,
                'register_meta_box_cb' => 'wpestate_add_property_metaboxes',
                //'menu_icon' => WPESTATE_PLUGIN_DIR_URL . '/img/properties.png'
            )
        );

    } // end function



    public function create_taxonomy_property_category()
    {

        $name_label = esc_html__('Categories', 'wprentals-core');
        $add_new_item_label = esc_html__('Add New Listing Category', 'wprentals-core');
        $new_item_name_label = esc_html__('New Listing Category', 'wprentals-core');

        $slug = 'property_category';


        register_taxonomy('property_category', 'estate_property', array(
                'labels' => array(
                    'name' => $name_label,
                    'add_new_item' => $add_new_item_label,
                    'new_item_name' => $new_item_name_label
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)
            )
        );

    } // end function




    public function create_taxonomy_property_action_category()
    {

        $action_name = esc_html__('What do you rent ?', 'wprentals-core');
        $action_add_new_item = esc_html__('Add new option for "What do you rent" ', 'wprentals-core');
        $action_new_item_name = esc_html__('Add new option for "What do you rent"', 'wprentals-core');


        $slug = 'property_action_category';


        // add custom taxonomy
        register_taxonomy('property_action_category', 'estate_property', array(
                'labels' => array(
                    'name' => $action_name,
                    'add_new_item' => $action_add_new_item,
                    'new_item_name' => $action_new_item_name
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)
            )
        );

    }// end function




    public function create_taxonomy_property_city()
    {

        $slug = 'property_city';

        // add custom taxonomy
        register_taxonomy('property_city', 'estate_property', array(
                'labels' => array(
                    'name' => esc_html__('City', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add New City', 'wprentals-core'),
                    'new_item_name' => esc_html__('New City', 'wprentals-core')
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)
            )
        );

    } // end function





    public function create_taxonomy_property_area()
    {

        $slug = 'property_area';

        // add custom taxonomy
        register_taxonomy('property_area', 'estate_property', array(
                'labels' => array(
                    'name'              => esc_html__( 'Neighborhood / Area','wprentals-core'),
                    'add_new_item'      => esc_html__( 'Add New Neighborhood / Area','wprentals-core'),
                    'new_item_name'     => esc_html__( 'New Neighborhood / Area','wprentals-core')
                ),
                'hierarchical'  => true,
                'query_var'     => true,
                'rewrite'       => array( 'slug' => $slug )

            )
        );


    } // end function



    public function create_taxonomy_property_features()
    {

        $slug = 'property_features';

        // add custom taxonomy
        register_taxonomy('property_features', 'estate_property', array(
                'labels' => array(
                    'name' => esc_html__('Features & Amenities', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add New Feature', 'wprentals-core'),
                    'new_item_name' => esc_html__('New Feature', 'wprentals-core')
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)

            )
        );

    }// end function



    public function create_taxonomy_property_extra_services()
    {

        $slug = 'property_extra_services';

        // add custom taxonomy
        register_taxonomy('extra_services', 'estate_property', array(
                'labels' => array(
                    'name' => esc_html__('Extra Services', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add New Extra Service', 'wprentals-core'),
                    'new_item_name' => esc_html__('New Extra Service', 'wprentals-core')
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)

            )
        );

    } // end function



    public function create_taxonomy_property_status()
    {

        $slug = 'property_status';

        // add custom taxonomy
        register_taxonomy('property_status', 'estate_property', array(
                'labels' => array(
                    'name' => esc_html__('Property Status', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add New Status', 'wprentals-core'),
                    'new_item_name' => esc_html__('New Status', 'wprentals-core')
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)

            )
        );

    } // end function




    public function create_taxonomy_property_swimming_pool()
    {

        $name_label = esc_html__('Piscina', 'wprentals-core');
        $add_new_item_label = esc_html__('Add New Piscina Category', 'wprentals-core');
        $new_item_name_label = esc_html__('New Piscina Category', 'wprentals-core');

        $slug = 'property_service_piscina';


        register_taxonomy('property_service_piscina', 'estate_property', array(
                'labels' => array(
                    'name' => $name_label,
                    'add_new_item' => $add_new_item_label,
                    'new_item_name' => $new_item_name_label
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)
            )
        );

    } // end function


    public function insert_taxononomy_property_swimming_pool($avantio_credentials)
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



    public function insert_term_taxonomy_multilanguage($taxonomy , $terms)
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



}// end class CreatePostsAndTaxonomies