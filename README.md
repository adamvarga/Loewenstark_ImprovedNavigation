Loewenstark_ImprovedNavigation
=====================
- Product listing in Magento Navigation
-------------------------------

Required Plugin
---------------
1. Webcomm_BootstrapNavigation & Webcomm_MagentoBoilerplate

Installation Instructions
-------------------------
1. Install the extension via GitHub, and deploy with modman & composer.
2. Clear the cache.
3. You can style the new product menu level in navigation individually with css.

Style hints:

 ```
nav.navbar .navbar-collapse ul.nav.navbar-nav li.level1 {
    position: relative;
    &:hover {
        >ul.level1 {
            display: block;
            position: absolute;
            background: #333;
            top: 0;
            left: 100%;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 5px;
            -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
        }
    }
}
 ```

Example frontend after styling:

![alt text](https://github.com/adamvarga/Loewenstark_ImprovedNavigation/blob/master/improved_navigation_frontend.png)

Uninstallation
--------------
1. Remove all extension files from your Magento installation OR
2. Modman remove Loewenstark_ImprovedNavigation & modman clean

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/adamvarga).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Adam Varga
