<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 04/11/2021
 * Time: 9:00
 */

class InsertDeleteTermsTaxonomies
{

    public function __construct()
    {
        // construct
    } // end function


    function insert_all(){
        $this->insert_features();
        $this->insert_geolocations();
        $this->insert_kind();
        $this->insert_kindAlquiler();
        $this->insert_status();
        $this->insert_taxononomy_property_swimming_pool();
        $this->insert_term_taxonomy_multilanguage();
        // insert_services();
    } // end function


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



}// end class