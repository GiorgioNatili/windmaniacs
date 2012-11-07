<?php
//check the email address
if(!isset($_POST['email'])){exit();}
$email=trim(addslashes($_POST['email']));
if(!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is',$email)){exit();}

//including the library that allows to use the wpdb object with external files
require_once('../../../../wp-load.php');

//creating a unique hash
$hash=hash('md5',uniqid());

//add the email address to the database
global $wpdb;$table_name=$wpdb->prefix."mail_list_table";
$sql = "INSERT INTO $table_name SET email='$email',hash='$hash'";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

//set cookie
setcookie("maillistcookie", "done", time()+31104000,"/");
?>
