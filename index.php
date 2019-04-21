<?php
/**
*  Enquiry Form Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
*/

if (!defined('e107_INIT')) {
    require_once ("../../class2.php");
}
e107::lan('enquiry',false,true);

require_once ('includes/enquiry_class.php');
$enquiryObj = new enquiry_class;
$enquiryObj->runPage();


require_once (HEADERF); // render the header (everything before the main content area)

e107::getRender()->tablerender('Enquiry', $enquiryObj->text);// render the main content area

require_once (FOOTERF); // render the footer (everything after the main content area)
