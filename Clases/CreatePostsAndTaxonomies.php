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
        # create post type estate property
        $this->create_post_type_estate_property();
        # create taxonomies
        $this->create_taxonomy_property_category();
        $this->create_taxonomy_property_action_category();
        $this->create_taxonomy_property_city();
        $this->create_taxonomy_property_area();
        $this->create_taxonomy_property_features();
        $this->create_taxonomy_property_extra_services();
        $this->create_taxonomy_property_status();
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



}// end class CreatePostsAndTaxonomies