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

/**
 * Klasse zum sammeln der Admin-Rechte Infos der Domain(s)
 */

class AdminRights{
     
     var $regKarten = "'dnsbl_settings','gl_settings','allg_settings','multi_right','report_settings'";
     
     var $reloadLeftFrame = false;

     /** Variable, die die Domain aufnimmt */
     var $dieDomain;

     /** Variable, die alle Rechte aus der Rights.txt aufnimmt */
     var $alleRechte = array();

     var $glFolder;

     var $scriptFolder;

     var $spamdykeFolder;
     
     var $spamdykeFolderConfigDir;
     
     var $spamdykeFolderCustomsDirs = array();

     var $isAllView = false;

     var $settings_4_0_10 = array("reject-empty-rdns","reject-missing-sender-mx","reject-unresolvable-rdns","reject-ip-in-cc-rdns");
     
     var $settings_4_1_0 = array("reject-identical-sender-recipient");
     
     var $settings_5_0_0 = array("relay-level"=>array("normal","block-all","allow-all"),
                                 "ip-relay-file"=>"/ip_relay_file",
                                 "rdns-relay-file"=>"/rnds_relay_file",
                                 "reject-recipient"=>array("none","same-as-sender","invalid","unavailable"),
                                 "reject-sender"=>array("none","no-mx","not-local"),
                                 "reject-empty-rdns"=>"checkbox",
                                 "reject-unresolvable-rdns"=>"checkbox",
                                 "reject-ip-in-cc-rdns"=>"checkbox",
                                 );
     
     var $otherSettings = array("4.0.10","4.1.0","5.0.0");
     
     var $otherSettingsAll = array();
     
     var $isReportOn;
     
     var $reportStates;
     
     var $remoteStates;
     
     var $abuseDetails;
     
     var $spamDykeVersion;
     
     var $xtea;

     var $spamdykeWatchdog;

     var $db_conn;
     
     
     /**
       * Konstruktor
       *
       */

     function AdminRights($dom,$glDir,$xtea,$db){

	      $this->db_conn = $db;
              $this->xtea = $xtea;
              $this->dieDomain = $dom;
              $this->scriptFolder = str_replace("index.php","",$_SERVER["SCRIPT_FILENAME"]);
              $this->spamdykeFolder = substr($glDir,0,strrpos($glDir,"/"));
              $this->spamdykeFolderConfigDir = substr($glDir,0,strrpos($glDir,"/"))."/conf.";
              $this->setUpOtherSettings();

	      $this->leseRechte();
	      $this->leseAbuseRechte();
	      
	      $this->leseRemoteRechte();
	      
	      $this->isReportOn = file_exists("/etc/cron.daily/scp_reports");
             $this->spamdykeWatchdog= file_exists("/etc/cron.d/checkspamdyke");
	      if($this->isReportOn) $this->leseReportRechte();
     	      
             }
             
     function setUpOtherSettings(){
     
              $res = mysqli_query($this->db_conn,"SELECT id FROM custom_buttons WHERE text = 'Spamdyke Administration'");
              $this->psaIntegration = (mysqli_num_rows($res)>0)?true:false;
     
     
     	      $ot = $this->otherSettings;
     	      $this->otherSettings = null;
     	      for($i=0;$i<count($ot);$i++){
     	      	    $varName = "settings_".str_replace(".","_",$ot[$i]);
     	      	    $this->otherSettings[$ot[$i]] = $this->${varName};
     	      	    $this->otherSettingsAll = array_merge($this->otherSettingsAll,$this->${varName});
     	      	    }
     	      }
             
     function getDomainRight($recht){
     	
              $select = ($this->alleRechte[$this->dieDomain][$recht])?"selected":"";
	      
	      echo "<select name=\"$recht\">\n";
	      echo "<option value=\"0\">".VALUE_NO."</option>\n";
	      echo "<option value=\"1\" ".$select.">".VALUE_YES."</option>\n";
	      echo "</select>";
              }
              
     function getDomainRightMulti($dom,$recht){
     	
              $select = ($this->alleRechte[$dom][$recht])?"checked":"";
	      
	      $html = '<input type="checkbox" name="'.$dom.'#'.$recht.'" value="domrights" '.$select.'>'; 
	      return $html;
	      }

     function checkResellerRight(){
              if(empty($_GET["cl_id"]))return false;
              return in_array($_GET["cl_id"],$this->remoteStates["reseller"]);
              }

     function getEventHandler($typ){

              $res = mysqli_query($this->db_conn,"SELECT id,command FROM event_handlers where command like '%plesk_event_handler.sh%' and command like '%".$typ."%'");
              $select = (mysqli_num_rows($res)>0)?"selected":"";
              
              $gbc = ($typ=="create_rights" && $select)?'background:#DCDEDE;border-left:1px solid #000000;border-top:1px solid #000000;border-bottom:1px solid #000000;':'';
              $JsH = ($typ=="create_rights")?'onChange="eventCheckerJs(this)"':'';
	      
	      echo '<div id="eh_'.$typ.'" style="z-index:51;padding:1px;padding-right:5px;padding-left:5px;position:absolute;width:55px;float:left;margin-top:-15px;'.$gbc.'">';
	      echo "<select name=\"$typ\" ".$JsH.">\n";
	      echo "<option value=\"0\">".VALUE_NO."</option>\n";
	      echo "<option value=\"1\" ".$select.">".VALUE_YES."</option>\n";
	      echo "</select>";
	      echo "</div>";
	      
	      if($typ=="create_rights"){
	        $data = mysqli_fetch_array($res);
		preg_match("#DEFAULT=(.*)#",$data["command"], $treffer);
		$cBuf = array();
		for($ii=0;$ii<strlen($treffer[1]);$ii++){
	            $cBuf[] = substr($treffer[1],$ii,1);
		    }
		$typs = array("gl"=>SCP_GREYLISTING,
		              "live"=>SCP_LIVE_COUNTRY_ALLOW,
		              "ip"=>SCP_LISTS_IP,
		              "sender"=>SCP_LISTS_SENDER,
		              "recipient"=>SCP_LISTS_RECIPIENT,
		              "rdns"=>SCP_LISTS_RDNS,
		              "keywords"=>SCP_LISTS_KEYWORDS,
		              "header"=>SCP_LISTS_HEADER);
		              
		$vis = ($select)?"block":"none";
		
		echo '<div id="details_'.$typ.'" style="z-index:50;position:absolute;padding:1px;padding-right:5px;padding-left:5px;margin-left:65px;margin-top:-15px;float:left;height:192px;width:220px;background:#DCDEDE;display:'.$vis.';border:1px solid #000000;">';
		$c=0;
		while(list($k,$v) = each($typs)){
 	              echo "<select name=\"".$typ."_".$k."\">\n";
 	              $selD = ($cBuf[$c]==0)?"selected":"";
     	              echo "<option value=\"0\" $selD>".VALUE_NO."</option>\n";
     	              $selD = ($cBuf[$c]==1)?"selected":"";
	              echo "<option value=\"1\" $selD>".VALUE_YES."</option>\n";
	              echo "</select> -> $v<br>";
	              $c++;
	              }
		echo '</div>';
	        }
	      
	     
	      
              }

     function setEventHandler($conf,$params){

              @mysqli_query($this->db_conn,"DELETE FROM event_handlers where command like '%plesk_event_handler.sh%'");
              
              $handler["create"] = 21;
              $handler["update"] = 22; 
              $handler["delete"] = 23;
              
              if(PSA_VERSION>=10){
              	 $handler["create"] = 123;
                 $handler["update"] = 124; 
                 $handler["delete"] = 125;
              	 }
              
              if($params["create"]) mysqli_query($this->db_conn,"INSERT INTO event_handlers (action_id, priority, user, command )VALUES ( '".$handler["create"]."', '50', 'root', '".$this->scriptFolder."plesk_event_handler.sh create ".$conf["graylist-dir"]."/ <new_domain_name>')"); 
	      if($params["delete"]) mysqli_query($this->db_conn,"INSERT INTO event_handlers (action_id, priority, user, command )VALUES ( '".$handler["delete"]."', '50', 'root', '".$this->scriptFolder."plesk_event_handler.sh delete ".$conf["graylist-dir"]."/ <old_domain_name>')"); 
              if($params["update"]) mysqli_query($this->db_conn,"INSERT INTO event_handlers (action_id, priority, user, command )VALUES ( '".$handler["update"]."', '50', 'root', '".$this->scriptFolder."plesk_event_handler.sh update ".$conf["graylist-dir"]."/ <old_domain_name> <new_domain_name>')"); 

              if($params["create_rights"]) mysqli_query($this->db_conn,"INSERT INTO event_handlers (action_id, priority, user, command )VALUES ( '".$handler["create"]."', '50', 'root', '".$this->scriptFolder."plesk_event_handler.sh create_rights ".$this->scriptFolder." <new_domain_name> DEFAULT=".$params["create_rights_gl"].$params["create_rights_live"].$params["create_rights_ip"].$params["create_rights_sender"].$params["create_rights_recipient"].$params["create_rights_rdns"].$params["create_rights_keywords"].$params["create_rights_header"]."')"); 
              
	      if($params["delete_rights"]) mysqli_query($this->db_conn,"INSERT INTO event_handlers (action_id, priority, user, command )VALUES ( '".$handler["delete"]."', '50', 'root', '".$this->scriptFolder."plesk_event_handler.sh delete_rights ".$this->scriptFolder." ".$this->spamdykeFolderConfigDir." <old_domain_name>')"); 
              if($params["update_rights"]) mysqli_query($this->db_conn,"INSERT INTO event_handlers (action_id, priority, user, command )VALUES ( '".$handler["update"]."', '50', 'root', '".$this->scriptFolder."plesk_event_handler.sh update_rights ".$this->scriptFolder." ".$this->spamdykeFolderConfigDir." <old_domain_name> <new_domain_name>')"); 
              }

     function updateRechte($recht,$state){
              $this->alleRechte[$this->dieDomain][$recht] = $state;
	      }
	      
     function updateRechteMulti($data){
     	      $this->alleRechte = array();
     	      while (list($key, $val) = each($data)){
     	      	    if($val == "domrights"){
     	      	    	$handler = explode("#",$key);
     	      	    	$this->alleRechte[str_replace("_",".",$handler[0])][$handler[1]] = 1;
     	      	    	}
     	      	    }
     	      $this->schreibeRechte();
     	      }


     function schreibeRechte(){
     	      $this->checkCurrentDomains();
     	      $write = serialize($this->alleRechte);
     	      $openStream = fopen ("rights.txt","w");
	      fwrite($openStream,$write,strlen($write)); 
              fclose($openStream);
              $this->createSpamdykeTree();
              }
              
     function checkCurrentDomains(){
     
              $fixDash = (PSA_VERSION < 11.5)?"":"_";
     
     	      $qry = "SELECT domains.name as dName, domain".$fixDash."aliases.name as aName FROM psa.domains ";
     	      $qry.= "LEFT JOIN psa.domain".$fixDash."aliases ON psa.domain".$fixDash."aliases.dom_id = psa.domains.id";
     	      
     	      $res = mysqli_query($this->db_conn,$qry);
     	      while($dat = mysqli_fetch_object($res)){
     	      	    $serverdomains[] = $dat->dName;
     	      	    if(!empty($dat->aName)) $serverdomains[] = $dat->aName;
     	      	    }
     	      
     	      $serverdomains = array_unique($serverdomains);
     	      $alleRechte = $this->alleRechte;
     	      while(list($k,$v) = each($alleRechte)){
     	      	    if(!in_array($k,$serverdomains)){
     	      	    	unset($this->alleRechte[$k]);
     	      	       }
     	      	   }
     	      }              
              
     function checkSpamdykeTree($dir){
  	      $dirlist = opendir($dir);
  	      
              while ($file = readdir ($dirlist))
              {
                if ($file != '.' && $file != '..')
                {
                  $newpath = $dir.'/'.$file;
                  $level = explode('/',$newpath);
                  if (is_dir($newpath)){
                    $this->checkSpamdykeTree($newpath);
                  }
                  else{
                  
                    $str = str_replace($this->spamdykeFolderConfigDir.'/_recipient_/',"",$newpath);
                    $strArray = explode("/",$str);
                  
                    array_push($this->spamdykeFolderCustomsDirs,implode(".",array_reverse($strArray)));
                  }
                }
              }
              closedir($dirlist);
            }
              
     function createSpamdykeTree(){
     	      $org = $this->alleRechte;
     	      $this->checkSpamdykeTree($this->spamdykeFolderConfigDir.'d/_recipient_');
              $this->checkSpamdykeTree($this->spamdykeFolderConfigDir.'s/_recipient_');
     	      
     	      while (list($key,$val) = each ($org)){
     	      	    $baum[$key] = $val;
     	      	    
     	      	    $res = mysqli_query($this->db_conn,"SELECT a.name FROM psa.domainaliases AS a, psa.domains AS b WHERE b.name = '$key' AND b.id = a.dom_id");
     	      	    while($dat = mysqli_fetch_object($res)){
     	      	    	 $baum[$dat->name] = $val;
     	      	         }
     	      	    }
     	     
     	      while (list($key, $val) = each($baum)){

     	      	     $domArray = explode(".",$key);
     	      	     $suffix = (count($domArray)>2)?"s":"d";
     	      	     $writeFile = $this->spamdykeFolderConfigDir.$suffix.'/_recipient_/'.implode("/",array_reverse($domArray));
     	      	     
     	      	     while (list($key2, $val2) = each($val)){
          	      	 $html = "";
       	      	     	 $wList = $this->spamdykeFolder."/whitelist_ip_".implode("_",$domArray);
       	      	     	 $bList = $this->spamdykeFolder."/blacklist_ip_".implode("_",$domArray);	
       	      	     	 $html.="ip-whitelist-file=".$wList."\n";
       	      	     	 $html.="ip-blacklist-file=".$bList."\n";
       	      	     	 if(!file_exists($wList)){
       	      	     	     exec('./wrapper "1" "touch '.$wList.'"');
       	      	     	     }
       	      	     	 if(!file_exists($bList)){
       	      	     	     exec('./wrapper "1" "touch '.$bList.'"');
       	      	     	     }

       	      	     	 $wList = $this->spamdykeFolder."/whitelist_senders_".implode("_",$domArray);
       	      	     	 $bList = $this->spamdykeFolder."/blacklist_senders_".implode("_",$domArray);	
       	      	     	 $html.="sender-whitelist-file=".$wList."\n";
       	      	     	 $html.="sender-blacklist-file=".$bList."\n";
       	      	     	 if(!file_exists($wList)){
       	      	     	     exec('./wrapper "1" "touch '.$wList.'"');
       	      	     	     }
       	      	     	 if(!file_exists($bList)){
       	      	     	     exec('./wrapper "1" "touch '.$bList.'"');
       	      	     	     }
       	      	     	 
       	      	     	 $wList = $this->spamdykeFolder."/whitelist_recipient_".implode("_",$domArray);
       	      	     	 $bList = $this->spamdykeFolder."/blacklist_recipient_".implode("_",$domArray);	
       	      	     	 $html.="recipient-whitelist-file=".$wList."\n";
       	      	     	 $html.="recipient-blacklist-file=".$bList."\n";
       	      	     	 if(!file_exists($wList)){
       	      	     	     exec('./wrapper "1" "touch '.$wList.'"');
       	      	     	     }
       	      	     	 if(!file_exists($bList)){
       	      	     	     exec('./wrapper "1" "touch '.$bList.'"');
       	      	     	     }
       	      	     	     
       	      	     	 $wList = $this->spamdykeFolder."/whitelist_rdns_".implode("_",$domArray);
       	      	     	 $bList = $this->spamdykeFolder."/blacklist_rdns_".implode("_",$domArray);	
       	      	     	 $html.="rdns-whitelist-file=".$wList."\n";
       	      	     	 $html.="rdns-blacklist-file=".$bList."\n";
       	      	     	 if(!file_exists($wList)){
       	      	     	     exec('./wrapper "1" "touch '.$wList.'"');
       	      	     	     }
   	      	     	 if(!file_exists($bList)){
       	      	     	     exec('./wrapper "1" "touch '.$bList.'"');
       	      	     	     }

       	      	     	 $bList = $this->spamdykeFolder."/blacklist_keywords_".implode("_",$domArray);
       	      	     	 $html.="ip-in-rdns-keyword-blacklist-file=".$bList."\n";
       	      	     	 if(!file_exists($bList)){
       	      	     	     exec('./wrapper "1" "touch '.$bList.'"');
       	      	     	     }

       	      	     	 $bList = $this->spamdykeFolder."/blacklist_headers_".implode("_",$domArray);
       	      	     	 $html.="header-blacklist-file=".$bList."\n";
       	      	     	 if(!file_exists($bList)){
       	      	     	     exec('./wrapper "1" "touch '.$bList.'"');
       	      	     	     }     	      	     	      
     	                   
             	   if(!file_exists($writeFile )) exec('./wrapper "1" "mkdir '.substr($writeFile,0,strrpos($writeFile,"/")).'"');
    	           exec('./wrapper "1" "writeconfigdir '.$writeFile.'" "'.$html.'"'); 
     	                   
       	      	   }
       	      	} 	
     	  
     	      }
              
     function leseRechte(){
     	      $file = fopen("rights.txt",'r');
	      $this->alleRechte = unserialize(trim(fread($file,filesize("rights.txt"))));
	      fclose($file);
              }

     function hideMe($width){
              if($this->isAllView) echo "<div id=\"blind\" style=\"width:$width\"></div>";
              }
              
     function leseReportRechte(){
     	      $file = fopen("reports.txt",'r');
	      $this->reportStates = unserialize(trim(fread($file,filesize("reports.txt"))));
	      fclose($file);
     	      }
     	      
     function schreibeReportRechte(){
              $write = serialize($this->reportStates);
	      $openStream = fopen ("reports.txt","w");
	      fwrite($openStream,$write,strlen($write)); 
              fclose($openStream);
     	      }
	      
     function leseRemoteRechte(){
     	      $file = fopen("remote.txt",'r');
	      $this->remoteStates = unserialize(trim(fread($file,filesize("remote.txt"))));
	      fclose($file);
     	      }
     	      
     function schreibeRemoteRechte(){
              $write = serialize($this->remoteStates);
	      $openStream = fopen ("remote.txt","w");
	      fwrite($openStream,$write,strlen($write)); 
              fclose($openStream);
     	      }
     	      
     function leseAbuseRechte(){
     	      $file = fopen("abuse.txt",'r');
	      $this->abuseDetails = unserialize(trim(fread($file,filesize("abuse.txt"))));
	      fclose($file);
              if(empty($this->abuseDetails["sender"])) $this->abuseDetails["sender"] = $_SERVER["SERVER_ADMIN"];
     	      }
     	      
     function schreibeAbuseRechte(){
              $write = serialize($this->abuseDetails);
	      $openStream = fopen ("abuse.txt","w");
	      fwrite($openStream,$write,strlen($write)); 
              fclose($openStream);
     	      }

     function setSpamDykeConf($conf,$post){


              if($this->psaIntegration!=$post["psaIntegration"]){
              
                 $type = ($post["psaIntegration"])?"add":"del";
                 
                 $folder = str_replace("/index.php","",$_SERVER["SCRIPT_NAME"]);
                 
                 $sql["add"] = "INSERT INTO custom_buttons(level,level_id,place,text,url,conhelp,options,file,plan_item_name) VALUES (1,0,'navigation','Spamdyke Administration','".$folder."/index.php?cl_id=0','Spamdyke Administration','256','../..".$folder."/dslogo.gif',NULL)";
                 $sql["del"] = "DELETE FROM custom_buttons WHERE text = 'Spamdyke Administration'";

                 $res = mysqli_query($this->db_conn,$sql[$type]);
 
                 $this->reloadLeftFrame = true;
                 
                 $this->psaIntegration=$post["psaIntegration"];
              
                }
     
     	
              while (list($key, $val) = each($conf)){
                     if(!stristr($key,"config-dir")){
                        $confStart[$key] = $val;
                        }
                     }
              $conf = $confStart;
     	
     	      $conf["config-dir"] = $this->spamdykeFolderConfigDir."d";
     	      $conf["config-dirSCPADD"] = $this->spamdykeFolderConfigDir."s";
     	
              $conf["graylist-level"] = $post["graylist-level"];
              if(!empty($post["graylist-min-secs"])){
              	 $conf["graylist-min-secs"] = $post["graylist-min-secs"];
              }else{
                 unset($conf["graylist-min-secs"]);
               }
              if(!empty($post["graylist-max-secs"])){
              	 $conf["graylist-max-secs"] = $post["graylist-max-secs"];
              }else{
                 unset($conf["graylist-max-secs"]);
               }
              if(!empty($post["greeting-delay-secs"])){
              	 $conf["greeting-delay-secs"] = $post["greeting-delay-secs"];
              }else{
                 unset($conf["greeting-delay-secs"]);
               }
              if(!empty($post["connection-timeout-secs"])){
              	 $conf["connection-timeout-secs"] = $post["connection-timeout-secs"];
              }else{
                 unset($conf["connection-timeout-secs"]);
               }
              if(!empty($post["idle-timeout-secs"])){
              	 $conf["idle-timeout-secs"] = $post["idle-timeout-secs"];
              }else{
                 unset($conf["idle-timeout-secs"]);
               }
              if(!empty($post["max-recipients"])){
              	 $conf["max-recipients"] = $post["max-recipients"];
              }else{
                 unset($conf["max-recipients"]);
               }     
               $otResets = array();          
              while(list($otkey,$otval) = each($this->otherSettingsAll)){
                    if(is_numeric($otkey)){
                       $otResets[] = $otval;
                       }
                    else{
                       $otResets[] = $otkey;
                       }
                    }
              reset($this->otherSettingsAll);
              
              while (list($key, $val) = each($conf)){
                     if(!stristr($key,"dns-blacklist-entry") && !stristr($key,"dns-whitelist-entry") && !stristr($key,"rhs-blacklist-entry") && !preg_match("(".implode("|",$otResets).")",$key)){
                        $confNew[$key] = $val;
                        }
                     }
              $conf = $confNew;
              
              

              while (list($key, $val) = each($post["dns-blacklist-entry"])){
                     if(!empty($val)){
                        $key = ($post["dns-blacklist-entry-activ"][$key])?"dns-blacklist-entrySCP$key":"#dns-blacklist-entrySCP$key";
                        $conf[$key] = $val;
                        }
                     }
                     
              while (list($key, $val) = each($post["dns-whitelist-entry"])){
                     if(!empty($val)){
                        $key = ($post["dns-whitelist-entry-activ"][$key])?"dns-whitelist-entrySCP$key":"#dns-whitelist-entrySCP$key";
                        $conf[$key] = $val;
                        }
                     }                     

              while (list($key, $val) = each($post["rhs-blacklist-entry"])){
                     if(!empty($val)){
                        $key = ($post["rhs-blacklist-entry-activ"][$key])?"rhs-blacklist-entrySCP$key":"#rhs-blacklist-entrySCP$key";
                        $conf[$key] = $val;
                        }
                     }                                          

              if(!empty($post["new_dnsbl"])){
                 $conf["dns-blacklist-entrySCPNEW"] = $post["new_dnsbl"];
                }

              if(!empty($post["new_dnsrhsbl"])){
                 $conf["rhs-blacklist-entrySCPNEW"] = $post["new_dnsrhsbl"];
                }

              if(!empty($post["new_dnswl"])){
                 $conf["dns-whitelist-entrySCPNEW"] = $post["new_dnswl"];
                }


              while (list($key, $val) = each($post["conf-others"])){
                     if(strpos($key, "/") > 0){
                        $OtherConfFile = explode("=",$key);
                        exec('./wrapper "1" "writespamdykeconf '.$OtherConfFile[1].'" "'.$val.'"');
                        $val = $key;
                        } 

                     if(!empty($val)){
                        $conf[$val] = "";
                        }
                     }           
              

              while (list($key, $val) = each($conf)){
              	
              	if(!strstr($key,"_MY")){
              	   $key = preg_replace("#SCP(.*)#","",$key);
                   $confFile.= (empty($val))?"$key\n":"$key=$val\n";
                   }
        	}

     	      if($this->spamdykeWatchdog != $post["spamdykeWatchdog"]){
     	      	 $on = ($post["spamdykeWatchdog"])?"on":"off";
               $chmod = substr($this->scriptFolder,0,strlen($this->scriptFolder)-1);
     	        $cron = 'checkspamdyke.sh';
     	      	 exec('./wrapper "1" "spamdykeWatchdog_'.$on.'" "'.$chmod.'" "'.$cron.'"');
     	      	 $this->spamdykeWatchdog = $post["spamdykeWatchdog"];
     	        }

              //echo nl2br($confFile);
              exec('./wrapper "1" "writespamdykeconf '.SPAMDYKE_CONFIG.'" "'.$confFile.'"');

              }

      function getDnsblList($conf){

               $count = 1;
               while (list($key, $val) = each($conf)){
                      if(stristr($key,"dns-blacklist-entry")){
                         $checked = stristr($key,"#")?"":"checked";
                         echo "<tr>";
                         echo "<td>&nbsp;</td>";
                         echo "<td><input type=\"checkbox\" name=\"dns-blacklist-entry-activ[$count]\" value=\"1\" $checked></td>";
                         echo "<td><input type=\"text\" size=\"50\" name=\"dns-blacklist-entry[$count]\" value=\"$val\"></td>";
                         echo "</tr>";
                         $count++;
                         }
                      }
               }
               
      function getDnsrhsblList($conf){

               $count = 1;
               while (list($key, $val) = each($conf)){
                      if(stristr($key,"rhs-blacklist-entry")){
                         $checked = stristr($key,"#")?"":"checked";
                         echo "<tr>";
                         echo "<td>&nbsp;</td>";
                         echo "<td><input type=\"checkbox\" name=\"rhs-blacklist-entry-activ[$count]\" value=\"1\" $checked></td>";
                         echo "<td><input type=\"text\" size=\"50\" name=\"rhs-blacklist-entry[$count]\" value=\"$val\"></td>";
                         echo "</tr>";
                         $count++;
                         }
                      }
               }               
               
      function getDnswlList($conf){

               $count = 1;
               while (list($key, $val) = each($conf)){
                      if(stristr($key,"dns-whitelist-entry")){
                         $checked = stristr($key,"#")?"":"checked";
                         echo "<tr>";
                         echo "<td>&nbsp;</td>";
                         echo "<td><input type=\"checkbox\" name=\"dns-whitelist-entry-activ[$count]\" value=\"1\" $checked></td>";
                         echo "<td><input type=\"text\" size=\"50\" name=\"dns-whitelist-entry[$count]\" value=\"$val\"></td>";
                         echo "</tr>";
                         $count++;
                         }
                      }
               }

      function getOtherSettings($conf,$ver){

               
               preg_match("/spamdyke (.*)\+/",$this->spamDykeVersion,$treffer);
               $treffer = preg_replace("[^0-9\.]","",$treffer[1]);

               
               if(version_compare($treffer, "5.0.0", '>=') && version_compare($ver, "5.0.0", '<')){
                  unset($this->otherSettings[$ver]);
                  }
               
               if(version_compare($treffer, "5.0.0", '<') && version_compare($ver, "5.0.0", '>=')){
                  unset($this->otherSettings[$ver]);
                  }
               
               $count = $ver;
               
               if(version_compare($treffer, $ver, '<')){
               	  $isDisabled = "disabled";
               	  $whyDisabled = sprintf(SCP_ALLG_SPAMDYKE_OTHER_WRONG_VERSION,$ver);
               	  $colorDisabled = "style=\"color:#CCCCCC\"";
               	  }
               else{
                  $isDisabled = "";
               	  $whyDisabled = "";
               	  $colorDisabled = "";
                  }
             
               if(version_compare($treffer, "5.0.0", '>=')){
                   while(list($k,$v)=each($this->otherSettings[$ver])){
                       while (list($key, $val) = each($conf)){
                              $found="";
                              if(trim(str_replace("#","",$key)) == $k){
                                  $found=$key;
                                  if(!empty($val))$found.="=".$val;
                                  break;
                                  }
                              }
                       reset($conf);
                   
                       echo "<tr>";
                       echo "<td></td>";
                       if($v=="checkbox"){
                          $check = ($k == $found)?" checked":"";
                          echo "<td><input type=\"checkbox\" name=\"conf-others[$count]\" value=\"".$k."\"".$check."></td>";
                          echo "<td>".constant("SCP_ALLG_SPAMDYKE_OTHER_".strtoupper($k))."</td>";
                          }
                       if(is_array($v)){
                          echo "<td>";
                          echo "<select name=\"conf-others[$count]\" style=\"width:175px;font-size:10px\">";
                          for($iii=0;$iii<count($v);$iii++){
                               $check = ($k."=".$v[$iii] == $found)?" selected":"";
                               echo "<option value=\"".$k."=".$v[$iii]."\"".$check.">".constant("SCP_ALLG_SPAMDYKE_OTHER_".strtoupper($k)."_".strtoupper($v[$iii]))."</option>";
                              }
                          echo "</select>";
                          
                          echo "</td>";
                          echo "<td>".constant("SCP_ALLG_SPAMDYKE_OTHER_".strtoupper($k))."</td>";
                          }
                       if(strpos($v, "/") === 0){
                          $out = shell_exec('./wrapper "1" "readconf '.$this->spamdykeFolder.$v.'"');
                          
                          echo "<td><textarea name=\"conf-others[".$k."=".$this->spamdykeFolder.$v."]\" style=\"width:175px;height:55px\">".$out."</textarea></td>";
                          echo "<td>".constant("SCP_ALLG_SPAMDYKE_OTHER_".strtoupper($k))."</td>";
                          }
                       echo "</tr>";
                       $count++;
                      }
               
               
               }
               else{
                   while (list($key, $val) = each($conf)){
                      $found = array_search(trim(str_replace("#","",$key)),$this->otherSettings[$ver]);
                      if($found > -1){
                         $checked = stristr($key,"#")?"":"checked";
                         echo "<tr title=\"$whyDisabled\" alt=\"$whyDisabled\" $colorDisabled>";
                         echo "<td>&nbsp;</td>";
                         echo "<td><input title=\"$whyDisabled\" alt=\"$whyDisabled\" type=\"checkbox\" name=\"conf-others[$count]\" value=\"$key\" $checked $isDisabled></td>";
                         echo "<td>".constant("SCP_ALLG_SPAMDYKE_OTHER_".strtoupper(str_replace("#","",$key)))."</td>";
                         echo "</tr>";
                         $count++;
                         unset($this->otherSettings[$ver][$found]);
                         }
                      }
                   sort($this->otherSettings[$ver]);
                   for($r=0;$r<count($this->otherSettings[$ver]);$r++){
                       echo "<tr>";
                       echo "<td>&nbsp;</td>";
                       echo "<td><input type=\"checkbox\" name=\"conf-others[$count]\" value=\"".$this->otherSettings[$ver][$r]."\"></td>";
                       echo "<td>".constant("SCP_ALLG_SPAMDYKE_OTHER_".strtoupper($this->otherSettings[$ver][$r]))."</td>";
                       echo "</tr>";
                       $count++;
                      }
                  }

               }
               
      function viewAdmPart($part){
      	       echo '<script type="text/javascript">';
      	       if(!empty($part)){
      	       	  echo 'adminView(\''.$part.'\');';
      	       	  }
      	       echo 'ermittlePids();';
	       echo '</script>';
      	       }
      	
      function actSpamdykeProc($kill=false){
      	
      	       if($kill){
      	          exec('./wrapper "1" "killallspamdyke"');
      	       }else{      	        
      	          exec('ps -ef | grep spamdyke', $out); 
      	          echo count($out);
      	          }
      	       }

     function getSpamdykeVer($output=true){
              exec('./wrapper "1" "spamdykeversion"',$out);
              $this->spamDykeVersion = $out[0];
              if($output)echo $out[0];
              }

     function showReseller(){
               $res = array();
               $r = mysqli_query($this->db_conn,"SELECT id,cname FROM `clients` where type = 'reseller'");
               while($data=mysqli_fetch_array($r)){
                     $check = (in_array($data["id"],$this->remoteStates["reseller"]))?" checked":"";
                     echo "<input type=\"checkbox\" name=\"remote_reseller[]\" size=\"1\" value=\"".$data["id"]."\"".$check.">".$data["cname"]."\n<br>";
                     }
              }
      	       
     function viewAllDomains($conf){
     	      $tmpDom = $this->dieDomain;
     	      $res = mysqli_query($this->db_conn,"SELECT name FROM psa.domains");
     	      $c = 0;
     	      while($data = mysqli_fetch_object($res)){
     	      	   $farbe = ($c%2)?"bgcolor='#CCCCCC'":"";
     	      	   echo '<tr '.$farbe .'>';
     	      	   echo '<td><a href="javascript:toggleDomain(\''.$data->name.'\')"><img src="toggleAllNone.gif" border="0" alt="'.SCP_ADMIN_ALLNONE_SELECTION.'" title="'.SCP_ADMIN_ALLNONE_SELECTION.'"></a></td>';
     	      	   echo '<td>'.$data->name.'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'gl').'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'live').'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'ip').'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'sender').'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'recipient').'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'rdns').'</td>';
     	      	   echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'keywords').'</td>';
     	      	   if(array_key_exists("header-blacklist-file",$conf)) echo '<td colspan="2">'.$this->getDomainRightMulti($data->name,'header').'</td>';
     	      	   $c++;
     	      	   }
     	      }
     	      
     function updateReportSettings($post){
     	
     	      $psa = substr($this->scriptFolder,0,strpos($this->scriptFolder,"admin")+5)."/bin/php";
     	      
     	      $chmod = substr($this->scriptFolder,0,strlen($this->scriptFolder)-1);
     	      $cron = $psa.' dailyreport.php';
     	
     	      if($this->isReportOn != $post["report_on"]){
     	      	 $on = ($post["report_on"])?"on":"off";
     	      	 exec('./wrapper "1" "reports_'.$on.'" "'.$chmod.'" "'.$cron.'"');
     	      	 $this->isReportOn = $post["report_on"];
     	        }
     	         
     	         
     	         
     	         
     	         
     	       
     	      $rechte["admin"] = $this->reportStates["admin"];
     	      $rechte["user"] = $this->reportStates["user"];
     	      
     	         
     	      if((is_array($this->reportStates["admin"]) != $post["report_admin"]) || $_POST["report_admin_email"] != $rechte["admin"][0]){
      	      	 $rechte["admin"] = false;
     	      	 if($post["report_admin"])$rechte["admin"] = array($_POST["report_admin_email"]);
     	      	 $this->reportStates["admin"] = $rechte["admin"];
     	      	 }
     	
     	      if(is_array($this->reportStates["user"]) != $post["report_user"]){
     	      	 $rechte["user"] = false;
     	      	 if($post["report_user"])$rechte["user"] = array();
     	      	 $this->reportStates["user"] = $rechte["user"];
     	      	 }
     	      	 
     	      $this->schreibeReportRechte();
     	
              } 
     
      function updateRemoteSettings($post){
               if(is_array($this->remoteStates["user"]) != $post["remote_user"]){
     	      	    $remote["user"] = false;
     	      	    if($post["remote_user"])$remote["user"] = array();
     	      	    $this->remoteStates["user"] = $remote["user"];
     	      	    }

                $this->remoteStates["reseller"] = $post["remote_reseller"];
     	      	    
     	      	if(!empty($this->remoteStates["admin"]) != $post["remote_admin"]){
     	      	    $remote["admin"] = 0;
     	      	    if($post["remote_admin"])$remote["admin"] = $this->xtea->Encrypt(rand(1000000,9999999));
     	      	    $this->remoteStates["admin"] = $remote["admin"];
     	      	    }
     	      	 
     	      	 
     	      $this->schreibeRemoteRechte();
     	      }
              


      function updateAbuseSettings($post){
      	       $this->abuseDetails = $post["spam-abuse"];
      	       $this->schreibeAbuseRechte();
      	       }
      	       
      function isSpamReport(){
      	
      	       if(empty($this->abuseDetails["sender"]) ||
        	  empty($this->abuseDetails["titel"]) ||
        	  empty($this->abuseDetails["template"])) return 0;
        	  
               return 1;	  
      	
      	       }

      function sendSpamReport($data){
               if(!$this->isSpamReport()){
                  echo "NO RIGHTS TO SEND AN ABUSE MAIL!";
                  exit;
                  }
             
               $spamline = "==============================================\n";
               $spamline.= "FROM: ".$data["from"]."\n";
               $spamline.= "TO: ".$data["to"]."\n";
               $spamline.= "IP: ".$data["ip"]."\n";
               $spamline.= "RDNS: ".$data["rdns"]."\n";
               $spamline.= "TIMESTAMP: ".$data["stamp"]."\n";
               $spamline.= "==============================================";

               $nachricht = str_replace("{SPAMLINE}",$spamline,$this->abuseDetails["template"]);
               $header = 'From: '.$this->abuseDetails["sender"]. "\r\n";
               $header.= 'Reply-To: '.$this->abuseDetails["sender"]. "\r\n";
               $header.= 'Bcc: '.$this->abuseDetails["sender"]. "\r\n";
               $header.= 'SCP2[haggybear.de]/X-Mailer: PHP/' . phpversion();

               $isSend = mail($data["rec"], $this->abuseDetails["titel"], $nachricht, $header);

               if(!$isSend){
                 echo "ERROR WHILE SENDING THE MAIL!";
                 exit;
                 }

               echo "emailwassend";

               }
               
      function spamdykeConfigCheck(){
               if(!file_exists("_s.5.0.0")){
                $this->getSpamdykeVer(false);
                preg_match("/spamdyke (.*)\+/",$this->spamDykeVersion,$treffer);
                $treffer = preg_replace("[^0-9\.]","",$treffer[1]);
               
                if(version_compare($treffer, "5.0.0", '>=')){
                  exec('./wrapper "1" "spamdyke5config '.SPAMDYKE_CONFIG.'"');
                  return "<br><div class=\"confupd\">".sprintf(SCP_CONFIG_UPDATE,$treffer)."</div>";
                  }      
                }
               return "";
               }               

     }













?>