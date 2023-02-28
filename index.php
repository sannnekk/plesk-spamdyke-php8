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
@ini_set("memory_limit",'256M'); 
require("./config.inc.php");
if(function_exists("apc_clear_cache")) @apc_clear_cache();
require("./version.php");
require("./lang/".LANG.".inc.php");
require("./scp.class.php");

$action = "main";

if($_GET["action"] != "") $action = $_GET["action"];

$scp = scp::factory($session, $_GET, array(DB_HOST,DB_NAME,DB_USR,DB_PWD));
$scp->feProps('filter,rechte,einstellungen');

$checkOut = $scp->dieRechte->spamdykeConfigCheck();

$action = ($scp->getPleskManagerView()&&$action=="main")?"allview":$action;

if(file_exists("./config.new.txt") && !isset($_GET["action"]) && $scp->plesk_session->chkLevel(IS_ADMIN) && AUTOUPDATE){
   header("Location: index.php?".$_SERVER["QUERY_STRING"]."&action=update");
  }
  
if(file_exists("default_rights/UPDATE")){
   $domainUpdates = file("default_rights/UPDATE");
   foreach($domainUpdates as $domUp){
           if(empty($domUp))continue;
 	   $domUpDetails = explode(">",$domUp);
           $scp->dieRechte->alleRechte[trim($domUpDetails[1])] = $scp->dieRechte->alleRechte[trim($domUpDetails[0])];
           unset($scp->dieRechte->alleRechte[trim($domUpDetails[0])]);
           }
   $scp->dieRechte->schreibeRechte();
   $scp->setSpamDykeConf();
   unlink("default_rights/UPDATE");
   }  
  
if(file_exists("default_rights/".$scp->plesk_domain)){
   preg_match("#DEFAULT=(.*)[^0-1]#",file_get_contents("default_rights/".$scp->plesk_domain), $treffer);
   $cBuf = array();
   for($ii=0;$ii<strlen($treffer[1]);$ii++){
       $cBuf[] = substr($treffer[1],$ii,1);
       }
   $scp->dieRechte->updateRechte("gl",$cBuf[0]);
   $scp->dieRechte->updateRechte("live",$cBuf[1]);
   $scp->dieRechte->updateRechte("ip",$cBuf[2]);
   $scp->dieRechte->updateRechte("sender",$cBuf[3]);
   $scp->dieRechte->updateRechte("recipient",$cBuf[4]);         
   $scp->dieRechte->updateRechte("rdns",$cBuf[5]);
   $scp->dieRechte->updateRechte("keywords",$cBuf[6]);
   $scp->dieRechte->updateRechte("header",$cBuf[7]);
   $scp->dieRechte->schreibeRechte();
   $scp->setSpamDykeConf();
   @unlink("default_rights/".$scp->plesk_domain);
   }  

if(!empty($_POST["suchmuster"]) || isset($_POST["savelist"])){
$scp->suchmuster = $_POST["suchmuster"];
$scp->anzVars["anz_PerPage"] = $_POST["anz_PerPage"];
$scp->anzVars["anz_LiveCountry"] = $_POST["anz_LiveCountry"];
$scp->anzVars["anz_Von"] = $_POST["anz_Von"];
$scp->anzVars["anz_Filter"] = $_POST["anz_Filter"];
$scp->anzVars["anz_Richtung"] = $_POST["anz_Richtung"];
$scp->anzVars["anz_GlPaar"] = $_POST["anz_GlPaar"];
}

if($action == "abusemail"){
$scp->getAdmin()->sendSpamReport($_POST);
exit;
}

if($_GET["todo"] == "anzeige"){
$scp->anzVars = $_POST;
}

if($action == "admin"){
  if(!$scp->plesk_session->chkLevel(IS_ADMIN)) $action == "main";
 
    if(isset($_POST["e_handler"])){
       $scp->getAdmin()->setEventHandler($scp->spamDykeConf,$_POST);
       }

    if(isset($_POST["spamdyke_conf"])){
       $scp->getAdmin()->setSpamDykeConf($scp->spamDykeConf,$_POST);
       $scp->setSpamDykeConf();
       $scp->getAdmin()->updateRechteMulti($_POST);
       $scp->getAdmin()->updateReportSettings($_POST);
       $scp->getAdmin()->updateAbuseSettings($_POST);
       $scp->getAdmin()->updateRemoteSettings($_POST);
       }
       
    if(isset($_POST["killspamdyke"])){
       $scp->getAdmin()->actSpamdykeProc(true);
       }

   }

if($action == "allview"){
  if($scp->plesk_session->chkLevel(IS_ADMIN)) $scp->getAdmin()->isAllView = true;
 
    $action = "main";
   }


if($action == "main"){
   $scp->readQmailLogs();
   $scp->readList("ip-whitelist");
   $scp->readList("ip-blacklist");
   $scp->readList("rdns-whitelist");
   $scp->readList("rdns-blacklist");
   $scp->readList("sender-whitelist");
   $scp->readList("sender-blacklist");
   $scp->readList("recipient-whitelist");
   $scp->readList("recipient-blacklist");
   $scp->readList("ip-in-rdns-keyword-blacklist");
   if(array_key_exists("header-blacklist-file",$scp->spamDykeConf))$scp->readList("header-blacklist");
   }

$scp->setQueryString($_SERVER["QUERY_STRING"]);

if(!$scp->getPleskAllowed()){
    //alert(lmsg('__perm_denied'));
    go_to_uplevel();
   }

if(isset($_POST["savelist"])){
   if(!array_key_exists("ip-whitelist-file_MY",$scp->spamDykeConf)){
      $scp->dieRechte->updateRechte("gl",$scp->alleRechte[$scp->dieDomain]["gl"]);
      $scp->dieRechte->updateRechte("live",$scp->alleRechte[$scp->dieDomain]["live"]);
      $scp->dieRechte->updateRechte("ip",$scp->alleRechte[$scp->dieDomain]["ip"]);
      $scp->dieRechte->updateRechte("sender",$scp->alleRechte[$scp->dieDomain]["sender"]);
      $scp->dieRechte->updateRechte("rdns",$scp->alleRechte[$scp->dieDomain]["rdns"]);
      $scp->dieRechte->updateRechte("keywords",$scp->alleRechte[$scp->dieDomain]["keywords"]);
      $scp->dieRechte->updateRechte("recipient",$scp->alleRechte[$scp->dieDomain]["recipient"]); 
      $scp->dieRechte->updateRechte("header",$scp->alleRechte[$scp->dieDomain]["header"]);      
      $scp->dieRechte->schreibeRechte();
      $scp->setSpamDykeConf();
     }
   $scp->setAllLists($_POST);
}



if(($scp->plesk_session->chkLevel(IS_ADMIN)||$scp->getAdmin()->checkResellerRight()) && !empty($_POST["adminsave"])){
   $scp->dieRechte->updateRechte("gl",$_POST["gl"]);
   $scp->dieRechte->updateRechte("live",$_POST["live"]);
   $scp->dieRechte->updateRechte("ip",$_POST["ip"]);
   $scp->dieRechte->updateRechte("sender",$_POST["sender"]);
   $scp->dieRechte->updateRechte("rdns",$_POST["rdns"]);
   $scp->dieRechte->updateRechte("keywords",$_POST["keywords"]);
   $scp->dieRechte->updateRechte("recipient",$_POST["recipient"]);
   $scp->dieRechte->updateRechte("header",$_POST["header"]);
   $scp->dieRechte->schreibeRechte();
   }

$scp->getAllBlocked();

if($_POST["doexport"]){
   $scp->doExport();
   }
   

?>
<!doctype html>
<html>
<head>
<link rel="shortcut icon" href="/favicon.ico">
<title>Spamdyke Contron Panel - <?php echo $scp->plesk_domain;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script language="javascript" type="text/javascript" src="/javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="/javascript/chk.js.php"></script>
<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="jquery.popupWindow.js"></script>
<script language="javascript" type="text/javascript" src="jquery.selection.js"></script>
<?php if(PSA_VERSION < 11.5):?>
<link rel="stylesheet" href="/skins/<?php echo $scp->getPleskSkin();?>/css/base.css" type="text/css" />
<link rel="stylesheet" href="/skins/<?php echo $scp->getPleskSkin();?>/css/btns.css" type="text/css" />
<link rel="stylesheet" href="/skins/<?php echo $scp->getPleskSkin();?>/css/customer/main.css" type="text/css" />
<link rel="stylesheet" href="/skins/<?php echo $scp->getPleskSkin();?>/css/customer/custom.css" type="text/css" />
<?php else:?>
<link rel="stylesheet" href="/<?php echo $scp->getPleskSkin();?>/css/common.css" type="text/css" />
<link rel="stylesheet" href="/<?php echo $scp->getPleskSkin();?>/css/main.css" type="text/css" />
<link rel="stylesheet" href="/<?php echo $scp->getPleskSkin();?>/css/main-buttons.css" type="text/css" />
<link rel="stylesheet" href="/<?php echo $scp->getPleskSkin();?>/css/custom.css" type="text/css" />
<?php endif;?>

<link rel="stylesheet" href="responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo (stristr($_SERVER["HTTP_USER_AGENT"],"msie"))?"doof":"schlau";?>_browser.css">
<style type="text/css">

input.button-add:hover{
background-color: #DDDDDD;
}

#no-more-tables{
margin-right:37px;
}

input.button-add{
background-color: #FFFFFF; /* make the button transparent */
background-repeat: no-repeat;  /* make the background image appear only once */
background-position: 2px 2px;  /* equivalent to 'top left' */
border: 1px solid #000000;           /* assuming we don't want any borders */
cursor: pointer;        /* make the cursor like hovering over an <a> element */
height: 22px;           /* make this the size of your image */
width: 22px;
}

input.button-add-big{
background-color: #FFFFFF; /* make the button transparent */
background-repeat: no-repeat;  /* make the background image appear only once */
background-position: 2px 3px;  /* equivalent to 'top left' */
border: 1px solid #000000;           /* assuming we don't want any borders */
cursor: pointer;        /* make the cursor like hovering over an <a> element */
height: 25px;          /* make this the size of your image */
padding-left:20px;
margin-bottom:2px;
}


#menuslot{
position:fixed;
height:100%;
width:36px;
left:100%;
top:0px;
border-left:1px solid #000000;
margin:0px 0px 0px -37px;
padding-top:3px;
background:#DDDDDD;
overflow:hidden;
z-index:9999;
}

#menuicon{
padding-left:2px;
width:200px;
cursor:pointer;
}

#menuicon img{
width:32px;
height:32px;
}

form[name=setlists] textarea{
font-family:Arial;
font-size:11px !important;
width:310px;
}

table{
font-size:11px !important;
}

#topStatBody td {vertical-align: top}

.rightconf{
width:175px;
text-align:right;
padding-right:20px;
}

.confupd{
font-size:20px;
font-weight:bold;
color:#006600;
text-align:center;
width:100%;
}

#allg_settings table td{
padding-top:2px;
padding-bottom:2px;
}

#content{
width:100%;
}
th{
text-align:left;
}

#head{
font-size: 11px !important;
width:100%;
padding:3px;
height: 32px;
background-color:#DDDDDD;
}

#head #logo{
margin-right:25px;
}

#head #topmenu{
margin-top:5px;
margin-right:25px;
float:right;
}

#head #topsearch{
margin-top:5px;
float:right;
margin-right:40px;
}

#head div{
float:left;
height: 32px;
}
</style>

<script type="text/javascript">
<!--

//InitTips('/javascript/conhelp.js.php','plesk-base');

var opt_no_frames = false;
var opt_integrated_mode = false;
var stats_top_generated = false;
var selta;
var listWin;
var tainlines;
var isOut = false;
var openMenu = 0;

function toggleMenu(){
    t = openMenu;
    openMenu = 1;
    if(t==0){
    $("#m_on").fadeOut("fast",function(){$("#m_off").fadeIn("fast");});
    $("#menuslot").animate({
       marginLeft: "-=164",
       width: "200"
       }, 500, function() {
	openMenu = 2
	});
    }
    if(t==2){
    $("#m_off").fadeOut("fast",function(){$("#m_on").fadeIn("fast");});
    $("#menuslot").animate({
       marginLeft: "+=164",
       width: "36"
       }, 500, function() {
	openMenu = 0
	});
    }

}

function toggleDirectList(id,inOut){
         
         if(inOut){
            document.getElementById(id).className='vis'
            document.getElementById(id).style.border = "1px solid #000000";
            }
         else{
            document.getElementById(id).className='invis'
            document.getElementById(id).style.border = "none";
           }

         }

function _body_onload()
{
        loff();
        SetContext('<?php echo SCP_ADMIN;?>');
			
}
			
function _body_onunload()
{
        lon();
				
}
				
		
		
function fullscreen(){
top.location = "/smb/web/view";

dieBreite = Math.round(screen.width/100*90);
dieHoehe = Math.round(screen.height/100*90);

startx = (screen.width - dieBreite)/2;
starty = (screen.height - dieHoehe)/2;

ScpFenster = window.open(self.location, "SCPWindow", "width="+dieBreite+",height="+dieHoehe+",screenX="+startx+",screenX="+starty+",scrollbars=yes");
ScpFenster.focus();
}		
		
function toggleScreen(what)
{
listWin = what;
if(document.all){
   document.getElementById(what).style.marginTop = -240 + (document.body.scrollTop);
   window.onscroll = function(){document.getElementById(what).style.marginTop = -240 + (document.body.scrollTop);}
   }

if(document.getElementById(what).style.display != "block"){
  $("#fader").fadeIn("fast",function(){$("#"+what).fadeIn("fast");});
  }
else{
  if(document.all)window.onscroll = null;
   
  $("#"+what).fadeOut("fast",function(){$("#fader").fadeOut("fast");});
 
}

}

function toggleStats(inFade,outFade){
          yn = true;
          blocked = "<?php echo $scp->blocked;?>";
          mins = blocked/500/60;
          if(mins>1){
            mins = Math.round(mins);
          }else{
           mins = mins.toFixed(2);
           }
          mess = "<?php echo SCP_TAKES_LONG_TO_GENERATE;?>";
          mess = mess.replace(/#IPS#/,blocked);
          mess = mess.replace(/#MIN#/,mins);
          if(blocked > 50 && !stats_top_generated) yn = window.confirm(unescape(mess));
          jn = (yn)?1:0;

         $("#"+outFade).fadeOut();
         $("#"+inFade).fadeIn("fast",function(){

         if(inFade == "topStat" && !stats_top_generated){
          
            von = document.anzeige.anz_Von.value;

            $("#topStat").load("topstat.php?<?php $scp->getQueryString(); ?>&todo=anzeige<?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>", {anzeige:von,land:jn},function(){
             stats_top_generated = true;
             });}
          });
           

         }

function setWhite(what,ip){

         myDom = document.getElementById(what);
         col = myDom.getElementsByTagName('textarea');
         for(i=0;i<col.length;i++){
             if(col[i].name == "whitelist"){

                if(col[i].value.indexOf(ip) >= 0){
                   alert("<?php echo SCP_ON_WHITELIST;?>");
                   return;
                   }

                if(col[i].value != ""){
                   col[i].value = col[i].value + "\n"+ip;
                   }
                else{
                 col[i].value = ip;
                }
               }
            }
         toggleScreen(what);
         }
         
function setBlack(what,ip){

         myDom = document.getElementById(what);
         col = myDom.getElementsByTagName('textarea');
         for(i=0;i<col.length;i++){
             if(col[i].name == "blacklist"){

                if(col[i].value.indexOf(ip) >= 0){
                   alert("<?php echo SCP_ON_BLACkLIST;?>");
                   return;
                   }

                if(col[i].value != ""){
                   col[i].value = col[i].value + "\n"+ip;
                   }
                else{
                   col[i].value = ip;
                  }
              }
           } 
         toggleScreen(what);
         }

function getPageContent(strURL,IP,what) {
    toggleScreen('iplook');
    var xmlHttpReq = false;
    
    var header_box = "<?php echo GETTING_IP_INFO;?>";

    if(what == "mail") header_box = "<?php echo CHECKING_EMAIL;?>";
    
    if(what == "analyze") header_box = "<?php echo SCP_MAIL_ANALYZE;?>";
    
    <?php if($scp->plesk_session->chkLevel(IS_ADMIN)):?>
    if(what == "ip") strURL+="&abuse=<?php echo $scp->getAdmin()->isSpamReport();?>";
    <?php endif;?>

     
    var loadContent = '<table width="600" height="250" border="0" cellpadding="0" cellspacing="0"><tr>'
                    + '<td align="center" valign="middle"><b>'+header_box+' ('+IP+')</b></td></tr><tr>'
                    + '<td align="center" valign="middle"><img src="loading.gif" width="225" height="225"></td></tr></table>'
    
							    
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
	
    updatepage(loadContent);
    
    self.xmlHttpReq.open('GET', strURL, true);
    
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    self.xmlHttpReq.onreadystatechange = function() {
    if (self.xmlHttpReq.readyState == 4) {
        updatepage(self.xmlHttpReq.responseText);
        }
    }
    
    self.xmlHttpReq.send(null);
    
}
function updatepage(str){
    document.getElementById("iplook_content").innerHTML = str;
    }

function adminView(id){
         views = Array(<?php echo $scp->getAdmin()->regKarten;?>);
         for(i=0;i<views.length;i++){
            if(document.getElementById(views[i]).style.display != "none"){
               wer = views[i];
               break;
               }
            }
         $("#"+wer).fadeOut("fast",function(){
         $("#"+id).fadeIn("fast");});

         document.scpedit.saveAdmView.value = id;
         ermittlePids();
         }
         
function viewPart(part){
         $("#"+part).slideToggle("slow",function(){
           tf = (document.getElementById(part).style.display == "none")?false:true;
           p = $("div").find("[data-rel='" + part + "_lnk']");
           if(tf){
              $(p).addClass("boldMenu");;
              }
           else{
              $(p).removeClass("boldMenu");;
             }
          storeValues();
         });
         }
         
function storeValues(){
	 
	 vals = new Array("einstellungen_fe","filter_fe"<?php echo ($scp->plesk_session->chkLevel(IS_ADMIN)||$scp->getAdmin()->checkResellerRight())?',"rechte_fe"':'';?>);
	 
	 for(i=0;i<vals.length;i++){
	     tf = (document.getElementById(vals[i]).style.display != "none")?true:false;
	     if(tf){
	        document.cookie = vals[i]+"=block:boldMenu";
	     }else{
	        document.cookie = vals[i]+"=none:normal";
	        }
	    }
	 }
	 
function changeDailyRep(val){
	
	 if(val>0){
	    status = window.prompt("Bitte geben Sie die Empfänger-Adresse für den täglichen Report an");
	    }
	 else{
	    status = 0;
	    }
	
	 document.scpedit.isdailyreport.value = status;
	 document.scpedit.submit()
	
	 }

function resetFilter(){
         filterTags = document.getElementById("filter_fe").getElementsByTagName("select");
         for(i=0;i<filterTags.length;i++){
            alleTags = document.getElementsByName(filterTags[i].name);
            for(ii=0;ii<alleTags.length;ii++){
                if(alleTags[ii].type=="hidden"){
                   alleTags[ii].value = "";
                  }
                else{
                   alleTags[ii].selectedIndex = 0;
                 }
               }
            
            }

         suchTags = document.getElementsByName("suchmuster");
         for(i=0;i<suchTags.length;i++){
            suchTags[i].value = "";
            }
         }

function doExport(){
         document.suchenf.doexport.value = "1";
         document.suchenf.submit();
         }

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
    }
         
function abuseReport(vals,email){
	
	 yn = window.confirm(unescape("<?php echo SCP_SPAM_ABUSE_REPORT_DO;?>"));
	 if(!yn) return;

	 dieRows = document.getElementById("log_table").getElementsByTagName("tr");
	 dieTds = dieRows[(Number(vals)+1)].getElementsByTagName("td");
	 from = dieTds[1].getAttribute('title');
 	 to = dieTds[2].getAttribute('title');
 	 ip = dieTds[4].getAttribute('title');
	 rdns = dieTds[5].getAttribute('title');
	 stamp = dieTds[6].innerHTML;
	 
	 
	 $.post("index.php?<?php $scp->getQueryString(); ?>&action=abusemail",{ rec:email, from: from, to: to, ip: ip, rdns: rdns,stamp: stamp }, function(data){
           if(data.indexOf("emailwassend")>-1){
              yn = window.confirm("<?php echo SCP_SPAM_ABUSE_REPORT_DO_OK;?>");
              if(yn) toggleScreen('iplook');
              }
           else{
             alert("<?php echo SCP_SPAM_ABUSE_REPORT_DO_NOK;?>"+data);
             }
           });

	 }         
$(function() {
     
    //$( "#menuslot" ).mouseenter(function() {toggleMenu()});
    //$( "#menuslot" ).mouseleave(function() {toggleMenu()});

    $('#helpWin').popupWindow({ 
    centerBrowser:1,
    height:500,
    width:800,
    });

   $('.caldiv').mouseout(function(){
       if(isOut){
          $("#"+listWin+" .caldiv").slideUp("fast");
          isOut = false;
          }
       });

    $('textarea').focus(function(){
       selta = $(this);
       });

    $('textarea').mouseup(function(){
      tain = $.trim($(selta).selection());
      tainlines = [];
     
      if(tain!=""){
         tainlines = tain.split("\n");
         $("#"+listWin+" .caldiv").html('<center><iframe onMouseOver="isOut=true" onLoad="$(\'#\'+listWin+\' .caldiv\').slideDown(\'fast\');" src="calender.php" style="background:#FFFFFF;border:none;width:300px;height:250px"></iframe></center>');
         }
      
      });
});


   
function setlistdate(dat){
         dat = dat.replace(/li-/,"");
         $("#"+listWin+" .caldiv").slideUp("fast");
         tacur = $(selta).val().split("\n");
         neuerBlock = "";
         for(i=0;i<tacur.length;i++){
            dieline = tacur[i];
            for(ii=0;ii<tainlines.length;ii++){
               if(dieline.indexOf(tainlines[ii])>=0){
                  hasEx = dieline.indexOf(" [EXPIRES");
                  if(hasEx>0) dieline=dieline.substring(0,hasEx);
 
                  dieline=dieline+" [EXPIRES:"+dat+"]";
                  }
                  
               }

            neuerBlock+=dieline+"\n";
            }
         $(selta).val(neuerBlock);
         }

 
//-->
</script>
</head>
<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="">
<div id="fader" class="invis"></div>
<?php if(!empty($checkOut))echo $checkOut;?>
<div id="content">
<div id="head">
  <div id="logo"><img src="logo.png"></div>
  <div id="titel"><strong>Spamdyke Control Panel Version (<?php echo SCP_VERSION;?>-<?php echo LOG_TYPE;?>)</strong><br>[<?php $scp->checkVersion(SCP_VERSION);?>]</div>
  <?php if($action != "admin"):?>
  <div id="topsearch">
        <form name="suchenf" id="suchf" method="post" action="index.php?<?php $scp->getQueryString(); ?>&todo=anzeige<?php echo ($scp->getAdmin()->isAllView)?"&action=allview":"";?>">
        <input type="text" name="suchmuster" value="<?php echo $_POST["suchmuster"];?>" size="25" style="height:22px">
        <input type="submit" value="" name="jetztsuchen" title="<?php echo SCP_SEARCH_DO;?>" alt="<?php echo SCP_SEARCH_DO;?>" class="button-add" style="background-image:url(search.png);margin-right:15px">
        <input type="hidden" name="anz_PerPage" value="<?php echo $scp->anzVars["anz_PerPage"];?>">
        <input type="hidden" name="anz_Richtung" value="<?php echo $scp->anzVars["anz_Richtung"];?>">
        <input type="hidden" name="anz_LiveCountry" value="<?php echo $scp->anzVars["anz_LiveCountry"];?>">
        <input type="hidden" name="anz_Von" value="<?php echo $scp->anzVars["anz_Von"];?>">
        <input type="hidden" name="anz_Filter" value="<?php echo $scp->anzVars["anz_Filter"];?>">
        <input type="hidden" name="anz_GlPaar" value="<?php echo $scp->anzVars["anz_GlPaar"];?>">
        <input type="hidden" name="doexport" value="0">
	</form>
  </div>
    <div id="topmenu">
  <input type="button" onClick="resetFilter()"  alt="<?php echo SCP_RESETFILTER;?>" title="<?php echo SCP_RESETFILTER;?>" class="button-add" style="background-image:url(reset.png)">
        <input type="button" onClick="document.anzeige.submit()" alt="<?php echo SCP_REFRESH;?>" title="<?php echo SCP_REFRESH;?>" class="button-add" style="background-image:url(refresh.png)">
        <input type="button" onClick="toggleScreen('stats')" alt="<?php echo SCP_STATISTIK;?>" title="<?php echo SCP_STATISTIK;?>" name="button" class="button-add" style="background-image:url(chart.png)">
        <input type="button" onClick="doExport()" alt="<?php echo SCP_EXPORT;?>" title="<?php echo SCP_EXPORT;?>" name="exportview" class="button-add" style="background-image:url(export.png)">
<input type="button" onClick="fullscreen()" alt="<?php echo TOGGLE_FULLSCREEN;?>" title="<?php echo TOGGLE_FULLSCREEN;?>" name="fullscreeview" class="button-add" style="background-image:url(full.png)">
  </div>
  <?php endif;?>
</div>
		      								
<?php 

if($scp->spamDykeInstalled){
   include("./".$action.".php");
}else{
   include("./nodyke.php");
}

?>
</div>
</body>
</html>
