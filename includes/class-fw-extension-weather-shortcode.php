<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

class _FW_Extension_Weather_Shortcode {

    public function __construct() {
        add_shortcode( 'weather', array( $this, 'shortcode' ) );
    }

    public function shortcode( $atts ) {
        $params = shortcode_atts( array(
            'url'     => '',
            'units'   => 'metric',
            'lat'     => '',
            'lon'     => '',
            'zip'     => '',
            'country' => '',
            'type'    => '',
        ), $atts );

        $params[ 'url' ] = ($params[ 'type' ] === 'slider') ? 'http://api.openweathermap.org/data/2.5/forecast' : 'http://api.openweathermap.org/data/2.5/weather';

        $extension = fw()->extensions->get( 'weather' );
        $content   = $extension->getWeather( $params );

        if ( is_wp_error( $content ) ) {
            return $content->get_error_message();
        }

        $view_path = ($params[ 'type' ] === 'slider') ? $extension->locate_path( '/views/shortcode-slider.php' ) : $extension->locate_path( '/views/shortcode-base.php' );
        $img_path  = $extension->locate_URI( '/static/img' );

        return fw_render_view( $view_path, compact( 'content', 'img_path', 'params', 'extension' ) );
    }

}

new _FW_Extension_Weather_Shortcode();
