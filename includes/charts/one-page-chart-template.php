<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.


class DT_Advanced_Metrics_Chart_Template extends DT_Advanced_Metrics_Chart_Base
{

    public $title = 'Template';
    public $slug = 'template'; // lowercase
    public $js_object_name = 'wpApiTemplate'; // This object will be loaded into the metrics.js file by the wp_localize_script.
    public $js_file_name = 'one-page-chart-template.js'; // should be full file name plus extension
    public $deep_link_hash = '#template_overview'; // should be the full hash name. #example_of_hash
    public $onclick_function = 'show_template_overview()'; // should be full name plus ()


    public function __construct() {
        parent::__construct();

        // Main renaming array. Change these names to customize the template.
        $this->names['template'] = [
            'slug' =>  'template',
            'title' =>  'Template',
            'js_object_name' =>  'wpApiTemplate',
            'js_file_name' =>  'one-page-chart-template.js',
            'deep_link_hash' =>  '#template_overview',
            'onclick_function' =>  'show_template_overview()',
        ];
        
        
        // Add menu item
        $this->list_item[] = [
            'slug' => $this->names['template']['slug'],
            'deep_link_hash' => $this->names['template']['deep_link_hash'],
            'onclick_function' => $this->names['template']['onclick_function'],
            'title' => $this->names['template']['title'],
        ];

        // get current url from parent
        $url_path = $this->get_url();

         // loads only for the advanced folder
        if ( 'metrics/advanced' === substr( $url_path, '0', 16 ) ) {

            add_filter( 'dt_templates_for_urls', [ $this, 'add_url' ] ); // add custom URL

            // only load script if exact url
            if ( 'metrics/advanced/'.$this->names['template']['slug'] === $url_path ) {
                add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );
            }
        }
        add_action( 'rest_api_init', [ $this, 'add_api_routes' ] );
    }

    /**
     * Adds URL to the template
     *
     * @param $template_for_url
     *
     * @return mixed
     */
    public function add_url( $template_for_url ) {
        $template_for_url['metrics/advanced/'.$this->names['template']['slug'] ] = 'template-metrics.php';
        return $template_for_url;
    }


    /**
     * Load scripts for the plugin
     */
    public function scripts() {
        wp_enqueue_script( 'dt_'.$this->names['template']['slug'].'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . $this->names['template']['js_file_name'], [
            'jquery',
            'jquery-ui-core',
        ], filemtime( plugin_dir_path( __DIR__ ) . 'includes/charts/'.$this->names['template']['js_file_name'] ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->names['template']['slug'].'_script', $this->names['template']['js_object_name'], [
                'name_key' => $this->names['template']['slug'],
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
                    "title" => $this->names['template']['title'],
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
