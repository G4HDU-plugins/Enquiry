<?php

/**
 * Enquiry Form  Plugin for the e107 Website System
 *
 * Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */
global $sec_img;
define('e_CAPTCHA_FONTCOLOR', "ff0000");
include_once (e_HANDLER . 'secure_img_handler.php');
$sec_img = new secure_image;

class enquiry_class
{
    private $db;
    private $tp;
    private $frm;
    public $ns;
    private $template;
    private $message;
    private $shortcodes;
    private $prefs;
    private $allowedActions;
    private $rolesValue = 0;
    private $search = '';
    private $pdfInstalled = false;
    private $cache;
    //private $session = false;

    function __construct()
    {
        global $e107cache;

        $this->cache = $e107cache;

        error_reporting(E_ALL);
        $this->pdfInstalled = e107::isInstalled('e107pdf');

        if ($this->pdfInstalled)
        {
            require_once (e_PLUGIN . 'pdf/e107pdf.php'); //require the e107pdf class
        }
        $this->message = e107::getMessage();

        e107::js('footer', e_PLUGIN . 'enquiry/js/enquiry.js', 'jquery'); // Load Plugin javascript and include jQuery framework
        e107::css('url', e_PLUGIN . 'enquiry/css/enquiry.css'); // load css file
        e107::lan('enquiry'); // load language file ie. e107_plugins/addressbook/languages/English.php

        require_once (e_PLUGIN . 'enquiry/templates/enquiry_template.php');
        $this->template = new plugin_enquiry_template_class;

        require_once (e_PLUGIN . 'enquiry/shortcodes/enquiry_shortcodes.php');
        $this->shortcodes = new plugin_enquiry_shortcodes_class;


        $this->db = e107::getDB(); // mysql class object
        $this->tp = e107::getParser(); // parser for converting to HTML and parsing templates etc.
        $this->frm = e107::getForm(); // Form element class.
        $this->ns = e107::getRender(); // render in theme box.
        $this->prefs = e107::pref('enquiry'); // returns an array.

        $this->shortcodes->prefs = $this->prefs;
        $this->guest = check_class('252');
        $this->shortcodes->guest = $this->guest;
        $this->image = ($this->prefs['pref_use_captcha'] == 1 && (extension_loaded("gd") ? true : false));
        $this->shortcodes->use_imagecode = $this->image;
        if ($this->image && isset($_POST['rand_num']))
        {
            $this->verified_code = e107::getSecureImg()->verify_code($_POST['rand_num'], $_POST['code_verify']);
            
        }

    }

    /**
     * enquiry_class::runPage()
     * 
     * @return
     */
    public function runPage()
    {

        $class = $this->prefs['viewClass'];
        if (e107::getUser()->checkClass($class, false))
        {
            $this->text = $this->notPermitted();
            //$this->render = true;
        } else
        {
            $this->action = 'show'; // default value
            if ($_GET['action'] == 'show' || $_GET['action'] == 'form')
            {
                $this->action = 'form';
            }
           // print_a($_POST);
            if (isset($_POST['submit']))
            {
                $this->action = 'save';
            }
        if ($this->image && ($this->image && isset($_POST['rand_num']) && !$this->verified_code))
        {
            // not verified code
            $this->action='form';
        }
            switch ($this->action)
            {

                case 'form':
                    $this->text = $this->doForm();
                    break;
                case 'save':
                    if ($this->doSave())
                    {
                        $this->text = $this->enquirySaved();
                    } else
                    {
                        $this->text = $this->enquiryNotSaved();
                    }
                    break;
                default:
                case 'show':
                    $this->text = $this->enquiryFront();
                    break;
            }
        }
        return $this->text;
    }
    private function enquiryNotSaved()
    {
        if ($this->prefs['pref_error_use'])
        {
            $this->text = $this->tp->parseTemplate($this->tp->toHTML($this->prefs['pref_error'], true), true, $this->shortcodes);
        } else
        {

            $this->text = $this->tp->parseTemplate($this->tp->toHTML(LAN_PLUGIN_ENQUIRY_ERROR, true), true, $this->shortcodes);

        }
        return $this->text;
    }

    private function enquiryFront()
    {
        if ($this->prefs['pref_welcome_use'])
        {
            $this->text = $this->tp->parseTemplate($this->tp->toHTML($this->prefs['pref_welcome'], true), true, $this->shortcodes);
        } else
        {
            $this->text = $this->tp->parseTemplate($this->tp->toHTML(LAN_PLUGIN_ENQUIRY_WELCOME, true), true, $this->shortcodes);

        }
        return $this->text;
    }


    private function enquirySaved()
    {
        if ($this->prefs['pref_success_use'])
        {
            $this->text = $this->tp->parseTemplate($this->tp->toHTML($this->prefs['pref_success'], true), true, $this->shortcodes);
        } else
        {
            $this->text = $this->tp->parseTemplate($this->tp->toHTML(LAN_PLUGIN_ENQUIRY_SUCCESS, true), true, $this->shortcodes);
        }
        return $this->text;


        return $this->text;
    }
    /**
     * enquiry_class::doForm()
     * 
     * @return the form
     */
    function doForm()
    {
        $this->shortcodes->post = $_POST;
        $this->text = $this->tp->parseTemplate($this->template->enquiryForm(), true, $this->shortcodes);
        return $this->text;
    }

    /**
     * enquiry_class::doSave()
     * 
     * @return true if saved false if error
     */
    function doSave()
    {

        $row['enquiry_title'] = intval($_POST['enquiry_title']);
        $row['enquiry_name'] = $this->tp->toDB($_POST['enquiry_name']);
        $row['enquiry_address1'] = $this->tp->toDB($_POST['enquiry_address1']);
        $row['enquiry_address2'] = $this->tp->toDB($_POST['enquiry_address2']);
        $row['enquiry_town'] = $this->tp->toDB($_POST['enquiry_town']);
        $row['enquiry_county'] = $this->tp->toDB($_POST['enquiry_county']);
        $row['enquiry_phone'] = $this->tp->toDB($_POST['enquiry_phone']);
        $row['enquiry_email'] = $this->tp->toDB($_POST['enquiry_email']);
        $row['enquiry_category'] = (int)($_POST['enquiry_category']);
        $row['enquiry_otherinfo'] = $this->tp->toDB($_POST['enquiry_otherinfo']);
        $row['enquiry_agerange'] = intval($_POST['enquiry_agerange']);
        $row['enquiry_gender'] = intval($_POST['enquiry_gender']);
        $row['enquiry_dateposted'] = time();
        if (!$this->image || ($this->image && isset($_POST['rand_num']) && $this->verified_code))
        {
            $sql = 'INSERT INTO #enquiry_forms (';
            $values = 'VALUES (';
            foreach ($row as $key => $value)
            {
                $sql .= "{$key},";
                $values .= "'{$value}',";
            }
            $sql = substr($sql, 0, -1);
            $values = substr($values, 0, -1);

            $values .= ') ';
            $sql .= ') ' . $values;

            $this->cache->clear('nomd5_enquiry', false, true);
            if (e107::getDB()->gen($sql, false) !== false)
            {
                e107::getMessage()->addSuccess('Saved');
                $id = e107::getDB()->lastInsertId();
                $sql = 'SELECT * FROM #enquiry_forms 
            left join #enquiry_categories on enquiry_category = enquiry_category_id
            WHERE enquiry_id = ' . $id . ' ';
                e107::getDB()->gen($sql, false);
                $info = e107::getDB()->fetch();
                //   print_a($info);
                e107::getEvent()->trigger('enquiryNotify', $info);
                return true;
            } else
            {
                e107::getMessage()->addError(LAN_PLUGIN_ENQUIRY_FRONT_ERROR_SUBMIT);
                return false;
            }
        } else
        {
            e107::getMessage()->addError(LAN_PLUGIN_ENQUIRY_FRONT_ERROR_CAPTURE);
            return false;
        }


    }
    /**
     * enquiry_class::secure_image()
     * 
     * @return void
     */
    function secure_image()
    {
        global $sec_img; // if ( !USER && $this->LAN_GB_image )
        //  {
        if ($this->image)
        {
            $retval = " <input type = 'hidden' name = 'rand_num' value = '" . $securecodeimg = $this->sec_img->random_number . "' >
    " . $sec_img->r_image() . " <br /> <input class = 'tbox' type = 'text' name = 'code_verify' id = 'code_verify' size = '15' maxlength = '20' > ";
            //  }
            $this->sc->datarow['secure_image'] = $retval;
        }
        return;
    }
}

/**
 * mailMunger
 * 
 * @package Enquiry
 * @author Father Barry
 * @copyright 2019
 * @version $Id$
 * @access public
 */
class mailMunger
{

    function __constructor()
    {

    }
    /**
     * enquiry_class::munge()
     * 
     * @param mixed $address
     * @return munged email address
     */
    static function munge($address)
    {
        $address = strtolower($address);
        $coded = "";
        $unmixedkey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.@-";
        $inprogresskey = $unmixedkey;
        $mixedkey = "";
        $unshuffled = strlen($unmixedkey);
        for ($i = 0; $i <= strlen($unmixedkey); $i++)
        {
            $ranpos = rand(0, $unshuffled - 1);
            $nextchar = $inprogresskey{$ranpos};
            $mixedkey .= $nextchar;
            $before = substr($inprogresskey, 0, $ranpos);
            $after = substr($inprogresskey, $ranpos + 1, $unshuffled - ($ranpos + 1));
            $inprogresskey = $before . '' . $after;
            $unshuffled -= 1;
        }
        $cipher = $mixedkey;

        $shift = strlen($address);

        $txt = "<script type=\"text/javascript\" language=\"javascript\">\n" . "<!-" . "-\n" . "// Email obfuscator script 2.1 by Tim Williams, University of Arizona\n" .
            "// Random encryption key feature by Andrew Moulden, Site Engineering Ltd\n" . "// PHP version coded by Ross Killen, Celtic Productions Ltd\n" .
            "// This code is freeware provided these six comment lines remain intact\n" . "// A wizard to generate this code is at http://www.jottings.com/obfuscator/\n" .
            "// The PHP code may be obtained from http://www.celticproductions.net/\n\n";

        for ($j = 0; $j < strlen($address); $j++)
        {
            if (strpos($cipher, $address{$j}) == -1)
            {
                $chr = $address{$j};
                $coded .= $address{$j};
            } else
            {
                $chr = (strpos($cipher, $address{$j}) + $shift) % strlen($cipher);
                $coded .= $cipher{$chr};
            }
        }


        $txt .= "\ncoded = \"" . $coded . "\"\n" . " key = \"" . $cipher . "\"\n" . " shift=coded.length\n" . " link=\"\"\n" . " for (i=0; i<coded.length; i++) {\n" .
            " if (key.indexOf(coded.charAt(i))==-1) {\n" . " ltr = coded.charAt(i)\n" . " link += (ltr)\n" . " }\n" . " else { \n" . " ltr = (key.indexOf(coded.charAt(i))- 
shift+key.length) % key.length\n" . " link += (key.charAt(ltr))\n" . " }\n" . " }\n" . "document.write(\"<a href='mailto:\"+link+\"'>\"+link+\"</a>\")\n" . "\n" . "//-" . "->\n" . "<" .
            "/script><noscript>N/A" . "<" . "/noscript>";
        return $txt;
    }
}
