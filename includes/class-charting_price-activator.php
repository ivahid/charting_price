<?php

/**
 * Fired during plugin activation
 *
 * @link       s7n.ir
 * @since      1.0.0
 *
 * @package    Charting_price
 * @subpackage Charting_price/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Charting_price
 * @subpackage Charting_price/includes
 * @author     hosseinnaghneh <hosseinnaghneh@live.com>
 */
class Charting_price_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            self::cp_create_database();
	}

    private static function cp_create_database()
    {
        global $wpdb;
        $Charting_price = new Charting_price();
        $pluginVersion = $Charting_price->get_version();
        $installed_ver = get_option("cp_db_version");
        if ($installed_ver != $pluginVersion) {
            update_option('cp_db_version', $pluginVersion);
            $table_name = $wpdb->prefix . 'cp';
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id mediumint(9) NOT NULL,
            price VARCHAR(15) DEFAULT '' NOT NULL,
            time VARCHAR(15),
            PRIMARY KEY  (id)
	) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);


        }
    }
}
