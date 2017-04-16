<?php
function entry_scripts() {
    if(is_page_template('entry_form.php')){
        wp_enqueue_script('entry-js', get_template_directory_uri() . '/js/entry_form.js');
    }
}
add_action('wp_enqueue_scripts', 'entry_scripts');

function ev($key, $index=99) {
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
	if(isset($r)) echo " value=\"$r\"";
}

function entry_form() {
    global $vals;
?>
<div id="entryform">
<form action="" method="post">
<p>Název týmu&nbsp;&nbsp;<input class="shadbox" type="text" name="team" size="30"<?php ev('team') ?> required></p>
<hr>
<?php
    for($i=0; $i<=1; $i++):
        if($i == 0){
            echo '<p><b>Biker(ka)</b></p>';
        }else{
            echo '<p><b>Parťák</b>&nbsp;&nbsp;&nbsp;<input type="checkbox"';
            if($vals['alone'] == 'on') echo 'checked';
            echo ' name="alone" id="alone" style="vertical-align: middle" onChange="javascript: ToggleSecond(document.getElementById(\'alone\').checked);"> Není - jedu sám</p>';
            echo "\n";
            echo '<div id="second_biker"';
            if($vals['alone'] == 'on') echo ' style="display:none"';
            echo '>';
        }
?>
<table class="formtable">
<tr><td>Jméno</td><td>Příjmení</td><td>Rok narození</td></tr>
<tr><td><input type="text" id="fname<?php echo $i?>" name="fname[<?php echo $i?>]" size="20"<?php ev('fname',$i)?> required></td>
<td><input type="text" id="sname<?php echo $i?>" name="sname[<?php echo $i?>]" size="20"<?php ev('sname',$i)?>></td>
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
<td><input type="radio" value="m" name="sex[<?php echo $i?>]"<?php if(isset($vals['sex'][$i]) && $vals['sex'][$i] == 'm'){ echo "checked"; }?>>Muž
&nbsp;<input type="radio" value="z" name="sex[<?php echo $i?>]"<?php if(isset($vals['sex'][$i]) && $vals['sex'][$i] == 'z'){ echo "checked"; }?>>Žena
</td></tr>
<tr><td>SHOCartLiga ID</td><td><?php if(get_theme_mod('entry_show_meal')) echo 'Guláš po dojezdu' ?></td><td></td></tr>
<tr><td><input type="text" name="shocart_id[<?php echo $i?>]" size="5"<?php ev('shocart_id',$i)?>></td>
<td>
<?php if(get_theme_mod('entry_show_meal')){
    echo '<input type="radio" value="1" name="meal[' . $i . ']"';
    if($vals['meal'][$i] > 0) echo 'checked';
    echo '>Ano&nbsp;<input type="radio" value="0" name="meal[' . $i . ']"';
    if($vals['meal'][$i] == 0) echo 'checked';
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
<p>Heslo pro změny v přihlášce (nezadáte-li, nebudou možné změny)<br>
<input type="text" name="password"></p>
<p><input type="submit" name="cancel" value=" Zrušit ">&nbsp;&nbsp;&nbsp;<input type="submit" name="ok" value=" Odeslat "></p>
</form>
</div><!-- entryform -->
<script>
ToggleSecond(document.getElementById('alone').checked);
</script>
<?php
}
add_shortcode('entryform', 'entry_form');
?>
