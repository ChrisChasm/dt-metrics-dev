<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.

/**
 * @todo replace all occurrences of the string "template" with a string of your choice
 * @todo also rename in charts-loader.php
 */

class DT_Template_Metrics_Chart_Template extends DT_Example_Metrics_Chart_Base
{

    public $title = 'Template';
    public $slug = 'template'; // lowercase
    public $js_object_name = 'wpApiTemplate'; // This object will be loaded into the metrics.js file by the wp_localize_script.
    public $js_file_name = 'one-page-chart-template.js'; // should be full file name plus extension
    public $deep_link_hash = '#template_overview'; // should be the full hash name. #template_of_hash
    public $permissions = [ 'view_any_contacts', 'view_project_metrics' ];

    public function __construct() {
        parent::__construct();
        if ( !$this->has_permission() ){
            return;
        }
        $url_path = dt_get_url_path();

        // only load scripts if exact url
        if ( 'metrics/example/'.$this->slug === $url_path ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );
        }
        add_action( 'rest_api_init', [ $this, 'add_api_routes' ] );
    }


    /**
     * Load scripts for the plugin
     */
    public function scripts() {
        wp_register_script( 'amcharts-core', 'https://www.amcharts.com/lib/4/core.js', false, '4' );
        wp_register_script( 'amcharts-charts', 'https://www.amcharts.com/lib/4/charts.js', false, '4' );
        wp_enqueue_script( 'dt_'.$this->slug.'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . $this->js_file_name, [
            'jquery',
            'jquery-ui-core',
            'amcharts-core',
            'amcharts-charts'
        ], filemtime( plugin_dir_path( __FILE__ ) .$this->js_file_name ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->slug.'_script', $this->js_object_name, [
                'name_key' => $this->slug,
                'root' => esc_url_raw( rest_url() ),
                'plugin_uri' => plugin_dir_url( __DIR__ ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'spinner' => '<img src="' .trailingslashit( plugin_dir_url( __DIR__ ) ) . 'ajax-loader.gif" style="height:1em;" />',
                'map_key' => get_option( 'dt_map_key' ), // this expects Disciple Tools to have this google maps key installed
                'stats' => [
                    // add preload stats data into arrays here

                ],
                'translations' => [
                    "title" => $this->title,
                    "Sample API Call" => __( "Sample API Call" )
                ]
            ]
        );
    }

    public function add_api_routes() {
        register_rest_route(
            $this->namespace, 'template/sample', [
                'methods'  => 'POST',
                'callback' => [ $this, 'sample' ],
            ]
        );
    }

    public function sample( WP_REST_Request $request ) {
        if ( !$this->has_permission() ){
            return new WP_Error( __METHOD__, 'Missing auth.' );
        }
        $params = $request->get_params();
        if ( isset( $params['button_data'] ) ) {
            // Do something
            $results = $params['button_data'];
            return $results;
        } else {
            return new WP_Error( __METHOD__, 'Missing parameters.' );
        }
    }

}
