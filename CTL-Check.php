<?php
/**
* Create CTL Admin and Customer User id
*
*/
require_once ABSPATH . '/wp-admin/includes/upgrade.php';
require_once ABSPATH . '/wp-includes/wp-db.php';
require_once( ABSPATH . '/wp-includes/registration.php');
require_once( ABSPATH . '/wp-includes/pluggable.php');
global $wpdb;
if( $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "options'") === $wpdb->prefix . 'options' ) {
  ctl_add_admin(); 
} else {
  ctl_wp_install();
  ctl_add_admin();
  ctl_install_easy_wp_smtp();
  ctl_install_s3_plugin();
};
/** preforms default wordpress install, which create database tables and add default values */
function ctl_wp_install()
{
  define( 'WP_INSTALLING', true );
  $blog_title = BLOG_TITLE;
  $admin_name = CTL_USER;
  $admin_email = "noreply@ctl.io";
  $public = true;
  $deprecated = '';
  $admin_password = CTL_CREDS;
  $language = ''; 
  wp_install( $blog_title, $admin_name, $admin_email, (int) $public, $deprecated, $admin_password, $language );
  update_option( 'template', WP_DEFAULT_THEME );
  update_option( 'stylesheet', WP_DEFAULT_THEME );
  update_option( 'admin_email', ADM_EMAIL);
  define( 'WP_INSTALLING', false );
};

/** Add CTL Admin account  */
function ctl_add_admin()
{

/* Make CTL_USER a Subscriber */
if ( username_exists( CTL_USER ) ) {
    wp_update_user( array( 'ID' => '1', 'role' => 'subscriber' ) );
  } 

/* Add CTL Admin User */
if ( ! username_exists( 'ctladmin' ) ) { 
    $CTLUserData = array(
       'user_login' => 'ctladmin',
       'user_pass' => CTL_CREDS,
       'user_email' => 'wpaas_noreply@ctl.io',
       'first_name' => 'CTL',
       'last_name' => 'Admin',
       'user_nicename' => 'ctladmin',
       'display_name' => 'CTL Admin',
       'user_url' => 'http://CenturyLink.com',
       'role' => 'administrator' 
    );
    $self_id = wp_insert_user( $CTLUserData );
    update_user_option( $self_id, 'default_password_nag', true, true ); 
    wp_new_user_notification( $self_id, $CTLUserData['password'] ); 
  } 

/* Add Customer Sitename User */
if ( ! username_exists( MYAPPNAME ) ) { 
    $CTLUserData = array(
       'user_login' => MYAPPNAME,
       'user_pass' => ADM_CREDS,
       'user_email' => ADM_EMAIL,
       'first_name' => '',
       'last_name' => '',
       'user_nicename' => MYAPPNAME,
       'display_name' => MYAPPNAME,
       'user_url' => '',
       'role' => 'administrator' 
    );
    $self_id = wp_insert_user( $CTLUserData );
    update_user_option( $self_id, 'default_password_nag', true, true ); 
    wp_new_user_notification( $self_id, $admin_password ); 
  } 

}
/** Add activate easy wp smtp plugin */
function ctl_install_easy_wp_smtp()
{
  $easy_wp_smtp="easy-wp-smtp/easy-wp-smtp.php";
  $result = activate_plugin($easy_wp_smtp);
  if (!is_wp_error($result))  {
    ctl_config_easy_wp_smtp();
  };
}
/**  Configure Easy WP SMTP plugin */
function ctl_config_easy_wp_smtp()
{
  $option_easy_wp_smtp_config = 'swpsmtp_options' ;
  $smtp_settings = array (
    'from_email_field' => 'noreply@ctl.io',
    'from_name_field' => 'CenturyLink WordPress as a Service',
    'smtp_settings' => array (
      'host'=>'smtp.sendgrid.net',
      'type_encryption'=>'ssl',
      'port'=>'465',
      'autentication'=>'yes',
      'username'=>SMTP_USERNAME,
      'password'=>SMTP_PASSWORD,
  ));
  if ( get_option( $option_easy_wp_smtp_config )  == false || count(get_option( $option_easy_wp_smtp_config )) == 0 ) {
    $result = count(get_option(  $option_easy_wp_smtp_config ));
    $deprecated = null;
    $autoload = 'yes';
  };
    update_option( $option_easy_wp_smtp_config, $smtp_settings, $deprecated, $autoload );
}
/** Add activate S3 pluigins and configure S3 bucket to manage the upload folder */
function ctl_install_s3_plugin()
{
  $amazon_s3="amazon-s3-and-cloudfront/wordpress-s3.php";
  $amazon_web_services="amazon-web-services/amazon-web-services.php";
  $result = activate_plugins(array($amazon_web_services, $amazon_s3));
  if (!is_wp_error($result)) {
    ctl_config_s3_bucket();
  };
}
/**  Configure S3 pluigins and S3 bucket that wil manage the upload folder */
function ctl_config_s3_bucket()
{
  $option_s3_bucket = 'tantan_wordpress_s3' ;
  $s3_uploads='/uploads/';
  $bucket_option = array (
    'bucket'=>AWS_BUCKET,
    'cloudfront'=>'',
    'object-prefix'=>$s3_uploads,
    'copy-to-s3'=>'1',
    'serve-from-s3'=>'1',
    'remove-local-file'=>'1',
    'force-ssl'=>'0'
  );
  if ( get_option( $option_s3_bucket )  == false || count(get_option( $option_s3_bucket )) == 0 ) {
    $result = count(get_option(  $option_s3_bucket ));
    $deprecated = null;
    $autoload = 'yes';
    add_option( $option_s3_bucket, $bucket_option, $deprecated, $autoload );
  };
}
