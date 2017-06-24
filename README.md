# Billplz for CubeCart

Accept payment using Billplz by using this plugin

# Minimum System Requirement

* CubeCart Version 5 or 6
* PHP 5.5 or newer

# Auto Install/Upgrade

There is no Auto Install/Upgrade for this time

# Manual Install

1. Download/Clone this repository
2. Upload the main folder to the modules/gateway folder
3. It may be necessary to clear your store cache before it shows in the admin control panel of your store. This can be done from the "Rebuild" tab of the "Maintenance" area. Select "Clear cache" the click the submit button.

# How do I enable Website Payments Standard in CubeCart?

1. Install PayPal Standard following the instructions above.
2. Locate the module configuration page in the admin side of your store.
3. Enter the following settings:
    * Status: Enable
    * Scope: Both
    * Description: Billplz
    * API Secret Key: Get your API Secret Key at www.billplz.com
    * X Signature Key: Get your X Signature key at www.billplz.com
    * Description to appear: Payment for Order or <Any string with less than 200 Character)
    * Send Billplz Bill to Customer: Don't Send
4. Click Save
