Drupal colorbox module:
------------------------
Maintainers:
  Joe Wheaton (http://drupal.org/user/298179)
  Fredrik Jonsson (http://drupal.org/user/5546)
Requires - Drupal 7
License - GPL (see LICENSE)


Overview:
--------
Colorbox is a lightweight customizable lightbox plugin for jQuery
1.3 and 1.4. This module allows for integration of Colorbox into Drupal.
The jQuery library is a part of Drupal since version 5+.

Images, forms, iframed or inline content etc. can be displayed in a
overlay above the current page.

* jQuery - http://jquery.com/
* Colorbox - http://colorpowered.com/colorbox/


Features:
---------

The Colorbox module:

* Excellent integration with Image field and Image styles
* Choose between a default style and 5 example styles that are included.
* Style the Colorbox with a custom colorbox.css file in your theme.
* Option to open a login form by clicking on any login link
* Simple API to open any form in a Colorbox
* Drush command to download and install the Colorbox plugin in
  sites/all/libraries

The Colorbox plugin:

* Supports images, image groups, slideshow, ajax, inline, and
  iframed content.
* Appearance is controlled through CSS so users can restyle the box.
* Preloads background images and can preload upcoming images in a
  photo group.
* Generates W3C valid XHTML and adds no JS global variables and
  passes JSLint.
* Tested in Firefox 2 & 3, Safari 3 & 4, Opera 9, Chrome,
  Internet Explorer 6, 7, 8.
* Released under the MIT License.


Installation:
------------
1. Download and unpack the Colorbox plugin in "sites/all/libraries".
   Link: http://colorpowered.com/colorbox/colorbox.zip
   Drush users can use the command "drush colorbox-plugin".
2. Place the Colorbox module directory in your modules folder (this will
   usually be "sites/all/modules/").
3. Go to "Administer" -> "Site building" -> "Modules" and enable the module.


Configuration:
-------------
Go to "Administer" -> "Site configuration" -> "Colorbox" to find
all the configuration options.


Use the Views Colorbox Trigger field:
------------------------------------
TODO


Add a custom Colorbox style to your theme:
----------------------------------------
The easiest is to start with either the default style or one of the
example styles from the Colorbox plugin. Simply copy the whole
style folder to the theme and rename it and the files to
something logical.

Add entries in the themes info file for the Colorbox CSS/JS files.

In the Colorbox settings select "None" as style. This will leave the
styling of Colorbox up to your theme.


Load a selection of forms in a Colorbox:
----------------------------------------
Check the "Enable Colorbox load" option in Colorbox settings.

The following form_id can be used:
* contact_mail_page
* user_login
* user_login_block
* user_register
* user_pass

The links to open a form needs the class "colorbox-load". The URL should look like this.

"/colorbox/form/[form_id]?width=[with_in_pixel]&height=[height_in_pixel]".

Here is an example where the user register form is opened in an
500 by 250 pixel Colorbox.

<a class="colorbox-load" href="/colorbox/form/user_register?width=500&height=250">Create new account</a>


Drush:
------
A Drush command is provides for easy installation of the Colorbox
plugin itself.

% drush colorbox-plugin

The command will download the plugin and unpack it in "sites/all/libraries".
It is possible to add another path as an option to the command, but not
recommended unless you know what you are doing.


Example styles borders do not display in Internet Explorer:
----------------------------------------------------------
If you use one of the example styles and have problems with the border
images not loading in Internet Explorer please read
http://colorpowered.com/colorbox/#help_paths.

The default style in Colorbox module does not have this problem.


Contributions:
-------------
* Porting all features from the Thickbox module,
  by Fredrik Jonsson (http://drupal.org/user/5546).
* Image module integration improvements by recrit
  (http://drupal.org/user/452914).
* Help with testing and many good suggestions by Shane
  (http://drupal.org/user/262473).


Last updated:
------------
$Id: README.txt,v 1.9.2.1 2011/01/04 10:02:44 frjo Exp $