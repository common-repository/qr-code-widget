=== QR Code Widget  ===
Contributors: Wjatscheslaw Poluschin
Donate link: http://www.poluschin.info/
Tags: qr code, widget, mobile, shotcode
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 2.0.1

== Description ==
QR Code generator for your webpages with multiwidget, shortcode support.
Based on PHP QR Code encoder http://phpqrcode.sourceforge.net/ .
Complete rewrited for the 2.0 version


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `qr-code-widget` to the `/wp-content/plugins/` directory
2. Set write permissions for `/wp-content/plugins/qr-code-widget/cache` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Have fun...

Read http://en.wikipedia.org/wiki/QR_Code


== Showing Your QR Code ==

= MultiWidget Friendly =
If you are using widgets, you can drag QR Code widget to your sidebar to display your active Article Qr Code.

= Shortcode =
Use: 
`[qr_code_display]`
to display QR Code for Article. 

If u want to change context of Qr Code, you can do so by adding a qr_text parameter like this:
`[qr_code_display qr_text="http://www.poluschin.info"]`

Full parameters for shortcode:
[qr_code_display qr_code_format=”png|jpg” qr_code_size=”1-10″ qr_code_ecc=”0-3″ qr_code_trans_bg=”0|1″ qr_code_bg=”ffffff” qr_code_fg=”000000″ qr_text=”http://www.poluschin.info″ no_cache=”0|1″]

= How I can check QR code =

You can use online service http://zxing.org/w/decode.jspx

= Features =
* QR Code generator
* Widget support
* Shortcode `[qr_code_display]` insert QR Image to your contenten
* Custom colors and transparency for your QrCodes
* No filesystem permission required.
* Embed images directly in to HTM Source


== Frequently Asked Questions ==

= Where I can download barcode reader for mobile =

http://www.quickmark.com.tw/
http://www.i-nigma.mobi/
http://reader.kaywa.com/
http://www.imatrix.lt/

== Changelog ==
= 2.0.0 =
* Complete source rewrite
* Use phpqrcode library instead of Swetake QR Code Library
* Huge increase of functionality like shortcodes, multiwidgets,colorized/tranparency QrCodes

= 1.1.1 = 
* Swetake QR Code Library Updated to 0.50i for some Security issues and PHP 5.3 compatibility. 

= 1.1.0 =
* [BUG-FIX] It was not possible generate more then one Qr-Code.
* [Feature] Shortcode for more usability was added.

