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
<p><input type="submit" name="cancel" value=" Zrušit " formnovalidate>&nbsp;&nbsp;&nbsp;<input type="submit" name="ok" value=" Odeslat "></p>
</form>
</div><!-- entryform -->
<script>
ToggleSecond(document.getElementById('alone').checked);
</script>
<?php
}
add_shortcode('entryform', 'entry_form');

function tables_init(){
	$prefix = get_theme_mod('entry_race_id');
	if(empty($prefix)){
		echo '<div class="errmsg">Není vyplněn identifikátor závodu.</div>';
	}
	if(create_tables($prefix) == 0){
		echo '<div class="errmsg">Tabulky se nepodařilo vytvořit.</div>';
	}else{
		echo '<div class="okmsg">Tabulky vytvořeny.</div>';
	}
}
add_shortcode('tbinit', 'tables_init');

?>
