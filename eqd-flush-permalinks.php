<?php
/*
Plugin Name: Equalize Digital Flush Permalinks
Plugin URI:  https://equalizedigital.com/
Description: This plugin is used to schedule a WordPress cron job that flushes permalinks every five minutes.
Version:     1.0
Author:      Your Name
Author URI:  https://equalizedigital.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: eqd-flush-permalinks
*/

/**
 * Class EQD_Flush_Permalinks
 *
 * This class is used to schedule a WordPress cron job that flushes permalinks every five minutes.
 */
class EQD_Flush_Permalinks {

	/**
	 * EQD_Flush_Permalinks constructor.
	 *
	 * The constructor adds the necessary actions and filters when an object of this class is created.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'custom_cron_job_recurrence' ) );
		add_action( 'wp', array( $this, 'schedule_flush_permalinks' ) );
		add_action( 'eqd_flush_permalinks_cron_job', array( $this, 'flush_permalinks' ) );
	}

	/**
	 * Add a custom cron job recurrence interval of five minutes.
	 *
	 * @param array $schedules An array of non-default cron schedules.
	 *
	 * @return array Filtered array of non-default cron schedules.
	 */
	public function custom_cron_job_recurrence( $schedules ) {
		$schedules['every_five_minutes'] = array(
			'interval' => 5 * MINUTE_IN_SECONDS,
			'display'  => __( 'Every Five Minutes', 'eqd-flush-permalinks' ),
		);

		return $schedules;
	}

	/**
	 * Flush permalinks.
	 *
	 * This function flushes the permalinks when the cron job is executed.
	 */
	public function flush_permalinks() {
		flush_rewrite_rules();
	}

	/**
	 * Schedule the permalink flush cron job.
	 *
	 * This function checks if the cron job is already scheduled, and if not, it schedules the job.
	 */
	public function schedule_flush_permalinks() {
		if ( ! wp_next_scheduled( 'eqd_flush_permalinks_cron_job' ) ) {
			wp_schedule_event( time(), 'every_five_minutes', 'eqd_flush_permalinks_cron_job' );
		}
	}
}

$eqd_flush_permalinks = new EQD_Flush_Permalinks();
