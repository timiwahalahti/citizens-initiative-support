=== Citizens' initiative support ===
Contributors: SipuliSopuli
Donate link: 
Tags: finnish, citizens' initiative, support, support statement, widget, sidebar, aloite, kansalaisaloite
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a widget to display number of support statements in defined Finnish citizens' initiative.

== Description ==
This plugin provides easy way for adding widget to show number of support statements in Finnish citizens' initiative. It also adds a link to kansalaisaloite.fi where the initiative may be signed. It receives initiative information from kansalaisaloite.fi Open Data API so all the information is fresh.

Plugin handles citizens' initiative states correctly.

If initiative is more than month old and has less than 50 support statements, the due date and signature link will not be shown because signing is no longer possible in kansalaisaloite.fi. Initiative more than six months old and with less than 50 000 support statements is treated same way.

Citizens' initiative sent to Parliament will show message saying that the initiative has been sent to Parliament and signature link will not be shown.

**Usage**
Navigate to the 'Widgets' dashboard, drag and drop 'Kansalaisaloite'-widget to the desired place and set initiative number to get started. Initiative number is the last part of initiative url in kansalaisaloite.fi, example kansalaisaloite.fi/fi/aloite/**1**.

In widget settings there is input for title, if it is empty widget title will be title of initiative. There is also opportunity to choose whether the widget uses Finnish or Swedish title of initiative. Signature link uses this same choice so that if Swedish is chosen, link will point to Swedish version of kansalaisaloite.fi.

**Future plans**

* Add possibility to show progress bar for visualizing the number of support statements
* Add shortcode support to get number of support statements into content

**Languages**

* Finnish
* Swedish *by [Lotta Söderholm](http://lottasoderholm.org/)*

*Plugin is NOT provided by Finnish Ministry of Justice and they are not responsible of this plugin! Plugin is developed by Timi Wahalahti.*

== Installation ==

= Using The WordPress Dashboard =
1. Navigate to the 'Add New' Plugin Dashboard
2. Select `citizen-initiative-support.zip` from your computer
3. Upload
4. Activate the plugin on the WordPress Plugin Dashboard

= Using FTP =
1. Extract `citizen-initiative-support.zip` to your computer
2. Upload the citizen-initiative-support directory to your wp-content/plugins directory
3. Activate the plugin on the WordPress Plugins dashboard

== Screenshots ==

1. Widget in Twenty Twelve with different initiative statuses
2. Widget options

== Changelog ==

= 1.0 =
First release
