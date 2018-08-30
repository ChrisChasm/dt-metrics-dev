<?php


abstract class DT_Advanced_Metrics_Chart_Base
{

    public $names = [];
    public $list_item = [];
    public $namespace = "dt/v1/advanced/";

    /**
     * Disciple_Tools_Counter constructor.
     */
    public function __construct()
    {
        // these are the master list of names
        $this->names['base'] = [
           'slug' =>  'advanced',
           'title' =>  'Advanced Metrics',
           'js_object_name' =>  'wpApiAdvanced',
           'js_file_name' =>  'charts-base.js',
           'deep_link_hash' =>  '#advanced_overview',
           'onclick_function' =>  'show_advanced_overview()',
           'first_item' =>  'Overview',
        ];
        
        $this->names['base']['slug'] = str_replace( ' ', '', trim( strtolower( $this->names['base']['slug'] ) ) );
        $url_path = $this->get_url();

        if ( 'metrics' === substr( $url_path, '0', 7 ) ) {

            add_filter( 'dt_templates_for_urls', [ $this, 'base_add_url' ] ); // add custom URL
            add_filter( 'dt_metrics_menu', [ $this, 'base_menu' ], 99 );

            if ( 'metrics/advanced' === $url_path ) {
                add_action( 'wp_enqueue_scripts', [ $this, 'base_scripts' ], 99 );
            }
        }
        add_action( 'rest_api_init', [ $this, 'base_api_routes' ] );

    }

    public function get_url() {
        if ( isset( $_SERVER["SERVER_NAME"] ) ) {
            $url  = ( !isset( $_SERVER["HTTPS"] ) || @( $_SERVER["HTTPS"] != 'on' ) ) ? 'http://'. sanitize_text_field( wp_unslash( $_SERVER["SERVER_NAME"] ) ) : 'https://'. sanitize_text_field( wp_unslash( $_SERVER["SERVER_NAME"] ) );
            if ( isset( $_SERVER["REQUEST_URI"] ) ) {
                $url .= sanitize_text_field( wp_unslash( $_SERVER["REQUEST_URI"] ) );
            }
        }
        return trim( str_replace( get_site_url(), "", $url ), '/' );
    }

    public function base_menu( $content ) {
        $lines = '';
        $list = $this->names;
        unset($list['base']);
        array_filter($list);
        if ( ! empty( $list ) ) {
            foreach ( $list as $item ) {
                dt_write_log($item);
                $lines .= '<li><a href="'. site_url( '/metrics/'.$this->names['base']['slug'].'/'.$item['slug'].'/' ) . $item['deep_link_hash'].'" onclick="'.$item['onclick_function'].'">' . $item['title'] . '</a></li>';
            }
        }

        $content .= '
            <li><a href="'. site_url( '/metrics/'. $this->names['base']['slug'] .'/'. $this->names['base']['deep_link_hash'] ) .'" onclick="'.$this->names['base']['onclick_function'].'">'.$this->names['base']['title'].'</a>
                <ul class="menu vertical nested">
                    <li><a href="'. site_url( '/metrics/'. $this->names['base']['slug'] .'/'. $this->names['base']['deep_link_hash'] ) . '" onclick="'.$this->names['base']['onclick_function'].'">'.$this->names['base']['first_item'].'</a></li>';
        $content .= $lines; // this adds other menu items after
        $content .= '</ul></li>';

        return $content;
    }

    public function base_add_url( $template_for_url ) {
        $template_for_url['metrics/'.$this->names['base']['slug']] = 'template-metrics.php';
        return $template_for_url;
    }

    public function base_scripts() {
        wp_enqueue_script( 'dt_'.$this->names['base']['slug'].'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . $this->names['base']['js_file_name'], [
            'jquery',
            'jquery-ui-core',
        ], filemtime( plugin_dir_path( __DIR__ ) . 'includes/'.$this->names['base']['js_file_name'] ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->names['base']['slug'].'_script', $this->names['base']['js_object_name'], [
                'slug' => $this->names['base']['slug'],
                'root' => esc_url_raw( rest_url() ),
                'plugin_uri' => plugin_dir_url( __DIR__ ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'spinner' => '<img src="' .trailingslashit( plugin_dir_url( __DIR__ ) ) . 'ajax-loader.gif" style="height:1em;" />',
                'map_key' => get_option( 'dt_map_key' ), // this expects Disciple Tools to have this google maps key installed
                'stats' => $this->base_chart_data(),
                'translations' => $this->base_translations(),
            ]
        );
    }

    public function base_chart_data() {
        return [
            'sample' => [],
        ];
    }

    public function base_translations() {
        return [
            "title" => $this->names['base']['title'],
        ];
    }

    /**
     * Rest endpoint
     */
    public function base_api_routes() {
        register_rest_route(
            $this->namespace, 'sample', [
                'methods'  => 'POST',
                'callback' => [ $this, 'base_sample' ],
            ]
        );
    }

    public function base_sample( WP_REST_Request $request ) {

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