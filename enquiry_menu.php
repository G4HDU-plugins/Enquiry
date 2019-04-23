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
$enquiry_prefs = e107::pref('enquiry');
$class = $enquiry_prefs['pref_userclass']; // get the class to check
if (!e107::getUser()->checkClass($class, false))
{
    // not permitted to view
} else
{

    e107::lan('enquiry', 'menu', true); // English_menu.php or {LANGUAGE}_menu.php
    e107::css('url', e_PLUGIN . 'enquiry/css/enquiry.css'); // load css file
    global $e107cache;

    // get the cached version if available
    $text = $e107cache->retrieve("nomd5_enquiry");
    if (!$text)
    {
        // No cache content so select all unresponded enquiries
        $sql = e107::getDB(); // mysql class object
        $tp = e107::getParser(); // parser for converting to HTML and parsing templates etc.
        $td = new convert(); // date convertor


        $qry = 'SELECT * FROM #enquiry_forms where enquiry_closedon = 0 ORDER BY enquiry_dateposted ASC LIMIT 0,' . $enquiry_prefs['pref_max_menu'];
        if ($sql->gen($qry, false))
        {
            $today = time();
            $oneDay = 86400;
            $green = (3 * $oneDay);
            $amber = (9 * $oneDay);
            $red = (12 * $oneDay);
            $text = '
    <table>';
            while ($row = $sql->fetch())
            {
                // step through each found record and check days since posted
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

                // $record = $tp->html_truncate($tp->toHTML($row['enquiry_name']), 25);
                $date = $td->convert_date($row['enquiry_dateposted'], '%e %b %Y');
                $text .= '<tr><td class="enquiryMenuName">' . $circle . ' ' . $record . '</td>';
                $text .= '<td class="enquiryMenuDate">' . $date . '</td></tr>';
            }
            $text .= '</table>
    <div class="enquiryAdmin"><a href="' . e_PLUGIN . 'enquiry/admin_config.php" class="btn btn-info" role="button">' . LAN_PLUGIN_ENQUIRY_MENU_ADMIN . '</a></div>';

        } else
        {
            // no outstanding enquiries
            $text = LAN_PLUGIN_ENQUIRY_MENU_NONE;
        }
        // eitherway set the cache
        $e107cache->set("nomd5_enquiry", $text);
    }

    e107::getRender()->tablerender(LAN_PLUGIN_ENQUIRY_MENU_CAPTION, $text, 'enquirylist');

}
