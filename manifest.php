<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$manifest = array();

$manifest[ 'name' ]        = __( 'Weather', 'crumina' );
$manifest[ 'description' ] = __( 'Weather widgets.', 'crumina' );
$manifest[ 'github_repo' ]  = 'https://github.com/crumina/FW_Extension_Weather';
$manifest[ 'version' ]      = '1.0.10';
$manifest[ 'display' ]     = true;
$manifest[ 'standalone' ]  = true;
$manifest[ 'thumbnail' ]   = plugins_url( 'unyson/framework/extensions/weather/static/img/thumbnail.png');
