<?php
/**
 * AutoDescription
 *
 * @package WP_Graphql_Auto_Description
 */

namespace WPGraphQL\AutoDescription;

/**
 * Final class AutoDescription
 */
final class AutoDescription
{

    /**
     * Stores the instance of the WPGraphQL\AutoDescription class
     *
     * @var AutoDescription The one true WPGraphQL\Extensions\AutoDescription
     * @access private
     */
    private static $instance;

    /**
     * Get the singleton.
     *
     * @return AutoDescription
     */
    public static function instance()
    {

        if (!isset(self::$instance) && !(self::$instance instanceof AutoDescription)) {
            self::$instance = new AutoDescription();
            self::$instance->setup_constants();
            self::$instance->includes();
            self::$instance->init();
        }

        /**
         * Fire off init action
         *
         * @param AutoDescription $instance The instance of the WPGraphQL\AutoDescription class
         */
        do_action('graphql_seoframework_init', self::$instance);

        /**
         * Return the WPGraphQL Instance
         */
        return self::$instance;
    }

    /**
     * Throw error on object clone.
     * The whole idea of the singleton design pattern is that there is a single object
     * therefore, we don't want the object to be cloned.
     *
     * @access public
     * @return void
     */
    public function __clone()
    {

        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, esc_html__('The \WPGraphQL\AutoDescription class should not be cloned.', 'wp-graphql-autodescription'), '0.0.1');

    }

    /**
     * Disable unserializing of the class.
     *
     * @access protected
     * @return void
     */
    public function __wakeup()
    {

        // De-serializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, esc_html__('De-serializing instances of the \WPGraphQL\AutoDescription class is not allowed', 'wp-graphql-autodescription'), '0.0.1');

    }

    /**
     * Setup plugin constants.
     *
     * @access private
     * @return void
     */
    private function setup_constants()
    {

        // Plugin Folder Path.
        if (!defined('WPGraphQL_THESEOFRAMEWORK_PLUGIN_DIR')) {
            define('WPGraphQL_THESEOFRAMEWORK_PLUGIN_DIR', plugin_dir_path(__FILE__ . '/..'));
        }

        // Plugin Folder URL.
        if (!defined('WPGraphQL_THESEOFRAMEWORK_PLUGIN_URL')) {
            define('WPGraphQL_THESEOFRAMEWORK_PLUGIN_URL', plugin_dir_url(__FILE__ . '/..'));
        }

        // Plugin Root File.
        if (!defined('WPGraphQL_THESEOFRAMEWORK_PLUGIN_FILE')) {
            define('WPGraphQL_THESEOFRAMEWORK_PLUGIN_FILE', __FILE__ . '/..');
        }

    }

    /**
     * Include required files.
     * Uses composer's autoload
     *
     * @access private
     * @return void
     */
    private function includes()
    {

        // Autoload Required Classes.
    }

    /**
     * Initialize
     */
    private function init()
    {

        $content_nodes = new ContentNodes();
        add_action('graphql_register_types', [$content_nodes, 'init'], 10, 1);

        $seo_settings = new SiteSettings();
        add_action('graphql_register_types', [$seo_settings, 'init'], 10, 1);

    }

}
