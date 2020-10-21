<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Statistics Model.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * MAZ Loader Statistics Model.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Statistics_Model {

	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * Get all statistics
	 *
	 * @return  integer
	 */
	public function getStatistics() {
		$table_loaders     = $this->wpdb->prefix . 'mzldr_loaders';
		$table_impressions = $this->wpdb->prefix . 'mzldr_impressions';

		$lastMonth     = date( 'm', strtotime( 'first day of last month' ) );
		$lastMonthYear = date( 'Y', strtotime( 'first day of last month' ) );

		$sql = '
			SELECT
			COUNT(l.id) as total_loaders,
			(
				SELECT COUNT(id)
				FROM `' . $table_loaders . '`
				WHERE published=1
			) enabled_loaders,
			(
				SELECT COUNT(DISTINCT i.loader_id)
				FROM `' . $table_impressions . "` as i
			) seen_by_visitors,
			(
				SELECT SUM(JSON_LENGTH(ll.data, '$.data'))
				FROM `" . $table_loaders . '` as ll    
			) as total_fields,
			(
				SELECT COUNT(i.id)
				FROM `' . $table_impressions . '` as i
			) as total_impressions,
			(
				SELECT COUNT(i.id)
				FROM `' . $table_impressions . '` as i
				WHERE DATE(date) = DATE(NOW() - INTERVAL 1 DAY)
			) as total_impressions_yesterday,
			(
				SELECT COUNT(i.id)
				FROM `' . $table_impressions . '` as i
				WHERE DATE(date) = DATE(NOW())
			) as total_impressions_today,
			(
				SELECT COUNT(i.id)
				FROM `' . $table_impressions . "` as i
				WHERE MONTH(date) = '" . $lastMonth . "' AND
					  YEAR(date) = '" . $lastMonthYear . "'
			) as last_month_impressions
			FROM `" . $table_loaders . '` as l
		';

		$results = $this->wpdb->get_results( $sql );

		if ( isset( $results[0] ) ) {
			$days = (int) cal_days_in_month( CAL_GREGORIAN, date( 'm' ), date( 'Y' ) );

			$last_month_impressions = $results[0]->last_month_impressions;

			$last_month_impressions = $last_month_impressions / date( 'd' );

			$results[0]->month_projection = $last_month_impressions * $days;
		}

		return $results;
	}

	/**
	 * Get the days in the month
	 *
	 * @return  integer
	 */
	function getcalDaysInMonth( $calendar, $month, $year ) {
		return date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
	}

}
