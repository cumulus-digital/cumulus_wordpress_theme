<?php

namespace CumulusTheme;

@ini_set('memory_limit', '128M');
@ini_set('upload_max_size', '128M');
@ini_set('post_max_size', '128M');
@ini_set('max_execution_time', '1200');
@ini_set('max_input_time', '1200');

define('THEME_PATH', parse_url(\get_template_directory_uri(), PHP_URL_PATH));

// Auto-updates
\add_filter('auto_update_plugin', '__return_true');
\add_filter('auto_update_theme', '__return_true');
\add_filter('auto_update_translation', '__return_true');

// Helper to namespace stuff
function ns($str)
{
    return __NAMESPACE__ . '\\' . $str;
}

// Helper for default values from arrays
function get_arr_val($array, $key, $default = null)
{
    if (is_array($array) && array_key_exists($key, $array)) {
        return $array[$key];
    }
    return $default;
}

// Disable theme and plugin editor
define('DISALLOW_FILE_EDIT', true);

// Disable comments
\add_filter('comments_open', '__return_false', PHP_INT_MAX, 2);

// Disable pingbacks
\add_filter('pings_open', '__return_false', PHP_INT_MAX, 2);

// Recommend plugins
require_once 'classes/TGM-Plugin-Activation/class-tgm-plugin-activation.php';
function registerRequiredPlugins()
{
    $plugins = array(
        array(
            'name'               => 'Advanced Custom Fields',
            'slug'               => 'advanced-custom-fields',
            'required'           => true,
            'force_activation'   => true,
            'is_callable'        => 'acf_register_block_type',
        ),
        array(
            'name'      => 'Safe SVG',
            'slug'      => 'safe-svg',
            'required'  => false,
        ),
        /*
        array(
            'name'      => 'Ninja Forms',
            'slug'      => 'ninja-forms',
            'required'  => false,
        ),
        array(
            'name'      => 'Form Maker',
            'slug'      => 'form-maker',
            'required'  => false,
        ),
        array(
            'name'      => 'Stackable - Gutenberg Blocks',
            'slug'      => 'stackable-ultimate-gutenberg-blocks',
            'required'  => false,
        ),
        */
        /*
        array(
            'name'      => 'Ultimate Addons for Gutenberg',
            'slug'      => 'ultimate-addons-for-gutenberg',
            'required'  => false,
        ),
        */
        array(
            'name'      => 'Kadence Blocks',
            'slug'      => 'kadence-blocks',
            'required'  => false,
        ),
    );

    $config = array(
        'id'           => 'cumuluswp',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'cumuluswp-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
        ),
    );

    \tgmpa($plugins, $config);
}
\add_action('tgmpa_register', ns('registerRequiredPlugins'));

// Disable ACF menu if we're not in a dev environment
if (function_exists('acf_update_setting')) {
    function init_disable_acf_in_prod()
    {
        if (
            strstr(\get_site_url(), '.local') === false &&
            strstr(\get_site_url(), '.dev') === false &&
            strstr(\get_site_url(), 'localhost') === false
        ) {
            \acf_update_setting('show_admin', false);
        }
    }
    \add_action('acf/init', ns('init_disable_acf_in_prod'));
}

// Yoast annoyances
if (defined('WPSEO_VERSION')) {
    // Remove Yoast ad
    // https://buddydev.com/remove-this-site-is-optimized-with-the-yoast-seo-plugin-vx-y-z/
    \add_action('template_redirect', function () {
        if (! class_exists('WPSEO_Frontend')) {
            return;
        }

        $instance = \WPSEO_Frontend::get_instance();

        // make sure, future version of the plugin does not break our site.
        if (! method_exists($instance, 'debug_mark')) {
            return ;
        }

        // ok, let us remove the love letter.
        \remove_action('wpseo_head', array( $instance, 'debug_mark' ), 2);
    }, 9999);

    // Move Yoast post box to bottom
    \add_filter('wpseo_metabox_prio', function () {
        return 'low';
    });

    \add_filter('wpseo_debug_markers', '__return_false');
}

// Remove MonsterInsights comments and version leak
if (defined('MONSTERINSIGHTS_VERSION') || defined('MONSTERINSIGHTS_PRO_VERSION')) {
    \add_action('get_header', function () {
        ob_start(function ($o) {
            $ret = preg_replace('/\n?<\!\-\-.*?monsterinsights.*?>/mi', '', $o);
            $ret = preg_replace('/\n?\s*var mi_version = .*;/mi', '', $ret);
            return $ret;
        });
    });
    \add_action('wp_head', function () {
        ob_end_flush();
    }, 999);
}

// Custom menu display
require_once 'classes/MenuWalker.php';

// Remove emoji crud
\remove_action('wp_head', 'print_emoji_detection_script', 7);
\remove_action('wp_print_styles', 'print_emoji_styles');

// remove version info from head and feeds
function remove_identity()
{
    return '';
}
\add_filter('the_generator', ns('remove_identity'));
\remove_action('wp_head', 'wlwmanifest_link'); // remove MS-live writer
\remove_action('wp_head', 'rsd_link'); // remove Really Simple Discovery
\remove_action('wp_head', 'feed_links_extra', 3); // remove Comments Feed

// Allow media to have categories
function registerMediaTaxonomy()
{
    \register_taxonomy_for_object_type('category', 'attachment');
}
\add_action('init', ns('registerMediaTaxonomy'));

// Exclude SVGs from Jetpack Photon
function photonExcludeSVG($val, $src, $tag)
{
    if (strpos(strtolower($src), '.svg') >= -1) {
        return true;
    }
    return $val;
}
\add_filter('jetpack_photon_skip_image', ns('photonExcludeSVG'), 10, 3);

// Add custom copyright field to Settings / General
function register_copyright_field()
{
    \register_setting(
        'general',
        'copyright_info',
        array(
            'description' => 'Copyright text displayed on site.',
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => null,
        )
    );
    \add_settings_field('copyright_info', '<label for="copyright_info">'.__('Copyright Info', 'copyright_info').'</label>', ns('output_copyright_field'), 'general');
}
function output_copyright_field()
{
    $value = \get_option('copyright_info', '');
    echo '<input type="text" id="copyright_info" name="copyright_info" value="' . $value . '" class="regular-text" />';
}
\add_filter('admin_init', ns('register_copyright_field'));

// Show custom copyright line in admin footer
function custom_admin_footer()
{
    return '&copy; ' . date('Y') . ' ' . esc_html(\get_option('copyright_info'));
}
\add_filter('admin_footer_text', ns('custom_admin_footer'));

// Remove prefix from archive titles
function archive_title($title)
{
    if (\is_category()) {
        $title = \single_cat_title('', false);
    } elseif (\is_tag()) {
        $title = \single_tag_title('', false);
    } elseif (\is_author()) {
        $title = '<span class="vcard">' . \get_the_author() . '</span>';
    } elseif (\is_post_type_archive()) {
        $title = \post_type_archive_title('', false);
    } elseif (\is_tax()) {
        $title = \single_term_title('', false);
    }

    return $title;
}
\add_filter('get_the_archive_title', ns('archive_title'));

// Use frontpage.php if blog is set to static posts
function front_page_template($template)
{
    return \is_home() ? '' : $template;
}
\add_filter('frontpage_template', ns('front_page_template'));

// Rename "Default Template"
\add_filter('default_page_template_title', function () {
    return __('Basic', 'cumuluswp');
});

function theme_setup()
{
    \add_theme_support('title-tag');
    \add_theme_support('post-thumbnails', array('page', 'post'));
    \add_theme_support('responsive-embeds');
    \add_theme_support('post-formats', array( 'aside', 'gallery', 'video' ));
    \add_post_type_support('page', 'excerpt');

    \add_theme_support('html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ]);
    \add_theme_support('align-wide');
    \add_theme_support('custom-spacing');
    \add_theme_support('custom-units');
    \add_theme_support('wp-block-styles');

    \register_nav_menus(array(
        'header-menu' => __('Header Menu'),
        'footer-menu' => __('Footer Menu'),
        'social-menu' => __('Social Menu')
    ));

    // Add theme support for Custom Logo.
    \add_theme_support('custom-logo', array(
        'width'       => 250,
        'height'      => 250,
        'flex-width'  => true,
    ));

    // Enable color palette
    \add_theme_support('editor-color-palette', array(
        array(
            'name' => __('White', 'cumuluswp'),
            'slug' => 'white',
            'color' => '#fff',
        ),
        array(
            'name' => __('Black', 'cumuluswp'),
            'slug' => 'black',
            'color' => '#000',
        ),
        array(
            'name' => __('Grey', 'cumuluswp'),
            'slug' => 'grey',
            'color' => '#888',
        ),
        array(
            'name' => __('Light Grey', 'cumuluswp'),
            'slug' => 'light-grey',
            'color' => '#d6d6d6',
        ),
        array(
            'name' => __('Dark Grey', 'cumuluswp'),
            'slug' => 'cumulus-dark-grey',
            'color' => '#333',
        ),
        array(
            'name' => __('Cumulus Blue', 'cumuluswp'),
            'slug' => 'cumulus-blue',
            'color' => '#00598e',
        ),
        array(
            'name' => __('Cumulus Light Blue', 'cumuluswp'),
            'slug' => 'cumulus-light-blue',
            'color' => '#3399cc',
        ),
        array(
            'name' => __('WWOPN Purple', 'cumuluswp'),
            'slug' => 'wwopn-purple',
            'color' => '#6a2774',
        ),
        array(
            'name' => __('WWOPN Light Purple', 'cumuluswp'),
            'slug' => 'wwopn-light-purple',
            'color' => '#a33cb3',
        ),
    ));

    // Additional editor font styles
    add_theme_support('editor-font-sizes', array(
        array(
            'name'      => __('Default', 'cumuluswp'),
            'shortName' => __('default', 'cumuluswp'),
            'size'      => 18,
            'slug'      => 'default'
        ),
        array(
            'name'      => __('Small', 'cumuluswp'),
            'shortName' => __('S', 'cumuluswp'),
            'size'      => 12,
            'slug'      => 'small'
        ),
        array(
            'name'      => __('Medium', 'cumuluswp'),
            'shortName' => __('M', 'cumuluswp'),
            'size'      => 16,
            'slug'      => 'medium'
        ),
        array(
            'name'      => __('Large', 'cumuluswp'),
            'shortName' => __('L', 'cumuluswp'),
            'size'      => 20,
            'slug'      => 'large'
        ),
        array(
            'name'      => __('Larger', 'cumuluswp'),
            'shortName' => __('XL', 'cumuluswp'),
            'size'      => 24,
            'slug'      => 'larger'
        )
    ));
}
\add_action('after_setup_theme', ns('theme_setup'));

/**
 * Enqueue scripts and styles
 * @return [type] [description]
 */
function scripts_and_styles()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && ! \is_admin()) {
        \wp_register_script(
            'cumulus_theme_script',
            THEME_PATH . '/assets/prod/index.js',
            array('jquery'),
            null,
            true
        );
        \wp_enqueue_script('cumulus_theme_script');

        \wp_register_style(
            'cumulus_theme_style',
            THEME_PATH . '/assets/prod/index.css',
            array(),
            null,
            'all'
        );
        \wp_enqueue_style('cumulus_theme_style');

        \wp_register_style(
            'google_montserrat_font',
            'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,500;0,600;0,700;0,800;0,900;1,100;1,300;1,500;1,600;1,700;1,800;1,900&display=swap',
            false,
            null,
            'all'
        );
        \wp_enqueue_style('google_montserrat_font');
    }
}
\add_action('wp_enqueue_scripts', ns('scripts_and_styles'));

// Setup Gutenberg editor
function setupGutenberg()
{
    \wp_register_Style(
        'google_montserrat_font',
        'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,500;0,600;0,700;0,800;0,900;1,100;1,300;1,500;1,600;1,700;1,800;1,900&display=swap',
        false,
        null,
        'all'
    );
    \wp_enqueue_style('google_montserrat_font');

    \wp_register_Style(
        'guttenberg_styles',
        THEME_PATH . '/assets/prod/editor.css',
        false,
        null,
        'all'
    );
    \wp_enqueue_style('guttenberg_styles');

    // Custom blocks
    \wp_enqueue_script(
        'cumulus-gutenberg/anchor', // Handle.
        THEME_PATH . '/assets/prod/blocks-anchor.js',
        array('wp-blocks', 'wp-element')
    );

    /*
    \wp_enqueue_script(
        'cumulus-gutenberg/force-values', // Handle.
        THEME_PATH . '/assets/prod/js/blocks-FORCE.js',
        array('wp-blocks', 'wp-element')
    );

    \wp_register_Style(
        'cumulus-gutenberg/force-values',
        THEME_PATH . '/assets/prod/css/flipcards.css',
        false,
        null,
        'all'
    );
    \wp_enqueue_style('cumulus-gutenberg/force-values');

    \wp_enqueue_script(
        'cumulus-gutenberg/image-flipper', // Handle.
        THEME_PATH . '/assets/prod/js/blocks-imageflipper.js',
        array('wp-blocks', 'wp-element')
    );
    */
}
\add_action('enqueue_block_editor_assets', ns('setupGutenberg'));

// Setup block front-end
function setupGutenbergFrontend()
{
    /*
    if (\has_block('cumulus-gutenberg/force-values')) {
        \wp_register_Style(
            'cumulus-gutenberg/force-values',
            THEME_PATH . '/assets/prod/css/flipcards.css',
            false,
            null,
            'all'
        );
        \wp_enqueue_style('cumulus-gutenberg/force-values');
    }

    if ( ! \is_admin() && \has_block('cumulus-gutenberg/image-flipper')) {
        \wp_enqueue_script(
            'cumulus-gutenberg/image-flipper/frontend',
            THEME_PATH . '/assets/prod/js/blocks-imageflipper-frontend.js',
            null,
            null,
            true
        );
        \wp_localize_script( 'cumulus-gutenberg/image-flipper/frontend', 'theme_vars', array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress A
        ) );
        \wp_register_Style(
            'cumulus-gutenberg/image-flipper/frontend',
            THEME_PATH . '/assets/prod/css/imageflipper.css',
            false,
            null,
            'all'
        );
        \wp_enqueue_style('cumulus-gutenberg/image-flipper/frontend');

    }
    */
}
\add_action('enqueue_block_assets', ns('setupGutenbergFrontend'));

function getImagesByCategory()
{
    $category = json_decode($_POST['category'], true);
    if (! filter_var($category, FILTER_VALIDATE_INT)) {
        header('HTTP/1.0 400 Bad error');
        echo '{ error: "Bad category." }';
    } else {
        $args = array(
            'category' => $category,
            'post_type' => 'attachment'
        );
        $media = \get_posts($args);
        if (! empty($_GET['callback'])) {
            echo $_GET['callback'] . '(' . json_encode($media) . ');';
        } else {
            echo json_encode($media);
        }
    }
}
add_action('wp_ajax_get_images_by_category', ns('getImagesByCategory'));
add_action('wp_ajax_nopriv_get_images_by_category', ns('getImagesByCategory'));

/**
 * Additional date parameters for fetching posts
 */
function fetch_post_from_years($query)
{
    if ($query->is_search() || $query->is_archive()) {
        if (isset($_GET['previous-years'])) {
            $query->set(
                'date_query',
                array(
                    array('before' => '1 year ago')
                )
            );
        }
        if (isset($_GET['before'])) {
            $d = \DateTime::createFromFormat('Y-m-d', $_GET['before']);
            if ($d && $d->format('Y-m-d') === $_GET['before']) {
                $query->set(
                    'date_query',
                    array(
                        array('before' => $d->format('Y-m-d'))
                    )
                );
            }
        }
        if (isset($_GET['after'])) {
            $d = \DateTime::createFromFormat('Y-m-d', $_GET['after']);
            if ($d && $d->format('Y-m-d') === $_GET['after']) {
                $query->set(
                    'date_query',
                    array(
                        array('after' => $d->format('Y-m-d'))
                    )
                );
            }
        }
    }
    return $query;
}
\add_action('pre_get_posts', ns('fetch_post_from_years'), 1000);

// Menus
function header_menu()
{
    \wp_nav_menu(array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'nav',
        'container_class' => 'menu menu-header-container',
        'container_id'    => 'header-menu-container',
        'menu_class'      => 'menu',
        'menu_id'         => 'header-menu',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul itemscope itemtype="http://www.schema.org/SiteNavigationElement">%3$s</ul>',
        'depth'           => 0,
        //'walker'          => new MenuWalker()
    ));
}
function footer_menu()
{
    \wp_nav_menu(array(
        'theme_location'  => 'footer-menu',
        'menu'            => '',
        'container'       => '',
        'container_class' => 'menu-footer-menu-container',
        'container_id'    => 'footer-menu-container',
        'menu_class'      => 'menu',
        'menu_id'         => 'footer-menu',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        //'walker'          => new MenuWalker()
    ));
}
function social_menu()
{
    \wp_nav_menu(array(
        'theme_location'  => 'social-menu',
        'menu'            => '',
        'container'       => '',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '<span>',
        'link_after'      => '</span>',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        //'walker'          => new MenuWalker()
    ));
}

// Body Class helper
class BodyClasses
{
    public static $classes = array();
    public static function add($class)
    {
        self::$classes[] = $class;
    }
    public static function get()
    {
        return self::$classes;
    }
}

/**
 * Handle some HTTP header stuff
 */
add_action('wp_headers', function () {
    // Don't send these headers in the admin
    if (\is_user_logged_in() || \is_admin()) {
        return;
    }

    /*
     * Remove X-Powered-By which may expose PHP version
     */
    \header_remove('X-Powered-By');

    /*
     * X-DNS-Prefetch-Control - Proactively perform domain name resolution on both
     * links that the user may choose to follow as well as URLs for items
     * referenced by the document
     */
    \header('X-DNS-Prefetch-Control: on');

    /*
     * X-Frame-Options: SAMEORIGIN - Prevent web pages from being loaded inside iFrame
     */
    \header('X-Frame-Options: SAMEORIGIN');

    /*
     * X-Content-Type-Options: nosniff - Prevent MIME Type sniffing
     */
    \header('X-Content-Type-Options: nosniff');

    /**
     * Modicrom of support for old IE
     */
    \header('X-UA-Compatible: IE=edge,chrome=1');
});

/**
 * Last-modified should reflect content
 */
\add_action('send_headers', function () {
    if (! \is_admin() && \is_singular()) {
        $last_modified = \get_post_modified_time('D, d M Y H:i:s', true);

        if ($last_modified) {
            \header('Last-Modified: ' . $last_modified . ' GMT');
        }
    }
});

/*
 * Remove jQuery Migrate on frontend
 */
if (! \is_admin() && ! \is_user_logged_in()) {
    \add_action('wp_default_scripts', function ($scripts) {
        if (isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];

            if ($script->deps) {
                $script->deps = \array_diff($script->deps, [ 'jquery-migrate' ]);
            }
        }
    });
}
