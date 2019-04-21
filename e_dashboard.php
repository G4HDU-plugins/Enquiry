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
e107::lan('enquiry', true, true);

class enquiry_dashboard // include plugin-folder in the name.
{
   	function chart()
	{
		return false;
	}
	
	
	function status()
	{
		$sql = e107::getDb();
		$enquiry_posts = $sql->count('enquiry_forms');
		
		$var[0]['icon'] 	= "<img src='".e_PLUGIN."enquiry/images/enquiry_16.png' />";
		$var[0]['title'] 	= LAN_PLUGIN_ENQUIRY_ADMIN_STATUS;
		$var[0]['url']		= e_PLUGIN."enquiry/admin_config.php";
		$var[0]['total'] 	= $enquiry_posts;

		$unanswered = $sql->count('enquiry_forms', '(*)', "WHERE enquiry_respondedon=0 ");
		
		$var[1]['icon'] 	= "<img src='".e_PLUGIN."enquiry/images/enquiry_16.png' />";
		
		$var[1]['title'] 	= LAN_PLUGIN_ENQUIRY_ADMIN_UNANSWERED;
		$var[1]['url']		= e_PLUGIN."enquiry/admin_config.php";
		$var[1]['total'] 	= $unanswered;
        
		return $var;
	}	
	
	
	function latest()
	{
		$sql = e107::getDb();
		$unanswered = $sql->count('enquiry_forms', '(*)', "WHERE enquiry_respondedon=0 ");
		
		$var[0]['icon'] 	= E_16_FORUM;
		$var[0]['title'] 	= LAN_PLUGIN_ENQUIRY_ADMIN_UNANSWERED;
		$var[0]['url']		= e_PLUGIN."enquiry/admin_config.php";
		$var[0]['total'] 	= $unanswered;
return;
		return $var;
	}
}
