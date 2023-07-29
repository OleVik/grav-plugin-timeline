# v2.2.0
## 29-07-2023

1. [](#new)
    * Publish 2.2.0
2. [](#bugfix)
    * Demo-link

# v2.2.0-rc.1
## 12-02-2023

1. [](#new)
    * Filter timeline by date range
2. [](#improved)
    * Processing overhead
    * Qode-quality

# v2.1.2
## 10-06-2021

1. [](#bugfix)
    * Autoload-fallback

# v2.1.1
## 02-05-2021

1. [](#bugfix)
    * Remove debug-message

# v2.1.0
## 02-05-2021

1. [](#improved)
    * Bumped spatie/schema-org
    * Bumped nesbot/carbon

# v2.0.1
## 04-03-2021

1. [](#bugfix)
    * Bad, bad elements
    * Copying and pasting at the speed of light

# v2.0.0
## 20-01-2021

1. [](#new)
    * Stable release

# v2.0.0-beta.1
## 30-12-2020

1. [](#new)
    * Support for alternative text with `image_alt`-property
    * Support for image caption with `image_caption`-property, including Markdown
    * Support for header image in `timeline.html.twig`
    * `truncate_image`-setting, to hide header image(s)
    * Replaced `language`-setting with `locale`
    * Expose `Content->buildTree()`-method as `build_timeline` in Twig
    * Expose `LinkedData->buildTree()`-method as `build_linkeddata` in Twig
    * Set `timeline_content` in Twig, not PHP, by default
    * Set `application/ld+json` in Twig, not PHP, by default
    * Render date in template
2. [](#improved)
    * Build tree-data in template rather than globally
    * Build Linked Data in template rather than globally
    * Reduce deprecation-warnings
    * Locale-handling `dt`-function in Twig
    * Replaced `jenssegers/date` with `nesbot/carbon`-dependency
    * Increase supported locales from 136 to 305
    * Remove `phpextra/sorter`-dependency
    * Remove `ext-intl`-dependency
    * PSR-4 autoloading and code-quality
    * Blueprints
    * README-documentation
3. [](#bugfix)
    * Do not truncate standalone Page(s)

# v1.3.3
## 17-07-2020

1. [](#bugfix)
    * Blueprints
    * Caching-logic

# v1.3.2
## 26-02-2020

1. [](#improved)
    * Link to right branch in Docs-link

# v1.3.1
## 16-06-2019

1. [](#improved)
    * Conditional Linked Data

# v1.3.0
## 16-06-2019

1. [](#new)
    * Deprecate FileStorage
    * Bump Grav-dependency
2. [](#improved)
    * Event-blueprint formatting

# v1.2.1
## 06-06-2019

1. [](#improved)
    * Raw-filter assets

# v1.2.0
## 07-12-2018

1. [](#new)
    * Added template-partials and blocks for header and footer
    * Change template-partial name from "header" to "period"
    * Added functionality for injecting timelines
    * Added support for subdirectory-installations
2. [](#improved)
    * Code quality
    * .header-styling
    * Pathing

# v1.1.6
## 30-11-2018

1. [](#improved)
    * Added blueprint option for linked_data

# v1.1.5
## 30-11-2018

1. [](#bugfix)
    * Fix LinkedData-assets
    * Fix misaligned events

# v1.1.4
## 30-11-2018

1. [](#bugfix)
    * Fix missing orderBy-options
    * Fix Utilities::sortLeaf defaults

# v1.1.3
## 30-11-2018

1. [](#bugfix)
    * Fix missing `static`-keyword

# v1.1.2
## 27-11-2018

1. [](#bugfix)
    * Fix event template
2. [](#improved)
    * Add image alt-attribute

# v1.1.1
## 27-10-2018

1. [](#improved)
    * Restrict CSS to template and related elements

# v1.1.0
## 26-10-2018

1. [](#improved)
    * Abstracted tree-building from page-context
    * Moved some methods to Utilities
    * Visual flow in Print-style, .print-class for graphics
    * Responsive-styles
    * README
2. [](#new)
    * Standalone-template for Timeline-event
    * Added Data-API for UML-, JSON-, and Markdown-outputs
    * Added `dump`-command for Data-API access
    * CSS asset-group 'bottom' for non-render-blocking stylesheets
3. [](#bugfix)
    * CSS-fixes to enforce uniform styling
    * Restrict assets to Timeline-pages

# v1.0.2
## 13-09-2018

1. [](#new)
    * Print-friendly stylesheet
    * Native Bootstrap
2. [](#improved)
    * SCSS-formatting

# v1.0.1
## 09-09-2018

1. [](#bugfix)
    * Plugin requires Grav at least v1.6
    * Plugin requires PHP at least v7.1

# v1.0.0
## 09-09-2018

1. [](#new)
    * Initial public release
