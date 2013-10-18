<?php
/**
 * Plugin Name: W8ing
 * Plugin URI: https://github.com/bgcom/bgp-wp-w8ing
 * Description: A basic waiting/landing page plugin for Wordpress
 * Version: 1.0
 * Author: Guillaume Molter for B+G & Partners SA
 * Author URI: http://bgcom.ch
 * License: WTFPL
 */
 
 
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'w8ing_admin.php');

function w8ing_redirect(){
	
	/*** We retreive the plugin's options - Array ***/
	$options = get_option('w8ing_options');
	
	$landingURl=false;
	$vip=false;
	
	/*** We check that the visitor is not a white listed IP address or trying to reach a white listed  ***/
	
	if(isset($options["ip_list"]) && $options["ip_list"]!=""){
		$ip_list=explode(",", $options["ip_list"]);
		foreach($ip_list as $ip){
			if($ip==$_SERVER['REMOTE_ADDR']){
				$vip=true;
			}
		}
	}
	
	if(isset($options["page_list"]) && $options["page_list"]!=""){
		$post_list=explode(",", $options["page_list"]);
		foreach($post_list as $post){
			if(strpos(get_permalink($post),$_SERVER['REDIRECT_URL'])){
				$vip=true;
			}
		}
	}
	
	/*** We check if a landing page has been defined and if  ***/
	if(isset($options["pageID"]) && $landingURl=get_permalink($options["pageID"]) && !$vip){
		
		/*** We check if the landing page redirection is active or not ***/
		if(isset($options["active"]) && $options["active"]=="on"){
			wp_redirect($landingURl, 302 );
		}
		/*** If not we check if we should redirect to the homepage ***/
		elseif(isset($options["homeRedirect"]) && $options["homeRedirect"]=="on" && $_SERVER['REDIRECT_URL']==$landingURl){
			wp_redirect(get_bloginfo("wpurl"), 302 );
		}
		
	}
		
}


add_action('init', 'w8ing_redirect', 1);

?>