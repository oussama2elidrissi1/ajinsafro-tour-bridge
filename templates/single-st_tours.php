<?php
/**
 * Single Tour Template
 *
 * Override template for single st_tours post type
 * New design layout with two-column structure
 *
 * @package AjinsafroTourBridge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get tour data
$tour = AJTB_Template_Loader::get_tour_data();

// Fallback if no data
if (empty($tour)) {
    AJTB_Template_Loader::reset_loading();
    get_template_part('404');
    return;
}

get_header();

// Pre-compute summary data for Summary tab
$has_itinerary_tab = !empty($tour['itinerary']) || !empty($tour['wp_program']['items']);
$summary_days = [];
if (!empty($tour['itinerary']) && is_array($tour['itinerary'])) {
    $summary_list = function ($value) {
        if (empty($value)) {
            return [];
        }
        return (is_array($value) && isset($value[0]) && is_array($value[0])) ? $value : [$value];
    };
    $summary_pick = function ($row, $keys, $fallback = '') {
        if (!is_array($row)) {
            return $fallback;
        }
        foreach ($keys as $key) {
            if (!empty($row[$key])) {
                return trim((string) $row[$key]);
            }
        }
        return $fallback;
    };
    $summary_transfer = function ($transfer, $fallback) use ($summary_pick) {
        $name = $summary_pick($transfer, ['name', 'title', 'transfer_name', 'service_name', 'service_title', 'transfer_title']);
        $from = $summary_pick($transfer, ['from_label', 'pickup_location']);
        $to = $summary_pick($transfer, ['to_label', 'dropoff_location']);
        if ($name !== '') {
            return $name;
        }
        if ($from !== '' || $to !== '') {
            return trim($from . ($from !== '' || $to !== '' ? ' → ' : '') . $to, " \t\n\r\0\x0B→");
        }
        return $fallback;
    };
    $summary_hotel = function ($hotel, $checkout = false) use ($summary_pick) {
        $name = $summary_pick($hotel, ['hotel_name', 'name'], __('Hôtel', 'ajinsafro-tour-bridge'));
        return $checkout
            ? sprintf(__('Check-out from %s', 'ajinsafro-tour-bridge'), $name)
            : sprintf(__('Check-in to %s', 'ajinsafro-tour-bridge'), $name);
    };
    $summary_flight = function ($flight, $fallback) use ($summary_pick) {
        $from = $summary_pick($flight, ['from_city', 'depart_label', 'departure_city']);
        $to = $summary_pick($flight, ['to_city', 'arrive_label', 'arrival_city']);
        if ($from !== '' || $to !== '') {
            return trim(sprintf(__('Flight %s → %s', 'ajinsafro-tour-bridge'), $from !== '' ? $from : '—', $to !== '' ? $to : '—'));
        }
        return $fallback;
    };
    foreach ($tour['itinerary'] as $index => $day) {
        $entries = [];
        $day_number = (int) ($day['day'] ?? ($index + 1));
        $day_date_raw = trim((string) ($day['date'] ?? ''));
        $day_date = ($day_date_raw !== '' && strtotime($day_date_raw) !== false)
            ? date_i18n('M j, D', strtotime($day_date_raw))
            : '';
        $is_first = $index === 0;
        $is_last = $index === (count($tour['itinerary']) - 1);
        $day_flights = $summary_list($day['flight'] ?? []);
        $day_flights_return = $summary_list($day['flight_return'] ?? []);
        $day_transfer_list = $summary_list($day['transfer'] ?? []);
        $day_transfer_return_list = $summary_list($day['transfer_return'] ?? []);
        $day_hotels_list = isset($day['hotels']) && is_array($day['hotels']) ? $day['hotels'] : (!empty($day['hotel']) ? [$day['hotel']] : []);
        $activities = isset($day['activities']) && is_array($day['activities']) ? $day['activities'] : [];

        if ($is_first && !empty($day_flights)) {
            foreach ($day_flights as $flight) {
                $entries[] = ['type' => 'flight', 'text' => $summary_flight($flight, __('Outbound flight', 'ajinsafro-tour-bridge'))];
            }
        } elseif (!$is_first && !empty($day_flights)) {
            foreach ($day_flights as $flight) {
                $entries[] = ['type' => 'flight', 'text' => $summary_flight($flight, __('Flight', 'ajinsafro-tour-bridge'))];
            }
        }

        foreach ($day_transfer_list as $transfer) {
            $entries[] = ['type' => 'transfer', 'text' => $summary_transfer($transfer, __('Airport transfer', 'ajinsafro-tour-bridge'))];
        }

        foreach ($day_hotels_list as $hotel) {
            $entries[] = ['type' => 'hotel', 'text' => $summary_hotel($hotel, false)];
        }

        foreach ($activities as $activity) {
            if (empty($activity['is_included'])) {
                continue;
            }
            $title = !empty($activity['title']) ? trim((string) $activity['title']) : __('Activité', 'ajinsafro-tour-bridge');
            $entries[] = ['type' => 'activity', 'text' => $title];
        }

        if (!empty(trim((string) ($day['meals'] ?? '')))) {
            $entries[] = ['type' => 'meal', 'text' => trim((string) $day['meals'])];
        }

        if ($is_last && !empty($day['hotel_checkout'])) {
            foreach ($day_hotels_list as $hotel) {
                $entries[] = ['type' => 'hotel', 'text' => $summary_hotel($hotel, true)];
            }
        }

        foreach ($day_transfer_return_list as $transfer) {
            $entries[] = ['type' => 'transfer', 'text' => $summary_transfer($transfer, __('Return transfer', 'ajinsafro-tour-bridge'))];
        }

        if ($is_last && !empty($day_flights_return)) {
            foreach ($day_flights_return as $flight) {
                $entries[] = ['type' => 'flight', 'text' => $summary_flight($flight, __('Return flight', 'ajinsafro-tour-bridge'))];
            }
        }

        $summary_days[] = [
            'day_number' => $day_number,
            'day_date' => $day_date,
            'entries' => $entries,
        ];
    }
}
$has_summary_tab = !empty($summary_days);
$has_flights_section = (empty($tour['outboundFlight']) && empty($tour['inboundFlight'])) && (!empty($tour['flights']) || !empty($tour['all_flights']));
?>

<div class="theme-wrapper ajtb-tour-page">
    <!-- Search Bar -->
    <div class="listing-modifysearch_container">
        <div class="listing-modifysearch_inner">
            <?php ajtb_get_partial('searchbar', ['tour' => $tour, 'in_top_bar' => true]); ?>
        </div>
    </div>
    <div class="warning-message-container"></div>

    <!-- Top Section: Hero + Tabs -->
    <div class="topSection newTopSection appendBottom20">
        <div class="pageContainer">
            <!-- Hero (title + gallery) -->
            <?php ajtb_get_partial('hero', ['tour' => $tour]); ?>

            <!-- Main Tabs -->
            <div id="tabItem" class="mainTab">
                <div class="mainTabItem <?php echo $has_itinerary_tab ? 'active' : ''; ?>" data-tab="itinerary"><?php esc_html_e('ITINERARY', 'ajinsafro-tour-bridge'); ?></div>
                <?php if (!empty($tour['inclusions']) || !empty($tour['exclusions']) || !empty($tour['cancellation_policy'])): ?>
                    <div class="mainTabItem" data-tab="policies"><?php esc_html_e('POLICIES', 'ajinsafro-tour-bridge'); ?></div>
                <?php endif; ?>
                <?php if ($has_summary_tab): ?>
                    <div class="mainTabItem" data-tab="summary"><?php esc_html_e('SUMMARY', 'ajinsafro-tour-bridge'); ?></div>
                <?php endif; ?>
                <div class="flexOne">
                    <ul class="actionIcons makeFlex right appendTop15 appendRight15">
                        <li class="makeFlex perfectCenter cursorPointer" id="share-tour" data-url="<?php echo esc_url($tour['permalink'] ?? get_permalink()); ?>">
                            <span class="holidaySprite icon24 share appendRight3"></span><?php esc_html_e('Share', 'ajinsafro-tour-bridge'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Two-column layout -->
    <div class="pageContainer makeFlex spaceBetween">
        <!-- Left Column -->
        <div class="leftContainer">
            <div class="left-section">

                <!-- Overview / Inclusions intro -->
                <?php ajtb_get_partial('overview', ['tour' => $tour]); ?>

                <!-- Destinations Section -->
                <?php if (!empty($tour['locations'])): ?>
                    <?php ajtb_get_partial('destinations', ['tour' => $tour]); ?>
                <?php endif; ?>

                <!-- Categories Section -->
                <?php if (!empty($tour['categories']) || !empty($tour['tour_types'])): ?>
                    <section class="ajtb-section ajtb-tab-panel padding20" id="categories">
                        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Catégories du Voyage', 'ajinsafro-tour-bridge'); ?></h2>
                        <?php if (!empty($tour['categories'])): ?>
                            <div class="ajtb-tags appendBottom10">
                                <?php foreach ($tour['categories'] as $cat): ?>
                                    <a href="<?php echo esc_url($cat['link']); ?>" class="packageTypeTagV2">
                                        <span class="font11 widthMaxContent"><?php echo esc_html($cat['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($tour['tour_types'])): ?>
                            <div class="appendTop10">
                                <h3 class="font14 latoBold appendBottom10"><?php esc_html_e('Types de voyage', 'ajinsafro-tour-bridge'); ?></h3>
                                <div class="ajtb-tags">
                                    <?php foreach ($tour['tour_types'] as $type): ?>
                                        <a href="<?php echo esc_url($type['link']); ?>" class="packageTypeTagV2">
                                            <span class="font11 widthMaxContent"><?php echo esc_html($type['name']); ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <!-- Summary Tab Content -->
                <?php if ($has_summary_tab): ?>
                    <section class="ajtb-section ajtb-tab-panel padding20" id="summary" style="display:none;">
                        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Summary', 'ajinsafro-tour-bridge'); ?></h2>
                        <div class="ajtb-summary-table">
                            <?php foreach ($summary_days as $summary_day): ?>
                                <?php
                                $entries = !empty($summary_day['entries'])
                                    ? $summary_day['entries']
                                    : [['type' => 'empty', 'text' => __('Programme non disponible pour ce jour.', 'ajinsafro-tour-bridge')]];
                                ?>
                                <div class="ajtb-summary-day appendBottom10">
                                    <div class="font14 latoBold"><?php echo esc_html('Day ' . (int) $summary_day['day_number']); ?></div>
                                    <?php if (!empty($summary_day['day_date'])): ?>
                                        <div class="font12 greyText"><?php echo esc_html($summary_day['day_date']); ?></div>
                                    <?php endif; ?>
                                    <div class="appendTop5">
                                        <?php foreach ($entries as $entry): ?>
                                            <div class="makeFlex appendBottom3">
                                                <span class="ajtb-summary-cell__icon ajtb-summary-cell__icon--<?php echo esc_attr($entry['type']); ?>" aria-hidden="true"></span>
                                                <span class="font12"><?php echo esc_html($entry['text']); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Flights Section (standalone, when not in itinerary) -->
                <?php if (empty($tour['outboundFlight']) && empty($tour['inboundFlight'])): ?>
                    <?php ajtb_get_partial('flights', ['tour' => $tour]); ?>
                <?php endif; ?>

                <!-- Itinerary -->
                <?php if (!empty($tour['itinerary']) || !empty($tour['wp_program']['items'])): ?>
                    <?php ajtb_get_partial('itinerary', ['tour' => $tour]); ?>
                <?php endif; ?>

                <!-- Inclusions/Exclusions -->
                <?php if (!empty($tour['inclusions']) || !empty($tour['exclusions'])): ?>
                    <?php ajtb_get_partial('include-exclude', ['tour' => $tour]); ?>
                <?php endif; ?>

                <!-- FAQ -->
                <?php if (!empty($tour['faqs'])): ?>
                    <?php ajtb_get_partial('faq', ['tour' => $tour]); ?>
                <?php endif; ?>

                <!-- Gallery Section -->
                <?php
                $hero_gallery = !empty($tour['hero_gallery']) && is_array($tour['hero_gallery']) ? $tour['hero_gallery'] : [];
                $gallery = $tour['gallery'] ?? [];
                $gallery_ids_seen = [];
                $section_gallery = [];
                foreach ($hero_gallery as $img) {
                    $section_gallery[] = $img;
                    if (isset($img['url'])) $gallery_ids_seen[] = rtrim($img['url'], '/');
                }
                foreach ($gallery as $img) {
                    $u = isset($img['url']) ? rtrim($img['url'], '/') : '';
                    if ($u && !in_array($u, $gallery_ids_seen, true)) {
                        $section_gallery[] = $img;
                        $gallery_ids_seen[] = $u;
                    }
                }
                ?>
                <?php if (!empty($section_gallery)): ?>
                    <section class="ajtb-section padding20" id="gallery">
                        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Galerie Photos', 'ajinsafro-tour-bridge'); ?></h2>
                        <div class="ajtb-gallery-grid makeFlex" style="flex-wrap:wrap;gap:10px;">
                            <?php foreach ($section_gallery as $image): ?>
                                <a href="<?php echo esc_url($image['url'] ?? '#'); ?>" class="gallery-item" data-lightbox="tour-gallery">
                                    <img src="<?php echo esc_url($image['medium'] ?? $image['thumbnail'] ?? $image['url'] ?? ''); ?>"
                                         alt="<?php echo esc_attr($image['alt'] ?? ''); ?>"
                                         loading="lazy"
                                         style="width:120px;height:90px;object-fit:cover;border-radius:8px;">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Video -->
                <?php if (!empty($tour['video'])): ?>
                    <section class="ajtb-section padding20" id="video">
                        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Vidéo', 'ajinsafro-tour-bridge'); ?></h2>
                        <div class="ajtb-video-container">
                            <?php echo wp_oembed_get($tour['video']); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Cancellation Policy -->
                <?php if (!empty($tour['cancellation_policy'])): ?>
                    <section class="ajtb-section padding20" id="policy" style="display:none;">
                        <h2 class="font16 latoBold appendBottom15"><?php esc_html_e("Politique d'Annulation", 'ajinsafro-tour-bridge'); ?></h2>
                        <div class="font14 lineHeight18">
                            <?php echo wp_kses_post($tour['cancellation_policy']); ?>
                        </div>
                    </section>
                <?php endif; ?>

            </div>
        </div>

        <!-- Right Column: Booking Box -->
        <div class="rightContainer">
            <?php ajtb_get_partial('booking-box', ['tour' => $tour]); ?>
        </div>
    </div>
</div>

<?php
AJTB_Template_Loader::reset_loading();
get_footer();
?>
