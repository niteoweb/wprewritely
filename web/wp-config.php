<?php

// ===================================================
// Load database info and local development parameters
// ===================================================


define( 'DB_NAME', 'test' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'docker' );
define( 'DB_HOST', 'localhost' ); // Probably 'localhost'

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', '/srv/wordpress/content' );
define( 'WP_CONTENT_URL', 'http://localhost/content' );
define( 'UPLOADS',        'http://localhost/uploads' );
define( 'WP_PLUGIN_URL',  'http://localhost/content/plugins' );

define( 'COMPRESS_CSS',        false );
define( 'COMPRESS_SCRIPTS',    false );
define( 'CONCATENATE_SCRIPTS', false );
define( 'ENFORCE_GZIP',        false );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define('AUTH_KEY',         'B3Zj`L~ApBPv@p?Wo7+v_TBX^?Yr(=*2=/Z67K88+][)HLE^%{eRK.[cH3&>!%32');
define('SECURE_AUTH_KEY',  'JPuB|sS0Ad]}:i?R!+yPES^}+^=VAx#=1FO#1W[y<eq0OobAFywP+p*5H~ /)]I%');
define('LOGGED_IN_KEY',    '?&_WkvkIi0txn;~N)-)#KZVIj;v!A6Pw[J2aFX<)G~5:.o)u4t!H[zX-k4OmwgMb');
define('NONCE_KEY',        'jS]J|B6<vM{w I[ecW8])~w?fc(q^8*O%,U!=FqJN3FWVM,m-+YM/e(VM&yH*5+$');
define('AUTH_SALT',        'LRoH!bR|u,[&<e=WylFML1#~,J}SMS*c#vhPuVsgV68Rdpr1}R%;qZ[H^Z13|-yP');
define('SECURE_AUTH_SALT', 'Cqb~){a~.kx`j~dpk_bv{oV%C4q8^xa+s|VJ7a|k2(3lryQTVy3+(L>W,fIciO^#');
define('LOGGED_IN_SALT',   'W^ y(z_$E}{&u&xgL~n*[3uu8_S_/U7qDRB?uFrRhqz3@H>)ZHx? wgqw+V3T*Z|');
define('NONCE_SALT',       '+|LzQ<r7%O3QY]]Q]fc-yT+5:~~^pDTJn!C~nbJI7,V!I5pFB@Go`BfaP+O/(rJ,');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'app_dev_';

define( 'WP_DEBUG',         true );
define( 'WP_DEBUG_LOG',     true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG',     false );
define( 'SAVEQUERIES',      false );
error_reporting(E_ALL ^ E_DEPRECATED);
// ===================
// Bootstrap WordPress
// ===================
define( 'ABSPATH', '/srv/wordpress/' );
require_once( ABSPATH . 'wp-settings.php' );