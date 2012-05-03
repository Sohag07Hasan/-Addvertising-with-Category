<?php

/*
 * Controls all the stuff
 * */
 
class Category_Binding_With_Add{
	
	
	public static function init(){
		//hooks from wp-admin/edit-tag-form.php
		add_action('admin_menu', array(get_class(),'create_the_menu'));
		
		//init hooks to control the form submission
		add_action('init', array(get_class(), 'form_submit_handling'));
		
		//creating table to store the data
		register_activation_hook(ADDMANAGEMENT_FILE, array(get_class(), 'create_the_table'));
		
		//the content
		add_filter('the_content', array(get_class(), 'content_filter'));
		
		//category delte hook
		add_action('delete_category', array(get_class(), 'delete_category'), 10, 2);
		
		//category term description filtering
		add_filter('get_category', array(get_class(), 'get_category_description'), 10, 2);
	}
	
	
	/*
	 * Edit the category description
	 * */
	 static function get_category_description($_term, $taxonomy){
		$term = self::get_term_meta($_term->term_id);
		if(empty($term)) return $_term;
		
		$_term->description = stripcslashes($term->content);
		
		return $_term;
		
	 }
	
	 
	
	/*
	 * handles the form submit, update etc..
	 * */
	 static function form_submit_handling(){
		
		if($_POST['advertisement_added'] == 'Y'){
			self :: save_advertisement();
			self :: set_global_term();
			$url = get_option('siteurl') . '/wp-admin/admin.php?page=advertisent_addition&message=1&term_id=' . $_POST['advertise_category'];
			if(!function_exists('wp_redirect')){
				include ABSPATH . '/wp-includes/pluggable.php';
			}

			wp_redirect($url);
			exit;
		}
				
	 }
	 
	 
	 /*
	  *save form data 
	  * */
	  static function save_advertisement(){
			global $wpdb;
			$table = self::get_table_name();
			$name = $_POST['addvertise_name'];
			$content = $_POST['addvertise_content'];
			$position = (int) $_POST['advertise_position'];
			$term_id = (int) $_POST['advertise_category'];
			$data = array('name'=>$name, 'content'=>$content, 'position'=>$position);
											
			if(self::term_id_exsits($term_id)){
				$wpdb->update($table, array('name'=>$name, 'content'=>$content, 'position'=>$position), array('term_id'=>$term_id), array('%s', '%s', '%d'), array('%d'));
			}
			else{
				$wpdb->insert($table, array('name'=>$name, 'content'=>$content, 'position'=>$position, 'term_id'=>$term_id), array('%s', '%s', '%d', '%d'));
			}
			
			wp_update_term($term_id, 'category', array('description'=>$content));
			
			return;
	  }
	  
	
	
	/*
	 * Creates menu and submenu
	 * */
	static function create_the_menu(){
		add_menu_page(__('wp add management'), __('Advertising'), 'activate_plugins', 'add_management', array(get_class(),'management_page'));
		add_submenu_page('add_management',__('add new advertisement'),__('Add New'),'activate_plugins','advertisent_addition',array(get_class(),'ad_add'));
	}
	
	
	
	/*
	 * management page
	 * */
	 static function management_page(){
		 global $wpdb;
		 $table = self::get_table_name();
		 		 
		 if($_POST['b_action'] == 'delete'){
			 $terms = $_POST['check'];
			 if(empty($terms)) $terms = array();
			 foreach($terms as $t){
				 $t = (int) $t;
				$wpdb->query("DELETE FROM `$table` WHERE `term_id` = '$t'");
			 }
		 }
		 
		 if($_GET['action'] == 'delete'){
			$t = (int)$_GET['term_id'];
			$wpdb->query("DELETE FROM `$table` WHERE `term_id` = '$t'");
		 }
		 
		 $data = self::get_alldata();
		 if(empty($data)) $data = array();
		 include dirname(__FILE__) . "/includes/form-table.php";
	 }
	 
	 /*
	  * returns all data
	  * */
	  static function get_alldata(){
			global $wpdb;
			$table = self::get_table_name();
			return $wpdb->get_results("SELECT * FROM `$table` ORDER BY `name`");
	  }
	  
	 
	
	/*
	 * advertisement adding page
	 * */
	 static function ad_add(){		 
		 if(isset($_REQUEST['term_id']) && !empty($_REQUEST['term_id'])){
			$meta = self::get_term_meta($_REQUEST['term_id']);
			$global_term = self::get_global_term();
			if($global_term == $_REQUEST['term_id']) $gt = 1;
		 }
		 
		 include dirname(__FILE__) . "/includes/form-fields.php";
	 }
	 
	 
	 /*
	  * get_categoryies as options
	  * */
	  static function get_categories($cid){
			$category_ids = get_all_category_ids();
			
			$option = '';
			foreach($category_ids as $cat_id){
				$option .= '<option ' . selected($cat_id,$cid) . ' value="' . $cat_id . '">' . get_cat_name($cat_id) . '</option>';
			}
			return $option;			
	  }
	
	
	
	/*
	 * Add the extra html or js from the category with the post
	 * */
	 static function content_filter($content){
		 
		 if(is_page()) return $content;
		 
		 global $post;
		 $global_cat = self::get_global_term();
		 
		 if($global_cat){
			$category = $global_cat;
		 }
		 else{
			$categories = get_the_category($post->ID);
			$category = $categories[0]->term_id;
		 }
		 
		return self::sanitized_content($content, $category);	
		 
	 }
	 
	 
	 
	 /*
	  *sanitize the content 
	  * */
	  static function sanitized_content($content, $category=null){
			if(empty($category)) return $content;
			
		//var_dump($category);	
			global $wpdb;
			$table = self::get_table_name();
			$options = $wpdb->get_row("SELECT * FROM `$table` WHERE `term_id` = '$category'");
						
			
			if(strlen($options->content) < 5) return $content;
			
			if($options->position == 1){
				return stripslashes($options->content) . $content;
			}
			if($options->position == 2){
				return $content . stripslashes($options->content);
			}
			if($options->position == 3){
				preg_match('%(<p[^>]*>.*?</p>)%i', $content, $matches);
				if(empty($matches)) return $content;
				
				$first_portion = $matches[0];
				$last_portion = preg_replace('%(<p[^>]*>.*?</p>)%i', stripslashes($options->content), $content, 1);
				
				return $first_portion . $last_portion;
			}
			
			return $content;			 
			 
	  }
	 
	 
	
	/*
	 * Deletes form the custom table
	 * */
	 static function delete_category($term, $tt_id){
		 /*
		 $table = self::get_table_name();
		 global $wpdb;
		 $wpdb->query("DELETE FROM `$table` WHERE `term_id` = '$term'");
		 return;
		 * */
		 
		 if(self::get_global_term() == $term){
			update_option('global_category_binding', 0);
		 }
	 }
	 
	 
	
	/*
	 * get Term meta data from custom table
	 * */
	 static function get_term_meta($term_id = 0){
		$term_id = (int) $term_id;
		$table = self::get_table_name();
		global $wpdb;
		return $wpdb->get_row("SELECT * FROM `$table` WHERE `term_id` = '$term_id'");
	 }
	
	
	
	/*
	 * Globally set the category for the html
	 * */
	 static function set_global_term(){
		$term_id = (int) $_POST['advertise_category'];
		if($_POST['global_status'] == '1') :
			update_option('global_category_binding', $term_id);
		else :
			if(self::get_global_term() == $term_id){
				update_option('global_category_binding', 0);
			}
		endif;
		
	 }
	 
	 
	 /*
	 *get the global term 
	 * */
	 static function get_global_term(){
		return get_option('global_category_binding', false);
	 }
	
	
	/*
	 * creates the table while the plugin is activated
	 * */
	 static function create_the_table(){
		$table = self::get_table_name();
		$sql = "CREATE TABLE IF NOT EXISTS $table(
			`id` bigint unsigned NOT NULL AUTO_INCREMENT,
			`term_id` bigint unsigned NOT NULL,
			`position` tinyint NOT NULL,
			`name` varchar(100) NOT NULL,
			`content` longtext DEFAULT NULL,
			PRIMARY KEY(id),
			UNIQUE(term_id)	 
		)";
		
		if(!function_exists('dbDelta')) :
			include ABSPATH . 'wp-admin/includes/upgrade.php';
		endif;
		dbDelta($sql);
	 }
	 
	 
	 /*
	  * returns table name
	  * */
	  
	  static function get_table_name(){
		global $wpdb;
		return $wpdb->prefix . 'category_advertising'; 
	  }
	  
	  
	  
	  /*
	   * returns true if term id exists
	   * */
	   static function term_id_exsits($term_id){
			global $wpdb;
			$table = $table = self::get_table_name();
			return $wpdb->get_var("SELECT `id` FROM `$table` WHERE `term_id` = '$term_id'");
	   }
}
