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
e107::lan('enquiry', 'menu', true); // English_menu.php or {LANGUAGE}_menu.php
e107::css('url', e_PLUGIN . 'enquiry/css/enquiry.css'); // load css file
global $e107cache;

if (!empty($parm))
{
   // $text .= print_a($parm, true); // e_menu.php form data.
}
$text=$e107cache->retrieve("nomd5_enquiry");
//var_dump($text);
if (!$text)
{
    
    $sql = e107::getDB(); // mysql class object
    $tp = e107::getParser(); // parser for converting to HTML and parsing templates etc.
    // $frm = e107::getForm(); 				// Form element class.
    // $ns = e107::getRender();				// render in theme box.
    $td = new convert();
    $enquiry_prefs = e107::pref('enquiry');
    //print_a($enquiry_prefs);
    $qry = 'SELECT * FROM #enquiry_forms where enquiry_closedon = 0 ORDER BY enquiry_dateposted ASC LIMIT 0,' . $enquiry_prefs['pref_max_menu'];
    if ($sql->gen($qry, false))
    {
        $today = time();
        $oneDay = 86400;
        $green = (3 * $oneDay);
        $amber = (9 * $oneDay);
        $red = (12 * $oneDay);
        //   print ($today - $red)."<br>";
        //    print $today - $amber."<br>";
        //   print $today - $green."<br>";
        //  print $today."<br>";
        //  print $td->convert_date($today, '%e %b %Y')."<br>";
        $text = '
    <table>';
        while ($row = $sql->fetch())
        {
            // print $today - $row['enquiry_dateposted']."<br>";
            if ($today - $row['enquiry_dateposted'] >= $red)
            {
                $circle = '<i class="fa fa-warning faa-flash animated enquiryRed" aria-hidden="true"></i>';
            } elseif ($today - $row['enquiry_dateposted'] >= $amber)
            {
                $circle = '<i class="fa fa-circle enquiryAmber" aria-hidden="true"></i>';
            } else
            {
                $circle = '<i class="fa fa-circle enquiryGreen" aria-hidden="true"></i>';
            }

            $record = $tp->html_truncate($tp->toHTML($row['enquiry_name']), 25);
            $date = $td->convert_date($row['enquiry_dateposted'], '%e %b %Y');
            $text .= '<tr><td class="enquiryMenuName">' . $circle . ' ' . $record . '</td>';
            $text .= '<td class="enquiryMenuDate">' . $date . '</td></tr>';
        }
        $text .= '</table>
    <div class="enquiryAdmin"><a href="' . e_PLUGIN . 'enquiry/admin_config.php" class="btn btn-info" role="button">' . LAN_PLUGIN_ENQUIRY_MENU_ADMIN . '</a></div>';
        //
   //     var_dump( $text);
        
    }else{
        $text=LAN_PLUGIN_ENQUIRY_MENU_NONE;
        }
    $e107cache->set("nomd5_enquiry", $text);
}

e107::getRender()->tablerender(LAN_PLUGIN_ENQUIRY_MENU_CAPTION, $text, 'enquirylist');
