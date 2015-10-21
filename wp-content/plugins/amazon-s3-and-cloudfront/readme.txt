=== CLC Object Storage ===
Contributors: bradt
Requires at least: 3.5
Tested up to: 3.6.1
Stable tag: 0.6.1
License: GPLv3

Copies files to CLC Object Storage as they are uploaded to the Media Library.

== Description ==

This plugin automatically copies images, videos, documents, and any other media added through WordPress' media uploader to CLC Object Storage. It then automatically replaces the URL to each media file with their respective CLC Object Storage URL. Image thumbnails are also copied to CLC Object Storage.

Uploading files *directly* to your CLC Object Storage account is not currently supported by this plugin. They are uploaded to your server first, then copied to CLC Object Storage. There is an option to automatically remove the files from your server once they are copied to CLC Object Storage however.

If you're adding this plugin to a site that's been around for a while, your existing media files will not be copied or served from CLC Object Storage. Only newly uploaded files will be copied and served from CLC Object Storage.

* This plugin is a rebranding of [bradt's fork](https://github.com/bradt/wp-tantan-s3/), which has been completely rewritten, but was originally a fork of
[Amazon S3 for WordPress with CloudFront](http://wordpress.org/extend/plugins/tantan-s3-cloudfront/) 
which is a fork of [Amazon S3 for WordPress](http://wordpress.org/extend/plugins/tantan-s3/), also known as tantan-s3.*

== Screenshots ==

1. Settings screen

== Upgrade Notice ==

= 0.6 =
This version requires PHP 5.3.3+ and the Amazon Web Services plugin

= 0.6.1 =
This version requires PHP 5.3.3+ and the Amazon Web Services plugin

== Changelog ==

= Update =
* Forked [bradt's fork](https://github.com/bradt/wp-tantan-s3/)

= 0.6.1 - 2013-09-21 =
* WP.org download of Amazon Web Services plugin is giving a 404 Not Found, so directing people to download from Github instead

= 0.6 - 2013-09-20 =
* Complete rewrite
* Now requires PHP 5.3.3+
* Now requires the [Amazon Web Services plugin](https://github.com/deliciousbrains/wp-amazon-web-services) which contains the latest PHP libraries from Amazon
* Now works with multisite
* New Option: Custom S3 object path
* New Option: Always serve files over https (SSL)
* New Option: Enable object versioning by appending a timestamp to the S3 file path
* New Option: Remove uploaded file from local filesystem once it has been copied to S3
* New Option: Copy any HiDPI (@2x) images to S3 (works with WP Retina 2x plugin)

= 0.5 - 2013-01-29 =
* Forked [Amazon S3 for WordPress with CloudFront](http://wordpress.org/extend/plugins/tantan-s3-cloudfront/)
* Cleaned up the UI to fit with today's WP UI
* Fixed issues causing error messages when WP_DEBUG is on
* [Delete files on S3 when deleting WP attachment](https://github.com/bradt/wp-tantan-s3/commit/e777cd49a4b6999f999bd969241fb24cbbcece60)
* [Added filter to the get_attachment_url function](https://github.com/bradt/wp-tantan-s3/commit/bbe1aed5c2ae900e9ba1b16ba6806c28ab8e2f1c)
* [Added function to get a temporary, secure download URL for private files](https://github.com/bradt/wp-tantan-s3/commit/11f46ec2714d34907009e37ad3b97f4421aefed3)