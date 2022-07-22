# WPGraphQL for The Seo Framework

This plugin adds The Seo Framework support to WP GraphQL.

Supports:

-   Pages
-   Posts
-   Custom post types
-   Categories
-   Custom taxonomies
-   The Seo Framework Settings
    -   Webmaster verification
    -   Social settings
    -   Schema Presence

## Pre-req's

Using this plugin requires having the [WPGraphQL](https://github.com/wp-graphql/wp-graphql) and [The Seo Framework](https://wordpress.org/plugins/autodescription) (free or pro) installed and activated.

## Activating

Activate the plugin like you would any other WordPress plugin.

Once the plugin is active, the `seo` argument will be available to any post object connectionQuery
(posts, pages, custom post types, etc).

## Usage

### Post Type Data

```
query PostsSeo {
  posts(first: 10) {
    nodes {
      seo {
        title
        description
        canonical
        images {
          altText
          uri
          mediaDetails {
            height
            width
          }
        }
        opengraph {
          description
          title
          type
        }
        twitter {
          description
          title
        }
        metaRobotsNofollow
        metaRobotsNoarchive
        metaRobotsNoindex
        redirect
      }
    }
  }
}
```

### Taxonomy Data

```
query CategoriesSeo {
  categories(first: 10) {
    nodes {
      seo {
        title
        description
        canonical
        images {
          altText
          uri
          mediaDetails {
            height
            width
          }
        }
        opengraph {
          description
          title
          type
        }
        twitter {
          description
          title
        }
        metaRobotsNofollow
        metaRobotsNoarchive
        metaRobotsNoindex
        redirect
      }
    }
  }
}
```

### Settings Data

```
query SeoSettings {
  seoSettings {
    separator
    presence {
      logo
      name
      type
    }
    webmaster {
      baiduVerification
      googleVerification
      pinterestVerification
      yandexVerification
      bingVerification
    }
    social {
      facebook {
        appId
        authorFallback
        publisher
      }
      twitter {
        authorFallback
        cardType
        profile
      }
    }
  }
}
```
