<?php

/**
 * Enquiry Plugin for the e107 Website System
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

//e107::lan('enquiry','notify',true);

// v2.x Standard
class enquiry_notify extends notify
{
    function config()
    {

        $config = array();

        $config[] = array(
            'name' => "New Enquiry",
            'function' => "enquiryNotify",
            'category' => '');

        return $config;
    }

    function enquiryNotify($data)
    {

        $subject = LAN_PLUGIN_ENQUIRY_MAIL;
        //	$message = print_a($data,true);
        $message = "<br />
    " . LAN_PLUGIN_ENQUIRY_MAIL_NEW . " " . $data['enquiry_category_name'] . "  " . LAN_PLUGIN_ENQUIRY_MAIL_NAME . " : " . $data['enquiry_name'] . "<br />
    " . LAN_PLUGIN_ENQUIRY_MAIL_ADDR . " : " . $data['enquiry_address1'] . ", " . $data['enquiry_address2'] . ", " . $data['enquiry_town'] . ", " . $data['enquiry_county'] . $data['enquiry_postcode'] .
            ".<br />";
        $message .= LAN_PLUGIN_ENQUIRY_MAIL_TELEPHONE . " : " . $data['enquiry_phone'] . ", " . LAN_PLUGIN_ENQUIRY_MAIL_EMAIL . " : " . $data['enquiry_email'] . " <br />
    " . LAN_PLUGIN_ENQUIRY_MAIL_CATEGORY . " : " . $data['enquiry_category_name'] . "<br />" . LAN_PLUGIN_ENQUIRY_MAIL_INFO . " : <br />*******<br />" . $data['enquiry_otherinfo'] . "<br />";

        $this->send('enquiryNotify', $subject, $message);

    }


}
