<?php
/*
 * Plugin Name: Addvertising with Category
 * Author: Mahibul Hasan Sohag
 * Description: This plugin binds adds with category. It has a great Admin UI
 * AUthor URI: http://sohag07hasan.elance.com
 * plugin uri: http://sohag.me
 * 
 * */
 
 define('ADDMANAGEMENT_DIR', dirname(__FILE__));
 define('ADDMANAGEMENT_FILE', __FILE__);
 include ADDMANAGEMENT_DIR . '/classes/add-management.php';

 Category_Binding_With_Add :: init();
