<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 04/11/2021
 * Time: 9:00
 */

class Task
{

    public function __construct()
    {
        // construct
    } // end function

    public function initialize(){

    }


    public function wpshout_add_cron_interval( $schedules ) {
        $schedules_one['everyminute'] = array(
            'interval'  => 60, // time in seconds
            'display'   => 'Every Minute'
        );
        $schedules['5seconds'] = array(
            'interval'  => 5, // time in seconds
            'display'   => __('5 seconds' , 'wprentals-ws-avantio')
        );
        return $schedules;
    }


}// end function
?>