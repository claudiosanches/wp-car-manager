<?php

namespace Never5\WPCarManager\Ajax;

use Never5\WPCarManager\Vehicle;

class GetVehiclesListings extends Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'get_vehicles_listings' );
	}

	/**
	 * AJAX callback method
	 *
	 * @return void
	 */
	public function run() {

		// @todo set filters
		$filters = array();

		// get vehicles
		$vehicle_manager = new Vehicle\Manager();
		$vehicles        = $vehicle_manager->get_vehicles( $filters );

		// check & loop
		if ( count( $vehicles ) > 0 ) {
			foreach ( $vehicles as $vehicle ) {

				// title
				$title = get_the_title( $vehicle->get_id() );

				// get image
				$image = get_the_post_thumbnail( $vehicle->get_id(), apply_filters( 'single_vehicle_listings_thumbnail_size', 'wpcm_vehicle_listings_item' ), array(
					'title' => $title,
					'alt'   => $title,
					'class' => 'wpcm-listings-item-image'
				) );

				// load template
				wp_car_manager()->service( 'template_manager' )->get_template_part( 'listings/item', '', array(
					'url'         => get_permalink( $vehicle->get_id() ),
					'title'       => $title,
					'image'       => $image,
					'description' => $vehicle->get_short_description(),
					'price'       => $vehicle->get_formatted_price(),
					'mileage'     => $vehicle->get_formatted_mileage(),
					'frdate'      => $vehicle->get_frdate()
				) );
			}
		}

		// bye
		exit;
	}

}