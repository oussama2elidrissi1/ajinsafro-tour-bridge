<?php
/**
 * Transfer Card partial – New design: transfer-row-v2 with image, title, description, route.
 *
 * @var array  $transfer Transfer row (from_label, to_label, pickup_time, dropoff_time, vehicle_type, notes, image_url)
 * @var string $label    Optional; e.g. "Transfert Aeroport -> Hotel"
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

if (empty($transfer) || !is_array($transfer)) {
    return;
}

$dash = '-';
$from = isset($transfer['from_label']) ? trim((string) $transfer['from_label']) : '';
$to = isset($transfer['to_label']) ? trim((string) $transfer['to_label']) : '';
if ($from === '' && $to === '') {
    return;
}
$from = $from !== '' ? $from : $dash;
$to = $to !== '' ? $to : $dash;

$pickup = isset($transfer['pickup_time']) ? trim((string) $transfer['pickup_time']) : '';
$dropoff = isset($transfer['dropoff_time']) ? trim((string) $transfer['dropoff_time']) : '';
$vehicle = isset($transfer['vehicle_type']) ? trim((string) $transfer['vehicle_type']) : '';
$notes = isset($transfer['notes']) ? trim((string) $transfer['notes']) : '';
$card_label = isset($label) && (string) $label !== '' ? (string) $label : __('Transfert', 'ajinsafro-tour-bridge');

$date_label = '';
foreach (['transfer_date_formatted', 'date_formatted', 'transfer_date', 'date', 'pickup_date'] as $key) {
    if (!empty($transfer[$key])) {
        $date_label = trim((string) $transfer[$key]);
        break;
    }
}
if ($date_label === '' && $pickup !== '') {
    $date_label = $pickup;
}

$section_title = '';
foreach (['transfer_title', 'service_title', 'service_name', 'transfer_type', 'type'] as $key) {
    if (!empty($transfer[$key])) {
        $section_title = trim((string) $transfer[$key]);
        break;
    }
}
if ($section_title === '') {
    $section_title = __('Transfert Partagé', 'ajinsafro-tour-bridge');
}

$transfer_name = '';
foreach (['name', 'title', 'transfer_name'] as $key) {
    if (!empty($transfer[$key])) {
        $transfer_name = trim((string) $transfer[$key]);
        break;
    }
}
if ($transfer_name === '') {
    $transfer_name = $section_title;
}

$route_text = $from . ' → ' . $to;
$description = $notes !== '' ? $notes : $card_label . ' - ' . $route_text;

$image_url = '';
if (!empty($transfer['image_url'])) {
    $image_url = trim((string) $transfer['image_url']);
}

$can_edit = current_user_can('edit_posts');
?>
<div class="transfer-wrapper-v2 transfer-header-left-bar" data-transfer-id="<?php echo esc_attr((int) ($transfer['id'] ?? 0)); ?>">
    <div class="transfer-row-header paddingB15">
        <div class="makeFlex">
            <div class="header-width">
                <span class="latoBold"><?php esc_html_e('TRANSFER', 'ajinsafro-tour-bridge'); ?></span>
                <span class="header-span"></span>
                <span><?php echo esc_html($card_label); ?></span>
            </div>
            <span class="mmt-chevron-up appendTop2"></span>
        </div>
        <?php if ($can_edit): ?>
            <div id="changeRemoveBtn" class="transfer-row-btns change-remove-btn-layout">
                <span id="other" data-aj-action="edit-transfer"><?php esc_html_e('Modifier', 'ajinsafro-tour-bridge'); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="transfer-row-body">
        <div class="transfer-card-img-top-bar">
            <?php if ($image_url !== ''): ?>
                <figure class="transfer-row-image-wrapper-v2">
                    <div class="image-carousel-slide">
                        <div class="imageLoaderContainer" style="width: 180px; height: 100px;">
                            <img class="imgborder-transfer borderRadius16 vrtTop active" width="180" height="100" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($transfer_name); ?>" loading="lazy">
                        </div>
                    </div>
                </figure>
            <?php endif; ?>
            <div class="transfer-row-details">
                <div class="transfer-row-details-title appendBottom6">
                    <span class="font18 latoBlack blackText"><?php echo esc_html($transfer_name); ?></span>
                </div>
                <div class="transfer-row-text-desc description-width-v2 font12 appendBottom6">
                    <p class="descriptionContainer">
                        <span class="activity-row-text-desc"><?php echo esc_html($description); ?></span>
                    </p>
                </div>
                <?php if ($vehicle !== ''): ?>
                    <div class="transfer-row-date-info appendTop2">
                        <p class="font12 greyText"><?php echo esc_html(__('Véhicule:', 'ajinsafro-tour-bridge') . ' ' . $vehicle); ?></p>
                    </div>
                <?php endif; ?>
                <div class="transfer-row-date-info appendTop2">
                    <div class="icon-image-v2 paddingTop2">
                        <svg width="12" height="12" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.0002 8.20024C15.0002 13.1185 9.10024 19.0002 8.20024 19.0002C7.30024 19.0002 1.00024 13.1185 1.00024 8.20024C1.00024 3.28202 4.13425 1.00024 8.00024 1.00024C11.8662 1.00024 15.0002 3.28202 15.0002 8.20024Z" stroke="#A2A2A2" stroke-linecap="round"></path>
                            <ellipse cx="8.00054" cy="8.00024" rx="3.23077" ry="3" stroke="#A2A2A2" stroke-linecap="round"></ellipse>
                        </svg>
                    </div>
                    <p class="appendLeft5"><?php echo esc_html($route_text); ?></p>
                </div>
                <?php if ($date_label !== ''): ?>
                    <div class="transfer-row-date-info appendTop2">
                        <p class="font12 greyText"><?php echo esc_html($date_label); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
