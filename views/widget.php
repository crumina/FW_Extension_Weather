<?php
/**
 * @var $title
 * @var $content
 * @var $img_path
 * @var $params
 * @var $extension
 * @var $before_widget
 * @var $after_widget
 */
echo $before_widget;
echo $title;

if ( !isset( $content->list ) || count( $content->list ) < 2 ) {
    return false;
}

$units         = $extension->getUnits( $params[ 'units' ] );
$current       = array_shift( $content->list );
$current->icon = $extension->getIcon( isset( $current->weather[ 0 ]->icon ) ? $current->weather[ 0 ]->icon : false );
?>
<div class="widget w-wethear" style="background-image: url(<?php echo $img_path; ?>/bg-wethear.jpg) !important;">
    <div class="wethear-now inline-items">
        <div class="temperature-sensor"><?php echo round( $current->main->temp ) . "°{$units[ 'tmp' ]}"; ?></div>
        <div class="max-min-temperature">
            <span><?php echo round( $current->main->temp_min ) . "°{$units[ 'tmp' ]}"; ?></span>
            <span><?php echo round( $current->main->temp_max ) . "°{$units[ 'tmp' ]}"; ?></span>
        </div>
        <?php if ( $current->icon ) { ?>
            <svg class="olymp-weather-partly-sunny-icon"><use xlink:href="<?php echo $img_path; ?>/icons-weather.svg<?php echo $current->icon; ?>"></use></svg>
        <?php } ?>
    </div>

    <?php if ( isset( $current->weather[ 0 ]->main ) ) { ?>
        <div class="wethear-now-description">
            <div class="climate"><?php echo esc_html( $current->weather[ 0 ]->main ); ?></div>
        </div>
    <?php } ?>

    <ul class="weekly-forecast">
        <?php
        $i = 1;
        foreach ( $content->list as $item ) {
            $icon = $extension->getIcon( isset( $item->weather[ 0 ]->icon ) ? $item->weather[ 0 ]->icon : false );
            ?>
            <li>
                <div class="day"><?php echo date( 'D', $item->dt ); ?><br /><small><?php echo $item->time; ?></small></div>
                <svg class="olymp-weather-sunny-icon"><use xlink:href="<?php echo $img_path; ?>/icons-weather.svg<?php echo $icon; ?>"></use></svg>

                <div class="temperature-sensor-day"><?php echo round( $item->main->temp ) . "°{$units[ 'tmp' ]}"; ?></div>
            </li>
            <?php
            if ( $i === 7 ) {
                break;
            }
            $i++;
        }
        ?>
    </ul>

    <div class="date-and-place">
        <h5 class="date"><?php echo date( get_option( 'date_format' ) ); ?></h5>
        <div class="place"><?php echo isset( $content->city->name ) && isset( $content->city->country ) ? "{$content->city->name}, {$content->city->country}" : ""; ?></div>
    </div>

</div>

<?php echo $after_widget; ?>