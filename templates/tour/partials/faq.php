<?php
/**
 * FAQ Partial - Frequently Asked Questions Accordion
 *
 * @var array $tour Tour data
 * @package AjinsafroTourBridge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$faqs = $tour['faqs'] ?? [];

if (empty($faqs)) {
    return;
}
?>

<section class="ajtb-section padding20" id="faq">
    <h2 class="font16 latoBold appendBottom15"><?php esc_html_e('Questions Fréquentes', 'ajinsafro-tour-bridge'); ?></h2>

    <div class="ajtb-faq-accordion">
        <?php foreach ($faqs as $index => $faq): 
            $question = $faq['question'] ?? '';
            $answer = $faq['answer'] ?? '';
            
            if (empty($question)) {
                continue;
            }
        ?>
            <div class="faq-item" data-faq="<?php echo $index; ?>">
                <button class="faq-question makeFlex spaceBetween" 
                        aria-expanded="false" 
                        aria-controls="faq-answer-<?php echo $index; ?>">
                    <span class="font14 latoBold"><?php echo esc_html($question); ?></span>
                    <span class="mmt-chevron-down"></span>
                </button>
                <div class="faq-answer font12 greyText lineHeight18" id="faq-answer-<?php echo $index; ?>">
                    <div class="answer-content">
                        <?php echo wp_kses_post($answer); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Contact CTA -->
    <div class="appendTop15 makeFlex center">
        <p class="font12 greyText appendRight10"><?php esc_html_e('Vous avez d\'autres questions ?', 'ajinsafro-tour-bridge'); ?></p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="primaryBtn fill btn btn-primary btn-sm borderRadius8 padding13">
            <?php esc_html_e('Contactez-nous', 'ajinsafro-tour-bridge'); ?>
        </a>
    </div>
</section>
