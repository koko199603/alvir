os.system('clear');
include "fcgomart.php";
ulang:
// function change(){
echo color("purple","               CLAIM VOUCHER GOJEK   GOMART                  \n");
echo color("red","                By king @pemangsavoucher                     \n");
echo color("nevy","        Waktu  : ".date('[d-m-Y] [H:i:s]')."                        \n");
echo color("green","         Donasi Bisa Ke WA Dibawah Ini                       \n") ;
echo color("yellow","                 WA 08xxxxxxxxxx                          \n");
echo color("blue","        Format Nomor 08/62 Pake Salah Satu                  \n");
echo "| --------------------------- \n";
if(file_exists("config.json")){
    $arr2 = json_decode(file_get_contents('config.json'), true);
    $token = $arr2['token'];
    $memberid = $arr2['id'];
    $hp = $arr2['hp'];
    echo "| Anda sudah login menggunakan nomer hp $hp \n";
    echo "| 1. Login \n";
    echo "| 2. Daftar \n";
    echo "| Pilih: ";
    $pilih = trim(fgets(STDIN));
    if($pilih !== '1'){
        goto daftar;
    }else{goto login;}
}
daftar:
echo "| Masukan Nomor HP: ";
$nomorhp = trim(fgets(STDIN));
$nomorhp = "\"$nomorhp\"";

$data_otp = '{"action":"REGISTER","mobileNumber":'.$nomorhp.',"type":0}';
$header_otp = array(
    "accept: application/json",
    "accept-language: id",
    "versionname: 4.0.30",
    "versionnumber: 403016",
    "devicemodel: Xiaomi Redmi Note 8",
    "packagename: com.alfamart.alfagift",
    "signature: 6E:41:03:61:A5:09:55:05:B6:84:84:C9:75:0B:89:56:5D:1D:41:C7",
    "latitude: 0.0",
    "longitude: 0.0",
    "deviceid: $deviceid",
    "Content-Type: application/json",
    "user-agent: okhttp/3.14.4",
 );
$url_otp = "https://api.alfagift.id/v1/otp/request";
$get_otp = curl($url_otp,$header_otp,$data_otp);
$get_otp = json_decode($get_otp,true);
$status = $get_otp['status']['code'];
if($status == "00"){
    $pesan = $get_otp['otpDescription'];
    echo "| $pesan \n";
}elseif($status == "01"){
    $pesan = $get_otp['otpDescription'];
    echo "| $pesan \n";
}else{echo "| Gagal"; die;}

otp:
echo "| Masukan KODE OTP: ";
$otp = trim(fgets(STDIN));
$otp = "\"$otp\"";
$url_verif_otp = "https://api.alfagift.id/v1/otp/verify";
$data_otp_login = '{"action":"REGISTER","mobileNumber":'.$nomorhp.',"otpCode":'.$otp.',"type":0}';
$verif_otp = curl($url_verif_otp,$header_otp,$data_otp_login);
$verif_otp = json_decode($verif_otp,true);
$status_login = $verif_otp['status']['code'];
if($status_login !== "00"){
    $pesan = $verif_otp['verifyOtpDescription'];
    echo "| $pesan \n";
    goto otp;
}else{
    $token = $verif_otp['token'];
    $token2 = "\"$token\"";
}


$url_daftar = "https://api.alfagift.id/v1/account/member/create";
$random_name = "Arie"." ".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
$random_email = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
$random_name = "\"$random_name\"";
$random_email = "\"$random_email@gmail.com\"";
$data_create = '{"address":"","birthDate":"1996-03-23","debug":false,"deviceId":'.$deviceid2.',"email":'.$random_email.',"firstName":"","fullName":'.$random_name.',"gender":"F","lastName":"","latitude":0,"longitude":0,"maritalStatus":"M","password":"Straight23","phone":'.$nomorhp.',"postCode":"","registerPonta":true,"token":'.$token2.'}';
$create_akun = curl($url_daftar,$header_otp,$data_create);
$put = file_put_contents('createakunalfa.txt',$create_akun);
$create_akun = json_decode($create_akun,true);
$status_create = $create_akun['status']['code'];
if($status_create !== "00"){
    $message = $create_akun['status']['message'];
    echo "| $message";
    die;
}else{
    $message = $create_akun['status']['message'];
    echo "| Status: $message \n";
    $memberid = $create_akun['memberId'];
    $token = $create_akun['status']['token'];
    $id_ponta = $create_akun['member']['ponta']['accountCard'];
    $no_hp = $create_akun['member']['ponta']['phoneNumber'];
    echo "| Sukses daftar!!! \n";
    echo "| Nomer hp $no_hp dan password HajasBor \n";
    echo "| Member Ponta $id_ponta \n";
    echo "| Loading voucher \n";
    sleep(4);
    $arr1 = ["token"=>$token,"id"=>$memberid, "hp"=>$no_hp];
    file_put_contents("config.json",json_encode($arr1));
    $file = fopen("alfajoss.txt","a");  
    fwrite($file,"--------------------------------------------------------".PHP_EOL);
    fwrite($file,"$no_hp & Nomor PONTA : $id_ponta".PHP_EOL);
    fwrite($file,"--------------------------------------------------------".PHP_EOL);
    fclose($file);
    goto login;
}



login:
$heder_jadi = array(
    "accept: application/json",
    "accept-language: id",
    "versionname: 4.0.30",
    "versionnumber: 403016",
    "devicemodel: Xiaomi Redmi Note 8",
    "packagename: com.alfamart.alfagift",
    "signature: 6E:41:03:61:A5:09:55:05:B6:84:84:C9:75:0B:89:56:5D:1D:41:C7",
    "latitude: 0.0",
    "longitude: 0.0",
    "deviceid: $deviceid",
    "token: $token",
    "id: $memberid",
    "Content-Type: application/json",
    "user-agent: okhttp/3.14.4",
 );
 $url_login = "https://api.alfagift.id/v1/promotion/myVoucher";
 $data_login = '{"limit":10,"start":0}';
 $ogin = curl($url_login,$heder_jadi,$data_login);
 $login = json_decode($ogin,true);
 $status = $login['status']['code'];
 if($status == "00"){
    $total_voucher = $login['totalVouchers'];
    if($total_voucher == null){
        echo "| Voucher Kosong!!! \n";
        
    }elseif($total_voucher !== null){
        echo "| Total voucher = $total_voucher \n";
        $voucher = $login['vouchers'];
        print_r($voucher);
        
    }
 }

goto welcome;





