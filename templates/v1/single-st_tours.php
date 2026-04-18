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
                    <span class="ajtb-v1-search-value">Casablanca <strong>v</strong></span>
                </div>
                <div class="ajtb-v1-search-card">
                    <span class="ajtb-v1-search-label">Date de voyage</span>
                    <span class="ajtb-v1-search-value">Fri, 14 Aug 2026 <strong>v</strong></span>
                </div>
                <div class="ajtb-v1-search-card">
                    <span class="ajtb-v1-search-label">Chambres et voyageurs</span>
                    <span class="ajtb-v1-search-value">2 Adultes <strong>v</strong></span>
                </div>
                <button type="button" class="ajtb-v1-search-btn">Rechercher</button>
            </section>

            <section class="ajtb-v1-hero" aria-label="Hero tour">
                <p class="ajtb-v1-hero-kicker">Ajinsafro Signature Escape - Dakhla Lagoon</p>
                <h1 class="ajtb-v1-title"><?php echo esc_html($tour_title); ?></h1>

                <div class="ajtb-v1-meta-pills">
                    <span class="ajtb-v1-pill">5 jours / 4 nuits</span>
                    <span class="ajtb-v1-pill">Dakhla Premium Stay</span>
                    <span class="ajtb-v1-pill">Petit groupe 12 pers</span>
                    <span class="ajtb-v1-pill">4.9 / 5 voyageurs</span>
                    <span class="ajtb-v1-pill ajtb-v1-pill--id">ID #<?php echo esc_html((string) $tour_id); ?></span>
                </div>

                <div class="ajtb-v1-gallery">
                    <img class="ajtb-v1-gallery-main" src="<?php echo $img('hero-main.svg'); ?>" alt="Dakhla lagoon hero visual" loading="eager">
                    <div class="ajtb-v1-gallery-side">
                        <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Dakhla side visual 1" loading="lazy">
                        <img src="<?php echo $img('hero-side-2.svg'); ?>" alt="Dakhla side visual 2" loading="lazy">
                        <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Dakhla side visual 3" loading="lazy">
                        <img src="<?php echo $img('hero-side-4.svg'); ?>" alt="Dakhla side visual 4" loading="lazy">
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
                            <p class="ajtb-v1-kicker">Programme Dakhla coherent avec un sejour Ajinsafro premium</p>
                            <ul class="ajtb-v1-overview-list">
                                <li>Vols aller retour et transferts aeroport inclus.</li>
                                <li>Sejour en eco lodge premium face au lagon.</li>
                                <li>Activites nautiques, excursion desert et experiences locales.</li>
                                <li>Accompagnement Ajinsafro et assistance client 7j/7.</li>
                            </ul>
                        </article>

                        <div class="ajtb-v1-included-bar">
                            <span class="ajtb-v1-chip">Flights</span>
                            <span class="ajtb-v1-chip">Transfers</span>
                            <span class="ajtb-v1-chip">Lagoon Lodge</span>
                            <span class="ajtb-v1-chip">Meals</span>
                            <span class="ajtb-v1-chip">Water Activities</span>
                        </div>

                        <div class="ajtb-v1-stats-grid">
                            <div class="ajtb-v1-stat"><strong>5</strong><span>Day Plan</span></div>
                            <div class="ajtb-v1-stat"><strong>2</strong><span>Flights</span></div>
                            <div class="ajtb-v1-stat"><strong>2</strong><span>Transfers</span></div>
                            <div class="ajtb-v1-stat"><strong>1</strong><span>Hotel</span></div>
                            <div class="ajtb-v1-stat"><strong>3</strong><span>Activities</span></div>
                        </div>

                        <div class="ajtb-v1-day-layout">
                            <aside class="ajtb-v1-day-nav" aria-label="Day navigation">
                                <button type="button" class="ajtb-v1-day-chip is-active" data-ajtb-day-target="ajtb-v1-day-1">14 Aug, Fri</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-2">15 Aug, Sat</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-3">16 Aug, Sun</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-4">17 Aug, Mon</button>
                                <button type="button" class="ajtb-v1-day-chip" data-ajtb-day-target="ajtb-v1-day-5">18 Aug, Tue</button>
                            </aside>

                            <div class="ajtb-v1-timeline">
                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-1">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head">
                                            <div class="ajtb-v1-day-head-left">
                                                <span class="ajtb-v1-day-badge">Day 1</span>
                                                <h3>Arrival in Dakhla Lagoon</h3>
                                                <p>Included: 1 Flight - 1 Hotel - 1 Transfer - 1 Meal</p>
                                            </div>
                                        </header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Arrivee a Dakhla, accueil a l aeroport et transfert prive vers le lodge. Installation, cocktail de bienvenue et briefing du sejour.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-1.svg'); ?>" alt="Day 1 Dakhla visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Day 1 Dakhla visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-4.svg'); ?>" alt="Day 1 Dakhla visual 3" loading="lazy">
                                            </div>
                                            <div class="ajtb-v1-service-card ajtb-v1-service-card--flight">
                                                <div class="ajtb-v1-service-head"><span>Flight - Casablanca to Dakhla</span><span>Confirmed</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-flight-grid">
                                                    <div><strong>08:10</strong><small>CMN</small></div>
                                                    <div class="ajtb-v1-flight-line"></div>
                                                    <div><strong>10:20</strong><small>VIL</small></div>
                                                    <img src="<?php echo $img('card-flight.svg'); ?>" alt="Flight card Dakhla visual" loading="lazy">
                                                </div>
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Transfer - Airport to Lagoon Lodge</span><span>Included</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-transfer.svg'); ?>" alt="Transfer card Dakhla visual" loading="lazy">
                                                    <div>
                                                        <h4>Private arrival transfer</h4>
                                                        <p>Vehicule premium climatise avec accueil Ajinsafro directement a la sortie terminal.</p>
                                                        <div class="ajtb-v1-meta-line"><span>35 min ride</span><span>Meet and greet</span><span>Baggage assistance</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Hotel - 4 Nights in Dakhla</span><span>View</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-hotel.svg'); ?>" alt="Hotel card Dakhla visual" loading="lazy">
                                                    <div>
                                                        <h4>Dakhla Lagoon Signature Camp</h4>
                                                        <p>Suites vue mer, demi pension, espace wellness et acces direct a la plage privee.</p>
                                                        <div class="ajtb-v1-meta-line"><span>4.8/5</span><span>Lagoon view suite</span><span>Breakfast included</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="ajtb-v1-meal">Meal - Welcome dinner by the lagoon</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-2">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head">
                                            <div class="ajtb-v1-day-head-left">
                                                <span class="ajtb-v1-day-badge">Day 2</span>
                                                <h3>Kite and lagoon experience</h3>
                                                <p>Included: 1 Activity - 2 Meals</p>
                                            </div>
                                        </header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Session initiation kite ou wing foil selon votre niveau, suivie d une croisiere douce sur le lagon en fin d apres midi.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-2.svg'); ?>" alt="Day 2 Dakhla visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-2.svg'); ?>" alt="Day 2 Dakhla visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Day 2 Dakhla visual 3" loading="lazy">
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Activity - Half day coaching</span><span>Included</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-activity.svg'); ?>" alt="Activity card Dakhla visual" loading="lazy">
                                                    <div>
                                                        <h4>Kite coaching and lagoon run</h4>
                                                        <p>Materiel inclus, briefing securite, suivi instructeur et session adaptee debutants/intermediaires.</p>
                                                        <div class="ajtb-v1-meta-line"><span>Certified coach</span><span>2h water time</span><span>Equipment included</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="ajtb-v1-meal">Meal - Breakfast at lodge</p>
                                            <p class="ajtb-v1-meal">Meal - Seafood dinner selection</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-3">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head">
                                            <div class="ajtb-v1-day-head-left">
                                                <span class="ajtb-v1-day-badge">Day 3</span>
                                                <h3>White Dune and Imlili by 4x4</h3>
                                                <p>Included: 1 Activity - 2 Meals</p>
                                            </div>
                                        </header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Excursion 4x4 vers la dune blanche, pause photo, decouverte des sources d Imlili et dejeuner pique nique sur site.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-3.svg'); ?>" alt="Day 3 Dakhla visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Day 3 Dakhla visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Day 3 Dakhla visual 3" loading="lazy">
                                            </div>
                                            <p class="ajtb-v1-meal">Meal - Breakfast at lodge</p>
                                            <p class="ajtb-v1-meal">Meal - Sunset dinner at camp</p>
                                            <p class="ajtb-v1-note">Optional add-ons: quad ride, private photo session, spa recovery.</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-4">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head">
                                            <div class="ajtb-v1-day-head-left">
                                                <span class="ajtb-v1-day-badge">Day 4</span>
                                                <h3>Dragon Island and oyster tasting</h3>
                                                <p>Included: 1 Activity - 2 Meals</p>
                                            </div>
                                        </header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Sortie bateau vers Dragon Island, balade sur bancs de sable et degustation d huitres avec equipe locale.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-4.svg'); ?>" alt="Day 4 Dakhla visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-4.svg'); ?>" alt="Day 4 Dakhla visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-2.svg'); ?>" alt="Day 4 Dakhla visual 3" loading="lazy">
                                            </div>
                                            <p class="ajtb-v1-meal">Meal - Breakfast at lodge</p>
                                            <p class="ajtb-v1-meal">Meal - Oyster and grill dinner</p>
                                        </div>
                                    </div>
                                </article>

                                <article class="ajtb-v1-day-card" id="ajtb-v1-day-5">
                                    <div class="ajtb-v1-day-box">
                                        <header class="ajtb-v1-day-head">
                                            <div class="ajtb-v1-day-head-left">
                                                <span class="ajtb-v1-day-badge">Day 5</span>
                                                <h3>Departure from Dakhla</h3>
                                                <p>Included: 1 Flight - 1 Transfer - 1 Meal</p>
                                            </div>
                                        </header>
                                        <div class="ajtb-v1-day-content">
                                            <p class="ajtb-v1-day-desc">Dernier petit dejeuner face au lagon, check out puis transfert aeroport pour votre vol retour vers Casablanca.</p>
                                            <div class="ajtb-v1-day-gallery">
                                                <img src="<?php echo $img('day-5.svg'); ?>" alt="Day 5 Dakhla visual 1" loading="lazy">
                                                <img src="<?php echo $img('hero-side-1.svg'); ?>" alt="Day 5 Dakhla visual 2" loading="lazy">
                                                <img src="<?php echo $img('hero-side-3.svg'); ?>" alt="Day 5 Dakhla visual 3" loading="lazy">
                                            </div>
                                            <div class="ajtb-v1-service-card">
                                                <div class="ajtb-v1-service-head"><span>Transfer - Lodge to Airport</span><span>Included</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-media-row">
                                                    <img src="<?php echo $img('card-transfer.svg'); ?>" alt="Transfer return card visual" loading="lazy">
                                                    <div>
                                                        <h4>Departure transfer</h4>
                                                        <p>Prise en charge a la reception du lodge avec assistance Ajinsafro jusqu au comptoir check in.</p>
                                                        <div class="ajtb-v1-meta-line"><span>On time pickup</span><span>Comfort vehicle</span><span>24/7 support</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ajtb-v1-service-card ajtb-v1-service-card--flight">
                                                <div class="ajtb-v1-service-head"><span>Flight - Dakhla to Casablanca</span><span>Confirmed</span></div>
                                                <div class="ajtb-v1-service-body ajtb-v1-flight-grid">
                                                    <div><strong>16:35</strong><small>VIL</small></div>
                                                    <div class="ajtb-v1-flight-line"></div>
                                                    <div><strong>18:45</strong><small>CMN</small></div>
                                                    <img src="<?php echo $img('card-flight.svg'); ?>" alt="Return flight card visual" loading="lazy">
                                                </div>
                                            </div>
                                            <p class="ajtb-v1-meal">Meal - Breakfast at lodge</p>
                                            <p class="ajtb-v1-note">Merci d avoir voyage avec Ajinsafro. Team support reste disponible apres retour.</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>

                    <section class="ajtb-v1-tab-panel" id="ajtb-v1-panel-policies" role="tabpanel" hidden>
                        <article class="ajtb-v1-card">
                            <h2>Policies and Conditions</h2>
                            <p>Contenu statique V1 harmonise pour un sejour Dakhla.</p>
                            <ul class="ajtb-v1-overview-list">
                                <li>Annulation gratuite jusqu a 14 jours avant depart.</li>
                                <li>Acompte de confirmation de 30 pourcent a la reservation.</li>
                                <li>Les activites nautiques dependent des conditions vent et maree.</li>
                                <li>Support client Ajinsafro disponible avant, pendant et apres voyage.</li>
                            </ul>
                        </article>
                    </section>

                    <section class="ajtb-v1-tab-panel" id="ajtb-v1-panel-summary" role="tabpanel" hidden>
                        <article class="ajtb-v1-card">
                            <h2>Summary</h2>
                            <div class="ajtb-v1-summary-table">
                                <div><strong>Day 1</strong><span>Arrival flight, transfer and lagoon check in</span></div>
                                <div><strong>Day 2</strong><span>Kite coaching and premium lagoon dinner</span></div>
                                <div><strong>Day 3</strong><span>White Dune 4x4 excursion and sunset dinner</span></div>
                                <div><strong>Day 4</strong><span>Dragon Island cruise and oyster tasting</span></div>
                                <div><strong>Day 5</strong><span>Return transfer, return flight and departure</span></div>
                            </div>
                        </article>
                    </section>
                </div>

                <aside class="ajtb-v1-sidebar">
                    <div class="ajtb-v1-side-card ajtb-v1-price-card" id="ajtb-v1-price-card">
                        <h3>Starting price</h3>
                        <p class="ajtb-v1-price"><span>12,900</span> MAD / adulte</p>
                        <p class="ajtb-v1-price-note">Base chambre double. Tarifs hors options privees et assurances complementaires.</p>
                        <ul class="ajtb-v1-price-includes">
                            <li>4 nuits en lodge premium</li>
                            <li>Vols aller retour</li>
                            <li>2 transferts aeroport</li>
                            <li>3 experiences signatures</li>
                        </ul>
                        <button type="button">Proceed to payment</button>
                    </div>

                    <div class="ajtb-v1-side-card ajtb-v1-side-highlight">
                        <h3>Depart confirme</h3>
                        <p>Fri 14 Aug 2026 - Slots restants: 4 voyageurs</p>
                    </div>

                    <div class="ajtb-v1-side-card">
                        <h3>Coupons and Offers</h3>
                        <div class="ajtb-v1-coupon"><div><strong>DAKHLA1200</strong><p>Reduction directe sur ce depart Dakhla.</p></div><span>-1,200 MAD</span></div>
                        <div class="ajtb-v1-coupon"><div><strong>AJBANK500</strong><p>Bonus additionnel avec cartes bancaires eligibles.</p></div><span>-500 MAD</span></div>
                        <div class="ajtb-v1-coupon"><div><strong>FLEXPAY</strong><p>Paiement en plusieurs fois sur validation dossier.</p></div><span>3x</span></div>
                    </div>

                    <div class="ajtb-v1-side-card ajtb-v1-best-deals">
                        <h3>Best Deals For You</h3>
                        <ul>
                            <li>Lodge premium en bord de lagon</li>
                            <li>Experiences nautiques et desert combinees</li>
                            <li>Assistance Ajinsafro de A a Z</li>
                            <li>Programme equilibre entre activite et repos</li>
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
                <div><h4>Newsletter</h4><form action="#" method="post"><input type="email" name="email" placeholder="Saisissez votre email" required><button type="submit">S inscrire</button></form></div>
            </div>
        </footer>
    <?php endif; ?>

    <button type="button" class="ajtb-v1-floating-btn" data-ajtb-action="scroll-price">Customize my trip</button>
</div>

<?php get_footer(); ?>
