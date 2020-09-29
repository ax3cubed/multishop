# Change Log (multishop-merchant)

## Version 0.25 - Aug 5, 2018

This release contains several enhancements, as well as also supports PHP 7.2 and Yii 1.1.20 and Yii 2.0.15
 
### New Features:

 + New: Added support to track as many guest accounts for each shop by using guest email address for customer_id.
Registered (sign up) customer will be assigned id start with Account::TYPE_CUSTOMER and one running sequence number, e.g. c1, c2 ... cN, whereas guest account will be assigned email address as their customer id.
When guest account eventually converted (register as shop customer account), all previous orders under guest email address will be auto transferred to newly registered customer account

### Enhancements:

 - Enh: Upgraded to support PHP 7.2
 - Enh: Upgraded to support Yii 1.1.20 and Yii 2.0.15
 - Enh: Simplify the design of webapp index page, and remove section FAQ, DEMO and PLANS
 - Enh: Add more wcm page content params support, e.g. DEMO_SHOP_URL, DEMO_CHATBOT_URL, OPEN_SOURCE_URL, THEME_STORE_URL
 - Enh: Change google analytics tracking to use gtag.js
 - Enh: Load version footer from common.views.version.index
 - Enh: Read OPEN_SOURCE_URL via Config::getSystemSetting('repo_source_link'). 
 - Chg: Resort MerchantLoginMenu items ("Manage Shops" put to first)
 - Chg: Centralised loading of asset bundles at `orders` and `products` module instead of at each individual view files (also resolving bugs where certain view files are missing assets files)
 - Chg: Change the loading sequence of Sii messages of each module. It auto merges message in sequence:
(1) application level messages
(2) common module level messages (inclusive of kernel common messages)
(3) local module level messages

 

## Version 0.24 - Jun 24, 2017

This is the initial release of `multishop-merchant`, part of Multishop.org open source project. 

It includes code re-architecture and refactoring to separate the `merchant` app out from old code.
All existing functions and features remain same as inherited from previous code base (v0.23.2).

For full copyright and license information, please view the [LICENSE](LICENSE.md) file that was distributed with this source code.


## Version 0.23 and before - June 2013 to March 2017

Started since June 2013 as private development, the beta version (v0.1) was released at September 2015. 

Multishop.org open source project was created by forking from beta release v0.23.2 (f4f4b25). 
