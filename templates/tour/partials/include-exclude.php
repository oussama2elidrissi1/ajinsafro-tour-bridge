<?php
/**
 * Include/Exclude Partial - What's included and not included
 *
 * @var array $tour Tour data
 * @package AjinsafroTourBridge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$inclusions = $tour['inclusions'] ?? [];
$exclusions = $tour['exclusions'] ?? [];

if (empty($inclusions) && empty($exclusions)) {
    return;
}
?>

<section class="ajtb-section padding20" id="include-exclude">
    <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Ce qui est Inclus / Exclus', 'ajinsafro-tour-bridge'); ?></h2>

    <div class="makeFlex spaceBetween gap20">
        <!-- Inclusions -->
        <?php if (!empty($inclusions)): ?>
            <div class="flexOne">
                <div class="font14 latoBold appendBottom10" style="color: #249995;">
                    &#10003; <?php esc_html_e('Inclus dans le prix', 'ajinsafro-tour-bridge'); ?>
                </div>
                <ul class="ajtb-items-list">
                    <?php foreach ($inclusions as $item): ?>
                        <li class="font12 appendBottom5" style="color: #249995;">&#10003; <?php echo esc_html($item); ?></li>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Exclusions -->
        <?php if (!empty($exclusions)): ?>
            <div class="flexOne">
                <div class="font14 latoBold appendBottom10" style="color: #eb5757;">
                    &#10007; <?php esc_html_e('Non inclus', 'ajinsafro-tour-bridge'); ?>
                </div>
                <ul class="ajtb-items-list">
                    <?php foreach ($exclusions as $item): ?>
                        <li class="font12 appendBottom5" style="color: #eb5757;">&#10007; <?php echo esc_html($item); ?></li>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="font11 greyText appendTop10">
        <span><?php esc_html_e('Les services listés sont indicatifs et peuvent varier selon la saison et la disponibilité.', 'ajinsafro-tour-bridge'); ?></span>
    </div>
</section>
