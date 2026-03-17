<?php
/**
 * Tour Flights partial – Between "Aperçu du Circuit" and "Programme du Circuit".
 * Displays Vol Aller + Vol Retour (Laravel voyage_flights) as two separate cards when present,
 * otherwise falls back to WP flights list (session Add/Remove).
 *
 * @var array $tour Tour data (id, flights, all_flights, outboundFlight, inboundFlight, _session_token)
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

$tour_id = isset($tour['id']) ? (int) $tour['id'] : 0;
$flights = isset($tour['flights']) ? $tour['flights'] : [];
$all_flights = isset($tour['all_flights']) ? $tour['all_flights'] : [];
$outboundFlight = $tour['outboundFlight'] ?? null;
$inboundFlight = $tour['inboundFlight'] ?? null;
// Multi-vol: outbound/inbound can be arrays of rows
$outboundList = is_array($outboundFlight) && isset($outboundFlight[0]) && is_array($outboundFlight[0]) ? $outboundFlight : ($outboundFlight ? [$outboundFlight] : []);
$inboundList = is_array($inboundFlight) && isset($inboundFlight[0]) && is_array($inboundFlight[0]) ? $inboundFlight : ($inboundFlight ? [$inboundFlight] : []);
$session_token = isset($tour['_session_token']) ? $tour['_session_token'] : '';
$has_laravel_flights = (function_exists('ajtb_flights_have_content') && ajtb_flights_have_content($outboundList)) || (function_exists('ajtb_flights_have_content') && ajtb_flights_have_content($inboundList));
$has_wp_flights = !empty($flights) || !empty($all_flights);

if (!$has_laravel_flights && !$has_wp_flights) {
    return;
}
?>
<section class="ajtb-section padding20" id="flights">
    <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Informations Vols', 'ajinsafro-tour-bridge'); ?></h2>
    <div id="ajtb-flights-container">
        <?php if ($has_laravel_flights): ?>
            <div class="commute-wrapper-v2" data-tour-id="<?php echo esc_attr($tour_id); ?>">
                <?php if (function_exists('ajtb_flights_have_content') && ajtb_flights_have_content($outboundList)): ?>
                    <div class="card-separator-commute">
                        <h3 class="font14 latoBold appendBottom10"><?php esc_html_e('Vol Aller', 'ajinsafro-tour-bridge'); ?> (Jour 1)</h3>
                        <?php foreach ($outboundList as $flight): if (function_exists('ajtb_flight_has_content') && !ajtb_flight_has_content($flight)) { continue; } $show_remove = false; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (function_exists('ajtb_flights_have_content') && ajtb_flights_have_content($inboundList)): ?>
                    <div class="card-separator-commute">
                        <h3 class="font14 latoBold appendBottom10"><?php esc_html_e('Vol Retour', 'ajinsafro-tour-bridge'); ?></h3>
                        <?php foreach ($inboundList as $flight): if (function_exists('ajtb_flight_has_content') && !ajtb_flight_has_content($flight)) { continue; } $show_remove = false; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php echo ajtb_render_flights_html($tour_id, $flights, $all_flights, $session_token); ?>
        <?php endif; ?>
    </div>
</section>
