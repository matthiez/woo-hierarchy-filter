<?php
global $wpdb;
$table      = $wpdb->prefix . "hierarchyfilter";
$table_data = $wpdb->get_results("SELECT * FROM $table;");
$row_count  = $wpdb->get_var("SELECT COUNT(*) FROM $table;");
?>
<div class ="wrap">
	<h1><?php _e('Database', 'hierarchyfilter'); ?></h1><p><?php echo $row_count . __(' database entries in total.', 'hierarchyfilter'); ?></p>
	<form method="POST">
		<table id="hierarchyfilter-admin-database-table" class="sortable">
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
				foreach ($table_data as $key => $row) {
					echo "<tr data-id='{$row->id}'>";
					echo "<td>{$row->id}</td>";
					echo "<td><input type='text' name='hierarchyfilter-edit-level-1[]' class='hierarchyfilter-edit-level-1' data-id='{$row->id}' value='{$row->level_1}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-edit-level-1-ids[]' class='hierarchyfilter-edit-level-1-ids' data-id='{$row->id}' value='{$row->id}'/>";
					
					echo "<td><input type='text' name='hierarchyfilter-edit-level-2[]' class='hierarchyfilter-edit-level-2' data-id='{$row->id}' value='{$row->level_2}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-edit-level-2-ids[]' value='{$row->id}'/>";
					
					echo "<td><input type='text' name='hierarchyfilter-edit-level-3[]' class='hierarchyfilter-edit-level-3' data-id='{$row->id}' value='{$row->level_3}'/></td>";
					echo "<input type='hidden' name='hierarchyfilter-edit-level-3-ids[]' value='{$row->id}'/>";
					
					echo "<td><input type='checkbox' name='hierarchyfilter-delete[]' class='hierarchyfilter-delete' value='{$row->id}'/></td>";
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
		</table>
		<?php //wp_nonce_field( 'hierarchyfilter-database-submit', 'hierarchyfilter-database-submit-nonce' ); ?>
	</form>
	<h2><?php _e('Event Log', 'hierarchyfilter'); ?></h2>
	<div id="hierarchyfilter-admin-database-ajax"></div>
</div>
