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
if(! isset($_REQUEST['action'])) $_REQUEST['action'] = 'new';

if(isset($_REQUEST['pwdok'])) {     // Password form submitted
    $vals = [];
    if(pwd_check($_REQUEST['id'], $_REQUEST['pwd'])){
        $team = $wpdb->get_row("SELECT * FROM {$tb_prefix}_team WHERE id = {$_REQUEST['id']}", ARRAY_A); // TODO escape
        $vals['team'] = $team['name'];
        foreach(['comment', 'password'] as $f) {
            $vals[$f] = $team[$f];
        }
        $persons = $wpdb->get_results( "SELECT * FROM {$tb_prefix}_person WHERE team_id = {$_REQUEST['id']}", ARRAY_A); // TODO escape
        $i = 0;
        foreach($persons as $p) {
            foreach(['fname','sname','ybirth','sex','phone','email','shocart_id','meal'] as $f) {
                $vals[$f][$i] = $p[$f];
            }
            $i++;
            if($i > 1) break;
        }
    }
} elseif(isset($_REQUEST['ok'])) { 	// Input form submitted
	// Validation
	if(empty($_REQUEST['team'])) $invalid['team'] = 1;
	if(empty($_REQUEST['password'])) $invalid['password'] = 1;
	for($i=0; $i < 2; $i++) {
		$reqs = array('fname', 'sname', 'sex'); // Required items
		foreach($reqs as $r) {
			if(empty($_REQUEST[$r][$i])) $invalid[$r][$i] = 1;
		}
		if(req('alone') == 'on') break;
	}

	if(empty($invalid)){
		$table = $tb_prefix . '_person';
        $ids = [NULL, NULL];
		$fields = ['fname','sname','ybirth','sex','phone','email','shocart_id'];
		for($i=0; $i < 2; $i++) {
			$data = [];
			foreach($fields as $f) {
				$data[$f] = $_REQUEST[$f][$i];
			}
            $data['meal'] = isset($_REQUEST['meal'][$i]) ? $_REQUEST['meal'][$i] : 0;
			$data['password'] = $_REQUEST['password'];
			if($_REQUEST['action'] == 'new'){
//  			echo "Insert:\n<pre>";
//				print_r($data);
//              echo "</pre>";
                $wpdb->insert($table, $data);
                $ids[$i] = $wpdb->insert_id;
			}
			if(isset($_REQUEST['alone']) && $_REQUEST['alone'] == 'on') break;
		}

		$table = $tb_prefix . '_team';
		$data = [];
		$data['name'] = $_REQUEST['team'];
		$fields = ['comment', 'password'];
		foreach($fields as $f) {
			$data[$f] = $_REQUEST[$f];
		}
        $data['p1_id'] = $ids[0];
        $data['p2_id'] = $ids[1];
		if($_REQUEST['action'] == 'new'){
			$data['d_create'] = date('Y-m-d H:i:s');
//			echo "Insert:\n<pre>";
//			print_r($data);
//          echo "</pre>";
			$wpdb->insert($table, $data);
		}

		wp_redirect(get_home_url()); // TODO
		exit;
    } else {    // Invalid form - redisplay
        $vals = $_REQUEST;
    }
} // Form submitted end


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
