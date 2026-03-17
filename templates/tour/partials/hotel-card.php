<?php
/**
 * Hotel Card partial – New design: hotel-card-content with image gallery, rating, details.
 *
 * @var array $hotel Hotel row (hotel_name, stars, address, room_type, meal_plan, notes, image_url, etc.)
 * @var bool  $is_checkout Optional; true = last day (check-out).
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

if (empty($hotel) || !is_array($hotel)) {
    return;
}

$name = isset($hotel['hotel_name']) ? trim((string) $hotel['hotel_name']) : '';
if ($name === '') {
    $name = isset($hotel['name']) ? trim((string) $hotel['name']) : '';
}
if ($name === '') {
    $name = __('Hotel', 'ajinsafro-tour-bridge');
}

$stars = isset($hotel['stars']) ? (int) $hotel['stars'] : 0;
$address = isset($hotel['address']) ? trim((string) $hotel['address']) : '';
$city = '';
foreach (['city', 'hotel_city', 'location'] as $key) {
    if (!empty($hotel[$key])) {
        $city = trim((string) $hotel[$key]);
        break;
    }
}

$room_type = isset($hotel['room_type']) ? trim((string) $hotel['room_type']) : '';
if ($room_type === '') {
    $room_type = __('Standard room with 2 Single beds', 'ajinsafro-tour-bridge');
}
$rooms = isset($hotel['rooms']) ? (int) $hotel['rooms'] : (isset($hotel['room_count']) ? (int) $hotel['room_count'] : 1);
$meal_plan = isset($hotel['meal_plan']) ? trim((string) $hotel['meal_plan']) : '';
$breakfast_included = !empty($hotel['breakfast_included']) || stripos($meal_plan, 'breakfast') !== false || stripos($meal_plan, 'petit') !== false || stripos($meal_plan, 'bb') !== false;

$checkin = '';
$checkout = '';
foreach (['checkin_date_formatted', 'checkin_date', 'arrival_date'] as $key) {
    if (!empty($hotel[$key])) {
        $checkin = trim((string) $hotel[$key]);
        break;
    }
}
foreach (['checkout_date_formatted', 'checkout_date', 'departure_date'] as $key) {
    if (!empty($hotel[$key])) {
        $checkout = trim((string) $hotel[$key]);
        break;
    }
}

$nights = 0;
foreach (['nights', 'nights_count', 'duration_nights'] as $key) {
    if (!empty($hotel[$key])) {
        $nights = (int) $hotel[$key];
        break;
    }
}

$adults = 0;
foreach (['adults', 'pax_adults', 'guests_adults'] as $key) {
    if (!empty($hotel[$key])) {
        $adults = (int) $hotel[$key];
        break;
    }
}
if ($adults === 0) {
    $adults = 2;
}

$rating = isset($hotel['rating']) ? (float) $hotel['rating'] : 4.9;
$rating_count = isset($hotel['rating_count']) ? (int) $hotel['rating_count'] : 7;
$rating_label = __('Excellent', 'ajinsafro-tour-bridge');
$location_line = $city !== '' ? $city : ($address !== '' ? $address : '');
$date_line = '';
if ($checkin !== '' || $checkout !== '') {
    $date_line = $checkin !== '' ? $checkin : '—';
    if ($checkout !== '') {
        $date_line .= ' - ' . $checkout;
    }
}
if ($nights > 0) {
    $date_line .= ($date_line !== '' ? ', ' : '') . $nights . ' ' . _n(__('Night', 'ajinsafro-tour-bridge'), __('Nights', 'ajinsafro-tour-bridge'), $nights);
}

// Hotel images
$main_image = '';
$gallery_images = [];
if (!empty($hotel['image_url'])) {
    $main_image = $hotel['image_url'];
}
if (!empty($hotel['images']) && is_array($hotel['images'])) {
    foreach ($hotel['images'] as $img) {
        $url = is_array($img) ? ($img['url'] ?? ($img['image_url'] ?? '')) : (string) $img;
        if ($url !== '') {
            if ($main_image === '') {
                $main_image = $url;
            } else {
                $gallery_images[] = $url;
            }
        }
    }
}
$gallery_images = array_slice($gallery_images, 0, 4);
$gallery_extra = max(0, count($gallery_images) - 3);

// Star rating class
$star_class = '';
if ($stars === 1) $star_class = 'ratingOne';
elseif ($stars === 2) $star_class = 'ratingTwo';
elseif ($stars === 3) $star_class = 'ratingThree';
elseif ($stars === 4) $star_class = 'ratingFour';
elseif ($stars >= 5) $star_class = 'ratingFive';
?>
<div class="hotel-card-content" data-hotel-id="<?php echo esc_attr((int) ($hotel['id'] ?? 0)); ?>">
    <!-- Image section -->
    <div class="hotel-card-content__img-container">
        <?php if ($main_image !== ''): ?>
            <div class="image-carousel-slide">
                <div class="imageLoaderContainer" style="width: 243px; height: 200px;">
                    <img class="main-img-border vrtTop active" width="243" height="200" src="<?php echo esc_url($main_image); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($gallery_images)): ?>
            <div class="hotel-card-content__image-gallery">
                <?php foreach ($gallery_images as $gi => $gimg): ?>
                    <div class="hotel-card-content__gallery-thumbnail">
                        <div class="imageLoaderContainer" style="width: 55px; height: 55px;">
                            <img class="active" width="55" height="55" src="<?php echo esc_url($gimg); ?>" alt="<?php echo esc_attr($name . ' view ' . ($gi + 1)); ?>" loading="lazy">
                        </div>
                        <?php if ($gi === count($gallery_images) - 1 && $gallery_extra > 0): ?>
                            <div class="hotel-card-content__view-all-overlay">
                                <span class="hotel-card-content__view-all-count"><?php echo $gallery_extra; ?>+</span>
                                <span class="hotel-card-content__view-all-text"><?php esc_html_e('View All', 'ajinsafro-tour-bridge'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Details section -->
    <div class="hotel-card-content__details">
        <div class="hotel-card-content__details-upper-section">
            <div class="hotel-card-content__mmt-rating-info-container">
                <div class="hotel-card-content__mmt-rating-score"><?php echo esc_html(number_format($rating, 1)); ?></div>
                <span class="hotel-card-content__mmt-rating-label"><?php echo esc_html($rating_label); ?></span>
                <span class="hotel-card-content__mmt-rating-count">(<?php echo (int) $rating_count; ?> <?php echo esc_html(_n(__('Rating', 'ajinsafro-tour-bridge'), __('Ratings', 'ajinsafro-tour-bridge'), $rating_count)); ?>)</span>
            </div>

            <p class="hotel-card-content__title">
                <?php echo esc_html($name); ?>
                <span class="hotel-card-content__or-similar">(<?php esc_html_e('Or Similar', 'ajinsafro-tour-bridge'); ?>)</span>
                <?php if ($stars > 0): ?>
                    <span class="holidaySprite rating_blank"><span class="holidaySprite rating_fill <?php echo esc_attr($star_class); ?>"></span></span>
                <?php endif; ?>
            </p>

            <?php if ($location_line !== ''): ?>
                <p class="hotel-card-content__location"><?php echo esc_html($location_line); ?></p>
            <?php endif; ?>

            <div class="hotel-card-content__additional-info">
                <p class="hotel-card-content__additional-info-text">
                    <?php echo (int) $rooms; ?> <?php echo esc_html(_n(__('Room', 'ajinsafro-tour-bridge'), __('Rooms', 'ajinsafro-tour-bridge'), $rooms)); ?> | <?php echo (int) $adults; ?> <?php echo esc_html(_n(__('Adult', 'ajinsafro-tour-bridge'), __('Adults', 'ajinsafro-tour-bridge'), $adults)); ?>
                </p>
            </div>

            <?php if ($date_line !== ''): ?>
                <div class="hotel-card-content__additional-info">
                    <p class="hotel-card-content__additional-info-text"><?php echo esc_html($date_line); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($breakfast_included): ?>
                <div class="hotel-card-content__additional-info">
                    <p class="hotel-card-content__additional-info-text"><?php esc_html_e('Breakfast is included', 'ajinsafro-tour-bridge'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
