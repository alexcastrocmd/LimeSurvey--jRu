<?php
    App()->getClientScript()->registerPackage('jquery-selectboxes');

?>
<script type="text/javascript">
    var msgAtLeastOneLanguageNeeded = '<?php eT("You must set at last one available language.",'js'); ?>';
</script>
<div class='header ui-widget-header'><?php eT("Global settings"); ?></div>
<div id='tabs'>
    <ul>
        <li><a href='#overview'><?php eT("Overview & update"); ?></a></li>
        <li><a href='#general'><?php eT("General"); ?></a></li>
        <li><a href='#email'><?php eT("Email settings"); ?></a></li>
        <li><a href='#bounce'><?php eT("Bounce settings"); ?></a></li>
        <li><a href='#security'><?php eT("Security"); ?></a></li>
        <li><a href='#presentation'><?php eT("Presentation"); ?></a></li>
        <li><a href='#language'><?php eT("Language"); ?></a></li>
        <li><a href='#interfaces'><?php eT("Interfaces"); ?></a></li>
    </ul>
    <?php echo CHtml::form(array("admin/globalsettings"), 'post', array('class'=>'form30','id'=>'frmglobalsettings','name'=>'frmglobalsettings'));?>
        <div id='overview'>
            <div class='header ui-widget-header'><?php eT("System overview"); ?></div>
            <br /><table class='statisticssummary'>
                <tr>
                    <th ><?php eT("Users"); ?>:</th><td><?php echo $usercount; ?></td>
                </tr>
                <tr>
                    <th ><?php eT("Surveys"); ?>:</th><td><?php echo $surveycount; ?></td>
                </tr>
                <tr>
                    <th ><?php eT("Active surveys"); ?>:</th><td><?php echo $activesurveycount; ?></td>
                </tr>
                <tr>
                    <th ><?php eT("Deactivated result tables"); ?>:</th><td><?php echo $deactivatedsurveys; ?></td>
                </tr>
                <tr>
                    <th ><?php eT("Active token tables"); ?>:</th><td><?php echo $activetokens; ?></td>
                </tr>
                <tr>
                    <th ><?php eT("Deactivated token tables"); ?>:</th><td><?php echo $deactivatedtokens; ?></td>
                </tr>
                <?php
                    if (Yii::app()->getConfig('iFileUploadTotalSpaceMB')>0)
                    {
                        $fUsed=calculateTotalFileUploadUsage();
                    ?>
                    <tr>
                        <th ><?php eT("Used/free space for file uploads"); ?>:</th><td><?php echo sprintf('%01.2F',$fUsed); ?> MB / <?php echo sprintf('%01.2F',Yii::app()->getConfig('iFileUploadTotalSpaceMB')-$fUsed); ?> MB</td>
                    </tr>
                    <?php
                    }
                ?>
            </table>
            <?php
                if (Permission::model()->hasGlobalPermission('superadmin','read'))
                {
                ?>
                    <p><a href="<?php echo $this->createUrl('admin/globalsettings',array('sa'=>'showphpinfo')) ?>" target="blank" class="button"><?php eT("Show PHPInfo"); ?></a></p>
                <?php
                }
                ?>

                <div class='header ui-widget-header'><?php echo eT("Updates"); ?></div><br/><ul>
                <li><label for='updatecheckperiod'><?php echo eT("Automatically check for updates:"); ?></label>
                    <select name='updatecheckperiod' id='updatecheckperiod'>
                        <option value='0'
                            <?php if ($thisupdatecheckperiod==0) { echo "selected='selected'";} ?>
                            ><?php echo eT("Never"); ?></option>
                        <option value='1'
                            <?php if ($thisupdatecheckperiod==1) { echo "selected='selected'";} ?>
                            ><?php echo eT("Every day"); ?></option>
                        <option value='7'
                            <?php if ($thisupdatecheckperiod==7) { echo "selected='selected'";} ?>
                            ><?php echo eT("Every week"); ?></option>
                        <option value='14'
                            <?php if ($thisupdatecheckperiod==14) { echo "selected='selected'";} ?>
                            ><?php echo eT("Every 2 weeks"); ?></option>
                        <option value='30'
                            <?php if ($thisupdatecheckperiod==30) { echo "selected='selected'";} ?>
                            ><?php echo eT("Every month"); ?></option>
                    </select>&nbsp;<input type='button' onclick="window.open('<?php echo $this->createUrl("admin/globalsettings/sa/updatecheck"); ?>', '_top')" value='<?php eT("Check now"); ?>' />&nbsp;<span id='lastupdatecheck'><?php echo sprintf(gT("Last check: %s"),$updatelastcheck); ?></span>
                </li>
                <li><label for='updatenotification'><?php echo eT("Show update notifications:"); ?></label>
                    <select name='updatenotification' id='updatenotification'>
                        <option value='never'
                            <?php if ($sUpdateNotification=='never') { echo "selected='selected'";} ?>
                            ><?php echo eT("Never"); ?></option>
                        <option value='stable'
                            <?php if ($sUpdateNotification=='stable') { echo "selected='selected'";} ?>
                            ><?php echo eT("For stable versions"); ?></option>
                        <option value='both'
                            <?php if ($sUpdateNotification=='both') { echo "selected='selected'";} ?>
                            ><?php echo eT("For stable and unstable versions"); ?></option>
                    </select></li>

                <?php
                    if (isset($updateavailable) && $updateavailable==1 && is_array($aUpdateVersions))
                    { ?>
                    <li><label><span style="font-weight: bold;"><?php echo gT('The following LimeSurvey updates are available:');?></span></label><table>
                        <?php 
                        foreach ($aUpdateVersions as $aUpdateVersion)
                        {?>
                           <tr><td>
                            <?php echo $aUpdateVersion['versionnumber'];?> (<?php echo $aUpdateVersion['build'];?>) <?php if ($aUpdateVersion['branch']!='master') eT('(unstable)'); else eT('(stable)');?>
                           </td>
                           <td>
                                <input type='button' onclick="window.open('<?php echo $this->createUrl("admin/update/sa/index",array('build'=>$aUpdateVersion['build'])); ?>', '_top')" value='<?php eT("Use ComfortUpdate"); ?>' />
                                <?php if ($aUpdateVersion['branch']!='master') {?> <input type='button' onclick="window.open('http://www.limesurvey.org/en/unstable-release/viewcategory/26-unstable-releases', '_blank')" value='<?php eT("Download"); ?>' /> <?php } 
                                else {?> <input type='button' onclick="window.open('http://www.limesurvey.org/en/stable-release', '_blank')" value='<?php eT("Download"); ?>' /> <?php }?>
                           </td></tr>
                        <?php    
                        };?>
                        </table>
                    </ul>
                    <p><?php echo sprintf(gT('You can %s download and update manually %s or use the %s.'),"<a href='http://manual.limesurvey.org//Upgrading_from_a_previous_version'>","</a>","<a href='http://manual.limesurvey.org/ComfortUpdate'>".gT('3-Click ComfortUpdate').'</a>'); ?></p>
                    <?php }
                    elseif (isset($updateinfo['errorcode']))
                    { echo sprintf(gT('There was an error on update check (%s)'),$updateinfo['errorcode']); ?><br />
                    <textarea readonly='readonly' style='width:35%; height:60px; overflow: auto;'><?php echo strip_tags($updateinfo['errorhtml']); ?></textarea>

                    <?php }
                    elseif ($updatable)
                    {
                        eT('There is currently no newer LimeSurvey version available.');
                    }
                    else
                    {
                        printf(gT('This is an unstable version and cannot be updated using ComfortUpdate. Please check %sour website%s regularly for a newer version.'),"<a href='http://www.limesurvey.org'>","</a>");
                    }

                ?>
            </ul>
            </p></div>

        <div id='general'>
            <ul>
                <li><label for='sitename'><?php eT("Site name:"); echo ((Yii::app()->getConfig("demoMode")==true)?'*':''); ?></label>
                    <input type='text' size='50' id='sitename' name='sitename' value="<?php echo htmlspecialchars(getGlobalSetting('sitename')); ?>" /></li>
                <?php

                    $thisdefaulttemplate=getGlobalSetting('defaulttemplate');
                    $templatenames=array_keys(getTemplateList());

                ?>

                <li><label for="defaulttemplate"><?php eT("Default template:"); echo ((Yii::app()->getConfig("demoMode")==true)?'*':''); 
                
                ?></label>
                    <select name="defaulttemplate" id="defaulttemplate">
                        <?php
                            foreach ($templatenames as $templatename)
                            {
                                echo "<option value='$templatename'";
                                if ($thisdefaulttemplate==$templatename) { echo " selected='selected' ";}
                                echo ">$templatename</option>";
                            }
                        ?>
                    </select>
                </li>
                <?php

                    $thisadmintheme=getGlobalSetting('admintheme');
                    $adminthemes=array_keys(getAdminThemeList());

                ?>
                <li><label for="admintheme"><?php eT("Administration template:"); ?></label>
                    <select name="admintheme" id="admintheme">
                        <?php
                            foreach ($adminthemes as $templatename)
                            {
                                echo "<option value='{$templatename}'";
                                if ($thisadmintheme==$templatename) { echo " selected='selected' ";}
                                echo ">{$templatename}</option>";
                            }
                        ?>
                    </select>
                </li>


                <?php $thisdefaulthtmleditormode=getGlobalSetting('defaulthtmleditormode'); ?>
                <li><label for='defaulthtmleditormode'><?php eT("Default HTML editor mode:"); echo ((Yii::app()->getConfig("demoMode")==true)?'*':''); ?></label>
                    <select name='defaulthtmleditormode' id='defaulthtmleditormode'>
                        <option value='none'
                            <?php if ($thisdefaulthtmleditormode=='none') { echo "selected='selected'";} ?>
                            ><?php eT("No HTML editor"); ?></option>
                        <option value='inline'
                            <?php if ($thisdefaulthtmleditormode=='inline') { echo "selected='selected'";} ?>
                            ><?php eT("Inline HTML editor (default)"); ?></option>
                        <option value='popup'
                            <?php if ($thisdefaulthtmleditormode=='popup') { echo "selected='selected'";} ?>
                            ><?php eT("Popup HTML editor"); ?></option>
                    </select></li>
                <?php $thisdefaultquestionselectormode=getGlobalSetting('defaultquestionselectormode'); ?>
                <li><label for='defaultquestionselectormode'><?php eT("Question type selector:"); echo((Yii::app()->getConfig("demoMode")==true)?'*':''); ?></label>
                    <select name='defaultquestionselectormode' id='defaultquestionselectormode'>
                        <option value='default'
                            <?php if ($thisdefaultquestionselectormode=='default') { echo "selected='selected'";} ?>
                            ><?php eT("Full selector (default)"); ?></option>
                        <option value='none'
                            <?php if ($thisdefaultquestionselectormode=='none') { echo "selected='selected'";} ?>
                            ><?php eT("Simple selector"); ?></option>
                    </select></li>
                <?php $thisdefaulttemplateeditormode=getGlobalSetting('defaulttemplateeditormode'); ?>
                <li><label for='defaulttemplateeditormode'><?php eT("Template editor:"); echo ((Yii::app()->getConfig("demoMode")==true)?'*':''); ?></label>
                    <select name='defaulttemplateeditormode' id='defaulttemplateeditormode'>
                        <option value='default'
                            <?php if ($thisdefaulttemplateeditormode=='default') { echo "selected='selected'";} ?>
                            ><?php eT("Full template editor (default)"); ?></option>
                        <option value='none'
                            <?php if ($thisdefaulttemplateeditormode=='none') { echo "selected='selected'";} ?>
                            ><?php eT("Simple template editor"); ?></option>
                    </select></li>
                <?php $dateformatdata=getDateFormatData(Yii::app()->session['dateformat']); ?>
                <li><label for='timeadjust'><?php eT("Time difference (in hours):"); ?></label>
                    <span><input type='text' size='10' id='timeadjust' name='timeadjust' value="<?php echo htmlspecialchars(str_replace(array('+',' hours',' minutes'),array('','',''),getGlobalSetting('timeadjust'))/60); ?>" />
                        <?php echo gT("Server time:").' '.convertDateTimeFormat(date('Y-m-d H:i:s'),'Y-m-d H:i:s',$dateformatdata['phpdate'].' H:i')." - ". gT("Corrected time:").' '.convertDateTimeFormat(dateShift(date("Y-m-d H:i:s"), 'Y-m-d H:i:s', getGlobalSetting('timeadjust')),'Y-m-d H:i:s',$dateformatdata['phpdate'].' H:i'); ?>
                    </span></li>

                <li <?php if( ! isset(Yii::app()->session->connectionID)) echo 'style="display: none"';?>><label for='iSessionExpirationTime'><?php eT("Session lifetime for surveys (seconds):"); ?></label>
                    <input type='text' size='10' id='iSessionExpirationTime' name='iSessionExpirationTime' value="<?php echo htmlspecialchars(getGlobalSetting('iSessionExpirationTime')); ?>" /></li>
                <li><label for='ipInfoDbAPIKey'><?php eT("IP Info DB API Key:"); ?></label>
                    <input type='text' size='35' id='ipInfoDbAPIKey' name='ipInfoDbAPIKey' value="<?php echo htmlspecialchars(getGlobalSetting('ipInfoDbAPIKey')); ?>" /></li>
                <li><label for='googleMapsAPIKey'><?php eT("Google Maps API key:"); ?></label>
                    <input type='text' size='35' id='googleMapsAPIKey' name='googleMapsAPIKey' value="<?php echo htmlspecialchars(getGlobalSetting('googleMapsAPIKey')); ?>" /></li>
                <li><label for='googleanalyticsapikey'><?php eT("Google Analytics API key:"); ?></label>
                    <input type='text' size='35' id='googleanalyticsapikey' name='googleanalyticsapikey' value="<?php echo htmlspecialchars(getGlobalSetting('googleanalyticsapikey')); ?>" /></li>
                <li><label for='googletranslateapikey'><?php eT("Google Translate API key:"); ?></label>
                    <input type='text' size='35' id='googletranslateapikey' name='googletranslateapikey' value="<?php echo htmlspecialchars(getGlobalSetting('googletranslateapikey')); ?>" /></li>
            </ul></div>


        <div id='email'><ul>
                <li><label for='siteadminemail'><?php eT("Default site admin email:"); ?></label>
                    <input type='email' size='50' id='siteadminemail' name='siteadminemail' value="<?php echo htmlspecialchars(getGlobalSetting('siteadminemail')); ?>" /></li>

                <li><label for='siteadminname'><?php eT("Administrator name:"); ?></label>
                    <input type='text' size='50' id='siteadminname' name='siteadminname' value="<?php echo htmlspecialchars(getGlobalSetting('siteadminname')); ?>" /><br /><br /></li>
                <li><label for='emailmethod'><?php eT("Email method:"); ?></label>
                    <select id='emailmethod' name='emailmethod'>
                        <option value='mail'
                            <?php if (getGlobalSetting('emailmethod')=='mail') { echo "selected='selected'";} ?>
                            ><?php eT("PHP (default)"); ?></option>
                        <option value='smtp'
                            <?php if (getGlobalSetting('emailmethod')=='smtp') { echo "selected='selected'";} ?>
                            ><?php eT("SMTP"); ?></option>
                        <option value='sendmail'
                            <?php if (getGlobalSetting('emailmethod')=='sendmail') { echo "selected='selected'";} ?>
                            ><?php eT("Sendmail"); ?></option>
                        <option value='qmail'
                            <?php if (getGlobalSetting('emailmethod')=='qmail') { echo "selected='selected'";} ?>
                            ><?php eT("Qmail"); ?></option>
                    </select></li>
                <li><label for="emailsmtphost"><?php eT("SMTP host:"); ?></label>
                    <input type='text' size='50' id='emailsmtphost' name='emailsmtphost' value="<?php echo htmlspecialchars(getGlobalSetting('emailsmtphost')); ?>" />&nbsp;<span class='hint'><?php eT("Enter your hostname and port, e.g.: my.smtp.com:25"); ?></span></li>
                <li><label for='emailsmtpuser'><?php eT("SMTP username:"); ?></label>
                    <input type='text' size='50' id='emailsmtpuser' name='emailsmtpuser' value="<?php echo htmlspecialchars(getGlobalSetting('emailsmtpuser')); ?>" /></li>
                <li><label for='emailsmtppassword'><?php eT("SMTP password:"); ?></label>
                    <input type='password' size='50' id='emailsmtppassword' name='emailsmtppassword' value='somepassword' /></li>
                <li><label for='emailsmtpssl'><?php eT("SMTP SSL/TLS:"); ?></label>
                    <select id='emailsmtpssl' name='emailsmtpssl'>
                        <option value=''
                            <?php if (getGlobalSetting('emailsmtpssl')=='') { echo "selected='selected'";} ?>
                            ><?php eT("Off"); ?></option>
                        <option value='ssl'
                            <?php if (getGlobalSetting('emailsmtpssl')=='ssl' || getGlobalSetting('emailsmtpssl')==1) { echo "selected='selected'";} ?>
                            ><?php eT("SSL"); ?></option>
                        <option value='tls'
                            <?php if (getGlobalSetting('emailsmtpssl')=='tls') { echo "selected='selected'";} ?>
                            ><?php eT("TLS"); ?></option>
                    </select></li>
                <li><label for='emailsmtpdebug'><?php eT("SMTP debug mode:"); ?></label>
                    <select id='emailsmtpdebug' name='emailsmtpdebug'>
                        <option value='0'
                            <?php
                            if (getGlobalSetting('emailsmtpdebug')=='0') { echo "selected='selected'";} ?>
                            ><?php eT("Off"); ?></option>
                        <option value='1'
                            <?php if (getGlobalSetting('emailsmtpdebug')=='1' || getGlobalSetting('emailsmtpssl')==1) { echo "selected='selected'";} ?>
                            ><?php eT("On errors"); ?></option>
                        <option value='2'
                            <?php if (getGlobalSetting('emailsmtpdebug')=='2' || getGlobalSetting('emailsmtpssl')==1) { echo "selected='selected'";} ?>
                            ><?php eT("Always"); ?></option>
                    </select><br />&nbsp;</li>
                <li><label for='maxemails'><?php eT("Email batch size:"); ?></label>
                    <input type='text' size='5' id='maxemails' name='maxemails' value="<?php echo htmlspecialchars(getGlobalSetting('maxemails')); ?>" /></li>
            </ul>

        </div>

        <div id='bounce'><ul>
                <li><label for='siteadminbounce'><?php eT("Default site bounce email:"); ?></label>
                    <input type='text' size='50' id='siteadminbounce' name='siteadminbounce' value="<?php echo htmlspecialchars(getGlobalSetting('siteadminbounce')); ?>" /></li>
                <li><label for='bounceaccounttype'><?php eT("Server type:"); ?></label>
                    <select id='bounceaccounttype' name='bounceaccounttype'>
                        <option value='off'
                            <?php if (getGlobalSetting('bounceaccounttype')=='off') {echo " selected='selected'";}?>
                            ><?php eT("Off"); ?></option>
                        <option value='IMAP'
                            <?php if (getGlobalSetting('bounceaccounttype')=='IMAP') {echo " selected='selected'";}?>
                            ><?php eT("IMAP"); ?></option>
                        <option value='POP'
                            <?php if (getGlobalSetting('bounceaccounttype')=='POP') {echo " selected='selected'";}?>
                            ><?php eT("POP"); ?></option>
                    </select></li>

                <li><label for='bounceaccounthost'><?php eT("Server name & port:"); ?></label>
                    <input type='text' size='50' id='bounceaccounthost' name='bounceaccounthost' value="<?php echo htmlspecialchars(getGlobalSetting('bounceaccounthost'))?>" /> <span class='hint'><?php eT("Enter your hostname and port, e.g.: imap.gmail.com:995"); ?></span>
                </li>
                <li><label for='bounceaccountuser'><?php eT("User name:"); ?></label>
                    <input type='text' size='50' id='bounceaccountuser' name='bounceaccountuser'
                        value="<?php echo htmlspecialchars(getGlobalSetting('bounceaccountuser'))?>" /></li>
                <li><label for='bounceaccountpass'><?php eT("Password:"); ?></label>
                    <input type='password' size='50' id='bounceaccountpass' name='bounceaccountpass' value='enteredpassword' /></li>
                <li><label for='bounceencryption'><?php eT("Encryption type:"); ?></label>
                    <select id='bounceencryption' name='bounceencryption'>
                        <option value='off'
                            <?php if (getGlobalSetting('bounceencryption')=='off') {echo " selected='selected'";}?>
                            ><?php eT("Off"); ?></option>
                        <option value='SSL'
                            <?php if (getGlobalSetting('bounceencryption')=='SSL') {echo " selected='selected'";}?>
                            ><?php eT("SSL"); ?></option>
                        <option value='TLS'
                            <?php if (getGlobalSetting('bounceencryption')=='TLS') {echo " selected='selected'";}?>
                            ><?php eT("TLS"); ?></option>
                    </select></li></ul>
        </div>

        <div id='security'><ul>
                <?php $thissurveyPreview_require_Auth=getGlobalSetting('surveyPreview_require_Auth'); ?>
                <li><label for='surveyPreview_require_Auth'><?php eT("Survey preview only for administration users"); ?></label>
                    <select id='surveyPreview_require_Auth' name='surveyPreview_require_Auth'>
                        <option value='1'
                            <?php if ($thissurveyPreview_require_Auth == true) { echo " selected='selected'";}?>
                            ><?php eT("Yes"); ?></option>
                        <option value='0'
                            <?php if ($thissurveyPreview_require_Auth == false) { echo " selected='selected'";}?>
                            ><?php eT("No"); ?></option>
                    </select></li>

                <?php $thisfilterxsshtml=getGlobalSetting('filterxsshtml'); ?>
                <li><label for='filterxsshtml'><?php eT("Filter HTML for XSS:"); echo ((Yii::app()->getConfig("demoMode")==true)?'*':''); ?></label>
                    <select id='filterxsshtml' name='filterxsshtml'>
                        <option value='1'
                            <?php if ( $thisfilterxsshtml == true) { echo " selected='selected'";}?>
                            ><?php eT("Yes"); ?></option>
                        <option value='0'
                            <?php if ( $thisfilterxsshtml == false) { echo " selected='selected'";}?>
                            ><?php eT("No"); ?></option>
			    </select>&nbsp;<span class='hint'><?php eT("(XSS filtering is always disabled for the superadministrator.)"); ?></span></li>

                <?php $thisusercontrolSameGroupPolicy=getGlobalSetting('usercontrolSameGroupPolicy'); ?>
                <li><label for='usercontrolSameGroupPolicy'><?php eT("Group member can only see own group:"); ?></label>
                    <select id='usercontrolSameGroupPolicy' name='usercontrolSameGroupPolicy'>
                        <option value='1'
                            <?php if ( $thisusercontrolSameGroupPolicy == true) { echo " selected='selected'";}?>
                            ><?php eT("Yes"); ?></option>
                        <option value='0'
                            <?php if ( $thisusercontrolSameGroupPolicy == false) { echo " selected='selected'";}?>
                            ><?php eT("No"); ?></option>
                    </select></li>

                <?php $thisforce_ssl = getGlobalSetting('force_ssl');
                    $opt_force_ssl_on = $opt_force_ssl_off = $opt_force_ssl_neither = '';
                    $warning_force_ssl = sprintf(gT('Warning: Before turning on HTTPS,%s check if this link works.%s'),'<a href="https://'.$_SERVER['HTTP_HOST'].$this->createUrl("admin/globalsettings/sa").'" title="'. gT('Test if your server has SSL enabled by clicking on this link.').'">','</a>')
                    .'<br/> '
                    . gT("If the link does not work and you turn on HTTPS, LimeSurvey will break and you won't be able to access it.");
                    switch($thisforce_ssl)
                    {
                        case 'on':
                            $warning_force_ssl = '&nbsp;';
                            break;
                        case 'off':
                        case 'neither':
                            break;
                        default:
                            $thisforce_ssl = 'neither';
                    };
                    $this_opt = 'opt_force_ssl_'.$thisforce_ssl;
                    $$this_opt = ' selected="selected"';
                ?><li><label for="force_ssl"><?php eT('Force HTTPS:'); ?></label>
                    <select name="force_ssl" id="force_ssl">
                        <option value="on" <?php echo $opt_force_ssl_on; ?>><?php eT('On'); ?></option>
                        <option value="off" <?php echo $opt_force_ssl_off; ?>><?php eT('Off'); ?></option>
                        <option value="neither" <?php echo $opt_force_ssl_neither; ?>><?php eT("Don't force on or off"); ?></option>
                    </select></li>
                <li><span style='font-size:0.7em;'><?php echo $warning_force_ssl; ?></span></li>
                <?php unset($thisforce_ssl,$opt_force_ssl_on,$opt_force_ssl_off,$opt_force_ssl_neither,$warning_force_ssl,$this_opt); ?>
            </ul></div>

        <div id='presentation'><ul>
                <?php $shownoanswer=getGlobalSetting('shownoanswer');
                    $sel_na = array( 0 => '' , 1 => '' , 2 => '');
                    $sel_na[$shownoanswer] = ' selected="selected"'; ?>
                <li><label for='shownoanswer'><?php eT("Show 'no answer' option for non-mandatory questions:"); ?></label>
                    <select id='shownoanswer' name='shownoanswer'>
                        <option value="1" <?php echo $sel_na[1]; ?> ><?php eT('Yes'); ?></option>
                        <option value="0" <?php echo $sel_na[0]; ?> ><?php eT('No'); ?></option>
                        <option value="2" <?php echo $sel_na[2]; ?> ><?php eT('Survey admin can choose'); ?></option>
                    </select></li>

                <?php $thisrepeatheadings=getGlobalSetting('repeatheadings'); ?>
                <li><label for='repeatheadings'><?php eT("Repeating headings in array questions every X subquestions:"); ?></label>
                    <input id='repeatheadings' name='repeatheadings' value='<?php echo $thisrepeatheadings; ?>' size='4' maxlength='4' /></li>

                <?php
                    // showxquestions
                    $set_xq=getGlobalSetting('showxquestions');
                    $sel_xq = array( 'hide' => '' , 'show' => '' , 'choose' => '');
                    $sel_xq[$set_xq] = ' selected="selected"';
                    if( empty($sel_xq['hide']) && empty($sel_xq['show']) && empty($sel_xq['choose']))
                    {
                        $sel_xq['choose'] = ' selected="selected"';
                    };
                ?>
                <li><label for="showxquestions"><?php eT('Show "There are X questions in this survey"'); ?></label>
                    <select id="showxquestions" name="showxquestions">
                        <option value="show"<?php echo $sel_xq['show']; ?>><?php eT('Yes'); ?></option>
                        <option value="hide"<?php echo $sel_xq['hide']; ?>><?php eT('No'); ?></option>
                        <option value="choose"<?php echo $sel_xq['choose']; ?>><?php eT('Survey admin can choose'); ?></option>
                    </select></li>
                <?php unset($set_xq,$sel_xq);
                    $set_gri=getGlobalSetting('showgroupinfo');
                    $sel_gri = array( 'both' => '' , 'choose' =>'' , 'description' => '' , 'name' => '' , 'none' => '' );
                    $sel_gri[$set_gri] = ' selected="selected"';
                    if( empty($sel_gri['both']) && empty($sel_gri['choose']) && empty($sel_gri['description']) && empty($sel_gri['name']) && empty($sel_gri['none']))
                    {
                        $sel_gri['choose'] = ' selected="selected"';
                    }; ?>
                <li><label for="showgroupinfo"><?php eT('Show question group name and/or description'); ?></label>
                    <select id="showgroupinfo" name="showgroupinfo">
                        <option value="both"<?php echo $sel_gri['both']; ?>><?php eT('Show both'); ?></option>
                        <option value="name"<?php echo $sel_gri['name']; ?>><?php eT('Show group name only'); ?></option>
                        <option value="description"<?php echo $sel_gri['description']; ?>><?php eT('Show group description only'); ?></option>
                        <option value="none"<?php echo $sel_gri['none']; ?>><?php eT('Hide both'); ?></option>
                        <option value="choose"<?php echo $sel_gri['choose']; ?>><?php eT('Survey admin can choose'); ?></option>
                    </select></li><?php
                    unset($set_gri,$sel_gri);

                    // showqnumcode
                    $set_qnc=getGlobalSetting('showqnumcode');
                    $sel_qnc = array( 'both' => '' , 'choose' =>'' , 'number' => '' , 'code' => '' , 'none' => '' );
                    $sel_qnc[$set_qnc] = ' selected="selected"';
                    if( empty($sel_qnc['both']) && empty($sel_qnc['choose']) && empty($sel_qnc['number']) && empty($sel_qnc['code']) && empty($sel_qnc['none']))
                    {
                        $sel_qnc['choose'] = ' selected="selected"';
                    };
                ?>
                <li><label for="showqnumcode"><?php eT('Show question number and/or question code'); ?></label>
                    <select id="showqnumcode" name="showqnumcode">
                        <option value="both"<?php echo $sel_qnc['both']; ?>><?php eT('Show both'); ?></option>
                        <option value="number"<?php echo $sel_qnc['number']; ?>><?php eT('Show question number only'); ?></option>
                        <option value="code"<?php echo $sel_qnc['code']; ?>><?php eT('Show question code only'); ?></option>
                        <option value="none"<?php echo $sel_qnc['none']; ?>><?php eT('Hide both'); ?></option>
                        <option value="choose"<?php echo $sel_qnc['choose']; ?>><?php eT('Survey admin can choose'); ?></option>
                    </select></li><?php
                    unset($set_qnc,$sel_qnc);
                ?>
            </ul>

        </div>
        <div id='language'>
            <ul>
                <li><label for='defaultlang'><?php eT("Default site language:"); echo ((Yii::app()->getConfig("demoMode")==true)?'*':''); ?></label>
                    <select name='defaultlang' id='defaultlang'>
                        <?php
                            $actuallang=getGlobalSetting('defaultlang');
                            foreach (getLanguageData(true) as  $langkey2=>$langname)
                            {
                            ?>
                            <option value='<?php echo $langkey2; ?>'
                                <?php
                                    if ($actuallang == $langkey2) { ?> selected='selected' <?php } ?>
                                ><?php echo $langname['nativedescription']." - ".$langname['description']; ?></option>
                            <?php
                            }
                        ?>
                    </select>
                </li>
                <li><label for='includedLanguages'><?php eT("Available languages:"); ?></label>
                    <table id='languageSelection'>
                        <tr>
                            <td>
                                <select style='min-width:220px;' size='5' id='includedLanguages' name='includedLanguages' multiple='multiple'><?php
                                        foreach ($restrictToLanguages as $sLanguageCode) {?>
                                        <option value='<?php echo $sLanguageCode; ?>'><?php echo $allLanguages[$sLanguageCode]['description']; ?></option>
                                        <?php
                                    }?>

                                </select>
                            </td>
                            <td >
                                <button id="btnAdd" type="button"><span class="ui-icon ui-icon-carat-1-w" style="float:left"></span><?php eT("Add"); ?></button><br /><button type="button" id="btnRemove"><span class="ui-icon ui-icon-carat-1-e" style="float:right"></span><?php eT("Remove"); ?></button>
                            </td>
                            <td >
                                <select size='5' style='min-width:220px;' id='excludedLanguages' name='excludedLanguages' multiple='multiple'>
                                    <?php foreach ($excludedLanguages as $sLanguageCode) {
                                        ?><option value='<?php echo $sLanguageCode; ?>'><?php echo $allLanguages[$sLanguageCode]['description']; ?></option><?php
                                    } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </li>
            </ul>
        </div>
        <div id='interfaces'>
            <ul>
                <?php $RPCInterface=getGlobalSetting('RPCInterface'); ?>
                <li><label for='RPCInterface'><?php eT("RPC interface enabled:"); ?></label>
                    <select id='RPCInterface' name='RPCInterface'>
                        <option value='off'
                            <?php if ($RPCInterface == 'off') { echo " selected='selected'";}?>
                            ><?php eT("Off"); ?></option>
                        <option value='json'
                            <?php if ($RPCInterface == 'json') { echo " selected='selected'";}?>
                            ><?php eT("JSON-RPC"); ?></option>
                        <option value='xml'
                            <?php if ($RPCInterface == 'xml') { echo " selected='selected'";}?>
                            ><?php eT("XML-RPC"); ?></option>
                    </select></li>
                    <li><label><?php eT("URL:"); ?></label><?php echo $this->createAbsoluteUrl("admin/remotecontrol"); ?></li>
                    <?php $rpc_publish_api=getGlobalSetting('rpc_publish_api'); ?>
                    <li><label for='rpc_publish_api'><?php eT("Publish API on /admin/remotecontrol:"); ?></label>
                        <select id='rpc_publish_api' name='rpc_publish_api'>
                            <option value='1'
                                <?php if ($rpc_publish_api == true) { echo " selected='selected'";}?>
                                ><?php eT("Yes"); ?></option>
                            <option value='0'
                                <?php if ($rpc_publish_api == false) { echo " selected='selected'";}?>
                                ><?php eT("No"); ?></option>
                        </select>
                    </li>
            </ul>
        </div>
        <input type='hidden' name='restrictToLanguages' id='restrictToLanguages' value='<?php implode(' ',$restrictToLanguages); ?>'/>
        <input type='hidden' name='action' value='globalsettingssave'/>
    </form>

</div>

<p><br/><input type='button' onclick='$("#frmglobalsettings").submit();' class='standardbtn' value='<?php eT("Save settings"); ?>' /><br /></p>
<?php if (Yii::app()->getConfig("demoMode")==true)
    { ?>
    <p><?php eT("Note: Demo mode is activated. Marked (*) settings can't be changed."); ?></p>
    <?php } ?>
