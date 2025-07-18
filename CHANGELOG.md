# Changelog

## \[1.2.0] - 2025-07-18

### Added

* Support for tab activation via anchor links (e.g., `#tab1`, `#tab2`) instead of URL query parameters.
* Dynamic tab switching based on the current URL hash.
* Automatic assignment of the `active` class to the corresponding tab navigation link when selected.
* Special handling for `#courseTabContent`, which maps to `#tab1` and triggers the correct tab and link state.
* Hash change listener (`window.onhashchange`) to support browser navigation (back/forward) between tabs.

### Changed

* Internal mapping logic to allow flexibility between hash values and actual tab pane IDs.

### Fixed

* Navigation links now correctly generate `#tabX` anchors instead of `?tab=tabX` URLs.
* Fixed an issue where clicking the `#courseTabContent` link did not activate the corresponding tab or highlight the navigation item.

## \[1.1.0] - 2025-02-04

### Added

* Custom CSS class (`coursetabs-title`) applied to the block title to support bold styling and font size customization.
* Default icon for tabs when no activity icon is defined (`/custom/icon/arguments.png`).

### Changed

* Updated block visibility rules to allow the block to appear on all course and activity pages.

### Fixed

* Corrected icon path issues when retrieving module icons from the active theme.

## \[1.0.0] - 2025-01-01

### Added

* Initial release of the **Course Tabs** plugin.
* Display of up to 5 tabs retrieved from the **Universe** theme.
* Support for custom CSS to style the block.
* Integration with Moodle’s configuration system for tab titles.
* Basic JavaScript module (`tabs.js`) to handle tab navigation.