<div class ="wrap">
	<h2><?php _e('Add New', 'hierarchyfilter'); ?></h2>
	<form action="<?php //echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
		<!-- LEVEL 1 -->
		<p class ="hierarchyfilter-admin-add-new-input"><?php _e('Level 1', 'hierarchyfilter'); ?></br>
				<input type="text" name="hierarchyfilter-add-new-level-1" id="hierarchyfilter-add-new-level-1" placeholder="Example: Audi" value="" autofocus/>
		</p>
		<!-- LEVEL 2 -->
		<p class ="hierarchyfilter-admin-add-new-input" ><?php _e('Level 2', 'hierarchyfilter'); ?></br>
			<input type="text" name="hierarchyfilter-add-new-level-2" id="hierarchyfilter-add-new-level-2" placeholder="First specify a level 1 entry." value=""/>
			<span id="hierarchyfilter-admin-add-level-2-notice" class="hierarchyfilter-admin-add-new-notice">
				<?php _e('Specify a level 1 entry in order to unlock this field.', 'hierarchyfilter'); ?>
			</span>
		</p>
		<!-- LEVEL 3 -->
		<p class ="hierarchyfilter-admin-add-new-input"><?php _e('Level 3', 'hierarchyfilter'); ?></br>
			<input type="text" name="hierarchyfilter-add-new-level-3" id="hierarchyfilter-add-new-level-3" placeholder="First specify a level 2 entry." value=""/>
			<span id="hierarchyfilter-admin-add-level-3-notice" class="hierarchyfilter-admin-add-new-notice">
				<?php if (empty($_POST['hierarchyfilter-add-new-level-2'])) _e('Specify a level 2 entry in order to unlock this field.', 'hierarchyfilter'); ?>
			</span>
		</p>
		<input type="hidden" name="action" value="add_new">
		<!-- BUTTONS -->
		<input type="submit" class="button button-primary" name="hierarchyfilter-admin-add-new-submit" id="hierarchyfilter-admin-add-new-submit" value="Add New"/>
		<input type="reset" id="hierarchyfilter-admin-add-new-reset" value="Reset Fields"/>
		<input type="hidden" name="hierarchyfilter-admin-add-new-nonce" id="hierarchyfilter-admin-add-new-nonce" value="<?php echo wp_create_nonce('2305813681361')?>"/>
	</form>
	<div id="hierarchyfilter-admin-add-new-ajax"></div>
</div>
