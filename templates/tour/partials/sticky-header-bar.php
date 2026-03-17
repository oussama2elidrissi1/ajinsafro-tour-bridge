<?php
/**
 * Sticky header bar: titre + barre onglets uniquement (collés). Les jours et le plan de séjour scrollent avec le contenu.
 *
 * @var array $tour Tour data
 * @var array $global_totals ['days','flights','transfers','hotels','activities','meals']
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

$tour_title_display = $tour['title'] ?? get_the_title();
?>
<div class="initerary-nav ajtb-sticky-header-bar" id="ajtb-global-summary-bar" data-ajtb-mode="global">
    <ul aria-label="<?php esc_attr_e('Résumé du séjour', 'ajinsafro-tour-bridge'); ?>">
        <li class="active" data-ajtb-global-tab="programme">
            <?php echo esc_html($global_totals['days']); ?> DAY PLAN
        </li>
        <?php if ($global_totals['flights'] > 0 || $global_totals['transfers'] > 0): ?>
            <li data-ajtb-global-tab="flights-transfers">
                <?php
                $parts = [];
                if ($global_totals['flights'] > 0) $parts[] = $global_totals['flights'] . ' ' . ($global_totals['flights'] > 1 ? __('FLIGHTS', 'ajinsafro-tour-bridge') : __('FLIGHT', 'ajinsafro-tour-bridge'));
                if ($global_totals['transfers'] > 0) $parts[] = $global_totals['transfers'] . ' ' . ($global_totals['transfers'] > 1 ? __('TRANSFERS', 'ajinsafro-tour-bridge') : __('TRANSFER', 'ajinsafro-tour-bridge'));
                echo esc_html(implode(' & ', $parts));
                ?>
            </li>
        <?php endif; ?>
        <?php if ($global_totals['hotels'] > 0): ?>
            <li data-ajtb-global-tab="hotels">
                <?php echo esc_html($global_totals['hotels']); ?> <?php echo $global_totals['hotels'] > 1 ? __('HOTELS', 'ajinsafro-tour-bridge') : __('HOTEL', 'ajinsafro-tour-bridge'); ?>
            </li>
        <?php endif; ?>
        <?php if ($global_totals['activities'] > 0): ?>
            <li data-ajtb-global-tab="activities">
                <?php echo esc_html($global_totals['activities']); ?> <?php echo $global_totals['activities'] > 1 ? __('ACTIVITIES', 'ajinsafro-tour-bridge') : __('ACTIVITY', 'ajinsafro-tour-bridge'); ?>
            </li>
        <?php endif; ?>
        <?php if ($global_totals['meals'] > 0): ?>
            <li data-ajtb-global-tab="meals">
                <?php echo esc_html($global_totals['meals']); ?> <?php echo $global_totals['meals'] > 1 ? __('MEALS', 'ajinsafro-tour-bridge') : __('MEAL', 'ajinsafro-tour-bridge'); ?>
            </li>
        <?php endif; ?>
    </ul>
</div>
