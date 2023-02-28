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
   <div id="menuslot">
    <div id="menuicon"><img id="m_on" src="menu.svg" onClick="toggleMenu()"><img id="m_off" src="cancel.png" onClick="toggleMenu()" style="display:none"> <span>Menu</span></div>
    <div id="menuicon"><img src="Transparent.gif"></div>
    <div id="menuicon" class="<?php echo $scp->plesk_fe_props["einstellungen_css"];?>" data-rel="einstellungen_fe_lnk" onClick="viewPart('einstellungen_fe')"><img src="settings.svg" title="<?php echo SCP_SETTINGS_LINK;?>" alt="<?php echo SCP_SETTINGS_LINK;?>"> 
      <span>
         <a href="#"><?php echo SCP_SETTINGS_LINK;?></a>
      </span>
    </div>
    <div id="menuicon" class="<?php echo $scp->plesk_fe_props["filter_css"];?>" data-rel="filter_fe_lnk" onClick="viewPart('filter_fe')"><img src="filter.svg" title="<?php echo SCP_FILTER_LINK;?>" alt="<?php echo SCP_FILTER_LINK;?>"> 
      <span>
         <a href="#"><?php echo SCP_FILTER_LINK;?></a>
      </span>
    </div>

    <?php if(($scp->plesk_session->chkLevel(IS_ADMIN)||$scp->getAdmin()->checkResellerRight()) && !$scp->getPleskManagerView()):?>
    <?php if(!$scp->getAdmin()->isAllView):?> 
     <div id="menuicon" class="<?php echo $scp->plesk_fe_props["rechte_css"];?>" data-rel="rechte_fe_lnk" onClick="viewPart('rechte_fe')"><img src="lock.svg" title="<?php echo SCP_RIGHTS_LINK;?>" alt="<?php echo SCP_RIGHTS_LINK;?>"> 
      <span>
          <a href="#"><?php echo SCP_RIGHTS_LINK;?></a>
      </span>
    </div>
   <?php else:?>
     <div id="menuicon"><img src="lock-grey.svg" title="<?php echo SCP_RIGHTS_LINK;?>" alt="<?php echo SCP_RIGHTS_LINK;?>"> 
      <span>
          <font color="#999999"><?php echo SCP_RIGHTS_LINK;?></font>
      </span>
    </div>
   <?php endif;?>
   <?php endif;?>
    <div id="menuicon"><img src="Transparent.gif"></div>
    <?php if($scp->plesk_session->chkLevel(IS_ADMIN) && !$scp->getPleskManagerView()):?>
    <div id="menuicon"><img src="user<?php echo (!$scp->getAdmin()->isAllView)?"s":"1";?>.svg" onClick="location.href='index.php?<?php echo $scp->queryString;?>&action=<?php echo (!$scp->getAdmin()->isAllView)?"allview":"main";?>'" title="<?php echo (!$scp->getAdmin()->isAllView)?SCP_OVERVIEW:SCP_DOMAINVIEW;?>" alt="<?php echo (!$scp->getAdmin()->isAllView)?SCP_OVERVIEW:SCP_DOMAINVIEW;?>"> 
      <span>
         <a href="#"><?php echo (!$scp->getAdmin()->isAllView)?"<a href=\"index.php?".$scp->queryString."&action=allview\">".SCP_OVERVIEW."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_DOMAINVIEW."</a>";?></a>
      </span>
   </div>
   <?php endif;?>
     <?php if($scp->plesk_session->chkLevel(IS_ADMIN)):?>
    <div id="menuicon"><img src="gear.svg" onClick="location.href='index.php?<?php echo $scp->queryString;?>&action=<?php echo ($action == "main")?"admin":"main";?>'" title="<?php echo ($action == "main")?SCP_ADMIN_LINK:SCP_OVERVIEW_LINK;?>" alt="<?php echo ($action == "main")?SCP_ADMIN_LINK:SCP_OVERVIEW_LINK;?>"> 
      <span>
        <a href="#"><?php echo($action == "main")?"<a href=\"index.php?".$scp->queryString."&action=admin\">".SCP_ADMIN_LINK."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_OVERVIEW_LINK."</a>";?>
      </span>
   </div>
   <?php endif;?>

   </div>
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr align="center" valign="middle"> 
      <td width="30">&nbsp; </td>
      <td width="135"> 
        <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
          <tr> 
            <td width="11" background="menu_l.png" height="20">&nbsp;</td>
            <td bgcolor="#DDDDDD" align="center" valign="middle" height="20">
             <a href="#" class="<?php echo $scp->plesk_fe_props["einstellungen_css"];?>" id="einstellungen_fe_lnk" onClick="viewPart('einstellungen_fe')"><?php echo SCP_SETTINGS_LINK;?></a>
             </td>
            <td width="11" background="menu_r.png" height="20">&nbsp;</td>
          </tr>
        </table>
      </td>
      <td width="135"> 
        <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
          <tr> 
            <td width="11" background="menu_l.png" height="20">&nbsp;</td>
            <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#" class="<?php echo $scp->plesk_fe_props["filter_css"];?>" id="filter_fe_lnk" onClick="viewPart('filter_fe')"><?php echo SCP_FILTER_LINK;?></a></td>
            <td width="11" background="menu_r.png" height="20">&nbsp;</td>
          </tr>
        </table>
      </td>
      <td width="135">
        <?php if(($scp->plesk_session->chkLevel(IS_ADMIN)||$scp->getAdmin()->checkResellerRight()) && !$scp->getPleskManagerView()):?>
        <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
          <tr> 
            <td width="11" background="menu_l.png" height="20">&nbsp;</td>
            <td bgcolor="#DDDDDD" align="center" valign="middle" height="20">
            <?php if(!$scp->getAdmin()->isAllView):?>
              <a href="#" class="<?php echo $scp->plesk_fe_props["rechte_css"];?>" id="rechte_fe_lnk" onClick="viewPart('rechte_fe')"><?php echo SCP_RIGHTS_LINK;?></a>
            <?php else:?>
             <font color="#999999"><?php echo SCP_RIGHTS_LINK;?></font>
            <?php endif;?>
            </td>
            <td width="11" background="menu_r.png" height="20">&nbsp;</td>
          </tr>
        </table>
        <?php endif;?>
      </td>
      <td>&nbsp;</td>
      <td>&nbsp; </td>
      <td width="135">
         <?php if($scp->plesk_session->chkLevel(IS_ADMIN) && !$scp->getPleskManagerView()):?> 
        <table width="130" border="0" cellspacing="0" cellpadding="0" height="20">
          <tr> 
            <td width="11" background="menu_l.png" height="20">&nbsp;</td>
            <td bgcolor="#DDDDDD" align="center" valign="middle" height="20"><a href="#"><?php echo (!$scp->getAdmin()->isAllView)?"<a href=\"index.php?".$scp->queryString."&action=allview\">".SCP_OVERVIEW."</a>":"<a href=\"index.php?".$scp->queryString."&action=main\">".SCP_DOMAINVIEW."</a>";?></a></td>
            <td width="11" background="menu_r.png" height="20">&nbsp;</td>
          </tr>
        </table>
        <?php endif;?>
      </td>
      <td width="135"> 
        <?php if($scp->plesk_session->chkLevel(IS_ADMIN)):?>
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
      </tr>
  </table>
  <br>
//-->
<form name="scpedit" method="post" action="index.php?<?php echo $scp->queryString; ?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>" style="margin-bottom:0px">
<section id="no-more-tables">
<table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-bottom:1px solid #000000">
<thead>
  <tr> 
    <th><?php echo SCP_ALL_MAILS;?>: <?php echo $scp->blocked + $scp->allowed;?></th>
    <th><?php echo SCP_DROPPED_MAILS;?>: <?php echo $scp->blocked;?></th>
    <th><?php echo SCP_DROPPED_MAILS_GL;?>: <?php echo $scp->blockedType["DENIED_GRAYLISTED"]*1;?></th>
    <th><?php echo SCP_DROPPED_MAILS_OTHER;?>: <?php echo $scp->blocked - $scp->blockedType["DENIED_GRAYLISTED"];?></th>
    <th><?php echo SCP_ALLOWED;?>: <?php echo $scp->allowed;?></th>
    <th><?php echo SCP_SPAMRATE;?>: <?php echo $scp->getSpamrate();?> %</th>
  </tr>
</thead>
<tbody id="hideDummy">
  <tr> 
    <td data-title="<?php echo SCP_ALL_MAILS;?>"><?php echo $scp->blocked + $scp->allowed;?></td>
    <td data-title="<?php echo SCP_DROPPED_MAILS;?>"><?php echo $scp->blocked;?></td>
    <td data-title="<?php echo SCP_DROPPED_MAILS_GL;?>"><?php echo $scp->blockedType["DENIED_GRAYLISTED"]*1;?></td>
    <td data-title="<?php echo SCP_DROPPED_MAILS_OTHER;?>"><?php echo $scp->blocked - $scp->blockedType["DENIED_GRAYLISTED"];?></td>
    <td data-title="<?php echo SCP_ALLOWED;?>"><?php echo $scp->allowed;?></td>
    <td data-title="<?php echo SCP_SPAMRATE;?>"><?php echo $scp->getSpamrate();?> %</td>
  </tr>
</tbody>
</table>
<div id="einstellungen_fe" style="display:<?php echo $scp->plesk_fe_props["einstellungen_style"];?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-bottom:1px solid #000000">
     <thead>
      <tr>
        <?php if(!$scp->getAdmin()->isAllView):?><th><?php echo SCP_GREYLISTING;?></th><?php endif;?>
        <th><?php echo SCP_LISTS_IP;?></th>
        <th><?php echo SCP_LISTS_SENDER;?></th>
        <th><?php echo SCP_LISTS_RECIPIENT;?></th>
        <th><?php echo SCP_LISTS_RDNS;?></th>
        <th><?php echo SCP_LISTS_KEYWORDS;?></th>
        <?php if(array_key_exists("header-blacklist-file",$scp->spamDykeConf)):?>
                <th><?php echo SCP_LISTS_HEADER;?></th>
        <?php endif;?>
        <?php if(!$scp->getAdmin()->isAllView):?><th><?php echo SCP_DAILYREPORT;?></td><?php endif;?>
	<?php if(!$scp->getAdmin()->isAllView):?><th><a href="http://www.haggybear.de/download/scp-remote-example.zip" alt="<?php echo SCP_REMOTE_ACCESS_EXAMPLE;?>" title="<?php echo SCP_REMOTE_ACCESS_EXAMPLE;?>"><?php echo SCP_REMOTE_ACCESS;?></a></td><?php endif;?>
      </tr>
      </thead>
      <tbody>
      <tr valign="middle"> 
        <?php if(!$scp->getAdmin()->isAllView):?><td data-title="<?php echo SCP_GREYLISTING;?>"><?php $scp->checkForGreylisting($_POST,$scp->canUse("gl"));?></td><?php endif;?>
        <td data-title="<?php echo SCP_LISTS_IP;?>"><input type="button" class="button-add-big" onClick="toggleScreen('iplists')" value="<?php echo count($scp->allLists[$scp->whichList]["ip-whitelist"])."/".count($scp->allLists[$scp->whichList]["ip-blacklist"]);?>" title="<?php echo ($scp->canUse("ip"))?SCP_EDIT:SCP_SHOW;?>" style="background-image:url(<?php echo ($scp->canUse("ip"))?"edit":"eye";?>.png)"></td>
        <td data-title="<?php echo SCP_LISTS_SENDER;?>"><input type="button" class="button-add-big" onClick="toggleScreen('senderlists')" value="<?php echo count($scp->allLists[$scp->whichList]["sender-whitelist"])."/".count($scp->allLists[$scp->whichList]["sender-blacklist"]);?>" title="<?php echo ($scp->canUse("sender"))?SCP_EDIT:SCP_SHOW;?>" style="background-image:url(<?php echo ($scp->canUse("sender"))?"edit":"eye";?>.png)"></td>
        <td data-title="<?php echo SCP_LISTS_RECIPIENT;?>"><input type="button" class="button-add-big" onClick="toggleScreen('recipientlists')" value="<?php echo count($scp->allLists[$scp->whichList]["recipient-whitelist"])."/".count($scp->allLists[$scp->whichList]["recipient-blacklist"]);?>" title="<?php echo ($scp->canUse("recipient"))?SCP_EDIT:SCP_SHOW;?>" style="background-image:url(<?php echo ($scp->canUse("recipient"))?"edit":"eye";?>.png)"></td>
        <td data-title="<?php echo SCP_LISTS_RDNS;?>"><input type="button" class="button-add-big" onClick="toggleScreen('rdnslists')" value="<?php echo count($scp->allLists[$scp->whichList]["rdns-whitelist"]).'/'.count($scp->allLists[$scp->whichList]["rdns-blacklist"]);?>" title="<?php echo ($scp->canUse("rdns"))?SCP_EDIT:SCP_SHOW;?>" style="background-image:url(<?php echo ($scp->canUse("rdns"))?"edit":"eye";?>.png)"></td>
        <td data-title="<?php echo SCP_LISTS_KEYWORDS;?>"><input type="button" class="button-add-big" onClick="toggleScreen('keywordlists')" value="<?php echo count($scp->allLists[$scp->whichList]["ip-in-rdns-keyword-blacklist"]);?>" title="<?php echo ($scp->canUse("keyword"))?SCP_EDIT:SCP_SHOW;?>" style="background-image:url(<?php echo ($scp->canUse("keyword"))?"edit":"eye";?>.png)"></td>
        <?php if(array_key_exists("header-blacklist-file",$scp->spamDykeConf)):?>
        <td data-title="<?php echo SCP_LISTS_HEADER;?>"><input type="button" class="button-add-big" onClick="toggleScreen('headerlists')"value="<?php echo count($scp->allLists[$scp->whichList]["header-blacklist"]);?>"  title="<?php echo ($scp->canUse("header"))?SCP_EDIT:SCP_SHOW;?>" style="background-image:url(<?php echo ($scp->canUse("header"))?"edit":"eye";?>.png)"></td>
        <?php endif;?>
        <?php if(!$scp->getAdmin()->isAllView):?><td data-title="<?php echo SCP_DAILYREPORT;?>"><?php $scp->checkForDailyReport($_POST);?></td><?php endif;?>
	<?php if(!$scp->getAdmin()->isAllView):?><td data-title="<?php echo SCP_REMOTE_ACCESS_EXAMPLE;?>"><?php $scp->checkForRemoteAccess($_POST);?></td><?php endif;?>
      </tr>
      </tbody>
    </table>
</table>
</div>
<?php if($scp->plesk_session->chkLevel(IS_ADMIN) || $scp->getAdmin()->checkResellerRight()):?>
<div id="rechte_fe" style="display:<?php echo $scp->plesk_fe_props["rechte_style"];?>">
<?php if(!$scp->getAdmin()->isAllView):?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-bottom:1px solid #000000">
   <thead>
      <tr>
        <td><?php echo SCP_GREYLISTING;?></td>
        <td><?php echo SCP_LIVE_COUNTRY_ALLOW;?></td>
        <td><?php echo SCP_LISTS_IP;?></td>
        <td><?php echo SCP_LISTS_SENDER;?></td>
        <td><?php echo SCP_LISTS_RECIPIENT;?></td>
        <td><?php echo SCP_LISTS_RDNS;?></td>
        <td><?php echo SCP_LISTS_KEYWORDS;?></td>
        <?php if(array_key_exists("header-blacklist-file",$scp->spamDykeConf)):?>
        <td><?php echo SCP_LISTS_HEADER;?></td>
        <?php endif;?>
        <td></td>
     </tr>
    </thead>
    <tbody>
      <tr>
        <td data-title="<?php echo SCP_GREYLISTING;?>"><?php $scp->dieRechte->getDomainRight('gl');?></td>
        <td data-title="<?php echo SCP_LIVE_COUNTRY_ALLOW;?>"><?php $scp->dieRechte->getDomainRight('live');?></td>
        <td data-title="<?php echo SCP_LISTS_IP;?>"><?php $scp->dieRechte->getDomainRight('ip');?></td>
        <td data-title="<?php echo SCP_LISTS_SENDER;?>"><?php $scp->dieRechte->getDomainRight('sender');?></td>
        <td data-title="<?php echo SCP_LISTS_RECIPIENT;?>"><?php $scp->dieRechte->getDomainRight('recipient');?></td>
        <td data-title="<?php echo SCP_LISTS_RDNS;?>"><?php $scp->dieRechte->getDomainRight('rdns');?></td>
        <td data-title="<?php echo SCP_LISTS_KEYWORDS;?>"><?php $scp->dieRechte->getDomainRight('keywords');?></td>
        <?php if(array_key_exists("header-blacklist-file",$scp->spamDykeConf)):?>
        <td data-title="<?php echo SCP_LISTS_HEADER;?>"><?php $scp->dieRechte->getDomainRight('header');?></td>
        <?php endif;?>
      <td data-title="&nbsp;"><input type="submit" name="adminsave" value="<?php echo SCP_SAVE_BTN;?>" class="button-add-big" style="background-image:url(save.png)"></td>
      </tr>
    </tbody>
  </table>
<?php endif;?> 
</div>
<?php endif;?> 
</form>
<div id="filter_fe" style="display:<?php echo $scp->plesk_fe_props["filter_style"];?>">
<form name="anzeige" method="post" action="index.php?<?php $scp->getQueryString(); ?>&todo=anzeige<?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
  <table width="100%" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #000000">
    <thead>
     <tr>
      <td align="center"><?php echo SCP_LOGFROM;?></td>
      <td align="center"><?php echo SCP_DIRECTION;?></td>
      <td align="center"><?php echo SCP_SHOW_ALLADDR;?></td>
      <td align="center"><?php echo SCP_PER_PAGE;?></td>
      <?php if($scp->canUse("live")):?>  
      <td align="center"><?php echo SCP_LIVE_COUNTRY;?></td>
      <?php endif;?>  
      <td align="center"><?php echo SCP_SET_FILTER;?></td>
      <td align="center"><?php echo SCP_SHOW_PASSED;?></td>
     </tr>
    </thead>
    <tbody>
      <tr>
      <td data-title="<?php echo SCP_LOGFROM;?>" align="center"><?php $scp->getLogDate();?></td>
      <td data-title="<?php echo SCP_DIRECTION;?>" align="center"><?php $scp->getRichtungOpt();?></td>
      <td data-title="<?php echo SCP_SHOW_ALLADDR;?>" align="center"><?php $scp->getAlleAddrOpt();?></td>
      <td data-title="<?php echo SCP_PER_PAGE;?>" align="center"><?php $scp->getPerPageOpt();?></td>
      <?php if($scp->canUse("live")):?>  
      <td data-title="<?php echo SCP_LIVE_COUNTRY;?>" align="center"><?php $scp->getLiveCountryOpt();?></td>
      <?php endif;?>  
      <td data-title="<?php echo SCP_SET_FILTER;?>" align="center"><?php $scp->getFilterOpt();?></td>
      <td data-title="<?php echo SCP_SHOW_PASSED;?>" align="center"><?php $scp->getGlPaarOpt();?></td>
      </tr>
    </tbody>
  </table>
<input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">
</form>
</div>
</section>
 <section id="no-more-tables">
<table width="100%" border="0" cellspacing="0" cellpadding="3" id="log_table">
<thead>
<tr class="bold">
<th><?php echo SCP_DIRECTION;?></th>
<th><?php echo SCP_SENDER;?></th>
<th><?php echo SCP_RECEIVE;?></th>
<th><?php echo SCP_REASON;?></th>
<th><?php echo SCP_ORG_IP;?></th>
<th><?php echo SCP_ORG_RDNS;?></th>
<th><?php echo SCP_SENDTIME;?></th>
</tr>
</thead>
<tbody>
<?php $scp->getLogTable();?>
</tbody>
</table>
</section>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr align="center" valign="middle"> 
    <td height="1" bgcolor="000000"></td>
  </tr>
  <tr align="center" valign="middle"> 
    <td height="5">&copy; 2008-2018 <a href="http://www.haggybear.de">Matthias Hackbarth</a></td>
  </tr>
</table>
<br>
<div id="iplists" class="invis">
<form name="setlists" method="post" action="index.php?<?php $scp->getQueryString();?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" width="320">&nbsp;</td>
    <td height="20" class="big bold" width="320" >[<a href="javascript://" onClick="toggleScreen('iplists')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="75" colspan="2" align="left" valign="top" class="rand"><?php echo SCP_EXPLAIN_IP;?><p style="margin-top:15px;text-align:center" class="fatred"><?php echo SCP_EXPLAIN_EXPIRES;?></p><br><div class="caldiv" style="display:none;position:absolute;width:95%"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="0" width="320"><?php echo SCP_WHITELISTS;?></td>
    <td height="0" width="320"><?php echo SCP_BLACKLISTS;?></td>
  </tr>
  <?php if(!$scp->dieRechte->isAllView):?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["ip-whitelist"]);?></textarea>
    </td>
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["ip-blacklist"]);?></textarea>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="200" width="320"> 
      <br><?php echo SCP_WHITELISTS_CUSTOM;?><br>
      <textarea name="whitelist" rows="10" cols="30" <?php echo ($scp->canUse("ip"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["ip-whitelist"]);?></textarea>
    </td>
    <td height="200" width="320"> 
      <br><?php echo SCP_BLACKLISTS_CUSTOM;?><br>
      <textarea name="blacklist" rows="10" cols="30" <?php echo ($scp->canUse("ip"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["ip-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php else:?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="325" width="320"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["ip-whitelist"]);?></textarea>
    </td>
    <td height="325" width="320"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="blacklist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["ip-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php endif;?>
  <tr bgcolor="#FFFFFF"> 
    <td height="40" colspan="2" align="center" valign="middle"> 
      <?php if($scp->canUse("ip")):?>
      <input type="hidden" name="type" value="ip">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">  
        <input type="hidden" name="anz_alleAddr" value="<?php echo $scp->anzVars["anz_alleAddr"];?>">  
        <input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">    
      <input type="submit" name="savelist" value="<?php echo SCP_SAVE_BTN;?>">
      <?php endif;?>
    </td>
  </tr>
</table>
</form>
</div> 
<div id="rdnslists" class="invis">
<form name="setlists" method="post" action="index.php?<?php $scp->getQueryString();?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" colspan="2" class="big bold" width="640" >[<a href="javascript://" onClick="toggleScreen('rdnslists')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="75" colspan="2" align="left" valign="top" class="rand"><?php echo SCP_EXPLAIN_RDNS;?><p style="margin-top:15px;text-align:center" class="fatred"><?php echo SCP_EXPLAIN_EXPIRES;?></p><br><div class="caldiv" style="display:none;position:absolute;width:95%"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="0" width="320"><?php echo SCP_WHITELISTS;?></td>
    <td height="0" width="320"><?php echo SCP_BLACKLISTS;?></td>
  </tr>
  <?php if(!$scp->dieRechte->isAllView):?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["rdns-whitelist"]);?></textarea>
    </td>
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["rdns-blacklist"]);?></textarea>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="200" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_CUSTOM;?><br>
      <textarea name="whitelist" rows="10" cols="30" <?php echo ($scp->canUse("rdns"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["rdns-whitelist"]);?></textarea>
    </td>
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_CUSTOM;?><br>
      <textarea name="blacklist" rows="10" cols="30" <?php echo ($scp->canUse("rdns"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["rdns-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php else:?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="325" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["rdns-whitelist"]);?></textarea>
    </td>
    <td height="325" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["rdns-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php endif;?>
  <tr bgcolor="#FFFFFF"> 
    <td height="40" align="center" valign="middle" colspan="2"> 
    <?php if($scp->canUse("rdns")):?>
      <input type="hidden" name="type" value="rdns">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">
        <input type="hidden" name="anz_alleAddr" value="<?php echo $scp->anzVars["anz_alleAddr"];?>">  
        <input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">
      <input type="submit" name="savelist" value="<?php echo SCP_SAVE_BTN;?>">
    <?php endif;?>   
    </td>
  </tr>
</table>
</form>
</div> 
<div id="senderlists" class="invis">
<form name="setlists" method="post" action="index.php?<?php $scp->getQueryString();?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" width="320">&nbsp;</td>
    <td height="20" class="big bold" width="320" >[<a href="javascript://" onClick="toggleScreen('senderlists')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="75" colspan="2" align="left" valign="top" class="rand"><?php echo SCP_EXPLAIN_SENDER;?><p style="margin-top:15px;text-align:center" class="fatred"><?php echo SCP_EXPLAIN_EXPIRES;?></p><br><div class="caldiv" style="display:none;position:absolute;width:95%"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="0" width="320"><?php echo SCP_WHITELISTS;?></td>
    <td height="0" width="320"><?php echo SCP_BLACKLISTS;?></td>
  </tr>
  <?php if(!$scp->dieRechte->isAllView):?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist_global" id="teets" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["sender-whitelist"]);?></textarea>
    </td>
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["sender-blacklist"]);?></textarea>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="200" width="320"> 
      <?php echo SCP_WHITELISTS_CUSTOM;?><br>
      <textarea name="whitelist" rows="10" cols="30" <?php echo ($scp->canUse("sender"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["sender-whitelist"]);?></textarea>
    </td>
    <td height="200" width="320"> 
      <?php echo SCP_BLACKLISTS_CUSTOM;?><br>
      <textarea name="blacklist" rows="10" cols="30" <?php echo ($scp->canUse("sender"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["sender-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php else:?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="325" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["sender-whitelist"]);?></textarea>
    </td>
    <td height="325" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["sender-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php endif;?>
  <tr bgcolor="#FFFFFF"> 
    <td height="40" colspan="2" align="center" valign="middle"> 
     <?php if($scp->canUse("sender")):?>
      <input type="hidden" name="type" value="sender">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">
        <input type="hidden" name="anz_alleAddr" value="<?php echo $scp->anzVars["anz_alleAddr"];?>">  
        <input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">
      <input type="submit" name="savelist" value="<?php echo SCP_SAVE_BTN;?>">
    <?php endif;?>
    </td>
  </tr>
</table>
</form>
</div> 
<div id="recipientlists" class="invis">
<form name="setlists" method="post" action="index.php?<?php $scp->getQueryString();?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" width="320">&nbsp;</td>
    <td height="20" class="big bold" width="320" >[<a href="javascript://" onClick="toggleScreen('recipientlists')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="75" colspan="2" align="left" valign="top" class="rand"><?php echo SCP_EXPLAIN_SENDER;?><p style="margin-top:15px;text-align:center" class="fatred"><?php echo SCP_EXPLAIN_EXPIRES;?></p><br><div class="caldiv" style="display:none;position:absolute;width:95%"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="0" width="320"><?php echo SCP_WHITELISTS;?></td>
    <td height="0" width="320"><?php echo SCP_BLACKLISTS;?></td>
  </tr>
  <?php if(!$scp->dieRechte->isAllView):?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["recipient-whitelist"]);?></textarea>
    </td>
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["recipient-blacklist"]);?></textarea>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="200" width="320"> 
      <br><?php echo SCP_WHITELISTS_CUSTOM;?><br>
      <textarea name="whitelist" rows="10" cols="30" <?php echo ($scp->canUse("recipient"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["recipient-whitelist"]);?></textarea>
    </td>
    <td height="200" width="320"> 
      <br><?php echo SCP_BLACKLISTS_CUSTOM;?><br>
      <textarea name="blacklist" rows="10" cols="30" <?php echo ($scp->canUse("recipient"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["recipient-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php else:?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="325" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_WHITELISTS_GLOBAL;?><br>
      <textarea name="whitelist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["recipient-whitelist"]);?></textarea>
    </td>
    <td height="325" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="blacklist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["recipient-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php endif;?>
  <tr bgcolor="#FFFFFF"> 
    <td height="40" colspan="2" align="center" valign="middle"> 
     <?php if($scp->canUse("recipient")):?>
      <input type="hidden" name="type" value="recipient">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">
        <input type="hidden" name="anz_alleAddr" value="<?php echo $scp->anzVars["anz_alleAddr"];?>">  
        <input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">
      <input type="submit" name="savelist" value="<?php echo SCP_SAVE_BTN;?>">
    <?php endif;?>
    </td>
  </tr>
</table>
</form>
</div> 
<div id="headerlists" class="invis">
<form name="setlists" method="post" action="index.php?<?php $scp->getQueryString();?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
<table width="320" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" class="big bold" width="320" >[<a href="javascript://" onClick="toggleScreen('headerlists')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="45" align="left" valign="top" class="rand"><?php echo SCP_EXPLAIN_HEADER;?><p style="margin-top:15px;text-align:center" class="fatred"><?php echo SCP_EXPLAIN_EXPIRES;?></p><br><div class="caldiv" style="display:none;position:absolute;width:95%"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="30" align="center" valign="top" class="rand"><img align="absmiddle" src="help_ico.gif" style="margin-right:5px"><a id="helpWin" href="http://spamdyke.org/documentation/README.html#HEADERS"><?php echo SCP_EXPLAIN_EXAMPLES;?></a></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="0" width="320"><?php echo SCP_BLACKLISTS;?></td>
  </tr>
  <?php if(!$scp->dieRechte->isAllView):?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="whitelist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["header-blacklist"]);?></textarea>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="200" width="320">
      <br><?php echo SCP_BLACKLISTS_CUSTOM;?><br> 
      <textarea name="blacklist" rows="10" cols="30" <?php echo ($scp->canUse("header"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["header-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php else:?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="325" width="320">
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br> 
      <textarea name="blacklist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["header-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php endif;?>
  <tr bgcolor="#FFFFFF"> 
    <td height="40" align="center" valign="middle"> 
     <?php if($scp->canUse("header")):?>
      <input type="hidden" name="type" value="header">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">
        <input type="hidden" name="anz_alleAddr" value="<?php echo $scp->anzVars["anz_alleAddr"];?>">  
        <input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">
      <input type="submit" name="savelist" value="<?php echo SCP_SAVE_BTN;?>">
     <?php endif;?>
    </td>
  </tr>
</table>
</form>
</div>
<div id="keywordlists" class="invis">
<form name="setlists" method="post" action="index.php?<?php $scp->getQueryString();?><?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
<table width="320" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" class="big bold" width="320" >[<a href="javascript://" onClick="toggleScreen('keywordlists')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="75" align="left" valign="top" class="rand"><?php echo SCP_EXPLAIN_KEYWORDS;?><p style="margin-top:15px;text-align:center" class="fatred"><?php echo SCP_EXPLAIN_EXPIRES;?></p><br><div class="caldiv" style="display:none;position:absolute;width:95%"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="0" width="320"><?php echo SCP_BLACKLISTS;?></td>
  </tr>
  <?php if(!$scp->dieRechte->isAllView):?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="125" width="320" style="vertical-align:top"> 
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br>
      <textarea name="whitelist_global" rows="5" cols="30" disabled><?php echo implode("\n",$scp->allLists["global"]["ip-in-rdns-keyword-blacklist"]);?></textarea>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="200" width="320">
      <br><?php echo SCP_BLACKLISTS_CUSTOM;?><br> 
      <textarea name="blacklist" rows="10" cols="30" <?php echo ($scp->canUse("keyword"))?"":"disabled";?>><?php echo implode("\n",$scp->allLists["my"]["ip-in-rdns-keyword-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php else:?>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="325" width="320">
      <br><?php echo SCP_BLACKLISTS_GLOBAL;?><br> 
      <textarea name="blacklist" rows="15" cols="30"><?php echo implode("\n",$scp->allLists["global"]["ip-in-rdns-keyword-blacklist"]);?></textarea>
    </td>
  </tr>
  <?php endif;?>
  <tr bgcolor="#FFFFFF"> 
    <td height="40" align="center" valign="middle"> 
     <?php if($scp->canUse("keyword")):?>
      <input type="hidden" name="type" value="ip-in-rdns-keyword">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">
        <input type="hidden" name="anz_alleAddr" value="<?php echo $scp->anzVars["anz_alleAddr"];?>">  
        <input type="hidden" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="20">
      <input type="submit" name="savelist" value="<?php echo SCP_SAVE_BTN;?>">
     <?php endif;?>
    </td>
  </tr>
</table>
</form>
</div> 
<div id="iplook" class="invis">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="480">
  <tr align="right" valign="middle" bgcolor="#CCCCCC"> 
    <td height="20" class="big bold" >[<a href="javascript://" onClick="toggleScreen('iplook')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF" align="center" valign="middle"> 
    <td height="460" align="left" valign="top" class="rand"><div id="iplook_content"></div></td>
  </tr>
</table>
</div>
<div id="stats" class="invis" style="height:480px;background:#FFFFFF">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="20" class="bold">
  <tr bgcolor="#CCCCCC">
    <td align="left" valign="middle" height="20" colspan="3" class="big bold" >&nbsp;<a href="javascript://" onClick="toggleStats('prozStat','topStat')"><?php echo SCP_STATS_PROZ;?></a> - <a href="javascript://" onClick="toggleStats('topStat','prozStat')"><?php echo SCP_STATS_TOP;?></a></td> 
    <td align="right" valign="middle" height="20" colspan="3" class="big bold" >[<a href="javascript://" onClick="toggleScreen('stats')"><?php echo SCP_CLOSE;?></a>]</td>
  </tr>
</table>
<div id="prozStat" style="position:absolute;width:640;height:460">
<table width="640" border="0" cellspacing="0" cellpadding="0" height="460" class="bold" id="">
<?php $scp->getStat();?>
  <tr bgcolor="#FFFFFF">
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</div>
<div id="topStat" style="display:none;position:absolute;width:640;height:460">
<table width="640" height="460" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td align="center" valign="middle">
  <img src="loading.gif" width="225" height="225"><br>
  <h1><?php echo SCP_GENERATATE_TOP_STATS;?><h1>
  </td>
 </tr>
</table>
</div>
</div>
