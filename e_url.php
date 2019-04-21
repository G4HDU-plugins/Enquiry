<?php

/**
* Enquiry Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*

* IMPORTANT: Make sure the redirect script uses the following code to load class2.php: 
* 
* 	if (!defined('e107_INIT'))
* 	{
* 		require_once("../../class2.php");
* 	}
* 
*/

if (!defined('e107_INIT'))
{
    require_once("../../class2.php");
}

// v2.x Standard  - Simple mod-rewrite module.

class enquiry_url // plugin-folder + '_url'
{
    function config()
    {
        $config = array();

        $config['other'] = array(
            'alias' => 'enquiry', // default alias '_enquiry'. {alias} is substituted with this value below. Allows for customization within the admin area.
            'regex' => '^{alias}/?$', // matched against url, and if true, redirected to 'redirect' below.
            'sef' => '{alias}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}enquiry/index.php', // file-path of what to load when the regex returns true.
            );


        $config['index'] = array(
            'alias' => 'enquiry',
            'regex' => '^enquiry/?$', // matched against url, and if true, redirected to 'redirect' below.
            'sef' => '{alias}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}enquiry/index.php', // file-path of what to load when the regex returns true.
            );

        return $config;
    }


}
