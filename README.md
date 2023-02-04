# Timeline Plugin

**Table of Contents**
- [Description](#description)
- [Examples of use](#examples)
- [Configuration](#configuration)
  - [Settings and Usage](#settings-and-usage)
- [Adding Timelines and Events](#adding-timelines-and-events)
  - [Step by step](#step-by-step)
  - [Adding a header or a footer](#header-and-footer)
  - [Customizing templates](#header-and-footer)
  - [Strategies for large timelines](#strategies)
- [Installation](#installation)
  - [Requirements](#requirements)
  - [Grav Package Manager](#gpm)
  - [Manually](#manually)
- [Printing](#printing)

![Timeline](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/assets/readme.png)

<a name="description"/>

## Description

The **Timeline**-plugin is for [Grav CMS](http://github.com/getgrav/grav), and lets you create and manage timelines in an ordered, hierarchical manner. Timelines can be nested within each other, minutely customized on a Page-level, and further customized with your own templates and styles. A [demo is available](https://olevik.me/staging/grav-skeleton-timeline), and demo content can be found in [/pages](https://github.com/OleVik/grav-skeleton-timeline/tree/master/pages).

### Changes in version 2

**Note: Version 2.0.0 contains API-changes, and so your plugin-configuration and files will need to be updated.** Please see the [CHANGELOG](/CHANGELOG.md). Most notably:

- Templates must call `build_timeline()` and `build_linkeddata()` themselves, see [timeline.html.twig#L27](https://github.com/OleVik/grav-plugin-timeline/blob/v2.0.0-beta.1/templates/timeline.html.twig#L27) and [timeline.html.twig#L80](https://github.com/OleVik/grav-plugin-timeline/blob/v2.0.0-beta.1/templates/timeline.html.twig#L80)
- `language`-setting in plugin-configuration and FrontMatter is now `locale`
- `limit`-setting in plugin-configuration and FrontMatter defaults to `8`

<a name="examples"/>

## Examples of use

- Colin M. Madland has used the plugin to document a [Critical Family History](https://grav.madland.ca/writing/timeline)

<a name="configuration"/>

## Configuration

Before configuring this plugin, you should copy the `user/plugins/timeline/timeline.yaml` to `user/config/plugins/timeline.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
language: en
order:
  by: date
  dir: asc
cache: native
truncate: 100
```

<a name="settings-and-usage"/>

### Settings and Usage

| Variable | Default | Options | Note |
|----------|---------|-------------------------------------------------|--------------------------------------------------------------------------------------------|
| enabled | true | `true` or `false` | Enables or disables the plugin. |
| locale | en | string (2-3) | Any two- or three-letter (ISO 639-2 or 639-3) language code. |
| order: |  |  |  |
|   by | date | `date`, `title`, or `folder` | Orders Pages according to date, title, or folder-name. |
|   dir | asc | `asc` or `desc` | Order Pages ascending or descending. |
| cache | native | `native`, `persist`, `transient`, or `disabled` | Where to store plugin's internal data. |
| truncate | 100 | int or boolean | Limits the amount of words in each note, to an integer or boolean state for default (100). |
| truncate_image | false | boolean | Prevent images from Events rendering in Timeline. |
| linked_data | true | boolean | Generate Linked Data from Timelines and Events. |
| limit | 16 | int | Limit how deep to render a nested Timeline. |

Each timeline is structured with a Header (`timeline.md`, Timeline-template) and Events (`timeline_event.md`, Timeline Event-template). Headers are used as separators and can order their descendant Events, as well as contain normal fields such as `title`, `subtitle`, and `date`. Dates should be as exhaustive as possible, and are best expressed in the [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601)-format: `2020-12-29` for a date or `2020-12-29T16:36:49+00:00` for date and time.

Events render a formatted, localized `date` using the `locale`-setting, as well as an `image`. In addition, Events are cast as Linked Data with [JSON-LD](https://json-ld.org/), wherein `type`, `place`, `locality`, and `region` are used.

**Note: Events' dates are used for sorting and structuring the Timeline, so it's important that this information can be correctly parsed by the plugin.**

Sound confusing? It's much easier to do all this from the [Admin](https://github.com/getgrav/grav-plugin-admin)-plugin.

<a name="adding-timelines-and-events"/>

## Adding Timelines and Events

The plugin assumes some knowledge of basics with Grav, largely [Pages](https://learn.getgrav.org/16/content/content-pages) and [FrontMatter](https://learn.getgrav.org/16/content/headers). Any Page that you create in Grav with the filename `timeline.md` is a wrapper for the Timeline, and will take any Pages created with the filename `timeline_event.md` as its Events.

Consider the Constantinian Dynasty, in [this folder](https://github.com/OleVik/grav-skeleton-timeline/tree/master/pages/timeline/Dominate/Constantinian). The initial [timeline.md](https://github.com/OleVik/grav-skeleton-timeline/blob/master/pages/timeline/Dominate/Constantinian/timeline.md) creates the Page at [domain.tld/timeline/dominate](https://olevik.me/staging/grav-skeleton-timeline/timeline/dominate), and all the Pages below it are Events - each with their own [timeline_event.md](https://github.com/OleVik/grav-skeleton-timeline/blob/master/pages/timeline/Dominate/Constantinian/julian/timeline_event.md), like Julian at [domain.tld/timeline/dominate/constantinian/julian](https://olevik.me/staging/grav-skeleton-timeline/timeline/dominate/constantinian/julian).

<a name="step-by-step"/>

### Step by step

1. Create a Page for your Timeline, for example a folder like /user/pages/mytimeline
2. In this folder, create a file named `timeline.md`
3. Edit this file like any normal Page in Grav: Add some FrontMatter and Content ([example](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/pages/timeline/timeline.md))
    - This is your actual Timeline, but not the Events within it
4. For each Event in the Timeline, create a subfolder, like /user/pages/mytimeline/myevent
5. In this folder, create a file named `timeline_event.md`
6. Edit this file like any normal Page in Grav too: Add FrontMatter and Content ([example](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/pages/timeline/Dominate/Constantinian/consantius-ii/timeline_event.md))

#### In the Admin-panel

If you are using the Admin-plugin, you will need to create a Page using the Timeline or Timeline Event template:

1. Go to the Pages-administration:

![Pages-list in Admin](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/assets/Pages-list_Grav-1.7.0_Admin-1.10.0.png)

Clicking the Add Page button yields:

![Add Page Dialog](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/assets/Add_Page_Dialog.png)

##### Editing a Timeline

![Editing a Timeline](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/assets/header-page.png)

##### Editing an Event

![Editing an Event](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/assets/event-page.png)

##### Event Options

![Event Options](https://raw.githubusercontent.com/OleVik/grav-skeleton-timeline/master/assets/event-options.png)

<a name="header-and-footer"/>

### Adding a header or a footer, or customizing templates

Your theme can override, extend or otherwise customize the [`timeline.html.twig`](https://github.com/OleVik/grav-plugin-timeline/blob/master/templates/timeline.html.twig)-template just by creating a local copy in `/user/themes/mytheme/templates/timeline.html.twig`. If you just want to add a header or footer, for a menu or copyright notice for example, you can use the [`partials/timeline_header.html.twig`](https://github.com/OleVik/grav-plugin-timeline/blob/master/templates/partials/timeline_header.html.twig) and [`partials/timeline_footer.html.twig`](https://github.com/OleVik/grav-plugin-timeline/blob/master/templates/partials/timeline_footer.html.twig) templates.

These are empty in the plugin's directory, but anything inside them will be included by the plugin's main template. Just place them in `/user/themes/mytheme/templates/partials`. You could also call the [`timeline`-block](https://github.com/OleVik/grav-plugin-timeline/blob/master/templates/timeline.html.twig) from the main template using the [block-function](https://twig.symfony.com/doc/1.x/functions/block.html).

<a name="strategies"/>

### Strategies for large timelines

The following tips are not implemented in the plugin by default, because they encompass advanced usage which should extend the plugin, rather than be included by default.

#### Large, nested Timelines

The structure of a Timeline's data is hierarchical, not flat, and as such pagination can not effectively be implemented. However, you most likely do not want pagination, but rather to distinguish the periods of the Timeline. There are two ways to reduce the load from a large Timeline:

1. Construct the Timeline into smaller series, nesting it further, so that less Events are loaded at once. You'll also need to set the `limit`-setting to the maximum depth to render. Note that this is applied to each Timeline-constructed, so you can have a low limit set, which won't prevent rendering larger structures further down.
2. Alternatively, destructure the Timeline into smaller series, but make them distinct Pages, thereby reducing nesting. This would entail lifting your nested Timelines up one or more levels in the Pages-hierarchy.

#### Many assets load at once from Events

If many images, videos or other media are loaded in Events, they'll all load at once when the Timeline renders, causing a large increase in the Page's weight. To avoid loading them all at once, implement [lazy-loading](https://www.smashingmagazine.com/2019/05/hybrid-lazy-loading-progressive-migration-native/). There are many [solutions for this](https://www.npmjs.com/search?q=keywords:lazy-loading) available, and Google has a [substantial guide](https://web.dev/fast/#lazy-load-images-and-video).

#### Collapsing periods

Each subordinate Timeline has the classes `.timeline-item.period`, and each of its Events have the classes `.timeline-item` with `.odd` or `.even`. As such, if you are familiar with JavaScript, you could collapse the Timeline - at any level - and its Events by just traversing the DOM between each `.period`.

<a name="installation"/>

## Installation

Installing the Timeline-plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

<a name="requirements"/>

### Requirements

**This plugin will only work with [Grav v1.6](https://github.com/getgrav/grav/tree/1.6) or higher, as it requires [PHP v7.1.3](http://php.net/manual/en/migration71.new-features.php) or higher.** Dropping this requirement would mean devolving features, which the developer is not inclined to do.

<a name="gpm"/>

### Grav Package Manager

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line). From the root of your Grav install type:

    bin/gpm install timeline

This will install the Timeline-plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/timeline`.

<a name="manually"/>

### Manually

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `timeline`. You can find these files on [GitHub](https://github.com/ole-vik/grav-plugin-timeline) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/timeline
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

<a name="printing"/>

## Printing

Two modes of printing are supported: **Pure TexT** or **Graphics**.

By default, graphics are included, and the layout will typically emulate mobile-view. Events will not alternate between odd and even.

To print pure text, remove the `print`-class from `<div class="print timeline-wrapper col-12 col-sm-10 col-sm-offset-1 col-md-8 offset-md-2">` in [timeline.html.twig](https://github.com/OleVik/grav-plugin-timeline/blob/master/templates/timeline.html.twig).

<a name="development"/>

## Development

Use a SCSS-compiler, like LibSass, eg. node-sass and compile `assets/timeline.scss` to `assets/timeline.css` in the plugin-folder. For example: `node-sass --watch --source-map true assets/timeline.scss assets/timeline.css`.

## License

This theme is free and open source software, distributed under the [MIT License](/LICENSE).

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FOleVik%2Fgrav-plugin-timeline.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FOleVik%2Fgrav-plugin-timeline?ref=badge_large)
