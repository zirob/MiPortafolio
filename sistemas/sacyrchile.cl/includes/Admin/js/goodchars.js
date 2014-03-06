
function getkey(e) {
	if (window.event) {
    	return window.event.keyCode;
	} else if (e) {
		return e.which;
	} else {
		return null;
	}
}
function view(){
    alert('entre');
}

function goodchars(e, goods) {
    var key, keychar;
    key = getkey(e);
    if (key == null) return true;
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();
    goods = goods.toLowerCase();
    if (goods.indexOf(keychar) != -1) {
    	return true;
    }
    if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 ) {
    	return true;
    }
    return false;
}