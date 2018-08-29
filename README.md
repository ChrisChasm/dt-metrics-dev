[![Build Status](https://travis-ci.org/DiscipleTools/disciple-tools-advanced-metrics.svg?branch=master)](https://travis-ci.org/DiscipleTools/disciple-tools-advanced-metrics)

# Disciple Tools Advanced Metrics
The Disciple Tools Advanced Metrics is intended to accelerate integrations and extensions to the Disciple Tools system.
This basic plugin starter has some of the basic elements to quickly launch and extension project in the pattern of
the Disciple Tools system.


### The starter plugin is equipped with:
1. Wordpress style requirements
1. Travis Continueous Integration
1. Disciple Tools Theme presence check
1. Remote upgrade system for ongoing updates outside the Wordpress Directory
1. Multilingual ready
1. PHP Code Sniffer support (composer) @use /vendor/bin/phpcs and /vendor/bin/phpcbf
1. Starter Admin menu and options page with tabs.

### Refactoring this plugin as your own:
1. Refactor all occurences of the name DT_Advanced_Metrics, dt_advanced_metrics, and Advanced Metrics with you're own plugin
name for the `disciple-tools-advanced-metrics.php and admin-menu-and-tabs.php files.
1. Update the README.md and LICENSE
1. Update the default.pot file if you intend to make your plugin multilingual. Use a tool like POEdit