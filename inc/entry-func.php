<?php
function entry_scripts() {
    if(is_page_template('entry_form.php')){
        wp_enqueue_script('entry-js', get_template_directory_uri() . '/js/entry_form.js');
    }
}
add_action('wp_enqueue_scripts', 'entry_scripts');

function ev($key, $index=99, $type='text', $comp=0) {
	global $vals;
	if($index == 99){
		if(isset($vals[$key])){
			$r = $vals[$key];
		}
	}else{
    	if(isset($vals[$key][$index])){
        	$r = $vals[$key][$index];
		}
    }
	if(isset($r)){
		if($type == 'text') echo " value=\"$r\"";
		elseif($type == 'check') {
			if($r == $comp) echo ' checked';
		}
	}
}

function inval($key, $i=99) {
	global $invalid;
	if(($i == 99 && isset($invalid[$key])) || isset($invalid[$key][$i])){
		echo ' class="invalid"';
	}
}

function sanitize_id($id) {
    if(is_numeric($id)) {
        return (int) $id;
    } else {
        return 0;
    }
}

function entry_form() {
    global $vals;
?>
<div id="entryform">
<form action="" method="post">
<p>Název týmu&nbsp;&nbsp;<input type="text" name="team" size="30"<?php ev('team'); inval('team') ?> required></p>
<hr>
<?php
    for($i=0; $i<=1; $i++):
        if($i == 0){
            echo '<p><b>Biker(ka)</b></p>';
        }else{
            echo '<p><b>Parťák</b>&nbsp;&nbsp;&nbsp;<input type="checkbox"';
            ev('alone', 99, 'check', 'on');
            echo ' name="alone" id="alone" style="vertical-align: middle" onChange="javascript: ToggleSecond(document.getElementById(\'alone\').checked);"> Není - jedu sám</p>';
            echo "\n";
            echo '<div id="second_biker">';
        }
?>
<table class="formtable">
<tr><td>Jméno</td><td>Příjmení</td><td>Rok narození</td></tr>
<tr><td><input type="text" id="fname<?php echo $i?>" name="fname[<?php echo $i?>]" size="20"<?php ev('fname',$i); inval('fname', $i) ?> required></td>
<td><input type="text" id="sname<?php echo $i?>" name="sname[<?php echo $i?>]" size="20"<?php ev('sname',$i); inval('sname', $i) ?>></td>
<td><select name="ybirth[<?php echo $i?>]" required>
<?php for($j = 1950; $j <= 2010; $j++){
	echo "<option";
	if(isset($vals['ybirth'][$i])){
		if($vals['ybirth'][$i] == $j) echo " selected";
	}else{
		if($j == 1985) echo " selected";
	}
	echo ">$j</option>";
}?>
</select></td></tr>
<tr><td>Telefon</td><td>Email</td><td>Pohlaví</td></tr>
<tr><td><input type="text" name="phone[<?php echo $i?>]" size="20"<?php ev('phone',$i)?>></td>
<td><input type="email" name="email[<?php echo $i?>]" size="20"<?php ev('email',$i)?>></td>
<td><input type="radio" value="m" name="sex[<?php echo $i?>]"<?php ev('sex', $i, 'check', 'm'); inval('sex', $i) ?>>Muž
&nbsp;<input type="radio" value="w" name="sex[<?php echo $i?>]"<?php ev('sex', $i, 'check', 'w'); inval('sex', $i) ?>>Žena
</td></tr>
<tr><td>SHOCartLiga ID</td><td><?php if(get_theme_mod('entry_show_meal')) echo 'Guláš po dojezdu' ?></td><td></td></tr>
<tr><td><input type="text" name="shocart_id[<?php echo $i?>]" size="5"<?php ev('shocart_id',$i)?>></td>
<td>
<?php if(get_theme_mod('entry_show_meal')){
    echo '<input type="radio" value="1" name="meal[' . $i . ']"';
    ev('meal', $i, 'check', 1);
    echo '>Ano&nbsp;<input type="radio" value="0" name="meal[' . $i . ']"';
    ev('meal', $i, 'check', 0);
    echo '>Ne';
}
?>
</td>
<td></td>
</tr></table>
<hr>
<?php
    if($i > 0) echo '</div><!-- second_biker -->';
endfor;
?>
<p>Poznámka - cokoliv byste chtěli dodat<br>
<input type="text" name="comment" size="60"<?php echo ev('comment')?>></p>
<p>Heslo pro změny v přihlášce<br>
<input type="text" name="password"<?php echo ev('password'); inval('password') ?>></p>
<p><input type="submit" name="ok" value=" Odeslat "></p>
</form>
</div><!-- entryform -->
<script>
ToggleSecond(document.getElementById('alone').checked);
</script>
<?php
}
add_shortcode('entryform', 'entry_form');

function entry_list() {
    global $wpdb;
    $tb_prefix = get_theme_mod('entry_race_id');
    $table_t = $tb_prefix . '_team';
    $table_p = $tb_prefix . '_person';
    $entries = $wpdb->get_results("SELECT t.id, t.name, t.comment,
	  p0.fname as fname0, p0.sname as sname0, p0.sex as sex0, p0.meal as meal0,
	  p1.fname as fname1, p1.sname as sname1, p1.sex as sex1, p1.meal as meal1
      FROM `$table_t` t LEFT JOIN `$table_p` p0 ON p0.id = t.p0_id
      LEFT JOIN `$table_p` p1 ON p1.id = t.p1_id
      ORDER BY t.d_create, t.id",
      ARRAY_A);
    if($entries) {
        $url = get_template_directory_uri();
?>
<table id="entrylist">
<thead><tr>
<td class="number" width="5%">Číslo</td><td>Tým</td><td>1. závodník</td><td>2. závodník</td><td>Kat.</td><td>Poznámka</td><td class="links"></td><td class="links"></td>
</tr></thead>
<tbody>
<?php   $i = 1;
        foreach($entries as $entry):
            $scat = $entry['sex0'] . $entry['sex1'];
            switch($scat){
                case 'mw':
                case 'wm': $cat = 'MW'; break;
                case 'mm':
                case  'm': $cat = 'MM'; break;
                case 'ww':
                case  'w': $cat = 'WW'; break;
                default: $cat = 'XX';
            }
?>
<tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd' ?>">
<td class="number"><?php echo($i++) ?></td><td><?php echo $entry['name'] ?></td>
<td><?php echo $entry['fname0'] . " " . $entry['sname0'] ?></td>
<td><?php echo $entry['fname1'] . " " . $entry['sname1'] ?></td>
<td><?php echo $cat ?></td><td><?php echo $entry['comment'] ?></td>
<td class="links"><a href="zadejheslo.php?id=<?php echo $entry['id'] ?>"><img src="<?php echo $url ?>/img/edit.gif" title="Upravit" width="14" height="14" border="0"></a></td>
<td class="links"><a href="<?php echo home_url('_delete?id='. $entry['id']) ?>"><img src="<?php echo $url ?>/img/delete.gif" title="Smazat" width="14" height="14" border="0"></a></td>
</tr>
<?php   endforeach;?>
</tbody></table>
    <a href="<?php echo home_url('_error')?>">Error Page</a>
<?php
    }
}
add_shortcode('entrylist', 'entry_list');

function error_page() {
    echo '<div class="errmsg">';
    echo isset($_SESSION['error']) ? $_SESSION['error']
      : 'Vyskytla se neočekávaná chyba.';
    echo "</div>\n";
}
add_shortcode('error', 'error_page');

function delete_form() {
    if(isset($_REQUEST['id'])) {
        $id = sanitize_id($_REQUEST['id']);
        if($id > 0) {
            global $wpdb;
            $tb_prefix = get_theme_mod('entry_race_id'); $table_t = $tb_prefix . '_team'; $table_p = $tb_prefix . '_person';
            $entry = $wpdb->get_row("SELECT t.id, t.name, t.comment,
              p0.fname as fname0, p0.sname as sname0, p0.sex as sex0, p0.meal as meal0,
              p1.fname as fname1, p1.sname as sname1, p1.sex as sex1, p1.meal as meal1
              FROM `$table_t` t LEFT JOIN `$table_p` p0 ON p0.id = t.p0_id
              LEFT JOIN `$table_p` p1 ON p1.id = t.p1_id
              WHERE t.id = $id",
              ARRAY_A);
            if($entry) { ?>
<table id="entryshow">
<tr class="first"><td>Tým:</td><td><?php echo $entry['name'] ?></td></tr>
<tr><td>1. závodník:</td><td><?php echo $entry['fname0'] . '&nbsp;' . $entry['sname0'] ?></td></tr>
<tr><td>2. závodník:</td><td><?php echo $entry['fname1'] . '&nbsp;' . $entry['sname1'] ?></td></tr>
<tr><td>Poznámka:</td><td><?php echo $entry['comment'] ?></td></tr>
</table>
<form action="" method="post">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<p>Heslo:  <input type="text" name="pwd" /></p>
<p><input type="submit" name="pwdok" value=" Smazat! "></p>
</form>
<?php
                return;
            }
        }
    }
    echo '<div class="errmsg">Chybné, nebo neexistující ID záznamu.</div>';
}
add_shortcode('deleteform', 'delete_form');

?>
