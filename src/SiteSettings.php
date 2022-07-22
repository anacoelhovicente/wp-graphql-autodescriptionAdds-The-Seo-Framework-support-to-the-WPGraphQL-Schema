<?php
/**
 * Config for WPGraphQL The Seo Framework settings
 *
 * @package WP_Graphql_Auto_Description
 */

namespace WPGraphQL\AutoDescription;

use Exception;
use WPGraphQL\Registry\TypeRegistry;

/**
 * SEO Framework settings class.
 */
class SiteSettings
{

    /**
     * @var The_SEO_Framework
     */
    protected $seo_framework;

    /**
     * @var TypeRegistry
     */
    protected $type_registry;

    /**
     * @var array <string> List of field names registered to the Schema
     */
    protected $registered_field_names;

    /**
     *  Register The Seo Framework settings fields
     *
     * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry
     *
     * @throws Exception
     */
    public function init(TypeRegistry $type_registry)
    {
        $this->type_registry = $type_registry;
        $this->seo_framework = the_seo_framework();

        $this->register_initial_types();
        $this->add_settings_fields();
    }

    /**
     * Add SEO data to the settings graphQL response.
     */
    protected function add_settings_fields()
    {
        register_graphql_field('RootQuery', 'seoSettings', [
            'type' => 'SeoSettings',
            'description' => __('The SEO Framework settings', 'wp-graphql-autodescription'),
            'resolve' => function ($root, $args, $context, $info) {
                // Whether to output knowledge fields
                $knowledge_output = $this->get_option('knowledge_output');
                $knowledge_type = $this->get_option('knowledge_type');

                return [
                    'separator' => Utils::format_string($this->seo_framework->get_separator()),
                    'siteUrl' => Utils::get_home_url(),
                    'webmaster' => [
                        'googleVerification' => $this->get_option('google_verification'),
                        'bingVerification' => $this->get_option('bing_verification'),
                        'yandexVerification' => $this->get_option('yandex_verification'),
                        'baiduVerification' => $this->get_option('baidu_verification'),
                        'pinterestVerification' => $this->get_option('pint_verification'),
                    ],
                    'presence' => $knowledge_output ? [
                        'logo' => Utils::format_string($this->seo_framework->get_knowledge_logo()),
                        'name' => $this->get_option('knowledge_name') ?: get_bloginfo('name'),
                        'type' => $knowledge_type ? ucfirst($knowledge_type) : null,
                    ] : null,
                    'social' => [
                        'facebook' => [
                            'appId' => $this->get_option('facebook_appid'),
                            'publisher' => $this->get_option('facebook_publisher'),
                            'authorFallback' => $this->get_option('facebook_author'),
                        ],
                        'twitter' => [
                            'cardType' => $this->get_option('twitter_card'),
                            'profile' => $this->get_option('twitter_site'),
                            'authorFallback' => $this->get_option('twitter_creator'),
                        ],
                    ],
                ];
            },
        ]);
    }

    /**
     * Registers initial Types for use with The Seo Framework Fields
     *
     * @throws Exception
     */
    protected function register_initial_types()
    {
        $this->type_registry->register_object_type(
            'SeoWebmaster',
            [
                'description' => __('Webmaster Integration Settings', 'autodescription'),
                'fields' => [
                    'googleVerification' => [
                        'type' => 'String',
                        'description' => __('Google Search Console Verification Code', 'autodescription'),
                    ],
                    'bingVerification' => [
                        'type' => 'String',
                        'description' => __('Bing Webmaster Verification Code', 'autodescription'),
                    ],
                    'yandexVerification' => [
                        'type' => 'String',
                        'description' => __('Yandex Webmaster Verification Code', 'autodescription'),
                    ],
                    'baiduVerification' => [
                        'type' => 'String',
                        'description' => __('Baidu Search Resource Platform Code', 'autodescription'),
                    ],
                    'pinterestVerification' => [
                        'type' => 'String',
                        'description' => __('Pinterest Analytics Verification Code', 'autodescription'),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type(
            'SeoSettingsFacebook',
            [
                'description' => __('Facebook Integration Settings', 'autodescription'),
                'fields' => [
                    'appId' => [
                        'type' => 'String',
                        'description' => __('Facebook App ID', 'autodescription'),
                    ],
                    'publisher' => [
                        'type' => 'String',
                        'description' => __('Facebook Publisher page', 'autodescription'),
                    ],
                    'authorFallback' => [
                        'type' => 'String',
                        'description' => __('Facebook Author Fallback Page', 'autodescription'),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type(
            'SeoSettingsTwitter',
            [
                'description' => __('Twitter Integration Settings', 'autodescription'),
                'fields' => [
                    'cardType' => [
                        'type' => 'String',
                        'description' => __('Twitter Card Type', 'autodescription'),
                    ],
                    'profile' => [
                        'type' => 'String',
                        'description' => __('Website Twitter Profile', 'autodescription'),
                    ],
                    'authorFallback' => [
                        'type' => 'String',
                        'description' => __('Twitter Author Fallback Profile', 'autodescription'),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type(
            'SeoSocial',
            [
                'description' => __('Social Meta Tags Settings', 'autodescription'),
                'fields' => [
                    'facebook' => [
                        'type' => 'SeoSettingsFacebook',
                        'description' => __('Facebook Integration Settings', 'autodescription'),
                    ],
                    'twitter' => [
                        'type' => 'SeoSettingsTwitter',
                        'description' => __('Facebook Integration Settings', 'autodescription'),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type(
            'SeoPresence',
            [
                'description' => __('Authorized Presence Options', 'autodescription'),
                'fields' => [
                    'type' => [
                        'type' => 'String',
                        'description' => __('Wether website represents an organization or person', 'wp-graphql-autodescription'),
                    ],
                    'logo' => [
                        'type' => 'String',
                        'description' => __('Logo URL', 'autodescription'),
                    ],
                    'name' => [
                        'type' => 'String',
                        'description' => __('The organization or personal name', 'autodescription'),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type('SeoSettings', [
            'description' => __('The Seo Framework settings fields', 'wp-graphql-autodescription'),
            'fields' => [
                'separator' => [
                    'type' => 'String',
                    'description' => __('Title separator setting for seo titles', 'wp-graphql-autodescription'),
                ],
                'siteUrl' => [
                    'type' => 'String',
                    'description' => __('Website url', 'wp-graphql-autodescription'),
                ],
                'presence' => [
                    'type' => 'SeoPresence',
                    'description' => __('Authorized Presence Options', 'autodescription'),
                ],
                'social' => [
                    'type' => 'SeoSocial',
                    'description' => __('Social Meta Tags Settings', 'autodescription'),
                ],
                'webmaster' => [
                    'type' => 'SeoWebmaster',
                    'description' => __('Webmaster Integration Settings', 'autodescription'),
                ],
            ],
        ]);
    }

    /**
     * Get and format SEO framework option
     *
     * @param string $option_key SEO Framework option key.
     * @param array $fields Queried fields
     * @param string $field_key WPGraphql field key.
     * @return mixed
     */
    private function get_option($option_key)
    {
        $value = $this->seo_framework->get_option($option_key);

        return Utils::format_string($value);
    }
}
