<?php
/**
 * Select field markup
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/admin/partials
 */
?>

<input
        type="text"
        aria-role="text"
        aria-label="<?php esc_attr(_e($atts['aria_label'], 'hierarchyfilter')); ?>"
        class="<?php echo esc_attr($atts['class']); ?>"
        id="<?php echo esc_attr($atts['id']); ?>"
        name="<?php echo esc_attr($atts['name']); ?>"
        value="<?php echo get_option($atts['id']); ?>"
/>

<span class="description"><?php esc_html_e($atts['description'], 'hierarchyfilter'); ?></span>
