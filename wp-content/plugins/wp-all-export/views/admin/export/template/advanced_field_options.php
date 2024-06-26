<div class="wp-all-export-advanced-field-options-content">
	<!-- Options for SQL field -->
	<div class="input cc_field sql_field_type" style="margin-left:25px;">
		<a href="#help" rel="sql" class="help" style="display:none;" title="<?php esc_html_e('%%ID%% will be replaced with the ID of the post being exported, example: SELECT meta_value FROM wp_postmeta WHERE post_id=%%ID%% AND meta_key=\'your_meta_key\';', 'wp_all_export_plugin'); ?>">?</a>
		<textarea style="width:100%;" rows="5" class="column_value"></textarea>										
	</div>
	<!-- Options for ACF Repeater field -->
	<div class="input cc_field repeater_field_type" style="margin-left:25px;">
		<input type="hidden" name="repeater_field_item_per_line" value="0"/>
		<input type="checkbox" id="repeater_field_item_per_line" class="switcher" name="repeater_field_item_per_line" value="1" style="margin: 2px;"/>
		<label for="repeater_field_item_per_line"><?php esc_html_e("Display each repeater row in its own csv line", "wp_all_export_plugin"); ?></label>
		<div class="input switcher-target-repeater_field_item_per_line" style="margin-top: 10px; padding-left: 15px;">
			<input type="hidden" name="repeater_field_fill_empty_columns" value="0"/>
			<input type="checkbox" id="repeater_field_fill_empty_columns" name="repeater_field_fill_empty_columns" value="1"/>
			<label for="repeater_field_fill_empty_columns"><?php esc_html_e("Fill in empty columns", "wp_all_export_plugin"); ?></label>
			<a href="#help" class="wpallexport-help" style="position: relative; top: 0px;" title="<?php esc_html_e('If enabled, each repeater row will appear as its own csv line with all post info filled in for every column.', 'wp_all_export_plugin'); ?>">?</a>
		</div>
	</div>
	<!-- Options for Image field from Media section -->
	<div class="input cc_field image_field_type" style="margin-left:25px;">
		<div class="input">
			<input type="hidden" name="image_field_is_export_featured" value="0"/>
			<input type="checkbox" id="is_image_export_featured" name="image_field_is_export_featured" value="1" style="margin: 2px;" checked="checked"/>
			<label for="is_image_export_featured"><?php esc_html_e("Export featured image", "wp_all_export_plugin"); ?></label>
		</div>
		<div class="input">
			<input type="hidden" name="image_field_is_export_attached_images" value="0"/>
			<input type="checkbox" id="is_image_export_attached_images" class="switcher" name="image_field_is_export_attached_images" value="1" style="margin: 2px;" checked="checked"/>
			<label for="is_image_export_attached_images"><?php esc_html_e("Export attached images", "wp_all_export_plugin"); ?></label>
			<div class="switcher-target-is_image_export_attached_images" style="margin: 5px 2px;">
				<label><?php esc_html_e("Separator", "wp_all_export_plugin"); ?></label>
				<input type="text" name="image_field_separator" value="|" style="width: 40px; text-align:center;">
			</div>
		</div>	
	</div>

	<!-- Options for Date field -->
	<div class="input cc_field wpae-select-field date_field_type" style="margin-left:25px;">
		<select class="date_field_export_data" style="width: 100%;">
			<option value="unix"><?php esc_html_e("UNIX timestamp - PHP time()", "wp_all_export_plugin");?></option>
			<option value="php"><?php esc_html_e("Natural Language PHP date()", "wp_all_export_plugin");?></option>
		</select>
		<div class="input pmxe_date_format_wrapper">
			<label style="padding:4px; display: block;"><?php esc_html_e("date() Format", "wp_all_export_plugin"); ?></label>
			<input type="text" class="pmxe_date_format" value="" placeholder="Y-m-d"/>
		</div>
	</div>		
	<!-- Options for Up/Cross sells products -->
	<div class="input cc_field linked_field_type" style="margin-left:25px;">
		<select class="linked_field_export_data" style="width: 100%; height: 30px;">
			<option value="sku"><?php esc_html_e("Product SKU", "wp_all_export_plugin");?></option>
			<option value="id"><?php esc_html_e("Product ID", "wp_all_export_plugin");?></option>
			<option value="name"><?php esc_html_e("Product Name", "wp_all_export_plugin");?></option>
		</select>				
	</div>
	<!-- PHP snippet options -->
	<div class="input php_snipped" style="margin-top:0;margin-left:24px;">
		<input type="checkbox" id="coperate_php" name="coperate_php" value="1" class="switcher" style="margin: 2px;"/>
		<label for="coperate_php"><?php esc_html_e("Export the value returned by a PHP function", "wp_all_export_plugin"); ?></label>
		<a href="#help" class="wpallexport-help" title="<?php esc_html_e('The value of the field chosen for export will be passed to the PHP function.', 'wp_all_export_plugin'); ?>" style="top: 0;">?</a>
		<div class="switcher-target-coperate_php" style="margin-top:5px;">
			<div class="wpallexport-free-edition-notice" style="margin: 15px 0;">
				<a class="upgrade_link" target="_blank" href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839967&edd_options%5Bprice_id%5D=1&utm_source=export-plugin-free&utm_medium=upgrade-notice&utm_campaign=custom-php"><?php esc_html_e('Upgrade to Pro to use Custom PHP Functions','wp_all_export_plugin');?></a>
			</div>
			<?php echo "&lt;?php ";?>
			<input type="text" class="php_code" value="" style="width:50%;" placeholder='your_function_name'/> 
			<?php echo "(\$value); ?&gt;"; ?>

			<?php
				$uploads = wp_upload_dir();
				$functions = $uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_EXPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';				
			?>
		</div>												
	</div>				
</div>