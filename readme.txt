=== Plugin Name ===
Contributors: Jean-François “Jeff” VIAL
Donate link: http://www.modulaweb.fr/blog/wp-plugins-en/
Tags: articles, sidebar, widget, pages, categories
Requires at least: 2.0
Tested up to: 2.8.4
Stable tag: 1.1

Articles to Sidebar allow you to put some articles into the sidebar of pages.

== Description ==

Articles to Sidebar allow you to put some articles into the sidebar of pages.

When you use WP as a “normal” CMS, you could want to add some custom boxes into the pages' sidebars.
Whith “Articles to Sidebar” you can write articles, put them into :

* a special category (in order to display to all pages)
* a category called the same as the current page
* both special category and category called the same as the curent page

When the article is displayed into the sidebar, the fist images it contains is used to illustrate.
The displayed content is the article's excerpt. If there is no excerpt, the extend (before the `<!--more-->` tag) is used and if there is no extend, one is computed (55 first words) and used.

You can choose to put a link to the article or not. That behavior can be chanched for each articles by using a custom boolean var (articles2sidebar_display_link : 1 force link | force no link).

== Installation ==

1. Upload `article2sidebar.zip` to the `/wp-content/plugins/` directory and uncompress it.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Modify the options in the Appearance > Widgets page

== Frequently Asked Questions ==

== Screenshots ==

== Versions ==

1 first public release
1.1 better handling of little images ; adding the link to image when present

== Translations ==

Actually, there are no translations, but the plugin code is ready for them.

