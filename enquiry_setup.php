<?php
/**
* Enquiry Plugin for the e107 Website System
*
* Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
*/

if (!class_exists("enquiry_setup")) {
    class enquiry_setup
    {

        function install_pre($var)
        {
            // print_a($var);
            // echo "custom install 'pre' function<br /><br />";
        }

        /**
         * For inserting default database content during install after table has been created by the enquiry_sql.php file.
         */
        function install_post($var)
        {
            //$sql = e107::getDb();
            $mes = e107::getMessage();

            $pref_welcome = LAN_PLUGIN_ENQUIRY_WELCOME;

            $pref_success = LAN_PLUGIN_ENQUIRY_SUCCESS;


            $pref_error = LAN_PLUGIN_ENQUIRY_ERROR;


            $cfg = e107::getPlugConfig('enquiry', '', true);
            $cfg->set('pref_welcome', $pref_welcome);
            $cfg->set('pref_success', $pref_success);
            $cfg->set('pref_error', $pref_error);

            $result=$cfg->save(false, true, true);
            if ($result) {
                $mes->add("Added suggested texts.", E_MESSAGE_SUCCESS);
            } else {
                $mes->add("Failed to add suggested texts.", E_MESSAGE_ERROR);
            }

        }

        function uninstall_options()
        {

            $listoptions = array(0 => 'option 1', 1 => 'option 2');

            $options = array();
            $options['enquiry'] = array(
                'label' => 'Custom Uninstall Label',
                'preview' => 'Preview Area',
                'helpText' => 'Custom Help Text',
                'itemList' => $listoptions,
                'itemDefault' => 1);

            return $options;
        }


        function uninstall_post($var)
        {
            // print_a($var);
        }

        function upgrade_post($var)
        {
            // $sql = e107::getDb();
        }

    }

}
