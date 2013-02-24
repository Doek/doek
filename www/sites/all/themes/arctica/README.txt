Introduction
--------------

Congratulations on choosing Arctica as your basetheme. To get an idea of what
your subtheme should look like it's advisable to look at some examples:
http://www.drupal.org/project/arti (small, simple theme)


INSTALLATION
------------

 1. Download Arctica from http://drupal.org/project/arctica

 2. Place the arctica folder under sites/all/themes. Not in the themes directory
    of your Drupal base directory.

 3. Install the Skinr module. It is important you download the latest dev because
    it contains a patch that was needed to improve the Arctica skinr form.

 4. Follow the instructions below to build an Arctica subtheme.


BUILD YOUR OWN SUBTHEME
-----------------------

The Arctica base theme (parent theme) is designed to be easily extended by a
subtheme (child theme). You shouldn't modify any of the CSS or PHP files in the
arctica/arctica folder; but instead create a subtheme of Arctica. The examples
below assume Arctica and your subtheme will be in sites/all/themes/

 1. Copy the arctica_starterkit or arctica_starterkit_bigtheme folder and rename it to be
    your new subtheme. IMPORTANT: Only lowercase letters and underscores should
    be used for the name of your subtheme.

    For example, copy the sites/all/themes/arctica_starterkit folder and rename it
    as sites/all/themes/sunshine.

	* Which starter theme to use?
	  For a small site with few templates arctica_starterkit is perfect.
    For a big site that may have many overrides, templates, stylesheets and
    functions arctica_starterkit_bigtheme will provide the necessary structure.

 2. In your new subtheme folder, rename the .info file to include the name of your
    new subtheme (for example: sunshine.info).
    Then edit the .info file to update the name and description.

 3. Visit your subtheme's settings page (click "Settings" next to it at
    admin/appearance) to make yourself familiar with the various settings and options.


Important:

    1. MODIFYING TEMPLATE FILES:
    If you decide you want to modify any of the .tpl.php template files in the
    arctica/arctica folder, copy them to your subtheme's folder before making any
    changes. Then rebuild the theme registry.

    For example, copy arctica/arctica/templates/page.tpl.php to sunshine/page.tpl.php

    2. ADDING AND REMOVING TEMPLATES / UPDATNIG .info FILE
    It is absolutely necessary that you clear cache when you add or remove template files,
    or when you update the .info file. Until you do so, your changes will not have effect.
    You can clear cache by clicking the
    "Clear all caches" button at admin/config/development/performance.

    If you have the devel module or admin_menu installed you can make use of their shortcuts
    to clear the cache.

