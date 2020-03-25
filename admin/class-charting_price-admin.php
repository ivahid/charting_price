<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       s7n.ir
 * @since      1.0.0
 *
 * @package    Charting_price
 * @subpackage Charting_price/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Charting_price
 * @subpackage Charting_price/admin
 * @author     hosseinnaghneh <hosseinnaghneh@live.com>
 */
class Charting_price_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Charting_price_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Charting_price_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/charting_price-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Charting_price_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Charting_price_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/charting_price-admin.js', array('jquery'), $this->version, false);

    }

    public function add_plugin_admin_menu()
    {
        add_menu_page(__('آرشیو قیمت', 'charting_price'), __('آرشیو قیمت', 'charting_price'), 'manage_options', $this->plugin_name . '_all',
            array($this, 'display_charting_price_page'),
            'dashicons-chart-area');
    }

    public function display_charting_price_page()
    {
        include_once('partials/charting_price-admin-display.php');
    }

    public function insert_price_database($post_id, $post, $update)
    {
        $product = wc_get_product($post_id);
        $price = $product->get_price();
        $time = time();
        global $wpdb;
        $table_name = $wpdb->prefix . 'cp';
        if ($update) {
            $last_price = $this->get_price($post_id, 1);
            if ($last_price) {
                if ($price > $last_price[0]->price) {
                    $price_status = 2;
                } elseif ($price < $last_price[0]->price) {
                    $price_status = 1;
                } else {
                    $price_status = 0;
                }
                update_post_meta($post_id, 'price_status', $price_status);
                $price_time = date("Y-m-d", $last_price[0]->time);
                $now = date("Y-m-d");
                if ($now == $price_time) {
                    $result = $wpdb->update($table_name, array('price' => $price, 'time' => $time), array('id' => $last_price[0]->id));
                    if ($result == 1) {
                        $price_id = $wpdb->insert_id;
                        return $price_id;
                    } else {
                        return $result;
                    }
                }
            }
        }

        $result = $wpdb->insert(
            $table_name,
            array(
                'post_id' => $post_id,
                'price' => $price,
                'time' => $time,
            )
        );

        if ($result == 1) {
            $price_id = $wpdb->insert_id;
            return $price_id;
        } else {
            return $result;
        }
    }

    public function get_price($post_id, $limit = 1)
    {
        if(!$post_id){
            $post_id = $_POST['post_id'];
        }
        if(isset($_POST['limit'])){
            $limit =$_POST['limit'];
        }
        if ($limit && is_array($limit)) {
            $limit_str = 'LIMIT ' . implode(',', $limit);
        } else if ($limit && !is_array($limit)) {
            $limit_str = 'LIMIT ' . $limit;
        } else {
            $limit_str = '';
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'cp';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE post_id = %d ORDER BY id DESC $limit_str", $post_id);
        $result = $wpdb->get_results($query);
        return $result;
    }

    public function get_all_prices()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cp';
        $query = $wpdb->prepare("SELECT * FROM $table_name GROUP BY post_id ORDER BY id DESC");
        $result = $wpdb->get_results($query);
        return $result;
    }


}
