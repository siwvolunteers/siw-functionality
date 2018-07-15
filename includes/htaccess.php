<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* htaccess opnieuw genereren na update plugin */
add_action( 'siw_update_plugin', function() {
	if ( ! function_exists( 'flush_rocket_htaccess' )  || ! function_exists( 'rocket_generate_config_file' ) ) {
		return false;
	}
	flush_rocket_htaccess();
	rocket_generate_config_file();
});


/* HTTPS redirect */
add_filter( 'before_rocket_htaccess_rules', function ( $marker ) {
	$redirection  = '# Redirect http to https' . PHP_EOL;
	$redirection .= 'RewriteEngine On' . PHP_EOL;
	$redirection .= 'RewriteCond %{HTTPS} !on' . PHP_EOL;
	$redirection .= 'RewriteCond %{SERVER_PORT} !^443$' . PHP_EOL;
	$redirection .= 'RewriteCond %{HTTP:X-Forwarded-Proto} !https' . PHP_EOL;
	$redirection .= 'RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]' . PHP_EOL;
	$redirection .= '# END https redirect' . PHP_EOL . PHP_EOL;

	$marker = $redirection . $marker;
	return $marker;
});

/* Security headers */
add_filter( 'after_rocket_htaccess_rules', function( $marker ) {
	$security_headers  = '# Add security headers' . PHP_EOL;
	$security_headers .= '<IfModule mod_headers.c>' . PHP_EOL;
	$security_headers .= '	Header always set Strict-Transport-Security "max-age=31536000" env=HTTPS' . PHP_EOL;
	$security_headers .= '	Header always set X-XSS-Protection "1; mode=block; report='. siw_generate_report_uri( 'xss', true ) . '"' . PHP_EOL;
	$security_headers .= '	Header always append X-Frame-Options SAMEORIGIN' . PHP_EOL;
	$security_headers .= '	Header always set X-Content-Type-Options nosniff' . PHP_EOL;
	$security_headers .= '	Header always set Referrer-Policy no-referrer-when-downgrade' . PHP_EOL;
	$security_headers .= '	Header unset X-Powered-By' . PHP_EOL;
	$security_headers .= '	Header always set Expect-CT "max-age=0; report-uri='. siw_generate_report_uri( 'ct', false ) . '"' . PHP_EOL;
	$security_headers .= '</IfModule>' . PHP_EOL;
	$security_headers .= '# END security headers' . PHP_EOL . PHP_EOL;

	$marker = $security_headers . $marker;
	return $marker;
});

/* Firewall */
add_filter( 'after_rocket_htaccess_rules', function( $marker ) {
	$firewall  = '# 6G FIREWALL/BLACKLIST' . PHP_EOL;
	$firewall .= '# @ https://perishablepress.com/6g/' . PHP_EOL;
	$firewall .= '# 6G:[QUERY STRINGS]' . PHP_EOL;
	$firewall .= '<IfModule mod_rewrite.c>' . PHP_EOL;
	$firewall .= '	RewriteEngine On' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (eval\() [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (127\.0\.0\.1) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} ([a-z0-9]{2000,}) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (javascript:)(.*)(;) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (base64_encode)(.*)(\() [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (GLOBALS|REQUEST)(=|\[|%) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (<|%3C)(.*)script(.*)(>|%3) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (\\|\.\.\.|\.\./|~|`|<|>|\|) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (boot\.ini|etc/passwd|self/environ) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (thumbs?(_editor|open)?|tim(thumb)?)\.php [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{QUERY_STRING} (\'|\")(.*)(drop|insert|md5|select|union) [NC]' . PHP_EOL;
	$firewall .= '	RewriteRule .* - [F]' . PHP_EOL;
	$firewall .= '</IfModule>' . PHP_EOL;
	$firewall .= PHP_EOL;
	$firewall .= '# 6G:[REQUEST METHOD]' . PHP_EOL;
	$firewall .= '<IfModule mod_rewrite.c>' . PHP_EOL;
	$firewall .= '	RewriteCond %{REQUEST_METHOD} ^(connect|debug|move|put|trace|track) [NC]' . PHP_EOL;
	$firewall .= '	RewriteRule .* - [F]' . PHP_EOL;
	$firewall .= '</IfModule>' . PHP_EOL;
	$firewall .= PHP_EOL;
	$firewall .= '# 6G:[REFERRERS]' . PHP_EOL;
	$firewall .= '<IfModule mod_rewrite.c>' . PHP_EOL;
	$firewall .= '	RewriteCond %{HTTP_REFERER} ([a-z0-9]{2000,}) [NC,OR]' . PHP_EOL;
	$firewall .= '	RewriteCond %{HTTP_REFERER} (semalt.com|todaperfeita) [NC]' . PHP_EOL;
	$firewall .= '	RewriteRule .* - [F]' . PHP_EOL;
	$firewall .= '</IfModule>' . PHP_EOL;
	$firewall .= PHP_EOL;
	$firewall .= '# 6G:[REQUEST STRINGS]' . PHP_EOL;
	$firewall .= '<IfModule mod_alias.c>' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)([a-z0-9]{2000,})' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)(https?|ftp|php):/' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)(base64_encode)(.*)(\()' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)(=\\\'|=\\%27|/\\\'/?)\.' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)/(\$(\&)?|\*|\"|\.|,|&|&amp;?)/?$' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)(\{0\}|\(/\(|\.\.\.|\+\+\+|\\\"\\\")' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)(~|`|<|>|:|;|,|%|\\|\s|\{|\}|\[|\]|\|)' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)/(=|\$&|_mm|cgi-|etc/passwd|muieblack)' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)(&pws=0|_vti_|\(null\)|\{\$itemURL\}|echo(.*)kae|etc/passwd|eval\(|self/environ)' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)\.(aspx?|bash|bak?|cfg|cgi|dll|exe|git|hg|ini|jsp|log|mdb|out|sql|svn|swp|tar|rar|rdf)$' . PHP_EOL;
	$firewall .= '	RedirectMatch 403 (?i)/(^$|(wp-)?config|mobiquo|phpinfo|shell|sqlpatch|thumb|thumb_editor|thumbopen|timthumb|webshell)\.php' . PHP_EOL;
	$firewall .= '</IfModule>' . PHP_EOL;
	$firewall .= PHP_EOL;
	$firewall .= '# 6G:[USER AGENTS]' . PHP_EOL;
	$firewall .= '<IfModule mod_setenvif.c>' . PHP_EOL;
	$firewall .= '	SetEnvIfNoCase User-Agent ([a-z0-9]{2000,}) bad_bot' . PHP_EOL;
	$firewall .= '	SetEnvIfNoCase User-Agent (archive.org|binlar|casper|checkpriv|choppy|clshttp|cmsworld|diavol|dotbot|extract|feedfinder|flicky|g00g1e|harvest|heritrix|httrack|kmccrew|loader|miner|nikto|nutch|planetwork|postrank|purebot|pycurl|python|seekerspider|siclab|skygrid|sqlmap|sucker|turnit|vikspider|winhttp|xxxyy|youda|zmeu|zune) bad_bot' . PHP_EOL;
	$firewall .= '	# Apache < 2.3' . PHP_EOL;
	$firewall .= '	<IfModule !mod_authz_core.c>' . PHP_EOL;
	$firewall .= '		Order Allow,Deny' . PHP_EOL;
	$firewall .= '		Allow from all' . PHP_EOL;
	$firewall .= '		Deny from env=bad_bot' . PHP_EOL;
	$firewall .= '	</IfModule>' . PHP_EOL;
	$firewall .= '	# Apache >= 2.3' . PHP_EOL;
	$firewall .= '	<IfModule mod_authz_core.c>' . PHP_EOL;
	$firewall .= '		<RequireAll>' . PHP_EOL;
	$firewall .= '			Require all Granted' . PHP_EOL;
	$firewall .= '			Require not env bad_bot' . PHP_EOL;
	$firewall .= '		</RequireAll>' . PHP_EOL;
	$firewall .= '	</IfModule>' . PHP_EOL;
	$firewall .= '</IfModule>' . PHP_EOL;
	$firewall .= PHP_EOL;
	$firewall .= '# 6G:[BAD IPS]' . PHP_EOL;
	$firewall .= '<Limit GET HEAD OPTIONS POST PUT>' . PHP_EOL;
	$firewall .= '	Order Allow,Deny' . PHP_EOL;
	$firewall .= '	Allow from All' . PHP_EOL;
	$firewall .= '	# uncomment/edit/repeat next line to block IPs' . PHP_EOL;
	$firewall .= '	# Deny from 123.456.789' . PHP_EOL;
	$firewall .= '</Limit>' . PHP_EOL . PHP_EOL;
	$marker = $firewall . $marker;
	return $marker;
});


/* Divers security aanpassingen */
add_filter( 'after_rocket_htaccess_rules', function( $marker ) {
	$security  = '# Add security rules' . PHP_EOL;
    $security .= '<files wp-config.php>' . PHP_EOL;
    $security .= '	order allow,deny' . PHP_EOL;
    $security .= '	deny from all' . PHP_EOL;
    $security .= '</files>' . PHP_EOL;
	$security .= PHP_EOL;
	$security .= '<files .htaccess>' . PHP_EOL;
    $security .= '	order allow,deny' . PHP_EOL;
    $security .= '	deny from all' . PHP_EOL;
    $security .= '</files>' . PHP_EOL;
	$security .= PHP_EOL;
    $security .= '<Files xmlrpc.php>' . PHP_EOL;
    $security .= '	order deny,allow' . PHP_EOL;
    $security .= '	deny from all' . PHP_EOL;
    $security .= '</Files>' . PHP_EOL;
    $security .= PHP_EOL;
    $security .= '<Files readme.html>' . PHP_EOL;
    $security .= '	order deny,allow' . PHP_EOL;
    $security .= '	deny from all' . PHP_EOL;
    $security .= '</Files>' . PHP_EOL;
    $security .= PHP_EOL;
    $security .= '<Files license.txt>' . PHP_EOL;
    $security .= '	order deny,allow' . PHP_EOL;
    $security .= '	deny from all' . PHP_EOL;
    $security .= '</Files>' . PHP_EOL;
    $security .= PHP_EOL;
    $security .= '<Files install.php>' . PHP_EOL;
    $security .= '	order deny,allow' . PHP_EOL;
    $security .= '	deny from all' . PHP_EOL;
    $security .= '</Files>' . PHP_EOL;
    $security .= PHP_EOL;
    $security .= '<IfModule mod_rewrite.c>' . PHP_EOL;
    $security .= '	RewriteEngine On' . PHP_EOL;
    $security .= '	RewriteBase /' . PHP_EOL;
    $security .= '	RewriteRule ^wp-admin/includes/ - [F,L]' . PHP_EOL;
    $security .= '	RewriteRule !^wp-includes/ - [S=3]' . PHP_EOL;
    $security .= '	RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]' . PHP_EOL;
    $security .= '	RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]' . PHP_EOL;
    $security .= '	RewriteRule ^wp-includes/theme-compat/ - [F,L]' . PHP_EOL;
    $security .= '	RewriteRule ^(.*)/uploads/(.*).php(.?) – [F,L]' . PHP_EOL;
    $security .= '</IfModule>' . PHP_EOL;
    $security .= PHP_EOL;
	$security .= '# END security rules' . PHP_EOL . PHP_EOL;

	$marker = $security . $marker;
	return $marker;
});
