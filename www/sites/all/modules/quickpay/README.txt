$Id$

OVERVIEW
======== 

This module works with the Danish payment gateway provider QuickPay.dk
allowing you to receive payments using the payment options supported
by QuickPay on your Drupal site.

It includes 2 sub modules to implement the gateway into a shop based
on either the ECommerce package or the Ubercart package.

Development of these modules has been sponsored by QuickPay themselves.

=== WARNING ===

While these modules are as plug and play as is possible, setting up
online payment requires some insight into Drupal, payment gateways and
PBS requirements, and is not for the casual user. If in doubt, consult
a professional.

INSTALLATION
============

Unpack the module to sites/all/modules, and activate the Quickpay
module and either the ECommerce or Ubercart module at
admin/build/modules. Configure the modules at either
admin/ecsettings/quickpay or
admin/store/settings/payment/edit/methods, depending on what
e-commerce package you're using. The settings should be fairly strait
forward, but do read the IMPLEMENTATION section later in this
document, as it contains some important information.

Using quickpay.module in a custom Drupal solution
-------------------------------------------------

The core module can be used as a payment gateway in a custom written
(ie. not using ECommerce or Ubercart) Drupal solution. What is needed
is basically to present the quickpay_settings_form somewhere (using
system_settings_form), and then use the, mostly undocumented,
API. Take a look at ec_quickpay and uc_quickpay for example code.

IMPLEMENTATION
==============

The core module provides a block to show the accepted credit cards, as
required by PBS. 

Remember that PBS requires that the customer gets an confirmation of
the order either by email or as a web page. To implement this as a web
page, use E-Commerce or Ubercarts settings to set a custom return
page. As a convenience the modules sets $_SESSION['last_order'] to the
id of the last successful order in this session, which allows the page
to display an appropriate confirmation page.

AUTHOR / MAINTAINER
===================
Thomas Fini Hansen (aka Xen on Drupal.org)
xen at xen dot dk
