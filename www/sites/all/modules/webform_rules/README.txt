/* $Id: README.txt,v 1.1 2010/11/26 15:50:02 stborchert Exp $ */

This module provides a basic rules integration for webform.


-- REQUIREMENTS --

You need the following modules for a working feature:

 * Webform (http://drupal.org/project/webform)
 * Rules (http://drupal.org/project/rules)


-- INSTALLATION --

Copy the module files to you module directory and then enable it on the admin
modules page. After that you'll see a new event in the listing while creating
a new rule.


-- HINTS --

If you have a field called 'name' in your webform you can access its submitted
value by using <code>$data['name']</code> in you rule (requires 'PHP Filter' to
be installed).


-- AUTHOR --
Stefan Borchert
http://drupal.org/user/36942
http://www.undpaul.de
