<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 10/12/2021
 * Time: 13:13
 */


class AvailabilityRates
{

    private $avantio_credential = "servidor_tiendapisos";

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


    public function insertAccomodations($avantio_credentials)
    {

        # vectors
        $post_vector = array();

        # counters
        $counter_property = -1;
        $counter_property_create = 0;
        $id_anterior = 0;

        # get accommodations
        $xml = $this->getAcommodationById($avantio_credentials);
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

            //echo "identificador: " .$id. "<br>";

            if ( $id_anterior != $id ){
                $id_anterior = $id;
                $counter_property++;
            }// end if

            if($counter_property <= 0) {

                # insert
                if (!get_post_status($id)) {

                    //echo "insert post<br>";

                    $counter_language = 0;

                    foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
                        $counter_language++;
                    }// end foreach

                # update
                } else {

                    //echo "encontrado post<br>";

                    # translations
                    $my_post_translation = pll_get_post_translations($id);

                    # disponibilidades
                    $disponibilidades = $this->getAvailabilityById($id);
                    //p_($disponibilidades);

                    # create or not metadata
                    $metas = get_post_custom_keys( $id );

                    delete_post_meta( $id, 'mega_details', '' );
                    delete_post_meta( $id, 'custom_price', '' );

                    if (!in_array("mega_details",$metas))
                        add_post_meta($id, 'mega_details' , "" , false );

                    if (!in_array("custom_price",$metas))
                        add_post_meta($id, 'custom_price' , "" , false );


                    foreach($my_post_translation as $key_lang => $id_post){

                        $vector_disponibilidad = array();
                        $vector_precios = array();

                        if($disponibilidades){
                            foreach($disponibilidades as $disponibilidad){
                                $fecha = $this->createDateTime($disponibilidad->fecha);
                                echo "fecha: ".$fecha."<br>";
                                $dia_disponibilidad = $this->createVectorDisponibilidad($fecha);
                                $precio_noche = $this->getPriceByAcommodationIdAndFecha($id , $disponibilidad->fecha);
                                array_push($vector_disponibilidad , $dia_disponibilidad);
                                $vector_precios[$fecha] = $precio_noche;
                            }// end foreach

                            # save metadata
                            # availability
                            update_post_meta($id_post, 'mega_details',$vector_disponibilidad );
                            //wpml_mega_details_adjust_save($id_post,$vector_disponibilidad);

                            # precios
                            update_post_meta($id_post, 'custom_price',$vector_precios );
                            //wpml_custom_price_adjust_save($id_post , $vector_precios);
                        }

                    }// end foreach

                } // check if post exist

            }// end if

        } // end foreach

    } // end function



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


    private function createVectorDisponibilidad($fecha_unix)
    {
        $vector = array(
            $fecha_unix => array(
                    'period_min_days_booking'             => 1,
                    'period_extra_price_per_guest'        => 0,
                    'period_price_per_weekeend'           => 0,
                    'period_checkin_change_over'          => 0,
                    'period_checkin_checkout_change_over' => 0,
                    'period_price_per_month'              => 0,
                    'period_price_per_week'               => 0
            ),
        );
        return $vector;
    } // end function


    
    private function createDateTime($fecha)
    {
        # format mysql yyyy-mm-dd | fecha por defecto de la plantilla dd-mm-yyyy | $fromdate = "18-12-2021"; // dd-mm-yyyy
        $fecha          = new DateTime($fecha);
        $fecha->format('d-m-y');
        //echo "fecha: ".$fecha->format('d-m-y')."<br>";
        $from_date_unix = $fecha->getTimestamp();

        return $from_date_unix;

    } // end function


    private function getAvailabilityById($id_accommodation)
    {
        $sql = "select ac.id, ac.avantio_occupation_rules , aa.fecha
from avantio_accomodations as ac 
join avantio_availabilities as aa on aa.accommodation_id = '".$id_accommodation."' and aa.occupation_rule_id = ac.avantio_occupation_rules  
where ac.id = '".$id_accommodation."' and aa.status = 1 limit 5 ";

        $acommodations = $this->db->get_results($sql);

        return  ($acommodations) ? $acommodations : false;
    } // end function



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


    private function getPriceByAcommodationIdAndFecha($id_accommodation , $fecha)
    {
        $my_date = new DateTime($fecha);
        $my_date = $my_date->format('y-m-d');
        //echo "fecha ".$my_date."<br>";
        $sql = "select * from avantio_rates where accommodation_id = '".$id_accommodation."' and fecha = '".$fecha."' ";
        // 2000-01-01
        $acommodations = $this->db->get_row($sql);

        return  ($acommodations) ? $acommodations->price : false;
    } // end function
    
}// end class

?>