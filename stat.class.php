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

class stat{
     
     var $howMany;

     var $isLand;
     
     var $isReport = false;

     var $isAdm;

     var $topRec;

     var $topIp;

     var $topCountry;
     
     var $topCountrySave = array();

     var $topTime;

     var $tlds;
     
     var $scriptFolder;

     
     function __construct($m,$land,$adm){
              $this->howMany = $m;
              $this->isLand = $land;
              $this->isAdm = $adm;
              $this->scriptFolder = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
              }

     function setTlds($tld){
              $this->tlds = $tld;
              }
              
     function setIsReport(){
     	      $this->isReport = true;
     	      }

     function setTopRec($rec){
              $this->topRec = $rec;
              }

     function setTopIp($ip){
              $this->topIp = $ip;
              }

     function setTopCountry($country){
              $this->topCountry = $country;
              }

     function setTopTime($time){
              $this->topTime = $time;
              }
     
     function getTopRec(){
              echo '<table width="275" border="0" cellspacing="0" cellpadding="0">';
              $head = ($this->isAdm)?SCP_STAT_DOMAIN:SCP_STAT_EMAIL;
              
              $isOnl = ($this->isReport)?'':'&nbsp;[<a href="#" onClick="$(\'#gfx_dom_stat\').fadeIn()">'.SCP_STAT_GFX.'</a>]';
              
              echo '<tr class="bold"><td width="245">'.$head.$isOnl.'</td><td width="30">'.SCP_STAT_NO.'</td></tr>';
              $anz = 0;
              while (list($key,$val) = each($this->topRec)){
                     echo '<tr height="20"><td>'.$key.'</td><td>'.$val.'</td></tr>';
                     $anz++;
                     $arr_stat[$key] = $val;
                     if($anz == $this->howMany) break;
                     }
              echo '</table>';


               if(!$this->isReport){
                  $stat = new dynStat($arr_stat);
                  echo '<div id="gfx_dom_stat" style="z-index:20;position:absolute;top:20;left:20;display:none">';
                  echo '<div style="cursor:pointer;position:absolute;top:1;left:579"><img width="20" height="19" src="x.gif" onClick="$(\'#gfx_dom_stat\').fadeOut()"/></div>';
  	          $dRoot = $this->scriptFolder."/stats";
	          $stat->createBar("$dRoot/domstat-".$_GET["dom_name"].".gif");
   	          echo '<table width="600" border="0" cellspacing="0" cellpadding="5" style="background:#FFFFFF;padding:0px; border: 1px solid #000000">';
	          echo '<tr height="20">';
	          echo '<td><img src="stats/domstat-'.$_GET["dom_name"].'.gif" /></td>';
	          echo '</tr></table></div>';
	          }
	       

              }

    function getTopIp(){
              echo '<table width="275" border="0" cellspacing="0" cellpadding="0">';
              
              $isOnl = ($this->isReport)?'':'&nbsp;[<a href="#" onClick="$(\'#gfx_ip_stat\').fadeIn()">'.SCP_STAT_GFX.'</a>]';
              
              echo '<tr class="bold"><td width="245" colspan="2">'.SCP_STAT_IP.$isOnl.'</td><td width="30">'.SCP_STAT_NO.'</td></tr>';
              $anz = 0;
              while (list($key,$val) = each($this->topIp)){
              	     if($this->isReport){
                        echo '<tr height="20"><td width="20"><a href="http://www.geoiptool.com/'.LANG.'/?IP='.$key.'" border="0" target="_blank"><img border="0" src="cid:help_ico.gif" width="15" height="15"></a></td><td>'.$key.'</td><td>'.$val.'</td></tr>';
                        }
                     else{
                        echo '<tr height="20"><td width="20" onMouseOver="getMiniIp(\''.$key.'\')" onMouseOut="closeMiniIp(\''.$key.'\')"><img border="0" src="help_ico.gif" width="15" height="15"><br><div id="miniIp_'.str_replace(".","",$key).'" style="font-size:8px;padding:3px;background:#CCCCCC;position:absolute;z-index:98;border:1px dashed #000000;overflow:auto" class="invis"><center><img src="36-0.gif" height="15" width="128"></center></div></td><td>'.$key.'</td><td>'.$val.'</td></tr>';
                        }
                     $anz++;
                     $arr_stat[$key] = $val;
                     if($anz == $this->howMany) break;
                     }
              echo '</table>';

               if(!$this->isReport){
                  $stat = new dynStat($arr_stat);
                  echo '<div id="gfx_ip_stat" style="z-index:20;position:absolute;top:20;left:20;display:none">';
                  echo '<div style="cursor:pointer;position:absolute;top:1;left:579"><img width="20" height="19" src="x.gif" onClick="$(\'#gfx_ip_stat\').fadeOut()"/></div>';
  	          $dRoot = $this->scriptFolder."/stats";
	          $stat->createBar("$dRoot/ipstat-".$_GET["dom_name"].".gif");
   	          echo '<table width="600" border="0" cellspacing="0" cellpadding="5" style="background:#FFFFFF;padding:0px; border: 1px solid #000000">';
	          echo '<tr height="20">';
	          echo '<td><img src="stats/ipstat-'.$_GET["dom_name"].'.gif" /></td>';
	          echo '</tr></table></div>';
	          }
              } 

    function getTopCountry(){
              echo '<table width="275" border="0" cellspacing="0" cellpadding="0">';
              $isOnl = ($this->isReport)?'':'&nbsp;[<a href="#" onClick="$(\'#gfx_cty_stat\').fadeIn()">'.SCP_STAT_GFX.'</a>]';
              echo '<tr class="bold"><td width="245" colspan="2">'.SCP_STAT_COUNTRY.$isOnl.'</td><td width="30">'.SCP_STAT_IPS.'</td></tr>';
              $anz = 0;
              while (list($key,$val) = each($this->topCountry)){
                     echo '<tr height="20"><td width="20"><img style="border:1px solid #000000" src="flags/'.$key.'.png"></td><td width="225">'.$this->tlds[$key].'</td><td>'.$val.'</td></tr>';
                     $anz++;
                     $arr_stat[$this->tlds[$key]] = $val;                     
                     $this->topCountrySave[] = $key;
                     if($anz == $this->howMany) break;
                     }

              echo '</table>';
              if(!$this->isReport){
                  $stat = new dynStat($arr_stat);
                  echo '<div id="gfx_cty_stat" style="z-index:20;position:absolute;top:20;left:20;display:none">';
                  echo '<div style="cursor:pointer;position:absolute;top:1;left:579"><img width="20" height="19" src="x.gif" onClick="$(\'#gfx_cty_stat\').fadeOut()"/></div>';
  	          $dRoot = $this->scriptFolder."/stats";
	          $stat->createBar("$dRoot/ctystat-".$_GET["dom_name"].".gif");
   	          echo '<table width="600" border="0" cellspacing="0" cellpadding="5" style="background:#FFFFFF;padding:0px; border: 1px solid #000000">';
	          echo '<tr height="20">';
	          echo '<td><img src="stats/ctystat-'.$_GET["dom_name"].'.gif" /></td>';
	          echo '</tr></table></div>';
	          }
              }   

    function getTopTime(){
              echo '<table width="275" border="0" cellspacing="0" cellpadding="0">';
              $isOnl = ($this->isReport)?'':'&nbsp;[<a href="#" onClick="$(\'#gfx_tme_stat\').fadeIn()">'.SCP_STAT_GFX.'</a>]';              
              echo '<tr class="bold"><td width="245">'.SCP_STAT_STD.$isOnl.'</td><td width="30">'.SCP_STAT_NO.'</td></tr>';
              $anz = 0;
              while (list($key,$val) = each($this->topTime)){
                     echo '<tr height="20"><td>'.intval($key).'-'.(intval($key)+1).' '.SCP_STAT_HOUR.'</td><td>'.$val.'</td></tr>';
                     $anz++;
                     $arr_stat[intval($key).'-'.(intval($key)+1).' '.SCP_STAT_HOUR] = $val; 
                     if($anz == $this->howMany) break;
                     }
              echo '</table>';
              
             if(!$this->isReport){
                  $stat = new dynStat($arr_stat);
                  echo '<div id="gfx_tme_stat" style="z-index:20;position:absolute;top:20;left:20;display:none">';
                  echo '<div style="cursor:pointer;position:absolute;top:1;left:579"><img width="20" height="19" src="x.gif" onClick="$(\'#gfx_tme_stat\').fadeOut()"/></div>';                  
  	          $dRoot = $this->scriptFolder."/stats";
	          $stat->createBar("$dRoot/tmestat-".$_GET["dom_name"].".gif");
   	          echo '<table width="600" border="0" cellspacing="0" cellpadding="5" style="background:#FFFFFF;padding:0px; border: 1px solid #000000">';
	          echo '<tr height="20">';
	          echo '<td><img src="stats/tmestat-'.$_GET["dom_name"].'.gif" /></td>';
	          echo '</tr></table></div>';
	          }

              }           

     }













?>