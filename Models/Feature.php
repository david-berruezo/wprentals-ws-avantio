<?php

class Feature{



    public function __construct()
    {
        // empty no database connected
    }

    public function createFeatures($avantio_credentials)
    {

        # term_vector
        $term_vector = array();
        $max_elements = 0;
        $terms_luxury = array();

        # create taxonomy
        // $this->create_taxonomy_property_features();

        $feature_vector_lang = array();
        $counter_feature_vector_lang = 0;

        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            $counter_feature_vector_lang = 0;
            $features_titulos = $this->textosFeature($lang);
            //print_r($features_titulos);
            //if($counter_feature_vector_lang < 4){
                foreach($features_titulos as $feature_titulo){
                    $this->insertFeature($feature_titulo , $lang , $feature_vector_lang , $counter_feature_vector_lang);
                }// end foreach
            //}// end if
            $counter_feature_vector_lang++;
        } // end foreach

        //my_print($feature_vector_lang);

        for($i = 0; $i < $counter_feature_vector_lang; $i++) {
            $vector_plantilla = array();
            foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
                //$lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $feature_vector_lang[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for


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

    }


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



    private function textosFeature($language){
        $textos = array(
            "es" => array(
                "sauna",
                "jacuzzi",
                "secador de pelo",
                "ascensor",
                "grupos de personas",
                "piscina",
                "tv",
                "jardin",
                "muebles de jardin",
                "plancha",
                "chimenea",
                "barbacoa",
                "radio",
                "minibar",
                "terraza",
                "parcela vallada",
                "caja de seguridad",
                "dvd",
                "balcon",
                "exprimidor",
                "hervidor electrico",
                "zona para niños",
                "gimnasio",
                "alarma",
                "tennis",
                "squash",
                "paddel",
                "apta para discapacitados",
                "nevera",
                "congelador",
                "lavavajillas",
                "lavadora",
                "secadora",
                "cafetera",
                "tostadora",
                "microondas",
                "horno",
                "vajilla",
                "utensilios de cocina",
            ),
            "en" => array(
                "sauna",
                "jacuzzi",
                "hair dryer",
                "lift",
                "Groups of people",
                "pool",
                "TV",
                "yard",
                "garden furniture",
                "iron",
                "fireplace",
                "barbecue",
                "radio",
                "minibar",
                "Terrace",
                "fenced plot",
                "safe deposit box",
                "DVD",
                "balcony",
                "squeezer",
                "electric kettle",
                "children's area",
                "Gym",
                "alarm",
                "tennis",
                "squash",
                "paddle",
                "suitable for the disabled",
                "fridge",
                "freezer",
                "dishwasher",
                "washing machine",
                "drying machine",
                "coffee maker",
                "toaster",
                "microwave",
                "kiln",
                "crockery",
                "Cookware",
            ),
            "ca" => array(
                "sauna",
                "jacuzzi",
                "assecador de cabell",
                "ascensor",
                "grups de persones",
                "piscina",
                "tv",
                "jardí",
                "mobles de jardí",
                "planxa",
                "xemeneia",
                "barbacoa",
                "ràdio",
                "minibar",
                "terrassa",
                "parcel·la tancada",
                "caixa de seguretat",
                "dvd",
                "balcó",
                "espremedora",
                "bullidor elèctric",
                "zona per a nens",
                "gimnàs",
                "alarma",
                "tennis",
                "esquaix",
                "pàdel",
                "apta per a discapacitats",
                "nevera",
                "congelador",
                "rentavaixelles",
                "rentadora",
                "assecadora",
                "cafetera",
                "torradora",
                "microones",
                "forn",
                "vaixella",
                "estris de cuina",
            ),
            "fr" => array(
                "sauna",
                "jacuzzi",
                "sèche-cheveux",
                "ascenseur",
                "Des groupes de personnes",
                "bassin",
                "LA TÉLÉ",
                "Cour",
                "mobilier de jardin",
                "fer à repasser",
                "cheminée",
                "barbecue",
                "radio",
                "mini-bar",
                "Terrasse",
                "terrain clôturé",
                "coffre-fort",
                "DVD",
                "balcon",
                "presse-agrumes",
                "bouilloire électrique",
                "espace enfants",
                "Gym",
                "alarme",
                "tennis",
                "écraser",
                "pagayer",
                "adapté aux personnes handicapées",
                "réfrigérateur",
                "congélateur",
                "lave-vaisselle",
                "Machine à laver",
                "Sèche-linge",
                "machine à café",
                "grille-pain",
                "four micro onde",
                "four",
                "vaisselle",
                "ustensiles de cuisine",
            ),

        );
        return $textos[$language];
    }


    private function textosFeatureXml($language){

        $textos = array(
            "es" => array(
                "sauna" => "",
                "jacuzzi" => "",
                "secador de pelo" => "",
                "ascensor" => "",
                "grupos de personas" => "",
                "piscina" => "",
                "tv" => "",
                "jardin" => "",
                "muebles de jardin" => "",
                "plancha" => "",
                "chimenea" => "",
                "barbacoa" => "",
                "radio" => "",
                "minibar" => "",
                "terraza" => "",
                "parcela vallada" => "",
                "caja de seguridad" => "",
                "dvd" => "",
                "balcon" => "",
                "exprimidor" => "",
                "hervidor electrico" => "",
                "zona para niños" => "",
                "gimnasio" => "",
                "alarma" => "",
                "tennis" => "",
                "squash" => "",
                "paddel" => "" ,
                "apta para discapacitados" => "",
                "nevera" => "",
                "congelador" => "",
                "lavavajillas" => "",
                "lavadora" => "",
                "secadora" => "",
                "cafetera" => "",
                "tostadora" => "",
                "microondas" => "",
                "horno" => "",
                "vajilla" => "",
                "utensilios de cocina" => "",
            ),
            "en" => array(
                "sauna"=> "" ,
                "jacuzzi"=> "" ,
                "hair dryer"=> "" ,
                "lift"=> "" ,
                "Groups of people"=> "" ,
                "pool"=> "" ,
                "TV"=> "" ,
                "yard"=> "" ,
                "garden furniture"=> "" ,
                "iron"=> "" ,
                "fireplace"=> "" ,
                "barbecue"=> "" ,
                "radio"=> "" ,
                "minibar"=> "" ,
                "Terrace"=> "" ,
                "fenced plot"=> "" ,
                "safe deposit box"=> "" ,
                "DVD"=> "" ,
                "balcony"=> "" ,
                "squeezer"=> "" ,
                "electric kettle"=> "" ,
                "children's area"=> "" ,
                "Gym"=> "" ,
                "alarm"=> "" ,
                "tennis"=> "" ,
                "squash"=> "" ,
                "paddle"=> "" ,
                "suitable for the disabled"=> "" ,
                "fridge"=> "" ,
                "freezer"=> "" ,
                "dishwasher"=> "" ,
                "washing machine"=> "" ,
                "drying machine"=> "" ,
                "coffee maker"=> "" ,
                "toaster"=> "" ,
                "microwave"=> "" ,
                "kiln"=> "" ,
                "crockery"=> "" ,
                "Cookware"=> "" ,
            ),
            "fr" => array(
                "sauna"=> "" ,
                "jacuzzi"=> "" ,
                "sèche-cheveux"=> "" ,
                "ascenseur"=> "" ,
                "Des groupes de personnes"=> "" ,
                "bassin"=> "" ,
                "LA TÉLÉ"=> "" ,
                "Cour"=> "" ,
                "mobilier de jardin"=> "" ,
                "fer à repasser"=> "" ,
                "cheminée"=> "" ,
                "barbecue"=> "" ,
                "radio"=> "" ,
                "mini-bar"=> "" ,
                "Terrasse"=> "" ,
                "terrain clôturé"=> "" ,
                "coffre-fort"=> "" ,
                "DVD"=> "" ,
                "balcon"=> "" ,
                "presse-agrumes"=> "" ,
                "bouilloire électrique"=> "" ,
                "espace enfants"=> "" ,
                "Gym"=> "" ,
                "alarme"=> "" ,
                "tennis"=> "" ,
                "écraser"=> "" ,
                "pagayer"=> "" ,
                "adapté aux personnes handicapées"=> "" ,
                "réfrigérateur"=> "" ,
                "congélateur"=> "" ,
                "lave-vaisselle"=> "" ,
                "Machine à laver"=> "" ,
                "Sèche-linge"=> "" ,
                "machine à café"=> "" ,
                "grille-pain"=> "" ,
                "four micro onde"=> "" ,
                "four"=> "" ,
                "vaisselle"=> "" ,
                "ustensiles de cuisine"=> "" ,
            ),
            "ca" => array(
                "sauna"=> "" ,
                "jacuzzi"=> "" ,
                "assecador de cabell"=> "" ,
                "ascensor"=> "" ,
                "grups de persones"=> "" ,
                "piscina"=> "" ,
                "tv"=> "" ,
                "jardí"=> "" ,
                "mobles de jardí"=> "" ,
                "planxa"=> "" ,
                "xemeneia"=> "" ,
                "barbacoa"=> "" ,
                "ràdio"=> "" ,
                "minibar"=> "" ,
                "terrassa"=> "" ,
                "parcel·la tancada"=> "" ,
                "caixa de seguretat"=> "" ,
                "dvd"=> "" ,
                "balcó"=> "" ,
                "espremedora"=> "" ,
                "bullidor elèctric"=> "" ,
                "zona per a nens"=> "" ,
                "gimnàs"=> "" ,
                "alarma"=> "" ,
                "tennis"=> "" ,
                "esquaix"=> "" ,
                "pàdel"=> "" ,
                "apta per a discapacitats"=> "" ,
                "nevera"=> "" ,
                "congelador"=> "" ,
                "rentavaixelles"=> "" ,
                "rentadora"=> "" ,
                "assecadora"=> "" ,
                "cafetera"=> "" ,
                "torradora"=> "" ,
                "microones"=> "" ,
                "forn"=> "" ,
                "vaixella"=> "" ,
                "estris de cuina"=> "" ,
            )

        );

        return $textos[$language];
    }



    private function insertFeature($name_service , $lang , &$feature_vector_lang , &$counter_feature_vector_lang)
    {

        $name_total_service = $name_service . "-" . $lang;

        # es | en  | others
        $my_cat = array(
            'description' => $name_total_service,
            'slug' =>sanitize_title($name_total_service),
            'parent' => 0
        );

        $feature_vector_lang[$lang][$counter_feature_vector_lang] = term_exists($name_total_service, 'property_features',$my_cat);
        if(!$feature_vector_lang[$lang][$counter_feature_vector_lang] ){
            $feature_vector_lang[$lang][$counter_feature_vector_lang] = wp_insert_term($name_total_service, 'property_features',$my_cat);
            pll_set_term_language($feature_vector_lang[$lang][$counter_feature_vector_lang]["term_id"], $lang);
        }else{
            pll_set_term_language($feature_vector_lang[$lang][$counter_feature_vector_lang]["term_id"], $lang);
        }


        # change name
        $word = "-" . $lang;
        $final_name = str_replace($word, "" , $name_total_service);
        $args = array('name' => $final_name , 'description' => $final_name);
        //print_r($args);
        wp_update_term( $feature_vector_lang[$lang][$counter_feature_vector_lang]["term_id"], 'property_features', $args );



        // my_print($term_vector[$lang][$counter_element]);

        # add option save cityparent category_fetured_image and others
        $term_data = array(
            "pagetax" => "",
            "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
            "category_tax" => "property_category"
        );

        if (is_array($feature_vector_lang[$lang][$counter_feature_vector_lang]))
            $taxonomy = "feature_".$feature_vector_lang[$lang][$counter_feature_vector_lang]["term_id"];

        if(is_object($feature_vector_lang[$lang][$counter_feature_vector_lang]))
            $taxonomy = "feature_".$feature_vector_lang[$lang][$counter_feature_vector_lang]->term_id;


        $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes');

    } // end function



    private function add_two_primary_keys()
    {

        $forge = \Config\Database::forge();
        $forge->addKey('id', TRUE);
        $forge->addKey('language', TRUE);

    } // end function


    private function insertar_actualizar()
    {

        $data = [
            'id'            => "",
            'language'      => "",
            'text_title'    => ""
        ];
        $this->save($data);

    } // end function

}// end class
?>