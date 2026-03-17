<?php
/**
 * Itinerary Partial - New design: dayplan-container with dayplan-nav sidebar + dayplan-details
 * Also handles fallback to WP tours_program list.
 *
 * @var array $tour Tour data
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

$itinerary = $tour['itinerary'] ?? [];
$wp_program = $tour['wp_program'] ?? ['style' => 'style1', 'items' => []];
$source = $tour['_sources']['itinerary'] ?? 'wordpress';
$session_token = $tour['_session_token'] ?? '';
$tour_id = (int) ($tour['id'] ?? 0);
$activities_catalog = $tour['activities_catalog'] ?? [];
$can_toggle_activities = ($source === 'laravel' && !empty($session_token) && $tour_id > 0);
$outboundFlight = $tour['outboundFlight'] ?? null;
$inboundFlight = $tour['inboundFlight'] ?? null;

$outboundFlightsList = [];
if (is_array($outboundFlight) && isset($outboundFlight[0]) && is_array($outboundFlight[0])) {
    $outboundFlightsList = $outboundFlight;
} elseif (is_array($outboundFlight) && !empty($outboundFlight) && (isset($outboundFlight['id']) || isset($outboundFlight['flight_type']))) {
    $outboundFlightsList = [$outboundFlight];
}
$inboundFlightsList = [];
if (is_array($inboundFlight) && isset($inboundFlight[0]) && is_array($inboundFlight[0])) {
    $inboundFlightsList = $inboundFlight;
} elseif (is_array($inboundFlight) && !empty($inboundFlight) && (isset($inboundFlight['id']) || isset($inboundFlight['flight_type']))) {
    $inboundFlightsList = [$inboundFlight];
}
$total_days = count($itinerary);
$duration_day = max(1, (int) ($tour['duration_day'] ?? 1));

// Debug
$flights_debug = $tour['_flights_debug'] ?? null;
if ($flights_debug !== null && (defined('WP_DEBUG') && WP_DEBUG || !empty($_GET['ajtb_flights_debug']))) {
    $d = $flights_debug;
    echo '<!-- AJTB flights | table=' . esc_attr($d['table'] ?? '') . ' | exists=' . (isset($d['table_exists']) && $d['table_exists'] ? '1' : '0') . ' | tour_id=' . (int) ($d['tour_id'] ?? 0) . ' | total_rows=' . (int) ($d['total_rows'] ?? 0) . ' | outbound=' . (int) ($d['outbound'] ?? 0) . ' | inbound=' . (int) ($d['inbound'] ?? 0) . ' | segments=' . esc_attr(implode(',', isset($d['segments_keys']) ? $d['segments_keys'] : [])) . ' -->' . "\n";
}

// ── No Laravel days: fallback ──
if (empty($itinerary)) {
    $outbound_count = is_array($outboundFlightsList) ? count($outboundFlightsList) : 0;
    $inbound_count = is_array($inboundFlightsList) ? count($inboundFlightsList) : 0;
    $show_out = $outbound_count > 0;
    $show_in = $inbound_count > 0;
    $has_flights_in_program = $show_out || $show_in;
    $last_day_num = $duration_day;

    if ($has_flights_in_program) {
        ?>
    <section class="ajtb-section padding20" id="itinerary" data-tour-id="<?php echo $tour_id; ?>">
        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Programme du Circuit', 'ajinsafro-tour-bridge'); ?></h2>
        <div class="ajtb-flights-in-programme">
            <div class="card-separator-commute commute-wrapper-v2" data-aj-day-flight="outbound" data-aj-day-number="1">
                <?php if ($show_out):
                    $first_out = $outboundFlightsList[0] ?? [];
                    $fo_from = trim((string) ($first_out['from_city'] ?? $first_out['depart_label'] ?? ''));
                    $fo_to   = trim((string) ($first_out['to_city'] ?? $first_out['arrive_label'] ?? ''));
                    $fo_from = $fo_from !== '' ? $fo_from : '—';
                    $fo_to   = $fo_to !== '' ? $fo_to : '—';
                ?>
                    <h4 class="font14 latoBold appendBottom10"><?php esc_html_e('Vol Aller', 'ajinsafro-tour-bridge'); ?> — <?php echo esc_html("Jour 1 • $fo_from → $fo_to"); ?></h4>
                    <?php $flight = $outboundFlightsList[0]; $show_remove = true; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; ?>
                <?php else: ?>
                    <h4 class="font14 latoBold appendBottom10"><?php esc_html_e('Vol Aller', 'ajinsafro-tour-bridge'); ?> — Jour 1</h4>
                    <?php $label = __('Vol Aller non disponible', 'ajinsafro-tour-bridge'); include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card-unavailable.php'; ?>
                <?php endif; ?>
            </div>
            <div class="card-separator-commute commute-wrapper-v2" data-aj-day-flight="inbound" data-aj-day-number="<?php echo $last_day_num; ?>">
                <?php if ($show_in):
                    $first_in = $inboundFlightsList[0] ?? [];
                    $fi_from = trim((string) ($first_in['from_city'] ?? $first_in['depart_label'] ?? ''));
                    $fi_to   = trim((string) ($first_in['to_city'] ?? $first_in['arrive_label'] ?? ''));
                    $fi_from = $fi_from !== '' ? $fi_from : '—';
                    $fi_to   = $fi_to !== '' ? $fi_to : '—';
                ?>
                    <h4 class="font14 latoBold appendBottom10"><?php esc_html_e('Vol Retour', 'ajinsafro-tour-bridge'); ?> — <?php echo esc_html("Jour $last_day_num • $fi_from → $fi_to"); ?></h4>
                    <?php foreach ($inboundFlightsList as $flight): $show_remove = true; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; endforeach; ?>
                <?php else: ?>
                    <h4 class="font14 latoBold appendBottom10"><?php esc_html_e('Vol Retour', 'ajinsafro-tour-bridge'); ?> — Jour <?php echo $last_day_num; ?></h4>
                    <?php $label = __('Vol Retour non disponible', 'ajinsafro-tour-bridge'); include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card-unavailable.php'; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($wp_program['items'])):
            $program_style = isset($wp_program['style']) ? sanitize_html_class($wp_program['style']) : 'style1';
            if ($program_style === '') { $program_style = 'style1'; }
        ?>
        <div class="aj-program-list program-style-<?php echo esc_attr($program_style); ?> appendTop15">
            <?php foreach ($wp_program['items'] as $item):
                $title = isset($item['title']) ? trim((string) $item['title']) : '';
                $desc = isset($item['desc']) ? trim((string) $item['desc']) : '';
            ?>
                <div class="aj-program-item appendBottom10">
                    <?php if ($title !== ''): ?><h4 class="font14 latoBold"><?php echo esc_html($title); ?></h4><?php endif; ?>
                    <?php if ($desc !== ''): ?><div class="font12 greyText"><?php echo wp_kses_post(nl2br($desc)); ?></div><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
    <?php
        return;
    }

    if (!empty($wp_program['items'])) {
        $program_style = isset($wp_program['style']) ? sanitize_html_class($wp_program['style']) : 'style1';
        if ($program_style === '') { $program_style = 'style1'; }
        ?>
    <section class="ajtb-section padding20" id="itinerary">
        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Programme du Circuit', 'ajinsafro-tour-bridge'); ?></h2>
        <div class="aj-program-list program-style-<?php echo esc_attr($program_style); ?>">
            <?php foreach ($wp_program['items'] as $item):
                $title = isset($item['title']) ? trim((string) $item['title']) : '';
                $desc = isset($item['desc']) ? trim((string) $item['desc']) : '';
            ?>
                <div class="aj-program-item appendBottom10">
                    <?php if ($title !== ''): ?><h4 class="font14 latoBold"><?php echo esc_html($title); ?></h4><?php endif; ?>
                    <?php if ($desc !== ''): ?><div class="font12 greyText"><?php echo wp_kses_post(nl2br($desc)); ?></div><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    } else {
        ?>
    <section class="ajtb-section padding20" id="itinerary">
        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Programme du Circuit', 'ajinsafro-tour-bridge'); ?></h2>
        <p class="font14 greyText"><?php esc_html_e('Programme non disponible.', 'ajinsafro-tour-bridge'); ?></p>
    </section>
    <?php
    }
    return;
}
?>

<?php
// Helper: normalize day flight(s)
$ajtb_day_flights_list = function ($flight_or_list) {
    if (empty($flight_or_list)) {
        return [];
    }
    $first = is_array($flight_or_list) ? reset($flight_or_list) : null;
    $is_list = $first && is_array($first) && (isset($first['from_city']) || isset($first['flight_type']) || isset($first['depart_label']));
    return $is_list ? $flight_or_list : [$flight_or_list];
};

// Helper: compute "INCLUS" text
$ajtb_day_inclus = function ($day, $index, $total_days) use ($ajtb_day_flights_list) {
    $n = (int) ($day['day'] ?? $index + 1);
    $last = $total_days > 0 && $n === (int) $total_days;
    $parts = [];
    if (function_exists('ajtb_flights_have_content') && ajtb_flights_have_content($ajtb_day_flights_list($day['flight'] ?? []))) {
        $cnt = count(array_filter($ajtb_day_flights_list($day['flight'] ?? []), function ($f) { return function_exists('ajtb_flight_has_content') && ajtb_flight_has_content($f); }));
        $parts[] = $cnt . ' ' . _n('Vol', 'Vols', $cnt, 'ajinsafro-tour-bridge');
    }
    if (function_exists('ajtb_flights_have_content') && ajtb_flights_have_content($ajtb_day_flights_list($day['flight_return'] ?? []))) {
        $cnt = count(array_filter($ajtb_day_flights_list($day['flight_return'] ?? []), function ($f) { return function_exists('ajtb_flight_has_content') && ajtb_flight_has_content($f); }));
        $parts[] = $cnt . ' ' . _n('Vol', 'Vols', $cnt, 'ajinsafro-tour-bridge');
    }
    $tr_arr = isset($day['transfer']) && is_array($day['transfer']) ? $day['transfer'] : [];
    $tr_dep = isset($day['transfer_return']) && is_array($day['transfer_return']) ? $day['transfer_return'] : [];
    if (count($tr_arr) + count($tr_dep) > 0) {
        $cnt = count($tr_arr) + count($tr_dep);
        $parts[] = $cnt . ' ' . _n('Transfert', 'Transferts', $cnt, 'ajinsafro-tour-bridge');
    }
    $hotels_list = isset($day['hotels']) && is_array($day['hotels']) ? $day['hotels'] : (!empty($day['hotel']) ? [$day['hotel']] : []);
    if (count($hotels_list) > 0) {
        $parts[] = count($hotels_list) . ' ' . _n('Hôtel', 'Hôtels', count($hotels_list), 'ajinsafro-tour-bridge');
    }
    $act_count = 0;
    if (!empty($day['activities'])) {
        foreach ($day['activities'] as $a) {
            if (!empty($a['is_included'])) $act_count++;
        }
    }
    if ($act_count > 0) {
        $parts[] = $act_count . ' ' . _n('Activité', 'Activités', $act_count, 'ajinsafro-tour-bridge');
    }
    if (!empty(trim((string) ($day['meals'] ?? '')))) {
        $parts[] = '1 ' . __('Repas', 'ajinsafro-tour-bridge');
    }
    return implode(' + ', $parts);
};

// Structured includes per day
$ajtb_day_includes_raw = function ($day, $index, $total_days) use ($ajtb_day_flights_list) {
    $n = (int) ($day['day'] ?? $index + 1);
    $last = $total_days > 0 && $n === (int) $total_days;
    $flights = 0;
    $flight_list = $ajtb_day_flights_list($day['flight'] ?? []);
    $return_list = $ajtb_day_flights_list($day['flight_return'] ?? []);
    foreach ($flight_list as $f) { if (function_exists('ajtb_flight_has_content') && ajtb_flight_has_content($f)) $flights++; }
    foreach ($return_list as $f) { if (function_exists('ajtb_flight_has_content') && ajtb_flight_has_content($f)) $flights++; }
    $tr_arr = isset($day['transfer']) && is_array($day['transfer']) ? $day['transfer'] : [];
    $tr_dep = isset($day['transfer_return']) && is_array($day['transfer_return']) ? $day['transfer_return'] : [];
    $transfers = count($tr_arr) + count($tr_dep);
    $hotels_list_day = isset($day['hotels']) && is_array($day['hotels']) ? $day['hotels'] : (!empty($day['hotel']) ? [$day['hotel']] : []);
    $hotels = count($hotels_list_day);
    $act_count = 0;
    if (!empty($day['activities'])) {
        foreach ($day['activities'] as $a) { if (!empty($a['is_included'])) $act_count++; }
    }
    $meals = !empty(trim((string) ($day['meals'] ?? ''))) ? 1 : 0;
    return ['flights' => $flights, 'transfers' => $transfers, 'hotels' => $hotels, 'activities' => $act_count, 'meals' => $meals];
};

$day_includes_for_js = [];
foreach ($itinerary as $index => $day) {
    $day_num = $day['day'] ?? ($index + 1);
    $day_includes_for_js[(string) $day_num] = $ajtb_day_includes_raw($day, $index, $total_days);
}

// Global totals
$global_totals = ['days' => $total_days, 'flights' => 0, 'transfers' => 0, 'hotels' => 0, 'activities' => 0, 'meals' => 0];
foreach ($itinerary as $index => $day) {
    $day_includes = $ajtb_day_includes_raw($day, $index, $total_days);
    $global_totals['flights'] += $day_includes['flights'];
    $global_totals['transfers'] += $day_includes['transfers'];
    $global_totals['hotels'] += $day_includes['hotels'];
    $global_totals['activities'] += $day_includes['activities'];
    if (!empty(trim((string) ($day['meals'] ?? '')))) {
        $global_totals['meals']++;
    }
}
?>

<section class="ajtb-section ajtb-tab-panel" id="itinerary" data-tour-id="<?php echo $tour_id; ?>" data-session-token="<?php echo esc_attr($session_token); ?>" data-activities-catalog="<?php echo esc_attr(wp_json_encode($activities_catalog)); ?>" data-day-includes="<?php echo esc_attr(wp_json_encode($day_includes_for_js)); ?>">

    <!-- Itinerary Nav Pills -->
    <ul class="initerary-nav" id="initeraryNav" data-testid="itinerary-nav">
        <li class="active" data-ajtb-global-tab="programme">
            <b class="appendRight3"><?php echo $global_totals['days']; ?></b> <?php esc_html_e('Day Plan', 'ajinsafro-tour-bridge'); ?>
        </li>
        <?php if ($global_totals['flights'] > 0 || $global_totals['transfers'] > 0): ?>
            <li data-ajtb-global-tab="flights-transfers">
                <?php if ($global_totals['flights'] > 0): ?>
                    <b class="appendRight3"><?php echo $global_totals['flights']; ?></b> <?php echo $global_totals['flights'] > 1 ? esc_html__('Flights', 'ajinsafro-tour-bridge') : esc_html__('Flight', 'ajinsafro-tour-bridge'); ?>
                <?php endif; ?>
                <?php if ($global_totals['flights'] > 0 && $global_totals['transfers'] > 0): ?>
                    <b class="appendRight3"> &amp; </b>
                <?php endif; ?>
                <?php if ($global_totals['transfers'] > 0): ?>
                    <b class="appendRight3"><?php echo $global_totals['transfers']; ?></b> <?php echo $global_totals['transfers'] > 1 ? esc_html__('Transfers', 'ajinsafro-tour-bridge') : esc_html__('Transfer', 'ajinsafro-tour-bridge'); ?>
                <?php endif; ?>
            </li>
        <?php endif; ?>
        <?php if ($global_totals['hotels'] > 0): ?>
            <li data-ajtb-global-tab="hotels">
                <b class="appendRight3"><?php echo $global_totals['hotels']; ?></b> <?php echo $global_totals['hotels'] > 1 ? esc_html__('Hotels', 'ajinsafro-tour-bridge') : esc_html__('Hotel', 'ajinsafro-tour-bridge'); ?>
            </li>
        <?php endif; ?>
        <?php if ($global_totals['activities'] > 0): ?>
            <li data-ajtb-global-tab="activities">
                <b class="appendRight3"><?php echo $global_totals['activities']; ?></b> <?php echo $global_totals['activities'] > 1 ? esc_html__('Activities', 'ajinsafro-tour-bridge') : esc_html__('Activity', 'ajinsafro-tour-bridge'); ?>
            </li>
        <?php endif; ?>
        <?php if ($global_totals['meals'] > 0): ?>
            <li data-ajtb-global-tab="meals">
                <b class="appendRight3"><?php echo $global_totals['meals']; ?></b> <?php echo $global_totals['meals'] > 1 ? esc_html__('Meals', 'ajinsafro-tour-bridge') : esc_html__('Meal', 'ajinsafro-tour-bridge'); ?>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Day Plan Container: sidebar nav + details -->
    <div class="dayplan-container" id="sticky-itinerary-container">
        <!-- Day Plan Sidebar Navigation -->
        <div class="dayplan-nav" data-testid="dayplan-nav">
            <p class="pointer-list-title"><?php esc_html_e('Day Plan', 'ajinsafro-tour-bridge'); ?></p>
            <ul class="pointer-list aj-day-plan-nav">
                <?php foreach ($itinerary as $index => $day):
                    $day_num = $day['day'] ?? ($index + 1);
                    $day_mode = isset($day['mode']) ? $day['mode'] : 'program';
                    $day_date = isset($day['date']) ? $day['date'] : '';
                    if ($day_mode === 'free') {
                        $day_label = __('Jour libre', 'ajinsafro-tour-bridge');
                    } elseif (!empty($day_date) && is_string($day_date) && strtotime($day_date) !== false) {
                        $day_label = date_i18n('d M, D', strtotime($day_date));
                    } elseif (!empty($day['day_title'])) {
                        $day_label = strlen($day['day_title']) > 24 ? wp_trim_words($day['day_title'], 3) : $day['day_title'];
                    } else {
                        $day_label = 'Jour ' . $day_num;
                    }
                    $is_active = $index === 0;
                ?>
                    <li class="<?php echo $is_active ? 'active' : ''; ?> aj-day-nav-item" data-day-index="<?php echo $index; ?>" data-day="<?php echo $day_num; ?>" data-aj-nav-day="<?php echo $day_num; ?>" id="aj-day-nav-<?php echo $day_num; ?>">
                        <?php echo esc_html($day_label); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Day Plan Details -->
        <div class="dayplan-details aj-day-plan-content" data-testid="day-plan-details">
            <?php foreach ($itinerary as $index => $day):
                $day_number = $day['day'] ?? ($index + 1);
                $is_first = ($index === 0);
                $is_last = ($index === $total_days - 1);
                $day_flights = $ajtb_day_flights_list($day['flight'] ?? []);
                $day_flights_return = $ajtb_day_flights_list($day['flight_return'] ?? []);
                $day_title_display = !empty($day['day_title']) ? $day['day_title'] : ($day['title'] ?? 'Jour ' . $day_number);
                $day_date_raw = isset($day['date']) ? trim((string) $day['date']) : '';
                $day_date_display = $day_date_raw;
                if ($day_date_raw !== '' && strtotime($day_date_raw) !== false) {
                    $day_date_display = date_i18n('d/m/Y', strtotime($day_date_raw));
                }
                $mode = isset($day['mode']) ? $day['mode'] : 'program';
                $activities = isset($day['activities']) ? $day['activities'] : [];
                $day_id = (int) ($day['id'] ?? 0);
                $day_activity_ids = array_map(function ($a) { return (int) ($a['activity_id'] ?? 0); }, $activities);
                $day_inc = $ajtb_day_includes_raw($day, $index, $total_days);

                $day_transfer_list = isset($day['transfer']) && is_array($day['transfer']) ? $day['transfer'] : (!empty($day['transfer']) ? [$day['transfer']] : []);
                $day_transfer_return_list = isset($day['transfer_return']) && is_array($day['transfer_return']) ? $day['transfer_return'] : (!empty($day['transfer_return']) ? [$day['transfer_return']] : []);
                $day_hotels_list = isset($day['hotels']) && is_array($day['hotels']) ? $day['hotels'] : (!empty($day['hotel']) ? [$day['hotel']] : []);
                $day_flights_count = is_array($day_flights) ? count($day_flights) : 0;
                $day_flights_return_count = is_array($day_flights_return) ? count($day_flights_return) : 0;
                $has_day_flights = $day_flights_count > 0;
                $has_day_flights_return = $day_flights_return_count > 0;

                // Build INCLUDED items for header
                $included_items = [];
                if ($day_inc['flights'] > 0) $included_items[] = ['icon' => 'flight', 'text' => $day_inc['flights'] . ' ' . _n('Flight', 'Flights', $day_inc['flights'], 'ajinsafro-tour-bridge')];
                if ($day_inc['hotels'] > 0) $included_items[] = ['icon' => 'hotel', 'text' => $day_inc['hotels'] . ' ' . _n('Hotel', 'Hotels', $day_inc['hotels'], 'ajinsafro-tour-bridge')];
                if ($day_inc['transfers'] > 0) $included_items[] = ['icon' => 'transfer', 'text' => $day_inc['transfers'] . ' ' . _n('Transfer', 'Transfers', $day_inc['transfers'], 'ajinsafro-tour-bridge')];
                if ($day_inc['activities'] > 0) $included_items[] = ['icon' => 'activity', 'text' => $day_inc['activities'] . ' ' . _n('Activity', 'Activities', $day_inc['activities'], 'ajinsafro-tour-bridge')];
                if ($day_inc['meals'] > 0) $included_items[] = ['icon' => 'meal', 'text' => $day_inc['meals'] . ' ' . _n('Meal', 'Meals', $day_inc['meals'], 'ajinsafro-tour-bridge')];
            ?>
            <div class="dayplan-wrapper" data-testid="day-plan-wrapper" id="aj-day-panel-<?php echo $day_number; ?>" data-aj-day-panel="<?php echo $day_number; ?>" data-day="<?php echo $day_number; ?>" data-day-id="<?php echo $day_number; ?>" data-day-index="<?php echo $index; ?>" data-day-db-id="<?php echo $day_id; ?>" data-day-activity-ids="<?php echo esc_attr(implode(',', $day_activity_ids)); ?>">
                <div id="dayplan<?php echo $day_number; ?>" day="<?php echo $day_number; ?>"></div>
                <span id="aj-day-<?php echo $day_number; ?>" class="aj-day-anchor" aria-hidden="true"></span>

                <!-- Day Header -->
                <div class="dayplanheaderV2">
                    <div class="dayplanheaderV2-day font14 latoBold"><?php echo esc_html('Day ' . (int) $day_number); ?></div>
                    <div class="dayplanheaderV2-day-info latoBold"><?php echo esc_html($day_title_display); ?></div>
                    <?php if (!empty($included_items)): ?>
                        <div class="dayplanheaderV2-info">
                            <div class="dayplanheaderV2-info-heading latoBold appendLeft2"><?php esc_html_e('INCLUDED :', 'ajinsafro-tour-bridge'); ?></div>
                            <div class="dayplanheaderV2-info-content">
                                <?php foreach ($included_items as $item): ?>
                                    <div>
                                        <span class="ajtb-inclus-icon ajtb-inclus-icon--<?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                        <span><?php echo esc_html($item['text']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($mode === 'free'): ?>
                        <span class="badge badge-free-day"><?php esc_html_e('Jour libre', 'ajinsafro-tour-bridge'); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Day description / notes -->
                <?php
                $day_notes = trim((string) ($day['notes'] ?? ''));
                if ($day_notes === '' && isset($day['description'])) {
                    $day_notes = trim((string) $day['description']);
                }
                if ($day_notes === '' && isset($day['content'])) {
                    $day_notes = trim((string) $day['content']);
                }
                ?>
                <?php if ($day_notes !== ''): ?>
                    <?php
                    $notes_html = wp_kses_post(nl2br($day_notes));
                    $notes_plain = trim(wp_strip_all_tags($day_notes));
                    $notes_length = function_exists('mb_strlen') ? mb_strlen($notes_plain) : strlen($notes_plain);
                    $should_collapse = $notes_length > 320;
                    ?>
                    <p class="greyText font12 lineHeight18 dayplan-description<?php echo $should_collapse ? ' aj-day-notes-collapsed' : ''; ?>">
                        <?php echo $notes_html; ?>
                    </p>
                    <?php if ($should_collapse): ?>
                        <div class="dayplan-description-readMore"><span><?php esc_html_e('Read More', 'ajinsafro-tour-bridge'); ?></span></div>
                    <?php endif; ?>
                <?php elseif ($mode === 'free'): ?>
                    <p class="greyText font12 lineHeight18 dayplan-description"><?php esc_html_e('Jour libre', 'ajinsafro-tour-bridge'); ?></p>
                <?php endif; ?>

                <!-- Day image(s) -->
                <?php if (!empty($day['image'])): ?>
                    <ul class="dayplan-places-list">
                        <li>
                            <div class="dayPlanImage">
                                <div class="imageLoaderContainer"><img class="active" src="<?php echo esc_url($day['image']); ?>" alt="<?php echo esc_attr('Day ' . $day_number); ?>" loading="lazy"></div>
                            </div>
                        </li>
                    </ul>
                <?php endif; ?>

                <!-- Flights -->
                <?php if ($is_first && $has_day_flights): ?>
                    <div class="card-separator-commute commute-wrapper-v2" data-ajtb-tab="flights">
                        <div class="commute-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 28" fill="none" class="flight-icon-v2"><path d="M10.8974 21.625H12.4574L16.3574 15.4671L20.6474 15.4671C21.2948 15.4671 21.8174 14.9514 21.8174 14.3125C21.8174 13.6736 21.2948 13.1579 20.6474 13.1579H16.3574L12.4574 7H10.8974L12.8474 13.1579L8.55738 13.1579L7.38738 11.6184H6.21738L6.99738 14.3125L6.21738 17.0066L7.38738 17.0066L8.55738 15.4671H12.8474L10.8974 21.625Z" fill="#4a4a4a"></path></svg>
                            <div class="commute-header-left-bar">
                                <div class="flightListRow flexOne column">
                                    <div class="makeFlex">
                                        <ul class="flight-row firstFlightRow">
                                            <li class="highlight flight-row-container" data-aj-day-flight="outbound" data-aj-day-number="1">
                                                <?php $flight = $day_flights[0]; $show_remove = false; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($has_day_flights): ?>
                    <div class="card-separator-commute commute-wrapper-v2" data-ajtb-tab="flights">
                        <div class="commute-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 28" fill="none" class="flight-icon-v2"><path d="M10.8974 21.625H12.4574L16.3574 15.4671L20.6474 15.4671C21.2948 15.4671 21.8174 14.9514 21.8174 14.3125C21.8174 13.6736 21.2948 13.1579 20.6474 13.1579H16.3574L12.4574 7H10.8974L12.8474 13.1579L8.55738 13.1579L7.38738 11.6184H6.21738L6.99738 14.3125L6.21738 17.0066L7.38738 17.0066L8.55738 15.4671H12.8474L10.8974 21.625Z" fill="#4a4a4a"></path></svg>
                            <div class="commute-header-left-bar">
                                <div class="flightListRow flexOne column">
                                    <div class="makeFlex">
                                        <ul class="flight-row firstFlightRow">
                                            <?php foreach ($day_flights as $flight): ?>
                                                <li class="highlight flight-row-container" data-aj-day-flight="segment" data-aj-day-number="<?php echo (int) $day_number; ?>">
                                                    <?php $show_remove = false; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Transfers (arrival) -->
                <?php if (!empty($day_transfer_list)): ?>
                    <div class="card-separator" data-testid="day-plan-section-transfer" data-ajtb-tab="transfers">
                        <div class="flex-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 12" fill="none" class="car-icon-no-commute"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.8864 3.41705H17.3999C17.649 3.41705 17.8408 3.64145 17.8209 3.88505V4.43345C17.8209 4.69745 17.6303 4.90025 17.3812 4.90025H16.4606C16.6525 5.57225 16.7671 6.34505 16.7671 7.24025V8.80505V11.4511C16.7671 11.7151 16.5752 11.9191 16.3261 11.9191H14.6981C14.4489 11.9191 14.2571 11.7151 14.2571 11.4511V10.2307C12.7237 10.3723 11.0184 10.4539 9.21722 10.4539C7.41602 10.4539 5.70948 10.3723 4.17734 10.2307V11.4511C4.17734 11.7151 3.98551 11.9191 3.73638 11.9191H2.10708C1.85795 11.9191 1.66612 11.7151 1.66612 11.4511V7.24145C1.66612 6.34505 1.78072 5.57345 1.97255 4.90145H1.05326C0.804134 4.90145 0.612305 4.69745 0.612305 4.43345V3.88505C0.612305 3.62105 0.804134 3.41705 1.05326 3.41705H2.54804C2.59548 3.34642 2.63805 3.27078 2.68057 3.19524C2.72401 3.11807 2.76738 3.04101 2.81585 2.96945C2.89308 2.84705 3.92821 1.40345 4.36917 0.813055C4.77027 0.285055 5.28846 0.0810547 5.86394 0.0810547H12.5705C13.146 0.0810547 13.6816 0.304255 14.0653 0.813055C14.5249 1.40345 15.1191 2.21705 15.4056 2.64425C15.4454 2.69626 15.4803 2.75401 15.5152 2.81174C15.5479 2.86595 15.5806 2.92009 15.6173 2.96945C15.688 3.07376 15.7485 3.17872 15.8138 3.292C15.8372 3.33254 15.8612 3.37409 15.8864 3.41705Z" fill="#4a4a4a"></path></svg>
                            <?php foreach ($day_transfer_list as $day_transfer_item): ?>
                                <div class="highlight transfer-card-width transfer-row-v2" data-testid="transfer">
                                    <?php $transfer = $day_transfer_item; $label = __('Transfert Aéroport → Hôtel', 'ajinsafro-tour-bridge'); include AJTB_PLUGIN_DIR . 'templates/tour/partials/transfer-card.php'; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Hotels -->
                <?php if (!empty($day_hotels_list)): ?>
                    <div class="card-separator" data-testid="day-plan-section-hotel" data-ajtb-tab="hotels">
                        <?php foreach ($day_hotels_list as $day_hotel_item): ?>
                            <div class="hotel-card-v2-container hotel-icon-left-bar">
                                <div class="hotel-header-row-container">
                                    <div class="hotel-header-row-lhs">
                                        <svg width="20" height="20" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.6585 12.0283V0.727993H3.32865V12.0283H5.90747V9.71692H7.07967V12.0283H9.6585ZM7.78308 1.49808H8.95527V2.78222H7.78308V1.49808ZM7.07962 1.49808H5.90743V2.78222H7.07962V1.49808ZM4.0319 1.49808H5.2041V2.78222H4.0319V1.49808ZM8.95527 3.55305H7.78308V4.83718H8.95527V3.55305ZM5.90743 3.55305H7.07962V4.83718H5.90743V3.55305ZM5.2041 3.55305H4.0319V4.83718H5.2041V3.55305ZM3.09439 12.0279V4.06627H0.75V12.0279H3.09439ZM2.39088 4.8374H1.45312V5.86471H2.39088V4.8374ZM7.78308 5.60749H8.95527V6.89162H7.78308V5.60749ZM7.07962 5.60749H5.90743V6.89162H7.07962V5.60749ZM4.0319 5.60749H5.2041V6.89162H4.0319V5.60749ZM2.39088 6.63498H1.45312V7.66228H2.39088V6.63498ZM7.78308 7.66194H8.95527V8.94607H7.78308V7.66194ZM7.07962 7.66194H5.90743V8.94607H7.07962V7.66194ZM4.0319 7.66194H5.2041V8.94607H4.0319V7.66194ZM2.39088 8.43307H1.45312V9.46038H2.39088V8.43307ZM1.45312 10.2306H2.39088V11.258H1.45312V10.2306Z" fill="#4A4A4A"></path></svg>
                                        <div class="hotel-header-details">
                                            <span><span class="latoBold font14"><?php esc_html_e('HOTEL', 'ajinsafro-tour-bridge'); ?></span></span>
                                            <span class="mmt-chevron-up"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hotel-content-container">
                                    <?php $hotel = $day_hotel_item; $is_checkout = false; include AJTB_PLUGIN_DIR . 'templates/tour/partials/hotel-card.php'; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Transfers (return) -->
                <?php if (!empty($day_transfer_return_list)): ?>
                    <div class="card-separator" data-testid="day-plan-section-transfer" data-ajtb-tab="transfers">
                        <div class="flex-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 12" fill="none" class="car-icon-no-commute"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.8864 3.41705H17.3999C17.649 3.41705 17.8408 3.64145 17.8209 3.88505V4.43345C17.8209 4.69745 17.6303 4.90025 17.3812 4.90025H16.4606C16.6525 5.57225 16.7671 6.34505 16.7671 7.24025V8.80505V11.4511C16.7671 11.7151 16.5752 11.9191 16.3261 11.9191H14.6981C14.4489 11.9191 14.2571 11.7151 14.2571 11.4511V10.2307C12.7237 10.3723 11.0184 10.4539 9.21722 10.4539C7.41602 10.4539 5.70948 10.3723 4.17734 10.2307V11.4511C4.17734 11.7151 3.98551 11.9191 3.73638 11.9191H2.10708C1.85795 11.9191 1.66612 11.7151 1.66612 11.4511V7.24145C1.66612 6.34505 1.78072 5.57345 1.97255 4.90145H1.05326C0.804134 4.90145 0.612305 4.69745 0.612305 4.43345V3.88505C0.612305 3.62105 0.804134 3.41705 1.05326 3.41705H2.54804C2.59548 3.34642 2.63805 3.27078 2.68057 3.19524C2.72401 3.11807 2.76738 3.04101 2.81585 2.96945C2.89308 2.84705 3.92821 1.40345 4.36917 0.813055C4.77027 0.285055 5.28846 0.0810547 5.86394 0.0810547H12.5705C13.146 0.0810547 13.6816 0.304255 14.0653 0.813055C14.5249 1.40345 15.1191 2.21705 15.4056 2.64425C15.4454 2.69626 15.4803 2.75401 15.5152 2.81174C15.5479 2.86595 15.5806 2.92009 15.6173 2.96945C15.688 3.07376 15.7485 3.17872 15.8138 3.292C15.8372 3.33254 15.8612 3.37409 15.8864 3.41705Z" fill="#4a4a4a"></path></svg>
                            <?php foreach ($day_transfer_return_list as $day_transfer_ret): ?>
                                <div class="highlight transfer-card-width transfer-row-v2" data-testid="transfer">
                                    <?php $transfer = $day_transfer_ret; $label = __('Transfert Hôtel → Aéroport', 'ajinsafro-tour-bridge'); include AJTB_PLUGIN_DIR . 'templates/tour/partials/transfer-card.php'; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Last day: hotel checkout + return flight -->
                <?php if ($is_last):
                    $hotel_checkout = !empty($day['hotel_checkout']);
                    $show_inbound = $has_day_flights_return;
                ?>
                    <?php if (!empty($day_hotels_list) && $hotel_checkout): ?>
                        <div class="card-separator" data-testid="day-plan-section-hotel" data-ajtb-tab="hotels">
                            <?php foreach ($day_hotels_list as $day_hotel_last): ?>
                                <div class="hotel-card-v2-container hotel-icon-left-bar">
                                    <div class="hotel-header-row-container">
                                        <div class="hotel-header-row-lhs">
                                            <svg width="20" height="20" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.6585 12.0283V0.727993H3.32865V12.0283H5.90747V9.71692H7.07967V12.0283H9.6585ZM7.78308 1.49808H8.95527V2.78222H7.78308V1.49808ZM7.07962 1.49808H5.90743V2.78222H7.07962V1.49808ZM4.0319 1.49808H5.2041V2.78222H4.0319V1.49808ZM8.95527 3.55305H7.78308V4.83718H8.95527V3.55305ZM5.90743 3.55305H7.07962V4.83718H5.90743V3.55305ZM5.2041 3.55305H4.0319V4.83718H5.2041V3.55305ZM3.09439 12.0279V4.06627H0.75V12.0279H3.09439ZM2.39088 4.8374H1.45312V5.86471H2.39088V4.8374ZM7.78308 5.60749H8.95527V6.89162H7.78308V5.60749ZM7.07962 5.60749H5.90743V6.89162H7.07962V5.60749ZM4.0319 5.60749H5.2041V6.89162H4.0319V5.60749ZM2.39088 6.63498H1.45312V7.66228H2.39088V6.63498ZM7.78308 7.66194H8.95527V8.94607H7.78308V7.66194ZM7.07962 7.66194H5.90743V8.94607H7.07962V7.66194ZM4.0319 7.66194H5.2041V8.94607H4.0319V7.66194ZM2.39088 8.43307H1.45312V9.46038H2.39088V8.43307ZM1.45312 10.2306H2.39088V11.258H1.45312V10.2306Z" fill="#4A4A4A"></path></svg>
                                            <div class="hotel-header-details">
                                                <span><span class="latoBold font14"><?php esc_html_e('HOTEL (check-out)', 'ajinsafro-tour-bridge'); ?></span></span>
                                                <span class="mmt-chevron-up"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hotel-content-container">
                                        <?php $hotel = $day_hotel_last; $is_checkout = true; include AJTB_PLUGIN_DIR . 'templates/tour/partials/hotel-card.php'; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="card-separator-commute commute-wrapper-v2" data-ajtb-tab="flights">
                        <div class="commute-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 28" fill="none" class="flight-icon-v2"><path d="M10.8974 21.625H12.4574L16.3574 15.4671L20.6474 15.4671C21.2948 15.4671 21.8174 14.9514 21.8174 14.3125C21.8174 13.6736 21.2948 13.1579 20.6474 13.1579H16.3574L12.4574 7H10.8974L12.8474 13.1579L8.55738 13.1579L7.38738 11.6184H6.21738L6.99738 14.3125L6.21738 17.0066L7.38738 17.0066L8.55738 15.4671H12.8474L10.8974 21.625Z" fill="#4a4a4a"></path></svg>
                            <div class="commute-header-left-bar">
                                <div class="flightListRow flexOne column">
                                    <div class="makeFlex">
                                        <ul class="flight-row firstFlightRow">
                                            <li class="highlight flight-row-container" data-aj-day-flight="inbound" data-aj-day-number="<?php echo $total_days; ?>">
                                                <?php if ($show_inbound): ?>
                                                    <?php foreach ($day_flights_return as $flight): $show_remove = true; include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card.php'; endforeach; ?>
                                                <?php else: ?>
                                                    <?php $label = __('Vol Retour non disponible', 'ajinsafro-tour-bridge'); include AJTB_PLUGIN_DIR . 'templates/tour/partials/flight-card-unavailable.php'; ?>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Meals -->
                <?php if (!empty(trim((string) ($day['meals'] ?? '')))): ?>
                    <div class="card-separator" data-ajtb-tab="meals">
                        <div class="day-package-wise-meal-wrapper">
                            <svg width="21" height="20" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.358 2.889h4.849v15h-4.849v-15z" fill="#fff"></path><g><path fill-rule="evenodd" clip-rule="evenodd" d="M16.207 7.057c0-1.974-.894-4.168-2.424-4.168-1.53 0-2.425 2.194-2.425 4.168 0 1.579.697 2.542 1.652 2.858l-.485 6.553c-.06.773.53 1.42 1.273 1.42.742 0 1.318-.663 1.272-1.42l-.514-6.553c.954-.332 1.65-1.295 1.65-2.858z" fill="#4A4A4A"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M5 3.001h4.97v14.872H5V3z" fill="#fff"></path><g><path fill-rule="evenodd" clip-rule="evenodd" d="M9.37 3.018h-.05a.462.462 0 0 0-.467.467l.116 3.549c.017.258-.2.467-.466.467-.25 0-.467-.193-.467-.435l-.033-3.613c0-.242-.217-.435-.467-.435h-.067c-.25 0-.466.193-.466.435l-.05 3.597c0 .242-.217.435-.467.435a.462.462 0 0 1-.467-.467l.117-3.549c.017-.258-.2-.468-.467-.468h-.05c-.25 0-.45.194-.466.436l-.15 3.677c-.05 1.081.65 2 1.65 2.355l-.567 6.952c-.067.79.583 1.452 1.4 1.452s1.45-.678 1.4-1.452l-.567-6.952c.984-.338 1.683-1.274 1.65-2.355l-.133-3.66a.467.467 0 0 0-.467-.436z" fill="#4A4A4A"></path></g></svg>
                            <div class="meal-container">
                                <div class="meal-header">
                                    <span class="latoBold"><?php esc_html_e('MEAL', 'ajinsafro-tour-bridge'); ?></span>
                                    <span class="header-span"></span>
                                    <span><?php echo esc_html($day['meals']); ?></span>
                                    <span class="mmt-chevron-down"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Activities -->
                <?php
                $included_count = 0;
                if (!empty($activities)) {
                    foreach ($activities as $a) { if (!empty($a['is_included'])) $included_count++; }
                }
                ?>
                <div id="aj-day-activities-<?php echo $day_id; ?>" class="dayplan-section" data-ajtb-tab="activities">
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $act):
                            if (empty($act['is_included'])) { continue; }
                            $act_title = isset($act['title']) && (string) $act['title'] !== '' ? $act['title'] : '';
                            $act_desc = isset($act['description']) && (string) $act['description'] !== '' ? $act['description'] : '';
                            $act_id = (int) ($act['activity_id'] ?? 0);
                            $is_mandatory = !empty($act['is_mandatory']);
                            $show_act_remove = $can_toggle_activities && !$is_mandatory;
                            $act_price = null;
                            if (isset($act['custom_price']) && $act['custom_price'] !== null) {
                                $act_price = (float) $act['custom_price'];
                            } elseif (isset($act['base_price']) && $act['base_price'] !== null) {
                                $act_price = (float) $act['base_price'];
                            }
                            $act_image_url = isset($act['image_url']) ? $act['image_url'] : null;
                            if (empty($act_image_url) && !empty($act['activity_image_id']) && function_exists('ajtb_get_attachment_image_url')) {
                                $act_image_url = ajtb_get_attachment_image_url((int) $act['activity_image_id'], 'medium');
                            }
                            $act_start_time = $act['start_time'] ?? null;
                            $act_end_time = $act['end_time'] ?? null;
                            $day_activity_id = (int) ($act['id'] ?? 0);
                        ?>
                            <div class="activity-row" data-activity-id="<?php echo $act_id; ?>" data-day-activity-id="<?php echo $day_activity_id; ?>" data-is-mandatory="<?php echo $is_mandatory ? '1' : '0'; ?>">
                                <?php if ($act_image_url): ?>
                                    <div class="activity-row-image">
                                        <div class="imageLoaderContainer"><img class="active" src="<?php echo esc_url($act_image_url); ?>" alt="<?php echo esc_attr($act_title !== '' ? $act_title : __('Activité', 'ajinsafro-tour-bridge')); ?>" loading="lazy"></div>
                                    </div>
                                <?php endif; ?>
                                <div class="activity-row-details">
                                    <div class="font18 latoBlack blackText appendBottom6">
                                        <?php echo $act_title !== '' ? esc_html($act_title) : esc_html__('Activité', 'ajinsafro-tour-bridge'); ?>
                                        <?php if ($is_mandatory): ?>
                                            <span class="badge badge-mandatory font10">Obligatoire</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($act_start_time || $act_end_time): ?>
                                        <div class="font12 greyText appendBottom3">
                                            <?php if ($act_start_time): ?><span><?php echo esc_html($act_start_time); ?></span><?php endif; ?>
                                            <?php if ($act_start_time && $act_end_time): ?><span> – </span><?php endif; ?>
                                            <?php if ($act_end_time): ?><span><?php echo esc_html($act_end_time); ?></span><?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($act_desc !== ''): ?>
                                        <p class="activity-row-text-desc font12"><?php echo wp_kses_post($act_desc); ?></p>
                                    <?php endif; ?>
                                    <?php if ($act_price !== null): ?>
                                        <span class="font12 latoBold"><?php echo number_format($act_price, 0, ',', ' '); ?> DH</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($show_act_remove): ?>
                                    <div class="activity-row-actions">
                                        <button type="button" class="ajtb-btn-edit-activity linkText font12" data-day-activity-id="<?php echo $day_activity_id; ?>" data-tour-id="<?php echo $tour_id; ?>" data-day-id="<?php echo $day_id; ?>" data-activity-id="<?php echo $act_id; ?>"><?php esc_html_e('Modifier', 'ajinsafro-tour-bridge'); ?></button>
                                        <button type="button" class="ajtb-btn-remove-activity linkText font12" data-aj-action="remove" data-tour-id="<?php echo $tour_id; ?>" data-day-id="<?php echo $day_id; ?>" data-activity-id="<?php echo $act_id; ?>"><?php esc_html_e('Retirer', 'ajinsafro-tour-bridge'); ?></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($included_count === 0 && !$can_toggle_activities): ?>
                        <div class="font12 greyText padding10"><?php esc_html_e('Aucune activité', 'ajinsafro-tour-bridge'); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Add Activity CTA -->
                <?php if ($can_toggle_activities && $day_id > 0): ?>
                    <div class="add-activity-card-conatiner">
                        <div class="makeFlex center">
                            <span class="add-activity-content">
                                <p class="add-more-text"><?php esc_html_e('Add Activities to your day', 'ajinsafro-tour-bridge'); ?></p>
                                <p class="time-spend-text"><?php esc_html_e('Spend the day at leisure or add an activity, transfer or meal', 'ajinsafro-tour-bridge'); ?></p>
                            </span>
                        </div>
                        <span class="add-button ajtb-btn-open-activity-modal" data-tour-id="<?php echo $tour_id; ?>" data-day-id="<?php echo $day_id; ?>"><?php esc_html_e('ADD TO DAY', 'ajinsafro-tour-bridge'); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Day Details (meals, accommodation from Laravel) -->
                <?php if ($source === 'laravel'): ?>
                    <?php if (!empty($day['accommodation'])): ?>
                        <div class="font12 greyText padding10">
                            <span class="latoBold"><?php esc_html_e('Hébergement:', 'ajinsafro-tour-bridge'); ?></span>
                            <?php echo esc_html($day['accommodation']); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Activity Modals -->
    <?php if ($can_toggle_activities): ?>
        <?php ajtb_get_partial('activity-modal', ['tour_id' => $tour_id]); ?>
        <?php ajtb_get_partial('activity-edit-modal'); ?>
    <?php endif; ?>
</section>
