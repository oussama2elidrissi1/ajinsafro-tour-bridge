<?php
/**
 * Overview Partial - Tour Description & Highlights
 *
 * @var array $tour Tour data
 * @package AjinsafroTourBridge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="ajtb-section ajtb-tab-panel ajtb-tab-panel-hidden padding20" id="overview">
    <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Aperçu du Circuit', 'ajinsafro-tour-bridge'); ?></h2>

    <!-- Highlights -->
    <?php if (!empty($tour['highlights'])): ?>
        <div class="appendBottom15">
            <h3 class="font14 latoBold appendBottom10"><?php esc_html_e('Points Forts', 'ajinsafro-tour-bridge'); ?></h3>
            <ul class="ajtb-highlights-list">
                <?php foreach ($tour['highlights'] as $highlight): ?>
                    <li class="font12 appendBottom5">&#10003; <?php echo esc_html($highlight); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Overview Content (from Laravel if available) -->
    <?php if (!empty($tour['overview'])): ?>
        <div class="font12 greyText lineHeight18 appendBottom15">
            <?php echo wp_kses_post($tour['overview']); ?>
        </div>
    <?php endif; ?>

    <!-- Main Description -->
    <div class="font12 greyText lineHeight18 appendBottom15">
        <?php echo $tour['content']; ?>
    </div>

    <!-- Tour Quick Facts -->
    <div class="appendTop15">
        <h3 class="font14 latoBold appendBottom10"><?php esc_html_e('Informations Pratiques', 'ajinsafro-tour-bridge'); ?></h3>
        <div class="makeFlex flexWrap gap10">
            <?php if ($tour['duration_day'] > 0): ?>
                <div class="font12 appendBottom5">
                    <span class="latoBold"><?php esc_html_e('Durée:', 'ajinsafro-tour-bridge'); ?></span>
                    <?php echo esc_html($tour['duration_day']); ?> jour<?php echo $tour['duration_day'] > 1 ? 's' : ''; ?>
                </div>
            <?php endif; ?>

            <?php if ($tour['max_people'] > 0): ?>
                <div class="font12 appendBottom5">
                    <span class="latoBold"><?php esc_html_e('Groupe:', 'ajinsafro-tour-bridge'); ?></span>
                    <?php if ($tour['min_people'] > 0): ?>
                        <?php echo esc_html($tour['min_people']); ?> - 
                    <?php endif; ?>
                    <?php echo esc_html($tour['max_people']); ?> <?php esc_html_e('personnes', 'ajinsafro-tour-bridge'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($tour['type_tour'])): ?>
                <div class="font12 appendBottom5">
                    <span class="latoBold"><?php esc_html_e('Type:', 'ajinsafro-tour-bridge'); ?></span>
                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $tour['type_tour']))); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($tour['address'])): ?>
                <div class="font12 appendBottom5">
                    <span class="latoBold"><?php esc_html_e('Départ:', 'ajinsafro-tour-bridge'); ?></span>
                    <?php echo esc_html($tour['address']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</section>
