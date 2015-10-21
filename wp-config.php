<?php
/**
* AppFog addons
*
*/

// ** MySQL settings from resource descriptor ** //

$services = getenv("VCAP_SERVICES");
$services_json = json_decode($services,true);
$mysql_config = $services_json["ctl_mysql"][0]["credentials"];

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

if (getenv("VCAP_SERVICES")) {
	define('DB_NAME', $mysql_config["dbname"]);
	define('DB_USER', $mysql_config["username"]);
	define('DB_PASSWORD', $mysql_config["password"]);
	define('DB_HOST', $mysql_config["host"] . ":" . $mysql_config["port"]);
	define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
} ;

/**
* Begin CenturyLink default settings 
* 
*/

/** Pulling in the application information */
$appinfo = getenv("VCAP_APPLICATION");
$appinfo_json = json_decode($appinfo,true);

if (getenv("VCAP_APPLICATION")){
	define('MYAPPNAME', $appinfo_json["space_name"]);
} ;

/** Disable installing and updating plugins */
define('SYS_UNAME', getenv("USER"));
if( SYS_UNAME == "vcap" )
{
	define('DISALLOW_FILE_EDIT', TRUE);
	define('DISALLOW_FILE_MODS', TRUE);

	/** Insert site URL **/
	define('WP_SITEURL', getenv("SITEURL"));
	define('WP_HOME', getenv("SITEURL"));
}
else
{
	define('DISALLOW_FILE_EDIT', FALSE);
	define('DISALLOW_FILE_MODS', FALSE);
}

/* CenturyLink Object Storage Customer Variables */
define('AWS_REGION', 'ca.tier3.io');
define('AWS_ACCESS_KEY_ID', getenv("CLC_ACCESS_KEY_ID"));
define('AWS_SECRET_ACCESS_KEY', getenv("CLC_SECRET_ACCESS_KEY"));
define('AWS_BUCKET', getenv("CLC_BUCKET"));

/* Default website settings */
#define( 'WP_DEFAULT_THEME', 'twentyfourteen');
define('BLOG_TITLE', getenv("WEBSITE_NAME"));
define('CTL_CREDS', getenv("CTL_ADM_CREDS"));
define('ADM_CREDS', getenv('DEFAULT_ADM_CREDS'));
define('ADM_EMAIL', getenv("DEFAULT_ADM_EMAIL"));
define('DB_PREFIX', getenv("DATABASE_PREFIX"));
define('CTL_USER', 'ctluser');

/* Easy WP SMTP Settings */
define('SMTP_USERNAME', getenv("SMTP_USERNAME"));
define('SMTP_PASSWORD', getenv("SMTP_PASSWORD"));

/** Wordpress SSL Settings **/
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
       $_SERVER['HTTPS']='on';
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

/**
* End CenturyLink default settings
*
*/

define ('WPLANG', '');
/*
* Add this function back once adopted
* $table_prefix  = DB_PREFIX;
* and Remove the following line
*/
$table_prefix  = 'wp_';

require('wp-salt.php');

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
//require dirname(__FILE__) . '/le_php-master/logentries.php';

//$log->Info("Hello Logentries, I'm an info message");
//$log->Warn("Hey Logentries, I'm a warning");

require_once(ABSPATH . 'wp-settings.php');
/** Auto setup WordPress env. */
require_once ABSPATH . 'CTL-Check.php';

/* CenturyLink AWS Plugin Update Disabled */
function filter_plugin_updates( $value ) {
    unset( $value->response['amazon-web-services/amazon-web-services.php'] );
    unset( $value->response['amazon-s3-and-cloudfront/wordpress-s3.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );
