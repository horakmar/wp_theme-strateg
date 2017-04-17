<?php
/**
 * The template for displaying entry form
 *
 * Template Name: EntryForm
 *
 * @package strateg
 */
$tb_prefix = get_theme_mod('entry_race_id');

$invalid = array();
if(isset($_REQUEST['ok'])) { 	// Form submitted
	$vals = $_REQUEST;
	if(! isset($_REQUEST['alone'])) $vals['alone'] = 'off';

	// Validation
	if(empty($vals['team'])) $invalid['team'] = 1;
	if(empty($vals['password'])) $invalid['password'] = 1;
	for($i=0; $i < 2; $i++) {
		$reqs = array('fname', 'sname', 'sex'); // Required items
		foreach($reqs as $r) {
			if(empty($vals[$r][$i])) $invalid[$r][$i] = 1;
		}
		if($vals['alone'] == 'on') break;
	}

	if(empty($invalid)){
		if(! isset($vals['action'])) $vals['action'] = 'new';
		$team_id = 0;
		$table = $tb_prefix . '_team';
		$data = [];
		$data['name'] = $vals['team'];
		$fields = ['comment', 'password'];
		foreach($fields as $f) {
			$data[$f] = $vals[$f];
		}
		if($vals['action'] == 'new'){
			$data['d_create'] = date('Y-m-d H:i:s');
//			echo "Insert:\n";
//			print_r($data);
			$wpdb->insert($table, $data);
			$team_id = $wpdb->insert_id;
//			echo "\nID = $team_id\n";
		}

		$fields = ['fname','sname','ybirth','sex','phone','email','shocart_id'];
		if(get_theme_mod('entry_show_meal')) $fields[] = 'meal';
		$table = $tb_prefix . '_person';
		for($i=0; $i < 2; $i++) {
			$data = [];
			foreach($fields as $f) {
				$data[$f] = $vals[$f][$i];
			}
			$data['team_id'] = $team_id;
			$data['password'] = $vals['password'];
			if($vals['action'] == 'new'){
//				echo "Insert:\n";
//				print_r($data);
				$wpdb->insert($table, $data);
			}
			if($vals['alone'] == 'on') break;
		}
		wp_redirect(get_home_url());
		exit;
	}
}

#$res = create_tables($wpdb, $tb_prefix);

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
	if(! empty($invalid)) {
		echo '<div class="errmsg">Nejsou vyplněna povinná pole</div>';
	}
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content', 'page' );
			endwhile; // End of the loop.
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
/*
echo "<pre>\n";
echo("site URL = ". site_url(). "\nhome URL = ". home_url(). "\n");
echo("admin URL = ". admin_url(). "\ncontent URL = ". content_url(). "\n");
echo("plugins URL = ". plugins_url(). "\nincludes URL = ". includes_url(). "\n");
echo("permalink = ". get_permalink(). "\npermalink = ". get_permalink($post->ID). "\n");
$res = $wpdb->get_results('SHOW TABLES');
print_r($res);
echo "</pre>\n";
 */

get_sidebar();
get_footer();
?>
