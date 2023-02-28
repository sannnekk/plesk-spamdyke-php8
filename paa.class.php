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

require __DIR__ . "/polyfills.php";

class paa{

      
      /* Make your database settings here, or instantiate class with "paa(Object plesk_session, String domainname, array[host,dbname,dbuser,dbpass]) */
      var $plesk_database_host = "localhost";
      var $plesk_database_name = "plesk_db_name";
      var $plesk_database_user = "plesk_db_user";
      var $plesk_database_pass = "plesk_db_pass";
      
      
      /* Do not edit after here */
      var $plesk_session;
      var $plesk_domain;
      var $plesk_allowed_domains;
      var $plesk_skin;
      var $plesk_allowed = false;
      
      var $plesk_only_adm = true;
      
      var $plesk_db_conn;
      
      var $plesk_fe_props = array();
      
      var $plesk_manager_view = false;

      var $calender;

      function paa($sess,$dom=null,$db=null){
      
                 if(!defined("IS_ADMIN")) define("IS_ADMIN",1);
	
		 $this->plesk_session = $sess;
                 $this->plesk_domain = $dom;
	         if(is_array($db)){
                  $this->plesk_database_host = $db[0];
                  $this->plesk_database_name = $db[1];
                  $this->plesk_database_user = $db[2];
                  $this->plesk_database_pass = $db[3];
                 }
	
                if(!method_exists($this->plesk_session,'chkLevel')){
                  require("legacy.class.php");
                  $this->plesk_session = new legacy();
                  }
        
               }
	       
      function getFlattr($show){
               if(!$show)return;
               echo ' <script type="text/javascript">';
               echo 'var flattr_url = \'http://haggybear.de/de/spamdyke-control-panel\';';
  	       echo 'var flattr_btn=\'compact\';';
	       echo '</script>';
 	       echo '<script src="http://api.flattr.com/button/load.js" type="text/javascript"></script>';
               }
               
      function feProps($fe){
      	
      	       $fe = explode(",",$fe);
      	       $default["css"] = "normal";
      	       $default["style"] = "none";
      	       
      	       for($i=0;$i<count($fe);$i++){
      	       	
      	       	   if(empty($_COOKIE[$fe[$i]."_fe"])){
      	       	      $this->plesk_fe_props[$fe[$i]."_css"] = $default["css"];
      	       	      $this->plesk_fe_props[$fe[$i]."_style"] = $default["style"];
      	       	   }else{
      	       	   
      	       	     $cook = explode(":",$_COOKIE[$fe[$i]."_fe"]);
      	       	   
      	       	     $this->plesk_fe_props[$fe[$i]."_css"] = $cook[1];
      	       	     $this->plesk_fe_props[$fe[$i]."_style"] = $cook[0];
      	       	     }
      	       	   
      	       	  }
      	       }

      function openDatabase(){
               $this->plesk_db_conn = @mysqli_connect($this->plesk_database_host,$this->plesk_database_user,$this->plesk_database_pass,$this->plesk_database_name) or die ("No connection.");
               }
               
      function closeDatabase(){
               @mysqli_close($this->plesk_db_conn);
               }
            
      function setPleskSkin(){
      
               $sql = "SELECT val from misc where param='theme_skin'";
	       $ret = mysqli_query($this->plesk_db_conn, $sql);
	       $data = mysqli_fetch_array($ret);
	       
	       if($data["val"]=="default" || empty($data["val"])){
	          $this->plesk_skin = "theme";
	          }
	       else{
	          $this->plesk_skin = "theme-skins/".$data["val"];
	         }
               }

      function setPleskAllowed(){
 
	       if($this->plesk_session->chkLevel(IS_ADMIN)){
                  $this->plesk_allowed = true;
                  return;
                  }

               $r = mysqli_query($this->plesk_db_conn, "select a.name from domains as a, clients as b where a.cl_id = b.id and b.login ='".$this->plesk_session->_login."' and a.name = '".$this->plesk_domain."'");
	       if(mysqli_num_rows($r)>0){
	       	  $this->plesk_allowed = true;
                  return;
                  }
                  
               $r = mysqli_query($this->plesk_db_conn, "SELECT c.name FROM smb_users AS a, smb_roles AS b,domains AS c WHERE a.login = '".$this->plesk_session->_login."' AND a.roleId = b.id AND b.ownerId = c.cl_id and (b.name LIKE '%".$this->plesk_domain."%' or b.name='admin')");
               if(mysqli_num_rows($r)>0){
                  $this->plesk_allowed = true;
                  return;
                  }
               $r = mysqli_query($this->plesk_db_conn, "SELECT d.name FROM clients as a, domains AS d WHERE a.login = '".$this->plesk_session->_login."' and a.type = 'reseller' and a.id = d.vendor_id and d.name = '".$this->plesk_domain."'");                               
               if(mysqli_num_rows($r)>0){                                                                                                                                                                                                                 
                  $this->plesk_allowed = true;                                                                                                                                                                                                           
                  return;                                                                                                                                            
                  }
               }
               
      function psa10_domainGrab(){
               $where = "";
               $id = (PSA_VERSION>=12)?$_SESSION["subscription"]["currentId"]:$_SESSION["subscriptionId"]->current;
               if($_GET["cl_id"]>0){
                  $where = "where id ='".$id."'";
      	          }
               $r = mysqli_query($this->plesk_db_conn, "select name from domains $where");
      	       $res = mysqli_fetch_object($r);
      	       $this->plesk_domain = $res->name;
      	          
      	       $this->setPleskAllowed();
      	       }
      	       
      function psa10_aboGrab($dom){
               $id = (PSA_VERSION>=12)?$_SESSION["subscription"]["currentId"]:$_SESSION["subscriptionId"]->current;
               if($_GET["cl_id"]>0){
                  $qry = "select name from domains where webspace_id ='".$id."'";
      	          }
      	       else{
      	          $qry = "select name from domains";
      	          }
      	      $r = mysqli_query($this->plesk_db_conn, $qry);
      	      while($res = mysqli_fetch_object($r)){
      	            $dom[] = $res->name;
      	            }
               
              return array_unique($dom);
              }

      function setPleskAllowedDomains($id){
      
               if($id==0 && $this->plesk_session->chkLevel(IS_ADMIN)){
                  $this->plesk_manager_view = true;
                  return;
                  }
      	
      	       if($this->plesk_session->chkLevel(IS_ADMIN)){
                  $sql = "SELECT name,id,cl_id FROM domains WHERE cl_id = '$id'";
                  }
               else if($this->plesk_session->_login == $this->plesk_domain) {
                  $this->plesk_allowed_domains[] = $this->plesk_domain;
                  return;
                  }
               else{
                  $sql = "SELECT a.name, a.id, a.cl_id FROM domains AS a, clients AS b WHERE a.cl_id = b.id AND b.login = '".$this->plesk_session->_login."'";
                 }     	      
      	       
      	
      	       $r = mysqli_query($this->plesk_db_conn, $sql); 
      	       while($data = mysqli_fetch_object($r)){
      	       	     $this->plesk_allowed_domains[$data->name] = "?cl_id=".$data->cl_id."&dom_name=".$data->name."&dom_id=".$data->id."&previous_page=domains";
      	       	    }
      	       	  
      	       }

      function getPleskManagerView(){
               return $this->plesk_manager_view;
               }


      function getPleskLogin(){
               return $this->plesk_session->_login;
               }

      function getPleskAllowed(){
               return $this->plesk_allowed;
               }

      function getPleskSkin(){
               return $this->plesk_skin;
               }
	       
      function setOnlyAdm($var){
               $this->plesk_only_adm=$var;
               }
      
      function getOnlyAdm(){
               return $this->plesk_only_adm;
               }

      }


?>
