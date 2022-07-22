<?php
/**
 * Helper functions for WPGraphQL AutoDescription
 *
 * @package wp-graphql-autodescription
 */

namespace WPGraphQL\AutoDescription;

class Utils
{
    /**
     * Convert empty string values to null
     *
     * @param mixed $value The value to check.
     * @return mixed
     */
    public static function format_string($string)
    {
        return isset($string) && $string !== '' ? html_entity_decode(trim($string)) : null;
    }

    /**
     * Get homepage url
     *
     * @return string
     */
    public static function get_home_url()
    {
        if (defined('HEADLESS_FRONTEND_URL')) {
            return HEADLESS_FRONTEND_URL;
        }

        return get_home_url();

    }
}
