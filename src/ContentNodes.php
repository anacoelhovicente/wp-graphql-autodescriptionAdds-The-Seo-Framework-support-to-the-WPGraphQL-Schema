<?php
/**
 * Config for WPGraphQL AutoDescription
 *
 * @package WP_Graphql_Auto_Description
 */

namespace WPGraphQL\AutoDescription;

use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Registry\TypeRegistry;

/**
 * Config class.
 */
class ContentNodes
{

    /**
     * @var TypeRegistry
     */
    protected $type_registry;

    /**
     * @var array <string> List of field names registered to the Schema
     */
    protected $registered_field_names;

    /**
     * Register The Seo Framework content nodes fields
     *
     * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry
     *
     * @throws Exception
     */
    public function init(TypeRegistry $type_registry)
    {

        /**
         * Set the TypeRegistry
         */
        $this->type_registry = $type_registry;
        $this->register_initial_types();

        /**
         * Add The Seo Framework Fields to GraphQL Types
         */
        $this->add_fields_to_graphql_types();
    }

    /**
     * Adds Seo fields to GraphQL types.
     */
    protected function add_fields_to_graphql_types()
    {
        foreach (\WPGraphQL::get_allowed_post_types() as $post_type) {
            $this->add_post_type_fields(get_post_type_object($post_type));
        }

        foreach (\WPGraphQL::get_allowed_taxonomies() as $taxonomy) {
            $this->add_taxonomy_fields(get_taxonomy($taxonomy));
        }
    }

    /**
     * Add SEO data to the post graphQL response.
     *
     * @param WP_Post_Type $post_type_object The post object.
     */
    protected function add_post_type_fields(\WP_Post_Type $post_type_object)
    {
        register_graphql_field(
            $post_type_object->graphql_single_name,
            'seo',
            [
                'type' => 'Seo',
                'description' => __('Post type Seo fields', 'wp-graphql-autodescription'),
                'resolve' => function ($post, array $args, \WPGraphQL\AppContext $context, ResolveInfo $info) {
                    if (!$post) {
                        return null;
                    }

                    $fields = $info->getFieldSelection(2);

                    return $this->get_item_seo($context, $fields, [
                        'id' => $post->ID,
                    ]);
                },
            ]
        );

    }

    /**
     * Add SEO data to the post graphQL response.
     *
     * @param WP_Post_Type $taxonomy The taxonomy object.
     */
    protected function add_taxonomy_fields(\WP_Taxonomy $taxonomy)
    {
        register_graphql_field(
            $taxonomy->graphql_single_name,
            'seo',
            [
                'type' => 'Seo',
                'description' => __('Taxonomy Seo fields', 'wp-graphql-autodescription'),
                'resolve' => function ($term, array $args, AppContext $context, ResolveInfo $info) use ($taxonomy) {
                    if (!$term) {
                        return null;
                    }

                    $fields = $info->getFieldSelection(2);

                    return $this->get_item_seo($context, $fields, [
                        'id' => $term->term_id,
                        'taxonomy' => $taxonomy->name,
                    ]);
                },
            ]
        );

    }

    /**
     * Registers initial Types for use with The Seo Framework Fields
     */
    protected function register_initial_types()
    {
        $this->type_registry->register_object_type(
            'SeoOpengraph',
            [
                'description' => __('The Seo Framework opengraph meta tags', 'wp-graphql-autodescription'),
                'fields' => [
                    'title' => [
                        'type' => 'String',
                        'description' => __(
                            'Opengraph title',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'description' => [
                        'type' => 'String',
                        'description' => __(
                            'Opengraph description',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'type' => [
                        'type' => 'String',
                        'description' => __(
                            'Opengraph type',
                            'wp-graphql-autodescription'
                        ),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type(
            'SeoTwitter',
            [
                'description' => __('The Seo Framework twitter meta tags', 'wp-graphql-autodescription'),
                'fields' => [
                    'title' => [
                        'type' => 'String',
                        'description' => __(
                            'Twitter Title',
                            'autodescription'
                        ),
                    ],
                    'description' => [
                        'type' => 'String',
                        'description' => __(
                            'Twitter Description',
                            'autodescription'
                        ),
                    ],
                ],
            ]
        );

        $this->type_registry->register_object_type(
            'Seo',
            [
                'description' => __('The Seo Framework fields', 'wp-graphql-autodescription'),
                'fields' => [
                    'title' => [
                        'type' => 'String',
                        'description' => __(
                            'Seo post title',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'description' => [
                        'type' => 'String',
                        'description' => __(
                            'Seo post description',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'images' => [
                        'type' => ['list_of' => 'MediaItem'],
                        'description' => __(
                            'Opengraph image',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'canonical' => [
                        'type' => 'String',
                        'description' => __(
                            'Seo canonical url',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'metaRobotsNoarchive' => [
                        'type' => 'Boolean',
                        'description' => __(
                            'Archiving',
                            'autodescription'
                        ),
                    ],
                    'metaRobotsNofollow' => [
                        'type' => 'Boolean',
                        'description' => __(
                            'Link following',
                            'autodescription'
                        ),
                    ],
                    'metaRobotsNoindex' => [
                        'type' => 'Boolean',
                        'description' => __(
                            'Indexing',
                            'autodescription'
                        ),
                    ],
                    'redirect' => [
                        'type' => 'String',
                        'description' => __(
                            '301 Redirect URL',
                            'autodescription'
                        ),
                    ],
                    'opengraph' => [
                        'type' => 'SeoOpengraph',
                        'description' => __(
                            'Seo opengraph fields',
                            'wp-graphql-autodescription'
                        ),
                    ],
                    'twitter' => [
                        'type' => 'SeoTwitter',
                        'description' => __(
                            'The Seo Framework twitter meta tags',
                            'wp-graphql-autodescription'
                        ),
                    ],
                ],
            ]
        );

    }

    /**
     * Get the queried item meta fields
     *
     * @param AppContext  $context The context of the query to pass along
     * @param Array $fields The queried fields.
     * @param Array $query The object to query.
     * @return Seo
     */
    protected function get_item_seo($context, array $fields, array $query)
    {
        $seo_framework = the_seo_framework();
        $meta_robots = $seo_framework->generate_robots_meta($query);

        $seo = [
            'title' => Utils::format_string($seo_framework->get_title($query)),
            'description' => Utils::format_string($seo_framework->get_description($query)),
            'canonical' => null,
            'metaRobotsNoarchive' => isset($meta_robots['noarchive']) ?? false,
            'metaRobotsNofollow' => isset($meta_robots['nofollow']) ?? false,
            'metaRobotsNoindex' => isset($meta_robots['noindex']) ?? false,
            'redirect' => Utils::format_string($seo_framework->get_redirect_url($query)),
            'images' => null,
            'opengraph' => isset($fields['opengraph']) ? [
                'title' => Utils::format_string($seo_framework->get_open_graph_title($query)),
                'description' => Utils::format_string($seo_framework->get_open_graph_description($query)),
                'type' => Utils::format_string($seo_framework->get_og_type($query)),
            ] : null,
            'twitter' => isset($fields['twitter']) ? [
                'title' => Utils::format_string($seo_framework->get_twitter_title($query)),
                'description' => Utils::format_string($seo_framework->get_twitter_description($query)),
            ] : null,
        ];

        if (isset($fields['canonical'])) {
            $wp_home_url = get_home_url();
            $home_url = Utils::get_home_url();
            $seo['canonical'] = $seo_framework->create_canonical_url($query);

            if ($home_url !== $wp_home_url) {
                $seo['canonical'] = str_replace($wp_home_url, $home_url, $seo['canonical']);
            }
        }

        if (isset($fields['images'])) {
            $images = $seo_framework->get_image_details($query, true);

            if ($images && count($images) > 0) {
                $seo['images'] = array_map(function ($image) use ($context) {
                    return $context->get_loader('post')->load_deferred(absint($image['id']));
                }, $images);
            }
        }

        return $seo;
    }
}
