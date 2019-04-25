<?php

/**
 *  Plugin for the e107 Website System
 *
 * Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */
if (!defined('e107_INIT'))
{
    exit;
}


/**
 * plugin_enquiry_shortcodes_class
 * 
 * @package Enquiry
 * @author Father Barry
 * @copyright 2017
 * @version $Id$
 * @access public
 */
class plugin_enquiry_shortcodes_class extends e_shortcode
{
    public $override = false; // when set to true, existing core/plugin shortcodes matching methods below will be overridden.

    private $eform;
    public $prefs;
    public $use_imagecode;
    public $guest;
    /**
     * plugin_enquiry_shortcodes_class::__construct()
     * 
     * @return
     */
    function __construct()
    {

    }


    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_name()
     * 
     * @param mixed $parm
     * @return
     */
    function sc_enquiry_name($parm = null)
    {
        return '<input id="enquiry_name" name="enquiry_name" value="'.$this->post['enquiry_name'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_NAME . '" class="form-control input-md" required="">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_address1()
     * 
     * @return
     */
    function sc_enquiry_address1()
    {
        return '<input id="enquiry_address1" name="enquiry_address1" value="'.$this->post['enquiry_address1'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_ADDRESS1 . '" class="form-control input-md" required="">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_address2()
     * 
     * @return
     */
    function sc_enquiry_address2()
    {
        return '<input id="enquiry_address2" name="enquiry_address2" value="'.$this->post['enquiry_address2'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_ADDRESS2 . '" class="form-control input-md" >';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_town()
     * 
     * @return
     */
    function sc_enquiry_town()
    {
        return '<input id="enquiry_town" name="enquiry_town" value="'.$this->post['enquiry_town'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_TOWN . '" class="form-control input-md" required="">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_county()
     * 
     * @return
     */
    function sc_enquiry_county()
    {
        return ' <input id="enquiry_county" name="enquiry_county" value="'.$this->post['enquiry_county'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_COUNTY . '" class="form-control input-md">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_postcode()
     * 
     * @return
     */
    function sc_enquiry_postcode()
    {
        return '<input id="enquiry_postcode" name="enquiry_postcode" value="'.$this->post['enquiry_postcode'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_POST . '" class="form-control input-md" required="">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_phone()
     * 
     * @return
     */
    function sc_enquiry_phone()
    {
        return '<input id="enquiry_phone" name="enquiry_phone" value="'.$this->post['enquiry_phone'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_PHONE . '" class="form-control input-md">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_email()
     * 
     * @return
     */
    function sc_enquiry_email()
    {
        return '<input id="enquiry_email" name="enquiry_email" value="'.$this->post['enquiry_email'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_EMAIL . '" class="form-control input-md" required="">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_emailcheck()
     * 
     * @return
     */
    function sc_enquiry_emailcheck()
    {
        return '<input id="enquiry_emailcheck" name="enquiry_emailcheck" value="'.$this->post['enquiry_emailcheck'].'" type="text" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_CONFIRM . '" class="form-control input-md">';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_otherinfo()
     * 
     * @return
     */
    function sc_enquiry_otherinfo()
    {
        return '<textarea class="form-control" id="enquiry_otherinfo" name="enquiry_otherinfo" placeholder="' . LAN_PLUGIN_ENQUIRY_FRONT_PH_ENQUIRE . '" required="">'.$this->post['enquiry_otherinfo'].'</textarea>';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_gender2()
     * 
     * @return
     */
    function sc_enquiry_gender0()
    {
        return '<label for="enquiry_gender-2"><input type="radio" name="enquiry_gender" id="enquiry_gender-2" value="0" checked="checked">' . LAN_PLUGIN_ENQUIRY_FRONT_NOSAY . '</label>';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_gender0()
     * 
     * @return
     */
    function sc_enquiry_gender2()
    {
        return '<label for="enquiry_gender-0"><input type="radio" name="enquiry_gender" id="enquiry_gender-0" value="1" >' . LAN_PLUGIN_ENQUIRY_FRONT_FEMALE . '</label>';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_gender1()
     * 
     * @return
     */
    function sc_enquiry_gender1()
    {
        return '<label for="enquiry_gender-1"><input type="radio" name="enquiry_gender" id="enquiry_gender-1" value="2">' . LAN_PLUGIN_ENQUIRY_FRONT_MALE . '</label>';
    }

    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_contact()
     * 
     * @return
     */
    function sc_enquiry_contact()
    {
        return $this->prefs['pref_conphone'];
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_emailto()
     * 
     * @return
     */
    function sc_enquiry_emailto()
    {
        return mailMunger::munge($this->prefs['pref_emailaddress']);
    }
    function sc_enquiry_category()
    {
        $sql = new DB;
        $qry = "SELECT * FROM #enquiry_categories ORDER BY enquiry_category_name";
        $field = "<select id='enquiry_category' name='enquiry_category' class='' >";
        $sql->gen($qry, false);
        while ($row = $sql->fetch())
        {
            $field .= "<option value='" . $row['enquiry_category_id'] . "'>" . $row['enquiry_category_name'] . "</option>";
        }
        $field .= "</select>";
        return $field;

    }
    function sc_enquiry_less18()
    {
        return '<label class="radio-inline" for="enquiry_agerange-0">
      <input type="radio" name="enquiry_agerange" id="enquiry_agerange-0" value="1" >
      ' . LAN_PLUGIN_ENQUIRY_FRONT_LESS_18 . '
    </label> ';
    }
    function sc_enquiry_age18()
    {
        return '<label class="radio-inline" for="enquiry_agerange-0">
      <input type="radio" name="enquiry_agerange" id="enquiry_agerange-0" value="2" >
      ' . LAN_PLUGIN_ENQUIRY_FRONT_18 . '
    </label> ';
    }
    function sc_enquiry_age30()
    {
        return '<label class="radio-inline" for="enquiry_agerange-1">
      <input type="radio" name="enquiry_agerange" id="enquiry_agerange-1" value="3">
    ' . LAN_PLUGIN_ENQUIRY_FRONT_30 . '
    </label> ';
    }
    function sc_enquiry_age50()
    {
        return '<label class="radio-inline" for="enquiry_agerange-2">
      <input type="radio" name="enquiry_agerange" id="enquiry_agerange-2" value="4">
    ' . LAN_PLUGIN_ENQUIRY_FRONT_50 . '
    </label> ';
    }
    function sc_enquiry_age65()
    {
        return '<label class="radio-inline" for="enquiry_agerange-3">
      <input type="radio" name="enquiry_agerange" id="enquiry_agerange-3" value="5">
    ' . LAN_PLUGIN_ENQUIRY_FRONT_65 . '
    </label> ';
    }
    function sc_enquiry_agepns()
    {
        return '<label class="radio-inline" for="enquiry_agerange-4">
      <input type="radio" name="enquiry_agerange" id="enquiry_agerange-4" value="0" checked="checked">
    ' . LAN_PLUGIN_ENQUIRY_FRONT_NOSAY . '
    </label> ';
    }
    function sc_enquiry_submit()
    {
        return '<button id="submit" name="submit" class="btn btn-success">' . LAN_PLUGIN_ENQUIRY_FRONT_SUBMIT . '</button>';
    }
    function sc_enquiry_cancel()
    {
        return '<button id="cancel" name="cancel" class="btn btn-default" formnovalidate="formnovalidate">' . LAN_PLUGIN_ENQUIRY_FRONT_CANC . '</button>';
    }
    /**
     * plugin_enquiry_shortcodes_class::sc_enquiry_form()
     * 
     * @return
     */
    function sc_enquiry_form()
    {
        return '<a href="' . e_PLUGIN . 'enquiry/index.php?action=form" class="btn btn-info" role="button">Enquiry Form</a>';
    }
    function sc_enquiry_home()
    {
        return '<a href="index.php" class="btn btn-info" role="button">' . LAN_PLUGIN_ENQUIRY_FRONT_PH_HOME . '</a>';
    }
    function sc_enquiry_imagecode_number()
    {
        if ($this->use_imagecode )
        {
            return e107::getSecureImg()->renderImage();
        } else
        {
            return '';
        }
    }
    function sc_enquiry_imagecode_box($parm = '')
    {
        //var_dump($this->use_imagecode );
        if ($this->use_imagecode )
        {
            return e107::getSecureImg()->renderInput();
        } else
        {
            return '';
        }
    }
}
