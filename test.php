<?php
$res = $wpdb->get_results('SHOW TABLES');
echo "<pre>\n";
print_r($res);
echo "\n</pre>\n";
?>

