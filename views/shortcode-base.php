<?php
/**
 * @var $content
 * @var $img_path
 * @var $params
 * @var $extension
 */
$icon  = $extension->getIcon( isset( $content->weather[ 0 ]->icon ) ? $content->weather[ 0 ]->icon : false );
$units = $extension->getUnits( $params[ 'units' ] );
?>

<div class="main-header-weather">

    <div class="date-and-place">
        <div class="date"><?php echo date( get_option( 'date_format' ) ); ?></div>
        <div class="place"><?php echo isset( $content->name ) && isset( $content->sys->country ) ? "{$content->name}, {$content->sys->country}" : ""; ?></div>
    </div>
    <?php if ( isset( $content->dt ) ) { ?>
        <div class="wethear-update">
            Updated: <?php echo date( 'j/m g:ia', $content->dt ) ?>
        </div>
    <?php } ?>
    <div class="container">
        <div class="row">
            <div class="m-auto col-lg-4 col-md-8 col-sm-12">
                <div class="wethear-content">
                    <div class="wethear-now">
                        <?php if ( $icon ) { ?>
                            <svg class="olymp-weather-partly-sunny-icon icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $img_path; ?>/icons-weather.svg<?php echo $icon; ?>"></use></svg>
                        <?php } ?>
                        <?php if ( isset( $content->main->temp ) ) { ?>
                            <div class="temperature-sensor"><?php echo round( $content->main->temp ) . "°{$units[ 'tmp' ]}"; ?></div>
                        <?php } ?>
                        <div class="max-min-temperature">
                            <?php if ( isset( $content->main->temp_min ) ) { ?>
                                <span>Low: <?php echo round( $content->main->temp_min ) . "°{$units[ 'tmp' ]}"; ?></span>
                            <?php } ?>
                            <?php if ( isset( $content->main->temp_max ) ) { ?>
                                <span>High: <?php echo round( $content->main->temp_max ) . "°{$units[ 'tmp' ]}"; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if ( isset( $content->weather[ 0 ]->main ) ) { ?>
                        <div class="climate"><?php echo esc_html( $content->weather[ 0 ]->main ); ?></div>
                    <?php } ?>

                    <?php if ( isset( $content->wind->speed ) ) { ?>
                        <div class="wethear-now-description">
                            <div>
                                <svg class="olymp-weather-wind-icon-header icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $img_path; ?>/icons-weather.svg#olymp-weather-wind-icon-header"></use></svg>
                                <div>Wind Speed</div>
                                <span><?php echo round( $content->wind->speed ), strtoupper( $units[ 'speed' ] ); ?></span>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

</div>