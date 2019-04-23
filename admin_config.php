<?php

/**
 * Enquiry Plugin for the e107 Website System
 *
 * Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

require_once ("../../class2.php");
if (!getperms("P"))
{
    e107::redirect('admin');
    exit;
}

e107::css('url', e_PLUGIN . 'enquiry/css/enquiry.css'); // load css file
e107::lan('enquiry', true, true); // load language file ie. e107_plugins/addressbook/languages/English.php
e107::lan('enquiry', 'help', true); // e_PLUGIN.'enquiry/languages/'.e_LANGUAGE.'/help.php'

class plugin_enquiry_admin extends e_admin_dispatcher
{
    /**
     * Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
     * Note - default mode/action is autodetected in this order:
     * - $defaultMode/$defaultAction (owned by dispatcher - see below)
     * - $adminMenu (first key if admin menu array is not empty)
     * - $modes (first key == mode, corresponding 'index' key == action)
     * @var array
     */
    protected $modes = array('main' => array(
            'controller' => 'plugin_enquiry_admin_ui',
            'path' => null,
            'ui' => 'plugin_enquiry_admin_form_ui',
            'uipath' => null), 'category' => array(
            'controller' => 'plugin_enquiry_cat_admin_ui',
            'path' => null,
            'ui' => 'plugin_enquiry_admin_cat_form_ui',
            'uipath' => null));

    /* Both are optional
    * protected $defaultMode = null;
    * protected $defaultAction = null;
    */

    /**
     * Format: 'MODE/ACTION' => array('caption' => 'Menu link title'[, 'url' => '{e_PLUGIN}enquiry/admin_config.php', 'perm' => '0']);
     * Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
     * @var array
     */
    protected $adminMenu = array(
        'main/list' => array('caption' => LAN_PLUGIN_ENQUIRY_ADMIN_MENUCAPTION, 'perm' => '0'),
        'main/create' => array('caption' => LAN_CREATE, 'perm' => '0'),

        'other0' => array('divider' => true),
        'category/list' => array('caption' => LAN_PLUGIN_ENQUIRY_ADMIN_CATEGORIES, 'perm' => '0'),
        'category/create' => array('caption' => LAN_CREATE, 'perm' => '0'),
        'other1' => array('divider' => true),
        'main/prefs' => array('caption' => LAN_PLUGIN_ENQUIRY_ADMIN_MENUSETTINGS, 'perm' => '0'));

    /**
     * Optional, mode/action aliases, related with 'selected' menu CSS class
     * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
     * This will mark active main/list menu item, when current page is main/edit
     * @var array
     */
    protected $adminMenuAliases = array('main/edit' => 'main/list', 'category/edit' => 'category/list');

    /**
     * Navigation menu title
     * @var string
     */
    protected $menuTitle = LAN_PLUGIN_ENQUIRY_ADMIN_MENUSTITLE; //'Enquiry Menu';
}


class plugin_enquiry_admin_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = LAN_PLUGIN_ENQUIRY_ADMIN_PLUGTITLE; //"e107 enquiry";

    /**
     * plugin name or 'core'
     * IMPORTANT: should be 'core' for non-plugin areas because this
     * value defines what CONFIG will be used. However, I think this should be changed
     * very soon (awaiting discussion with Cam)
     * Maybe we need something like $prefs['core'], $prefs['enquiry'] ... multiple getConfig support?
     *
     * @var string
     */
    protected $pluginName = 'enquiry';

    /**
     * DB Table, table alias is supported
     * Example: 'r.enquiry'
     * @var string
     */
    protected $table = "enquiry_forms";

    /**
     * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
     * Write your list query without any Order or Limit.
     *
     * @var string [optional]
     */
    protected $listQry = "";
    //

    // optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
    //protected $editQry = "SELECT * FROM #enquiry WHERE enquiry_id = {ID}";

    // required - if no custom model is set in init() (primary id)
    protected $pid = "enquiry_id";

    // optional
    protected $perPage = 20;

    protected $batchDelete = true;

    //	protected \$sortField		= 'somefield_order';


    //	protected \$sortParent      = 'somefield_parent';


    //	protected \$treePrefix      = 'somefield_title';

    protected $preftabs = array(
        LAN_PLUGIN_ENQUIRY_ADMIN_TAB0,
        LAN_PLUGIN_ENQUIRY_ADMIN_TAB1,
        LAN_PLUGIN_ENQUIRY_ADMIN_TAB2,
        LAN_PLUGIN_ENQUIRY_ADMIN_TAB3);
    //TODO change the enquiry_url type back to URL before enquiry.
    // required
    /**
     * (use this as starting point for wiki documentation)
     * $fields format  (string) $field_name => (array) $attributes
     *
     * $field_name format:
     * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
     * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
     * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
     *
     * $attributes format:
     * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
     *
     *  - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
     *    boolean, method, ip
     *  	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
     *  	for list of possible read/writeParms per type see below
     *
     *  - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
     *    Default is 'str'
     *    Used only if $dataFields is not set
     *  	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
     *  - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
     *  - primary (boolean) primary field (obsolete, $pid is now used)
     *
     *  - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
     *  - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
     *
     *  - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
     *  - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
     *  - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
     *
     *  - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
     *    NOTE: batch may accept string values in the future...
     *  	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
     *
     *  - filter (boolean) list table - add current field to filter actions, rest is same as batch
     *
     *  - forced (boolean) list table - forced fields are always shown in list table
     *  - nolist (boolean) list table - don't show in column choice list
     *  - noedit (boolean) edit table - don't show in edit mode
     *
     *  - width (string) list table - width e.g '10%', 'auto'
     *  - thclass (string) list table header - th element class
     *  - class (string) list table body - td element additional class
     *
     *  - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
     *    depends on the current field type (see below). readParams are used mainly by list page
     *
     *  - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
     *    Structure on this attribute depends on the current field type (see below).
     *    writeParams are used mainly by edit page, filter (list page), batch (list page)
     *
     * $attributes['type']->$attributes['read/writeParams'] pairs:
     *
     * - null -> read: n/a
     * 		  -> write: n/a
     *
     * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
     * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
     *
     * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
     * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
     * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
     *
     * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
     * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
     *
     * - ip		-> read: n/a
     * 			-> write: [optional] element options array (see e_form class description for __options format)
     *
     * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
     * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
     *
     * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
     * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
     * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
     * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
     * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
     *
     * - bbarea -> read: same as textarea type
     * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
     * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
     * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
     *
     * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
     * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
     * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
     * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
     *
     * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
     * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
     *
     * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
     * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
     *
     * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
     * 			-> write:
     *
     * - method -> read: optional, passed to given method (the field name)
     * 			-> write: optional, passed to given method (the field name)
     *
     * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
     * 			-> write: same as readParms
     *
     * - upload -> read: n/a
     * 			-> write: Under construction
     *
     * Special attribute types:
     * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
     * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
     * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
     * 		Return type expected (by render action):
     * 			- read: list table - formatted value only
     * 			- write: edit table - form element (control)
     * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
     * 			- filter: same as batch
     * @var array
     */
    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'tab' => '0',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),
        'enquiry_id' => array(
            'title' => LAN_ID,
            'type' => 'dropdown',
            'data' => 'int',
            'width' => '5%',
            'thclass' => '',
            'class' => 'center',
            'forced' => true,
            'primary' => true,
            ),
        'enquiry_title' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_TITLE,
            'type' => 'dropdown',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => '',
            'forced' => true,
            'primary' => true,
            'writeParms' => array(
                '1' => 'Mr',
                '2' => 'Mrs',
                '3' => 'Miss',
                '4' => 'Ms',
                '5' => 'Mx')),
        'enquiry_name' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_NAME,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => '',
            'batch' => true,
            'filter' => true),
        'enquiry_address1' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_ADDRESS1,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_address2' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_ADDRESS2,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_town' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_TOWN,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_county' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_COUNTY,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_postcode' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_COUNTY,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_phone' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_POSTCODE,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_email' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_EMAIL,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => ''),
        'enquiry_agerange' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_AGE,
            'type' => 'dropdown',
            'data' => 'int',
            'width' => 'auto',
            'thclass' => '',
            'writeParms' => array(
                '0' => 'Prefer not to say',
                '1' => '< 18',
                '2' => '18-29',
                '3' => '30-49',
                '4' => '50-65',
                '5' => '65+')),
        'enquiry_gender' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_GENDER,
            'type' => 'dropdown',
            'data' => 'int',
            'width' => 'auto',
            'thclass' => '',
            'writeParms' => array(
                '0' => LAN_PLUGIN_ENQUIRY_ADMIN_NOSAY,
                '1' => LAN_PLUGIN_ENQUIRY_ADMIN_MALE,
                '2' => LAN_PLUGIN_ENQUIRY_ADMIN_FEMALE)),
        'enquiry_category' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_CAT,
            'type' => 'method',
            'data' => 'int',
            'width' => 'auto',
            'thclass' => '',
            ),
        'enquiry_otherinfo' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_NOTES,
            'type' => 'textarea',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => 'left'),
        'enquiry_dateposted' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_POSTED,
            'type' => 'datestamp',
            'data' => 'int',
            'width' => 'auto',
            'thclass' => 'left'),
        'enquiry_responder' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_RESPONDER,
            'type' => 'user',
            'data' => 'int',
            'width' => 'auto',
            'thclass' => '',
            'readParms' => 'long',
            'writeParms' => 'type=datetime'),
        'enquiry_closedon' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_RESPONDON,
            'type' => 'datestamp',
            'data' => 'int',
            'width' => '10%',
            'thclass' => 'center'),
        'enquiry_outcome' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_OUTCOME,
            'type' => 'textarea',
            'data' => 'str',
            'batch' => true,
            'filter' => true,
            'noedit' => false),
        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    //required - default column user prefs
    protected $fieldpref = array(
        'checkboxes',
        'enquiry_name',
        'enquiry_phone',
        'enquiry_name',
        'enquiry_email',
        'options');

    protected $prefs = array(
        'pref_conname' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_CONNAME,
            'type' => 'text',
            'data' => 'str',
            'tab' => 0,
            'validate' => false),
        'pref_conphone' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_CONPHONE,
            'type' => 'text',
            'data' => 'str',
            'tab' => 0),
        'pref_emailname' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_CONENAME,
            'type' => 'text',
            'data' => 'str',
            'tab' => 0),
        'pref_emailaddress' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_CONEMAIL,
            'type' => 'text',
            'data' => 'str',
            'tab' => 0),
        'pref_max_menu' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_MAXMENU,
            'type' => 'text',
            'data' => 'int',
            'tab' => 0),
        'pref_userclass' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_MENUVISIBILIIY,
            'type' => 'userclass',
            'data' => 'int',
            'tab' => 0),
        'pref_use_captcha' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_USECAPTURE,
            'type' => 'boolean',
            'data' => 'int',
            'tab' => 0),
        'pref_welcome_use' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_USEWELCOME,
            'type' => 'boolean',
            'data' => 'int',
            'tab' => 1),
        'pref_welcome' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_WELCOMETEXT,
            'type' => 'bbarea',
            'data' => 'str',
            'write' => array('rows' => '12'),
            'tab' => 1),
        'pref_success_use' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_USESUCCESS,
            'type' => 'boolean',
            'data' => 'int',
            'tab' => 2),
        'pref_success' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_SUCCESSTEXT,
            'type' => 'bbarea',
            'write' => array('rows' => '12'),
            'data' => 'str',
            'tab' => 2),
        'pref_error_use' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_USEERROR,
            'type' => 'boolean',
            'data' => 'int',
            'tab' => 3),
        'pref_error' => array(
            'title' => LAN_PLUGIN_ENQUIRY_ADMIN_ERRORTEXT,
            'type' => 'bbarea',
            'write' => array('rows' => '12'),
            'data' => 'str',
            'tab' => 3),
        );
    public function afterCreate($new_data, $old_data, $id)
    {
        global $e107cache;

        $e107cache->clear('nomd5_enquiry', false, true);

        return;
    }
    public function afterUpdate($new_data, $old_data, $id)
    {
        global $e107cache;

        $e107cache->clear('nomd5_enquiry', false, true);

        return;
    }
    public function afterDelete($new_data, $old_data, $id)
    {
        global $e107cache;

        $e107cache->clear('nomd5_enquiry', false, true);

        return;
    }
}

class plugin_enquiry_admin_form_ui extends e_admin_form_ui
{
    function enquiry_category($curVal, $mode) // not really necessary since we can use 'dropdown' - but just an example of a custom function.
    {
        $frm = e107::getForm();
        $sql = e107::getDB();
        $qry = "SELECT enquiry_category_id,enquiry_category_name from #enquiry_categories order by enquiry_category_name";
        $sql->gen($qry, false);
        while ($row = $sql->fetch())
        {
            $category[$row['enquiry_category_id']] = $row['enquiry_category_name'];
        }

        //  $types = array('type_1' => "Type 1", 'type_2' => 'Type 2');

        if ($mode == 'read')
        {
            return vartrue($category[$curVal]) . ' (custom!)';
        }

        if ($mode == 'batch') // Custom Batch List for qrz_type
        {
            return $category;
        }

        if ($mode == 'filter') // Custom Filter List for qrz_type
        {
            return $category;
        }

        return $frm->select('enquiry_category', $category, $curVal);
    }


}

class plugin_enquiry_cat_admin_ui extends e_admin_ui
{
    // required
    protected $pluginTitle = LAN_PLUGIN_ENQUIRY_ADMIN_PLUGTITLE; //"e107 enquiry";

    /**
     * plugin name or 'core'
     * IMPORTANT: should be 'core' for non-plugin areas because this
     * value defines what CONFIG will be used. However, I think this should be changed
     * very soon (awaiting discussion with Cam)
     * Maybe we need something like $prefs['core'], $prefs['enquiry'] ... multiple getConfig support?
     *
     * @var string
     */
    protected $pluginName = 'enquiry';

    /**
     * DB Table, table alias is supported
     * Example: 'r.enquiry'
     * @var string
     */
    protected $table = "enquiry_categories";

    /**
     * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
     * Write your list query without any Order or Limit.
     *
     * @var string [optional]
     */
    protected $listQry = "";
    //

    // optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
    //protected $editQry = "SELECT * FROM #enquiry WHERE enquiry_id = {ID}";

    // required - if no custom model is set in init() (primary id)
    protected $pid = "enquiry_category_id";

    // optional
    protected $perPage = 20;

    protected $batchDelete = true;

    //	protected \$sortField		= 'somefield_order';


    //	protected \$sortParent      = 'somefield_parent';


    //	protected \$treePrefix      = 'somefield_title';

    protected $preftabs = '';
    //TODO change the enquiry_url type back to URL before enquiry.
    // required
    /**
     * (use this as starting point for wiki documentation)
     * $fields format  (string) $field_name => (array) $attributes
     *
     * $field_name format:
     * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
     * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
     * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
     *
     * $attributes format:
     * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
     *
     *  - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
     *    boolean, method, ip
     *  	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
     *  	for list of possible read/writeParms per type see below
     *
     *  - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
     *    Default is 'str'
     *    Used only if $dataFields is not set
     *  	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
     *  - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
     *  - primary (boolean) primary field (obsolete, $pid is now used)
     *
     *  - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
     *  - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
     *
     *  - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
     *  - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
     *  - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
     *
     *  - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
     *    NOTE: batch may accept string values in the future...
     *  	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
     *
     *  - filter (boolean) list table - add current field to filter actions, rest is same as batch
     *
     *  - forced (boolean) list table - forced fields are always shown in list table
     *  - nolist (boolean) list table - don't show in column choice list
     *  - noedit (boolean) edit table - don't show in edit mode
     *
     *  - width (string) list table - width e.g '10%', 'auto'
     *  - thclass (string) list table header - th element class
     *  - class (string) list table body - td element additional class
     *
     *  - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
     *    depends on the current field type (see below). readParams are used mainly by list page
     *
     *  - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
     *    Structure on this attribute depends on the current field type (see below).
     *    writeParams are used mainly by edit page, filter (list page), batch (list page)
     *
     * $attributes['type']->$attributes['read/writeParams'] pairs:
     *
     * - null -> read: n/a
     * 		  -> write: n/a
     *
     * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
     * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
     *
     * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
     * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
     * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
     *
     * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
     * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
     *
     * - ip		-> read: n/a
     * 			-> write: [optional] element options array (see e_form class description for __options format)
     *
     * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
     * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
     *
     * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
     * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
     * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
     * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
     * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
     *
     * - bbarea -> read: same as textarea type
     * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
     * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
     * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
     *
     * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
     * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
     * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
     * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
     *
     * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
     * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
     *
     * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
     * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
     *
     * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
     * 			-> write:
     *
     * - method -> read: optional, passed to given method (the field name)
     * 			-> write: optional, passed to given method (the field name)
     *
     * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
     * 			-> write: same as readParms
     *
     * - upload -> read: n/a
     * 			-> write: Under construction
     *
     * Special attribute types:
     * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
     * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
     * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
     * 		Return type expected (by render action):
     * 			- read: list table - formatted value only
     * 			- write: edit table - form element (control)
     * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
     * 			- filter: same as batch
     * @var array
     */
    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'tab' => '0',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),
        'enquiry_category_id' => array(
            'title' => LAN_ID,
            'type' => 'dropdown',
            'data' => 'int',
            'width' => '5%',
            'thclass' => 'right',
            'class' => 'center',
            'forced' => true,
            'primary' => true,
            ),
        'enquiry_category_name' => array(
            'title' => LAN_PLUGIN_ENQUIRY_CAT_ADMIN_NAME,
            'type' => 'text',
            'data' => 'str',
            'width' => '20%',
            'thclass' => '',
            'forced' => true,
            'primary' => false,
            ),
        'enquiry_category_details' => array(
            'title' => LAN_PLUGIN_ENQUIRY_CAT_ADMIN_DESC,
            'type' => 'text',
            'data' => 'str',
            'width' => 'auto',
            'thclass' => '',
            'forced' => true,
            'primary' => false),

        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true));

    //required - default column user prefs
    protected $fieldpref = array(
        'checkboxes',
        'enquiry_category_id',
        'enquiry_category_name',
        'enquiry_category_details',
        'enquiry_category_lastupdate',
        'options');
    //
    public function afterCreate($new_data, $old_data, $id)
    {
        global $e107cache;

        $e107cache->clear('nomd5_enquiry', false, true);

        return;
    }
    public function afterUpdate($new_data, $old_data, $id)
    {
        global $e107cache;

        $e107cache->clear('nomd5_enquiry', false, true);

        return;
    }
    public function afterDelete($new_data, $old_data, $id)
    {
        global $e107cache;

        $e107cache->clear('nomd5_enquiry', false, true);

        return;
    }
}

class plugin_enquiry_cat_admin_form_ui extends e_admin_form_ui
{


}

new plugin_enquiry_admin();


require_once (e_ADMIN . "auth.php");

/*
* Send page content
*/
e107::getAdminUI()->runPage();

require_once (e_ADMIN . "footer.php");
