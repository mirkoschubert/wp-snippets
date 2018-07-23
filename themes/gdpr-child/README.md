# GDPR Child Theme

This is a WordPress child theme boilerplate, which aims to secure the site and to configure it to meet the GDPR requirements. The child theme performs the following tasks:

* Localize Google Fonts (or in fact any web font)
* Make links in the comments truely external
* Remove the commentor's IP (old ones have to be removed by hand)
* Disable oEmbeds (old ones have to be removed by hand)
* Disable WordPress Emojis (in every modern browser Emojis will be displayed anyway)
* Remove global DNS Prefetching
* Disable WordPress REST API for security reasons

### Instructions

In order to create your own child theme with this boilerplate, you have to replace all `gdpr` strings with the name of your parent theme and edit the initialization part in the `style.css` as well. If you want to append your own CSS code, you should use the `.child` class as a prefix.

Please note, that this Child Theme is **not** fully functional! If you want to use it, you have to determine whether the parent theme uses `function_exists()` calls or not and change the `functions.php` accordingly.

You may also create additional functions to change the customizer of the parent theme.

To use the `TODO` file you should edit it with VSCode and the `Todo+` extension. Please read their documentation.