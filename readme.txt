=== WooMulti ===
Contributors: buznetwork
Plugin Name: WooMulti
Plugin URI: https://genbuz.com/
Tags: woocommerce, multi site, multi store, orders, order managment, multi site order managment
Author URI: https://genbuz.com/
Author: GenBuz
Donate link: https://genbuz.com/
Requires at least: 4.6
Tested up to: 5.0.2
Stable tag: 1.7
Version: 1.7
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WooMulti is a wordrpess plugin that allows you to process orders for multiple woocommerce stores accross multiple domains from a single plugin.

== Description ==

If you are someone that has multiple woocommerce stores accross many different domains then you know how frustrating it can be to have to login into each site to process orders, now there is another way **WooMulti**:

* Display orders from multiple woocommerce stores accross multiple domains on a single site.
* Update the status of any order on any connected store.
* Update billing and shipping addresses for any order on any connected store *(1)*.
* Add tracking details to any order including attaching tracking info to emails shipped to customers and to the customer "My Account > Orders > View" section *(1)*.
* Add couriers so you can select which courier to associate with a tracking number.
* Download Word (Docx) or PDF for selected orders or of a certain status.

*(1)* requires **WooSite** plugin to be installed on connected site.

== Installation ==

**Minimum Requirements**

* PHP version 5.6 or greater (PHP 7.1 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)
* WooCommerce version 3.5 or greater
* WooCommerce REST API Consumer Key and Consumer Secret
* Wordpress > Settings > Permalinks must **NOT** be set to "Plain"

**Geting Started**

* Install the plugin via **Wordpress Admin > Plugins > Add New** or by uploading it.
* Goto **WooMulti > Sites** and add a new site

**Your Done**

The instructions above are for the basic setup which allows you to view orders and update order status, for full features and detailed setup instructions please visit the plugin page **WooMulti > Help** page.

In order to use some features of the plugin a second plugin called "WooSite" should be installed on the sites you wish to connect to (not the site with WooMulti installed), this however is only needed if you want to be able to include tracking information to an order and also to update billing and shipping addresses of an order.

You do not need to install the WooSite plugin on the connected sites if you dont intend to add tracking information to orders or to ever update billing or shipping addresses via this plugin.


== Frequently Asked Questions ==

= How do I add a site =

Full instructions on how to add a site (Including screenshots) are provided on the plugin page **WooMulti > Help** section.

= How do I add a courier =

* Goto plugin page **WooMulti > Couriers**.
* Click on **Add Courier**.
* Enter a name for the courier example: (Royal Mail).
* Enter the courier URL example: (https://www.royalmail.com) or a direct link to the tracking page example: (https://www.royalmail.com/track-your-item#/).
* Select Active or Inactive.
* Click on **Add Courier**.

**Courier Added** and now available to select in couriers list when updating an order with tracking (if courier is Active).

= Do I need to install the WooSite plugin =

The **WooSite** plugin is currently only needed if you wish to add tracking information to an order, customer emails and to the customer account section, it is also used to update the shipping or billing address of a customer (sometimes they make a mistake or ask to change address).

Basically if you only want to view and update the status of orders then you do not need the **WooSite** plugin.

== Upgrade Notice ==

Latest version includes a new download section, great for downloading invoices for orders, available in docx and pdf formats.

== Screenshots ==

1. WooMulti Dashboard
2. list of orders marked processing for a site
3. viewing an order
4. updating an order
5. managing sites showing add site form
6. edit a site credentials
7. managing couriers showing add courier form
8. edit a courier
9. downloads template customizer

== Changelog ==

= 1.7 =
* Minor bug fixes.
* new downloads section, enables you to download orders, 1 template (Classic - Invoice) Word (Docx) and PDF formats available.

= 1.6 =
* Initial release to wordpress directory.