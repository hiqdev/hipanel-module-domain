## hiqdev/hipanel-module-domain

## [Under development]

- Fixed many different issues
    - [3650409] 2017-01-18 csfixed [@SilverFire]
    - [3c7144d] 2017-01-18 Added labels to whois, is_secured, autorenewal switches [@SilverFire]
    - [c3d8260] 2017-01-11 Updated to follow yii2-bootstrap-switch changes [@SilverFire]
    - [052d366] 2017-01-10 Fixed parent menu url from `domains` to `domain` [@tafid]
    - [2ad7d5e] 2016-12-29 Added disabled send transfer button if all records has error [@tafid]
    - [9216ba6] 2016-12-28 Domain view _bulkSetContacts fixed to filter contacts that belong only to the client of selected domain [@SilverFire]
    - [d954157] 2016-12-28 Removed unused use [@tafid]
    - [5cf8e37] 2016-12-23 Following hipanel js plugin API changes [@SilverFire]
    - [6022b24] 2016-12-22 redone yii2-thememanager -> yii2-menus [@hiqsol]
    - [2bbf741] 2016-12-22 Added state GONE [@tafid]
    - [00e56c2] 2016-12-22 translation [@tafid]
    - [e15b87e] 2016-12-21 redone Menus: widget instead of create+render [@hiqsol]
    - [230674a] 2016-12-21 moved menus definitions to DI [@hiqsol]
    - [75e8be4] 2016-12-19 Removed getStates method [@tafid]
    - [5d5a660] 2016-12-19 Removed unused, the commented code [@tafid]
    - [c406d4e] 2016-12-19 Added new domain states for can support [@tafid]
    - [7cf352a] 2016-12-19 translation [@tafid]
    - [7b68be2] 2016-12-19 Removed filter by `state` [@tafid]
    - [e43d922] 2016-12-19 Removed `whois_protected`, `is_secured` filter attributes [@tafid]
    - [ec38f82] 2016-12-19 Fix popover without link [@tafid]
    - [0c25b16] 2016-12-19 Changed dropDown to StaticCombo for `state` attribute [@tafid]
    - [7fa4b09] 2016-12-16 Disabled Sorting for autorenewal [@tafid]
    - [9ce6d0b] 2016-12-09 removed 1999 xmlns [@hiqsol]
    - [a9d83cf] 2016-12-08 Removed not filter imtes [@tafid]
    - [d0abcb4] 2016-12-07 Hid `Buy domain` button from user who can not pay [@tafid]
    - [81a22ec] 2016-12-07 Change conditions when whois check [@tafid]
    - [a51f8a2] 2016-12-06 Fixed spelling mistake [@tafid]
    - [5808b54] 2016-12-02 Added statuses [@tafid]
    - [ff63119] 2016-12-02 CheckCircle widget moved to the core module [@SilverFire]
    - [c38dc78] 2016-12-02 Fixed wrong condition in CheckCircle [@SilverFire]
    - [a32363d] 2016-12-02 Fixed changed contacts [@tafid]
    - [634320a] 2016-12-02 Work with contacts [@tafid]
    - [73cdf62] 2016-12-02 Fixed set bulk contacts [@tafid]
    - [b9f1470] 2016-12-02 renamed contactOptions -> contactTypes [@hiqsol]
    - [212981c] 2016-12-02 renamed contactOptionsWithLabel -> contactTypesWithLabels [@hiqsol]
    - [ee37c97] 2016-12-01 Added ContactGridView [@tafid]
    - [17a91a4] 2016-12-01 Changed colors [@tafid]
    - [a6c5bba] 2016-12-01 + contact models in domain [@hiqsol]
    - [753dfd1] 2016-12-01 Added verivication [@tafid]
    - [4bc53f5] 2016-11-30 Added new widget [@tafid]
    - [224543e] 2016-11-29 Added new Menu to Host [@tafid]
    - [ecc900a] 2016-11-28 Moved Transfer action to Transfer controller [@tafid]
    - [623a59e] 2016-11-28 Removed rules for transfer password check [@tafid]
    - [4af7f30] 2016-11-24 Hid Freeze and Freeze WP when russion zones [@tafid]
    - [4f70475] 2016-11-24 Added specified Action for switch column [@tafid]
    - [11ac831] 2016-11-24 translations [@tafid]
    - [b0715f3] 2016-11-24 Hid authCode when russian zones [@tafid]
    - [095e80d] 2016-11-24 Changed design [@tafid]
    - [7eb572b] 2016-11-24 Changed design [@tafid]
    - [8fbb804] 2016-11-24 Logic fix [@tafid]
    - [6d7a803] 2016-11-24 translation [@tafid]
    - [538a628] 2016-11-23 Filtered object by isPushable() [@tafid]
    - [e2537a5] 2016-11-23 translation [@tafid]
    - [a79beed] 2016-11-23 Removed Change button [@tafid]
    - [eb2202c] 2016-11-23 translations [@tafid]
    - [72d1623] 2016-11-23 Added b tag for unchangeable domains shown [@tafid]
    - [92f8326] 2016-11-23 Added rule isContactChangeable [@tafid]
    - [62fa6bd] 2016-11-23 Fixed visible rules [@tafid]
    - [2759c56] 2016-11-23 Fixed contact labels by iteration [@tafid]
    - [97296f2] 2016-11-23 Added isContactChangeable rule [@tafid]
    - [dfefbdb] 2016-11-23 Fixed contact options iterate by array with label [@tafid]
    - [1f5efc7] 2016-11-23 Added new mthods for check if some action do [@tafid]
    - [69fae76] 2016-11-23 translations [@tafid]
    - [7cbf4aa] 2016-11-22 Added WP Freeze action and slide up off/on actions [@tafid]
    - [8f8c5cc] 2016-11-22 Fixed wp-freeze actions [@tafid]
    - [299f105] 2016-11-22 Added new scenario, add isHolded method [@tafid]
    - [db12301] 2016-11-22 translations [@tafid]
    - [cb2eb0a] 2016-11-22 translations [@tafid]
    - [d586573] 2016-11-22 Added Froze and Held statuses to index grid state column [@tafid]
    - [1e48249] 2016-11-22 Added force types to translation message [@tafid]
    - [cbb74d5] 2016-11-21 Added visible rules for Hold, Sync, Push [@tafid]
    - [99c8a98] 2016-11-21 Changed string stauses to constants [@tafid]
    - [c0c4afa] 2016-11-21 Added new const state Preincoming [@tafid]
    - [002a642] 2016-11-21 Added rules to Delete by AGP visibility [@tafid]
    - [fd70961] 2016-11-21 Redirect to cart when add-to-cart-renewal [@tafid]
    - [e9160e8] 2016-11-18 fixed view item from DomainActionsMenu [@hiqsol]
    - [a9e087a] 2016-11-18 added DomainDetailsMenu [@hiqsol]
    - [451ed56] 2016-11-18 Added menuManger menu to view [@tafid]
    - [91c316b] 2016-11-18 translations [@tafid]
    - [99e2f3f] 2016-11-18 Do not clean transfer form on tabs switching [@SilverFire]
    - [fa2bb93] 2016-11-18 Mark fqdn field as required [@SilverFire]
    - [6b08385] 2016-11-17 minior [@tafid]
    - [0499158] 2016-11-17 Removed `only-object` scenario [@tafid]
    - [580ff39] 2016-11-17 Added post options [@tafid]
    - [f508b1a] 2016-11-17 + un/freeze access behaviors [@hiqsol]
    - [204cc96] 2016-11-17 Added hold scenarios [@tafid]
    - [1b8173f] 2016-11-17 Allow IDN domains search [@SilverFire]
    - [9cc9518] 2016-11-17 Divided freeze and unfreeze access controll [@tafid]
    - [2bd2a9a] 2016-11-17 Added access controll ot freeze actions [@tafid]
    - [80963f7] 2016-11-17 Changed roles [@tafid]
    - [9f57046] 2016-11-16 Disabled IDN for domain check [@SilverFire]
    - [7223de9] 2016-11-16 Updated translations [@SilverFire]
    - [406c118] 2016-11-16 Refactored domain check controller [@SilverFire]
    - [80c99fa] 2016-11-16 Showed hold actions only can support [@tafid]
    - [17b8a46] 2016-11-16 Disabled autocomplete [@tafid]
    - [fc5deae] 2016-11-16 Added hold button to domain view [@tafid]
    - [6b95ae4] 2016-11-16 Added SEO attributes [@tafid]
    - [1295230] 2016-11-16 Added new composer require package - seostats [@tafid]
    - [952d557] 2016-11-16 Added IDN support for WHOIS [@SilverFire]
    - [02b57de] 2016-11-16 Fixed redirection URL after PUSH action [@SilverFire]
    - [6ca7999] 2016-11-16 + CheckDomainMenu [@hiqsol]
    - [5314447] 2016-11-15 + CheckDomainMenu [@hiqsol]
    - [d875cd2] 2016-11-15 redone translation category to `hipanel:domain` <- hipanel/domain [@hiqsol]
    - [8e82a86] 2016-11-14 redone translation category to `hipanel:domain` <- hipanel/domain [@hiqsol]
    - [1ba4d19] 2016-11-14 redone translation category to `hipanel:client` <- hipanel/client [@hiqsol]
    - [c322e0d] 2016-11-11 Added check to unsupported [@tafid]
    - [c7b9ec7] 2016-11-11 Fixed layout [@tafid]
    - [0118c91] 2016-11-11 Added error handle for whois [@tafid]
    - [bc3159b] 2016-11-11 Added redirectToCart -> true for DomainController add-to-cart-registration [@tafid]
    - [70c01cb] 2016-11-11 Fixed redirect after domain-push action [@SilverFire]
    - [8b9523d] 2016-11-11 added DomainBulkActionsMenu and DomainBulkActionsMenu [@hiqsol]
    - [5e5ca21] 2016-11-11 added DomainBulkActionsMenu [@hiqsol]
    - [c43f05e] 2016-11-11 fixed AccessControl in DomainController [@hiqsol]
    - [515fdbf] 2016-11-11 Fixed DomainRenewalProduct to allow managers renew clents' domains [@SilverFire]
    - [eba28f4] 2016-11-10 Changed to fa icon [@tafid]
    - [1f87a89] 2016-11-10 Added delete button, fix css styles [@tafid]
    - [59daced] 2016-11-10 Removed delete action [@tafid]
    - [95e8e3d] 2016-11-10 Added to fa icon fa-fw class [@tafid]
    - [e0390e2] 2016-11-09 Hid button `regenerate password` on domain details page when domain is not active [@SilverFire]
    - [377fa07] 2016-11-09 Rewrited domain actions menu [@tafid]
    - [661dfb9] 2016-11-09 Added new actionsColumn [@tafid]
    - [b53fd46] 2016-11-08 Added DomainActionsMenu [@tafid]
    - [a839c3f] 2016-11-04 improved text for name server creation page [@hiqsol]
    - [ce718c2] 2016-11-03 Hid renew domain button [@SilverFire]
    - [a9b79ec] 2016-11-03 Refactored regen-password action for DomainController [@SilverFire]
    - [d343f88] 2016-11-03 Updated translations [@SilverFire]
    - [6ff4595] 2016-10-31 fixed permissions [@hiqsol]
    - [bb3127d] 2016-10-28 translations [@tafid]
    - [857ed50] 2016-10-27 Fixed. Show registrar if array given [@tafid]
    - [cc058a2] 2016-10-27 fixed PNotify creation from JS: added styling bootstrap3 [@hiqsol]
    - [44ef3d5] 2016-10-27 Changed. Redesign Host create form, added Help text [@tafid]
    - [f6c9169] 2016-10-27 translations [@tafid]
    - [6f91e54] 2016-10-26 translation [@tafid]
    - [7e9669d] 2016-10-26 redone host set ips to bulk interface [@hiqsol]
    - [839c874] 2016-10-25 removed delete host link from host view [@hiqsol]
    - [dd3d7a3] 2016-10-25 fixed typo [@hiqsol]
    - [dfe2aef] 2016-10-25 translations [@hiqsol]
    - [274a0a6] 2016-10-20 Do not use PJAX on domains index page [@SilverFire]
    - [49e2181] 2016-10-19 Fixed host create, use hipanel DynammicFormWidget [@tafid]
    - [56a50c4] 2016-10-13 Updated spelling and translations [@SilverFire]
    - [628da00] 2016-10-09 Removed DomainTransferCalculation model usage [@SilverFire]
    - [6f57e6f] 2016-10-08 Removed DomainTransferCalculation model because it was redundant [@SilverFire]
    - [33cfc43] 2016-10-06 Removed from checkDomain view Request handling. Moved to CheckController. [@tafid]
    - [cd1a29c] 2016-10-05 Added messgae when domain-check validation [@tafid]
    - [afa71d2] 2016-10-05 Removed force client and seller asignment in cart/Calculation [@SilverFire]
    - [c8ee329] 2016-10-04 Changed params->seller to params->user.seller [@SilverFire]
    - [7cdc17d] 2016-10-03 Added translation [@tafid]
    - [c2d2a62] 2016-09-30 Added LazyLoad widget to whois view [@tafid]
    - [7bd8097] 2016-09-30 Added yii2-widget-lazyload require [@tafid]
    - [8549627] 2016-09-30 Changed whois link in check domain [@tafid]
    - [94791a2] 2016-09-30 Added new translations, remove old [@tafid]
    - [65214c2] 2016-09-30 removed require hiqdev/hipanel-domain-checker [@hiqsol]
    - [22ac1d3] 2016-09-30 Fixed CheckController, added getAvailableZones from DomainTariffRepository [@tafid]
    - [11ca48f] 2016-09-30 renaming domainchecker -> domain/check [@hiqsol]
    - [1290c29] 2016-09-30 still merging in hipanel-domain-checker [@hiqsol]
    - [c3e94cf] 2016-09-30 Add 'checker/' from commit '8111d0ae6c9243db4f96707584b975f512066965' [@hiqsol]
    - [f7ece47] 2016-09-30 started merging in hipanel-domain-checker [@hiqsol]
    - [8111d0a] 2016-09-29 Added availableZones [@tafid]
    - [e1a3213] 2016-09-29 Added DomainTariffRepository [@tafid]
    - [bf01059] 2016-09-29 Refactored whois [@tafid]
    - [b9f6e5d] 2016-09-29 Added html structure to whois views [@tafid]
    - [791d340] 2016-09-28 Added views for whois lookup [@tafid]
    - [6252aaf] 2016-09-28 Added Whois js plugin for defer loading whois lookup data [@tafid]
    - [f34489e] 2016-09-28 Removed required rule from get-whois scenario [@tafid]
    - [fe75660] 2016-09-28 Added rules for get-whois scenario to Domain model [@tafid]
    - [2ad0867] 2016-09-28 Added to sidebar menu link to whois lookup [@tafid]
    - [a47308a] 2016-09-28 Added controller and view files for whois check [@tafid]
    - [77bbf62] 2016-09-22 removed unused hidev config [@hiqsol]
    - [ef62597] 2016-09-22 removed unused hidev config [@hiqsol]
    - [19e3fa9] 2016-09-22 redone menu to new style [@hiqsol]
    - [2b0a437] 2016-09-20 Updated translations [@SilverFire]
    - [e218a48] 2016-09-13 updated translations [@SilverFire]
    - [1f2b8ad] 2016-09-13 Updated cart/Calculation to follow API changes [@SilverFire]
    - [6556517] 2016-09-12 Updated translations [@SilverFire]
    - [23b1700] 2016-08-24 redone subtitle to original Yii style [@hiqsol]
    - [b89b1d3] 2016-08-23 redone breadcrumbs to original Yii style [@hiqsol]
    - [3555d8a] 2016-08-23 redone breadcrumbs to original Yii style [@hiqsol]
    - [35ff0d1] 2016-08-08 inited empty tests [@hiqsol]
    - [dab4e36] 2016-08-08 rehideved [@hiqsol]
    - [6540eb5] 2016-08-08 csfixed [@hiqsol]
    - [f688f79] 2016-08-08 fixing translations app -> hipanel [@hiqsol]
    - [612619c] 2016-08-04 translations [@hiqsol]
    - [5a0e728] 2016-07-29 fixed translations app -> hipanel [@hiqsol]
    - [86d625e] 2016-07-21 Removed Client and Seller filters from the AdvancedSearch view for non-support [@SilverFire]
    - [be51c99] 2016-07-09 Updated translations [@SilverFire]
    - [9e4399d] 2016-07-08 Updated translations [@SilverFire]
    - [2f6a540] 2016-06-16 Changed Ref::getList to $this->getRefs in DomainConteoller [@SilverFire]
    - [830212b] 2016-06-16 Removed Kartik widget dependency [@SilverFire]
    - [c4f396a] 2016-06-16 csfixed [@hiqsol]
    - [8d358ce] 2016-06-16 allowed build failure for PHP 5.5 [@hiqsol]
    - [b62c65f] 2016-06-13 Updated translations [@SilverFire]
    - [1d5c020] 2016-06-09 Fix not found IsotopeAsset [@tafid]
    - [cee17d2] 2016-05-31 Change index layout [@tafid]
    - [1f2ab59] 2016-05-27 Fixed Domain view page to show DNS records without pagination [@SilverFire]
    - [08974e1] 2016-05-18 used composer-config-plugin [@hiqsol]
    - [a949938] 2016-05-18 fixing dependencies constraints [@hiqsol]
    - [728ce2f] 2016-05-18 fixing dependencies constraints [@hiqsol]
    - [f5ebe6e] 2016-05-18 used composer-config-plugin [@hiqsol]
    - [ad81508] 2016-05-13 Fix regular expression in DomainController [@tafid]
    - [5ef0551] 2016-05-13 Add add-to-cart-transfer [@tafid]
    - [94d9d61] 2016-05-13 Add DomainTransferCalculation [@tafid]
    - [cf97829] 2016-05-12 Fix validate-form for transfer [@tafid]
    - [0927e11] 2016-05-11 Updated composer.json - changed url to asset-packagist.org [@SilverFire]
    - [7a875bf] 2016-05-11 Updated DomaincheckerController::actionCheck - now uses hiart->disableAuth() [@SilverFire]
    - [0ec9858] 2016-04-25 Add Transfer controller [@tafid]
    - [6327382] 2016-04-18 Spell fix [@tafid]
    - [efb6570] 2016-04-08 Change layout [@tafid]
    - [ee68a5d] 2016-04-08 - forceTranslation [@hiqsol]
    - [d3cddb9] 2016-04-06 + WP un/freeze [@hiqsol]
    - [58c5c29] 2016-04-05 translation [@tafid]
    - [505d3f8] 2016-04-05 Fixed Cache call in DomaincheckerContoller [@SilverFire]
    - [b0900c5] 2016-04-05 Added domainChecker- ru translationg [@SilverFire]
    - [e79f8de] 2016-04-05 Fixed domain serach: form input names changed to proper [@SilverFire]
    - [abcb5e5] 2016-03-30 Add validation [@tafid]
    - [db1a680] 2016-03-30 Fix file location [@tafid]
    - [9747b5c] 2016-03-30 Replace all translations [@tafid]
    - [adad572] 2016-03-30 Add Bootstrap class [@tafid]
    - [5c9846e] 2016-03-29 Replace Yii::$app->get("cache") to (new Cache()) [@tafid]
    - [95d9ff7] 2016-03-24 final [@tafid]
    - [8cd44da] 2016-03-24 Add translation [@tafid]
    - [875ed45] 2016-03-23 Add views and assets [@tafid]
    - [ae2ac69] 2016-03-23 Add Controller [@tafid]
    - [9d41857] 2016-03-23 inited [@SilverFire]
- Fixed build with asset-packagist
    - [0d83813] 2016-04-05 fixed build with asset-packagist [@hiqsol]
    - [9ef5ddd] 2016-04-05 inited tests [@hiqsol]
- Added Hold, Freeze, Delete actions
    - [81e0c14] 2015-09-18 + new action: Hold, Freeze, Delete [@BladeRoot]
    - [bcf474c] 2016-04-05 + domain un/freeze actions [@hiqsol]
    - [e57f601] 2016-04-05 translation [@hiqsol]
- Added domain check, register, renewal and transfer
    - [4e71211] 2016-03-25 Add DomainChecker to sidebar menu of domain modules. Remove domainCheck functionality [@tafid]
    - [4b0b600] 2016-03-23 Add Domain Checker [@tafid]
    - [bc01666] 2016-03-23 Syntax error fix [@tafid]
    - [1e0edc4] 2016-03-23 Fixed domain NS records removing [@SilverFire]
    - [831bf78] 2016-03-21 Updated translations, minor [@SilverFire]
    - [cf81a2d] 2016-03-21 Added Domain::canRenew() [@SilverFire]
    - [ce025eb] 2016-03-21 Added DomainRenewalProduct::daysBeforeExpireValidator() [@SilverFire]
    - [1e3b081] 2016-03-21 AbstractDomainProduct::getQuantityOptions - updated to respect global domain delegation limit (10 years) [@SilverFire]
    - [ad74546] 2016-03-18 Updated translations [@SilverFire]
    - [098bc1c] 2016-03-17 Domain renewal calculation: client and seller for calculation are now extracted from the model [@SilverFire]
    - [14bb2a0] 2016-03-17 Relocate Renew domain button [@tafid]
    - [c3a7465] 2016-03-17 Translations update, minor [@SilverFire]
    - [7ee594c] 2016-03-16 Translations update [@SilverFire]
    - [ae7059f] 2016-03-16 Added missing translation [@SilverFire]
    - [4a2a5e5] 2016-03-16 Removed commented out code [@SilverFire]
    - [b10e9e4] 2016-03-04 + domain registration purchase notes [@hiqsol]
    - [db9a1a9] 2016-03-04 added transfer notices [@hiqsol]
    - [cca24cd] 2016-03-04 improving purchasing NOT FINISHED [@hiqsol]
    - [3a0fbd7] 2016-02-19 Host create page - added combo for IP addresses [@SilverFire]
    - [cba40ea] 2016-02-18 Spelling [@SilverFire]
    - [b911eb0] 2016-02-18 NSync - fixed inline-to-form sync [@SilverFire]
    - [4af40f2] 2016-02-18 Changed XEditableColumn import namespace [@SilverFire]
    - [e9fe7e1] 2016-02-18 translations [@hiqsol]
    - [3b565c7] 2016-02-18 php-cs-fixed [@hiqsol]
    - [fd7da57] 2016-02-18 + no wrapping for expires column [@hiqsol]
    - [7f31b80] 2016-02-09 Implemented support of multiple IPs per host [@SilverFire]
    - [48038fd] 2016-02-08 Domain check - fixed doman zone dropdown default value [@SilverFire]
    - [55fb325] 2016-02-08 Domain::getDomainZones - fixed exception for client with no available tariffs [@SilverFire]
    - [59cee3f] 2016-02-05 Domain check refactoring: tariff resources caching, variables renaming, perfomance tuning [@SilverFire]
    - [1f4dca1] 2016-02-05 Minor issues [@tafid]
    - [4dd44d4] 2016-02-05 Add StaticCombo to checkDomain instead dropDown [@tafid]
    - [e0200f8] 2016-02-05 domain/checkDomain - fixed resources passing to view [@SilverFire]
    - [27172b8] 2016-02-05 Add to cart by ajax [@tafid]
    - [875d88f] 2016-02-05 Add price to check domain [@tafid]
    - [7d02e39] 2016-02-04 DomainController::indexAction - removed defauls from the filterStorageMap [@SilverFire]
    - [4657f2e] 2016-02-04 phpcsfixed [@hiqsol]
    - [9710ecd] 2016-01-29 View modal push enhanced [@SilverFire]
    - [305e87c] 2016-01-29 DomainController - bulkSetContactsModal, domainPushModal, bulkSetNote - redone with PrepareBulkAction [@SilverFire]
    - [97acace] 2016-01-28 fixing domain push [@hiqsol]
    - [64ed654] 2016-01-28 + added translations [@hiqsol]
    - [1eb9911] 2016-01-28 Host create - fixed ips value after a failed save [@SilverFire]
    - [f7d6466] 2016-02-04 fixing showing domain price, not finished [@hiqsol]
    - [a815bbd] 2016-02-03 Add ajax add to cart functionality [@tafid]
    - [7166a78] 2016-02-03 Fixed domainProduct message category [@SilverFire]
    - [e0a2c9e] 2016-02-02 DomainRenewalProduct - fixed loadRelatedData [@SilverFire]
    - [44915ed] 2016-02-01 Relocate html elemets on the page [@tafid]
    - [bdbc84f] 2016-01-29 Group Domain actions to one Dropdown [@tafid]
    - [281d63e] 2016-01-27 Add new categories of zones [@tafid]
    - [e43697f] 2016-01-25 Add some design fixes to bulk transfer page [@tafid]
    - [e5ee2ec] 2016-01-22 DomainController::indexAction - added filterStorageMap [@SilverFire]
    - [56d5cf5] 2016-01-21 DomainController - domain transfer fixed bulk transfer error saving [@SilverFire]
    - [f7bae23] 2016-01-21 Redisigne transfer [@tafid]
    - [cf42821] 2016-01-20 DomainController::actionTransfer - fixed bulk search [@SilverFire]
    - [2f6b281] 2016-01-20 DomainTransferPurchase implemented [@SilverFire]
    - [81e95ee] 2016-01-20 Domain transfer grid: implemented check based on model errors insted of custom fields [@SilverFire]
    - [b5974b1] 2016-01-20 DomainRenewalProduct - expires is a required attribute now [@SilverFire]
    - [f38a24a] 2016-01-20 AbstractDomainProduct - name is required now [@SilverFire]
    - [5eea720] 2016-01-20 Bulk domain tranfer logic moved to the contoller instead of the model [@SilverFire]
    - [e0cf7bc] 2016-01-20 Added DomainTranferPurchase, DomainTransferProduct adopted to create Purchase object [@SilverFire]
    - [a3a669b] 2016-01-18 Cart: domain  renewal operation implemented [@SilverFire]
    - [a0d5197] 2016-01-18 Add extra check for zones [@tafid]
    - [1a88707] 2016-01-15 AbstractPurchase::init() - added model filling [@SilverFire]
    - [910a258] 2016-01-15 DomainRegistrationPruchase -> DomainRegistrationPurchase [@SilverFire]
    - [7ad02a2] 2016-01-14 Cart calculation - added links to position purchase classes [@SilverFire]
    - [063d575] 2016-01-14 Added cart position purchase classes [@SilverFire]
    - [48bdc8d] 2016-01-14 Add validate condition [@tafid]
    - [d4555b5] 2016-01-14 Revmove dev strings [@tafid]
    - [2e3981f] 2016-01-14 Add check zone in domainCheck. Add parse domain name from side-bar search input [@tafid]
    - [675644d] 2016-01-13 Remove popular filter by pass in Domain Check [@tafid]
    - [fa960e9] 2016-01-12 Remove force copy [@tafid]
    - [b15743f] 2016-01-12 Some tries with hide checked domains [@tafid]
    - [9c81f7e] 2016-01-12 Domain check. Make button the same width [@tafid]
    - [608c322] 2016-01-12 minor fix [@hiqsol]
    - [6ad1c64] 2016-01-12 Remove dev plugs [@tafid]
    - [19a32cf] 2016-01-12 Add default filter by popular. Add limit request functionality [@tafid]
    - [f253dea] 2016-01-11 Add filter for domain field on domain-check [@tafid]
    - [6e6ee5b] 2016-01-11 Fix dulicate domain in results [@tafid]
    - [e2e4189] 2016-01-11 Fix Status filter JS [@tafid]
    - [13b18be] 2016-01-06 code style fixed [@SilverFire]
    - [b3b4766] 2016-01-06 Work on domain check [@tafid]
    - [6f56a9e] 2016-01-05 Add designe for categories to domain check [@tafid]
    - [144a366] 2015-12-25 DomainCart - implemented domain actions calculation [@SilverFire]
    - [b3c1617] 2015-12-25 Spelling [@SilverFire]
    - [408240f] 2015-12-25 Change links on change contacts [@tafid]
    - [db842ec] 2015-12-25 Add indent in `_sync_button` [@tafid]
    - [f9b13ac] 2015-12-24 Domain check form sidebar search form [@tafid]
    - [acdc017] 2015-12-23 Add message folder, add local states for domains [@tafid]
    - [6101948] 2015-12-23 Add bulk contacts change [@tafid]
    - [73bf601] 2015-12-22 Redefine public static func primaryKey [@tafid]
    - [485e10d] 2015-12-21 Add domain bulk push [@tafid]
    - [fb12001] 2015-12-18 Add bulk Sync to domain [@tafid]
    - [23d406a] 2015-12-17 Fix bulk set ns [@tafid]
    - [a74ea36] 2015-12-17 make todo reminder [@tafid]
    - [ed55e61] 2015-12-17 Get list of zones from tariffGetAvailableInfo [@tafid]
    - [a67736a] 2015-12-17 Check domain v1 [@tafid]
    - [0855dee] 2015-12-15 Add DomainCheckPlugnAsset [@tafid]
    - [7d82740] 2015-12-15 Change size Search button [@tafid]
    - [54a26da] 2015-12-14 Fix Full Domain Name in the links [@tafid]
    - [c784e15] 2015-12-14 Fix if empty results [@tafid]
    - [ffb2f34] 2015-12-14 Finalize checkDomain designe [@tafid]
    - [792ccb8] 2015-12-14 Change domain check view [@tafid]
    - [705d681] 2015-12-11 Some function to NS [@tafid]
    - [66c6406] 2015-12-11 NsWidget - added form action property [@SilverFire]
    - [e2076ec] 2015-12-10 Add Domain Push functionality [@tafid]
    - [ee513ca] 2015-12-09 Removed PHP short-tags [@SilverFire]
    - [c2f5617] 2015-12-09 Add notice info [@tafid]
    - [6b194dd] 2015-12-09 Add new NsValidator, fix namespaces [@tafid]
    - [cda5d94] 2015-12-09 Add NsValidator to bulk change NS form [@tafid]
    - [22feb8f] 2015-12-07 Add validate rule nsips field [@tafid]
    - [f54c640] 2015-12-07 set-nss action, redirect to index [@tafid]
    - [8e27fe8] 2015-12-04 Design fixes [@tafid]
    - [5a95e6a] 2015-12-04 Add ArraySpoiler [@tafid]
    - [539073b] 2015-12-04 Domain bulkSetNote - domains list redone with ArraySpoiler [@SilverFire]
    - [fcb7c2d] 2015-12-04 Classes notation changed from pathtoClassName to PHP 5.6 ClassName::class [@SilverFire]
    - [c369923] 2015-12-03 Bulk NS set [@tafid]
    - [c5efb67] 2015-12-02 DomainController - actions definition updated to fit SmartActions new API [@SilverFire]
    - [94c2f4c] 2015-12-02 Add bulkSetNote operation [@tafid]
    - [1a5deac] 2015-12-01 Work on Bulk Set note [@tafid]
    - [aa0cf08] 2015-11-30 Initial work on bulk set note [@tafid]
    - [2eaf1b7] 2015-11-27 Redesign Bulk on/off buttons [@tafid]
    - [2dd1338] 2015-11-27 Add info block to NS tab [@tafid]
    - [26c5f80] 2015-11-27 Domain sync button redone with new Pjax scheme [@SilverFire]
    - [b9533c4] 2015-11-26 Change Save button color [@tafid]
    - [5691294] 2015-11-26 Add canSupport rule to delete button on domain index [@tafid]
    - [da3804b] 2015-11-25 Add IP Restrict Rule to NSync js [@tafid]
    - [58c165e] 2015-11-25 Relocate NSyncPlugin [@tafid]
    - [8705a2e] 2015-11-25 Add js validations to NSync js plugin [@tafid]
    - [42d2249] 2015-11-25 Internal IpValidator replaced with yii2 core one [@SilverFire]
    - [246ac1e] 2015-11-24 Add Ns rules [@tafid]
    - [5c7e551] 2015-11-24 Add button loading to NsWidget [@tafid]
    - [91fd645] 2015-11-24 Some changes [@tafid]
    - [5e63c79] 2015-11-24 NSync plugin complete [@tafid]
    - [37d668b] 2015-11-23 Start to work on NS form in domain detail view [@tafid]
- Added yii2-cart integration
    - [bd30ac0] 2015-11-16 php-cs-fixed [@hiqsol]
    - [ac92281] 2015-11-16 redone cart [@hiqsol]
    - [3b3b6fa] 2015-11-12 improved package description [@hiqsol]
    - [2049fc9] 2015-11-12 redone hipanel-module-cart -> yii2-cart [@hiqsol]
    - [5b99929] 2015-11-10 Add validation before AddToCart [@tafid]
    - [00c498d] 2015-11-10 Remove hiddenInputs where error occured [@tafid]
    - [f1271ef] 2015-11-10 Add transfered domains to cart [@tafid]
    - [4b3a9a9] 2015-11-09 Add DomainTransferProduct model [@tafid]
    - [7e7ffd2] 2015-11-09 domain/view: DNS edit now loads with DnsZoneEditWidget, removed unned uses [@SilverFire]
    - [16b6739] 2015-11-06 Add validation [@tafid]
    - [3eb3cc5] 2015-11-06 Add translations [@tafid]
    - [b30920d] 2015-11-05 Add domain transfer view [@tafid]
    - [30ecab7] 2015-11-05 DomainController::view() turned back to standalone action [@SilverFire]
    - [616c5c2] 2015-11-05 Add Domain Registration add to cart functionality [@tafid]
    - [bc15164] 2015-11-04 Add DomainRegistrationProduct, add validation to DomainCheck [@tafid]
    - [57a44d3] 2015-11-03 Domain check [@tafid]
    - [6b2b55e] 2015-10-30 Add model validation on domain-check [@tafid]
    - [569f10c] 2015-10-30 Add checkDomain view template [@tafid]
    - [5ebede8] 2015-10-29 Add Check Domain [@tafid]
    - [a008c29] 2015-10-28 Comment some code [@tafid]
    - [3db5032] 2015-10-26 Add data for source [@tafid]
    - [191eeaf] 2015-10-26 Add DomainRregistrationProduct. Fix getId [@tafid]
    - [027f210] 2015-10-23 Change icon in Product Model [@tafid]
    - [a113416] 2015-10-23 Add Cart functionality [@tafid]
    - [0d5594b] 2015-10-23 Work on DomainProduct [@tafid]
    - [1ec293c] 2015-10-23 Add DomainRenewProduct [@tafid]
    - [73349c8] 2015-10-22 Add DomainProduct [@tafid]
- Added DNS management
    - [7a26546] 2015-11-04 + Added domain DNS management [@SilverFire]
    - [2dd6135] 2015-11-04 * Fixed domain/view incorrect layout on Pjax load [@SilverFire]
- Deleted DomainValidator, moved to hipanel-core
    - [b36ede5] 2015-10-06 moved DomainValidator to hipanel-core [@hiqsol]
- Fixed translation and minor issues
    - [c22b2d8] 2016-01-27 rehideved [@hiqsol]
    - [9811ee7] 2016-01-27 added translations [@hiqsol]
    - [d8b65a4] 2015-09-23 used none Label type for State and Expires columns [@hiqsol]
    - [f97108a] 2015-09-23 improved index page look [@hiqsol]
    - [e6340c9] 2015-09-23 DomainGridView - enable-hold removed extra argument in callback function [@SilverFire]
    - [9792fc7] 2015-09-23 + new function and action [@BladeRoot]
    - [1b8d518] 2015-09-18 * improve language pack [@BladeRoot]
    - [53e7450] 2015-09-17 * improve language pack; - remove unnessary lines [@BladeRoot]
    - [aa20645] 2015-09-17 * improve language [@BladeRoot]
    - [552dc85] 2015-09-16 fixed problem with ViewAction data closure [@hiqsol]
    - [359d017] 2015-09-15 localized menu [@hiqsol]
    - [0969cab] 2015-09-09 PSR fix [@tafid]
    - [2bb123b] 2015-08-28 Added dependencies on related projects [@SilverFire]
    - [9e83d06] 2015-08-29 - require yii2 [@hiqsol]
    - [a1b37b0] 2015-08-27 Fixed breadcrumbs subtitle [@SilverFire]
    - [f4dbd8c] 2015-08-27 fixed domain set nss [@hiqsol]
    - [1794982] 2015-08-19 + domain/buy redirect [@hiqsol]
    - [d072fd0] 2015-08-19 minor: fixed indenting [@hiqsol]
- Added use of ClientSellerLink widget at host details
    - [d751f9f] 2015-08-30 used ClientSellerLink widget at host details [@hiqsol]
- Added go to site link at domain details
    - [1de7446] 2015-08-29 + go to site link [@hiqsol]
- Added details/edit buttons at domain contacts
    - [1152284] 2015-08-27 + details/edit buttons at domain contacts [@hiqsol]
- Fixed PHP warnings and deprecated
    - [426728d] 2015-08-27 Fixed deprecated method calling syntax [@SilverFire]
    - [7ccb975] 2015-08-26 fixed PHP warnings [@hiqsol]
    - [bcba156] 2015-08-25 Fix icons and fix warnings [@tafid]
    - [8b72875] 2015-08-25 Removed debug and commented code [@SilverFire]
    - [aa14c34] 2015-08-19 Index page redone with actual standarts [@SilverFire]
    - [bdb83ab] 2015-08-19 Fix merge [@tafid]
    - [d1c3a43] 2015-08-19 Some label fix [@tafid]
- Fixed: many for first release
    - [a8a28d0] 2015-08-19 Some fixes on both inline and bulk actions [@tafid]
    - [665436e] 2015-08-19 All inline actions are work [@tafid]
    - [2fa224a] 2015-08-17 Numerious fixes [@tafid]
    - [8ada5ce] 2015-08-12 Add sorter and per page to Host and Domain [@tafid]
    - [f41bc66] 2015-08-07 + sync button not finished [@hiqsol]
    - [1d6c6a3] 2015-08-07 Pjax is working [@tafid]
    - [d807bed] 2015-08-07 doing domain syncing [@hiqsol]
    - [3f2e63a] 2015-08-07 some changes [@tafid]
    - [4a0264c] 2015-08-07 Remove unnecessary USE and extend DomainGridView not for BoxedGridView [@tafid]
    - [33c3308] 2015-08-07 Remove unnecessary USE and extend DomainGridView not for BoxedGridView [@tafid]
    - [d2931dd] 2015-08-06 renamed SmartDeleteAction to SmartPerformAction [@hiqsol]
    - [3394b44] 2015-08-04 fixed filtering back [@hiqsol]
    - [474d6a7] 2015-08-04 Refactor index pages [@tafid]
    - [9b6a2a6] 2015-08-04 Add ActionBox to Host [@tafid]
    - [dd5239a] 2015-08-04 Index page: add ActionBox [@tafid]
    - [2f55a29] 2015-08-02 Code style fixes [@SilverFire]
    - [667e754] 2015-08-02 * Plugin: + aliases [@hiqsol]
    - [0cc25e9] 2015-07-31 + smart actions [@hiqsol]
    - [42178d0] 2015-07-31 used ViewAction [@hiqsol]
    - [3cddfbe] 2015-07-31 used ValidateFormAction and IndexAction [@hiqsol]
    - [b8a17a6] 2015-07-31 actionView fix [@tafid]
    - [76d123a] 2015-07-31 + ValidateFormAction [@hiqsol]
    - [ca2104a] 2015-07-31 + sort button [@hiqsol]
    - [7c3b40d] 2015-07-31 Minor [@SilverFire]
    - [01dd9fa] 2015-07-31 checkbox moved left [@hiqsol]
    - [63864f7] 2015-07-31 + commits.md [@hiqsol]
- Changed: moved to src
    - [36714cf] 2015-07-31 moved to src, hideved [@hiqsol]
- Added basics
    - [cf36043] 2015-07-31 + AdvancedSearch for domains [@hiqsol]
    - [cd94143] 2015-07-30 Fixed typos [@SilverFire]
    - [b133e6a] 2015-07-30 + note xeditable in details [@hiqsol]
    - [e6a32f1] 2015-07-30 improved host with smart actions [@hiqsol]
    - [e4c10b9] 2015-07-29 + host domain column [@hiqsol]
    - [9e7cdeb] 2015-07-29 refactored set-note with SmartUpdateAction [@hiqsol]
    - [482c057] 2015-07-23 + show ID at subtitle [@hiqsol]
    - [bbddf09] 2015-07-17 Work over Domain part functionality [@tafid]
    - [f9ee58b] 2015-07-13 improved link to s-shot [@hiqsol]
    - [b6672f9] 2015-07-13 + s-shot thumb for domains [@hiqsol]
    - [a26074b] 2015-07-10 Initial work with Host [@tafid]
    - [1dfbc86] 2015-07-09 NS and Contacts manipulation [@tafid]
    - [17b302c] 2015-07-02 More changes [@tafid]
    - [045cb2a] 2015-07-02 Auth code almost done [@tafid]
    - [8957705] 2015-07-01 Some other way [@tafid]
    - [fcd3ef7] 2015-06-24 work [@tafid]
    - [ac039c5] 2015-06-19 Initial UI-UX [@tafid]
    - [c7c5fa3] 2015-06-15 Continue work [@tafid]
    - [20038f4] 2015-06-11 Added DomainValidator [@SilverFire]
    - [8bb5508] 2015-06-08 Do workable lock, whois, autorenewal [@tafid]
    - [7ad141a] 2015-05-15 + Plugin, * Menu [@hiqsol]
    - [1e2bdc4] 2015-05-14 + Menu.php and changed breadcrumbing [@hiqsol]
    - [a36883d] 2015-04-23 * use GeoIP as Widget [@BladeRoot]
    - [1cf076b] 2015-04-22 + geoip, map to view [@BladeRoot]
    - [633a335] 2015-04-22 * change dependence of widget [@BladeRoot]
    - [8032812] 2015-04-21 renamed to State from DomainState [@hiqsol]
    - [58f04c1] 2015-04-21 x fix names of widget [@BladeRoot]
    - [35112cc] 2015-04-21 * domain class State extends base widget State [@BladeRoot]
    - [371c102] 2015-04-21 - zero sense lines [@hiqsol]
    - [637a7ca] 2015-04-21 renamed to mergeAttributeLabels from margeA... [@hiqsol]
    - [d19b36d] 2015-04-20 typo [@hiqsol]
    - [fc028b3] 2015-04-20 composer [@hiqsol]
    - [aeec2f7] 2015-04-20 inited [@hiqsol]

## [Development started] - 2015-04-20

[@hiqsol]: https://github.com/hiqsol
[sol@hiqdev.com]: https://github.com/hiqsol
[@SilverFire]: https://github.com/SilverFire
[d.naumenko.a@gmail.com]: https://github.com/SilverFire
[@tafid]: https://github.com/tafid
[andreyklochok@gmail.com]: https://github.com/tafid
[@BladeRoot]: https://github.com/BladeRoot
[bladeroot@gmail.com]: https://github.com/BladeRoot
[0d83813]: https://github.com/hiqdev/hipanel-module-domain/commit/0d83813
[9ef5ddd]: https://github.com/hiqdev/hipanel-module-domain/commit/9ef5ddd
[81e0c14]: https://github.com/hiqdev/hipanel-module-domain/commit/81e0c14
[bcf474c]: https://github.com/hiqdev/hipanel-module-domain/commit/bcf474c
[e57f601]: https://github.com/hiqdev/hipanel-module-domain/commit/e57f601
[4e71211]: https://github.com/hiqdev/hipanel-module-domain/commit/4e71211
[4b0b600]: https://github.com/hiqdev/hipanel-module-domain/commit/4b0b600
[bc01666]: https://github.com/hiqdev/hipanel-module-domain/commit/bc01666
[1e0edc4]: https://github.com/hiqdev/hipanel-module-domain/commit/1e0edc4
[831bf78]: https://github.com/hiqdev/hipanel-module-domain/commit/831bf78
[cf81a2d]: https://github.com/hiqdev/hipanel-module-domain/commit/cf81a2d
[ce025eb]: https://github.com/hiqdev/hipanel-module-domain/commit/ce025eb
[1e3b081]: https://github.com/hiqdev/hipanel-module-domain/commit/1e3b081
[ad74546]: https://github.com/hiqdev/hipanel-module-domain/commit/ad74546
[098bc1c]: https://github.com/hiqdev/hipanel-module-domain/commit/098bc1c
[14bb2a0]: https://github.com/hiqdev/hipanel-module-domain/commit/14bb2a0
[c3a7465]: https://github.com/hiqdev/hipanel-module-domain/commit/c3a7465
[7ee594c]: https://github.com/hiqdev/hipanel-module-domain/commit/7ee594c
[ae7059f]: https://github.com/hiqdev/hipanel-module-domain/commit/ae7059f
[4a2a5e5]: https://github.com/hiqdev/hipanel-module-domain/commit/4a2a5e5
[b10e9e4]: https://github.com/hiqdev/hipanel-module-domain/commit/b10e9e4
[db9a1a9]: https://github.com/hiqdev/hipanel-module-domain/commit/db9a1a9
[cca24cd]: https://github.com/hiqdev/hipanel-module-domain/commit/cca24cd
[3a0fbd7]: https://github.com/hiqdev/hipanel-module-domain/commit/3a0fbd7
[cba40ea]: https://github.com/hiqdev/hipanel-module-domain/commit/cba40ea
[b911eb0]: https://github.com/hiqdev/hipanel-module-domain/commit/b911eb0
[4af40f2]: https://github.com/hiqdev/hipanel-module-domain/commit/4af40f2
[e9fe7e1]: https://github.com/hiqdev/hipanel-module-domain/commit/e9fe7e1
[3b565c7]: https://github.com/hiqdev/hipanel-module-domain/commit/3b565c7
[fd7da57]: https://github.com/hiqdev/hipanel-module-domain/commit/fd7da57
[7f31b80]: https://github.com/hiqdev/hipanel-module-domain/commit/7f31b80
[48038fd]: https://github.com/hiqdev/hipanel-module-domain/commit/48038fd
[55fb325]: https://github.com/hiqdev/hipanel-module-domain/commit/55fb325
[59cee3f]: https://github.com/hiqdev/hipanel-module-domain/commit/59cee3f
[1f4dca1]: https://github.com/hiqdev/hipanel-module-domain/commit/1f4dca1
[4dd44d4]: https://github.com/hiqdev/hipanel-module-domain/commit/4dd44d4
[e0200f8]: https://github.com/hiqdev/hipanel-module-domain/commit/e0200f8
[27172b8]: https://github.com/hiqdev/hipanel-module-domain/commit/27172b8
[875d88f]: https://github.com/hiqdev/hipanel-module-domain/commit/875d88f
[7d02e39]: https://github.com/hiqdev/hipanel-module-domain/commit/7d02e39
[4657f2e]: https://github.com/hiqdev/hipanel-module-domain/commit/4657f2e
[9710ecd]: https://github.com/hiqdev/hipanel-module-domain/commit/9710ecd
[305e87c]: https://github.com/hiqdev/hipanel-module-domain/commit/305e87c
[97acace]: https://github.com/hiqdev/hipanel-module-domain/commit/97acace
[64ed654]: https://github.com/hiqdev/hipanel-module-domain/commit/64ed654
[1eb9911]: https://github.com/hiqdev/hipanel-module-domain/commit/1eb9911
[f7d6466]: https://github.com/hiqdev/hipanel-module-domain/commit/f7d6466
[a815bbd]: https://github.com/hiqdev/hipanel-module-domain/commit/a815bbd
[7166a78]: https://github.com/hiqdev/hipanel-module-domain/commit/7166a78
[e0a2c9e]: https://github.com/hiqdev/hipanel-module-domain/commit/e0a2c9e
[44915ed]: https://github.com/hiqdev/hipanel-module-domain/commit/44915ed
[bdbc84f]: https://github.com/hiqdev/hipanel-module-domain/commit/bdbc84f
[281d63e]: https://github.com/hiqdev/hipanel-module-domain/commit/281d63e
[e43697f]: https://github.com/hiqdev/hipanel-module-domain/commit/e43697f
[e5ee2ec]: https://github.com/hiqdev/hipanel-module-domain/commit/e5ee2ec
[56d5cf5]: https://github.com/hiqdev/hipanel-module-domain/commit/56d5cf5
[f7bae23]: https://github.com/hiqdev/hipanel-module-domain/commit/f7bae23
[cf42821]: https://github.com/hiqdev/hipanel-module-domain/commit/cf42821
[2f6b281]: https://github.com/hiqdev/hipanel-module-domain/commit/2f6b281
[81e95ee]: https://github.com/hiqdev/hipanel-module-domain/commit/81e95ee
[b5974b1]: https://github.com/hiqdev/hipanel-module-domain/commit/b5974b1
[f38a24a]: https://github.com/hiqdev/hipanel-module-domain/commit/f38a24a
[5eea720]: https://github.com/hiqdev/hipanel-module-domain/commit/5eea720
[e0cf7bc]: https://github.com/hiqdev/hipanel-module-domain/commit/e0cf7bc
[a3a669b]: https://github.com/hiqdev/hipanel-module-domain/commit/a3a669b
[a0d5197]: https://github.com/hiqdev/hipanel-module-domain/commit/a0d5197
[1a88707]: https://github.com/hiqdev/hipanel-module-domain/commit/1a88707
[910a258]: https://github.com/hiqdev/hipanel-module-domain/commit/910a258
[7ad02a2]: https://github.com/hiqdev/hipanel-module-domain/commit/7ad02a2
[063d575]: https://github.com/hiqdev/hipanel-module-domain/commit/063d575
[48bdc8d]: https://github.com/hiqdev/hipanel-module-domain/commit/48bdc8d
[d4555b5]: https://github.com/hiqdev/hipanel-module-domain/commit/d4555b5
[2e3981f]: https://github.com/hiqdev/hipanel-module-domain/commit/2e3981f
[675644d]: https://github.com/hiqdev/hipanel-module-domain/commit/675644d
[fa960e9]: https://github.com/hiqdev/hipanel-module-domain/commit/fa960e9
[b15743f]: https://github.com/hiqdev/hipanel-module-domain/commit/b15743f
[9c81f7e]: https://github.com/hiqdev/hipanel-module-domain/commit/9c81f7e
[608c322]: https://github.com/hiqdev/hipanel-module-domain/commit/608c322
[6ad1c64]: https://github.com/hiqdev/hipanel-module-domain/commit/6ad1c64
[19a32cf]: https://github.com/hiqdev/hipanel-module-domain/commit/19a32cf
[f253dea]: https://github.com/hiqdev/hipanel-module-domain/commit/f253dea
[6e6ee5b]: https://github.com/hiqdev/hipanel-module-domain/commit/6e6ee5b
[e2e4189]: https://github.com/hiqdev/hipanel-module-domain/commit/e2e4189
[13b18be]: https://github.com/hiqdev/hipanel-module-domain/commit/13b18be
[b3b4766]: https://github.com/hiqdev/hipanel-module-domain/commit/b3b4766
[6f56a9e]: https://github.com/hiqdev/hipanel-module-domain/commit/6f56a9e
[144a366]: https://github.com/hiqdev/hipanel-module-domain/commit/144a366
[b3c1617]: https://github.com/hiqdev/hipanel-module-domain/commit/b3c1617
[408240f]: https://github.com/hiqdev/hipanel-module-domain/commit/408240f
[db842ec]: https://github.com/hiqdev/hipanel-module-domain/commit/db842ec
[f9b13ac]: https://github.com/hiqdev/hipanel-module-domain/commit/f9b13ac
[acdc017]: https://github.com/hiqdev/hipanel-module-domain/commit/acdc017
[6101948]: https://github.com/hiqdev/hipanel-module-domain/commit/6101948
[73bf601]: https://github.com/hiqdev/hipanel-module-domain/commit/73bf601
[485e10d]: https://github.com/hiqdev/hipanel-module-domain/commit/485e10d
[fb12001]: https://github.com/hiqdev/hipanel-module-domain/commit/fb12001
[23d406a]: https://github.com/hiqdev/hipanel-module-domain/commit/23d406a
[a74ea36]: https://github.com/hiqdev/hipanel-module-domain/commit/a74ea36
[ed55e61]: https://github.com/hiqdev/hipanel-module-domain/commit/ed55e61
[a67736a]: https://github.com/hiqdev/hipanel-module-domain/commit/a67736a
[0855dee]: https://github.com/hiqdev/hipanel-module-domain/commit/0855dee
[7d82740]: https://github.com/hiqdev/hipanel-module-domain/commit/7d82740
[54a26da]: https://github.com/hiqdev/hipanel-module-domain/commit/54a26da
[c784e15]: https://github.com/hiqdev/hipanel-module-domain/commit/c784e15
[ffb2f34]: https://github.com/hiqdev/hipanel-module-domain/commit/ffb2f34
[792ccb8]: https://github.com/hiqdev/hipanel-module-domain/commit/792ccb8
[705d681]: https://github.com/hiqdev/hipanel-module-domain/commit/705d681
[66c6406]: https://github.com/hiqdev/hipanel-module-domain/commit/66c6406
[e2076ec]: https://github.com/hiqdev/hipanel-module-domain/commit/e2076ec
[ee513ca]: https://github.com/hiqdev/hipanel-module-domain/commit/ee513ca
[c2f5617]: https://github.com/hiqdev/hipanel-module-domain/commit/c2f5617
[6b194dd]: https://github.com/hiqdev/hipanel-module-domain/commit/6b194dd
[cda5d94]: https://github.com/hiqdev/hipanel-module-domain/commit/cda5d94
[22feb8f]: https://github.com/hiqdev/hipanel-module-domain/commit/22feb8f
[f54c640]: https://github.com/hiqdev/hipanel-module-domain/commit/f54c640
[8e27fe8]: https://github.com/hiqdev/hipanel-module-domain/commit/8e27fe8
[5a95e6a]: https://github.com/hiqdev/hipanel-module-domain/commit/5a95e6a
[539073b]: https://github.com/hiqdev/hipanel-module-domain/commit/539073b
[fcb7c2d]: https://github.com/hiqdev/hipanel-module-domain/commit/fcb7c2d
[c369923]: https://github.com/hiqdev/hipanel-module-domain/commit/c369923
[c5efb67]: https://github.com/hiqdev/hipanel-module-domain/commit/c5efb67
[94c2f4c]: https://github.com/hiqdev/hipanel-module-domain/commit/94c2f4c
[1a5deac]: https://github.com/hiqdev/hipanel-module-domain/commit/1a5deac
[aa0cf08]: https://github.com/hiqdev/hipanel-module-domain/commit/aa0cf08
[2eaf1b7]: https://github.com/hiqdev/hipanel-module-domain/commit/2eaf1b7
[2dd1338]: https://github.com/hiqdev/hipanel-module-domain/commit/2dd1338
[26c5f80]: https://github.com/hiqdev/hipanel-module-domain/commit/26c5f80
[b9533c4]: https://github.com/hiqdev/hipanel-module-domain/commit/b9533c4
[5691294]: https://github.com/hiqdev/hipanel-module-domain/commit/5691294
[da3804b]: https://github.com/hiqdev/hipanel-module-domain/commit/da3804b
[58c165e]: https://github.com/hiqdev/hipanel-module-domain/commit/58c165e
[8705a2e]: https://github.com/hiqdev/hipanel-module-domain/commit/8705a2e
[42d2249]: https://github.com/hiqdev/hipanel-module-domain/commit/42d2249
[246ac1e]: https://github.com/hiqdev/hipanel-module-domain/commit/246ac1e
[5c7e551]: https://github.com/hiqdev/hipanel-module-domain/commit/5c7e551
[91fd645]: https://github.com/hiqdev/hipanel-module-domain/commit/91fd645
[5e63c79]: https://github.com/hiqdev/hipanel-module-domain/commit/5e63c79
[37d668b]: https://github.com/hiqdev/hipanel-module-domain/commit/37d668b
[bd30ac0]: https://github.com/hiqdev/hipanel-module-domain/commit/bd30ac0
[ac92281]: https://github.com/hiqdev/hipanel-module-domain/commit/ac92281
[3b3b6fa]: https://github.com/hiqdev/hipanel-module-domain/commit/3b3b6fa
[2049fc9]: https://github.com/hiqdev/hipanel-module-domain/commit/2049fc9
[5b99929]: https://github.com/hiqdev/hipanel-module-domain/commit/5b99929
[00c498d]: https://github.com/hiqdev/hipanel-module-domain/commit/00c498d
[f1271ef]: https://github.com/hiqdev/hipanel-module-domain/commit/f1271ef
[4b3a9a9]: https://github.com/hiqdev/hipanel-module-domain/commit/4b3a9a9
[7e7ffd2]: https://github.com/hiqdev/hipanel-module-domain/commit/7e7ffd2
[16b6739]: https://github.com/hiqdev/hipanel-module-domain/commit/16b6739
[3eb3cc5]: https://github.com/hiqdev/hipanel-module-domain/commit/3eb3cc5
[b30920d]: https://github.com/hiqdev/hipanel-module-domain/commit/b30920d
[30ecab7]: https://github.com/hiqdev/hipanel-module-domain/commit/30ecab7
[616c5c2]: https://github.com/hiqdev/hipanel-module-domain/commit/616c5c2
[bc15164]: https://github.com/hiqdev/hipanel-module-domain/commit/bc15164
[57a44d3]: https://github.com/hiqdev/hipanel-module-domain/commit/57a44d3
[6b2b55e]: https://github.com/hiqdev/hipanel-module-domain/commit/6b2b55e
[569f10c]: https://github.com/hiqdev/hipanel-module-domain/commit/569f10c
[5ebede8]: https://github.com/hiqdev/hipanel-module-domain/commit/5ebede8
[a008c29]: https://github.com/hiqdev/hipanel-module-domain/commit/a008c29
[3db5032]: https://github.com/hiqdev/hipanel-module-domain/commit/3db5032
[191eeaf]: https://github.com/hiqdev/hipanel-module-domain/commit/191eeaf
[027f210]: https://github.com/hiqdev/hipanel-module-domain/commit/027f210
[a113416]: https://github.com/hiqdev/hipanel-module-domain/commit/a113416
[0d5594b]: https://github.com/hiqdev/hipanel-module-domain/commit/0d5594b
[1ec293c]: https://github.com/hiqdev/hipanel-module-domain/commit/1ec293c
[73349c8]: https://github.com/hiqdev/hipanel-module-domain/commit/73349c8
[7a26546]: https://github.com/hiqdev/hipanel-module-domain/commit/7a26546
[2dd6135]: https://github.com/hiqdev/hipanel-module-domain/commit/2dd6135
[b36ede5]: https://github.com/hiqdev/hipanel-module-domain/commit/b36ede5
[c22b2d8]: https://github.com/hiqdev/hipanel-module-domain/commit/c22b2d8
[9811ee7]: https://github.com/hiqdev/hipanel-module-domain/commit/9811ee7
[d8b65a4]: https://github.com/hiqdev/hipanel-module-domain/commit/d8b65a4
[f97108a]: https://github.com/hiqdev/hipanel-module-domain/commit/f97108a
[e6340c9]: https://github.com/hiqdev/hipanel-module-domain/commit/e6340c9
[9792fc7]: https://github.com/hiqdev/hipanel-module-domain/commit/9792fc7
[1b8d518]: https://github.com/hiqdev/hipanel-module-domain/commit/1b8d518
[53e7450]: https://github.com/hiqdev/hipanel-module-domain/commit/53e7450
[aa20645]: https://github.com/hiqdev/hipanel-module-domain/commit/aa20645
[552dc85]: https://github.com/hiqdev/hipanel-module-domain/commit/552dc85
[359d017]: https://github.com/hiqdev/hipanel-module-domain/commit/359d017
[0969cab]: https://github.com/hiqdev/hipanel-module-domain/commit/0969cab
[2bb123b]: https://github.com/hiqdev/hipanel-module-domain/commit/2bb123b
[9e83d06]: https://github.com/hiqdev/hipanel-module-domain/commit/9e83d06
[a1b37b0]: https://github.com/hiqdev/hipanel-module-domain/commit/a1b37b0
[f4dbd8c]: https://github.com/hiqdev/hipanel-module-domain/commit/f4dbd8c
[1794982]: https://github.com/hiqdev/hipanel-module-domain/commit/1794982
[d072fd0]: https://github.com/hiqdev/hipanel-module-domain/commit/d072fd0
[d751f9f]: https://github.com/hiqdev/hipanel-module-domain/commit/d751f9f
[1de7446]: https://github.com/hiqdev/hipanel-module-domain/commit/1de7446
[1152284]: https://github.com/hiqdev/hipanel-module-domain/commit/1152284
[426728d]: https://github.com/hiqdev/hipanel-module-domain/commit/426728d
[7ccb975]: https://github.com/hiqdev/hipanel-module-domain/commit/7ccb975
[bcba156]: https://github.com/hiqdev/hipanel-module-domain/commit/bcba156
[8b72875]: https://github.com/hiqdev/hipanel-module-domain/commit/8b72875
[aa14c34]: https://github.com/hiqdev/hipanel-module-domain/commit/aa14c34
[bdb83ab]: https://github.com/hiqdev/hipanel-module-domain/commit/bdb83ab
[d1c3a43]: https://github.com/hiqdev/hipanel-module-domain/commit/d1c3a43
[a8a28d0]: https://github.com/hiqdev/hipanel-module-domain/commit/a8a28d0
[665436e]: https://github.com/hiqdev/hipanel-module-domain/commit/665436e
[2fa224a]: https://github.com/hiqdev/hipanel-module-domain/commit/2fa224a
[8ada5ce]: https://github.com/hiqdev/hipanel-module-domain/commit/8ada5ce
[f41bc66]: https://github.com/hiqdev/hipanel-module-domain/commit/f41bc66
[1d6c6a3]: https://github.com/hiqdev/hipanel-module-domain/commit/1d6c6a3
[d807bed]: https://github.com/hiqdev/hipanel-module-domain/commit/d807bed
[3f2e63a]: https://github.com/hiqdev/hipanel-module-domain/commit/3f2e63a
[4a0264c]: https://github.com/hiqdev/hipanel-module-domain/commit/4a0264c
[33c3308]: https://github.com/hiqdev/hipanel-module-domain/commit/33c3308
[d2931dd]: https://github.com/hiqdev/hipanel-module-domain/commit/d2931dd
[3394b44]: https://github.com/hiqdev/hipanel-module-domain/commit/3394b44
[474d6a7]: https://github.com/hiqdev/hipanel-module-domain/commit/474d6a7
[9b6a2a6]: https://github.com/hiqdev/hipanel-module-domain/commit/9b6a2a6
[dd5239a]: https://github.com/hiqdev/hipanel-module-domain/commit/dd5239a
[2f55a29]: https://github.com/hiqdev/hipanel-module-domain/commit/2f55a29
[667e754]: https://github.com/hiqdev/hipanel-module-domain/commit/667e754
[0cc25e9]: https://github.com/hiqdev/hipanel-module-domain/commit/0cc25e9
[42178d0]: https://github.com/hiqdev/hipanel-module-domain/commit/42178d0
[3cddfbe]: https://github.com/hiqdev/hipanel-module-domain/commit/3cddfbe
[b8a17a6]: https://github.com/hiqdev/hipanel-module-domain/commit/b8a17a6
[76d123a]: https://github.com/hiqdev/hipanel-module-domain/commit/76d123a
[ca2104a]: https://github.com/hiqdev/hipanel-module-domain/commit/ca2104a
[7c3b40d]: https://github.com/hiqdev/hipanel-module-domain/commit/7c3b40d
[01dd9fa]: https://github.com/hiqdev/hipanel-module-domain/commit/01dd9fa
[63864f7]: https://github.com/hiqdev/hipanel-module-domain/commit/63864f7
[36714cf]: https://github.com/hiqdev/hipanel-module-domain/commit/36714cf
[cf36043]: https://github.com/hiqdev/hipanel-module-domain/commit/cf36043
[cd94143]: https://github.com/hiqdev/hipanel-module-domain/commit/cd94143
[b133e6a]: https://github.com/hiqdev/hipanel-module-domain/commit/b133e6a
[e6a32f1]: https://github.com/hiqdev/hipanel-module-domain/commit/e6a32f1
[e4c10b9]: https://github.com/hiqdev/hipanel-module-domain/commit/e4c10b9
[9e7cdeb]: https://github.com/hiqdev/hipanel-module-domain/commit/9e7cdeb
[482c057]: https://github.com/hiqdev/hipanel-module-domain/commit/482c057
[bbddf09]: https://github.com/hiqdev/hipanel-module-domain/commit/bbddf09
[f9ee58b]: https://github.com/hiqdev/hipanel-module-domain/commit/f9ee58b
[b6672f9]: https://github.com/hiqdev/hipanel-module-domain/commit/b6672f9
[a26074b]: https://github.com/hiqdev/hipanel-module-domain/commit/a26074b
[1dfbc86]: https://github.com/hiqdev/hipanel-module-domain/commit/1dfbc86
[17b302c]: https://github.com/hiqdev/hipanel-module-domain/commit/17b302c
[045cb2a]: https://github.com/hiqdev/hipanel-module-domain/commit/045cb2a
[8957705]: https://github.com/hiqdev/hipanel-module-domain/commit/8957705
[fcd3ef7]: https://github.com/hiqdev/hipanel-module-domain/commit/fcd3ef7
[ac039c5]: https://github.com/hiqdev/hipanel-module-domain/commit/ac039c5
[c7c5fa3]: https://github.com/hiqdev/hipanel-module-domain/commit/c7c5fa3
[20038f4]: https://github.com/hiqdev/hipanel-module-domain/commit/20038f4
[8bb5508]: https://github.com/hiqdev/hipanel-module-domain/commit/8bb5508
[7ad141a]: https://github.com/hiqdev/hipanel-module-domain/commit/7ad141a
[1e2bdc4]: https://github.com/hiqdev/hipanel-module-domain/commit/1e2bdc4
[a36883d]: https://github.com/hiqdev/hipanel-module-domain/commit/a36883d
[1cf076b]: https://github.com/hiqdev/hipanel-module-domain/commit/1cf076b
[633a335]: https://github.com/hiqdev/hipanel-module-domain/commit/633a335
[8032812]: https://github.com/hiqdev/hipanel-module-domain/commit/8032812
[58f04c1]: https://github.com/hiqdev/hipanel-module-domain/commit/58f04c1
[35112cc]: https://github.com/hiqdev/hipanel-module-domain/commit/35112cc
[371c102]: https://github.com/hiqdev/hipanel-module-domain/commit/371c102
[637a7ca]: https://github.com/hiqdev/hipanel-module-domain/commit/637a7ca
[d19b36d]: https://github.com/hiqdev/hipanel-module-domain/commit/d19b36d
[fc028b3]: https://github.com/hiqdev/hipanel-module-domain/commit/fc028b3
[aeec2f7]: https://github.com/hiqdev/hipanel-module-domain/commit/aeec2f7
[3650409]: https://github.com/hiqdev/hipanel-module-domain/commit/3650409
[3c7144d]: https://github.com/hiqdev/hipanel-module-domain/commit/3c7144d
[c3d8260]: https://github.com/hiqdev/hipanel-module-domain/commit/c3d8260
[052d366]: https://github.com/hiqdev/hipanel-module-domain/commit/052d366
[2ad7d5e]: https://github.com/hiqdev/hipanel-module-domain/commit/2ad7d5e
[9216ba6]: https://github.com/hiqdev/hipanel-module-domain/commit/9216ba6
[d954157]: https://github.com/hiqdev/hipanel-module-domain/commit/d954157
[5cf8e37]: https://github.com/hiqdev/hipanel-module-domain/commit/5cf8e37
[6022b24]: https://github.com/hiqdev/hipanel-module-domain/commit/6022b24
[2bbf741]: https://github.com/hiqdev/hipanel-module-domain/commit/2bbf741
[00e56c2]: https://github.com/hiqdev/hipanel-module-domain/commit/00e56c2
[e15b87e]: https://github.com/hiqdev/hipanel-module-domain/commit/e15b87e
[230674a]: https://github.com/hiqdev/hipanel-module-domain/commit/230674a
[75e8be4]: https://github.com/hiqdev/hipanel-module-domain/commit/75e8be4
[5d5a660]: https://github.com/hiqdev/hipanel-module-domain/commit/5d5a660
[c406d4e]: https://github.com/hiqdev/hipanel-module-domain/commit/c406d4e
[7cf352a]: https://github.com/hiqdev/hipanel-module-domain/commit/7cf352a
[7b68be2]: https://github.com/hiqdev/hipanel-module-domain/commit/7b68be2
[e43d922]: https://github.com/hiqdev/hipanel-module-domain/commit/e43d922
[ec38f82]: https://github.com/hiqdev/hipanel-module-domain/commit/ec38f82
[0c25b16]: https://github.com/hiqdev/hipanel-module-domain/commit/0c25b16
[7fa4b09]: https://github.com/hiqdev/hipanel-module-domain/commit/7fa4b09
[9ce6d0b]: https://github.com/hiqdev/hipanel-module-domain/commit/9ce6d0b
[a9d83cf]: https://github.com/hiqdev/hipanel-module-domain/commit/a9d83cf
[d0abcb4]: https://github.com/hiqdev/hipanel-module-domain/commit/d0abcb4
[81a22ec]: https://github.com/hiqdev/hipanel-module-domain/commit/81a22ec
[a51f8a2]: https://github.com/hiqdev/hipanel-module-domain/commit/a51f8a2
[5808b54]: https://github.com/hiqdev/hipanel-module-domain/commit/5808b54
[ff63119]: https://github.com/hiqdev/hipanel-module-domain/commit/ff63119
[c38dc78]: https://github.com/hiqdev/hipanel-module-domain/commit/c38dc78
[a32363d]: https://github.com/hiqdev/hipanel-module-domain/commit/a32363d
[634320a]: https://github.com/hiqdev/hipanel-module-domain/commit/634320a
[73cdf62]: https://github.com/hiqdev/hipanel-module-domain/commit/73cdf62
[b9f1470]: https://github.com/hiqdev/hipanel-module-domain/commit/b9f1470
[212981c]: https://github.com/hiqdev/hipanel-module-domain/commit/212981c
[ee37c97]: https://github.com/hiqdev/hipanel-module-domain/commit/ee37c97
[17a91a4]: https://github.com/hiqdev/hipanel-module-domain/commit/17a91a4
[a6c5bba]: https://github.com/hiqdev/hipanel-module-domain/commit/a6c5bba
[753dfd1]: https://github.com/hiqdev/hipanel-module-domain/commit/753dfd1
[4bc53f5]: https://github.com/hiqdev/hipanel-module-domain/commit/4bc53f5
[224543e]: https://github.com/hiqdev/hipanel-module-domain/commit/224543e
[ecc900a]: https://github.com/hiqdev/hipanel-module-domain/commit/ecc900a
[623a59e]: https://github.com/hiqdev/hipanel-module-domain/commit/623a59e
[4af7f30]: https://github.com/hiqdev/hipanel-module-domain/commit/4af7f30
[4f70475]: https://github.com/hiqdev/hipanel-module-domain/commit/4f70475
[11ac831]: https://github.com/hiqdev/hipanel-module-domain/commit/11ac831
[b0715f3]: https://github.com/hiqdev/hipanel-module-domain/commit/b0715f3
[095e80d]: https://github.com/hiqdev/hipanel-module-domain/commit/095e80d
[7eb572b]: https://github.com/hiqdev/hipanel-module-domain/commit/7eb572b
[8fbb804]: https://github.com/hiqdev/hipanel-module-domain/commit/8fbb804
[6d7a803]: https://github.com/hiqdev/hipanel-module-domain/commit/6d7a803
[538a628]: https://github.com/hiqdev/hipanel-module-domain/commit/538a628
[e2537a5]: https://github.com/hiqdev/hipanel-module-domain/commit/e2537a5
[a79beed]: https://github.com/hiqdev/hipanel-module-domain/commit/a79beed
[eb2202c]: https://github.com/hiqdev/hipanel-module-domain/commit/eb2202c
[72d1623]: https://github.com/hiqdev/hipanel-module-domain/commit/72d1623
[92f8326]: https://github.com/hiqdev/hipanel-module-domain/commit/92f8326
[62fa6bd]: https://github.com/hiqdev/hipanel-module-domain/commit/62fa6bd
[2759c56]: https://github.com/hiqdev/hipanel-module-domain/commit/2759c56
[97296f2]: https://github.com/hiqdev/hipanel-module-domain/commit/97296f2
[dfefbdb]: https://github.com/hiqdev/hipanel-module-domain/commit/dfefbdb
[1f5efc7]: https://github.com/hiqdev/hipanel-module-domain/commit/1f5efc7
[69fae76]: https://github.com/hiqdev/hipanel-module-domain/commit/69fae76
[7cbf4aa]: https://github.com/hiqdev/hipanel-module-domain/commit/7cbf4aa
[8f8c5cc]: https://github.com/hiqdev/hipanel-module-domain/commit/8f8c5cc
[299f105]: https://github.com/hiqdev/hipanel-module-domain/commit/299f105
[db12301]: https://github.com/hiqdev/hipanel-module-domain/commit/db12301
[cb2eb0a]: https://github.com/hiqdev/hipanel-module-domain/commit/cb2eb0a
[d586573]: https://github.com/hiqdev/hipanel-module-domain/commit/d586573
[1e48249]: https://github.com/hiqdev/hipanel-module-domain/commit/1e48249
[cbb74d5]: https://github.com/hiqdev/hipanel-module-domain/commit/cbb74d5
[99c8a98]: https://github.com/hiqdev/hipanel-module-domain/commit/99c8a98
[c0c4afa]: https://github.com/hiqdev/hipanel-module-domain/commit/c0c4afa
[002a642]: https://github.com/hiqdev/hipanel-module-domain/commit/002a642
[fd70961]: https://github.com/hiqdev/hipanel-module-domain/commit/fd70961
[e9160e8]: https://github.com/hiqdev/hipanel-module-domain/commit/e9160e8
[a9e087a]: https://github.com/hiqdev/hipanel-module-domain/commit/a9e087a
[451ed56]: https://github.com/hiqdev/hipanel-module-domain/commit/451ed56
[91c316b]: https://github.com/hiqdev/hipanel-module-domain/commit/91c316b
[99e2f3f]: https://github.com/hiqdev/hipanel-module-domain/commit/99e2f3f
[fa2bb93]: https://github.com/hiqdev/hipanel-module-domain/commit/fa2bb93
[6b08385]: https://github.com/hiqdev/hipanel-module-domain/commit/6b08385
[0499158]: https://github.com/hiqdev/hipanel-module-domain/commit/0499158
[580ff39]: https://github.com/hiqdev/hipanel-module-domain/commit/580ff39
[f508b1a]: https://github.com/hiqdev/hipanel-module-domain/commit/f508b1a
[204cc96]: https://github.com/hiqdev/hipanel-module-domain/commit/204cc96
[1b8173f]: https://github.com/hiqdev/hipanel-module-domain/commit/1b8173f
[9cc9518]: https://github.com/hiqdev/hipanel-module-domain/commit/9cc9518
[2bd2a9a]: https://github.com/hiqdev/hipanel-module-domain/commit/2bd2a9a
[80963f7]: https://github.com/hiqdev/hipanel-module-domain/commit/80963f7
[9f57046]: https://github.com/hiqdev/hipanel-module-domain/commit/9f57046
[7223de9]: https://github.com/hiqdev/hipanel-module-domain/commit/7223de9
[406c118]: https://github.com/hiqdev/hipanel-module-domain/commit/406c118
[80c99fa]: https://github.com/hiqdev/hipanel-module-domain/commit/80c99fa
[17b8a46]: https://github.com/hiqdev/hipanel-module-domain/commit/17b8a46
[fc5deae]: https://github.com/hiqdev/hipanel-module-domain/commit/fc5deae
[6b95ae4]: https://github.com/hiqdev/hipanel-module-domain/commit/6b95ae4
[1295230]: https://github.com/hiqdev/hipanel-module-domain/commit/1295230
[952d557]: https://github.com/hiqdev/hipanel-module-domain/commit/952d557
[02b57de]: https://github.com/hiqdev/hipanel-module-domain/commit/02b57de
[6ca7999]: https://github.com/hiqdev/hipanel-module-domain/commit/6ca7999
[5314447]: https://github.com/hiqdev/hipanel-module-domain/commit/5314447
[d875cd2]: https://github.com/hiqdev/hipanel-module-domain/commit/d875cd2
[8e82a86]: https://github.com/hiqdev/hipanel-module-domain/commit/8e82a86
[1ba4d19]: https://github.com/hiqdev/hipanel-module-domain/commit/1ba4d19
[c322e0d]: https://github.com/hiqdev/hipanel-module-domain/commit/c322e0d
[c7b9ec7]: https://github.com/hiqdev/hipanel-module-domain/commit/c7b9ec7
[0118c91]: https://github.com/hiqdev/hipanel-module-domain/commit/0118c91
[bc3159b]: https://github.com/hiqdev/hipanel-module-domain/commit/bc3159b
[70c01cb]: https://github.com/hiqdev/hipanel-module-domain/commit/70c01cb
[8b9523d]: https://github.com/hiqdev/hipanel-module-domain/commit/8b9523d
[5e5ca21]: https://github.com/hiqdev/hipanel-module-domain/commit/5e5ca21
[c43f05e]: https://github.com/hiqdev/hipanel-module-domain/commit/c43f05e
[515fdbf]: https://github.com/hiqdev/hipanel-module-domain/commit/515fdbf
[eba28f4]: https://github.com/hiqdev/hipanel-module-domain/commit/eba28f4
[1f87a89]: https://github.com/hiqdev/hipanel-module-domain/commit/1f87a89
[59daced]: https://github.com/hiqdev/hipanel-module-domain/commit/59daced
[95e8e3d]: https://github.com/hiqdev/hipanel-module-domain/commit/95e8e3d
[e0390e2]: https://github.com/hiqdev/hipanel-module-domain/commit/e0390e2
[377fa07]: https://github.com/hiqdev/hipanel-module-domain/commit/377fa07
[661dfb9]: https://github.com/hiqdev/hipanel-module-domain/commit/661dfb9
[b53fd46]: https://github.com/hiqdev/hipanel-module-domain/commit/b53fd46
[a839c3f]: https://github.com/hiqdev/hipanel-module-domain/commit/a839c3f
[ce718c2]: https://github.com/hiqdev/hipanel-module-domain/commit/ce718c2
[a9b79ec]: https://github.com/hiqdev/hipanel-module-domain/commit/a9b79ec
[d343f88]: https://github.com/hiqdev/hipanel-module-domain/commit/d343f88
[6ff4595]: https://github.com/hiqdev/hipanel-module-domain/commit/6ff4595
[bb3127d]: https://github.com/hiqdev/hipanel-module-domain/commit/bb3127d
[857ed50]: https://github.com/hiqdev/hipanel-module-domain/commit/857ed50
[cc058a2]: https://github.com/hiqdev/hipanel-module-domain/commit/cc058a2
[44ef3d5]: https://github.com/hiqdev/hipanel-module-domain/commit/44ef3d5
[f6c9169]: https://github.com/hiqdev/hipanel-module-domain/commit/f6c9169
[6f91e54]: https://github.com/hiqdev/hipanel-module-domain/commit/6f91e54
[7e9669d]: https://github.com/hiqdev/hipanel-module-domain/commit/7e9669d
[839c874]: https://github.com/hiqdev/hipanel-module-domain/commit/839c874
[dd3d7a3]: https://github.com/hiqdev/hipanel-module-domain/commit/dd3d7a3
[dfe2aef]: https://github.com/hiqdev/hipanel-module-domain/commit/dfe2aef
[274a0a6]: https://github.com/hiqdev/hipanel-module-domain/commit/274a0a6
[49e2181]: https://github.com/hiqdev/hipanel-module-domain/commit/49e2181
[56a50c4]: https://github.com/hiqdev/hipanel-module-domain/commit/56a50c4
[628da00]: https://github.com/hiqdev/hipanel-module-domain/commit/628da00
[6f57e6f]: https://github.com/hiqdev/hipanel-module-domain/commit/6f57e6f
[33cfc43]: https://github.com/hiqdev/hipanel-module-domain/commit/33cfc43
[cd1a29c]: https://github.com/hiqdev/hipanel-module-domain/commit/cd1a29c
[afa71d2]: https://github.com/hiqdev/hipanel-module-domain/commit/afa71d2
[c8ee329]: https://github.com/hiqdev/hipanel-module-domain/commit/c8ee329
[7cdc17d]: https://github.com/hiqdev/hipanel-module-domain/commit/7cdc17d
[c2d2a62]: https://github.com/hiqdev/hipanel-module-domain/commit/c2d2a62
[7bd8097]: https://github.com/hiqdev/hipanel-module-domain/commit/7bd8097
[8549627]: https://github.com/hiqdev/hipanel-module-domain/commit/8549627
[94791a2]: https://github.com/hiqdev/hipanel-module-domain/commit/94791a2
[65214c2]: https://github.com/hiqdev/hipanel-module-domain/commit/65214c2
[22ac1d3]: https://github.com/hiqdev/hipanel-module-domain/commit/22ac1d3
[11ca48f]: https://github.com/hiqdev/hipanel-module-domain/commit/11ca48f
[1290c29]: https://github.com/hiqdev/hipanel-module-domain/commit/1290c29
[c3e94cf]: https://github.com/hiqdev/hipanel-module-domain/commit/c3e94cf
[f7ece47]: https://github.com/hiqdev/hipanel-module-domain/commit/f7ece47
[8111d0a]: https://github.com/hiqdev/hipanel-module-domain/commit/8111d0a
[e1a3213]: https://github.com/hiqdev/hipanel-module-domain/commit/e1a3213
[bf01059]: https://github.com/hiqdev/hipanel-module-domain/commit/bf01059
[b9f6e5d]: https://github.com/hiqdev/hipanel-module-domain/commit/b9f6e5d
[791d340]: https://github.com/hiqdev/hipanel-module-domain/commit/791d340
[6252aaf]: https://github.com/hiqdev/hipanel-module-domain/commit/6252aaf
[f34489e]: https://github.com/hiqdev/hipanel-module-domain/commit/f34489e
[fe75660]: https://github.com/hiqdev/hipanel-module-domain/commit/fe75660
[2ad0867]: https://github.com/hiqdev/hipanel-module-domain/commit/2ad0867
[a47308a]: https://github.com/hiqdev/hipanel-module-domain/commit/a47308a
[77bbf62]: https://github.com/hiqdev/hipanel-module-domain/commit/77bbf62
[ef62597]: https://github.com/hiqdev/hipanel-module-domain/commit/ef62597
[19e3fa9]: https://github.com/hiqdev/hipanel-module-domain/commit/19e3fa9
[2b0a437]: https://github.com/hiqdev/hipanel-module-domain/commit/2b0a437
[e218a48]: https://github.com/hiqdev/hipanel-module-domain/commit/e218a48
[1f2b8ad]: https://github.com/hiqdev/hipanel-module-domain/commit/1f2b8ad
[6556517]: https://github.com/hiqdev/hipanel-module-domain/commit/6556517
[23b1700]: https://github.com/hiqdev/hipanel-module-domain/commit/23b1700
[b89b1d3]: https://github.com/hiqdev/hipanel-module-domain/commit/b89b1d3
[3555d8a]: https://github.com/hiqdev/hipanel-module-domain/commit/3555d8a
[35ff0d1]: https://github.com/hiqdev/hipanel-module-domain/commit/35ff0d1
[dab4e36]: https://github.com/hiqdev/hipanel-module-domain/commit/dab4e36
[6540eb5]: https://github.com/hiqdev/hipanel-module-domain/commit/6540eb5
[f688f79]: https://github.com/hiqdev/hipanel-module-domain/commit/f688f79
[612619c]: https://github.com/hiqdev/hipanel-module-domain/commit/612619c
[5a0e728]: https://github.com/hiqdev/hipanel-module-domain/commit/5a0e728
[86d625e]: https://github.com/hiqdev/hipanel-module-domain/commit/86d625e
[be51c99]: https://github.com/hiqdev/hipanel-module-domain/commit/be51c99
[9e4399d]: https://github.com/hiqdev/hipanel-module-domain/commit/9e4399d
[2f6a540]: https://github.com/hiqdev/hipanel-module-domain/commit/2f6a540
[830212b]: https://github.com/hiqdev/hipanel-module-domain/commit/830212b
[c4f396a]: https://github.com/hiqdev/hipanel-module-domain/commit/c4f396a
[8d358ce]: https://github.com/hiqdev/hipanel-module-domain/commit/8d358ce
[b62c65f]: https://github.com/hiqdev/hipanel-module-domain/commit/b62c65f
[1d5c020]: https://github.com/hiqdev/hipanel-module-domain/commit/1d5c020
[cee17d2]: https://github.com/hiqdev/hipanel-module-domain/commit/cee17d2
[1f2ab59]: https://github.com/hiqdev/hipanel-module-domain/commit/1f2ab59
[08974e1]: https://github.com/hiqdev/hipanel-module-domain/commit/08974e1
[a949938]: https://github.com/hiqdev/hipanel-module-domain/commit/a949938
[728ce2f]: https://github.com/hiqdev/hipanel-module-domain/commit/728ce2f
[f5ebe6e]: https://github.com/hiqdev/hipanel-module-domain/commit/f5ebe6e
[ad81508]: https://github.com/hiqdev/hipanel-module-domain/commit/ad81508
[5ef0551]: https://github.com/hiqdev/hipanel-module-domain/commit/5ef0551
[94d9d61]: https://github.com/hiqdev/hipanel-module-domain/commit/94d9d61
[cf97829]: https://github.com/hiqdev/hipanel-module-domain/commit/cf97829
[0927e11]: https://github.com/hiqdev/hipanel-module-domain/commit/0927e11
[7a875bf]: https://github.com/hiqdev/hipanel-module-domain/commit/7a875bf
[0ec9858]: https://github.com/hiqdev/hipanel-module-domain/commit/0ec9858
[6327382]: https://github.com/hiqdev/hipanel-module-domain/commit/6327382
[efb6570]: https://github.com/hiqdev/hipanel-module-domain/commit/efb6570
[ee68a5d]: https://github.com/hiqdev/hipanel-module-domain/commit/ee68a5d
[d3cddb9]: https://github.com/hiqdev/hipanel-module-domain/commit/d3cddb9
[58c5c29]: https://github.com/hiqdev/hipanel-module-domain/commit/58c5c29
[505d3f8]: https://github.com/hiqdev/hipanel-module-domain/commit/505d3f8
[b0900c5]: https://github.com/hiqdev/hipanel-module-domain/commit/b0900c5
[e79f8de]: https://github.com/hiqdev/hipanel-module-domain/commit/e79f8de
[abcb5e5]: https://github.com/hiqdev/hipanel-module-domain/commit/abcb5e5
[db1a680]: https://github.com/hiqdev/hipanel-module-domain/commit/db1a680
[9747b5c]: https://github.com/hiqdev/hipanel-module-domain/commit/9747b5c
[adad572]: https://github.com/hiqdev/hipanel-module-domain/commit/adad572
[5c9846e]: https://github.com/hiqdev/hipanel-module-domain/commit/5c9846e
[95d9ff7]: https://github.com/hiqdev/hipanel-module-domain/commit/95d9ff7
[8cd44da]: https://github.com/hiqdev/hipanel-module-domain/commit/8cd44da
[875ed45]: https://github.com/hiqdev/hipanel-module-domain/commit/875ed45
[ae2ac69]: https://github.com/hiqdev/hipanel-module-domain/commit/ae2ac69
[9d41857]: https://github.com/hiqdev/hipanel-module-domain/commit/9d41857
[Under development]: https://github.com/hiqdev/hipanel-module-domain/releases
[Under]: https://github.com/hiqdev/hipanel-module-domain/releases/tag/Under
