<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 03/11/2021
 * Time: 19:10
 */


class Accomodation
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


    public function insertAccomodations($avantio_credentials)
    {

        # vectors
        $post_vector = array();

        # counters
        $counter_property = -1;
        $counter_property_create = 0;
        $id_anterior = 0;

        # get accommodations
        $xml =  $this->getAcommodationById($avantio_credentials);
        //p_($xml);
        //die();
        //p_($xml[0]);
        //p_($xml[1]);

        foreach ($xml as $accommodation) {


            # id, language, text_title, text_referencia, text_userid, text_company, number_companyid, text_numero_registro_turistico, avantio_occupation_rules, avantio_pricemodifiers, avantio_gallery, dynamic_taxonomy, dynamic_geocountry, dynamic_georegion, dynamic_geocity, dynamic_geolocality, dynamic_geodistrict, text_geo_cp, text_geo_tipo_calle, text_geo_calle, text_geo_numero, text_geo_bloque, text_geo_puerta, text_geo_piso, text_geo_latitud, text_geo_longitud, number_geo_zoom, number_unidades, number_habitaciones, number_camas_doble, number_camas_individual, number_sofas_cama, number_sofas_cama_doble, number_literas, number_dormitorios_personal, number_camas_supletorias, number_aseos, number_banyos_banyera, number_banyos_ducha, number_metros_cuadrados, number_metros_cuadrados_utiles, number_metros_cuadrados_construidos, number_metros_cuadrados_terraza, number_capacidad_maxima, number_capacidad_minima, number_capacidad_sin_suplemento, number_cocinas, number_fun, text_cocina_clase, text_cocina_tipo, text_orientacion, checkbox_grups, checkbox_aparcamiento, number_plazas_aparcamiento, checkbox_piscina, text_dimensiones_piscina, text_tipo_piscina, checkbox_piscina_interior_climatizada, checkbox_piscina_climatizada, checkbox_piscina_de_agua_salada, checkbox_tv, checkbox_jardin, checkbox_muebles_jardin, checkbox_plancha, checkbox_chimenea, checkbox_barbacoa, checkbox_radio, checkbox_minibar, checkbox_terraza, checkbox_parcela_vallada, checkbox_caja_seguridad, checkbox_ascensor, checkbox_dvd, checkbox_balcon, checkbox_exprimidor, checkbox_hervidor_electrico, checkbox_secador_pelo, checkbox_zona_ninos, checkbox_gimnasio, checkbox_alarma, checkbox_tennis, checkbox_squash, checkbox_paddel, checkbox_sauna, checkbox_jacuzzi, checkbox_apta_discapacitados, checkbox_nevera, checkbox_congelador, checkbox_lavavajillas, checkbox_lavadora, checkbox_secadora, checkbox_cafetera, checkbox_tostadora, checkbox_microondas, checkbox_horno, checkbox_vajilla, checkbox_utensilios_cocina, id_avantio, dynamic_taxonomy_geographic_language_fields_customitzation, checkbox_cerca_del_mar, checkbox_acceso_directo_mar, checkbox_batidora, checkbox_extra_nevera, checkbox_vinoteca, checkbox_hervidor_agua, checkbox_cafetera_nespresso, checkbox_wifi, checkbox_fibra_optica, checkbox_internet_por_cable, checkbox_internet_velocidad_lenta, checkbox_internet_velocidad_media, checkbox_internet_velocidad_rapida, checkbox_aire_acondicionado, checkbox_ventilador, checkbox_calefacion_central, checkbox_bomba_aire, checkbox_suelo_radiante, checkbox_cine, checkbox_equipo_musica, checkbox_barbacoa_gas, checkbox_bano_turco, checkbox_billar, checkbox_ping_pong, checkbox_seguridad_piscina, checkbox_accesorios_yoga, checkbox_hosekeeping, checkbox_trona, checkbox_mascotas, checkbox_helipuerto, checkbox_zona_de_bar_exterior, checkbox_discoteca_privada, checkbox_distancias_playa_arena, checkbox_distancias_playa_roca, checkbox_distancias_golf, checkbox_distancias_pueblo, checkbox_distancias_supermercado, checkbox_distancias_aeropuerto, multiple_taxonomy_geographic_language_extra, multiple_taxonomy_geographic_language_carac, status, position, text_tipo_descuento, id, language, text_title, number_minprecio, number_precio, textarea_description, text_page_title, auto_slug, text_slug, text_meta_keywords, text_meta_robots, text_meta_description, textarea_scripts_header, textarea_scripts_body, status, position, id_avantio, taxonomy, country, region, city, locality, district
            $id = (int)$accommodation->id;
            $text_title = (string)$accommodation->text_title;
            $descripcion = (string)$accommodation->text_title;
            $lang = (string)$accommodation->language;

            if ( $id_anterior != $id ){
                $id_anterior = $id;
                $counter_property++;
            }// end if

            if($counter_property <= 0) {

                p_($accommodation);

                # insert
                if (!get_post_status($id)) {

                    // p_($accommodation);

                    $counter_language = 0;

                    foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {

                        # id languages different es
                        $id_language = $id . "-" . $counter_language;

                        if ($lang == "es") {
                            $text_title = $description = $this->getTextTitleByIdAcommodationAndLanguage($lang , $id);
                            $post = $this->create_post($id, $text_title, $description = "<p>Sin contenido</p>", "insert");
                        } else {
                            $text_title = $description = $this->getTextTitleByIdAcommodationAndLanguage($lang , $id);
                            $post = $this->create_post($id_language, $text_title, $description = "<p>Sin contenido</p>", "insert");
                        }// end if

                        # insert post and save into vector
                        $post_id = wp_insert_post($post, true);
                        //my_print($post_id);

                        $post_vector[$lang][$counter_property]["post_id"] = $post_id;
                        //my_print($post_vector);

                        # save language
                        pll_set_post_language($post_vector[$lang][$counter_property]["post_id"], $lang);

                        # taxonomy
                        $taxonomy_name = $this->getTaxonomyById((string)$accommodation->dynamic_taxonomy, $lang);
                        //echo "taxonomy_name: " . $taxonomy_name . "<br>";


                        # caracteristicas
                        $this->guardar_caracteristicas($taxonomy_name, $post_id, $avantio_credentials, $accommodation, $lang);

                        $counter_language++;

                    }// end foreach

                # update
                }else{

                    //echo "encontrado post";

                    $post_id = $id;


                    foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {

                        # taxonomy
                        $taxonomy_name = $this->getTaxonomyById((string)$accommodation->dynamic_taxonomy, $lang);
                        //echo "taxonomy_name: " . $taxonomy_name . "<br>";

                        # caracteristicas
                        $this->guardar_caracteristicas($taxonomy_name, $post_id, $avantio_credentials, $accommodation, $lang);

                    }


                } // check if post exist

            } // end if counter property

        }// end foreach accomoddation


        $counter_property = 1;

        for($i = 0; $i < $counter_property; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                if($post_vector[$lang][$i]["post_id"]){
                    $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                    $vector_plantilla[$lang] = $post_vector[$lang][$i]["post_id"];
                }
            }// end for
            # relationship languages between posts
            // pll_save_post_translations(array('en' => $post_id_en, 'es' => $post_id_es));
            //my_print($vector_plantilla);
            pll_save_post_translations($vector_plantilla);
        }// end for



    } // end function


    private function guardar_caracteristicas($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang){

        # property_category
        $this->guardarPropertyCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # property_action_category
        $this->guardarPropertyActionCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # property_status
        $this->guardarPropertyStatus($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # geographic areas
        $this->guardarGeo($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # general details
        $this->guardarGeneralDetails($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # feature
        $this->guardarFeatures($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # services
        $this->guardarServices($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

    }// end function



    private function guardarPropertyCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang){

        # villa de lujo or other
        /*
        if ($this->tipo_de_villa == "villa_de_lujo") {
            $name = "Villa de lujo";
            $name = $this->getTipoDeVillaLanguages($lang);
        } else {
            $name = $taxonomy_name;
        }// end if
        */


        # taxonomy
        $name = $taxonomy_name;

        # property_category
        $name = sanitize_title($taxonomy_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_category', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);

        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_category', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_category', true );

        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_category', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_category', true );
        } // end foreach
        */
    }


    private function guardarPropertyActionCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {
        # property_action_category
        $name_kind_alquiler = sanitize_title($this->getKindAlquiler($lang));
        $my_cat = array(
            'description' => $name_kind_alquiler,
            'slug' => $name_kind_alquiler . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name_kind_alquiler, 'property_action_category', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);


        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_action_category', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name_kind_alquiler, 'property_action_category', true );
        //wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_action_category', true );

        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_action_category', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name_kind_alquiler, 'property_action_category', true );
            //wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_action_category', true );
        } // end foreach
        */

    } // end function


    private function guardarPropertyStatus($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang){

        # property_status
        $name_status = sanitize_title($this->getStatus($lang));
        $my_cat = array(
            'description' => $name_status,
            'slug' => $name_status . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name_status, 'property_status', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);

        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_status', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name_status, 'property_status', true );
        //wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_status', true );


        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_status', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name_status, 'property_status', true );
            //wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_status', true );
        } // end foreach
        */
    }


    private function guardarGeo($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        # geographic ids
        $dynamic_geocountry = intval($accommodation->dynamic_geocountry);
        $dynamic_georegion = intval($accommodation->dynamic_georegion);
        $dynamic_geocity = intval($accommodation->dynamic_geocity);
        $dynamic_geolocality = intval($accommodation->dynamic_geolocality);
        $dynamic_geodistrict = intval($accommodation->dynamic_geodistrict);

        # geographic text_title
        /*
        $geo_titles = $this->getGeoByIdAndLanguage($dynamic_geocountry , $dynamic_georegion , $dynamic_geocity , $dynamic_geolocality , $dynamic_geodistrict , $lang );
        $dynamic_geocountry_name = (string)$geo_titles->dynamic_geocountry;
        $dynamic_georegion_name = (string)$geo_titles->dynamic_georegion;
        $dynamic_geocity_name = (string)$geo_titles->dynamic_geocity;
        $dynamic_geolocality_name = (string)$geo_titles->dynamic_geolocality;
        $dynamic_geodistrict_name = (string)$geo_titles->dynamic_geodistrict;
        */

        $dynamic_geocountry_name = $this->getGeocountryById($dynamic_geocountry,$lang);
        $dynamic_georegion_name = $this->getGeoregionById($dynamic_georegion,$lang);
        $dynamic_geocity_name = $this->getGeocityById($dynamic_geocity,$lang);
        $dynamic_geolocality_name = $this->getGeolocalityById($dynamic_geolocality,$lang);
        $dynamic_geodistrict_name = $this->getGeodistrictById($dynamic_geodistrict,$lang);


        # georegion
        $name = sanitize_title($dynamic_georegion_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_city', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        $my_parent = array();
        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_city', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true );
        $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_city', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true );
            $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        } // end foreach
        */
        # geocity
        $name = sanitize_title($dynamic_geocity_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => $my_parent[$lang]
        );
        $my_term_id = term_exists($name, 'property_city', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], array($my_parent[$lang],(int)$my_term_id_translation[$lang]), 'property_city', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true);
        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], array($my_parent[$lang],(int)$my_term_id_translation[$lang]), 'property_city', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true);
        } // end foreach
        */
        # geolocality
        //echo "geolocality: ".$dynamic_geolocality_name."<br>";
        $name = sanitize_title($dynamic_geolocality_name);
        //echo "geolocality: ".$name."<br>";
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($dynamic_geolocality_name, 'property_area', $my_cat);
        //p_($my_term_id);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        $my_parent = array();
        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_area', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_area', true );
        $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_area', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_area', true );
            $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        } // end foreach
        */
        # geodistrict
        //echo "geodistrict: ".$dynamic_geodistrict_name."<br>";
        $name = sanitize_title($dynamic_geodistrict_name);
        //echo "geodistrict: ".$name."<br>";
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" .$lang,
            'parent' => $my_parent[$lang]
        );
        $my_term_id = term_exists($dynamic_geodistrict_name, 'property_area', $my_cat);
        //p_($my_term_id);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], array($my_parent[$lang],(int)$my_term_id_translation[$lang]), 'property_area', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_area', true);
        /*
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], array($my_parent[$lang],(int)$my_term_id_translation[$lang]), 'property_area', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_area', true);
        } // end foreach
        */

    } // end function




    private function guardarGeneralDetails($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        $dynamic_georegion = intval($accommodation->LocalizationData->Region->RegionCode);

        # country list
        //$countries = wpestate_country_list_only_array();
        # data
        # calle piso bloque
        $text_geo_tipo_calle = (string)$accommodation->text_geo_tipo_calle;
        $text_geo_calle = (string)$accommodation->text_geo_calle;
        $text_geo_numero = (string)$accommodation->text_geo_numero;
        $text_geo_bloque = (string)$accommodation->text_geo_bloque;
        $text_geo_puerta = (string)$accommodation->text_geo_puerta;
        $text_geo_piso = (string)$accommodation->text_geo_piso;
        $text_geo_cp = intval($accommodation->text_geo_cp);
        $formato_calle = $text_geo_tipo_calle . " " . $text_geo_calle . " " . " " . $text_geo_numero . " " . $text_geo_bloque . " " . $text_geo_puerta . " " . $text_geo_piso;

        # save_data
        update_post_meta($id, 'guest_no', intval($accommodation->number_capacidad_maxima), $prev_value = '');
        update_post_meta($id, 'property_address', $formato_calle, $prev_value = '');
        update_post_meta($id, 'property_county', "", $prev_value = '');
        update_post_meta($id, 'property_state', "nuevo", $prev_value = '');
        update_post_meta($id, 'property_zip', $text_geo_cp, $prev_value = '');
        update_post_meta($id, 'property_country', "Spain", $prev_value = '');
        update_post_meta($id, 'prop_featured', 1, $prev_value = '');
        update_post_meta($id, 'property_affiliate', "", $prev_value = '');
        update_post_meta($id, 'private_notes', "Mis notas privadas", $prev_value = '');
        update_post_meta($id, 'instant_booking', 1, $prev_value = '');
        # precio de la propiedad
        update_post_meta($id, 'property_price', "", $prev_value = '');
        update_post_meta($id, 'property_price_before_label', "", $prev_value = '');
        update_post_meta($id, 'property_price_after_label', "", $prev_value = '');
        update_post_meta($id, 'property_taxes', "21", $prev_value = '');
        update_post_meta($id, 'property_price_per_week', "", $prev_value = '');
        update_post_meta($id, 'property_price_per_month', "", $prev_value = '');
        update_post_meta($id, 'price_per_weekeend', "", $prev_value = '');
        update_post_meta($id, 'cleaning_fee', "", $prev_value = '');
        update_post_meta($id, 'cleaning_fee_per_day', 1, $prev_value = '');
        update_post_meta($id, 'city_fee', "", $prev_value = '');
        update_post_meta($id, 'city_fee_per_day', "", $prev_value = '');
        update_post_meta($id, 'min_days_booking', "", $prev_value = '');
        update_post_meta($id, 'security_deposit', "", $prev_value = '');
        update_post_meta($id, 'early_bird_percent', "", $prev_value = '');
        update_post_meta($id, 'early_bird_days', "", $prev_value = '');
        update_post_meta($id, 'extra_price_per_guest', "", $prev_value = '');
        update_post_meta($id, 'overload_guest', "", $prev_value = '');
        update_post_meta($id, 'price_per_guest_from_one', "", $prev_value = '');
        update_post_meta($id, 'checkin_change_over', "", $prev_value = '');
        update_post_meta($id, 'checkin_checkout_change_over', "", $prev_value = '');
        # propiedad media
        update_post_meta($id, 'embed_video_type', "", $prev_value = '');
        update_post_meta($id, 'embed_video_id', "", $prev_value = '');
        update_post_meta($id, 'virtual_tour', "", $prev_value = '');
        # detalles_especificos
        update_post_meta($id, 'property_size', intval($accommodation->number_metros_cuadrados), $prev_value = '');
        update_post_meta($id, 'property_size_parcela', intval($accommodation->number_metros_cuadrados_utiles), $prev_value = '');
        update_post_meta($id, 'property_rooms', intval($accommodation->number_habitaciones), $prev_value = '');
        update_post_meta($id, 'property_bedrooms', intval($accommodation->number_camas_doble) + intval($accommodation->number_camas_individual), $prev_value = '');
        update_post_meta($id, 'property_bathrooms', intval($accommodation->number_banyos_banyera) + intval($accommodation->number_banyos_ducha), $prev_value = '');
        update_post_meta($id, 'cancellation_policy', "texto de política de cancelación", $prev_value = '');
        update_post_meta($id, 'other_rules', "otras reglas", $prev_value = '');
        # caracteristicas adicionales
        //$fumadores = ((string)$accommodation->Features->HouseCharacteristics->SmokingAllowed == 'true') ? "yes" : "no";
        //$jovenes_fiestas = ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? "yes" : "no";
        $text_numero_registro_turistico = (string)$accommodation->text_numero_registro_turistico;
        update_post_meta($id, 'smoking_allowed', "", $prev_value = '');
        update_post_meta($id, 'party_allowed', "", $prev_value = '');
        update_post_meta($id, 'pets_allowed', "", $prev_value = '');
        update_post_meta($id, 'registro_turistico', "$text_numero_registro_turistico", $prev_value = '');

        update_post_meta($id, 'children_allowed', "yes", $prev_value = '');
        update_post_meta($id, 'property_bathrooms_banera', intval($accommodation->number_banyos_banyera), $prev_value = '');
        update_post_meta($id, 'property_bathrooms_ducha', intval($accommodation->number_banyos_ducha), $prev_value = '');
        update_post_meta($id, 'property_aseos', intval($accommodation->number_aseos), $prev_value = '');
        # mapa
        # geo latitud longitud
        update_post_meta($id, 'property_latitude', (string)$accommodation->text_geo_latitud, $prev_value = '');
        update_post_meta($id, 'property_longitude', (string)$accommodation->text_geo_longitud, $prev_value = '');


    } // end function




    private function guardarFeatures($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        # create plantilla  and get all id languages and get es
        $plantilla = range(0, 38);
        $my_post_translation = pll_get_post_translations($id);

        $this->textosFeatureXml($lang);

        ((string)$accommodation->checkbox_sauna == 1) ? $plantilla[0] = 1 : $plantilla[0] = 0;
        ((string)$accommodation->checkbox_jacuzzi == 1) ? $plantilla[1] = 1 : $plantilla[1] = 0;
        ((string)$accommodation->checkbox_secador_pelo == 1) ? $plantilla[2] = 1 : $plantilla[2] = 0;
        ((string)$accommodation->checkbox_ascensor == 1) ? $plantilla[3] = 1 : $plantilla[3] = 0;
        ((string)$accommodation->checkbox_grups == 1) ? $plantilla[4] = 1 : $plantilla[4] = 0;
        isset($accommodation->checkbox_piscina) ? $plantilla[5] = 1 : $plantilla[5] = 0;
        ((string)$accommodation->checkbox_tv == 1) ? $plantilla[6] = 1 : $plantilla[6] = 0;
        ((string)$accommodation->checkbox_jardin == 1) ? $plantilla[7] = 1 : $plantilla[7] = 0;
        ((string)$accommodation->checkbox_muebles_jardin == 1) ? $plantilla[8] = 1 : $plantilla[8] = 0;
        ((string)$accommodation->checkbox_plancha == 1) ? $plantilla[9] = 1 : $plantilla[9] = 0;
        ((string)$accommodation->checkbox_chimenea == 1) ? $plantilla[10] = 1 : $plantilla[10] = 0;
        ((string)$accommodation->checkbox_barbacoa == 1) ? $plantilla[11] = 1 : $plantilla[11] = 0;
        ((string)$accommodation->checkbox_radio == 1) ? $plantilla[12] = 1 : $plantilla[12] = 0;
        ((string)$accommodation->checkbox_minibar == 1) ? $plantilla[13] = 1 : $plantilla[13] = 0;
        ((string)$accommodation->checkbox_terraza == 1) ? $plantilla[14] = 1 : $plantilla[14] = 0;
        ((string)$accommodation->checkbox_parcela_vallada == 1) ? $plantilla[15] = 1 : $plantilla[15] = 0;
        ((string)$accommodation->checkbox_caja_seguridad == 1) ? $plantilla[16] = 1 : $plantilla[16] = 0;
        ((string)$accommodation->checkbox_caja_seguridad == 1) ? $plantilla[17] = 1 : $plantilla[17] = 0;
        ((string)$accommodation->checkbox_balcon == 1) ? $plantilla[18] = 1 : $plantilla[18] = 0;
        ((string)$accommodation->checkbox_exprimidor == 1) ? $plantilla[19] = 1 : $plantilla[19] = 0;
        ((string)$accommodation->checkbox_hervidor_electrico == 1) ? $plantilla[20] = 1 : $plantilla[20] = 0;
        ((string)$accommodation->checkbox_zona_ninos == 1) ? $plantilla[21] = 1 : $plantilla[21] = 0;
        ((string)$accommodation->checkbox_gimnasio == 1) ? $plantilla[22] = 1 : $plantilla[22] = 0;
        ((string)$accommodation->checkbox_alarma == 1) ? $plantilla[23] = 1 : $plantilla[23] = 0;
        ((string)$accommodation->checkbox_tennis == 1) ? $plantilla[24] = 1 : $plantilla[24] = 0;
        ((string)$accommodation->checkbox_squash == 1) ? $plantilla[25] = 1 : $plantilla[25] = 0;
        ((string)$accommodation->checkbox_paddel == 1) ? $plantilla[26] = 1 : $plantilla[26] = 0;
        ((string)$accommodation->checkbox_apta_discapacitados == 1) ? $plantilla[27] = 1 : $plantilla[27] = 0;
        ((string)$accommodation->checkbox_nevera == 1) ? $plantilla[28] = 1 : $plantilla[28] = 0;
        ((string)$accommodation->checkbox_congelador == 1) ? $plantilla[29] = 1 : $plantilla[29] = 0;
        ((string)$accommodation->checkbox_lavavajillas == 1) ? $plantilla[30] = 1 : $plantilla[30] = 0;
        ((string)$accommodation->checkbox_lavadora == 1) ? $plantilla[31] = 1 : $plantilla[31] = 0;
        ((string)$accommodation->checkbox_secadora == 1) ? $plantilla[32] = 1 : $plantilla[32] = 0;
        ((string)$accommodation->checkbox_cafetera == 1) ? $plantilla[33] = 1 : $plantilla[33] = 0;
        ((string)$accommodation->checkbox_tostadora == 1) ? $plantilla[34] = 1 : $plantilla[34] = 0;
        ((string)$accommodation->checkbox_microondas == 1) ? $plantilla[35] = 1 : $plantilla[35] = 0;
        ((string)$accommodation->checkbox_horno == 1) ? $plantilla[36] = 1 : $plantilla[36] = 0;
        ((string)$accommodation->checkbox_vajilla == 1) ? $plantilla[37] = 1 : $plantilla[37] = 0;
        ((string)$accommodation->checkbox_utensilios_cocina == 1) ? $plantilla[38] = 1 : $plantilla[38] = 0;

        $feature_names = $this->textosFeature($lang);
        //my_print($feature_names);

        $vector_features_seleccionados = array();
        foreach($plantilla as $key => $valor){
            if($valor == 1){
                array_push($vector_features_seleccionados , $feature_names[$key]);
            }
        }

        //my_print($vector_features_seleccionados);
        foreach ($vector_features_seleccionados as $feature) {
            $this->insertFeatureValue($feature,$id,$lang);
        } // end foreach


    } // end function



    private function guardarServices($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        /*
        # georegion
        $name = sanitize_title($dynamic_georegion_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_city', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        $my_parent = array();
        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_city', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true );
        $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        */

        $services = $this->getServiceExtra($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);
        //p_($services);
        //echo "id_acommodation: ".$id."<br>";
        foreach ($services as $service) {
            //p_($service);
            $service_name = $this->getServiceNameById($service->dynamic_services , $lang);
            //$service_name = sanitize_title($service_name);
            //p_($service_name);
            # es | en  | others
            $my_cat = array(
                'description' => sanitize_title($service_name),
                'slug' => sanitize_title($service_name),
                'parent' => 0
            );

            $my_term_id = term_exists($service_name, 'extra_services', $my_cat);
            //p_($my_term_id);
            $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
            $my_post_translation = pll_get_post_translations($id);
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'extra_services', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $service_name, 'extra_services', true );

        } // end foreach


    } // end function


    private function insertFeatureValue($name,$id,$lang)
    {

        //$lang = "es";

        $name_total_service = $name . "-" . $lang;

        # feature
        //$name = sanitize_title($name);
        $my_cat = array(
            'description' => $name,
            'slug' => sanitize_title($name_total_service),
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_features', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);

        # lang
        wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_features', true);
        wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_features', true);

        //my_print($my_term_id_translation);
        //my_print($my_post_translation);

        /*
        # lang
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_features', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_features', true);
        } // end foreach
        */

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


    private function textosFeatureXmlPlantilla()
    {
        $plantilla = array(
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
        );


    } // end function


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
     * getAcommodations
     * @param $lang
     * @return bool
     */
    private function getAcommoationsByLang($lang){

        $sql = "select ac.* , dr.* , dtax.text_title as taxonomy ,  
geocountry.text_title as country , dgeoregion.text_title as region, dgeocity.text_title as city, 
dgeolocality.text_title as locality , dgeodistrict.text_title as district
from avantio_accomodations as ac
join dynamic_rooms as dr on dr.id = ac.id
join avantio_accomodations_locations as aal on aal.avantio_accomodations = ac.id
join dynamic_taxonomy as dtax on dtax.id = ac.dynamic_taxonomy
join dynamic_geocountry as dgeocountry on dgeocountry.id = ac.dynamic_geocountry
join dynamic_georegion as dgeoregion on dgeoregion.id = ac.dynamic_georegion
join dynamic_geocity as dgeocity on dgeocity.id = ac.dynamic_geocity
join dynamic_geolocality as dgeolocality on dgeolocality.id = ac.dynamic_geolocality
join dynamic_geodistrict as dgeodistrict on dgeodistrict.id = ac.dynamic_geodistrict
where 
dr.language = '".$lang."' 
and 
ac.language = '".$lang."'
and 
dtax.language = '".$lang."'
and 
dgeocountry.language = '".$lang."'
and 
dgeoregion.language = '".$lang."'
and 
dgeocity.language = '".$lang."'
and 
dgeolocality.language = '".$lang."'
and 
dgeodistrict.language = '".$lang."' ";

        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;

    }// end function


    /**
     * @return bool
     */
    private function getAcommodationById($avantio_credentials)
    {

        $sql = "select ac.*
from avantio_accomodations as ac 
where language = 'es' order by ac.id asc";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;

    } // end function


    private function getAcommodationByIdLang($avantio_credentials)
    {
        $languages = implode("','",$avantio_credentials['ACTIVED_LANGUAGES']);
        $sql = "select ac.*
from avantio_accomodations as ac 
where language IN('".$languages."')
order by ac.id asc limit 0 , 2";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function


    private function getTaxonomyById($id , $lang)
    {
        $sql = "select * from dynamic_taxonomy_group
where language = '".$lang."' and id = '".$id."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->text_title : false;

    } // end function


    # geo queries

    private function getGeoByIdAndLanguage($dynamic_geocountry , $dynamic_georegion , $dynamic_geocity , $dynamic_geolocality , $dynamic_geodistrict , $lang )
    {
        $sql = "select geo_country.text_title as dynamic_geocountry, geo_region.text_title as dynamic_georegion, 
geo_city.text_title as dynamic_geoccity, geo_locality.text_title as dynamic_geolocality , 
geo_district.text_title as dynamic_geodistrict  
from dynamic_geocountry as geo_country
join dynamic_georegion as geo_region on geo_region.dynamic_geocountry = geo_country.id
join dynamic_geocity as geo_city on geo_city.dynamic_georegion = geo_region.id
join dynamic_geolocality as geo_locality on geo_locality.dynamic_geocity = geo_city.id
join dynamic_geodistrict as geo_district on geo_district.dynamic_geolocality = geo_locality.id
where ( geo_country.language = '".$lang."' and geo_country.id = '".$dynamic_geocountry."')
and
(geo_region.language = '".$lang."' and geo_region.id =  '".$dynamic_georegion."')
and
(geo_city.language = '".$lang."' and geo_city.id = '".$dynamic_geocity."')
and
(geo_locality.language = '".$lang."' and geo_locality.id = '".$dynamic_geolocality."')
and
(geo_district.language = '".$lang."' and geo_district.id = '".$dynamic_geodistrict."')";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations : false;

    } // end function


    private function getGeocountryById($dynamic_geocountry , $lang)
    {
        $sql = "select geo_country.text_title as dynamic_geocountry  
from dynamic_geocountry as geo_country where id = '".$dynamic_geocountry."' and language = '".$lang."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->dynamic_geocountry : false;
    } // end function


    private function getGeoregionById($dynamic_georegion , $lang)
    {
        $sql = "select geo_region.text_title as dynamic_georegion  
from dynamic_georegion as geo_region where id = '".$dynamic_georegion."' and language = '".$lang."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->dynamic_georegion : false;

    } // end function

    private function getGeocityById($dynamic_geocity , $lang)
    {
        $sql = "select geo_city.text_title as dynamic_geocity  
from dynamic_geocity as geo_city where id = '".$dynamic_geocity."' and language = '".$lang."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->dynamic_geocity : false;
    } // end function


    private function getGeolocalityById($dynamic_geolocality , $lang)
    {
        $sql = "select geo_locality.text_title as dynamic_geolocality  
from dynamic_geolocality as geo_locality where id = '".$dynamic_geolocality."' and language = '".$lang."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->dynamic_geolocality : false;
    } // end function


    private function getGeodistrictById($dynamic_geodistrict , $lang)
    {
        $sql = "select geo_district.text_title as dynamic_geodistrict  
from dynamic_geodistrict as geo_district where id = '".$dynamic_geodistrict."' and language = '".$lang."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->dynamic_geodistrict : false;
    } // end function


    private function getServiceExtra($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {
        $sql = "select * from avantio_accomodations_extras where avantio_accomodations = '".$id."' ";
        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function

    private function getServiceNameById($id_service , $lang)
    {
        $sql = "select * from dynamic_services where language = '".$lang."' and id = '".$id_service."' ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->text_title : false;
    } // end function


    private function getTextTitleByIdAcommodationAndLanguage($language,$id)
    {
        $sql = " select text_title from avantio_accomodations where id = '".$id."' and language = '".$language."'  ";
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->text_title : false;
    }

}