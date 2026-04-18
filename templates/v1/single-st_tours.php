<?php
/**
 * Single Tour V1 Template (clean rebuild)
 *
 * Dynamic data in V1:
 * - post ID
 * - post title
 */

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('ajth_render_header_sitewide')) {
    remove_action('get_header', 'ajth_render_header_sitewide', 5);
}
if (function_exists('ajth_render_footer_sitewide')) {
    remove_action('wp_footer', 'ajth_render_footer_sitewide', 1);
}

$tour_id = (int) get_queried_object_id();
if ($tour_id <= 0) {
    $tour_id = (int) get_the_ID();
}

if ($tour_id <= 0) {
    get_template_part('404');
    return;
}

$tour_title = trim((string) get_the_title($tour_id));
if ($tour_title === '') {
    $tour_title = __('Voyage Ajinsafro', 'ajinsafro-tour-bridge');
}

$asset_base = AJTB_PLUGIN_URL . 'assets/images/tour-v1/';
$img = static function (string $name) use ($asset_base): string {
    return esc_url($asset_base . ltrim($name, '/'));
};

$ajth_ready = function_exists('ajth_render_site_header') && function_exists('ajth_get_settings');
$ajth_settings = $ajth_ready ? ajth_get_settings() : [];

get_header();
?>

<div class="ajtb-v1-page" id="ajtb-v1-page">
    <?php if ($ajth_ready): ?>
        <?php ajth_render_site_header($ajth_settings); ?>
    <?php else: ?>
        <header class="ajtb-v1-fallback-header">
            <div class="ajtb-v1-container ajtb-v1-fallback-header__inner">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="ajtb-v1-brand" aria-label="Ajinsafro">
                    <span class="ajtb-v1-brand__logo">A</span>
                    <span class="ajtb-v1-brand__text">Ajinsafro</span>
                </a>
                <nav class="ajtb-v1-fallback-nav" aria-label="Menu principal Ajinsafro">
                    <a href="#">Voyages</a>
                    <a href="#">Hebergement</a>
                    <a href="#">Activites</a>
                    <a href="#">Group Deals</a>
                </nav>
                <a href="#" class="ajtb-v1-lowcost-btn">Formule low cost</a>
            </div>
        </header>
    <?php endif; ?>

    <main class="ajtb-v1-main">
        <div class="ajtb-v1-container">
            <section class="ajtb-v1-search-box" aria-label="Search box premium">
                <div class="ajtb-v1-search-card">
                    <span class="ajtb-v1-search-label">Ville de depart</span>
                    <span class="ajtb-v1-search-value">Casablanca <strong>▾</strong></span>
                </div>
                <div class="ajtb-v1-search-card">
                    <span class="ajtb-v1-search-label">Date de voyage</span>
                    <span class="ajtb-v1-search-value">Thu, 02 Jul 2026 <strong>▾</strong></span>
                </div>
                <div class="ajtb-v1-search-card">
                    <span class="ajtb-v1-search-label">Rooms & Guests</span>
                    <span class="ajtb-v1-search-value">2 Adults <strong>▾</strong></span>
                </div>
                <button type="button" class="ajtb-v1-search-btn">Search</button>
            </section>

            <section class="ajtb-v1-hero" aria-label="Hero tour">
                <h1 class="ajtb-v1-title"><?php echo esc_html($tour_title); ?></h1>

                <div class="ajtb-v1-meta-pills">
                    <span class="ajtb-v1-pill">5N Group Package</span>
                    <span class="ajtb-v1-pill">4.9/5</span>
                    <span class="ajtb-v1-pill">Marrakech</span>
                    <span class="ajtb-v1-pill">15 People</span>
                    <span class="ajtb-v1-pill ajtb-v1-pill--id">ID #<?php echo esc_html((string) $tour_id); ?></span>
                </div>

                <div class="ajtb-v1-gallery">
                    <img class="ajtb-v1-gallery-main" src="<?php echo $img('hero-main.svg'); ?>" alt="Hero image" loading="eager">
                    <div class="ajtb-v1-gallery-side">
                        <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Hero side 1" loading="lazy">
                        <img src="<?php echo $img('hero-side-2.svg'); ?>" alt="Hero side 2" loading="lazy">
                        <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Hero side 3" loading="lazy">
                        <img src="<?php echo $img('hero-side-4.svg'); ?>" alt="Hero side 4" loading="lazy">
                    </div>
                </div>
            </section>

            <nav class="ajtb-v1-tabs" aria-label="Tabs" role="tablist">
                <button type="button" class="ajtb-v1-tab-btn is-active" data-ajtb-tab="itinerary" role="tab" aria-selected="true">Itinerary</button>
                <button type="button" class="ajtb-v1-tab-btn" data-ajtb-tab="policies" role="tab" aria-selected="false">Policies</button>
                <button type="button" class="ajtb-v1-tab-btn" data-ajtb-tab="summary" role="tab" aria-selected="false">Summary</button>
            </nav>

            <section class="ajtb-v1-layout">
                <div class="ajtb-v1-main-column">
                    <section class="ajtb-v1-tab-panel is-active" id="ajtb-v1-panel-itinerary" role="tabpanel">
                        <article class="ajtb-v1-card ajtb-v1-overview-card">
                            <p class="ajtb-v1-kicker">Voici un apercu des activites et inclusions de cette offre</p>
                            <ul class="ajtb-v1-overview-list">
                                <li>Programme accompagne avec coordination Ajinsafro.</li>
                                <li>Vols, transferts et hotel inclus dans la formule demo.</li>
                                <li>Activites, repas et assistance sur place.</li>
                                <li>Experience premium avec rythme optimise et marge de personnalisation.</li>
                            </ul>
                        </article>

                        <div class="ajtb-v1-included-bar">
                            <span class="ajtb-v1-chip">Flight</span>
                            <span class="ajtb-v1-chip">Transfer</span>
                            <span class="ajtb-v1-chip">Hotel</span>
                            <span class="ajtb-v1-chip">Meals</span>
                            <span class="ajtb-v1-chip">Activities</span>
                        </div>

                        <div class="ajtb-v1-stats-grid">
                            <div class="ajtb-v1-stat"><strong>5</strong><span>Day Plan</span></div>
                            <div class="ajtb-v1-stat"><strong>2</strong><span>Flights</span></div>
                            <div class="ajtb-v1-stat"><strong>2</strong><span>Transfers</span></div>
                            <div class="ajtb-v1-stat"><strong>1</strong><span>Hotel</span></div>
                            <div class="ajtb-v1-stat"><strong>2</strong><span>Activities</span></div>
                        </div>

                        <div class="ajtb-v1-day-layout">
                            <aside class="ajtb-v1-day-nav" aria-label="Day navigation">
                                <button type="button" class="ajtb-v1-day-chip is-active" data-ajtb-day-target="ajtb-v1-day-1">02 Jul, Thu</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-2">03 Jul, Fri</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-3">04 Jul, Sat</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-4">05 Jul, Sun</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-5">06 Jul, Mon</button>
                            </aside>

                            <div class="ajtb-v1-timeline">
                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-1">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head">
                                            <div class="ajtb-v1-day-head-left">
                                                <span class="ajtb-v1-day-badge">Day 1</span>
                                                <h3>Arrival in Marrakech</h3>
                                                <p>Included: 1 Flight • 1 Hotel • 1 Transfer • 1 Meal</p>
                                            </div>
                                        </header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Arrivee a Marrakech, accueil par l'equipe et transfert vers l'hotel. Installation puis briefing de voyage.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-1.svg'); ?>" alt="Day 1 visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Day 1 visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-4.svg'); ?>" alt="Day 1 visual 3" loading="lazy">
                                            </div>
                                            <div class="ajtb-v1-service-card ajtb-v1-service-card--flight">
                                                <div class="ajtb-v1-service-head"><span>Flight • Casablanca to Marrakech</span><span>Change</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-flight-grid">
                                                    <div><strong>09:35</strong><small>CMN</small></div>
                                                    <div class="ajtb-v1-flight-line"></div>
                                                    <div><strong>10:40</strong><small>RAK</small></div>
                                                    <img src="<?php echo $img('card-flight.svg'); ?>" alt="Flight card visual" loading="lazy">
                                                </div>
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Transfer • Airport to Hotel</span><span>Included</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-transfer.svg'); ?>" alt="Transfer card visual" loading="lazy">
                                                    <div>
                                                        <h4>Shared Transfer</h4>
                                                        <p>Accueil aeroport avec chauffeur puis transfert confortable vers l'hotel central.</p>
                                                        <div class="ajtb-v1-meta-line"><span>Group Transfer</span><span>Private Support Desk</span><span>English/French</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Hotel • 4 Nights in Marrakech</span><span>View</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-hotel.svg'); ?>" alt="Hotel card visual" loading="lazy">
                                                    <div>
                                                        <h4>Ajinsafro Signature Riad & Spa</h4>
                                                        <p>Hebergement premium avec petit-dejeuner, emplacement central et service d'assistance 24/7.</p>
                                                        <div class="ajtb-v1-meta-line"><span>4.7/5</span><span>Double Room</span><span>Breakfast Included</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="ajtb-v1-meal">Meal • Dinner in Marrakech</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-2">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head"><div class="ajtb-v1-day-head-left"><span class="ajtb-v1-day-badge">Day 2</span><h3>Desert Discovery</h3><p>Included: 1 Activity • 2 Meals</p></div></header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Journee experience dans le desert avec transport organise, activite principale et retour en ville.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-2.svg'); ?>" alt="Day 2 visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-2.svg'); ?>" alt="Day 2 visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Day 2 visual 3" loading="lazy">
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Activity • Full Day</span><span>Included</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-activity.svg'); ?>" alt="Activity card visual" loading="lazy">
                                                    <div><h4>Desert Experience + Sunset Camp</h4><p>Programme complet avec encadrement, pauses photo et diner d'ambiance en fin de journee.</p><div class="ajtb-v1-meta-line"><span>Guided Tour</span><span>Full Day</span><span>Ticket Included</span></div></div>
                                                </div>
                                            </div>
                                            <p class="ajtb-v1-meal">Meal • Breakfast</p>
                                            <p class="ajtb-v1-meal">Meal • Dinner</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-3">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head"><div class="ajtb-v1-day-head-left"><span class="ajtb-v1-day-badge">Day 3</span><h3>Medina and City Tour</h3><p>Included: 1 Activity • 2 Meals</p></div></header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Visite guidee des points iconiques: medina, jardins, places historiques et temps libre shopping.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-3.svg'); ?>" alt="Day 3 visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Day 3 visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Day 3 visual 3" loading="lazy">
                                            </div>
                                            <p class="ajtb-v1-meal">Meal • Breakfast</p>
                                            <p class="ajtb-v1-meal">Meal • Dinner</p>
                                            <p class="ajtb-v1-note">Optional add-ons available: dinner show, quad sunset, spa session.</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-4">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head"><div class="ajtb-v1-day-head-left"><span class="ajtb-v1-day-badge">Day 4</span><h3>Leisure Day</h3><p>Included: 2 Meals</p></div></header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Jour libre pour shopping, detente, experiences personnelles ou activites optionnelles.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-4.svg'); ?>" alt="Day 4 visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-4.svg'); ?>" alt="Day 4 visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-2.svg'); ?>" alt="Day 4 visual 3" loading="lazy">
                                            </div>
                                            <p class="ajtb-v1-meal">Meal • Breakfast</p>
                                            <p class="ajtb-v1-meal">Meal • Dinner</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-5">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head"><div class="ajtb-v1-day-head-left"><span class="ajtb-v1-day-badge">Day 5</span><h3>Departure</h3><p>Included: 1 Flight • 1 Transfer • 1 Meal</p></div></header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Check-out, transfert aeroport et vol retour. Assistance Ajinsafro jusqu'au depart.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-5.svg'); ?>" alt="Day 5 visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Day 5 visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Day 5 visual 3" loading="lazy">
                                            </div>
                                            <p class="ajtb-v1-note">Thank you for travelling with Ajinsafro.</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>

                    <section class="ajtb-v1-tab-panel" id="ajtb-v1-panel-policies" role="tabpanel" hidden>
                        <article class="ajtb-v1-card">
                            <h2>Policies & Conditions</h2>
                            <p>Ces informations sont statiques pour la V1.</p>
                            <ul class="ajtb-v1-overview-list">
                                <li>Annulation gratuite jusqu'a 14 jours avant depart.</li>
                                <li>Acompte de confirmation requis a la reservation.</li>
                                <li>Les horaires definitifs sont communiques avant depart.</li>
                                <li>Support client Ajinsafro disponible 7j/7.</li>
                            </ul>
                        </article>
                    </section>

                    <section class="ajtb-v1-tab-panel" id="ajtb-v1-panel-summary" role="tabpanel" hidden>
                        <article class="ajtb-v1-card">
                            <h2>Summary</h2>
                            <div class="ajtb-v1-summary-table">
                                <div><strong>Day 1</strong><span>Flight + Transfer + Hotel + Dinner</span></div>
                                <div><strong>Day 2</strong><span>Desert Activity + Breakfast + Dinner</span></div>
                                <div><strong>Day 3</strong><span>City Tour + Meals + Optional Extras</span></div>
                                <div><strong>Day 4</strong><span>Leisure + Meals</span></div>
                                <div><strong>Day 5</strong><span>Transfer + Return Flight + Breakfast</span></div>
                            </div>
                        </article>
                    </section>
                </div>

                <aside class="ajtb-v1-sidebar">
                    <div class="ajtb-v1-side-card ajtb-v1-price-card" id="ajtb-v1-price-card">
                        <h3>Starting price</h3>
                        <p class="ajtb-v1-price"><span>14,950</span> MAD / adulte</p>
                        <p class="ajtb-v1-price-note">Prix demo V1. Taxes et options additionnelles non incluses.</p>
                        <button type="button">Proceed to payment</button>
                    </div>

                    <div class="ajtb-v1-side-card">
                        <h3>Coupons & Offers</h3>
                        <div class="ajtb-v1-coupon"><div><strong>AJIN2026</strong><p>Reduction directe sur les departs groupes.</p></div><span>-1,500 MAD</span></div>
                        <div class="ajtb-v1-coupon"><div><strong>BANKBONUS</strong><p>Offre bancaire supplementaire a l'etape paiement.</p></div><span>-900 MAD</span></div>
                        <div class="ajtb-v1-coupon"><div><strong>EMI12M</strong><p>Paiement echelonne disponible pour dossiers eligibles.</p></div><span>12 Mo</span></div>
                    </div>

                    <div class="ajtb-v1-side-card ajtb-v1-best-deals">
                        <h3>Best Deals For You</h3>
                        <ul>
                            <li>Hotel bien situe et note premium</li>
                            <li>Programme optimise avec marge de personnalisation</li>
                            <li>Assistance Ajinsafro du depart au retour</li>
                            <li>Conditions d'annulation souples</li>
                        </ul>
                    </div>
                </aside>
            </section>
        </div>
    </main>

    <?php if ($ajth_ready && defined('AJTH_DIR') && file_exists(AJTH_DIR . 'parts/newsletter.php')): ?>
        <div class="ajtb-v1-footer-wrap aj-footer-sitewide">
            <?php
            $settings = $ajth_settings;
            include AJTH_DIR . 'parts/newsletter.php';
            ?>
        </div>
    <?php else: ?>
        <footer class="ajtb-v1-fallback-footer">
            <div class="ajtb-v1-container ajtb-v1-fallback-footer__grid">
                <div><h4>En savoir plus</h4><ul><li><a href="#">A propos</a></li><li><a href="#">FAQ</a></li><li><a href="#">Conditions</a></li></ul></div>
                <div><h4>Societe</h4><ul><li><a href="#">Carriere</a></li><li><a href="#">Partenaires</a></li><li><a href="#">Contact</a></li></ul></div>
                <div><h4>Mentions legales</h4><p>Ajinsafro Recreation SARL AU</p><p>Licence N 489117 | RC 18989</p></div>
                <div><h4>Newsletter</h4><form action="#" method="post"><input type="email" name="email" placeholder="Saisissez votre email" required><button type="submit">S'inscrire</button></form></div>
            </div>
        </footer>
    <?php endif; ?>

    <button type="button" class="ajtb-v1-floating-btn" data-ajtb-action="scroll-price">Customize my trip</button>
</div>

<?php get_footer(); ?>
