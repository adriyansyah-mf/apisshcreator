<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
header('Content-Type: application/json');
include('Net/SSH2.php');

// setting vps 
$host="167.99.66.59";
$root_password="uYjYbmMsyr3T3EW";

// setting user baru
$username = $_GET['username'];
$password = $_GET['password'];
$aktif = $_GET['aktif'];
$tgl=date('Y-m-d');
$tgl2=date('Y-m-d', strtotime('+'.$aktif.' days',strtotime($tgl)));

$ssh = new Net_SSH2($host);
 if (!$ssh->login('root', $root_password)) {
    echo "login gagal";
 }else{
    $ssh->exec("useradd -e $tgl2".' '."$username");
	    
    $ssh->enablePTY();
    
    $ssh->exec("passwd $username");
    
    $ssh->read("New password: ");
    
    $ssh->write("$password\n");
    
    $ssh->read("Retype new password: ");
    
    $ssh->write("$password\n");
    
    $ssh->read('passwd: password updated successfully');
    
    $arr = array();
    $arr['status']   = 'Account Created';
    $arr['server']   = '167.99.66.59';
    $arr['username'] = $username;
    $arr['password'] = $password;
    $arr['port']     = '443';
    $arr['Created '] = $tgl;
    $arr['Valid Until'] = $tgl2;

    echo json_encode($arr);
 }


 ?>