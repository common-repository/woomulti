<?php

/**
* @package GenBuz WooMulti
*/

namespace GBWM_Inc\Base;

class GBWM_Deactivate{

    public static function gbwm_deactivate(){

        // Remove crons.
        wp_clear_scheduled_hook('woomulti_files_retention_hook');

        // Get the timestamp for the next event.
        $timestamp = wp_next_scheduled( 'my_schedule_hook' );

        // If this event was created with any special arguments, you need to get those too.
        $original_args = array();

        wp_unschedule_event( $timestamp, 'my_schedule_hook', $original_args );

        flush_rewrite_rules();
    }
}