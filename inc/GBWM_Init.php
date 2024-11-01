<?php

/**
* @package GenBuz WooMulti
*/

namespace GBWM_Inc;

// final class so it cannot be extended
final class GBWM_Init
{

	/**
	 * let set up the classes that we want to be auto loaded
	 * and store them in an array to foreach.
	 */
	public static function gbwm_get_services()
	{
		// classes array
		return array(
			Base\GBWM_SettingsLink::class,
			Base\GBWM_Routes::class,
			Base\GBWM_Menu::class,
			Base\GBWM_Ajax::class,
			Base\GBWM_Tracking::class,
			Base\GBWM_Admin::class,
			Base\GBWM_Activate::class,
			Base\GBWM_Cron::class,
		);
	}

	/**
	 * this is the function called from the main plugin page
	 * this functions will foreach through the "gbwm_get_services"
	 * array and run the code found in the "gbwm_register" function.
	 */
	public static function gbwm_register_services()
	{
		// get all our services/classes and foreach them
		foreach( self::gbwm_get_services() as $class )
		{
			// start the class
			$service = new $class;

			// if the class has a "gbwm_register" function
			if( method_exists( $service, 'gbwm_register' ) )
			{
				// run the code in the "gbwm_register" function
				$service->gbwm_register();
			}
		}// end foreach
	}// end function

}// end Init class