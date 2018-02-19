<?php
/**
 * Checkbox field markup
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/admin/partials
 */
?>

<input type="checkbox"
id="<?php echo esc_attr( $atts['id'] ); ?>"
name="<?php echo esc_attr( $atts['name'] ); ?>"
class="<?php echo esc_attr( $atts['class'] ); ?>"
value="1"
aria-role="checkbox"
<?php checked( 1, $atts['value'], true ); ?>
/>

<span class="description"><?php esc_html_e( $atts['description'], 'hierarchyfilter'); ?></span>