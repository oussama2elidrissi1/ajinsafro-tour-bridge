<?php
/**
 * Tour recap step (V1).
 *
 * This page is a validation step before any booking/reservation.
 */
if (!defined('ABSPATH')) {
    exit;
}

$tour_id = (int) get_queried_object_id();
if ($tour_id <= 0) {
    $tour_id = (int) get_the_ID();
}
if ($tour_id <= 0) {
    get_template_part('404');
    return;
}

$tour_data = class_exists('AJTB_V1_Data_Provider')
    ? AJTB_V1_Data_Provider::build($tour_id)
    : [];

$tour_title = isset($tour_data['title']) ? (string) $tour_data['title'] : trim((string) get_the_title($tour_id));
if ($tour_title === '') {
    $tour_title = __('Voyage Ajinsafro', 'ajinsafro-tour-bridge');
}

$destination = isset($tour_data['destination']) ? (string) $tour_data['destination'] : 'Destination';
$duration_label = isset($tour_data['duration_label']) ? (string) $tour_data['duration_label'] : '';
$hero_main = $tour_data['hero']['main'] ?? (AJTB_PLUGIN_URL . 'assets/images/tour-v1/hero-main.svg');
$recap_back_url = get_permalink($tour_id) ?: home_url('/');

get_header();
?>

<div class="ajtb-v1-page ajtb-v1-recap" id="ajtb-v1-page" data-ajtb-recap-root data-tour-id="<?php echo esc_attr((string) $tour_id); ?>">
    <main class="ajtb-v1-main">
        <div class="ajtb-v1-container">
            <section class="ajtb-v1-recap-head">
                <a class="ajtb-v1-recap-back" href="<?php echo esc_url($recap_back_url); ?>">
                    ← Modifier ma sélection
                </a>
                <div>
                    <p class="ajtb-v1-kicker">Récapitulatif</p>
                    <h1 class="ajtb-v1-title"><?php echo esc_html($tour_title); ?></h1>
                    <p class="ajtb-v1-recap-subtitle">Vérifiez vos choix avant de confirmer.</p>
                </div>
            </section>

            <section class="ajtb-v1-recap-grid">
                <article class="ajtb-v1-card ajtb-v1-recap-tour">
                    <div class="ajtb-v1-recap-tour-media">
                        <img src="<?php echo esc_url($hero_main); ?>" alt="<?php echo esc_attr($tour_title); ?>" loading="eager">
                    </div>
                    <div class="ajtb-v1-recap-tour-body">
                        <div class="ajtb-v1-recap-tour-meta">
                            <span class="ajtb-v1-pill"><?php echo esc_html($duration_label !== '' ? $duration_label : 'Durée'); ?></span>
                            <span class="ajtb-v1-pill"><?php echo esc_html($destination); ?></span>
                            <span class="ajtb-v1-pill ajtb-v1-pill--id">ID #<?php echo esc_html((string) $tour_id); ?></span>
                        </div>
                        <h2 class="ajtb-v1-recap-section-title">Résumé du voyage</h2>
                        <ul class="ajtb-v1-recap-tour-points">
                            <li>Destination : <strong><?php echo esc_html($destination); ?></strong></li>
                            <li>Durée : <strong><?php echo esc_html($duration_label !== '' ? $duration_label : '—'); ?></strong></li>
                        </ul>
                    </div>
                </article>

                <article class="ajtb-v1-card ajtb-v1-recap-selection">
                    <h2 class="ajtb-v1-recap-section-title">Votre sélection</h2>
                    <dl class="ajtb-v1-recap-dl" id="ajtb-v1-recap-selection">
                        <div><dt>Ville de départ</dt><dd data-ajtb-recap-field="departure">—</dd></div>
                        <div><dt>Date de voyage</dt><dd data-ajtb-recap-field="date">—</dd></div>
                        <div><dt>Voyageurs</dt><dd data-ajtb-recap-field="guests">—</dd></div>
                        <div><dt>Détail</dt><dd data-ajtb-recap-field="guestBreakdown">—</dd></div>
                        <div><dt>Hébergement</dt><dd data-ajtb-recap-field="hotel">—</dd></div>
                        <div><dt>Vol</dt><dd data-ajtb-recap-field="flight">—</dd></div>
                        <div><dt>Transferts</dt><dd data-ajtb-recap-field="transfers">—</dd></div>
                        <div><dt>Activités</dt><dd data-ajtb-recap-field="activities">—</dd></div>
                        <div><dt>Options / suppléments</dt><dd data-ajtb-recap-field="options">—</dd></div>
                    </dl>
                    <p class="ajtb-v1-recap-hint" data-ajtb-recap-hint hidden>
                        Certaines informations n’ont pas pu être récupérées. Cliquez sur “Modifier ma sélection” puis “Continuer” à nouveau.
                    </p>
                </article>

                <aside class="ajtb-v1-side-card ajtb-v1-recap-price">
                    <h2 class="ajtb-v1-recap-section-title">Prix</h2>
                    <div class="ajtb-v1-recap-total">
                        <span>Total</span>
                        <strong><span data-ajtb-recap-field="total">—</span> <small data-ajtb-recap-field="currency">MAD</small></strong>
                    </div>
                    <div class="ajtb-v1-recap-price-detail" data-ajtb-recap-field="priceDetail">—</div>
                    <div class="ajtb-v1-recap-actions">
                        <a class="ajtb-v1-recap-btn ajtb-v1-recap-btn--ghost" href="<?php echo esc_url($recap_back_url); ?>">Modifier ma sélection</a>
                        <button type="button" class="ajtb-v1-recap-btn ajtb-v1-recap-btn--primary" data-ajtb-recap-action="confirm">Confirmer</button>
                    </div>
                    <p class="ajtb-v1-recap-note">Aucune réservation n’est créée tant que vous n’avez pas confirmé.</p>
                </aside>
            </section>
        </div>
    </main>
</div>

<script>
window.ajtbRecapBase = <?php echo wp_json_encode([
    'tourId' => (int) $tour_id,
    'tourTitle' => (string) $tour_title,
    'destination' => (string) $destination,
    'duration' => (string) $duration_label,
    'permalink' => (string) ($recap_back_url ?: ''),
]); ?>;
</script>

<?php get_footer(); ?>

