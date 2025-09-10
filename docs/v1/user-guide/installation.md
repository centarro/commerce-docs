---
title: Installing
taxonomy:
    category: docs
---

There are two ways to install Drupal Commerce. You can do it from scratch by installing Drupal 7 and then enabling the Commerce modules and their dependencies. This process requires additional configuration of products and product displays before you can start selling through your site.  However, it does afford you a greater measure of control over how exactly items are setup from the beginning.

An easier way to install Drupal Commerce would be to use an installation profile that enables and configures the Commerce modules and components for you.  The primary starter installation profile is <a href="https://drupal.org/project/commerce_kickstart">Commerce Kickstart</a>, but a major goal for the project is to see additional installation profiles tailor the initial configuration toward specific markets and business models.

Refer to the section below for your preferred method of installation.

* [Installing from scratch](../installation/#installing-from-scratch)
* [Installing with Commerce Kickstart 1.x](../installation/#installing-with-commerce-kickstart-1x)
* [Migrating from Ubercart Stores](../installation/#migrating-ubercart-stores)

## Installing from scratch

Your first step will be to perform a standard installation of Drupal 7.  For information on system requirements and database / file system configuration, please refer to the <a href="https://drupal.org/documentation/install">Installation guide</a> on drupal.org.

To install the Commerce modules, you need to download the latest versions of the following modules to your site's modules directory:

<ul>
<li><a href="https://drupal.org/project/addressfield">Address Field</a></li>
<li><a href="https://drupal.org/project/ctools">Chaos tool suite</a> (Views 3 dependency)</li>
<li><a href="https://drupal.org/project/entity">Entity API</a> (Rules 2 dependency)</li>
<li><a href="https://drupal.org/project/rules">Rules 2</a></li>
<li><a href="https://drupal.org/project/views">Views 3</a></li>
</ul>

Next you should download the latest version of Drupal Commerce to your site's modules directory.  You can find the latest supported release through the <a href="https://drupal.org/project/commerce">Drupal Commerce project page</a> (or get it from its <a href="https://drupal.org/project/commerce/git-instructions">Git repository</a> if you prefer).

With all of these modules in place, you can now enable the modules in the Commerce package on your module installation form.  Most sites will end up using most of the modules in the package, so for your first site you should just enable them all.  Note that advanced configurations may opt to not use the standard Cart module or provide a different administrative user interface.  The modular nature of Drupal Commerce supports this out of the box.

Once the modules have been enabled, you're ready to start configuring the Commerce components for your particular business needs.

## Installing with Commerce Kickstart 1.x

!!! note "This installation instruction page deals with Commerce Kickstart version 1.x. The first version of Kickstart is a developers "blank slate" that includes just the bare minimum to get a store up and running. The second version of Kickstart includes a developers "template" that includes 80+ contributed modules setup as a fully functioning store. <a href="../../commerce-kickstart-2/installing-and-upgrading">Installing Commerce Kickstart 2</a>."

Commerce Kickstart is an installation profile designed to get you up and running quickly with Drupal Commerce. It duplicates a standard Drupal installation and provides additional configuration for Commerce modules and components.  It is freely available through the <a href="https://drupal.org/project/commerce_kickstart">Commerce Kickstart project page</a>.


<h3>Simple Installation</h3>
Installing <a href="https://drupal.org/project/commerce_kickstart">Commerce Kickstart</a> is as easy as <a href="https://drupal.org/documentation/install">installing Drupal itself</a>, and in most cases is exactly the same procedure. Commerce Kickstart is just a copy of Drupal which includes the required modules and an install profile which does some sane initial setup for you.

<ol>
<li>Download the installation package from the <a href="https://drupal.org/project/commerce_kickstart">project page</a> (For example, the tar.gz or zip file).</li>
<li>Uncompress it into a directory where your webserver can find it. There are many ways to do this in many environments, but let's assume your webserver is looking for https://localhost in the /var/www directory. In that case, copy all the files (including the .htaccess, which may be hidden) into /var/www. </li>
<li>Point your browser at root of the site you have set up.  We'll assume it's https://localhost.</li> 
<li>Follow the instructions, choosing the "Commerce Kickstart" installation profile.</li>
</ol>

When you've completed this basic process, you have a Commerce install with the required modules in Drupal's profiles/commerce_kickstart/modules directory.

<h3>Alternatives, Recipes, and Issues</h3>

You have many options as an advanced sitebuilder:

<ul>
<li><span style="color:red;">This is considered bad practice.</span> <strike><b>Move modules into sites/all/modules or sites/all/modules/contrib:</b> Although installation profiles build with the modules in the profiles directory, many developers want them in sites/all/modules. <b><i>Before</i></b> running the installation process, just move the modules from profiles/commerce_kickstart/modules into sites/all/modules with any tool you like. For example, <code>mv profiles/commerce_kickstart/modules/* sites/all/modules/</code>.</strike> </li>
<li><b>Add Commerce Kickstart to an existing Drupal codebase:</b> Visit <a href="https://drupal.org/node/1079066/release">the releases page</a>, find your release, and download the -no-core.tar.gz or -no-core.zip version of commerce_kickstart and uncompress it into profiles/commerce_kickstart OR <code>drush dl commerce_kickstart</code>. Either of these will include the required modules.</li>
<li><b>Updating the PHP memory limit if necessary:</b> Commerce Kickstart installation involves the installation of 3 major contributed modules all at once on top of Drupal 7 itself, which may cause you to hit your memory limit during installation. If this happens, installation fails, and you will need to drop all tables from your database and reinstall with a higher memory limit (recommended 96M or higher to be safe; decrease as needed afterward).</li>
</ul>

<h3>Resources</h3>
Installing Commerce Kickstart requires no more knowledge than just installing Drupal, but depending on your starting point, that may seem like a lot. Here are some resources about installing Drupal:

<ul>
<li><a href="https://drupal.org/documentation/install">Complete Installation Guide</a></li>
<li><a href="https://drupal.org/documentation/install/beginners">Quick install for beginners.</a></li>
<li><a href="https://drupal.org/node/306267">Using an installation profile.</a></li>
</ul>

## Migrating from Ubercart Stores

<iframe src="https://player.vimeo.com/video/44428219?portrait=0&amp;badge=0" width="640" height="480" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

To migrate from Ubercart, you can either move the product catalog as described in <a href="https://www.drupalcommerce.org/node/467">Importing Products and Reference Nodes</a> or you can use the Commerce Migrate Ubercart submodule of Commerce Migrate to bring in a more full-featured migration. An article and screencast are at https://www.commerceguys.com/resources/articles/215

<iframe src="https://player.vimeo.com/video/26775252?byline=0&amp;portrait=0" width="640" height="480" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>