<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

class FW_Extension_Weather extends FW_Extension {

    /**
     * Called after all extensions instances was created
     * @internal
     */
    protected function _init() {
        
    }

    public function getWeather( $params ) {

        $query = array(
            'appid' => fw_get_db_ext_settings_option( 'weather', 'api-key' ),
        );

        switch ( $params[ 'units' ] ) {
            case 'metric':
                $query[ 'units' ] = 'metric';
                break;
            case 'imperial':
                $query[ 'units' ] = 'imperial';
                break;
            default:
                $query[ 'units' ] = 'default';
        }

        if ( !$query[ 'appid' ] ) {
            return new WP_Error( 'missing-api-key', sprintf( __( '%sOpenWeatherMap api key is missing!%s', 'fw' ), '<span style="color: #f00;">', '</span>' ) );
        }

        if ( !filter_var( $params[ 'url' ], FILTER_VALIDATE_URL ) ) {
            return new WP_Error( 'incorrect-api-url', sprintf( __( '%sIncorrect api url!%s', 'fw' ), '<span style="color: #f00;">', '</span>' ) );
        }

        if ( $params[ 'zip' ] && $params[ 'country' ] ) {
            $query[ 'zip' ] = "{$params[ 'zip' ]},{$params[ 'country' ]}";
        } else if ( $params[ 'lat' ] && $params[ 'lon' ] ) {
            $query[ 'lat' ] = $params[ 'lat' ];
            $query[ 'lon' ] = $params[ 'lon' ];
        } else {
            return new WP_Error( 'missing-location', sprintf( __( '%sLocation is missing!%s', 'fw' ), '<span style="color: #f00;">', '</span>' ) );
        }

        $url = "{$params[ 'url' ]}?" . http_build_query( $query );

        $response = get_site_transient( md5( $url ) );

        if ( !$response ) {
            $response = wp_remote_get( $url );

            if ( $response[ 'response' ][ 'code' ] != 200 ) {
                return new WP_Error( 'incorrect-response', sprintf( __( "%s{$response[ 'response' ][ 'message' ]}!%s", 'fw' ), '<span style="color: #f00;">', '</span>' ) );
            }

            $response = json_decode( $response[ 'body' ] );
            set_site_transient( md5( $url ), $response, HOUR_IN_SECONDS );
        }

        return $response;
    }

    public function getIcon( $code = false ) {
        $icon = false;

        switch ( $code ) {
            case '01d':
            case '01n':
                $icon = '#olymp-weather-clear-sky-icon';
                break;
            case '02d':
            case '02n':
                $icon = '#olymp-weather-few-clouds-icon';
                break;
            case '03d':
            case '03n':
                $icon = '#olymp-weather-broken-clouds-icon';
                break;
            case '04d':
            case '04n':
                $icon = '#olymp-weather-broken-clouds-icon';
                break;
            case '09d':
            case '09n':
                $icon = '#olymp-weather-shower-rain-icon';
                break;
            case '10d':
            case '10n':
                $icon = '#olymp-weather-rain-icon';
                break;
            case '11d':
            case '11n':
                $icon = '#olymp-weather-thunderstorm-icon';
                break;
            case '13d':
            case '13n':
                $icon = '#olymp-weather-snow-icon';
                break;
            case '50d':
            case '50n':
                $icon = '#olymp-weather-mist-icon';
                break;
        }

        return $icon;
    }

    public function getUnits( $type ) {
        $units = array(
            'tmp'   => 'K',
            'speed' => 'meter/sec'
        );

        switch ( $type ) {
            case 'metric':
                $units = array(
                    'tmp'   => 'C',
                    'speed' => 'meter/sec'
                );
                break;
            case 'imperial':
                $units = array(
                    'tmp'   => 'F',
                    'speed' => 'miles/sec'
                );
                break;
        }

        return $units;
    }

}
