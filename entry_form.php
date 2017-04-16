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
	if(empty($_REQUEST['team'])) $invalid['team'] = 1;
	for($i=0; $i < 2; $i++) {
		$reqs = array('fname', 'sname');
		foreach($reqs as $r) {
			if(empty($_REQUEST[$r][$i])) $invalid[$r][$i] = 1;
		}
		if($vals['alone'] == 'on') break;
	}
}

#$res = create_tables($wpdb, $tb_prefix);

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
	if(empty($invalid)) {
		echo "Input OK.\n";
	}else{
		print_r($invalid);
		echo "\nvals:\n";
		print_r($vals);
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
