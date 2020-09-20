<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * htacces-regels
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @link      https://perishablepress.com/6g/
 */

// 6G firewall
$query_string_rules = [
	'comment' => '6G:[QUERY STRINGS]',
	'tag'     => 'IfModule',
	'value'   => 'mod_rewrite.c',
	'lines'   => [
		'RewriteEngine On',
		'RewriteCond %{QUERY_STRING} (eval\() [NC,OR]',
		'RewriteCond %{QUERY_STRING} (127\.0\.0\.1) [NC,OR]',
		'RewriteCond %{QUERY_STRING} ([a-z0-9]{2000,}) [NC,OR]',
		'RewriteCond %{QUERY_STRING} (javascript:)(.*)(;) [NC,OR]',
		'RewriteCond %{QUERY_STRING} (base64_encode)(.*)(\() [NC,OR]',
		'RewriteCond %{QUERY_STRING} (GLOBALS|REQUEST)(=|\[|%) [NC,OR]',
		'RewriteCond %{QUERY_STRING} (<|%3C)(.*)script(.*)(>|%3) [NC,OR]',
		'RewriteCond %{QUERY_STRING} (\\|\.\.\.|\.\./|~|`|<|>|\|) [NC,OR]',
		'RewriteCond %{QUERY_STRING} (boot\.ini|etc/passwd|self/environ) [NC,OR]',
		'RewriteCond %{QUERY_STRING} (thumbs?(_editor|open)?|tim(thumb)?)\.php [NC,OR]',
		'RewriteCond %{QUERY_STRING} (\'|\")(.*)(drop|insert|md5|select|union) [NC]',
		'RewriteRule .* - [F]',
	],
];
$request_methods_rules = [
	'comment' => '6G:[REQUEST METHOD]',
	'tag'     => 'IfModule',
	'value'   => 'mod_rewrite.c',
	'lines'   => [
		'RewriteCond %{REQUEST_METHOD} ^(connect|debug|move|put|trace|track) [NC]',
		'RewriteRule .* - [F]',
	],
];
$referrers_rules = [
	'comment' => '6G:[REFERRERS]',
	'tag'     => 'IfModule',
	'value'   => 'mod_rewrite.c',
	'lines'   => [
		'RewriteCond %{HTTP_REFERER} ([a-z0-9]{2000,}) [NC,OR]',
		'RewriteCond %{HTTP_REFERER} (semalt.com|todaperfeita) [NC]',
		'RewriteRule .* - [F]',
	],
];
$request_string_rules = [
	'comment' => '6G:[REQUEST STRINGS]',
	'tag'     => 'IfModule',
	'value'   => 'mod_alias.c',
	'lines'   => [
		'RedirectMatch 403 (?i)([a-z0-9]{2000,})',
		'RedirectMatch 403 (?i)(https?|ftp|php):/',
		'RedirectMatch 403 (?i)(base64_encode)(.*)(\()',
		'RedirectMatch 403 (?i)(=\\\'|=\\%27|/\\\'/?)\.',
		'RedirectMatch 403 (?i)/(\$(\&)?|\*|\"|\.|,|&|&amp;?)/?$',
		'RedirectMatch 403 (?i)(\{0\}|\(/\(|\.\.\.|\+\+\+|\\\"\\\")',
		'RedirectMatch 403 (?i)(~|`|<|>|:|;|,|%|\\|\s|\{|\}|\[|\]|\|)',
		'RedirectMatch 403 (?i)/(=|\$&|_mm|cgi-|etc/passwd|muieblack)',
		'RedirectMatch 403 (?i)(&pws=0|_vti_|\(null\)|\{\$itemURL\}|echo(.*)kae|etc/passwd|eval\(|self/environ)',
		'RedirectMatch 403 (?i)\.(aspx?|bash|bak?|cfg|cgi|dll|exe|git|hg|ini|jsp|log|mdb|out|sql|svn|swp|tar|rar|rdf)$',
		'RedirectMatch 403 (?i)/(^$|(wp-)?config|mobiquo|phpinfo|shell|sqlpatch|thumb|thumb_editor|thumbopen|timthumb|webshell)\.php',
	],
];
$user_agent_rules = [
	'comment' => '6G:[USER AGENTS]',
	'tag'     => 'IfModule',
	'value'   => 'mod_setenvif.c',
	'lines'   => [
		'SetEnvIfNoCase User-Agent ([a-z0-9]{2000,}) bad_bot',
		'SetEnvIfNoCase User-Agent (archive.org|binlar|casper|checkpriv|choppy|clshttp|cmsworld|diavol|dotbot|extract|feedfinder|flicky|g00g1e|harvest|heritrix|httrack|kmccrew|loader|miner|nikto|nutch|planetwork|postrank|purebot|pycurl|python|seekerspider|siclab|skygrid|sqlmap|sucker|turnit|vikspider|winhttp|xxxyy|youda|zmeu|zune) bad_bot',
		[
			'comment' => 'Apache < 2.3',
			'tag'     => 'IfModule',
			'value'   => '!mod_authz_core.c',
			'lines'   => [
				'Order Allow,Deny',
				'Allow from all',
				'Deny from env=bad_bot',
			],
		],
		[
			'comment' => 'Apache >= 2.3',
			'tag'     => 'IfModule',
			'value'   => 'mod_authz_core.c',
			'lines'   => [
				'<RequireAll>',
				'	Require all Granted',
				'	Require not env bad_bot',
				'</RequireAll>',
			],
		],
	],
];
$bad_ip_rules = [
	'comment' => '6G:[BAD IPS]',
	'tag'     => 'Limit',
	'value'   => 'GET HEAD OPTIONS POST PUT',
	'lines'   => [
		'Order Allow,Deny',
		'Allow from All',
		'# uncomment/edit/repeat next line to block IPs',
		'# Deny from 123.456.789',
	],
];

//Security rules
$files_rules = [
	'tag'     => 'FilesMatch',
	'value'   => '^(wp-config\.php|readme\.html|license\.txt|install\.php|xmlrpc\.php|\.htaccess)',
	'lines'   => [
		[
			'comment' => 'Apache < 2.3',
			'tag'     => 'IfModule',
			'value'   => '!mod_authz_core.c',
			'lines'   => [
				'Order Allow,Deny',
				'Deny from all',
				'Satisfy All',
			],
		],
		[
			'comment' => 'Apache ≥ 2.3',
			'tag'     => 'IfModule',
			'value'   => 'mod_authz_core.c',
			'lines'   => [
				'Require all denied',
			],
		],
	]
];

$includes_rules = [
	'tag'     => 'IfModule',
	'value'   => 'mod_rewrite.c',
	'lines'   => [
		'RewriteEngine On',
		'RewriteBase /',
		'RewriteRule ^wp-admin/includes/ - [F,L]',
		'RewriteRule !^wp-includes/ - [S=3]',
		'RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]',
		'RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]',
		'RewriteRule ^wp-includes/theme-compat/ - [F,L]',
		'RewriteRule ^(.*)/uploads/(.*).php(.?) – [F,L]',
	],
];

// Alle regels
$data = [
	[
		'comment' => 'security headers',
		'tag'     => 'IfModule',
		'value'   => 'mod_headers.c',
		'lines'   => [
			'Header always set Strict-Transport-Security "max-age=31536000" env=HTTPS',
			'Header always set X-XSS-Protection "1; mode=block"',
			'Header always append X-Frame-Options SAMEORIGIN',
			'Header always set X-Content-Type-Options nosniff',
			'Header always set Referrer-Policy strict-origin-when-cross-origin',
			'Header always unset X-Powered-By',
		],
	],
	[
		'comment' => '6G FIREWALL/BLACKLIST',
		'lines'   =>
		[
			$query_string_rules,
			$request_methods_rules,
			$referrers_rules,
			$request_string_rules,
			$user_agent_rules,
			$bad_ip_rules,
		],
	],
	[
		'comment' => 'security rules',
		'lines'   =>
		[
			$files_rules,
			$includes_rules,
		],
	],
];

return $data;
