<?
// This is a file of miscellaneous functions that are called so damn often
// that it'd just be annoying to stick them in namespaces.

/**
 * Return true if the given string is numeric.
 *
 * @param string $Str
 * @return true if $Str numeric
 */
function is_number($Str) {
	$Return = true;
	if ($Str < 0) { $Return = false; }
	// We're converting input to a int, then string and comparing to original
	$Return = ($Str == strval(intval($Str)) ? true : false);
	return $Return;
}


/**
 * HTML-escape a string for output.
 * This is preferable to htmlspecialchars because it doesn't screw up upon a double escape.
 *
 * @param string $Str
 * @return string escaped string.
 */
function display_str($Str) {
	if ($Str === NULL || $Str === FALSE || is_array($Str)) {
		return '';
	}
	if ($Str!='' && !is_number($Str)) {
		$Str = Format::make_utf8($Str);
		$Str = mb_convert_encoding($Str,"HTML-ENTITIES","UTF-8");
		$Str = preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,5};)/m","&amp;",$Str);

		$Replace = array(
			"'",'"',"<",">",
			'&#128;','&#130;','&#131;','&#132;','&#133;','&#134;','&#135;','&#136;',
			'&#137;','&#138;','&#139;','&#140;','&#142;','&#145;','&#146;','&#147;',
			'&#148;','&#149;','&#150;','&#151;','&#152;','&#153;','&#154;','&#155;',
			'&#156;','&#158;','&#159;'
		);

		$With = array(
			'&#39;','&quot;','&lt;','&gt;',
			'&#8364;','&#8218;','&#402;','&#8222;','&#8230;','&#8224;','&#8225;','&#710;',
			'&#8240;','&#352;','&#8249;','&#338;','&#381;','&#8216;','&#8217;','&#8220;',
			'&#8221;','&#8226;','&#8211;','&#8212;','&#732;','&#8482;','&#353;','&#8250;',
			'&#339;','&#382;','&#376;'
		);

		$Str = str_replace($Replace, $With, $Str);
	}
	return $Str;
}


/**
 * Send a message to an IRC bot listening on SOCKET_LISTEN_PORT
 *
 * @param string $Raw An IRC protocol snippet to send.
 */
function send_irc($Raw) {
	$IRCSocket = fsockopen(SOCKET_LISTEN_ADDRESS, SOCKET_LISTEN_PORT);
	$Raw = str_replace(array("\n", "\r"), '', $Raw);
	fwrite($IRCSocket, $Raw);
	fclose($IRCSocket);
}


/**
 * Display a critical error and kills the page.
 *
 * @param string $Error Error type. Automatically supported:
 *	403, 404, 0 (invalid input), -1 (invalid request)
 *	If you use your own string for Error, it becomes the error description.
 * @param boolean $Ajax If true, the header/footer won't be shown, just the description.
 * @param string $Log If true, the user is given a link to search $Log in the site log.
 */
function error($Error, $Ajax=false, $Log=false) {
	global $Debug;
	require(SERVER_ROOT.'/sections/error/index.php');
	$Debug->profile();
	die();
}


/**
 * Convenience function. See doc in class_permissions.php
 */
function check_perms($PermissionName, $MinClass = 0) {
	return Permissions::check_perms($PermissionName, $MinClass);
}

?>