<?php
/**
 * @var $content
 * @var $img_path
 * @var $params
 * @var $extension
 */
?>
<div class="swiper-container pagination-bottom" data-slide="fade">
    <div class="swiper-wrapper">
        <?php
        if ( isset( $content->list ) ) {
            ?>

            <div class="swiper-slide swiper-slide-weather" data-swiper-parallax="-500">
                <?php
                $i     = 1;
                $units = $extension->getUnits( $params[ 'units' ] );
                foreach ( $content->list as $item ) {
                    $time = false;
                    switch ( date( 'H', $item->dt ) ) {
                        case '06':
                            $time = 'Morning';
                            break;
                        case '12':
                            $time = 'Day';
                            break;
                        case '18':
                            $time = 'Evening';
                            break;
                        case '21':
                            $time = 'Night';
                            break;
                    }
                    if ( !$time ) {
                        continue;
                    }

                    $icon = $extension->getIcon( isset( $item->weather[ 0 ]->icon ) ? $item->weather[ 0 ]->icon : false );
                    ?>
                    <div class="day-wethear-item" data-mh="wethear-item">
                        <div class="title"><?php echo date( 'l j', $item->dt ); ?><br /><small><?php echo $time; ?></small></div>

                        <svg class="olymp-weather-sunny-icon icon"><use xlink:href="<?php echo $img_path; ?>/icons-weather.svg<?php echo $icon; ?>"></use></svg>

                        <div class="wethear-now">
                            <div class="temperature-sensor"><?php echo round( $item->main->temp ) . "°{$units[ 'tmp' ]}"; ?></div>
                            <div class="max-min-temperature">
                                <span><?php echo round( $item->main->temp_min ) . "°{$units[ 'tmp' ]}"; ?></span>
                                <span class="high"><?php echo round( $item->main->temp_max ) . "°{$units[ 'tmp' ]}"; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ( $i % 6 === 0 ) {
                        ?>
                    </div>
                    <div class="swiper-slide swiper-slide-weather" data-swiper-parallax="-500">
                        <?php
                    }
                    $i++;
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <!-- If we need pagination -->
    <div class="swiper-pagination pagination-blue"></div>
</div>