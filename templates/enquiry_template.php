<?php

/**
 * Enquiry Form  Plugin for the e107 Website System
 *
 * Copyright (C) 2008-2017 Barry Keal G4HDU (http://www.keal.me.uk)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */


class plugin_enquiry_template_class
{


    function __construct()
    {

    }
    function notPermitted()
    {

    }
    function enquiryForm()
    {
        $retval = '
    <form id="enquiry-form" method="post" action="' . e_SELF . '" class="form-horizontal">
        <fieldset>

        <!-- Form Name -->
            <legend>'.LAN_PLUGIN_ENQUIRY_FRONT_LEGEND.'</legend>
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_title">'.LAN_PLUGIN_ENQUIRY_FRONT_TITLE.'</label>
                    <div class="col-md-4"> 
                        {ENQUIRY_MR} {ENQUIRY_MRS} {ENQUIRY_MISS}{ENQUIRY_MS} {ENQUIRY_MX}         
                    </div>
                </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_name">'.LAN_PLUGIN_ENQUIRY_FRONT_NAME.'</label>  
                    <div class="col-md-4">
                        {ENQUIRY_NAME}
                    </div>
            </div>
            

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_name">'.LAN_PLUGIN_ENQUIRY_FRONT_ADDR1.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_ADDRESS1}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_address2">'.LAN_PLUGIN_ENQUIRY_FRONT_ADDR2.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_ADDRESS2}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_town">'.LAN_PLUGIN_ENQUIRY_FRONT_TOWN.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_TOWN}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_county">'.LAN_PLUGIN_ENQUIRY_FRONT_COUNTY.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_COUNTY}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_postcode">'.LAN_PLUGIN_ENQUIRY_FRONT_POSTCODE.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_POSTCODE}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_phone">'.LAN_PLUGIN_ENQUIRY_FRONT_PHONE.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_PHONE}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_email">'.LAN_PLUGIN_ENQUIRY_FRONT_EMAIL.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_EMAIL}
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_emailcheck">'.LAN_PLUGIN_ENQUIRY_FRONT_REMAIL.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_EMAILCHECK}
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_category">'.LAN_PLUGIN_ENQUIRY_FRONT_CAT.'</label>  
                <div class="col-md-4">
                    {ENQUIRY_CATEGORY}
                </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_otherinfo">'.LAN_PLUGIN_ENQUIRY_FRONT_ENQUIRY.'</label>
                <div class="col-md-4">                     
                    {ENQUIRY_OTHERINFO}
                </div>
            </div>
            <hr>

            <!-- Multiple Radios -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_gender">'.LAN_PLUGIN_ENQUIRY_FRONT_GENDER.'</label>
                <div class="col-md-4">
                    <div class="radio">
                        {ENQUIRY_GENDER0}
                    </div>
                    <div class="radio">
                        {ENQUIRY_GENDER1}
                    </div>
                    <div class="radio">
                        {ENQUIRY_GENDER2}
                    </div>
                </div>
            </div>

            <!-- Multiple Radios (inline) -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="enquiry_agerange">'.LAN_PLUGIN_ENQUIRY_FRONT_AGE.'</label>
                <div class="col-md-4"> 
                    {ENQUIRY_AGEPNS}<br>
                    {ENQUIRY_LESS18} {ENQUIRY_AGE18} {ENQUIRY_AGE30} {ENQUIRY_AGE50} {ENQUIRY_AGE65}
                </div>
            </div>
            <hr>

            <!-- Button (Double) -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit">&nbsp;</label>
                <div class="col-md-8">
                    {ENQUIRY_IMAGECODE_NUMBER}{ENQUIRY_IMAGECODE_BOX}
                    {ENQUIRY_SUBMIT}
                    {ENQUIRY_CANCEL}
                 </div>
            </div>
        </fieldset>
    </form>
';
        return $retval;
    }

}
