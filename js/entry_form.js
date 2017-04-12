/**
 * File entry_form.js.
 *
 * Functions for Entry form application
 *
 */

function ToggleSecond(alone) {
	if(alone == true){
		document.getElementById('second_biker').style.display = 'none'
//		document.getElementById('need_team').style.display = 'none'
	}else{
		document.getElementById('second_biker').style.display = 'block'
//		document.getElementById('need_team').style.display = 'inline'
	}
}

