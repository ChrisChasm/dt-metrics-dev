<?php

if ( ! function_exists( 'enqueue_amcharts' ) ) {
    function enqueue_amcharts() {
        wp_enqueue_script( 'dt_'.$this->name_key.'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'metrics.js', [
            'jquery',
            'jquery-ui-core',
        ], filemtime( plugin_dir_path( __DIR__ ) . 'includes/metrics.js' ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->name_key.'_script', $this->js_object, [
                'name_key' => $this->name_key,
                'root' => esc_url_raw( rest_url() ),
                'plugin_uri' => plugin_dir_url( __DIR__ ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'spinner' => '<img src="' .trailingslashit( plugin_dir_url( __FILE__ ) ) . 'ajax-loader.gif" style="height:1em;" />',
                'map_key' => get_option( 'dt_map_key' ), // this expects Disciple Tools to have this google maps key installed
                'stats' => [
                    // add preload stats data into arrays here

                ],
                'translations' => [
                    "title" => $this->title,
                ]
            ]
        );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_amcharts', 99 );
}

if ( ! function_exists( 'enqueue_amcharts_geocharts' ) ) {
    function enqueue_amcharts() {
        wp_enqueue_script( 'dt_'.$this->name_key.'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'metrics.js', [
            'jquery',
            'jquery-ui-core',
        ], filemtime( plugin_dir_path( __DIR__ ) . 'includes/metrics.js' ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->name_key.'_script', $this->js_object, [
                'name_key' => $this->name_key,
                'root' => esc_url_raw( rest_url() ),
                'plugin_uri' => plugin_dir_url( __DIR__ ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'spinner' => '<img src="' .trailingslashit( plugin_dir_url( __FILE__ ) ) . 'ajax-loader.gif" style="height:1em;" />',
                'map_key' => get_option( 'dt_map_key' ), // this expects Disciple Tools to have this google maps key installed
                'stats' => [
                    // add preload stats data into arrays here

                ],
                'translations' => [
                    "title" => $this->title,
                ]
            ]
        );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_amcharts', 99 );
}

