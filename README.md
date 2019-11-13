# wordpress-approveme
WordPress plugin for ApproveMe 2.0

Create a .lando.yml file and add the following to it:
name: appme
recipe: wordpress
config:
  php: '7.2'
  via: nginx
  database: 'mysql'
  xdebug: true
  conf:
    apache: configs/
    php: configs/php.ini
proxy:
  mailhog:
    - mailhog.addservices.lndo.site
services:
  mailhog:
    type: mailhog
    hogfrom:
      - appserver




If you've done any of these steps before do the following:
    delete all files/folders in the project except .lando.yml and .README
    run lando destroy

lando start

lando ssh
wp core download
exit

cd wp-content/plugins && git clone https://github.com/approveme/wordpress-approveme.git
rm -rf wordpress-approveme/vendor
cd wordpress-approveme && composer install

cd ../../..
cp wp-config-sample.php wp-config.php
In wp-config.php update the following:
    DB_NAME, DB_USER, and DB_PASSWORD to wordpress
    DB_HOST to database


If running the app and api locally add this to the bottom of wp-config.php but ABOVE the last line "require_once( ABSPATH . 'wp-settings.php' );"
define( 'APPROVEME_API_URL', 'http://apiapproveme.lndo.site' );
define( 'APPROVEME_APP_URL', 'http://localhost:3000' );
Else add this:
define( 'APPROVEME_API_URL', 'https://dev-api.approveme.com' );
define( 'APPROVEME_APP_URL', 'https://dev-app.approveme.com' );

In the browser visit http://appme.lndo.site
    Follow the installation instructions and then login
    Click on plugins from the left menu. After page load find ApproveMe and click activate

Click ApproveMe from the left menu and then Settings
    Click the connect button
