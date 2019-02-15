<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

class _FW_Extension_Weather_Widget extends WP_Widget {

    public $slug  = 'fw-extension-weather-widget';
    public $title = 'Weather';

    public function __construct() {
        $this->acf();

        $widget_ops = array(
            'customize_selective_refresh' => true
        );

        parent::__construct( $this->slug, $this->title, $widget_ops );
    }

    public function acf() {
        acf_add_local_field_group( array(
            'key'                   => 'group_5a8579b70e591',
            'title'                 => $this->title,
            'fields'                => array(
                array(
                    'key'               => 'field_extension_widget_weather_title',
                    'label'             => 'Title',
                    'name'              => 'title',
                    'type'              => 'text',
                    'value'             => NULL,
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'default_value'     => '',
                    'placeholder'       => '',
                    'prepend'           => '',
                    'append'            => '',
                    'maxlength'         => '',
                ),
                array(
                    'key'               => 'field_5a859ffad60bd',
                    'label'             => 'Select place',
                    'name'              => 'place',
                    'type'              => 'google_map',
                    'value'             => NULL,
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'center_lat'        => '',
                    'center_lng'        => '',
                    'zoom'              => 6,
                    'height'            => 200,
                ),
                array(
                    'key'               => 'field_5a8d33e1265da',
                    'label'             => 'Select units',
                    'name'              => 'units',
                    'type'              => 'select',
                    'value'             => NULL,
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'choices'           => array(
                        'default'  => 'Default',
                        'metric'   => 'Metric',
                        'imperial' => 'Imperial',
                    ),
                    'default_value'     => array(
                    ),
                    'allow_null'        => 1,
                    'multiple'          => 0,
                    'ui'                => 0,
                    'ajax'              => 0,
                    'return_format'     => 'value',
                    'placeholder'       => '',
                ),
            ),
            'location'              => array(
                array(
                    array(
                        'param'    => 'widget',
                        'operator' => '==',
                        'value'    => $this->slug,
                    ),
                ),
            ),
            'menu_order'            => 0,
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen'        => '',
            'active'                => 1,
            'description'           => '',
        ) );
    }

    public function form( $instance ) {
        
    }

    public function update( $new_instance, $old_instance ) {
        $instance            = $old_instance;
        $instance[ 'title' ] = sanitize_text_field( $new_instance[ 'acf' ][ 'field_extension_widget_weather_title' ] );
        return $instance;
    }

    public function widget( $args, $instance ) {
        $title         = empty( $instance[ 'title' ] ) ? '' : apply_filters( 'widget_title', $instance[ 'title' ] );
        $widget_id     = 'widget_' . $args[ 'widget_id' ];
        $units         = get_field( 'units', $widget_id );
        $place         = get_field( 'place', $widget_id );
        $before_widget = $args[ 'before_widget' ];
        $after_widget  = $args[ 'after_widget' ];

        if ( $title ) {
            $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
        }

        $params = array(
            'url'     => 'http://api.openweathermap.org/data/2.5/forecast',
            'units'   => 'metric',
            'lat'     => '',
            'lon'     => '',
            'zip'     => '',
            'country' => ''
        );

        if ( $units ) {
            $params[ 'units' ] = $units;
        }

        if ( isset( $place[ 'lat' ] ) ) {
            $params[ 'lat' ] = $place[ 'lat' ];
        }

        if ( isset( $place[ 'lng' ] ) ) {
            $params[ 'lon' ] = $place[ 'lng' ];
        }

        $extension = fw()->extensions->get( 'weather' );
        $content   = $this->prepareList( $extension->getWeather( $params ) );

        if ( is_wp_error( $content ) ) {
            return $content->get_error_message();
        }

        $view_path = $extension->locate_path( '/views/widget.php' );
        $img_path  = $extension->locate_URI( '/static/img' );

        echo fw_render_view( $view_path, compact( 'title', 'content', 'img_path', 'params', 'extension', 'before_widget', 'after_widget' ) );
    }

    public function prepareList( $content ) {
        if ( !isset( $content->list ) ) {
            return $content;
        }

        foreach ( $content->list as $key => &$item ) {
            $time = false;
            switch ( date( 'H', $item->dt ) ) {
                case '12':
                    $time = 'Day';
                    break;
                case '21':
                    $time = 'Night';
                    break;
            }
            if ( !$time ) {
                unset( $content->list[ $key ] );
                continue;
            }

            $item->time = $time;
        }

        return $content;
    }

}

add_action( 'widgets_init', function() {
    if ( class_exists( 'acf' ) ) {
        register_widget( '_FW_Extension_Weather_Widget' );
    }
} );
