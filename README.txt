=== MAIRDUMONT NETLETIX ads.txt Agent ===
Contributors: mdnx, mrfischer
Tags: ads.txt, adstxt, ad manager, publishing, publishers, bannerwerbung, werbung, banner, ads, banner-ads, advertising, netletix, mairdumont, netzathleten
Requires at least: 4.0
Tested up to: 4.9.5
Stable tag: 1.0.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

With the ads.txt Agent of MAIRDUMONT NETLETIX not only local ads.txt data in the WordPress can be comfortably created and managed, but also remote ads.txt sources can be integrated via URL.

== Description ==

With the ads.txt Agent of MAIRDUMONT NETLETIX not only local ads.txt data in the WordPress can be comfortably created and managed, but also remote ads.txt sources can be integrated via URL.

It is recommended to add an ads.txt file to websites to allow authorised sellers and resellers for website advertising.
For this the [IAB](https://iabtechlab.com/ads-txt/) released a specification which define the process and functionality of the ads.txt.
The goal of this specification is to increase transparency in the programmatic advertising ecosystem and fight against fraud and misrepresented domains.

For this MAIRDUMONT NETLETIX develop the ads.txt Agent plugin for WordPress to handle comfortably and easy the content of your ads.txt within WordPress.
The plguin allow to enter your content of ads.txt as well as adding several URLs to handle entries from remote sources.
The ads.txt Agent collect all sources and combine them together automatically to generate the contents of yourdomain.com/ads.txt.

The entries of remote sources will be cached and were checked two times a day in case of lost connection to the server.
The plugin stores the latest readable content of the URL.


== Installation ==

1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings --> MD-NX ads.txt Agent and define your ads.txt entries
4. Check it out at yourdomain.com/ads.txt

Note: If you already have an existing ads.txt file in the web root, the plugin will not read in the contents of that file.
The plugin will rename existing file and changes you make in WordPress admin will generate the contents of yourdomain.com/ads.txt.

If you want, you can remove the existing ads.txt file (keeping a copy of the records it contains to put into the new settings screen) before you will be able to see any changes you make to ads.txt inside the WordPress admin.


== Frequently Asked Questions ==

= Does the plugin validate content?  =

Yes. The plugin validates the data you enter into the ads.txt file to make sure it 100% complies with IAB specification.

= Does the plugin can read existing ads.txt file in the web root? =

No. You had to copy the existing entries of your ads.txt file into the textarea within the ads.txt Agent.
The plugin will rename the exisiting file and changes you make in ads.txt Agent will generate the contents of yourdomain.com/ads.txt.