<?php
/**
 * Hero Partial - Title/meta + image gallery
 * New design: pageHeading with topHeading, then imageGalleryTopSection grid
 *
 * @var array $tour Tour data
 * @package AjinsafroTourBridge
 */

if (!defined('ABSPATH')) {
    exit;
}

$gallery = $tour['gallery'] ?? [];
$hero_gallery = [];
if (!empty($tour['hero_gallery']) && is_array($tour['hero_gallery'])) {
    foreach ($tour['hero_gallery'] as $img) {
        if (count($hero_gallery) >= 6) break;
        $hero_gallery[] = [
            'url'    => $img['url'] ?? '',
            'large'  => $img['large'] ?? $img['url'] ?? '',
            'medium' => $img['medium'] ?? $img['thumbnail'] ?? $img['url'] ?? '',
            'alt'    => $img['alt'] ?? $tour['title'],
        ];
    }
} else {
    $hero_url = $tour['hero_image']['url'] ?? $tour['featured_image']['url'] ?? '';
    $hero_alt = $tour['hero_image']['alt'] ?? $tour['featured_image']['alt'] ?? $tour['title'];
    if ($hero_url) {
        $hero_gallery[] = [
            'url'   => $hero_url,
            'large' => $tour['hero_image']['large'] ?? $tour['hero_image']['url'] ?? $hero_url,
            'medium' => $tour['hero_image']['medium'] ?? $hero_url,
            'alt'   => $hero_alt,
        ];
    }
    $main_url_normalized = $hero_url ? rtrim($hero_url, '/') : '';
    foreach ($gallery as $img) {
        if (count($hero_gallery) >= 6) break;
        $u = isset($img['url']) ? rtrim($img['url'], '/') : '';
        if ($u && $u !== $main_url_normalized) {
            $hero_gallery[] = [
                'url'    => $img['url'],
                'large'  => $img['large'] ?? $img['url'],
                'medium' => $img['medium'] ?? $img['thumbnail'] ?? $img['url'],
                'alt'    => $img['alt'] ?? $tour['title'],
            ];
        }
    }
}

$has_gallery = count($hero_gallery) > 0;
$all_gallery = $hero_gallery;
foreach ($gallery as $img) {
    $u = isset($img['url']) ? $img['url'] : '';
    if ($u && count($all_gallery) < 20) {
        $exists = false;
        foreach ($all_gallery as $a) {
            if (rtrim($a['url'], '/') === rtrim($u, '/')) { $exists = true; break; }
        }
        if (!$exists) {
            $all_gallery[] = [
                'url'    => $img['url'],
                'medium' => $img['medium'] ?? $img['thumbnail'] ?? $img['url'],
                'alt'    => $img['alt'] ?? $tour['title'],
            ];
        }
    }
}

// Gallery images by position
$img_main = $hero_gallery[0] ?? null;
$img_mid = array_slice($hero_gallery, 1, 4);
$img_right = $hero_gallery[5] ?? ($hero_gallery[4] ?? ($hero_gallery[3] ?? null));
$total_extra = count($all_gallery) - 6;
?>

<!-- Page Heading: Title + Meta badges -->
<div class="appendBottom15 pageHeading">
    <div class="makeFlex"><span class="topHeading"><?php echo esc_html($tour['title']); ?></span></div>
    <div class="topSubBar">
        <div class="topSubHead">
            <div class="makeFlex">
                <?php if (!empty($tour['type_tour'])): ?>
                    <div class="packageTypeTagV2">
                        <span class="font11 widthMaxContent"><?php echo esc_html(ucfirst(str_replace('_', ' ', $tour['type_tour']))); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($tour['max_people'])): ?>
                    <div class="packageTypeTagV2">
                        <span class="font11 widthMaxContent"><?php echo esc_html($tour['max_people'] . ' ' . __('People Group', 'ajinsafro-tour-bridge')); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($tour['duration_day']) || !empty($tour['duration_night'])): ?>
                    <div class="packageTypeTagV2">
                        <span class="font11 widthMaxContent">
                            <?php
                            $parts = [];
                            if (!empty($tour['duration_night'])) $parts[] = $tour['duration_night'] . 'N';
                            if (!empty($tour['duration_day'])) $parts[] = $tour['duration_day'] . 'D';
                            echo esc_html(implode('/', $parts));
                            ?>
                        </span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($tour['locations'])): ?>
                    <p class="duration-text">
                        <?php
                        $loc_names = array_map(function($loc) { return $loc['name'] ?? ''; }, array_slice($tour['locations'], 0, 3));
                        echo esc_html(implode(', ', array_filter($loc_names)));
                        ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="makeFlex hrtlCenter">
                <?php if (!empty($tour['rating'])): ?>
                    <span class="font12 latoBold"><?php echo number_format($tour['rating'], 1); ?>/5</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Image Gallery -->
<?php if ($has_gallery): ?>
    <div class="_Header imageGalleryTopSection">
        <div class="makeFlex">
            <!-- Main image (left) -->
            <div class="imageGalleryLeft">
                <div class="slideDetails" id="viewGallery">
                    <span class="holidaySprite viewGalleryIcon"></span><?php esc_html_e('VIEW GALLERY', 'ajinsafro-tour-bridge'); ?> →
                </div>
                <?php if ($img_main): ?>
                    <div class="imageLoaderContainer" style="width: 460px; height: 300px;">
                        <img class="active" width="460" height="300"
                             src="<?php echo esc_url($img_main['large'] ?: $img_main['url']); ?>"
                             alt="<?php echo esc_attr($img_main['alt']); ?>"
                             loading="eager">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Middle images (2x2 grid) -->
            <div class="imageGalleryMiddle">
                <div class="middleBlockLeft">
                    <?php for ($i = 0; $i < 2; $i++): ?>
                        <?php if (isset($img_mid[$i])): ?>
                            <div class="imageLoaderContainer" style="width: 225px; height: 145px;">
                                <img class="active pointer" width="225" height="145"
                                     src="<?php echo esc_url($img_mid[$i]['medium'] ?: $img_mid[$i]['url']); ?>"
                                     alt="<?php echo esc_attr($img_mid[$i]['alt']); ?>"
                                     loading="lazy">
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <div class="middleBlockRight">
                    <?php if (isset($img_mid[2])): ?>
                        <div class="imageLoaderContainer" style="width: 225px; height: 145px;">
                            <img class="active pointer" width="225" height="145"
                                 src="<?php echo esc_url($img_mid[2]['medium'] ?: $img_mid[2]['url']); ?>"
                                 alt="<?php echo esc_attr($img_mid[2]['alt']); ?>"
                                 loading="lazy">
                        </div>
                    <?php endif; ?>
                    <?php if (isset($img_mid[3])): ?>
                        <div class="imageNameWrapper">
                            <div class="imageLoaderContainer" style="width: 225px; height: 145px;">
                                <img class="active" width="225" height="145"
                                     src="<?php echo esc_url($img_mid[3]['medium'] ?: $img_mid[3]['url']); ?>"
                                     alt="<?php echo esc_attr($img_mid[3]['alt']); ?>"
                                     loading="lazy">
                            </div>
                            <div class="galleryImgContent">
                                <p class="galleryImgInfo"><span class="latoBold"><?php esc_html_e('Activities & Sightseeing', 'ajinsafro-tour-bridge'); ?></span></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right image -->
            <?php if ($img_right): ?>
                <div class="imageGalleryRight">
                    <div class="imageLoaderContainer" style="width: 285px; height: 300px;">
                        <img class="active" width="285" height="300"
                             src="<?php echo esc_url($img_right['large'] ?: $img_right['url']); ?>"
                             alt="<?php echo esc_attr($img_right['alt']); ?>"
                             loading="lazy">
                    </div>
                    <?php if ($total_extra > 0): ?>
                        <div class="galleryImgContent">
                            <p class="galleryImgInfo">
                                <span class="latoBold">+<?php echo $total_extra; ?> <?php esc_html_e('more photos', 'ajinsafro-tour-bridge'); ?></span>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="_Header imageGalleryTopSection">
        <div class="makeFlex perfectCenter" style="height:300px;background:#f2f2f2;border-radius:8px;">
            <span class="font14 greyText"><?php esc_html_e('Aucune image disponible', 'ajinsafro-tour-bridge'); ?></span>
        </div>
    </div>
<?php endif; ?>
