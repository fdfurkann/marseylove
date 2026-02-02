<?php
class Rental_Activator {
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wc_rental_availability';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			product_id bigint(20) NOT NULL,
			rental_date date NOT NULL,
			status varchar(20) DEFAULT 'available',
			order_id bigint(20) DEFAULT 0,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
