<?php
global $wpdb;
$table = $wpdb->prefix . "hierarchyfilter";
$dir = wp_upload_dir();
?>
    <div class="wrap">
        <h2><?php _e('Import', 'hierarchyfilter'); ?></h2>
        <h3><?php _e('CSV', 'hierarchyfilter'); ?></h3>
        <p>
            <?php _e('Please make sure that your file has the *.csv file extension.', 'hierarchyfilter'); ?></br>
            <?php _e('Import data must come in this format: level_1 data,level_2 data,level_3 data', 'hierarchyfilter'); ?></br>
            <?php _e('Example: Audi,A6 Avant (4A, C4),1.8 92 kW', 'hierarchyfilter'); ?>
        </p>
        <form method="POST" enctype="multipart/form-data">
            <input type='file' id='hierarchyfilter_import_csv' name='hierarchyfilter_import_csv'></input>
            <?php submit_button('Upload CSV') ?>
        </form>
    </div>
<?php
if (isset($_FILES['hierarchyfilter_import_csv'])) {
    $uploaded = media_handle_upload('hierarchyfilter_import_csv', 0); // 0 means the content is not associated with any other posts
    if (is_wp_error($uploaded)) {
        echo "Error uploading file: " . $uploaded->get_error_message();
    }
    else {
        $file = "{$dir['path']}/{$_FILES['hierarchyfilter_import_csv']['name']}";
        // die if not *.csv extension
        if ('csv' != pathinfo($file, PATHINFO_EXTENSION)) {
            die(__('The uploaded file needs the *.csv file extension.', 'hierarchyfilter'));
        }
        // show success message
        _e('*.CSV file successfully uploaded!', 'hierarchyfilter');
        echo "</br>";
        // die if more than 3 columns in the csv
        $csv = fopen($file, "r");
        while (!feof($csv)) {
            if (count(fgetcsv($csv)) !== 3) {
                die(__('Column mismatch: Please check the format of the CSV.', 'hierarchyfilter'));
            }
        }
        fclose($csv);
        // load into database
        $sql = "LOAD DATA LOCAL INFILE '" . $file . "' INTO TABLE $table FIELDS TERMINATED BY ',' (level_1,level_2,level_3)";
        $wpdb->query($sql);
        // show database errors
        if ($wpdb->last_error !== '') {
            $wpdb->print_error();
        }
        // add message with inserted row count
        $lines = count(file($file));
        _e("$lines entries successfully added.", 'hierarchyfilter');
        // delete the uploaded file
        unlink($file);
    }
}