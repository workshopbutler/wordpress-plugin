=== Plugin Name ===
Contributors: workshopbutlers
Tags: event management, training management, event schedule, workshop crm, online registrations
Requires at least: 4.6
Tested up to: 5.6
Requires PHP: 5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Integrate your website and Workshop Butler workshop management platform. Promote workshops and trainers, accept registrations.
This plugin suits for knowledge brand and training company accounts.

== Description ==

This plugin integrates your website with [Workshop Butler](https://workshopbutler.com) workshop management platform. It helps you to promote trainings
and other events, accept registrations online, and give visitors detailed information about your trainers and workshops.

It comes with five default themes, and many options to customise it for your needs.

When you add a new event or trainer to Workshop Butler, they automatically appear on your website, making the process
of workshop and license management fast and easy.

== Installation ==

1. Open your WordPress dashboard
2. Go to *Plugins -> Add New*
3. Search for *Workshop Butler* and select *Workshop Butler*
4. Click *Activate*

After the activation, go to *Settings -> Workshop Butler*, enter your *API key* and click *Save*.
Then you can open *yourwebsite.com/schedule* to see the list of events.

During the activation, the plugin adds five pages:

* Schedule (/schedule) contains the event schedule
* Event (/schedule/event) hosts the detailed information of each event
* Trainer List (/trainer-list, containing the list of all trainers
* Trainer (/trainer-list/profile) for the trainer profiles
* Registration (/registration) for the event registration

You can change them later.

== Screenshots ==

1. General
2. Events
3. Trainers
4. Pages
5. Custom CSS

== Languages ==

The public pages are available in English, Spanish, French, German, Portuguese, Dutch and Norwegian.
The administration panel is available only in English.

== Frequently Asked Questions ==

= Can I use the plugin without having a Workshop Butler account? =
No. However, you can easily register for [a free trainer account](https://app.workshopbutler.com)

= Can I use my own theme? =
Yes. Workshop Butler plugin for WordPress comes with a number of options for customization.

= What to do if I found a bug? =
Please, open an issue [here](https://github.com/workshopbutler/wordpress-plugin)

= What to do if I have a question? =
Please, open an issue [here](https://github.com/workshopbutler/wordpress-plugin)

== Changelog ==
= 2.14.0 =
* Add PayPal payments

= 2.13.9 =
* Fixes undefined property error notice

= 2.13.8 =
* Fixes a JS error on the registration form when jQuery doesn't work in some rare cases
* Adds filter by multiple categories.

= 2.13.7 =
* Rolls back the changes from the previous fix and uses another way to fix a fatal error with Buddyboss

= 2.13.6 =
* Fixes a fatal error happening on some pages when Buddyboss is installed

= 2.13.5 =
* Fixes translations and support for Macedonian, Hungarian and Hebrew languages

= 2.13.4 =
* Fixes a critical error visible in the logs but not affecting any behaviour

= 2.13.3 =
* Fixes registration on canceled event
* Fixes Yoast SEO compatibility
* Fixes an issue with incorrect featured event highlighting

= 2.13.2 =
* Fixes an issue with incorrect error handling from server
* Fixes an issue with registration in IE

= 2.13.1 =
Fixes a small translation issue in German language

= 2.13.0 =
* Adds `badges` attribute to `wsb-trainer_list` shortcode which shows trainers who have at least one of the given badges (use badge ids for that)

= 2.12.0 =
* Fixes a bug with incorrect Hebrew language
* Adds new [`wsb_schedule_language`](https://workshopbutler.com/developers/wordpress/shortcodes/schedule-template/#wsb_schedule_language) shortcode which renders the column of workshop's languages
* Adds support for customizable query parameters for schedule
* Reports a bit more data (request ID, user agent) on errors
* Adds support for featured events in schedules and on-page event lists
* Fixes the output of ordered/unordered lists in event description and trainer profile
* Adds a new element - [`Next event`](https://workshopbutler.com/developers/wordpress/shortcodes/next-event/) block. For now, it includes only button which contains the link to the next event
(either any event or from specified category/event types)

= 2.11.2 =
Fixes "mailto" button on the trainer's page

= 2.11.1 =
Fixes `[wsb_trainer_list_rating]` shortcode which didn't show trainer's rating

= 2.11.0 =
* Improves the output of event dates
* Adds `only_featured` parameter to `wsb_schedule` to show only featured workshops
* Fixes an issue with incorrect workshop dates in some border cases
* Adds two new shortcodes for `wsb_schedule`: `wsb_schedule_date` and `wsb_schedule_time`
* Improves the output for timezone abbreviations which have no abbreviations (adds GMT before them)
* Adds failed request logging with the ability to switch it off

= 2.10.0 =
* Fixes an issue with non-working registration form for free events
* On the trainer's profile, the newest past events are shown, not the oldest ones
* Improves support for multiple locales


= 2.9.0 =
* Adds 'truncate' parameter to wsb_schedule_title (only for 'tile' view). By default, 60. Set to 0 or false to remove completely. ... is added when truncate is on.
* Adds 'target' parameter to wsb_event_registration_button. By default, set to '_self'. Possible values are the same as for 'target' attribute for HTML link <a>.
* Adds support for optional start/end dates in ticket prices
* Fixes deactivation behaviour for the plugin (do not remove settings anymore)
* Adds complete settings removal when the plugin is uninstalled
* Sends a bit more statistics on each API request

= 2.8.4 =
Fixes a random bug when workshop ID wasn't retrieved from a query string (probably erased by WordPress)

= 2.8.3 =
Improves handling of secure requests for reverse-proxy website

= 2.8.2 =
Fixes a bug in Safari preventing card payments

= 2.8.1 =
Fixes an incorrect Stripe key

= 2.8.0 =
Adds support for card payments (card payments must be activated in Workshop Butler)

= 2.7.6 =
* Fixes the output of future/past events on a trainers's page: remove private workshops from the list
* Improves the handling of server errors during the registration process

= 2.7.5 =
* Fixes the output for future/past events on a trainer's page

= 2.7.4 =
* Fixes a bug preventing sending a correct ticket type on multiple tickets
* Fixes a problem with promo code box not showing when the link is clicked
* Improves the look of registration form
* Fixes a bug when an external registration link did not work

= 2.7.3 =
Partially fixes an issue when an event page template is not updated

= 2.7.2 =
Fixes an issue with incomplete list of trainers and events

= 2.7.1 =
Fixes an issue prevented to update some classes in page templates

= 2.7.0 =
* Cleaner, easier-to-user registration form
* Multiple smaller UI fixes
* Adds two new configuration options for event page: a number of events in the sidebar and what events to show in the sidebar

= 2.6.0 =
* Two new shortcodes added to show cover image of events: [wsb_schedule_image] and [wsb_event_image]

= 2.5.0 =
* Added 'Event Type' option to the widget. You can show events only from the selected event type
* Added 'event_type' parameter to '[wsb_schedule]' shortcode. You can show events only from the selected event type
* If there is only one ticket type, it's selected automatically on the registration form
* When a user filters workshops in the schedule, this information is saved in URL so you can share links to a filtered schedule
* Improved Upcoming events widget on the event page: it shows events of an active trainer and do not show an active event

= 2.4.1 =
* Fixes a bug with the registration form

= 2.4.0 =
* Improves support for Google Analytics actions
* Fixes a filter configuration on the list of trainers

= 2.3.1 =
* Fixes a price output for some locales

= 2.3.0 =
* Adds support for Norwegian language
* Fixes the rendering of italic, bold, <sub> and <sup> text
* G+ is removed from social sharing

= 2.2.4 =
* Fixes a bug with an incorrect country code sent to Workshop Butler. As a result, the countries of attendees were not saved correctly

= 2.2.3 =
* Fixes an incorrect array initialisation for PHP version < 5

= 2.2.2 =
* Fixes a bug preventing attendee registration when billing and/or work addresses are set as required

= 2.2.1 =
* Fixes the rendering of [custom fields](https://support.workshopbutler.com/article/46-how-to-add-a-new-custom-field). Before labels for custom fields were not shown

= 2.2.0 =
* Radically improves mobile templates
* Adds Trainer column to a Table layout of Schedule
* Fixes a bug with timezone when workshop dates were different from the ones set by trainers
* Fixes a Spanish translation

= 2.1.3 =
* Fixes another PHP 5.3 related bug

= 2.1.2 =
* Fixes a support for PHP 5.3
* Fixes a date/time formatting for one-day workshop

= 2.1.1 =
Fixes a bug with incorrect jQuery loading on some websites

= 2.1.0 =
* Adds support for WordPress 5.0
* Adds new shortcode [wsb_trainer_name]
* Improves the behaviour for external event pages - the links open in new tabs.

= 2.0.2 =
* Fixes a bug when a ticket price is not showing on some websites
* Moves all classes under WorkshopButler namespace to prevent name clashes

= 2.0.1 =
Fixes a bug which caused a repetitive menu item

= 2.0.0 =
**Attention:** The changes in this release are substantial and an additional manual setup is needed. Before proceed, read the article [How to migrate from to a new WordPress plugin](https://support.workshopbutler.com/article/110-how-to-migrate-to-new-wordpress-plugin)

Meet a completely new version of our website integration widgets. It includes a huge number of modifications, and makes
the process of customisation very simple. Here's just a short list of what we added:

* Powerful new settings allow you to change the layout of pages and update the styles
* Support for Spanish, German, French, Portuguese and Dutch languages
* Support for the list of trainers and trainer profiles
* Support for the list of testimonials for one trainer
* Support for a number of shortcodes and configuration settings

= 1.2.0 =
* Support for named widgets, allowing you to add many pre-configured event calendars and sidebars

= 1.1.0 =
* Support for new Workshop Butler website integration

= 1.0.4 =
* Added custom title for plugin

= 1.0.3 =
* Added theme supporting for plugin

= 1.0.2 =
* Updated a plugin description

= 1.0.0 =
* Release date: October 15th, 2016
