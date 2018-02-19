<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/arivelox/
 * @since      1.0.0
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    HierarchyFilter
 * @subpackage HierarchyFilter/admin
 * @author     Andre Matthies <matthiez@googlemail.com>
 */

class HierarchyFilter_Admin {
	
	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/hierarchyfilter-admin.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/hierarchyfilter-admin.js', array('jquery'), $this->version, false);
	}
	
	public function hierarchyfilter_add_admin_menu() {
		$page_title 	    = 'HierarchyFilter Settings';
		$menu_title		    = 'HierarchyFilter';
		$capability		    = 'manage_options';
		$menu_slug  	    = 'hierarchyfilter_settings';
		$callback_function  = array($this, 'hierarchyfilter_render_settings');
		$icon_url 		    = ''; // icon URL
		$position 		    =	''; // position
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback_function, $icon_url, $position);
		$submenu_page_title = 'HierarchyFilter: Settings';
		$submenu_menu_title = 'Settings';
		$submenu_menu_slug  = 'hierarchyfilter_settings';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_menu_title, $capability, $submenu_menu_slug);
		$submenu_page_title = 'HierarchyFilter: Add new';
		$submenu_menu_title = 'Add New';
		$submenu_menu_slug  = 'hierarchyfilter_add_new';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_menu_title, $capability, $submenu_menu_slug, array($this, 'hierarchyfilter_render_add_new'));
		$submenu_page_title = 'HierarchyFilter: Database';
		$submenu_menu_title = 'Database';
		$submenu_menu_slug  = 'hierarchyfilter_database';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_menu_title, $capability, $submenu_menu_slug, array($this, 'hierarchyfilter_render_database'));
		$submenu_page_title = 'HierarchyFilter: Import';
		$submenu_menu_title = 'Import';
		$submenu_menu_slug  = 'hierarchyfilter_import';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_menu_title, $capability, $submenu_menu_slug, array($this, 'hierarchyfilter_render_import'));
	}
	
	public function hierarchyfilter_register_fields() {
		add_settings_field(
			'hierarchyfilter_cookie_name', // id tag
			__( 'Cookie Name', 'hierarchyfilter' ), // field title
			array( $this, 'text_callback' ), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_general', // section (optional)
			array( // arguments (optional)
				'id' 			=> 'hierarchyfilter_cookie_name',
				'name' 			=> 'hierarchyfilter_cookie_name',
				'value' 		=> get_option( 'hierarchyfilter_cookie_name' ),
				'description' 	=> 'The cookie name. Should be somehow related to your filter, but will not be shown anywhere on the site.',
			)
		);
		add_settings_field(
			'hierarchyfilter_shortcode_labels', // id tag
			__( 'Shortcode Labels', 'hierarchyfilter' ), // field title
			array( $this, 'checkbox_callback' ), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_shortcode', // section (optional)
			array( // arguments (optional)
				'id' 			=> 'hierarchyfilter_shortcode_labels',
				'name' 			=> 'hierarchyfilter_shortcode_labels',
				'value' 		=> get_option( 'hierarchyfilter_shortcode_labels' ),
				'description' 	=> 'Show labels in shortcode',
			)
		);
		add_settings_field(
			'hierarchyfilter_widget_labels', // id tag
			__( 'Widget Labels', 'hierarchyfilter' ), // field title
			array( $this, 'checkbox_callback' ), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_widget', // section (optional)
			array( // arguments (optional)
				'id' 			=> 'hierarchyfilter_widget_labels',
				'name' 			=> 'hierarchyfilter_widget_labels',
				'value' 		=> get_option( 'hierarchyfilter_widget_labels' ),
				'description' 	=> 'Show labels in widget',
			)
		);
		add_settings_field(
			'hierarchyfilter_product_tab', // id tag
			__( 'Product Data Tab', 'hierarchyfilter' ), // field title
			array( $this, 'checkbox_callback' ), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_general', // section (optional)
			array( // arguments (optional)
				'id' 			=> 'hierarchyfilter_product_tab',
				'name' 			=> 'hierarchyfilter_product_tab',
				'value' 		=> get_option( 'hierarchyfilter_product_tab' ),
				'description' 	=> 'Enable additional product data tab',
			)
		);
		add_settings_field(
			'hierarchyfilter_product_tab_thead', // id tag
			__( 'Table Header', 'hierarchyfilter' ), // field title
			array( $this, 'checkbox_callback' ), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_general', // section (optional)
			array( // arguments (optional)
				'id' 			=> 'hierarchyfilter_product_tab_thead',
				'name' 			=> 'hierarchyfilter_product_tab_thead',
				'value' 		=> get_option( 'hierarchyfilter_product_tab_thead' ),
				'description' 	=> 'Show the table header on product data tab',
			)
		);
		add_settings_field(
			'hierarchyfilter_text_product_tab', // id tag
			__('Product Tab', 'hierarchyfilter'), // field title
			array( $this, 'text_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_text', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "Alternative string to display as the product data tab text",
				'id' 			=> "hierarchyfilter_text_product_tab",
				'name' 			=> "hierarchyfilter_text_product_tab"
			)
		);
		add_settings_field(
			'hierarchyfilter_text_product_tab_header', // id tag
			__('Product Tab H2', 'hierarchyfilter'), // field title
			array( $this, 'text_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_text', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "Alternative string to display as the product data tab text",
				'id' 			=> "hierarchyfilter_text_product_tab_header",
				'name' 			=> "hierarchyfilter_text_product_tab_header"
			)
		);
		add_settings_field(
			'hierarchyfilter_text_level_1', // id tag
			__('Level 1', 'hierarchyfilter'), // field title
			array( $this, 'text_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_text', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "Alternative string for 'Level 1'",
				'id' 			=> "hierarchyfilter_text_level_1",
				'name' 			=> "hierarchyfilter_text_level_1"
			)
		);
		add_settings_field(
			'hierarchyfilter_text_level_2', // id tag
			__('Level 2', 'hierarchyfilter'), // field title
			array( $this, 'text_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_text', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "Alternative string for 'Level 2'",
				'id' 			=> "hierarchyfilter_text_level_2",
				'name' 			=> "hierarchyfilter_text_level_2"
			)
		);
		add_settings_field(
			'hierarchyfilter_text_level_3', // id tag
			__('Level 3', 'hierarchyfilter'), // field title
			array( $this, 'text_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_text', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "Alternative string for 'Level 3'",
				'id' 			=> "hierarchyfilter_text_level_3",
				'name' 			=> "hierarchyfilter_text_level_3"
			)
		);
		add_settings_field(
			'hierarchyfilter_text_submit', // id tag
			__('Submit', 'hierarchyfilter'), // field title
			array( $this, 'text_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_text', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "String for 'Submit' button",
				'id' 			=> "hierarchyfilter_text_submit",
				'name' 			=> "hierarchyfilter_text_submit"
			)
		);
		add_settings_field(
			'hierarchyfilter_select_setting', // id tag
			__('hierarchyfilter_select_setting', 'hierarchyfilter'), // field title
			array( $this, 'select_callback'), // callback function
			'hierarchyfilter_settings', // menu page to display
			'hierarchyfilter_settings', // section (optional)
			array(
				'class' 			=> "",
				'description' 	=> "The string to display as the product data tab text",
				'id' 			=> "hierarchyfilter_select_setting",
				'name' 			=> "hierarchyfilter_select_setting",
				'options'		=> array('value' => 'Danish', 'English', 'German')
			)
		);
	}
	
	public function hierarchyfilter_register_sections() {
		add_settings_section(
			'hierarchyfilter_general', // id tag
			__('General', 'hierarchyfilter'), // section title
			null, // callback function
			'hierarchyfilter_settings' // menu page to display
		);
		add_settings_section(
			'hierarchyfilter_shortcode', // id tag
			__('Shortcode', 'hierarchyfilter'), // section title
			null, // callback function
			'hierarchyfilter_settings' // menu page to display
		);
		add_settings_section(
			'hierarchyfilter_widget', // id tag
			__('Widget', 'hierarchyfilter'), // section title
			null, // callback function
			'hierarchyfilter_settings' // menu page to display
		);
		add_settings_section(
			'hierarchyfilter_text', // id tag
			__('Text', 'hierarchyfilter'), // section title
			null, // callback function
			'hierarchyfilter_settings' // menu page to display
		); 
	}
	
	public function hierarchyfilter_register_settings() {
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_cookie_name', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_shortcode_labels', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_widget_labels', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_product_tab', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_product_tab_thead', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_text_product_tab', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_text_product_tab_header', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_text_level_1', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_text_level_2', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_text_level_3', '');
		register_setting('hierarchyfilter_settings', 'hierarchyfilter_text_submit', '');
	}
	
	public function checkbox_callback($args) {
		$defaults['value'] = 0;
		apply_filters('hierarchyfilter-checkbox-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if (!empty($this->options[$atts['id']])) { $atts['value'] = $this->options[$atts['id']]; }
		include(plugin_dir_path(__FILE__) . 'partials/hierarchyfilter-admin-checkbox.php');
	}
	
	public function select_callback($args) {
		$defaults['options'] = array();
		apply_filters('hierarchyfilter-select-options-defaults', $defaults );
		$atts = wp_parse_args( $args, $defaults );
		if (!empty( $this->options[$atts['id']])) { $atts['value'] = $this->options[$atts['id']]; }
		if (empty( $atts['aria_label'] ) && !empty( $atts['description'] ) ) { $atts['aria_label'] = $atts['description']; } 
		elseif (empty($atts['aria_label']) && !empty( $atts['label'])) { $atts['aria_label'] = $atts['label']; }
		include(plugin_dir_path(__FILE__) . 'partials/hierarchyfilter-admin-select.php');
	}
	
	public function text_callback($args) {
		$defaults['options'] = array();
		$atts = wp_parse_args( $args, $defaults );
		if (empty($atts['aria_label']) && !empty($atts['description'])) { $atts['aria_label'] = $atts['description']; }
		include(plugin_dir_path(__FILE__) . 'partials/hierarchyfilter-admin-text.php');
	}
	
	public function hierarchyfilter_editor_callback() {
		$text = get_option('hierarchyfilter_text');
		wp_editor($text, 'hierarchyfilter_text', $settings = array());
	}
	
	public function hierarchyfilter_render_settings() {
		include 'partials/hierarchyfilter-admin-settings.php';
	}
	
	public function hierarchyfilter_render_add_new() {
		include 'partials/hierarchyfilter-admin-add-new.php';
	}
	
	public function hierarchyfilter_render_database() {
		include 'partials/hierarchyfilter-admin-database.php';
	}
	
	public function hierarchyfilter_render_import() {
		include 'partials/hierarchyfilter-admin-import.php';
	}

	public function hierarchyfilter_meta_box_single_register() {
		add_meta_box('hierarchyfilter-meta-box-single', __('HierarchyFilter', 'hierarchyfilter'), array($this, 'hierarchyfilter_meta_box_single_content'), 'product', 'side', 'high', null);
	}
	
	public function hierarchyfilter_meta_box_single_content() {
		global $wpdb;
		$table      = $wpdb->prefix . "hierarchyfilter";
		$table_data = $wpdb->get_results("SELECT * FROM $table;");
		$post_meta = get_post_meta(get_the_ID(), 'hierarchyfilter_assosciations', true);
		if (!is_array($post_meta)) { $post_meta = array(); }
		echo "<label for ='hierarchyfilter_assosciations_single'>" . __('Assosciations', 'hierarchyfilter') . "</label>";
		echo "<select id='hierarchyfilter_assosciations_single' name='hierarchyfilter_assosciations_single[]' class='' multiple='multiple'>";
		if ($table_data) {
			foreach ($table_data as $key => $value) {
				echo "<option value='" . $value->id . "'" . ((in_array($value->id, $post_meta)) ? 'selected="selected"' : '') . ">". $value->level_1 . " " . $value->level_2 . " " . $value->level_3 ."</option>";
			}
		}
		else echo "<option>" . __('None found', 'hierarchyfilter') . "</option>";
		echo "</select>";
		if (!wc_get_product(get_the_ID())->is_type( 'simple' )) {
			echo "<p>" . __('Variable product: Setting assosciations here will overwrite specific variant assosciations.', 'hierarchyfilter') . "</p>";
		}
	}
	
	public function hierarchyfilter_meta_box_single_save() {
		$single = $_POST['hierarchyfilter_assosciations_single'];
		if (isset($single)) update_post_meta( get_the_ID(), 'hierarchyfilter_assosciations', $single );
	}
	
	public function hierarchyfilter_variations_metabox($loop, $variation_data, $variation) {
		global $wpdb, $product;
		$table      = $wpdb->prefix . "hierarchyfilter";
		$table_data = $wpdb->get_results("SELECT * FROM $table;");
		$post_meta = get_post_meta($variation->ID, 'hierarchyfilter_assosciations', true);
		if (!is_array($post_meta)) { $post_meta = array(); }
		echo "<label for ='hierarchyfilter_assosciations_variations'>" . __('Variation Assosciations', 'hierarchyfilter') . "</label>";
		echo "<select id='hierarchyfilter_assosciations_variations' name='hierarchyfilter_assosciations_variations[]' multiple='multiple'>";
		if ($table_data) {
			foreach ($table_data as $key => $value) {
				echo "<option value='" . $value->id . "'" . ((in_array($value->id, $post_meta)) ? 'selected="selected"' : '') . ">". $value->level_1 . " " . $value->level_2 . " " . $value->level_3 ."</option>";
			}
		}
		else echo "<option>" . __('None found', 'hierarchyfilter') . "</option>";
		echo "</select>";
	}
	
	public function hierarchyfilter_variations_metabox_save($variation_id) {
		$variation = $_POST['hierarchyfilter_assosciations_variations'];
		if (isset($variation)) {
			update_post_meta( $variation_id, 'hierarchyfilter_assosciations', $variation );
		}
	}
	
	public function hierarchyfilter_allow_sql_uploads($mime_types) {
		$mime_types['sql'] = 'text/x-sql';
		return $mime_types;
	}
	
	public function hierarchyfilter_ajax_add_new() {
		check_ajax_referer('2305813681361', 'security');
		if (!empty($_POST['hierarchyfilter_add_new_level_1'])) { $level_1 = sanitize_text_field($_POST['hierarchyfilter_add_new_level_1']); }
		if (!empty($_POST['hierarchyfilter_add_new_level_2'])) { $level_2 = sanitize_text_field($_POST['hierarchyfilter_add_new_level_2']); }
		if (!empty($_POST['hierarchyfilter_add_new_level_3'])) { $level_3 = sanitize_text_field($_POST['hierarchyfilter_add_new_level_3']); }
		if (empty($level_1) || empty($level_2) || empty($level_3)) { die(__('No field can be empty..', 'hierarchyfilter')); }
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		if ($wpdb->insert($table, array('level_1' => $level_1, 'level_2' => $level_2, 'level_3' => $level_3)) === FALSE) echo '<strong>ERROR!</strong></br>';
		else echo "<strong>SUCCESS:</strong> {$level_1} {$level_2} {$level_3} added!";
		wp_die();
	}
	
	public function hierarchyfilter_ajax_admin_database_delete() {
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		if (isset($_POST['hierarchyfilter_admin_database_delete'])) {
			$ids = $_POST['hierarchyfilter_admin_database_delete'];
			if (count($ids) == 0) { die(__('No input values specified.', 'hierarchyfilter')); }
			foreach ($ids as $id) {
				$wpdb->prepare($wpdb->delete($table, array('id' => $id), array('%d')));
			}
			?>
			<thead>
				<tr>
					<th class="hierarchyfilter-admin-database-table-th"><?php _e('ID', 'hierarchyfilter'); ?></th>
					<th class="hierarchyfilter-admin-database-table-th"><?php echo get_option('hierarchyfilter_text_level_1'); ?></th>
					<th class="hierarchyfilter-admin-database-table-th"><?php echo get_option('hierarchyfilter_text_level_2'); ?></th>
					<th class="hierarchyfilter-admin-database-table-th"><?php echo get_option('hierarchyfilter_text_level_3'); ?></th> 
					<th class="hierarchyfilter-admin-database-table-th sorttable_nosort"><?php _e('X', 'hierarchyfilter'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$table_data = $wpdb->get_results("SELECT * FROM $table;");
				foreach ($table_data as $key => $row) {
					echo "<tr>";
					echo "<td>{$row->id}</td>";
					echo "<td><input type='text' name='hierarchyfilter-edit-level-1[]' class='hierarchyfilter-edit-level-1' data-id='{$row->id}' value='{$row->level_1}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-edit-level-1-ids[]' value='{$row->id}'/>";
					
					echo "<td><input type='text' name='hierarchyfilter-edit-level-2[]' class='hierarchyfilter-edit-level-2' data-id='{$row->id}' value='{$row->level_2}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-edit-level-2-ids[]' value='{$row->id}'/>";
					
					echo "<td><input type='text' name='hierarchyfilter-edit-level-3[]' class='hierarchyfilter-edit-level-3' data-id='{$row->id}' value='{$row->level_3}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-edit-level-3-ids[]' value='{$row->id}'/>";
					
					echo "<td><input type='checkbox' name='hierarchyfilter-delete' class='hierarchyfilter-delete' value='{$row->id}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-delete-ids[]' id='hierarchyfilter-delete-ids[]' value=''/>";
					echo "</tr>";
				} ?>
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><?php submit_button( 'Delete', 'secondary', 'hierarchyfilter-admin-database-delete-submit' ); ?></td>
			</tfoot>
			<?php
			wp_die();
		}
	}
	
	public function hierarchyfilter_ajax_admin_database_edit() {
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		$level_1_values = $_POST['hierarchyfilter_admin_database_edit_level_1'];
		$level_1_keys = $_POST['hierarchyfilter_admin_database_edit_level_1_ids'];
		$combined = array_combine($level_1_keys, $level_1_values);
		foreach ($combined as $key => $value) $wpdb->update($table, array('level_1' => $value), array('id' => $key), array('%s'), array('%d'));
		?>
		<thead>
			<tr>
				<th class="hierarchyfilter-admin-database-table-th"><?php _e('ID', 'hierarchyfilter'); ?></th>
				<th class="hierarchyfilter-admin-database-table-th"><?php echo get_option('hierarchyfilter_text_level_1'); ?></th>
				<th class="hierarchyfilter-admin-database-table-th"><?php echo get_option('hierarchyfilter_text_level_2'); ?></th>
				<th class="hierarchyfilter-admin-database-table-th"><?php echo get_option('hierarchyfilter_text_level_3'); ?></th> 
				<th class="hierarchyfilter-admin-database-table-th sorttable_nosort"><?php _e('X', 'hierarchyfilter'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$table_data = $wpdb->get_results("SELECT * FROM $table;");
			foreach ($table_data as $key => $row): ?>
                <tr>
                    <td><?= $row->id ?></td>
                    <td><input type='text' name='hierarchyfilter-edit-level-1[]' class='hierarchyfilter-edit-level-1' data-id='{$row->id}' value='<?= $row->level_1 ?>'/></td>
                    <input type='hidden' name='hierarchyfilter-edit-level-1-ids[]' value='<?= $row->id ?>'/>

                    <td><input type='text' name='hierarchyfilter-edit-level-2[]' class='hierarchyfilter-edit-level-2' data-id='{$row->id}' value='<?= $row->level_2 ?>'/></td>
                    <input type='hidden' name='hierarchyfilter-edit-level-2-ids[]' value='<?= $row->id ?>'/>

                    <td><input type='text' name='hierarchyfilter-edit-level-3[]' class='hierarchyfilter-edit-level-3' data-id='{$row->id}' value='<?= $row->level_3 ?>'/></td>
                    <input type='hidden' name='hierarchyfilter-edit-level-3-ids[]' value='<?= $row->id ?>}'/>

                    <td><input type='checkbox' name='hierarchyfilter-delete' class='hierarchyfilter-delete' value='<?= $row->id ?>'/></td>
                    <input type='hidden' name='hierarchyfilter-delete-ids[]' id='hierarchyfilter-delete-ids[]' value=''/>
                </tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php submit_button( 'Delete', 'secondary', 'hierarchyfilter-admin-database-delete-submit' ); ?></td>
		</tfoot>
		<?php
		wp_die();
	}
	
	public function hierarchyfilter_ajax_admin_database_edit_level_1() {
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		$level_1 = $_POST['level_1'];
		$level_1_id = $_POST['level_1_id'];
		$wpdb->update($table, array('level_1' => $level_1), array('id' => $level_1_id), array('%s'), array('%d'));
		$prepared_statement = $wpdb->prepare( "SELECT level_1 FROM {$table} WHERE  id = %d", $level_1_id );
		$level_1 = $wpdb->get_col($prepared_statement);
		echo "<td><input type='text' name='hierarchyfilter-edit-level-1[]' class='hierarchyfilter-edit-level-1' data-id='{$level_1_id}' value='{$level_1[0]}'/></td>";
	}
	
	public function hierarchyfilter_ajax_admin_database_edit_level_2() {
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		$level_2 = $_POST['level_2'];
		$level_2_id = $_POST['level_2_id'];
		$wpdb->update($table, array('level_2' => $level_2), array('id' => $level_2_id), array('%s'), array('%d'));
		$prepared_statement = $wpdb->prepare( "SELECT level_2 FROM {$table} WHERE  id = %d", $level_2_id );
		$level_2 = $wpdb->get_col($prepared_statement);
		echo "<td><input type='text' name='hierarchyfilter-edit-level-2[]' class='hierarchyfilter-edit-level-2' data-id='{$level_2_id}' value='{$level_2[0]}'/></td>";
	}
	
	public function hierarchyfilter_ajax_admin_database_edit_level_3() {
		global $wpdb;
		$table = $wpdb->prefix . "hierarchyfilter";
		$level_3 = $_POST['level_3'];
		$level_3_id = $_POST['level_3_id'];
		$wpdb->update($table, array('level_3' => $level_3), array('id' => $level_3_id), array('%s'), array('%d'));
		$prepared_statement = $wpdb->prepare( "SELECT level_3 FROM {$table} WHERE  id = %d", $level_3_id );
		$level_3 = $wpdb->get_col($prepared_statement);
		echo "<td><input type='text' name='hierarchyfilter-edit-level-3[]' class='hierarchyfilter-edit-level-3' data-id='{$level_3_id}' value='{$level_3[0]}'/></td>";
	}
}