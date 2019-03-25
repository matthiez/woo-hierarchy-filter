<?php
/**
 * Select field markup
 *
 * @link       https://github.com/matthiez/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/admin/partials
 */
?>

<select
        aria-role="select"
        aria-label="<?php esc_attr(_e($atts['aria_label'], 'hierarchyfilter')); ?>"
        class="<?php echo esc_attr($atts['class']); ?>"
        id="<?php echo esc_attr($atts['id']); ?>"
        name="<?php echo esc_attr($atts['name']); ?>"
>
    <?php
    if (!empty($atts['blank'])) { ?>
        <option value><?php esc_html_e($atts['blank'], 'hierarchyfilter'); ?></option> <?php }
    foreach ($atts['options'] as $option) {
        $label = ($option);
        $value = strtolower($option);
        ?>
    <option
            value="<?php echo esc_attr($value); ?>" <?php
    selected(get_option($atts['id']), $value, true); ?>
    >
        <?php esc_html_e($label, 'hierarchyfilter'); ?>
        </option><?php
    }
    ?>
</select>

<span class="description"><?php esc_html_e($atts['description'], 'hierarchyfilter'); ?></span>