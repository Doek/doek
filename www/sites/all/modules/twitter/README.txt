Introduction
============
Twitter module allows listing tweets in blocks or pages. Its integration with Views opens the door to all sorts of formatting (ie. as an automatic slideshow with views_slideshow). Other submodules allow posting to twitter, executing actions when tweeting or login with a Twitter account.

OAuth
=====
Except for just listing tweets, OAuth module is required to authenticate against Twitter. If you 
just want to list tweets in a block, follow the steps at http://drupal.org/node/1253026.

When you download the OAuth module, get the latest stable release available at http://drupal.org/project/oauth

Once OAuth has been enabled, go to admin/config/services/twitter and follow instructions.

How to add a Twitter account to a Drupal user account
=====================================================
Read http://drupal.org/node/1253026 for details.

How to post to Twitter
======================
1. Read the OAuth section to install and configure OAuth.
2. Once OAuth has been configured, go to admin/config/services/twitter/post and select from which
   node types a user may post to Twitter and the default message.
3. Verify permissions at admin/people/permissions.
4. Add a Twitter account and try to edit or post content.

How to sign in with Twitter
===========================
Existing and new users can sign in with Twitter by enabling the twitter_signin module. The following scenarios are being contemplated so far:

* A visitor logs in with his Twitter account and, once authenticated at Twitter.com, it fills his email in the Drupal registration form and receives an email to log in and set his account password.
* An existing user with an already configured Twitter account can log in automatically by clicking on the Sign in with Twitter button.
