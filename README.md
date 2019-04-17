# Timeline Plugin

The **Timeline**-plugin is for [Grav CMS](http://github.com/getgrav/grav), and lets you create and manage timelines in an ordered, hierarchical manner. Timelines can be nested within each other, minutely customized on a page-level, and further customized with your own templates and styles. A [demo is available](https://olevik.me/staging/grav-skeleton-timeline), and demo content can be found in [/pages](https://github.com/OleVik/grav-skeleton-timeline/tree/master/pages).

## Installation

Installing the Timeline-plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### Requirements

**This plugin will only work with [Grav v1.6](https://github.com/getgrav/grav/tree/1.6) or higher, as it requires [PHP v7.1](http://php.net/manual/en/migration71.new-features.php) or higher. Dropping this requirement would mean devolving features, which the author is not inclined to do.**

The [intl](http://php.net/manual/en/book.intl.php)-extension for PHP must be [installed and enabled](http://php.net/manual/en/intl.installation.php "See especially User Contributed Notes") to use this plugin.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line). From the root of your Grav install type:

    bin/gpm install timeline

This will install the Timeline-plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/timeline`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `timeline`. You can find these files on [GitHub](https://github.com/ole-vik/grav-plugin-timeline) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/timeline
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the **user/plugins/timeline/timeline.yaml** to **user/config/plugins/timeline.yaml** and only edit that copy.

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

### Settings and Usage

| Variable | Default | Options | Note |
|----------|---------|-------------------------------------------------|--------------------------------------------------------------------------------------------|
| enabled | true | `true` or `false` | Enables or disables the plugin. |
| language | en | string (2) | Any two-letter (ISO-639-1) language code. |
| order: |  |  |  |
|   by | date | `date`, `title`, or `folder` | Orders pages according to date, title, or folder-name. |
|   dir | asc | `asc` or `desc` | Order pages ascending or descending. |
| cache | native | `native`, `persist`, `transient`, or `disabled` | Where to store plugin's internal data. |
| truncate | 100 | int or boolean | Limits the amount of words in each note, to an integer or boolean state for default (100). |
| linked_data | true | `true` or `false` | Enables or disables Linked Data output. |
| inject_timeline | null | string | Route to page to inject. |
| inject_period | false | `true` or `false` | Inject page as timeline-period if true, only events if false. |

Each timeline is structured with a Header (**timeline.md**, Timeline-template) and Events (**timeline_event.md**, Timeline Event-template). Headers are used as separators and can order their descendant Events, as well as contain normal fields such as `title`, `subtitle`, and `content`. Events also render a formatted, localized `date` (using [`date_format`](http://php.net/manual/en/function.date.php) and `locale`), as well as an `image`. In addition, Events are cast as Linked Data with [JSON-LD](https://json-ld.org/), wherein `type`, `place`, `locality`, and `region` are used.

Timelines can be mixed together from different locations in `/user/pages` by using the `inject_timeline`-field. Point it to a route (not path) where another timeline exists, and it will be added at the end of the current timeline. This can be done from any page that is a timeline, but not from an event. Use `inject_period` to designate that the page itself should be included, to demarcate a new period immediately following the timeline it was injected from.

#### Printing

Two modes of printing are supported: **Pure TexT** or **Graphics**.

By default, graphics are included, and the layout will typically emulate mobile-view. Events will not alternate between odd and even.

To print pure text, remove the `print`-class from `<div class="print timeline-wrapper col-12 col-sm-10 col-sm-offset-1 col-md-8 offset-md-2">` in [timeline.html.twig](https://github.com/OleVik/grav-plugin-timeline/blob/master/templates/timeline.html.twig).

## Development

Use a SCSS-compiler, like LibSass, eg. node-sass and compile `assets/timeline.scss` to `assets/timeline.css` in the plugin-folder. For example: `node-sass --watch --source-map true assets/timeline.scss assets/timeline.css`.


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FOleVik%2Fgrav-plugin-timeline.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FOleVik%2Fgrav-plugin-timeline?ref=badge_large)
