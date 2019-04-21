<?php
/**
* Enquiry Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
*/
// Always use the format LAN_PLUGIN_{FOLDER}_{TYPE} to prevent conflicts. In this case "_ENQUIRY" is the folder. 
// This should contain the LANs used in the plugin.xml file. 
define("LAN_PLUGIN_ENQUIRY_LINK", "Enquire");
define("LAN_PLUGIN_ENQUIRY_NAME", "Enquiries");
define("LAN_PLUGIN__ENQUIRY_DESC",  "An enquiries form and response management plugin."); 

define("LAN_PLUGIN_ENQUIRY_MAIL_NEW",  "New"); 
define("LAN_PLUGIN_ENQUIRY_MAIL_NAME",  "enquiry from"); 
define("LAN_PLUGIN_ENQUIRY_MAIL_ADDR",  "Address"); 
define("LAN_PLUGIN_ENQUIRY_MAIL_TELEPHONE",  "Telephone"); 
define("LAN_PLUGIN_ENQUIRY_MAIL_EMAIL",  "Email"); 
define("LAN_PLUGIN_ENQUIRY_MAIL_CATEGORY",  "Enquiry Category"); 
define("LAN_PLUGIN_ENQUIRY_MAIL_INFO",  "Enquiry"); 
 
 

define("LAN_PLUGIN_ENQUIRY_WELCOME",  '
        <p class="successText" >If you would like more information on how to join the Maghull and District Lions Club or want some assistance and think we may be able to help you or your organization then please contact us.</p>
        <p class="successText" >You can phone one of our club\'s officers on {ENQUIRY_CONTACT} or you can email one of our officers at {ENQUIRY_EMAILTO} Alternatively fill in the enquiry form by clicking on the button below.</p>
        <br><p class="successText" ><a href="' . e_PLUGIN .
                'enquiry/index.php?action=form" class="btn btn-info" role="button">Enquiry Form</a></p>.'); 

define("LAN_PLUGIN_ENQUIRY_SUCCESS",  '
    <p class="successHeading">Congratulations</p>
    <p class="successText" >Your enquiry has been received and passed on to the appropriate officers at Maghull and District Lions Club. You should hear back within the next 24 hours but if you don\'t then please call {ENQUIRY_CONTACT} or email {ENQUIRY_EMAILTO}</p>
    <p class="successItalic" >Thank you for your interest in our Lions Club.</p>
    <br><p class="successText" ><a href="index.php" class="btn btn-info" role="button">Return Home</a></p>');

define("LAN_PLUGIN_ENQUIRY_ERROR",  '
    <p class="errorHeading">Apologies</p>
    <p class="errorText" >Unfortunately we\'ve had a problem and been unable to record your enquiry so please call {ENQUIRY_CONTACT} or email {ENQUIRY_EMAILTO}</p>
    <p class="errorItalic" >Thank you for your interest in our Lions Club.</p>
    <br><p class="successText" ><a href="index.php" class="btn btn-info" role="button">Return Home</a></p>');
?>