====== Enquiry Form Plugin ======
==== Introduction ====
The enquiry plugin generates a comprehensive contact form collecting details of the enquirer and the enquiry. A category field allows the enquirer to specify the grouping for the query. In the administration page for each entry a date responded and who the responder is along with details of the response.

This plugin also supports the e107 front page option to enable it to be made the site's home page.
==== Admin Preferences ====

=== General Settings ===
**Contact Name : ** This is used in the {ENQUIRY_CONTACT} shortcode in each of the messages (see the enquiry tabs below) It is the name of the person to contact.

**Contact Phone : ** The {ENQUIRY_EMAILTO} shortcode in each of the enquiry tabs is for the email address for the above contact

**Contact Email Name : ** The email from name used in notifications.

**Contact Email : ** The address the notify email is from.

**No items in menu : ** How many of the enquiry forms are shown in the menu. Old enquiries (more than 12 days) are shown with a red flashing icon, between 6 and 12 days an amber icon and less than 6 days a green icon

**Menu visible to : ** Who can see the menu. Useful if menu manager doesn't show the visibility icon. The menu supports caching.

**Use Captcha : ** Use the e107 captcha for guests when submitting the form.

**Captcha Colour : ** If this is filled in (hex format without the #) then the captcha will have its colour set to this value. Useful if you want to highlight the captcha.

=== Enquiry Tabs ===

The three enquiry tabs allow you to write the text which will be displayed as the front page, the success page and the failed to save page. Use the {ENQUIRY_CONTACT} and {ENQUIRY_EMAILTO} to insert those fields into the page. To use the self generated pages click  on the //use this xxx message// or you can edit the language file.

==== Admin Manage/Create Forms ====
Most fields are self explanatory however the response fields are less so.

**Responder :** The person who is logged in and is responding to the enquiry.

**Responded on :** Date the enquiry was dealt with.

**Action/Outcomes :** Details of the response and what was done.

==== Admin Manage Categories ====
Very simply the two fields to edit are the name of the category and the description (the latter is not currently used.

====== Notifications ======
This plugin uses the e107 notify function in the main site administration. To configure the plugin to use the notifications then go to //tools/notify// in Admin. Select the Enquiry tab and select the email address or the userclass to be notified on the new enquiry event.

Remember that notify __does not__ use the Mail settings but the //settings/preferences/email and contact// server settings.

