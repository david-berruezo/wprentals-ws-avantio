<?php

class AvantioPms{


    private $debug = false;

    # soap client and credentials
    private $client;
    private $credentials = array();
    private $clientsCredentials = array();



    public function __construct()
    {
        // nothing to do
    } // end function



    /**
     * @param $clientCredential
     */
    public function connect($clientCredential , $type = "connection")
    {
        # credentials
        $username = $this->clientsCredentials[$clientCredential]["WEBSERVICE_USER"];
        $password = $this->clientsCredentials[$clientCredential]["Password"];

        # connect
        $this->credentials = array('Language' => 'ES' , 'UserName' => $username , 'Password' => $password);
        if($type == "connection"){
            $this->client = new SoapClient('http://ws.avantio.com/soap/vrmsConnectionServices.php?wsdl', array('connection_timeout' => 10));
        }else{
            $this->client = new SoapClient('http://ws.avantio.com/soap/vrmsInputServices.php?wsdl', array('connection_timeout' => 10));
        }// end if

        // http://ws.avantio.com/soap/vrmsInputServices.php?wsdl

    } // end function



    /**
     * @return array
     */
    public function getClientsCredentials()
    {
        return $this->clientsCredentials;
    }



    /**
     * @param array $clientsCredentials
     */
    public function setClientsCredentials($clientsCredentials)
    {

        if(!$clientsCredentials){
            $this->clientsCredentials = array(

            );
        }else{
            $this->clientsCredentials = $clientsCredentials;
        }// end if

        //p_($this->clientsCredentials);

    }//end function



    /**
     * @param $accommodation_id
     * @param $user_id
     * @param $company
     * @param $fecha_entrada
     * @param $fecha_salida
     * @return int
     */
    public function is_available($accommodation_id , $user_id , $company , $fecha_entrada , $fecha_salida , $adult_number){

        # url http://tiendapisosenmanresa.net/avantiopms/is_available/357837/2021-09-26/2021-09-27
        # url http://tiendapisosenmanresa.net/avantiopms/is_available/359034/2021-09-26/2021-09-27

        /*
        # bora house
        $accommodation_id = 357837;
        $user_id = 1629662129;
        $company = "ga2924";
        $fecha_entrada  = "2021-10-01";
        $fecha_salida = "2021-10-02";


        # sa serra
        $accommodation_id = 359034;
        $user_id = 1631175843;
        $company = "ga2924";
        $fecha_entrada  = "2022-06-01";
        $fecha_salida = "2022-06-05";
        */


        # criteria
        $criteria = array(
            "Accommodation" => array(
                "AccommodationCode" => $accommodation_id,
                "UserCode" => $user_id,
                "LoginGA" => $company
            ),
            "Occupants" => array(
                "AdultsNumber" => $adult_number
            ),
            "DateFrom" => $fecha_entrada,   // "yyyy-mm-dd",
            "DateTo" => $fecha_salida,  //"yyyy-mm-dd"
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Criteria" => $criteria
        );

        # call to webservice
        try{
            $result = $this->client->IsAvailable($request);
        }catch(SoapFault $e){
            return 0;
        }

        # result
        //p_($result);
        //return intval($result->Available->AvailableCode);
        return $result;

    }// end function



    /**
     * @param $accommodation_id
     * @param $user_id
     * @param $company
     * @param $fecha_entrada
     * @param $fecha_salida
     * @return array
     */
    public function get_price($accommodation_id,$user_id,$company,$fecha_entrada,$fecha_salida){

        # url http://tiendapisosenmanresa.net/avantiopms/get_price/357837/2021-09-25/2021-09-26/eur

        /*
        # bora house
        $accommodation_id = 357837;
        $user_id = 1629662129;
        $company = "ga2924";
        $fecha_entrada  = "2021-10-01";
        $fecha_salida = "2021-10-02";


        # sa serra
        $accommodation_id = 359034;
        $user_id = 1631175843;
        $company = "ga2924";
        $fecha_entrada  = "2022-06-01";
        $fecha_salida = "2022-06-05";
        */

        # variables
        $return = array('price' => 0 , 'price_before' => 0);

        # criteria
        $criteria = array(
            "Accommodation" => array(
                "AccommodationCode" => $accommodation_id,
                "UserCode" => $user_id,
                "LoginGA" => $company
            ),
            "Occupants" => array(
                "AdultsNumber" => 2
            ),
            "ArrivalDate" => $fecha_entrada,   // "yyyy-mm-dd",
            "DepartureDate" => $fecha_salida,  //"yyyy-mm-dd"
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Criteria" => $criteria
        );

        # call to webservice
        try{
            $result = $this->client->GetBookingPrice($request);
        }catch(SoapFault $e){
            return $return;
        }

        //p_($result);

        # result
        $return = array('price'=>$result->BookingPrice->RoomOnly, 'price_before'=>$result->BookingPrice->RoomOnlyWithoutOffer);
        return $return;

    }// end function




    /**
     * @param $booking_code
     * @param $localizator
     * @return array
     */
    public function get_booking($booking_code,$localizator){

        # variables
        $return = array();

        # criteria
        $localizer = array(
            "BookingCode" => $booking_code,
            "Localizator" => trim($localizator)
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Localizer" => $localizer
        );

        # call to webservice
        try{
            $result = $this->client->GetBooking($request);
        }catch(SoapFault $e){
            return $return;
        }

        # result
        //p_($result);
        return $result;

    }// end function



    /**
     * @param $booking_code
     * @param $localizator
     * @return array
     */
    public function cancel_booking($booking_code,$localizator){

        # variables
        $return = array();

        # criteria
        $criteria = array(
            "BookingCode" => $booking_code,
            "Localizator" => $localizator,
            "SendMailToOrganization" => FALSE,
            "SendMailToTourist" => FALSE
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Criteria" => $criteria
        );

        # call to webservice
        try{
            $result = $this->client->CancelBooking($request);
        }catch(SoapFault $e){
            return $return;
        }

        # result
        return $result;

    }//end function


    /**
     * @param $fecha_entrada
     * @param $fecha_salida
     * @return array
     */
    public function get_booking_list($fecha_entrada,$fecha_salida){

        # variables
        $return = array();

        # criteria
        $criteria = array(
            "StartDate" => $fecha_entrada,
            "EndDate" => $fecha_salida,
            "Filter" => 'STAY_DATES',
            "LastUpdatedDate" => date("Y-m-d")
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Criteria" => $criteria
        );

        # call to webservice
        try{
            $result = $this->client->GetBookingList($request);
        }catch(SoapFault $e){
            return $return;
        }

        # result
        //p_($result);
        return $result;

    }//end function



    public function set_booking($accommodation_id,$user_id,$company,$fecha_entrada,$fecha_salida,$client_data){

        # variables
        $return = array('price'=>0,'price_before'=>0);

        # criteria
        $criteria = array(
            "Accommodation" => array(
                "AccommodationCode" => $accommodation_id,
                "UserCode" => $user_id,
                "LoginGA" => $company
            ),
            "Occupants" => array(
                "AdultsNumber" => 2
            ),
            "ArrivalDate" => $fecha_entrada,   // "yyyy-mm-dd",
            "DepartureDate" => $fecha_salida,  //"yyyy-mm-dd"
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Criteria" => $criteria
        );


        # call to webservice
        try{
            $result = $this->client->setBooking($request);
        }catch(SoapFault $e){
            return $return;
        }

        # result
        $return = array('price'=>$result->BookingPrice->RoomOnly, 'price_before'=>$result->BookingPrice->RoomOnlyWithoutOffer);
        return $return;

    }//end function



    public function SetAccommodation(){

        /*
        # criteria
        $criteria = array(
            "Accommodation" => array(
                "AccommodationCode" => $accommodation_id,
                "UserCode" => $user_id,
                "LoginGA" => $company
            ),
            "Occupants" => array(
                "AdultsNumber" => 2
            ),
            "ArrivalDate" => $fecha_entrada,   // "yyyy-mm-dd",
            "DepartureDate" => $fecha_salida,  //"yyyy-mm-dd"
        );

        # credentials
        $request = array(
            "Credentials" => $this->credentials,
            "Criteria" => $criteria
        );


        # call to webservice
        try{
            $result = $this->client->setBooking($request);
        }catch(SoapFault $e){
            return $return;
        }

        # result
        $return = array('price'=>$result->BookingPrice->RoomOnly, 'price_before'=>$result->BookingPrice->RoomOnlyWithoutOffer);
        return $return;
        */
    }



}//end class
