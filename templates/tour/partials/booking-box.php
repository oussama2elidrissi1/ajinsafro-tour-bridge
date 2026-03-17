<?php
/**
 * Booking Box Partial – New design: pakageDtlWrap with price, CTA, offers.
 *
 * @var array $tour Tour data
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

$pricing = $tour['pricing'] ?? [];
$display_price = $pricing['display_price'] ?? 0;
$original_price = $pricing['original_price'] ?? $pricing['adult'] ?? 0;
$has_discount = $pricing['has_discount'] ?? false;
$discount = $pricing['discount'] ?? 0;
$currency_symbol = $pricing['currency_symbol'] ?? 'DH';
$has_seasonal = !empty($pricing['seasonal_rules']);
$current_season = $pricing['current_season'] ?? null;
?>

<!-- Price Card -->
<div class="pakageDtlWrap appendBottom20" id="ajtb-price-card">
    <div class="pakageDtlWrapTop">
        <?php if ($has_discount && $original_price > $display_price): ?>
            <div class="packageDtlslashedOfferPrice">
                <p class="slashedPrice"><?php echo esc_html($currency_symbol); ?> <?php echo number_format($original_price, 0, ',', ' '); ?></p>
                <span class="packageOfftag"><?php echo (int) $discount; ?>% OFF</span>
            </div>
        <?php endif; ?>
        <p class="priceContainer">
            <span class="priceDetail"><span><?php echo esc_html($currency_symbol); ?> <?php echo number_format($display_price, 0, ',', ' '); ?></span></span>
            /<?php esc_html_e('Adult', 'ajinsafro-tour-bridge'); ?>
        </p>
        <p class="excludingText"><?php esc_html_e('Excluding applicable taxes', 'ajinsafro-tour-bridge'); ?></p>
    </div>
    <div class="pakageDtlWrapBottom">
        <form class="booking-form" id="ajtb-booking-form">
            <input type="hidden" name="tour_id" value="<?php echo esc_attr($tour['id']); ?>">
            <input type="hidden" name="date" id="booking-date" value="">
            <input type="hidden" name="departure_place_id" id="booking-departure-place" value="">
            <input type="hidden" name="adults" id="adults" value="2">
            <input type="hidden" name="children" id="children" value="0">

            <div class="pakageDtlCta" id="continue">
                <?php if (!empty($tour['external_booking_link'])): ?>
                    <a href="<?php echo esc_url($tour['external_booking_link']); ?>" class="primaryBtn fill btn btn-primary btn-sm borderRadius8 padding13" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('PROCEED TO PAYMENT', 'ajinsafro-tour-bridge'); ?>
                    </a>
                <?php else: ?>
                    <button type="submit" class="primaryBtn fill btn btn-primary btn-sm borderRadius8 padding13">
                        <?php esc_html_e('PROCEED TO PAYMENT', 'ajinsafro-tour-bridge'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Offers / Coupons Section -->
<div class="offersWrap appendBottom20">
    <div class="offersSection appendBottom20" id="coupon">
        <p class="couponsHead"><?php esc_html_e('Coupons & Offers', 'ajinsafro-tour-bridge'); ?></p>

        <!-- Cart Summary -->
        <div class="padding15">
            <div class="booking-cart" id="booking-cart">
                <div class="cart-item cart-item-base makeFlex spaceBetween appendBottom10">
                    <span class="font12 greyText"><?php esc_html_e('Prix de base', 'ajinsafro-tour-bridge'); ?></span>
                    <span class="font12 latoBold" id="cart-base-value">
                        <?php echo number_format($display_price * 2, 0, ',', ' '); ?> <?php echo esc_html($currency_symbol); ?>
                    </span>
                </div>

                <!-- Activities List -->
                <div class="cart-activities" id="cart-activities-wrapper" style="display: none;">
                    <div class="latoBold font12 appendBottom5"><?php esc_html_e('Activités ajoutées', 'ajinsafro-tour-bridge'); ?></div>
                    <ul class="ajtb-activity-list" id="cart-activities-list"></ul>
                </div>
            </div>

            <!-- Total -->
            <div class="booking-total makeFlex spaceBetween appendTop10" style="border-top: 1px solid #e7e7e7; padding-top: 10px;">
                <span class="latoBold font14"><?php esc_html_e('Total estimé', 'ajinsafro-tour-bridge'); ?></span>
                <span class="latoBold font14" id="booking-total">
                    <?php echo number_format($display_price * 2, 0, ',', ' '); ?> <?php echo esc_html($currency_symbol); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Seasonal Pricing -->
<?php if ($has_seasonal && count($pricing['seasonal_rules']) > 1): ?>
    <div class="offersWrap appendBottom20">
        <div class="offersSection">
            <p class="couponsHead"><?php esc_html_e('Tarifs par Saison', 'ajinsafro-tour-bridge'); ?></p>
            <div class="padding15">
                <?php foreach ($pricing['seasonal_rules'] as $rule):
                    $is_current = $current_season && ($current_season['id'] ?? null) === ($rule['id'] ?? null);
                ?>
                    <div class="makeFlex spaceBetween appendBottom10 <?php echo $is_current ? 'latoBold' : ''; ?>">
                        <div class="flexOne">
                            <p class="font12 blackText">
                                <?php echo esc_html($rule['season_name'] ?? 'Saison'); ?>
                                <?php if ($is_current): ?><small class="linkText">(<?php esc_html_e('actuelle', 'ajinsafro-tour-bridge'); ?>)</small><?php endif; ?>
                            </p>
                            <?php if (!empty($rule['start_date']) && !empty($rule['end_date'])): ?>
                                <p class="font10 greyText">
                                    <?php
                                    $start = new DateTime($rule['start_date']);
                                    $end = new DateTime($rule['end_date']);
                                    echo esc_html($start->format('d M') . ' - ' . $end->format('d M Y'));
                                    ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <span class="font12 latoBold"><?php echo ajtb_format_price($rule['adult_price']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
