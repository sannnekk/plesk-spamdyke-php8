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
require("./paa.class.php");

class readlog extends paa{


      var $versionUrl = "http://www.haggybear.de/download/scp2";

      var $scpAllowed = false;
      
      var $maxStrLen = 30;

      var $queryString;      

      var $fehler;

      var $erfolg;

      var $domainId;

      var $allDomains;
      
      var $allDomainsWithMail;
      
      var $infoLog;
      
      var $errorLog;
      
      var $blocked = 0;
      
      var $delayed = array();

      var $blockedType = array();
      
      var $allowed = 0;
      
      var $logTable;

      var $spamDykeConf;

      var $spamDykeInstalled = true;

      var $spamDykeConfError = false;

      var $allLists;

      var $whichList;

      var $dieRechte;
 
      var $anzVars;
      
      var $suchmuster = false;

      var $logRotates;
      
      var $xtea;
      
         function __construct($sess,$getVars,$db){
                 parent::paa($sess,$getVars["dom_name"],$db);
	         parent::openDatabase();
	         parent::setPleskSkin();
	         parent::setPleskAllowed();
	         $this->domainId = $getVars["dom_id"];
	         parent::setPleskAllowedDomains($getVars["cl_id"]);
	         
         	 $this->domainId = (PSA_VERSION >= 12)?$_SESSION["subscription"]["currentId"]:$_SESSION["subscriptionId"]->current;
	         parent::psa10_domainGrab();
	         	         
	         $this->setSpamDykeConf();
	         $this->setScpAllowed();
	         parent::setOnlyAdm($getVars["onlyadm"]);
                 $this->xtea = new XTEA(CRYPT_KEY);
	         $this->dieRechte = new AdminRights($this->plesk_domain,$this->spamDykeConf["graylist-dir"],$this->xtea,$this->plesk_db_conn);


                 
                 $this->anzVars["anz_PerPage"] = DEFAULT_PERPAGE;
                 $this->anzVars["anz_GlPaar"] = false;
                 $this->anzVars["anz_alleAddr"] = false;
                 $this->anzVars["anz_Filter"] = false;
                 $this->anzVars["anz_Richtung"] = false;
                 $this->anzVars["anz_Von"] = false;
                 $this->anzVars["anz_LiveCountry"] = false;
                 }

        function __destruct(){
                 parent::closeDatabase();
                 }

        function setLogRotates(){

                 $res = mysqli_query($this->plesk_db_conn, "SELECT time FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table group by DATE_FORMAT(time,'%d.%m.%y') order by time desc");
                 while($data = mysqli_fetch_object($res)){
                       $this->logRotates[] = $data->time;
                       }
                 }

	function getAllDomainsAndAliases(){
	

	         $doms[] = $this->plesk_domain;
	         $doms = parent::psa10_aboGrab($doms);
	         
		 $domsWithMail = $doms;

	         $hasDomAlias = false;
	         
	         if(mysqli_num_rows(mysqli_query($this->plesk_db_conn, "SHOW TABLES LIKE 'domainaliases'"))>0) $hasDomAlias = true;
                 $result = mysqli_query($this->plesk_db_conn, "SELECT a.id,a.mail, a.name, b.id FROM psa.domainaliases AS a, psa.domains AS b WHERE b.name = '".$this->plesk_domain."' AND b.id = a.dom_id");
                 if($result && $hasDomAlias){
                    while($data = mysqli_fetch_object($result)){
                          $doms[] = $data->name;
			  if($data->mail == "true") $domsWithMail[] = $data->name;
	                  }
	            }
		 $this->allDomains = $doms;
		 $this->allDomainsWithMail = $domsWithMail;

	         }

	function getAllMailAddr(){
		$addys[] = "";
		$w = "";
		if(!$this->dieRechte->isAllView){
		   $w = "d.name in ('".implode("','",$this->allDomainsWithMail)."') and";	
		   }
		$result = mysqli_query($this->plesk_db_conn, "select m.id, m.mail_name from psa.mail m, psa.domains d where $w dom_id = d.id");
		if($result){
		   while($data = mysqli_fetch_object($result)){
		         foreach($this->allDomainsWithMail as $my_dom){
		  		 $addys[] = $data->mail_name.'@'.$my_dom;
				 }
		         }
		   }
		$result = mysqli_query($this->plesk_db_conn, "SELECT m.id, a.alias FROM psa.mail m, psa.domains d,mail_aliases a WHERE $w dom_id = d.id AND a.mn_id = m.id");
		if($result){
		   while($data = mysqli_fetch_object($result)){
		         foreach($this->allDomainsWithMail as $my_dom){
		  		 $addys[] = $data->alias.'@'.$my_dom;
				 }
		         }
		   }
		

		return $addys;
		
	        }
	
	function setScpAllowed(){
 
		 if($this->plesk_session->chkLevel(IS_ADMIN)){
                 $this->scpAllowed = true;
                 return;
                 }
 
               }
               
       function getAllowedDoms(){
       	        
       	        if(count($this->plesk_allowed_domains)==1) return $this->plesk_allowed_domains[0];
       	        
       	        echo "<select class=\"name\" name=\"doms\" onChange=\"document.location.href=this.value\">\n";
       	        while (list($key, $val) = each($this->plesk_allowed_domains)){
       	               $selected = ($this->plesk_domain == $key)?"selected":"";	
                      echo "<option value=\"".$val."\" $selected>".$key."</option>\n";
       	              }
		echo "</select>";
		
       	        
       	        }

       function canUse($recht){
               if($this->plesk_session->chkLevel(IS_ADMIN)) return true;

               if($this->dieRechte->alleRechte[$this->allDomains[0]][$recht]) return true;

               return false;               

               }

      function randomColor(){
               $zr=mt_rand(127,255);
               $zg=mt_rand(127,255);
               $zb=mt_rand(127,255);
               
	       $r = dechex($zr<0?0:($zr>255?255:$zr));
               $g = dechex($zg<0?0:($zg>255?255:$zg));
               $b = dechex($zb<0?0:($zb>255?255:$zb));

               $color = (strlen($r) < 2?'0':'').$r;
               $color .= (strlen($g) < 2?'0':'').$g;
               $color .= (strlen($b) < 2?'0':'').$b;
               $farbe = '#'.$color;
               
               #$farbe="rgb(".$zr.",".$zg.",".$zb.");";

               return $farbe;

               }


       function setSpamDykeConf(){
                $neededLoglevels = array("info","verbose","debug","excessive");
		$this->spamDykeConf = array();

                exec('./wrapper "1" "readconf '.SPAMDYKE_CONFIG.'"', $out);
                if(count($out)>2){
                  
                   for($i=0;$i<count($out);$i++){
                      $tmp = explode("=",$out[$i]);
                      if(isset($this->spamDykeConf[$tmp[0]])) $tmp[0].="SCP".rand(0,10000);
                      $this->spamDykeConf[$tmp[0]] = $tmp[1];
                      }

                }else{
                $this->spamDykeInstalled = false;
                return;
                }
                
                $domArray = explode(".",$this->plesk_domain);
                
                $suffix = (count($domArray)>2)?"s":"d";
                
                $myFile = substr($this->spamDykeConf["graylist-dir"],0,strrpos($this->spamDykeConf["graylist-dir"],"/"))."/conf.".$suffix."/_recipient_/".implode("/",array_reverse($domArray));

                exec('./wrapper "1" "readconf '.$myFile.'"', $outOwn);
                if(file_exists($myFile)){
                   for($i=0;$i<count($outOwn);$i++){
                      $tmp = explode("=",$outOwn[$i]);
                      if(!empty($tmp[1]))$this->spamDykeConf[$tmp[0]."_MY"] = $tmp[1];
                      }	
                   }

                if(empty($this->spamDykeConf["log-level"])){
                  $this->spamDykeConfError = SCP_NO_LOG_LEVEL;
                  $this->spamDykeInstalled = false;
                }else if(empty($this->spamDykeConf["config-mysql-database"])){
                  $this->spamDykeConfError = SCP_NO_DATABASE_SUPPORT;
                  $this->spamDykeInstalled = false;
                }else if(!in_array($this->spamDykeConf["log-level"],$neededLoglevels)){
                  $this->spamDykeConfError = SCP_WRONG_LOG_LEVEL;
                  $this->spamDykeInstalled = false;
                }

               $filePerms = substr(decoct( fileperms('./wrapper') ), 2);

               if($filePerms != 4755){
                  $this->spamDykeConfError = SCP_WRONG_FILEPERMS;
                  $this->spamDykeInstalled = false;
                 }
   }

     function getScpAllowed(){
              return $this->scpAllowed;
              }
              

     function checkVersion($v){
     
                $delay = ((time() - filemtime("server.ver")) / 60);
		
		if($delay > 60){
		   $url = $this->versionUrl.".txt";
 	   	   $p = fopen($url,"r");
	 	   $ver  = fgets($p,16);
	 	   fclose($fp);
		   $dateihandle = fopen("server.ver","w");
		   fwrite($dateihandle, $ver);
		   fclose($dateihandle);
		   }
		else{
		   $url = "server.ver";
 	   	   $p = fopen($url,"r");
	 	   $ver  = fgets($p,16);
	 	   fclose($fp);
		   }
		
		$dateihandle = fopen("server.ver","w");
		fwrite($dateihandle, $ver);
		fclose($dateihandle);

		if($this->plesk_session->chkLevel(IS_ADMIN) && AUTOUPDATE){
	           if($v != $ver){ 
 		      echo "<a href=\"?".$this->queryString."&action=update\">".str_replace("{VER}",$ver,SCP_VERSION_UPD)."</a>";
		      return;
		      }
	        }
	     
	 	if($v != $ver){
		   echo "<a href=\"".$this->versionUrl.".zip\">".str_replace("{VER}",$ver,SCP_VERSION_NOK)."</a>";
		   return;
		   }
	 
	 	echo SCP_VERSION_OK;
        	}
		
	     
     function setQueryString($qs){
            	$expr = '/statmessage=(.*)&/i';
		$qs = preg_replace($expr, '', $qs);
		$expr = '/&todo=(.*)/i';
		$qs = preg_replace($expr, '', $qs);
		$expr = '/&admin=(.*)/i';
		$qs = preg_replace($expr, '', $qs);
                $expr = '/&action=(.*)/i';
		$qs = preg_replace($expr, '', $qs);
		$this->queryString = $qs;
              }

     function getQueryString(){
              echo $this->queryString;
              }

     function getError(){
              if(empty($this->fehler))return;
              $this->fehler[] = "&nbsp;";
              echo "<span class=\"fatred\">&nbsp;&nbsp;".implode("<br>&nbsp;&nbsp;",$this->fehler)."<span>";

              }

     function getErfolg(){
              if(empty($this->erfolg))return;
              $this->erfolg[] = "&nbsp;";
              echo "<span class=\"fatgreen\">&nbsp;&nbsp;".implode("<br>&nbsp;&nbsp;",$this->erfolg)."<span>";

              }


     function readQmailLogs(){
              $this->setLogRotates();
	      $this->getAllDomainsAndAliases();
              $dasLog = substr($this->logRotates[0],0,10);

              if(!$this->dieRechte->isAllView){
	         $searchStr = "AND ( `to` LIKE '%@".implode("' OR `to` LIKE '%@",$this->allDomains)."' or ";
                 $searchStr.= " `from` LIKE '%@".implode("' OR `from` LIKE '%@",$this->allDomains)."')";
		 }

              if($this->anzVars["anz_Von"]) $dasLog = $this->anzVars["anz_Von"];

              

              $res = mysqli_query($this->plesk_db_conn, "SELECT id,reason FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE date_format(time,'%Y-%m-%d') = '".$dasLog."' ".$searchStr." AND reason != 'allowed'");
              while($data = mysqli_fetch_array($res)){
                    $this->blocked++;
                    $this->blockedType[$data["reason"]]++;
                    }

              $res = mysqli_query($this->plesk_db_conn, "SELECT * FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE date_format(time,'%Y-%m-%d') = '".$dasLog."' ".$searchStr." AND reason = 'allowed'");
              while($data = mysqli_fetch_array($res)){
                    $this->allowed++;
                       if($this->checkLogTableForFormerGreylisting($data)){
                          $this->blocked--;
                          $this->blockedType["DENIED_GRAYLISTED"]--;
                          }
                    }

             #echo "<hr>Dauer: ".($stop-$start)." sek.";
                    

	      }

     function readList($type){

              exec('find . -type f -name "'.str_replace("/","~~",$this->spamDykeConf[$type."-file"]).'"',$exOut);
              $exReplace = array();
              for($o=0;$o<count($exOut);$o++){
                  $entries = file($exOut[$o]);
                  $fileArr = explode("/",$exOut[$o]);
                  for($oo=0;$oo<count($entries);$oo++){
                      if(empty($entries[$oo]))continue;
                      $exReplace[trim($entries[$oo])] = " [EXPIRES: ".$fileArr[2]."]";
                      }

                  }

              exec('./wrapper "1" "readlists cat '.$this->spamDykeConf[$type."-file"].'"', $globalOut);
              $this->allLists["my"][$type] = array();
              $this->allLists["global"][$type] = array();

              $this->whichList = ($this->getAdmin()->isAllView)?"global":"my";
                  for($i=0;$i<count($globalOut);$i++){
                     if(array_key_exists(trim($globalOut[$i]),$exReplace)) $globalOut[$i].=$exReplace[trim($globalOut[$i])]; 	
		     if(trim($globalOut[$i]))$this->allLists["global"][$type][] = $globalOut[$i]; 
                  }

                  
              if(file_exists($this->spamDykeConf[$type."-file_MY"])){
                 exec('find . -type f -name "'.str_replace("/","~~",$this->spamDykeConf[$type."-file_MY"]).'"',$exOut);
                 $exReplace = array();
                 for($o=0;$o<count($exOut);$o++){
                     $entries = file($exOut[$o]);
                     $fileArr = explode("/",$exOut[$o]);
                     for($oo=0;$oo<count($entries);$oo++){
                         if(empty($entries[$oo]))continue;
                         $exReplace[trim($entries[$oo])] = " [EXPIRES: ".$fileArr[2]."]";
                         }
                     }

                 exec('./wrapper "1" "readlists cat '.$this->spamDykeConf[$type."-file_MY"].'"', $myOut);
                 for($i=0;$i<count($myOut);$i++){
                     if(array_key_exists(trim($myOut[$i]),$exReplace)) $myOut[$i].=$exReplace[trim($myOut[$i])];
		     if(trim($myOut[$i]))$this->allLists["my"][$type][] = $myOut[$i]; 
                     }
                 }
 
              }

     function setAllLists($data){
             $suffix = (!$this->dieRechte->isAllView)?"_MY":"";

             $isWhite = (isset($data["whitelist"]))?true:false;             
	     $isBlack = (isset($data["blacklist"]))?true:false;  

             if($isWhite) $tmpWhite = explode("\n",$data["whitelist"]);
             if($isBlack) $tmpBlack = explode("\n",$data["blacklist"]);

             $expWhite = array();
             $expBlack = array();

             for($i=0;$i<count($tmpWhite);$i++){
                 if(stristr($tmpWhite[$i]," [EXPIRES:")){
                    preg_match("#\[EXPIRES:(.*)\]#si", $tmpWhite[$i], $treffer);
                    $tmpWhite[$i] = trim(preg_replace("#\[EXPIRES:(.*)\]#si", "", $tmpWhite[$i]));
                    $expWhite[$treffer[1]][] = $tmpWhite[$i];
                    }

                 }

             for($i=0;$i<count($tmpBlack);$i++){
                 if(stristr($tmpBlack[$i]," [EXPIRES:")){
                    preg_match("#\[EXPIRES:(.*)\]#si", $tmpBlack[$i], $treffer);
                    $tmpBlack[$i] = trim(preg_replace("#\[EXPIRES:(.*)\]#si", "", $tmpBlack[$i]));
                    $expBlack[$treffer[1]][] = $tmpBlack[$i];
                    }

                 }
      


             if($isWhite){
                $myWhite = trim(implode("\n",$tmpWhite));
                $check = empty($myWhite);
                if(!$check)$saveWhite.= implode("\n",$tmpWhite);
                }
	     if($isBlack){
                $myBlack = trim(implode("\n",$tmpBlack));
                $check = empty($myBlack);
                if(!$check)$saveBlack.= implode("\n",$tmpBlack);
                }

             if($isWhite){
                exec('./wrapper "1" "writeconf '.$this->spamDykeConf[$data["type"]."-whitelist-file".$suffix].'" "'.$saveWhite.'"');
                exec('find . -type f -name "'.str_replace("/","~~",$this->spamDykeConf[$data["type"]."-whitelist-file".$suffix]).'" -exec rm -f {} \;'); 

                if(count($expWhite)>0){
                   while(list($k,$v)=each($expWhite)){
                         mkdir("EXPIRES/".trim($k));
                         for($iii=0;$iii<count($v);$iii++){
                             file_put_contents("EXPIRES/".trim($k)."/".str_replace("/","~~",$this->spamDykeConf[$data["type"]."-whitelist-file".$suffix]),$v[$iii]."\n",FILE_APPEND);
                             }
                      
                         }

                   }
                reset($expWhite);
                if(!$this->dieRechte->isAllView){
                   for($i=1;$i<count($this->allDomains);$i++){
                       $path = str_replace(str_replace(".","_",$this->allDomains[0]),str_replace(".","_",$this->allDomains[$i]),$this->spamDykeConf[$data["type"]."-whitelist-file".$suffix]);
                       exec('find . -type f -name "'.str_replace("/","~~",$path).'" -exec rm -f {} \;'); 

                       exec('./wrapper "1" "writeconf '.$path.'" "'.$saveWhite.'"');
                       if(count($expWhite)>0){
                          while(list($k,$v)=each($expWhite)){
                                mkdir("EXPIRES/".trim($k));
                                for($iii=0;$iii<count($v);$iii++){
                                    file_put_contents("EXPIRES/".trim($k)."/".str_replace("/","~~",$path),$v[$iii]."\n",FILE_APPEND);
                                    }
                      
                                }

                          }
                          reset($expWhite);
                       }
                       
                   }
                }
	     if($isBlack){
                exec('./wrapper "1" "writeconf '.$this->spamDykeConf[$data["type"]."-blacklist-file".$suffix].'" "'.$saveBlack.'"');
                exec('find . -type f -name "'.str_replace("/","~~",$this->spamDykeConf[$data["type"]."-blacklist-file".$suffix]).'" -exec rm -f {} \;');
                if(count($expBlack)>0){
                   while(list($k,$v)=each($expBlack)){
                         mkdir("EXPIRES/".trim($k));
                         for($iii=0;$iii<count($v);$iii++){
                             file_put_contents("EXPIRES/".trim($k)."/".str_replace("/","~~",$this->spamDykeConf[$data["type"]."-blacklist-file".$suffix]),$v[$iii]."\n",FILE_APPEND);
                             }
                      
                         }

                   }
                reset($expBlack);
                if(!$this->dieRechte->isAllView){
                   for($i=1;$i<count($this->allDomains);$i++){
                       $path = str_replace(str_replace(".","_",$this->allDomains[0]),str_replace(".","_",$this->allDomains[$i]),$this->spamDykeConf[$data["type"]."-blacklist-file".$suffix]);
                       exec('find . -type f -name "'.str_replace("/","~~",$path).'" -exec rm -f {} \;');
                       exec('./wrapper "1" "writeconf '.$path.'" "'.$saveBlack.'"');
			if(count($expBlack)>0){
                          while(list($k,$v)=each($expBlack)){
                                mkdir("EXPIRES/".trim($k));
                                for($iii=0;$iii<count($v);$iii++){
                                    file_put_contents("EXPIRES/".trim($k)."/".str_replace("/","~~",$path),$v[$iii]."\n",FILE_APPEND);
                                    }
                      
                                }

                          }
                          reset($expBlack);
                       }
                   }
                }

             if($isWhite) $this->readList($data["type"]."-whitelist");
             if($isBlack) $this->readList($data["type"]."-blacklist");

     	     $psa = substr($this->dieRechte->scriptFolder,0,strpos($this->dieRechte->scriptFolder,"admin")+5)."/bin/php";
     	      
     	     $chmod = substr($this->dieRechte->scriptFolder,0,strlen($this->dieRechte->scriptFolder)-1);
     	     $cron = $psa.' killexpiredlists.php';
   	     exec('./wrapper "1" "expires_on" "'.$chmod.'" "'.$cron.'"');
     	     
             }

     function checkForGreylisting($val,$edit){

              if(trim($val["isgreylisted"]) != "" && (trim($val["adminsave"]) == "" && trim($val["jetztsuchen"]) == "")) $this->changeGreylisting($val);
     
              $select = (is_dir($this->spamDykeConf["graylist-dir"]."/".$this->allDomains[0]))?"selected":"";
	      
	      if($this->spamDykeConf["graylist-level"] == "always-create-dir"){
                 echo VALUE_ACT;
                }
              else if($this->spamDykeConf["graylist-level"] == "none"){
                 echo VALUE_DEACT;
                }
              else{
              
                 if($edit){
                    echo "<select onChange=\"document.scpedit.isgreylisted.value = this.value;document.scpedit.submit()\">\n";
	            echo "<option value=\"0\">".VALUE_DEACT."</option>\n";
	            echo "<option value=\"1\" ".$select.">".VALUE_ACT."</option>\n";
	            echo "</select>";
	            echo '<input type="hidden" name="isgreylisted">';
                 }else{
                    echo (is_dir($this->spamDykeConf["graylist-dir"]."/".$this->allDomains[0]))?VALUE_ACT:VALUE_DEACT;
                 }
              }
     
              }
              
     function checkForRemoteAccess($val){

              if(trim($val["isremoteaccess"]) != "" && (trim($val["adminsave"]) == "" && trim($val["jetztsuchen"]) == "")) $this->RemoteAccess($val);

              $edit = (is_Array($this->getAdmin()->remoteStates["user"]))?true:false;
             
              $select = ($this->getAdmin()->remoteStates["user"][$this->allDomains[0]])?"selected":"";
              
	      if($edit){
	            $decToken = $this->xtea->Decrypt($this->getAdmin()->remoteStates["user"][$this->allDomains[0]]);
	            $token = ($decToken>0)?"token:".$decToken:VALUE_ACT;
	      
                    echo "<select name=\"isremoteaccess\" onChange=\"document.scpedit.submit()\">\n";
	            echo "<option value=\"0\">".VALUE_DEACT."</option>\n";
	            echo "<option value=\"1\" ".$select.">".$token."</option>\n";
	            echo "</select>";
                 }else{
                    echo VALUE_DEACT;
                 }
              }              

     function checkForDailyReport($val){

              if(trim($val["isdailyreport"]) != "" && (trim($val["adminsave"]) == "" && trim($val["jetztsuchen"]) == "")) $this->DailyReport($val);
     
              $edit = (is_Array($this->getAdmin()->reportStates["user"]))?true:false;
             
              $select = ($this->getAdmin()->reportStates["user"][$this->allDomains[0]])?"selected":"";
              $goTo = "";
              
	      if($edit){
	      	    if(!empty($select)){
                       $goTo = "alt=\"".SCP_STATUS_GOES_TO.":".$this->getAdmin()->reportStates["user"][$this->allDomains[0]]."\" title=\"".SCP_STATUS_GOES_TO.":".$this->getAdmin()->reportStates["user"][$this->allDomains[0]]."\"";
                       }
                    echo "<select $goTo onChange=\"changeDailyRep(this.value)\">\n";
	            echo "<option value=\"0\">".VALUE_DEACT."</option>\n";
	            echo "<option value=\"1\" ".$select.">".VALUE_ACT."</option>\n";
	            echo "</select>";
	            echo '<input type="hidden" name="isdailyreport">';
                 }else{
                    echo VALUE_DEACT;
                 }
              
              }

     function changeGreylisting($val){

              $theCmd = "setgl";
              if(!$val["isgreylisted"]) $theCmd = "delgl";
     
              for($i=0;$i<count($this->allDomains);$i++){
                  exec ('./wrapper "1" "'.$theCmd.' '.$this->spamDykeConf["graylist-dir"].' '.$this->allDomains[$i].'"');
                  }
              
              }
              
     function DailyReport($val){
     	      
     	      if($val["isdailyreport"]){
     	      	$this->getAdmin()->reportStates["user"][$this->allDomains[0]] = $val["isdailyreport"];
     	        }else{
     	        unset($this->getAdmin()->reportStates["user"][$this->allDomains[0]]);
     	        }
     	      
     	      $this->getAdmin()->schreibeReportRechte();
     	      }
     	      
     function RemoteAccess($val){
              $raid = rand(100000,999999);
     	      
     	      if($val["isremoteaccess"]){
     	      	if(!empty($this->getAdmin()->remoteStates["user"][$this->allDomains[0]])) return;
     	      	$this->getAdmin()->remoteStates["user"][$this->allDomains[0]] = $this->xtea->Encrypt($raid);
     	        }else{
     	        unset($this->getAdmin()->remoteStates["user"][$this->allDomains[0]]);
     	        }
     	      
     	      $this->getAdmin()->schreibeRemoteRechte();
     	      }     	      
     
     function getAllBlocked(){
     
              $neededLogEntries = null;

              if(!$this->dieRechte->isAllView){
                 $searchStr = "AND ( `to` LIKE '%@".implode("' OR `to` LIKE '%@",$this->allDomains)."' or ";
                 $searchStr.= " `from` LIKE '%@".implode("' OR `from` LIKE '%@",$this->allDomains)."')";
                 }

              if($this->suchmuster){
                 $searchStr.=" AND (";
                 $searchStr.=" `reason` LIKE '%".$this->suchmuster."%' or";
		 $searchStr.=" `ip` LIKE '%".$this->suchmuster."%' or";
                 $searchStr.=" `from` LIKE '%".$this->suchmuster."%' or";
                 $searchStr.=" `to` LIKE '%".$this->suchmuster."%' or";
                 $searchStr.=" `rdns` LIKE '%".$this->suchmuster."%')";
                 }
		 
	     if($this->anzVars["anz_Filter"]){
                 $searchStr.=" AND (";
                 $searchStr.=" `reason` = '".$this->anzVars["anz_Filter"]."')";
                 }

	     if($this->anzVars["anz_alleAddr"]){
		$searchStr.=" AND (";
		$searchStr.=" `to` in ('" . join("','",$this->getAllMailAddr()) ."'))";
	    	}

               if($this->anzVars["anz_Richtung"]){
                 if(!$this->dieRechte->isAllView){
   	           if($this->anzVars["anz_Richtung"] == SCP_DIRECTION_LOCAL){
                      $searchStr = "AND ( `to` LIKE '%@".implode("' OR `to` LIKE '%@",$this->allDomains)."' AND ";
                      $searchStr.= " `from` LIKE '%@".implode("' OR `from` LIKE '%@",$this->allDomains)."')";
		      $searchStr.= "AND `auth` != '(unknown)'";
                   }
		   if($this->anzVars["anz_Richtung"] == SCP_DIRECTION_OUT){
                      $searchStr = "AND ( `from` LIKE '%@".implode("' OR `from` LIKE '%@",$this->allDomains)."')";
		      $searchStr.= "AND `auth` != '(unknown)'";
                   }
		   if($this->anzVars["anz_Richtung"] == SCP_DIRECTION_IN){
		    $searchStr.= "AND `auth` = '(unknown)'";
                   }
		}else{
		   if($this->anzVars["anz_Richtung"] == SCP_DIRECTION_OUT){
		      $searchStr.= "AND `auth` != '(unknown)'";
                   }
		   if($this->anzVars["anz_Richtung"] == SCP_DIRECTION_IN){
		    $searchStr.= "AND `auth` = '(unknown)'";
                   }
		}
	     }



		 
              $dasLog = $dasLog = substr($this->logRotates[0],0,10);
              if($this->anzVars["anz_Von"]) $dasLog = $this->anzVars["anz_Von"];
              $res = mysqli_query($this->plesk_db_conn, "SELECT * FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE date_format(time,'%Y-%m-%d') = '".$dasLog."' ".$searchStr." ORDER BY time DESC LIMIT 0,".$this->anzVars["anz_PerPage"]);
	      
              while($data = mysqli_fetch_object($res)){
                       $logArray["time"] = strtotime($data->time); 
                       $logArray["reason"] = $data->reason;
		       $logArray["from"] = $data->from;
		       $logArray["to"] = $data->to;
		       $logArray["ip"] = $data->ip;
		       $logArray["rdns"] = $data->rdns;
		       $logArray["auth"] = $data->auth;
                       $logArray["color"] = $this->delayed[$data->reason][$data->id][0];
                       $logArray["delay"] = $this->delayed[$data->reason][$data->id][1];

                       if($data->reason == "DENIED_GRAYLISTED" && !empty($logArray["color"]) && !$this->anzVars["anz_GlPaar"]) continue;

                       
                       if($this->suchmuster){
                       	  
                       	  if(preg_match($this->suchmuster,$logArray["reason"])){
                       	     $logArray["reason"] = str_replace($this->suchmuster,"<span class=\"sucherg\">".$this->suchmuster."</span>",$logArray["reason"]);
                       	     }
                       	     

			  if(preg_match($this->suchmuster,$logArray["from"])){
                       	     $logArray["from"] = str_replace($this->suchmuster,"<span class=\"sucherg\">".$this->suchmuster."</span>",$logArray["from"]);
                       	     }

			  if(preg_match($this->suchmuster,$logArray["to"])){
                       	     $logArray["to"] = str_replace($this->suchmuster,"<span class=\"sucherg\">".$this->suchmuster."</span>",$logArray["to"]);
                       	     }

			  if(preg_match($this->suchmuster,$logArray["ip"])){
                       	     $logArray["ip"] = str_replace($this->suchmuster,"<span class=\"sucherg\">".$this->suchmuster."</span>",$logArray["ip"]);
                       	     }

			  if(preg_match($this->suchmuster,$logArray["rdns"])){
                       	     $logArray["rdns"] = str_replace($this->suchmuster,"<span class=\"sucherg\">".$this->suchmuster."</span>",$logArray["rdns"]);
                       	     }                       	     
                       	 
                       	  }
                     if(!$this->anzVars["anz_Richtung"]){  
                       if(preg_match("(@".implode("|@",$this->allDomains).")",$logArray["to"]) && 
                          preg_match("(@".implode("|@",$this->allDomains).")",$logArray["from"]) &&
			  $logArray["auth"] != "(unknown)"){
                       	  $logArray["direction"] = SCP_DIRECTION_LOCAL;
                       	  }
                       else if(preg_match("(@".implode("|@",$this->allDomains).")",$logArray["from"])){
                       	  $logArray["direction"] = SCP_DIRECTION_OUT;
                       	  }
                       else{
                          $logArray["direction"] = SCP_DIRECTION_IN;
                          }
                     }else{
                       if($logArray["auth"] != "(unknown)"){
                       	  $logArray["direction"] = SCP_DIRECTION_OUT;
                       	  }
                       else{
                          $logArray["direction"] = SCP_DIRECTION_IN;
                          }
                      }
		      
		       
		       $this->logTable[] = $logArray;
	       
	          }
	     }
	     
     function checkLogTableForFormerGreylisting($entry){

              $tmpColor = $this->randomColor();
              $retVal = false;

              $dasLog = substr($this->logRotates[0],0,10);
              if($this->anzVars["anz_Von"]) $dasLog = $this->anzVars["anz_Von"];

              $where = "`from` = '".$entry["from"]."' AND `to` = '".$entry["to"]."' AND (`ip` = '".$entry["ip"]."' or `rdns` = '".$entry["rdns"]."') AND (reason = 'DENIED_GRAYLISTED' OR reason = 'ALLOWED') AND time < '".$entry["time"]."'";
              $where.= " AND date_format(time,'%Y-%m-%d') = '".$dasLog."' LIMIT 1";
              $res = mysqli_query($this->plesk_db_conn, "SELECT id,time,reason FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE $where");
              while($data = mysqli_fetch_array($res)){
                    if($data["reason"] != "DENIED_GRAYLISTED") return $retVal;
                    $retVal = $data["id"];
                    $delay = strtotime($entry["time"]) - strtotime($data["time"]);
                    $this->delayed[$entry["reason"]][$entry["id"]] = array($tmpColor,$delay);
                    $this->delayed[$data["reason"]][$data["id"]] = array($tmpColor);
                 } 
              return $retVal;
              }


     function getSpamrate(){
     
              $allRequest = $this->allowed + $this->blocked;
	      
	      return str_replace(".",",",round($this->blocked / ($allRequest / 100),2));


     
              }	     
    
     function getLogTable(){

              $count = count($this->logTable);
              
              for($i=0;$i<$count;$i++){

                 $dieClass = "fatred";

                 if(stristr($this->logTable[$i]["reason"],"ALLOWED")){
                    $dieClass = "fatgreen";
                 }


                 $color = (empty($this->logTable[$i]["color"]) || !$this->anzVars["anz_GlPaar"])?"#FFFFFF":$this->logTable[$i]["color"];
	      
	         echo "<tr bgcolor=\"$color\" onMouseOver=\"this.bgColor='#ccffc'\" onMouseOut=\"this.bgColor='$color'\" class=\"".$dieClass."\">\n";
                 
                 if($this->logTable[$i]["direction"] == SCP_DIRECTION_IN){
                    echo "<td data-title=\"".SCP_DIRECTION."\"><img src=\"mail-in.svg\" width=\"16\" height=\"16\"><img style=\"cursor:pointer\" src=\"analyze.svg\" width=\"16\" height=\"16\" title=\"".SCP_MAIL_ANALYZE."\" alt=\"".SCP_MAIL_ANALYZE."\" onClick=\"getPageContent('analyze.php?from=".strip_tags($this->logTable[$i]["from"])."&to=".strip_tags($this->logTable[$i]["to"])."&time=".$this->logTable[$i]["time"]."','".strip_tags($this->logTable[$i]["from"])."','analyze')\">";
                    }
                 else if($this->logTable[$i]["direction"] == SCP_DIRECTION_OUT){
                    echo "<td data-title=\"".SCP_DIRECTION."\"><img src=\"mail-out.svg\" width=\"16\" height=\"16\"><img style=\"cursor:pointer\" src=\"analyze.svg\" width=\"16\" height=\"16\" title=\"".SCP_MAIL_ANALYZE."\" alt=\"".SCP_MAIL_ANALYZE."\" onClick=\"getPageContent('analyze.php?from=".strip_tags($this->logTable[$i]["from"])."&to=".strip_tags($this->logTable[$i]["to"])."&time=".$this->logTable[$i]["time"]."','".strip_tags($this->logTable[$i]["from"])."','analyze')\">";
                   }
                 else{
                    echo "<td data-title=\"".SCP_DIRECTION."\"><img src=\"local.gif\" width=\"16\" height=\"16\"><img style=\"cursor:pointer\" src=\"analyze.svg\" width=\"16\" height=\"16\" title=\"".SCP_MAIL_ANALYZE."\" alt=\"".SCP_MAIL_ANALYZE."\"  onClick=\"getPageContent('analyze.php?from=".strip_tags($this->logTable[$i]["from"])."&to=".strip_tags($this->logTable[$i]["to"])."&time=".$this->logTable[$i]["time"]."','".strip_tags($this->logTable[$i]["from"])."','analyze')\">";
                   }
                 
                 echo "&nbsp;".$this->logTable[$i]["direction"]."</td>";
                 echo "<td data-title=\"".SCP_SENDER."\" TITLE=\"".strip_tags($this->logTable[$i]["from"])."\" onMouseOut=\"toggleDirectList('".$i."_sender',0)\" onMouseOver=\"toggleDirectList('".$i."_sender',1)\">".$this->cutToLong($this->logTable[$i]["from"])."<br><div style=\"position:absolute;padding:3px 3px 3px 3px;background:#FFFFFF\" class=\"invis\" id=\"".$i."_sender\">&nbsp;<img style=\"cursor:pointer\" width=\"16\" height=\"16\" src=\"help.svg\" border=\"0\" onClick=\"getPageContent('cm.php?addy=".strip_tags($this->logTable[$i]["from"])."','".strip_tags($this->logTable[$i]["from"])."','mail')\">&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"wl.svg\" border=\"0\" onClick=\"setWhite('senderlists','".strip_tags($this->logTable[$i]["from"])."')\">&nbsp;&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"bl.svg\" border=\"0\" onClick=\"setBlack('senderlists','".strip_tags($this->logTable[$i]["from"])."')\">&nbsp;</div></td>";
	      	 
                 echo "<td data-title=\"".SCP_RECEIVE."\" TITLE=\"".strip_tags($this->logTable[$i]["to"])."\" onMouseOut=\"toggleDirectList('".$i."_recipient',0)\" onMouseOver=\"toggleDirectList('".$i."_recipient',1)\">".$this->cutToLong($this->logTable[$i]["to"])."<br><div style=\"position:absolute;padding:3px 3px 3px 3px;background:#FFFFFF\" class=\"invis\" id=\"".$i."_recipient\">&nbsp;<img style=\"cursor:pointer\" width=\"16\" height=\"16\" src=\"help.svg\" border=\"0\" onClick=\"getPageContent('pm.php?addy=".strip_tags($this->logTable[$i]["to"])."','".strip_tags($this->logTable[$i]["to"])."','mail')\">&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"wl.svg\" border=\"0\" onClick=\"setWhite('recipientlists','".strip_tags($this->logTable[$i]["to"])."')\">&nbsp;&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"bl.svg\" border=\"0\" onClick=\"setBlack('recipientlists','".strip_tags($this->logTable[$i]["to"])."')\">&nbsp;</div></td>";
   	         if($this->logTable[$i]["delay"]){
    	            echo "<td data-title=\"".SCP_REASON."\"><div style=\"float:left\">".$this->logTable[$i]["reason"]."</div><div style=\"float:left\" onMouseOut=\"toggleDirectList('".$i."_delay',0)\" onMouseOver=\"toggleDirectList('".$i."_delay',1)\"><img src=\"time.svg\" width=\"14\" height=\"14\" border=\"0\"></div><br><div style=\"position:absolute;padding:3px 3px 3px 3px;background:#FFFFFF;color:#000000;float:left\" class=\"invis\" id=\"".$i."_delay\">&nbsp;".$this->minutes($this->logTable[$i]["delay"])." ".SCP_GRAYLIST_MIN."&nbsp;</div></td>";
    	            }
    	         else{
    	            echo "<td data-title=\"".SCP_REASON."\">".$this->logTable[$i]["reason"]."</td>";
    	            }

                 $landFlag = "";

                 if($this->anzVars["anz_LiveCountry"]){
                    include("lang/tld.inc.php");
                    $output = null;
                    exec('./whois.sh "'.$this->logTable[$i]["ip"].'"',$output);
                    $land = strtolower(substr($output[0],(strlen($output[0])-2)));
                    if(empty($land)) $land = "unknown";
                    $landFlag = '<img src="flags/'.$land.'.png" title="'.$tld[$land].'" alt="'.$tld[$land].'" width="14" style="border:1px solid #000000" height="14">';
               
                    }

		 echo "<td data-title=\"".SCP_ORG_IP."\" TITLE=\"".strip_tags($this->logTable[$i]["ip"])."\" onMouseOut=\"toggleDirectList('".$i."_ip',0)\" onMouseOver=\"toggleDirectList('".$i."_ip',1)\"><div style=\"margin-right:5px;float:left\">".$landFlag.'</div>'.$this->logTable[$i]["ip"]."<br><div style=\"position:absolute;padding:3px 3px 3px 3px;background:#FFFFFF\" class=\"invis\" id=\"".$i."_ip\">&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"help.svg\" border=\"0\" onClick=\"getPageContent('ident.php?dieip=".strip_tags($this->logTable[$i]["ip"])."&row=".$i."','".strip_tags($this->logTable[$i]["ip"])."','ip')\">&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"wl.svg\" border=\"0\" onClick=\"setWhite('iplists','".strip_tags($this->logTable[$i]["ip"])."')\">&nbsp;&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"bl.svg\" border=\"0\" onClick=\"setBlack('iplists','".strip_tags($this->logTable[$i]["ip"])."')\">&nbsp;</div></td>";
                 echo "<td data-title=\"".SCP_ORG_RDNS."\" TITLE=\"".strip_tags($this->logTable[$i]["rdns"])."\" onMouseOut=\"toggleDirectList('".$i."_rdns',0)\" onMouseOver=\"toggleDirectList('".$i."_rdns',1)\">".$this->cutToLong($this->logTable[$i]["rdns"])."<br><div style=\"position:absolute;padding:3px 3px 3px 3px;background:#FFFFFF\" class=\"invis\" id=\"".$i."_rdns\">&nbsp;<img width=\"16\" height=\"16\" style=\"cursor:pointer\" src=\"wl.svg\" border=\"0\" onClick=\"setWhite('rdnslists','".strip_tags($this->logTable[$i]["rdns"])."')\">&nbsp;<img style=\"cursor:pointer\" width=\"16\" height=\"16\" src=\"bl.svg\" border=\"0\" onClick=\"setBlack('rdnslists','".strip_tags($this->logTable[$i]["rdns"])."')\">&nbsp;</div></td>";;
		 echo "<td data-title=\"".SCP_SENDTIME."\">".date("d. M. H:i:s",$this->logTable[$i]["time"])."</td>";
		 echo "</tr>";
		 }

               

              }	 
              
      function doExport(){
      	        $count = count($this->logTable);
      	        $sendStr = "";
      	        $sendStr = SCP_DIRECTION.",".SCP_SENDER.",".str_replace("&auml;","ae",SCP_RECEIVE).",".SCP_REASON.",".SCP_ORG_IP.",".SCP_ORG_RDNS.",".SCP_SENDTIME."\n";
                for($i=0;$i<$count;$i++){
                    $sendStr.=strip_tags($this->logTable[$i]["direction"]).",";
                    $sendStr.=strip_tags($this->logTable[$i]["from"]).",";
                    $sendStr.=strip_tags($this->logTable[$i]["to"]).",";
                    $sendStr.=strip_tags($this->logTable[$i]["reason"]).",";
                    $sendStr.=strip_tags($this->logTable[$i]["ip"]).",";
                    $sendStr.=strip_tags($this->logTable[$i]["rdns"]).",";
                    $sendStr.=date("d. M. H:i:s",$this->logTable[$i]["time"])."\n";
                    }  
               header ("Content-type: application/excel");
               header ("Content-Disposition: download; filename=SCP-export".date("d-m-y_H:i:s").".csv"); 
               echo $sendStr;
               exit;
      	       } 
              
      function cutToLong($str){
               $all = strip_tags($str);
               if(strlen($all) != strlen($str)) return $str;
               
               if(strlen($all) > $this->maxStrLen){
      	       	  return substr($all,0,$this->maxStrLen)."...";
      	       	  }
      	       	  
      	       return $str;
      	
      	       }    

      function getPerPageOpt(){

               $opts = array(25,50,100,200,500,1000,5000);
               $box = new optionboxes("anz_PerPage");
               $box->setSelPoint($this->anzVars["anz_PerPage"]);
               $box->setEvent("onChange=\"document.anzeige.submit()\"");
               $box->getBox($opts,$opts);

               }

      function getGlPaarOpt(){

               $opts = array(0,1);
               $vals = array(VALUE_NO,VALUE_YES);

               $box = new optionboxes("anz_GlPaar");
               $box->setSelPoint($this->anzVars["anz_GlPaar"]);
               $box->setEvent("onChange=\"document.anzeige.submit()\"");
               $box->getBox($opts,$vals);
               }
      function getAlleAddrOpt(){

               $opts = array(0,1);
               $vals = array(VALUE_NO,VALUE_YES);

               $box = new optionboxes("anz_alleAddr");
               $box->setSelPoint($this->anzVars["anz_alleAddr"]);
               $box->setEvent("onChange=\"document.anzeige.submit()\"");
               $box->getBox($opts,$vals);
               }

      function getLiveCountryOpt(){

               $opts = array(0,1);
               $vals = array(VALUE_NO,VALUE_YES);

               $box = new optionboxes("anz_LiveCountry");
               $box->setSelPoint($this->anzVars["anz_LiveCountry"]);
               $box->setEvent("onChange=\"if(this.value==1)alert('".SCP_LIVE_COUNTRY_NOTICE."');document.anzeige.submit()\"");
               $box->getBox($opts,$vals);
               }

     function getFilterOpt(){
               $opts = array(false,"ALLOWED");
               $vals = array(SCP_ALL,"ALLOWED");
               $blockTypes = $this->blockedType;
               while (list($key) = each($blockTypes)){
                      $opts[] = $key;
                      $vals[] = $key;
                      }
               unset($blockTypes);
               $box = new optionboxes("anz_Filter");
               $box->setSelPoint($this->anzVars["anz_Filter"]);
               $box->setEvent("onChange=\"document.anzeige.submit()\"");
               $box->getBox($opts,$vals);
               }
               
     function getRichtungOpt(){

               $opts = array(false,SCP_DIRECTION_IN,SCP_DIRECTION_OUT);
               $vals = array(SCP_ALL,SCP_DIRECTION_IN,SCP_DIRECTION_OUT);
               
               if(!$this->dieRechte->isAllView){
               	  $opts[] = SCP_DIRECTION_LOCAL;
               	  $vals[] = SCP_DIRECTION_LOCAL;
                  }
               
               $box = new optionboxes("anz_Richtung");
               $box->setSelPoint($this->anzVars["anz_Richtung"]);
               $box->setEvent("onChange=\"document.anzeige.submit()\"");
               $box->getBox($opts,$vals);
               }

     function getLogDate(){

               for($i=0;$i<count($this->logRotates);$i++){
                   $t = strtotime($this->logRotates[$i]); 
                   $vals[] = constant("SCP_MYSQL_DAY_".date("w",$t)).", ".date(SCP_MYSQL_VIEW_DATE_FORMAT,$t);
                   $opts[] = date("Y-m-d",$t);
                   }
               $box = new optionboxes("anz_Von");
               $box->setSelPoint($this->anzVars["anz_Von"]);
               $box->setEvent("onChange=\"document.anzeige.submit()\"");
               $box->getBox($opts,$vals);
               }               
               

     

      function getAdmin(){
               return $this->dieRechte;
               }

      function getStat(){

               $alle = $this->blocked + $this->allowed;
               
               echo "<tr bgcolor=\"#FFFFFF\">\n";
    	       echo "<td height=\"25\" width=\"150\">&nbsp;".strtoupper(SCP_ALL_MAILS).":</td>\n";
               echo "<td width=\"300\">\n";
               echo "<div id=\"stat\" style=\"background:#999999;width:200px; height:10px;\"></div><div style=\"float:left\">&nbsp;100 %</div>\n";
               echo "</td>\n";
               echo "<td height=\"25\" width=\"190\">".$alle."</td>\n";
               echo "</tr>\n";
               echo "<tr bgcolor=\"#FFFFFF\">\n";
    	       echo "<td height=\"25\" width=\"150\">&nbsp;".strtoupper(SCP_ALLOWED).":</td>\n";
               echo "<td width=\"300\">\n";
               echo "<div id=\"stat\" style=\"background:#009900;width:".($this->makeProz($alle,$this->allowed,0) * 2)."px; height:10px;\"></div><div style=\"float:left\">&nbsp;".$this->makeProz($alle,$this->allowed,2)." %</div>\n";
               echo "</td>\n";
               echo "<td height=\"25\" width=\"190\">".$this->allowed."</td>\n";
               echo "</tr>\n";
               echo "<tr bgcolor=\"#FFFFFF\">\n";
    	       echo "<td height=\"25\" width=\"150\">&nbsp;".strtoupper(SCP_DROPPED_MAILS).":</td>\n";
               echo "<td width=\"300\">\n";
               echo "<div id=\"stat\" style=\"background:#FF0000;width:".($this->makeProz($alle,$this->blocked,0) * 2)."px; height:10px;\"></div><div style=\"float:left\">&nbsp;".$this->makeProz($alle,$this->blocked,2)." %</div>\n";
               echo "</td>\n";
               echo "<td height=\"25\" width=\"190\">".$this->blocked."</td>\n";
               echo "</tr>\n";
               
               if(count($this->delayed["ALLOWED"]) > 0){
                        $delay = array();
                        while (list($key) = each($this->delayed["ALLOWED"])){
                            $delay[] = $this->delayed["ALLOWED"][$key][1];
                            }
               	
               	        echo "<tr bgcolor=\"#FFFFFF\">\n";
    	                echo "<td height=\"25\" width=\"150\" style=\"vertical-align:top\">&nbsp;".SCP_GREYLIST_DELAY."</td>\n";
                        echo "<td width=\"300\" style=\"vertical-align:top\">\n";
                        echo SCP_GREYLIST_DELAY_AVERAGE."<br>";
                        echo SCP_GREYLIST_DELAY_MAX."<br>";
                        echo SCP_GREYLIST_DELAY_MIN."<br>";
                        echo "</td>\n";
                        echo "<td height=\"25\" width=\"190\" style=\"vertical-align:top\">";
                        echo $this->minutes((array_sum($delay) / count($delay)))." ".SCP_GRAYLIST_MIN."<br>";
                        echo $this->minutes(max($delay))." ".SCP_GRAYLIST_MIN."<br>";
                        echo $this->minutes(min($delay))." ".SCP_GRAYLIST_MIN."<br>";
                        echo "</td>\n";
                        echo "</tr>\n";
                      }

               echo "<tr><td colspan=\"3\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>";
               echo "<tr><td colspan=\"3\" height=\"1\" bgcolor=\"#000000\"></td></tr>";
               echo "<tr><td colspan=\"3\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>";          
  
               while (list($key, $val) = each($this->blockedType)){
                    echo "<tr bgcolor=\"#FFFFFF\">\n";
    	            echo "<td height=\"25\" width=\"150\">&nbsp;".strtoupper($key).":</td>\n";
                    echo "<td width=\"300\">\n";
                    echo "<div id=\"stat\" style=\"background:#FF9900;width:".($this->makeProz($this->blocked,$val,0) * 2)."px; height:10px;\"></div><div style=\"float:left\">&nbsp;".$this->makeProz($this->blocked,$val,2)." %</div>\n";
                    echo "</td>\n";
                    echo "<td height=\"25\" width=\"190\">".$val."</td>\n";
                    echo "</tr>\n";
                    }
               }

     function makeProz($s, $w, $r){
              $w = (100 / $s * $w);
              $w = round($w,$r);
              return str_replace(".",",",$w);
              }

     function minutes ($sec) {
              return sprintf('%02d:%02d', floor($sec/60), $sec % 60);
              } 

     function topStat($stat){
              if(!$stat->isAdm){
                 $this->getAllDomainsAndAliases();
                 $searchStr = "AND ( `to` LIKE '%@".implode("' OR `to` LIKE '%@",$this->allDomains)."')";
                 }
                 
              $topRecMailAll = array();
              $topIP = array();
              $topIPAll = array();
              $topCountries = array();
              $topTime = array();
              $topTimeAll = array();
              $topRdnsAll = array();

              if($stat->isAdm){
                 $res = mysqli_query($this->plesk_db_conn, "SELECT substring( `to` , (locate( '@', `to` ) +1 )) AS DOM, COUNT( `to` ) AS Anzahl FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason != 'ALLOWED' AND date_format( time, '%Y-%m-%d' ) = '".$this->anzVars["anz_Von"]."' GROUP BY DOM");
                 while($data = mysqli_fetch_object($res)){
                       $topRecMailAll[$data->DOM] = $data->Anzahl;
                       }
              }else{
                 $res = mysqli_query($this->plesk_db_conn, "SELECT `to` , COUNT( `to` ) AS Anzahl FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason != 'ALLOWED' AND date_format(time,'%Y-%m-%d') = '".$this->anzVars["anz_Von"]."' $searchStr GROUP BY `to`");
                 while($data = mysqli_fetch_object($res)){
                       $topRecMailAll[$data->to] = $data->Anzahl;
                       }
              }
              
              $res = mysqli_query($this->plesk_db_conn, "SELECT `id` ,`rdns` FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason != 'ALLOWED' AND date_format(time,'%Y-%m-%d') = '".$this->anzVars["anz_Von"]."' $searchStr");
              while($data = mysqli_fetch_object($res)){
                    $topRdnsAll[$data->id] = $data->rdns;
                    }
              
              $res = mysqli_query($this->plesk_db_conn, "SELECT `id` ,`ip` FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason != 'ALLOWED' AND date_format(time,'%Y-%m-%d') = '".$this->anzVars["anz_Von"]."' $searchStr");
              while($data = mysqli_fetch_object($res)){
                    $topIP[$data->id] = $data->ip;
                    }

              $res = mysqli_query($this->plesk_db_conn, "SELECT `id` ,`ip` FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason != 'ALLOWED' AND date_format(time,'%Y-%m-%d') = '".$this->anzVars["anz_Von"]."' $searchStr");
              while($data = mysqli_fetch_object($res)){
                    $topIP[$data->id] = $data->ip;
                    }

              $res = mysqli_query($this->plesk_db_conn, "SELECT id,date_format(time,'%H') as zeit FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason != 'ALLOWED' AND date_format(time,'%Y-%m-%d') = '".$this->anzVars["anz_Von"]."' $searchStr");
              while($data = mysqli_fetch_object($res)){
                    $topTime[$data->id] = $data->zeit;
                    }

              $res = mysqli_query($this->plesk_db_conn, "SELECT * FROM ".$this->spamDykeConf["config-mysql-database"].".spamdyke_log_table WHERE reason = 'ALLOWED' AND date_format(time,'%Y-%m-%d') = '".$this->anzVars["anz_Von"]."' $searchStr");
              while($data = mysqli_fetch_array($res)){
                    $id = $this->checkLogTableForFormerGreylisting($data);
                    if($id){
                       $topRecMailAll[$data["to"]]--;
                       if(!$topRecMailAll[$data["to"]]) unset($topRecMailAll[$data["to"]]);
                       unset($topIP[$id]);
                       unset($topTime[$id]);
                       unset($topRdnsAll[$id]);
                       }
                    }

              while (list($key,$val) = each($topTime)){
                     $topTimeAll[$val]++;
                     }

              while (list($key,$val) = each($topIP)){
                     $topIPAll[$val]++;
                     }
              

              arsort($topIPAll);
              arsort($topRecMailAll);
              arsort($topTimeAll);
              $stat->setTopIp($topIPAll);
              $stat->setTopRec($topRecMailAll);
              $stat->setTopTime($topTimeAll);
              
              if($stat->isLand){
                 while (list($key) = each($topIPAll)){
                        $dieIps[] = $key;
                        }
                 $domForStat = (empty($this->allDomains[0]))?"zz_admin":"zz_".$this->allDomains[0];
                 exec('./wrapper "2" "'.$domForStat.'" "'.implode(" ",$dieIps).'"',$output);
                 
                 
                 $output = file($domForStat.'_erg'); 
                 unlink($domForStat.'_erg');
           
                 for($i=0;$i<count($output);$i++){
                   $land = trim(strtolower(substr($output[$i],(strlen($output[$i])-3))));
                   $topCountries[$land]++;
                   }
              }else{
                while (list($key,$val) = each($topRdnsAll)){
                       $tmpRdns = explode(".",$val);
                       $topCountries[preg_replace("[^a-z]","",$tmpRdns[count($tmpRdns)-1])]++;
                       }
              }
              
              

              arsort($topCountries);
              $stat->setTopCountry($topCountries);
              
              return $stat;
              }

     }
              

?>
