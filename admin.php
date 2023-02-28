<?php
/*
Plesk Spamdyke Control Panel (Version see version.php) - GUI for Plesk spamdyke implementation

Copyright (C) [2008] [Matthias Hackbarth / www.haggybear.de]

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as 
published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

$error = "";
?>
<script type="text/javascript">
<!--
function getPids() {
    var xmlHttpReq = false;
    						    
    var self = this;

    // Mozilla/Safari
    if (window.XMLHttpRequest) {
       windowPos = window.pageYOffset;
       self.xmlHttpReq = new XMLHttpRequest();
       }
    
    // IE
    else if (window.ActiveXObject) {
        windowPos = document.body.scrollTop;
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
        }
	
    self.xmlHttpReq.open('GET', "spamdyke_processes.php", true);
    
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    self.xmlHttpReq.onreadystatechange = function() {
    if (self.xmlHttpReq.readyState == 4) {
        document.getElementById("spamdyke_pids").innerHTML = self.xmlHttpReq.responseText;
        ermittlePids();
        }
    }
    self.xmlHttpReq.send(null);
    
}

function ermittlePids(){
	 if(document.scpedit.saveAdmView.value == "allg_settings"){
	    window.setTimeout("getPids()",1000);
	    }
	 }
	 
function toggleBox(id,allNone){
	 elements = document.getElementsByTagName("input");
	 firstElement = null;
	 for(i=0;i<elements.length;i++){
	     
	     if(!allNone){
  	        if(elements[i].name.indexOf("#"+id)> -1){
	     	  elements[i].checked = (elements[i].checked)?false:true;
	     	  }
	     	}
	     else{
	       if(elements[i].name.indexOf("#"+id)> -1){
	       	  if(firstElement == null){
	       	     firstElement = (!elements[i].checked)?true:false;
	       	     }
	       	
	     	  elements[i].checked = firstElement;
	     	  }
	       
	       }
	     }
	     
	 }
	 
function toggleDomain(dom){
	 elements = document.getElementsByTagName("input");
	 firstElement = null;
	 for(i=0;i<elements.length;i++){
	     if(elements[i].name.indexOf(dom+"#")== 0){
	        if(firstElement == null){
	           firstElement = (!elements[i].checked)?true:false;
	           }
	      
	        elements[i].checked = firstElement;
	     	}
	     }
	 }
	 
function eventCheckerJs(t){
	 if(t.value==0){
	    $('#eh_create_rights').css("background","#FFFFFF");	
	    $('#eh_create_rights').css("border","none");	
	    $('#details_create_rights').hide();	
	    }
	 else{
	    $('#eh_create_rights').css("background","#DCDEDE");	
	    $('#eh_create_rights').css("border-top","1px solid #000000");
	    $('#eh_create_rights').css("border-left","1px solid #000000");
	    $('#eh_create_rights').css("border-bottom","1px solid #000000");
	    var anz = $("#details_create_rights").find("select").length;
	    var h = 24;
	    hoehe = anz*h+'px';
	    $('#details_create_rights').height(hoehe);		 
	    $('#details_create_rights').show();		 
	    
	   }
         }	 
//-->
</script>
   <div id="menuslot">
    <div id="menuicon"><img id="m_on" src="menu.svg" onClick="toggleMenu(true)"><img id="m_off" src="cancel.png" onClick="toggleMenu(false)" style="display:none"> <span>Menu</span></div>
    <div id="menuicon"><img src="Transparent.gif"></div>
    <div id="menuicon" data-rel="gl_settings_lnk" onClick="adminView('gl_settings')"><img src="greylisted.svg" title="<?php echo SCP_ADMIN_GREYLISTING;?>" alt="<?php echo SCP_ADMIN_GREYLISTING;?>"> 
      <span>
         <a href="#"><?php echo SCP_ADMIN_GREYLISTING;?></a>
      </span>
    </div>
    <div id="menuicon" data-rel="dnsbl_settings_lnk" onClick="adminView('dnsbl_settings')"><img src="dnswlbl.svg" title="<?php echo SCP_ADMIN_DNSBL_LISTS;?>" alt="<?php echo SCP_ADMIN_DNSBL_LISTS;?>"> 
      <span>
         <a href="#"><?php echo SCP_ADMIN_DNSBL_LISTS;?></a>
      </span>
    </div>
    <div id="menuicon" data-rel="allg_settings_lnk" onClick="adminView('allg_settings')"><img src="divsettings.svg" title="<?php echo SCP_ADMIN_ALLG_SETTINGS;?>" alt="<?php echo SCP_ADMIN_ALLG_SETTINGS;?>"> 
      <span>
         <a href="#"><?php echo SCP_ADMIN_ALLG_SETTINGS;?></a>
      </span>
    </div>
    <div id="menuicon" data-rel="multi_right_lnk" onClick="adminView('multi_right')"><img src="rights.svg" title="<?php echo SCP_ADMIN_MULTI_RIGHTS;?>" alt="<?php echo SCP_ADMIN_MULTI_RIGHTS;?>"> 
      <span>
         <a href="#"><?php echo SCP_ADMIN_MULTI_RIGHTS;?></a>
      </span>
    </div>
    <div id="menuicon" data-rel="report_settings_lnk" onClick="adminView('report_settings')"><img src="report.svg" title="<?php echo SCP_ADMIN_REPORTS;?>" alt="<?php echo SCP_ADMIN_REPORTS;?>"> 
      <span>
         <a href="#"><?php echo SCP_ADMIN_REPORTS;?></a>
      </span>
    </div>
    <div id="menuicon"><img src="Transparent.gif"></div>
    <?php if(!$scp->getPleskManagerView()):?>
    <div id="menuicon" onClick="location.href='<?php echo($action == "main")?"index.php?".$scp->queryString."&action=admin":"index.php?".$scp->queryString."&action=main";?>'"><img src="users.svg" title="<?php echo SCP_OVERVIEW_LINK;?>" alt="<?php echo SCP_OVERVIEW_LINK;?>"> 
      <span>
         <a href="#"><?php echo($action == "main")?"<a href=\"index.php?".$scp->queryString."&action=admin\">".SCP_ADMIN_LINK."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_OVERVIEW_LINK."</a>";?></a>
      </span>
    </div>
    <?php endif;?>
    <div id="menuicon" onClick="location.href='<?php echo (!$scp->getAdmin()->isAllView)?"index.php?".$scp->queryString."&action=allview":"index.php?".$scp->queryString."&action=main";?>'"><img src="user1.svg" title="<?php echo SCP_DOMAINVIEW;?>" alt="<?php echo SCP_DOMAINVIEW;?>"> 
      <span>
         <a href="#"><?php echo (!$scp->getAdmin()->isAllView)?"<a href=\"index.php?".$scp->queryString."&action=allview\">".SCP_OVERVIEW."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_DOMAINVIEW."</a>";?></a>
      </span>
    </div>
   </div>
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="center" valign="middle"> 
    <td width="30">&nbsp; </td>
    <td width="135"> 
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#" onClick="adminView('gl_settings')"><?php echo SCP_ADMIN_GREYLISTING;?></a></td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="135"> 
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#" onClick="adminView('dnsbl_settings')"><?php echo SCP_ADMIN_DNSBL_LISTS;?></a></td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="135"> 
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#" onClick="adminView('allg_settings')"><?php echo SCP_ADMIN_ALLG_SETTINGS;?></a></td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="135">
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#" onClick="adminView('multi_right')"><?php echo SCP_ADMIN_MULTI_RIGHTS;?></a></td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="135">
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#" onClick="adminView('report_settings')"><?php echo SCP_ADMIN_REPORTS;?></a></td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td>&nbsp; </td>
    <td width="135"> 
    <?php if(!$scp->getPleskManagerView()):?>
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#"><?php echo($action == "main")?"<a href=\"index.php?".$scp->queryString."&action=admin\">".SCP_ADMIN_LINK."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_OVERVIEW_LINK."</a>";?></a> 
          </td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    <?php endif;?>
    </td>
    <td width="135"> 
      <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td width="11" background="menu_l.png" height="20">&nbsp;</td>
          <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#"><?php echo (!$scp->getAdmin()->isAllView)?"<a href=\"index.php?".$scp->queryString."&action=allview\">".SCP_OVERVIEW."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_DOMAINVIEW."</a>";?></a></td>
          <td width="11" background="menu_r.png" height="20">&nbsp;</td>
        </tr>
      </table>
    </td>
 </tr>
</table>
  <br>
-->
<form name="scpedit" method="post" action="index.php?<?php echo $_SERVER["QUERY_STRING"]; ?>&action=admin">
<div id="gl_settings" style="display:block">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td colspan="3"><b><?php echo SCP_GREYLIST_SPAMDYKE_CONF;?><br>
        <br>
        </b></td>
    </tr>
    <tr> 
      <td width="15%" height="25"><?php echo SCP_GRAYLIST_LEVEL;?></td>
      <td width="15%" height="25">
       <select name="graylist-level"> 
        <option value="always-create-dir" <?php if($scp->spamDykeConf["graylist-level"]=="always-create-dir")echo "selected";?>><?php echo SCP_GRAYLIST_LEVEL_ALWAYS_CREATE_DIR;?></option>
        <option value="always" <?php if($scp->spamDykeConf["graylist-level"]=="always")echo "selected";?>><?php echo SCP_GRAYLIST_LEVEL_ALWAYS;?></option>
        <option value="none" <?php if($scp->spamDykeConf["graylist-level"]=="none")echo "selected";?>><?php echo SCP_GRAYLIST_LEVEL_NONE;?></option>
       </select>
      </td>
     <td width="70%" height="25"></td>
    </tr>
    <tr> 
      <td width="15%" height="25"><?php echo SCP_GRAYLIST_MIN_SECS;?></td>
      <td width="15%" height="25"> 
      <input type="text" name="graylist-min-secs" size="10" value="<?php echo $scp->spamDykeConf["graylist-min-secs"];?>"> <?php echo SCP_GRAYLIST_SECS;?>
      </td>
      <td width="70%" height="25" class="ital"><?php echo SCP_GRAYLIST_MIN_SECS_HELP;?></td>
    </tr>
    <tr> 
      <td width="15%" height="25"><?php echo SCP_GRAYLIST_MAX_SECS;?></td>
      <td width="15%" height="25"> 
        <input type="text" name="graylist-max-secs" size="10" value="<?php echo $scp->spamDykeConf["graylist-max-secs"];?>"> <?php echo SCP_GRAYLIST_SECS;?>
      </td>
      <td width="70%" height="25" class="ital"><?php echo SCP_GRAYLIST_MAX_SECS_HELP;?></td>
    </tr>
    <tr> 
      <td colspan="3" height="25"><br>
        <input type="submit" name="spamdyke_conf" value="<?php echo SCP_EDIT;?>">
      </td>
    </tr>
  </table>
<hr size="1">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <?php if($scp->spamDykeConf["graylist-level"]=="always"):?>  
    <tr> 
      <td colspan="2"><b><?php echo SCP_EVENT_HANDLER_HEAD;?><br>
        <br>
        </b></td>
    </tr>
    <tr> 
      <td width="20%" height="25"><?php echo SCP_EVENT_HANDLER_CREATE;?></td>
      <td width="80%" height="25"> 
        <?php $scp->getAdmin()->getEventHandler("create");?>
      </td>
    </tr>
    <tr> 
      <td width="20%" height="25"><?php echo SCP_EVENT_HANDLER_UPDATE;?></td>
      <td width="80%" height="25"> 
        <?php $scp->getAdmin()->getEventHandler("update");?>
      </td>
    </tr>
    <tr> 
      <td width="20%" height="25"><?php echo SCP_EVENT_HANDLER_DELETE;?></td>
      <td width="80%" height="25"> 
        <?php $scp->getAdmin()->getEventHandler("delete");?>
      </td>
    </tr>
    <tr> 
      <td colspan="2"><b>&nbsp;<br>
        <br>
        </b></td>
    </tr>
    <?php endif;?>    
    <tr> 
      <td colspan="2"><b><?php echo SCP_EVENT_HANDLER_RIGHTS_HEAD;?><br>
        <br>
        </b></td>
    </tr>
    <tr> 
      <td width="20%" height="30"><?php echo SCP_EVENT_HANDLER_RIGHTS_CREATE;?></td>
      <td width="80%" height="30"> 
        <?php $scp->getAdmin()->getEventHandler("create_rights");?>
      </td>
    </tr>
    <tr> 
      <td width="20%" height="30"><?php echo SCP_EVENT_HANDLER_RIGHTS_UPDATE;?></td>
      <td width="80%" height="30"> 
        <?php $scp->getAdmin()->getEventHandler("update_rights");?>
      </td>
    </tr>
    <tr> 
      <td width="20%" height="30"><?php echo SCP_EVENT_HANDLER_RIGHTS_DELETE;?></td>
      <td width="80%" height="30"> 
        <?php $scp->getAdmin()->getEventHandler("delete_rights");?>
      </td>
    </tr>
    <tr> 
      <td colspan="2" height="25"><br>
        <input type="submit" name="e_handler" value="<?php echo SCP_EDIT;?>">
      </td>
    </tr>
  </table>
</div>
<div id="dnsbl_settings" style="display:none">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td colspan="3"><b><?php echo SCP_DNSBL_SPAMDYKE_CONF;?><br>
        <br>
        </b></td>
    </tr>
    <tr>
    <td width="20"></td>
    <td width="30"><u><?php echo SCP_DNSBL_SPAMDYKE_ACTIVE;?></u></td>
    <td><u><?php echo SCP_DNSBL_SPAMDYKE_LIST;?></u></td>
    </tr>
    <?php $scp->getAdmin()->getDnsblList($scp->spamDykeConf);?>
    <tr>
    <td colspan="2"><?php echo SCP_DNSBL_SPAMDYKE_NEW;?></td>
    <td><input type="text" name="new_dnsbl" size="50"></td>
    </tr>
        <tr> 
      <td colspan="3" height="10"></td>
    </tr>
        <tr> 
      <td colspan="3"><b><?php echo SCP_DNSWL_SPAMDYKE_CONF;?><br>
        <br>
        </b></td>
    </tr>
    <tr>
    <td width="20"></td>
    <td width="30"><u><?php echo SCP_DNSWL_SPAMDYKE_ACTIVE;?></u></td>
    <td><u><?php echo SCP_DNSWL_SPAMDYKE_LIST;?></u></td>
    </tr>
    <?php $scp->getAdmin()->getDnswlList($scp->spamDykeConf);?>
    <tr>
    <td colspan="2"><?php echo SCP_DNSWL_SPAMDYKE_NEW;?></td>
    <td><input type="text" name="new_dnswl" size="50"></td>
    </tr>
        <tr> 
      <td colspan="3" height="10"></td>
    </tr>
        <tr> 
      <td colspan="3"><b><?php echo SCP_DNSRHSBL_SPAMDYKE_CONF;?><br>
        <br>
        </b></td>
    </tr>
    <tr>
    <td width="20"></td>
    <td width="30"><u><?php echo SCP_DNSRHSBL_SPAMDYKE_ACTIVE;?></u></td>
    <td><u><?php echo SCP_DNSRHSBL_SPAMDYKE_LIST;?></u></td>
    </tr>
    <?php $scp->getAdmin()->getDnsrhsblList($scp->spamDykeConf);?>
    <tr>
    <td colspan="2"><?php echo SCP_DNSRHSBL_SPAMDYKE_NEW;?></td>
    <td><input type="text" name="new_dnsrhsbl" size="50"></td>
    </tr>

    <tr> 
      <td colspan="3" height="25"><br>
        <input type="submit" name="spamdyke_conf" value="<?php echo SCP_EDIT;?>">
      </td>
    </tr>
  </table>
</div>
<div id="allg_settings" style="display:none">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td colspan="3"><b><?php echo SCP_ALLG_SPAMDYKE_INFOS;?><br>
        <br>
        </b></td>
    </tr>
        <tr>
    <td width="20"></td>
    <td width="30" align="center" class="bold" id="spamdyke_pids"><?php $scp->getAdmin()->actSpamdykeProc();?></td>
    <td><?php echo SCP_ACTIVE_SPAMDYKE_PROC;?> <input type="submit" name="killspamdyke" value="<?php echo SCP_KILL_ALL_SPAMDYKE;?>"></td></tr>
        <tr>
    <td colspan="2" class="bold" width="30" align="center"><?php echo SCP_ALLG_SPAMDYKE_INFOS_V;?></td>
    <td><?php $scp->getAdmin()->getSpamdykeVer();?></td>
    <tr>
              <td colspan="3"><hr size="1" width="450"></td>
    </tr>
    
    <tr> 
      <td colspan="3"><b><?php echo SCP_SYSTEM_INTEGRATION;?><br>
        <br>
        </b></td>
    </tr>
    <tr>
    <td width="20"></td>
    </tr>
    <tr> 
      <td></td>
      <td class="rightconf"> 
      <input type="checkbox"  name="spamdykeWatchdog" size="1" value="1" <?php echo ($scp->getAdmin()->spamdykeWatchdog)?"checked":"";?>>
      </td>

      <td><?php echo SCP_SPAMDYKE_WATCHDOG;?></td>
      
    </tr>
    <tr>
              <td colspan="3"><hr size="1" width="450"></td>
    </tr>    

<tr> 
      <td colspan="3"><b><?php echo SCP_ALLG_SPAMDYKE_CONF;?><br>
        <br>
        </b></td>
    </tr>
    <?php $scp->getAdmin()->getOtherSettings($scp->spamDykeConf,"4.0.10");?>
    <tr> 
      <td></td>
      <td> 
      <input type="text" name="greeting-delay-secs" size="2" value="<?php echo $scp->spamDykeConf["greeting-delay-secs"];?>">
      </td>
      <td><?php echo SCP_GRAYLIST_SECS.' '.SCP_GREETING_DELAY;?></td>
    </tr>
    <tr> 
      <td></td>
      <td> 
      <input type="text" name="connection-timeout-secs" size="2" value="<?php echo $scp->spamDykeConf["connection-timeout-secs"];?>">
      </td>
      <td><?php echo SCP_GRAYLIST_SECS.' '.SCP_CONNECTION_TIMEOUT_SECS;?></td>
    </tr>
    <tr> 
      <td></td>
      <td> 
      <input type="text" name="idle-timeout-secs" size="2" value="<?php echo $scp->spamDykeConf["idle-timeout-secs"];?>">
      </td>
      <td><?php echo SCP_GRAYLIST_SECS.' '.SCP_IDLE_TIMEOUT_SECS;?></td>
    </tr>
    <tr> 
      <td></td>
      <td> 
      <input type="text" name="max-recipients" size="2" value="<?php echo $scp->spamDykeConf["max-recipients"];?>">
      </td>
      <td><?php echo SCP_MAXRECIPIENTS;?></td>
    </tr>    
     <?php $scp->getAdmin()->getOtherSettings($scp->spamDykeConf,"4.1.0");?>
     <?php $scp->getAdmin()->getOtherSettings($scp->spamDykeConf,"5.0.0");?>
     <tr> 
      <td></td>
      <td> 
      <input type="checkbox" name="remote_user" size="1" value="1" <?php echo (is_array($scp->getAdmin()->remoteStates["user"]))?"checked":"";?>>
      </td>
      <td><?php echo SCP_REMOTE_SPAMDYKE_TO_USER;?></td>
    </tr>
     <tr> 
      <td></td>
      <td> 
      <input type="checkbox" name="remote_admin" size="1" value="1" <?php echo (!empty($scp->getAdmin()->remoteStates["admin"]))?"checked":"";?>>
      </td>
      <td><?php echo SCP_REMOTE_SPAMDYKE_TO_ADMIN;?>
      <?php 
       if(!empty($scp->getAdmin()->remoteStates["admin"])){
          echo "<b> (SuperToken: ".trim($scp->xtea->Decrypt($scp->getAdmin()->remoteStates["admin"])).")</b>";       	
          }
      ?>	
      	</td>
    </tr>
    <tr>
              <td colspan="3"><hr size="1" width="450"></td>
    </tr>
     <tr> 
      <td></td>
      <td> 
<?php $scp->getAdmin()->showReseller();?>
      </td>
      <td><?php echo SCP_RESELLER_RIGHTS;?></td>
    </tr>
    <tr>
              <td colspan="3"><hr size="1" width="450"></td>
    </tr>
    
    <tr> 
      <td colspan="3"><b><?php echo SCP_PLESK_MENU_INTEGRATION;?><br>
        <br>
        </b></td>
    </tr>
    <tr>
    <td width="20"></td>
    </tr>
    <tr> 
      <td></td>
      <td> 
      <input type="checkbox"  name="psaIntegration" size="1" value="1" <?php echo ($scp->getAdmin()->psaIntegration)?"checked":"";?>>
      </td>
      <td><?php echo SCP_PSA_ADMIN_LINK;?></td>
    </tr>
    <tr>
              <td colspan="3"><hr size="1" width="450"></td>
    </tr>
    
    <tr> 
      <td colspan="3"><b><?php echo SCP_SPAM_ABUSE_REPORT;?><br>
        <br>
        </b></td>
    </tr>
        <tr> 
      <td></td>
      <td> 
      <?php echo SCP_SPAM_ABUSE_REPORT_SENDER;?>
      </td>
      <td><input type="text" name="spam-abuse[sender]" size="50" value="<?php echo $scp->getAdmin()->abuseDetails["sender"];?>"></td>
    </tr>
     <tr> 
      <td></td>
      <td> 
      <?php echo SCP_SPAM_ABUSE_REPORT_TITEL;?>
      </td>
      <td><input type="text" name="spam-abuse[titel]" size="50" value="<?php echo $scp->getAdmin()->abuseDetails["titel"];?>"></td>
    </tr>
     <tr> 
      <td></td>
      <td style="vertical-align:top"> 
      <?php echo SCP_SPAM_ABUSE_REPORT_TPL;?>
      </td>
      <td><textarea name="spam-abuse[template]" cols="50" rows="10"><?php echo $scp->getAdmin()->abuseDetails["template"];?></textarea><br>
      {SPAMLINE} = <?php echo SCP_SPAM_ABUSE_REPORT_SPAMMAIL;?>
      </td>
    </tr>
        <tr> 
      <td colspan="3" height="25"><br>
        <input type="submit" name="spamdyke_conf" value="<?php echo SCP_EDIT;?>">
      </td>
    </tr>
  </table>
</div>
<div id="multi_right" style="display:none">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td colspan="4"><b><?php echo SCP_MULTI_RIGHTS_SPAMDYKE_CONF;?><br>
        <br>
        </b></td>
    </tr>
        <tr>
    <tr>
    <td colspan="2"><u><?php echo SCP_MULTI_RIGHTS_DOMAIN;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('gl',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('gl',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_GREYLISTING;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('live',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('live',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LIVE_COUNTRY_ALLOW;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('ip',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('ip',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LISTS_IP;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('sender',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('sender',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LISTS_SENDER;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('recipient',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('recipient',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LISTS_RECIPIENT;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('rdns',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('rdns',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LISTS_RDNS;?></u></td>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('keywords',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('keywords',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LISTS_KEYWORDS;?></u></td>
    <?php if(array_key_exists("header-blacklist-file",$scp->spamDykeConf)):?>
    <td width="15" align="right" valign="middle"><a href="javascript:toggleBox('header',true)"><img src="toggleAllNone.gif" border="0" alt="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>" title="<?php echo SCP_ADMIN_ALLNONE_SELECTION;?>"></a><br><img src="pixel.gif" height="1" border="0"><br><a href="javascript:toggleBox('header',false)"><img src="toggle.gif" border="0" alt="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>" title="<?php echo SCP_ADMIN_TOGGLE_SELECTION;?>"></a></td>
    <td><u><?php echo SCP_LISTS_HEADER;?></u></td>
    <?php endif;?>
    </tr>
    
    <?php echo $scp->getAdmin()->viewAllDomains($scp->spamDykeConf);?>

    <tr> 
      <td colspan="3" height="25"><br>
        <input type="submit" name="spamdyke_conf" value="<?php echo SCP_EDIT;?>">
      </td>
    </tr>
  </table>
</div>
<div id="report_settings" style="display:none">
  <table width="700" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td colspan="3"><b><?php echo SCP_REPORT_SPAMDYKE_SETTINGS;?><br>
        <br>
        </b></td>
    </tr>
     <tr> 
      <td></td>
      <td width="20"> 
      <input type="checkbox" name="report_on" size="1" value="1" <?php echo ($scp->getAdmin()->isReportOn)?"checked":"";?> onClick="$('#reports_details').slideToggle('slow')";>
      </td>
      <td width="680"><?php echo SCP_REPORT_SPAMDYKE_ONOFF;?></td>
    </tr>
    </table>
    <table width="700" border="0" cellspacing="2" cellpadding="2" id="reports_details" style="display:<?php echo ($scp->getAdmin()->isReportOn)?"block":"none";?>">
    <tr> 
      <td colspan="3"><hr></td>
    </tr>
    <tr> 
      <td></td>
      <td width="20"> 
      <input type="checkbox" name="report_admin" size="1" value="1" onClick="document.scpedit.report_admin_email.disabled=(this.checked)?false:true" <?php echo (is_array($scp->getAdmin()->reportStates["admin"]))?"checked":"";?>>
      </td>
      <td width="680"><?php echo SCP_REPORT_SPAMDYKE_TO_ADMIN;?> - <i><?php echo SCP_REPORT_SPAMDYKE_TO_ADMIN_EMAIL;?>: <input type="text" name="report_admin_email" <?php echo (is_array($scp->getAdmin()->reportStates["admin"]))?"":"disabled";?> size="25" value="<?php echo $scp->getAdmin()->reportStates["admin"][0];?>"></i></td>
    </tr>
    <tr> 
      <td></td>
      <td> 
      <input type="checkbox" name="report_user" size="1" value="1" <?php echo (is_array($scp->getAdmin()->reportStates["user"]))?"checked":"";?>>
      </td>
      <td><?php echo SCP_REPORT_SPAMDYKE_TO_USER;?></td>
    </tr>
  </table>
    <table width="700" border="0" cellspacing="2" cellpadding="2">
    <tr> 
        <td colspan="3" height="25"><br>
        <input type="submit" name="spamdyke_conf" value="<?php echo SCP_EDIT;?>">
      </td>
    </tr>
  </table>
</div>
<input type="hidden" name="saveAdmView">
</form>
<?php $scp->getAdmin()->viewAdmPart($_POST["saveAdmView"]);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr align="center" valign="middle"> 
    <td height="1" bgcolor="000000"></td>
  </tr>
  <tr align="center" valign="middle"> 
    <td height="5">&copy; 2008-2018 <a href="http://www.haggybear.de">Matthias Hackbarth</a></td>
  </tr>
</table>
<?php if($scp->getAdmin()->reloadLeftFrame):?>
<script type="text/javascript">
top.document.getElementById("leftFrame").src = "/left.php3"
</script>
<?php endif;?>