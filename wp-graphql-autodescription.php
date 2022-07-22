<?php
/**
 * Plugin Name: WPGraphQL for The Seo Framework
 * Plugin URI: https: //github.com/anacoelhovicente/wp-graphql-autodescription
 * Description: Adds support for The Seo Framework to the WPGraphQL Schema. Requires WPGraphQL version 0.4.0 or newer.
 * Author: Ana Vicente
 * Author URI: https: //anavicente.me
 * Version: 0.0.1
 * Text Domain: wp-graphql-autodescription
 * Requires at least: 4.7.0
 * Tested up to: 5.8
 *
 * @package WP_Graphql_Auto_Description
 */

namespace WPGraphQL\AutoDescription;

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Define constants
 */
const WPGRAPHQL_REQUIRED_MIN_VERSION = '0.4.0';
const WPGraphQL_THESEOFRAMEWORK_VERSION = '0.5.3';

/**
 * Initialize the plugin
 *
 * @return AutoDescription|void
 */
function init()
{

    /**
     * If either The Seo Framework or WPGraphQL are not active, show the admin notice and bail
     */
    if (false === can_load_plugin()) {
        // Show the admin notice
        add_action('admin_init', __NAMESPACE__ . '\show_admin_notice');

        // Bail
        return;
    }

    /**
     * Return the instance of WPGraphQL\AutoDescription
     */
    return AutoDescription::instance();
}

add_action('init', '\WPGraphQL\AutoDescription\init');

/**
 * Show admin notice to admins if this plugin is active but either The Seo Framework and/or WPGraphQL
 * are not active
 *
 * @return bool
 */
function show_admin_notice()
{

    /**
     * For users with lower capabilities, don't show the notice
     */
    if (!current_user_can('manage_options')) {
        return false;
    }

    add_action(
        'admin_notices',
        function () {
            ?>
			<div class="error notice">
				<p><?php esc_html_e(sprintf('Both WPGraphQL (v%s+) and Advanced Custom Fields (v5.7+) must be active for "wp-graphql-autodescription" to work', WPGRAPHQL_REQUIRED_MIN_VERSION), 'wp-graphql-autodescription');?></p>
			</div>
			<?php
}
    );
}

/**
 * Check whether The Seo Framework and WPGraphQL are active, and whether the minimum version requirement has been
 * met
 *
 * @return bool
 * @since 0.3
 */
function can_load_plugin()
{
    // Is The Seo Framework active?
    if (!function_exists('the_seo_framework')) {
        return false;
    }

    // Is WPGraphQL active?
    if (!class_exists('WPGraphQL')) {
        return false;
    }

    // Do we have a WPGraphQL version to check against?
    if (empty(defined('WPGRAPHQL_VERSION'))) {
        return false;
    }

    // Have we met the minimum version requirement?
    if (true === version_compare(WPGRAPHQL_VERSION, WPGRAPHQL_REQUIRED_MIN_VERSION, 'lt')) {
        return false;
    }

    return true;
}
