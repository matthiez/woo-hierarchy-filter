<?php
global $wpdb;
$table = $wpdb->prefix . "hierarchyfilter";
$level_1 = $wpdb->get_results("SELECT DISTINCT(level_1) FROM $table");
$level_1_count = $wpdb->get_var("SELECT COUNT(DISTINCT level_1) FROM $table");
?>
<div class="wrap">
    <div id="hierarchyfilter-dropdown">
        <form action="<?= esc_url(admin_url('admin-post.php')); ?>" method="post">
            <?php if (1 == get_option('hierarchyfilter_shortcode_labels')): ?>
                <label for='hierarchyfilter-level-1'><?= get_option('hierarchyfilter_text_level_1') ?></label>
            <?php endif ?>
            <select id="hierarchyfilter-shortcode-level-1" name="hierarchyfilter-shortcode-level-1" data-level-1="<?php echo $level_1_count; ?>">
                <option value="" disabled selected><?= get_option('hierarchyfilter_text_level_1'); ?></option>
                <?php if ($level_1): ?>
                    <?php foreach ($level_1 as $key => $value): ?>
                        <option value="<?= $value->level_1 ?>"><?= $value->level_1 ?></option>
                    <?php endforeach ?>
                <?php else: ?>
                    <option><?= __('None found', 'hierarchyfilter') ?></option>
                <?php endif ?>
            </select>
            <?php if (1 == get_option('hierarchyfilter_shortcode_labels')): ?>
                <label for='hierarchyfilter-level-2'><?= get_option('hierarchyfilter_text_level_2') ?></label>
            <?php endif ?>
            <select id="hierarchyfilter-shortcode-level-2" name="hierarchyfilter-shortcode-level-2"></select>
            <?php if (1 == get_option('hierarchyfilter_shortcode_labels')): ?>
                <label for='hierarchyfilter-level-3'><?= get_option('hierarchyfilter_text_level_3') ?></label>
            <?php endif ?>
            <select id="hierarchyfilter-shortcode-level-3" name="hierarchyfilter-shortcode-level-3"></select>
            <input type="hidden" name="action" value="hierarchyfilter_shortcode_process_form"/>
            <input type="submit" id="hierarchyfilter-shortcode-submit" name="hierarchyfilter-shortcode-submit"
                   value="<?= get_option('hierarchyfilter_text_submit'); ?>">
            <?= wp_nonce_field('hierarchyfilter-shortcode-submit', 'hierarchyfilter-shortcode-submit-nonce'); ?>
        </form>
        <div id="hierarchyfilter-ajax"></div>
    </div>
</div>