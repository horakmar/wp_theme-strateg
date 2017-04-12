<?php
/**
 * The template for displaying entry form
 *
 * Template Name: EntryForm
 *
 * @package strateg
 */
$tb_prefix = get_theme_mod('entry_race_id');
#$res = create_tables($wpdb, $tb_prefix);

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
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
