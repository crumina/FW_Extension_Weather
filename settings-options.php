<?php

$options = array(
    'section_1' => array(
        'title'   => __( 'General', 'fw' ),
        'type'    => 'box',
        'options' => array(
            'api-key' => array(
                'type'  => 'text',
                'label' => __( 'Api Key', 'fw' ),
                'desc'  => sprintf( __( 'You can get Api Key %shere%s.', 'fw' ), '<a target="_blank" href="https://home.openweathermap.org/api_keys">', '</a>' )
            ),
        ),
    ),
);
