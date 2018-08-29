<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.

class DT_Advanced_Metrics_UI_Menu
{
    // Change These Names
    public $name_key = 'advanced'; // suggest using one word, lower case.
    public $title = 'Advanced Metrics';
    public $first_item = 'Overview';
    public $jsObject = 'wpApiAdvanced'; // This object will be loaded into the metrics.js file by the wp_localize_script.
    // End Change These Names

    /**
     * This filter adds a menu item to the metrics
     *
     * @param $content
     *
     * @return string
     */
    public function menu( $content ) {
        $content .= '<li><a href="'. site_url( '/metrics/'.$this->name_key.'/' ) .'#'.$this->name_key.'_overview" onclick="show_'.$this->name_key.'_overview()">' .  esc_html__( $this->title ) . '</a>
            <ul class="menu vertical nested">
              <li><a href="'. site_url( '/metrics/'.$this->name_key.'/' ) .'#'.$this->name_key.'_overview" onclick="show_'.$this->name_key.'_overview()">' .  esc_html__( $this->first_item ) . '</a></li>
              <!-- Add new menu list items below -->
              
              
              <!-- End add new menu items -->
            </ul>
          </li>';
        return $content;
    }

    /**
     * Load scripts for the plugin
     */
    public function scripts() {
        wp_enqueue_script( 'dt_'.$this->name_key.'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'metrics.js', [
            'jquery',
            'jquery-ui-core',
        ], filemtime( plugin_dir_path(__DIR__ ) . 'includes/metrics.js' ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->name_key.'_script', $this->jsObject, [
                'name_key' => $this->name_key,
                'root' => esc_url_raw( rest_url() ),
                'plugin_uri' => plugin_dir_url(__DIR__ ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'spinner' => '<img src="' .trailingslashit( plugin_dir_url( __FILE__ ) ) . 'ajax-loader.gif" style="height:1em;" />',
                'map_key' => get_option( 'dt_map_key' ), // this expects Disciple Tools to have this google maps key installed
                'stats' => [
                    // add preload stats data into arrays here

                ],
                'translations' => [
                    "title" => __( $this->title ),
                ]
            ]
        );
    }

    /**
     * Adds URL to the template
     *
     * @param $template_for_url
     *
     * @return mixed
     */
    public function add_url( $template_for_url ) {
        $template_for_url['metrics/'.$this->name_key] = 'template-metrics.php';
        return $template_for_url;
    }

    /**
     * Gets the available url
     * @return string
     */
    public function get_url() {
        if ( isset( $_SERVER["SERVER_NAME"] ) ) {
            $url  = ( !isset( $_SERVER["HTTPS"] ) || @( $_SERVER["HTTPS"] != 'on' ) ) ? 'http://'. sanitize_text_field( wp_unslash( $_SERVER["SERVER_NAME"] ) ) : 'https://'. sanitize_text_field( wp_unslash( $_SERVER["SERVER_NAME"] ) );
            if ( isset( $_SERVER["REQUEST_URI"] ) ) {
                $url .= sanitize_text_field( wp_unslash( $_SERVER["REQUEST_URI"] ) );
            }
        }
        return trim( str_replace( get_site_url(), "", $url ), '/' );
    }

    public function __construct() {
        $this->name_key = str_replace( ' ', '', trim( strtolower( $this->name_key ) ) );
        $url_path = $this->get_url();

        if ( 'metrics' === substr( $url_path, '0', 7 ) ) {

            add_filter( 'dt_templates_for_urls', [ $this, 'add_url' ] ); // add custom URL
            add_filter( 'dt_metrics_menu', [ $this, 'menu' ], 99 );

            if ( 'metrics/'.$this->name_key === $url_path ) {
                add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );
            }
        }
    }

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()
}
DT_Advanced_Metrics_UI_Menu::instance();