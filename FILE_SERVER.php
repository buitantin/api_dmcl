<?php

if($_SERVER['SERVER_NAME']=='192.168.150.63'){
        $hostname = "localhost";
        $pass = "123";
        $user     = "root";
        $db ="dmcl12";
}else{
    $hostname = "192.168.222.1";

    $pass = "1.2.3@987DMCL";
    $user     = "udbdmclnew";

    $db ="dbdmclnew_2014";
    }

$dbconn = mysql_connect($hostname, $user, $pass) or die("hello,not connect to mysql"); 
//mysql_close($dbconn);

mysql_select_db($db);
mysql_set_charset('utf8',$dbconn);
require_once('./lib/nusoap.php');
// Create the client instance
//Create a new soap server
$server = new soap_server();
$server->configureWSDL('orderwsdl2', 'urn:orderwsdl2');
$server->soap_defencoding = 'UTF-8';
$server->decode_utf8 = false;
$server->encode_utf8 = true;
// Register the data structures used by the service
/***********************************************************GET DATA *************************/
$server->wsdl->addComplexType(
    'OrderObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        
        'madonhang' => array('name' => 'madonhang', 'type' => 'xsd:string'),
        'ngaymua' => array('name' => 'ngaymua', 'type' => 'xsd:string'),
        'ngaygiao' => array('name' => 'ngaygiao', 'type' => 'xsd:string'),
        'hinhthucthanhtoan'  => array('name' => 'hinhthucthanhtoan', 'type' => 'xsd:string'),
        'tongdonhang'  => array('name' => 'tongdonhang', 'type' => 'xsd:string'),
        'ghichu'  => array('name' => 'ghichu', 'type' => 'xsd:string'),
        'tonggiam'  => array('name' => 'tonggiam', 'type' => 'xsd:string'),
        'buoigiaohang'     => array('name' => 'buoigiaohang', 'type' => 'xsd:string'),
        'bill_fullname'     => array('name' => 'bill_fullname', 'type' => 'xsd:string'),
        'bill_phone'     => array('name' => 'bill_phone', 'type' => 'xsd:string'),
        'bill_email'     => array('name' => 'bill_email', 'type' => 'xsd:string'),
        'bill_address'     => array('name' => 'bill_address', 'type' => 'xsd:string'),
        'bill_city'     => array('name' => 'bill_city', 'type' => 'xsd:string'),
        'bill_district'     => array('name' => 'bill_district', 'type' => 'xsd:string'),
        'sh_fullname'     => array('name' => 'sh_fullname', 'type' => 'xsd:string'),
        'sh_phone'     => array('name' => 'sh_phone', 'type' => 'xsd:string'),
        'sh_email'     => array('name' => 'sh_email', 'type' => 'xsd:string'),
        'sh_company'     => array('name' => 'sh_company', 'type' => 'xsd:string'),
        'sh_address'     => array('name' => 'sh_address', 'type' => 'xsd:string'),
        'sh_district'     => array('name' => 'sh_district', 'type' => 'xsd:string'),
        'sh_city'     => array('name' => 'sh_city', 'type' => 'xsd:string'),
        'sh_addresscompany'     => array('name' => 'sh_addresscompany', 'type' => 'xsd:string'),
        'sh_faxcompany'     => array('name' => 'sh_faxcompany', 'type' => 'xsd:string')
   )
);
$server->wsdl->addComplexType('ListOrder',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:OrderObject[]')
    ),
    'tns:OrderObject'
);

$server->register('get_data',
    array('from_date' => 'xsd:date', 'to_date'=> 'xsd:date', 'pass' =>'xsd:string'),
    array('return' => 'tns:ListOrder'),
    'urn:orderwsdl2',
    'orderwsdl2#get_data',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);

//lay thong tin don hang

function get_data($from_date,$to_date,$pass)
{
	$where='';
    $p = 'dienmaycholon5r75476456455634578';
    $p = md5(md5($p));
    if($p == md5(md5($pass)) && !empty($pass))
    {      
  		$where .= " or_or.date_bill >= '".$from_date." 00:00:00' and or_or.date_bill <= '".$to_date." 23:59:59' and or_or.approved=0 and or_or.is_ins = '0'";	
        $query = "SELECT 
                    or_or.code_order as madonhang,or_or.date_bill as ngaymua,or_or.date_ship as ngaygiao,or_or.pay_type as hinhthucthanhtoan,or_or.total_or as tongdonhang,or_or.order_info as ghichu,
                    or_or.dis_price as tonggiam,or_or.session as buoigiaohang,
                    or_ad.fullname as bill_fullname,or_ad.phone as bill_phone,or_ad.email as bill_email,or_ad.address as bill_address,city2.name as bill_city,city3.name as bill_district,
                    or_sh.fullname as sh_fullname,or_sh.phone as sh_phone,or_sh.email as sh_email,
                    or_sh.company as sh_company,or_sh.address as sh_address,city1.name as sh_district,city.name as sh_city,
                    or_sh.addresscompany as sh_addresscompany,or_sh.faxcompany as sh_faxcompany
                    FROM or_order or_or 
                    LEFT JOIN or_billing_address or_ad ON or_or.id=or_ad.cid_order
                    INNER JOIN or_shipping_address or_sh ON or_sh.cid_order=or_or.id
                    INNER JOIN tm_cities city ON city.id=or_sh.city
                    INNER JOIN tm_cities city1 ON city1.id=or_sh.distict
                    LEFT JOIN tm_cities city2 ON city2.id=or_ad.city
                    LEFT JOIN tm_cities city3 ON city3.id=or_ad.district
                    WHERE".$where." LIMIT 5";
        $result = mysql_query($query);
        $a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
        $return=array();
          for($i=0;$i<=count($a)-1;$i++) {
           $return[$i]=$a[$i];
        }
    	return $return;
    }
    mysql_close($dbconn);
}
/***********************************************************END GET DATA *************************/

/***********************************************************GET DON HANG TRA GOP *************************/
$server->wsdl->addComplexType(
    'OrdertragopObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id_order' => array('name' => 'id_order', 'type' => 'xsd:string'),
        'madonhang' => array('name' => 'madonhang', 'type' => 'xsd:string'),
        'ngaymua' => array('name' => 'ngaymua', 'type' => 'xsd:string'),
        'ngaygiao' => array('name' => 'ngaygiao', 'type' => 'xsd:string'),
        'hinhthucthanhtoan'  => array('name' => 'hinhthucthanhtoan', 'type' => 'xsd:string'),
        'tongdonhang'  => array('name' => 'tongdonhang', 'type' => 'xsd:string'),
        'ghichu'  => array('name' => 'ghichu', 'type' => 'xsd:string'),
        'tonggiam'  => array('name' => 'tonggiam', 'type' => 'xsd:string'),
        'buoigiaohang'     => array('name' => 'buoigiaohang', 'type' => 'xsd:string'),
        'bill_fullname'     => array('name' => 'bill_fullname', 'type' => 'xsd:string'),
        'bill_phone'     => array('name' => 'bill_phone', 'type' => 'xsd:string'),
        'bill_email'     => array('name' => 'bill_email', 'type' => 'xsd:string'),
        'bill_address'     => array('name' => 'bill_address', 'type' => 'xsd:string'),
        'bill_city'     => array('name' => 'bill_city', 'type' => 'xsd:string'),
        'bill_district'     => array('name' => 'bill_district', 'type' => 'xsd:string'),
        'sh_fullname'     => array('name' => 'sh_fullname', 'type' => 'xsd:string'),
        'sh_phone'     => array('name' => 'sh_phone', 'type' => 'xsd:string'),
        'sh_email'     => array('name' => 'sh_email', 'type' => 'xsd:string'),
        'sh_company'     => array('name' => 'sh_company', 'type' => 'xsd:string'),
        'sh_address'     => array('name' => 'sh_address', 'type' => 'xsd:string'),
        'sh_district'     => array('name' => 'sh_district', 'type' => 'xsd:string'),
        'sh_city'     => array('name' => 'sh_city', 'type' => 'xsd:string'),
        'sh_addresscompany'     => array('name' => 'sh_addresscompany', 'type' => 'xsd:string'),
        'sh_faxcompany'     => array('name' => 'sh_faxcompany', 'type' => 'xsd:string'),
        'loaitragop' => array('name' => 'loaitragop', 'type' => 'xsd:string'),
        'muctratruoc' => array('name' => 'muctratruoc', 'type' => 'xsd:string'),
        'sotienconlai'  => array('name' => 'sotienconlai', 'type' => 'xsd:string'),
        'thoigiantragop'  => array('name' => 'thoigiantragop', 'type' => 'xsd:string'),
        'sotienhangthang'  => array('name' => 'sotienhangthang', 'type' => 'xsd:string'),
        'gender'  => array('name' => 'gender', 'type' => 'xsd:string'),
        'cmnd' => array('name'=>'cmnd', 'type' => 'xsd:string'),
        'work' => array('name'=>'work', 'type' => 'xsd:string'),
        'sohopdong' => array('name'=>'sohopdong', 'type' => 'xsd:string'),
        'ghichutragop' => array('name'=>'ghichutragop', 'type' => 'xsd:string')
   )
);
$server->wsdl->addComplexType('ListOrdertragop',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:OrdertragopObject[]')
    ),
    'tns:OrdertragopObject'
);

$server->register('get_data_tra_gop',
    array('from_date' => 'xsd:date', 'to_date'=> 'xsd:date', 'pass' =>'xsd:string'),
    array('return' => 'tns:ListOrdertragop'),
    'urn:orderwsdl2',
    'orderwsdl2#get_data_tra_gop',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);

//lay thong tin don hang

function get_data_tra_gop($from_date,$to_date,$pass)
{
    $where='';
    $p = 'dienmaycholon5r75476456455634578';
    $p = md5(md5($p));
    if($p == md5(md5($pass)) && !empty($pass))
    {      
        $where .= " or_or.date_bill >= '".$from_date." 00:00:00' and or_or.date_bill <= '".$to_date." 23:59:59' and or_or.approved=0 and or_or.is_ins = '1' and or_inspay.status= '0'";
        $query = "SELECT 
                    or_or.code_order as madonhang,or_or.date_bill as ngaymua,or_or.date_ship as ngaygiao,or_or.pay_type as hinhthucthanhtoan,or_or.total_or as tongdonhang,or_or.order_info as ghichu,
                    or_or.dis_price as tonggiam,or_or.session as buoigiaohang,or_or.id as id_order,

                    or_ad.fullname as bill_fullname,or_ad.phone as bill_phone,or_ad.email as bill_email,or_ad.address as bill_address,city2.name as bill_city,city3.name as bill_district,
                    or_sh.fullname as sh_fullname,or_sh.phone as sh_phone,or_sh.email as sh_email,
                    or_sh.company as sh_company,or_sh.address as sh_address,city1.name as sh_district,city.name as sh_city,
                    or_sh.addresscompany as sh_addresscompany,or_sh.faxcompany as sh_faxcompany,

                    or_inspay.type as loaitragop, or_inspay.percent as muctratruoc, or_inspay.price as sotienconlai, or_inspay.time_ins as thoigiantragop, or_inspay.price_month as sotienhangthang, or_inspay.gender, or_inspay.cmnd, or_inspay.work, or_inspay.sohopdong, or_inspay.note as ghichutragop

                    FROM or_order or_or 
                    LEFT JOIN or_billing_address or_ad ON or_or.id=or_ad.cid_order
                    INNER JOIN or_shipping_address or_sh ON or_sh.cid_order=or_or.id
                    INNER JOIN tm_cities city ON city.id=or_sh.city
                    INNER JOIN tm_cities city1 ON city1.id=or_sh.distict
                    LEFT JOIN tm_cities city2 ON city2.id=or_ad.city
                    LEFT JOIN tm_cities city3 ON city3.id=or_ad.district
                    LEFT JOIN or_installment or_inspay ON or_inspay.is_order=or_or.id
                    WHERE".$where." LIMIT 5";
        $result = mysql_query($query);
        $a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
        $return=array();
          for($i=0;$i<=count($a)-1;$i++) {
           $return[$i]=$a[$i];
        }
        return $return;
    }
    mysql_close($dbconn);
}
/************************************************END GET DON HANG TRA GOP *************************/


/***************************************************UPDATE TRẠNG THAI TRẢ GOP*************************/
$server->register("update_or_installment",
    array('id_order' => 'xsd:string', 'pass' =>'xsd:string','type'=>'xsd:int', 'note' =>'xsd:string'),
    array('return' => 'xsd:string'),
    'urn:orderwsdl2',
    'orderwsdl2#update_or_installment');
/*$type: 1 hoac 2
=== 1: DMCL duyet tra gop
=== 2: DMCL huy don hang tra gop
$id_order id cua or_order
*/
function update_or_installment($id_order, $pass,$type=1, $note=''){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        if($type==1){
            @$a = mysql_query("Update or_installment SET approve='1', note= '".$note."' WHERE is_order=".$id_order."");
        }else{
            @$query = mysql_query("Update or_order SET approved=3 where id='".$id_order."'");

            @$sql2= mysql_query("Select cid_order, status from or_order_history where cid_order='".$id_order."' and status=3");
           @$re2= mysql_fetch_row($sql2);
           if(!empty($re2)){
            @$update = mysql_query("Update or_order_history set date_added = now() where cid_order='".$id_order."' and status=3");
           }else{
            @$query2 = mysql_query("Insert into or_order_history(cid_order,status,date_added) values({$id_order},3,now())"); 
           }

           @$b = mysql_query("Update or_installment SET status='1', note= '".$note."' WHERE is_order=".$id_order."");
        }
                        
        return 'success';
    }else{
        return 'failed';
   }
   mysql_close($dbconn);
}
/*************************************END UPDATE TRẠNG THAI TRẢ GOP*************************/


/****************************************************************ORDER DETAIL *************************/
$server->wsdl->addComplexType(
    'OrderdetailObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'madonhang' => array('name' => 'madonhang', 'type' => 'xsd:string'),
        'code_voucher' => array('name' => 'code_voucher', 'type' => 'xsd:string'),
        'tragop' => array('name' => 'tragop', 'type' => 'xsd:string'),
        'soluong'   => array('name' => 'soluong', 'type' => 'xsd:string'),
        'giasp' => array('name' => 'giasp', 'type' => 'xsd:string'),
        'giagiam' => array('name' => 'giagiam', 'type' => 'xsd:string'),
        'tong'  => array('name' => 'tong', 'type' => 'xsd:string'),
        'tensp'  => array('name' => 'tensp', 'type' => 'xsd:string'),
        'sap_code'  => array('name' => 'sap_code', 'type' => 'xsd:string'),
        'loaikm' => array('name'=>'loaikm','type'=>'xsd:string'),
        'macoupon'  => array('name' => 'macoupon', 'type' => 'xsd:string'),
        'quatang'  => array('name' => 'quatang', 'type' => 'xsd:string'),
        'id_or_detail'  => array('name' => 'id_or_detail', 'type' => 'xsd:string'),
        'tennhacungcap'  => array('name' => 'tennhacungcap', 'type' => 'xsd:string'),
        'id_order'  => array('name' => 'id_order', 'type' => 'xsd:int'),
        //'loaikmgift'  => array('name' => 'loaikmgift', 'type' => 'xsd:string'),
        'tenctkm' => array('name' => 'tenctkm', 'type' => 'xsd:string'),
   )
);
$server->wsdl->addComplexType('Orderdetail',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:OrderdetailObject[]')
    ),
    'tns:OrderdetailObject'
);

$server->register('get_orderdetail',
    array('cid_order' => 'xsd:tinytext' , 'pass' =>'xsd:string'),
    array('return' => 'tns:Orderdetail'),
    'urn:orderwsdl2',
    'orderwsdl2#get_orderdetail',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);

// lay chi tiet don hang
function get_orderdetail($cid_order, $pass)
{
    $p = 'die21nmaych283olondeqo31q';
    $p = md5(md5($p));    
    if(is_numeric($cid_order) && md5(md5($pass))== $p)
    {
        $query = "SELECT or_or.code_order as madonhang, or_or.code_voucher as code_voucher, or_or.is_ins as tragop, or_or.id as id_order, or_de.amount as soluong,
        or_de.sale_price as giasp, or_de.dis_price as giagiam, or_de.total as tong, or_de.choose as loaikm, or_de.code_coupon as macoupon, or_de.cid_gift as quatang, or_de.id as id_or_detail, or_de.cid_supplier as tennhacungcap,
        product.name as tensp, product.sap_code as sap_code,        
        CASE WHEN promo_text.name IS NULL THEN CASE  WHEN promo_press.name IS NULL THEN CASE  WHEN promo_online.name IS NULL THEN CASE  WHEN promo_deals.name IS NULL THEN 'KoKM' ELSE promo_deals.name END  ELSE promo_online.name END  ELSE promo_press.name END ELSE promo_text.name END AS tenctkm
        FROM or_detail or_de
        INNER JOIN pro_product product on or_de.cid_product = product.id                             
        INNER JOIN or_order or_or ON or_de.cid_order = or_or.id
                     
        LEFT JOIN promo_text promo_text ON promo_text.id = or_de.cid_promotion And or_de.choose='4'
        LEFT JOIN promo_press promo_press ON promo_press.id = or_de.cid_promotion And or_de.choose='3'
        LEFT JOIN promo_online promo_online ON promo_online.id = or_de.cid_promotion And or_de.choose='2'
        LEFT JOIN promo_deals promo_deals ON promo_deals.id = or_de.cid_promotion And or_de.choose='1'
        WHERE or_or.code_order='$cid_order'";
        
        //detail_gift.cid_gift, detail_gift.type as loaikmgift,nhacungcap.fullname as tennhacungcap,
        //LEFT JOIN or_detail_gift detail_gift ON detail_gift.cid_detail =  or_de.id
        //LEFT JOIN  market_supplier nhacungcap ON or_de.cid_supplier = nhacungcap.id   
    	$result = mysql_query($query);
    	$a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
    	return $a;
    }
    /*********************Note***********************/
    /****Loại km: ( loaikm )
		0: Không KM, 
        1: KM Deals, 
        2: KM Online, 
        3: KM Báo, 
        4: KM Text
	*/
    
    /****Quà tặng: ( quatang )
		0: Không có quà tặng
        1: Có quà tặng  => Nếu loaikmgift = 0 thì Sử dụng hàm: get_ordergift($cid_gift, $pass, $biensu) với $biensu=0
                        => Nếu loaikmgift = 1 thì Sử dụng hàm: get_ordergift($cid_gift, $pass, $biensu) với $biensu=1
	*/
    mysql_close($dbconn);
}
/*****************************************************LAY QUA TANG + KM TEXT CỦA SẢN PHẨM*************************/
//Lấy quà tặng
/*
$server->wsdl->addComplexType(
    'OrdergiftObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'noidungkm'  => array('name' => 'noidungkm', 'type' => 'xsd:string'),
   )
);
$server->wsdl->addComplexType('Ordergift',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:OrdergiftObject[]')
    ),
    'tns:OrdergiftObject'
);
*/
$server->register('get_ordergift',
    array('cid_gift' => 'xsd:tinytext' , 'pass' =>'xsd:string', 'biensu' => 'xsd:string','loaikm' => 'xsd:string'),
    array('return' => 'xsd:string'),
    'urn:orderwsdl2',
    'orderwsdl2#get_ordergift'
);
//lay qua tang don hang
function get_ordergift($cid_gift, $pass, $biensu,$loaikm=null){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        if($biensu==1){
           $sql="Select name from pro_gift where id= ".$cid_gift."";
            $result = mysql_query($sql);
            $re = mysql_fetch_row($result);
            if(!empty($re)){
                //return (!empty($re[0]))? $re[0] : $re['name'];
                return $re[0];
            }else{
                return 'failed';
            }
        }elseif($biensu==0){
            
            if($loaikm=='2'){
                $sql="Select description from promo_online where id= ".$cid_gift."";
                $result = mysql_query($sql);
                $re = mysql_fetch_row($result);
                if(!empty($re)){
                    //return (!empty($re[0]))? $re[0] : $re['name'];
                    return $re[0];
                }else{
                    return 'failed';
                }
            }elseif($loaikm =='3'){
                $sql="Select description from  promo_press where id= ".$cid_gift."";
                $result = mysql_query($sql);
                $re = mysql_fetch_row($result);
                if(!empty($re)){
                    //return (!empty($re[0]))? $re[0] : $re['name'];
                    return $re[0];
                }else{
                    return 'failed';
                }
            }elseif($loaikm =='4' || $loaikm =='1'){
                $sql="Select name from promo_text where id= ".$cid_gift."";
                $result = mysql_query($sql);
                $re = mysql_fetch_row($result);
                if(!empty($re)){
                    //return (!empty($re[0]))? $re[0] : $re['name'];
                    return $re[0];
                }else{
                    return 'failed';
                }
            }else{
                $sql="Select name from promo_text where id= ".$cid_gift."";
                $result = mysql_query($sql);
                $re = mysql_fetch_row($result);
                if(!empty($re)){
                    //return (!empty($re[0]))? $re[0] : $re['name'];
                    return $re[0];
                }else{
                    return 'failed';
                }
            }
        }
        
    }else{ return 'failed'; }
    mysql_close($dbconn);
}

/********************************LAY ID cid_gift , type trong or_detail_gift***************************/

$server->wsdl->addComplexType(
    'Orderdetail2Object',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'cid_detail' => array('name' => 'cid_detail', 'type' => 'xsd:string'),
        'cid_gift'   => array('name' => 'cid_gift', 'type' => 'xsd:string'),        
        'loaikmgift'  => array('name' => 'loaikmgift', 'type' => 'xsd:string'),
   )
);
$server->wsdl->addComplexType('Orderdetail2',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Orderdetail2Object[]')
    ),
    'tns:Orderdetail2Object'
);

$server->register('get_or_detail_gift',
    array('id_or_detail' => 'xsd:tinytext', 'pass' =>'xsd:string'),
    array('return' => 'tns:Orderdetail2'),
    'urn:orderwsdl2',
    'orderwsdl2#get_or_detail_gift',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);

function get_or_detail_gift($id_or_detail, $pass){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        $sql="Select type as loaikmgift, cid_detail as cid_detail,cid_gift as cid_gift from or_detail_gift where cid_detail=".$id_or_detail."";
        $result = mysql_query($sql);
    	$a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
    	return $a;
    }
    mysql_close($dbconn);
}
/*********************************************************** THONG TIN NHÀ CUNG CẤP***************************/
$server->wsdl->addComplexType(
    'Supplierdetail2Object',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'fullname' => array('name' => 'fullname', 'type' => 'xsd:string'),
        'email'   => array('name' => 'email', 'type' => 'xsd:string'),        
        'phone'  => array('name' => 'phone', 'type' => 'xsd:string'),
        'address'  => array('name' => 'address', 'type' => 'xsd:string'),
        'username'  => array('name' => 'username', 'type' => 'xsd:string'),
        'info'  => array('name' => 'info', 'type' => 'xsd:string'),
   )
);
$server->wsdl->addComplexType('Supplierdetail2',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Supplierdetail2Object[]')
    ),
    'tns:Supplierdetail2Object'
);
$server->register('get_supplier',
    array('tennhacungcap' => 'xsd:tinytext', 'pass' =>'xsd:string'),
    array('return' => 'tns:Supplierdetail2'),
    'urn:orderwsdl2',
    'orderwsdl2#get_supplier',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);
function get_supplier($tennhacungcap, $pass){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        $sql="Select fullname, email,phone,address,username,info from market_supplier where id=".$tennhacungcap."";
        $result = mysql_query($sql);
    	$a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
    	return $a;
    }
    mysql_close($dbconn);
}
/***********************************************************END  THONG TIN NHÀ CUNG CẤP***************************/
/***********************************************************UPDATE TRANG THAI DON HANG*************************/
$server->register("update_status",
                array('cid_order' => 'xsd:tinytext','status'=> 'xsd:tinyint' , 'pass' =>'xsd:string'),
                array('return' => 'xsd:string'),
                'urn:orderwsdl2',
                'orderwsdl2#update_status');

// update trang thai
function update_status($cid_order, $status, $pass)
{

	/****Trạng thái: 
		0 => chưa xem, đơn hàng vừa được tạo
		1 => Đã xem , đơn hàng đang xử lý
			- 4 => Xử lý đơn hàng
			- 5 => Đang xử lý
			- 6 => Thu ngân ra phiếu
			- 7 => Đang giao hàng
		2 => Đơn hàng đã giao thành công
		3 => Đơn hàng đã hủy, kết thúc
	*/
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(is_numeric($status) && md5(md5($pass))== $p){
        //$date = date("Y-m-d H:i:s");
        

       //Công tác viên
        /* include("Server_Object.php");
         $aff_test=new Service_Aff();

         $aff_test->add_aff_order($cid_order,$satus);*/


    	$sql="Select * from or_order where code_order='".$cid_order."'";
    	@$result2 = mysql_query($sql);
    	@$re = mysql_fetch_row($result2);
        if(!empty($re)){
            $query ="update or_order set approved='".$status."' where code_order='".$cid_order."'";
        	@$result = mysql_query($query);
        	if($result){
        	   @$sql2= mysql_query("Select cid_order, status from or_order_history where cid_order={$re[0]} and status=".$status."");
               @$re2= mysql_fetch_row($sql2);
               if(!empty($re2)){
                @$update = mysql_query("Update or_order_history set date_added = now() where cid_order={$re[0]} and status=".$status."");
               }else{
                $query2 = mysql_query("Insert into or_order_history(cid_order,status,date_added) values({$re[0]},$status,now())"); 
               }        	   
       		   return 'success';
        	}else{
        		return 'failed';
        	}
        }else  return 'failed';
    }else{
        return 'failed';
    }
    mysql_close($dbconn);
}




/***********************************************************MP3*************************/
$server->register("insert_mp3",
    array('cid_order' => 'xsd:tinytext','filemp3'=> 'xsd:string' , 'pass' =>'xsd:string','note' => 'xsd:string','fullname' => 'xsd:string','status' => 'xsd:int'),
    array('return' => 'xsd:string'),
    'urn:orderwsdl2',
    'orderwsdl2#insert_mp3');
function insert_mp3($cid_order, $filemp3, $pass,$note=null ,$fullname,$status){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        mysql_query("Insert into tm_call_mp3(filemp3,cid_order,note,fullname,date,status) values('".$filemp3."','".$cid_order."','".$note."','".$fullname."' ,now(),$status)");
        return 'success';
    }else{
        return 'failed';
   }
   mysql_close($dbconn);
}
/***********************************************************END MP3*************************/
/*********************************************************** THONG TIN WEBSITE***************************/
$server->wsdl->addComplexType(
    'WebsiteinformationObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'site_name' => array('name' => 'site_name', 'type' => 'xsd:string'),
        'smtp_username'   => array('name' => 'email', 'type' => 'xsd:string'),        
        'smtp_password'  => array('name' => 'smtp_password', 'type' => 'xsd:string'),
        'smtp_port'  => array('name' => 'smtp_port', 'type' => 'xsd:string'),
   )
);
$server->wsdl->addComplexType('Websiteinformation',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:WebsiteinformationObject[]')
    ),
    'tns:WebsiteinformationObject'
);
$server->register('get_general',
    array('tennhacungcap' => 'xsd:tinytext', 'pass' =>'xsd:string'),
    array('return' => 'tns:Websiteinformation'),
    'urn:orderwsdl2',
    'orderwsdl2#get_general',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);
function get_general($pass){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        $sql="Select site_name,smtp_username,smtp_password,smtp_port from tm_general where id=1";
        $result = mysql_query($sql);
    	$a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
    	return $a;
    }
    mysql_close($dbconn);
}
/***********************************************************END  THONG TIN WEBSITE***************************/

/***********************************************************GET DATA SUPPLIER*************************/
$server->wsdl->addComplexType(
    'SupplierObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'fullname' => array('name' => 'fullname', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string'),
        'phone'  => array('name' => 'phone', 'type' => 'xsd:string'),
        'address'  => array('name' => 'address', 'type' => 'xsd:string'),
        'fax'  => array('name' => 'fax', 'type' => 'xsd:string'),
        'GPKD'  => array('name' => 'GPKD', 'type' => 'xsd:string'),
        'web'     => array('name' => 'web', 'type' => 'xsd:string'),
        'is_type' => array('name'=>'is_type', 'type' => 'xsd:string'),
        'status' => array('name'=>'status', 'type' => 'xsd:string')
   )
);
$server->wsdl->addComplexType('ListSupplier',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SupplierObject[]')
    ),
    'tns:SupplierObject'
);

$server->register('get_data_supplier',
    array('pass' =>'xsd:string'),
    array('return' => 'tns:ListSupplier'),
    'urn:orderwsdl2',
    'orderwsdl2#get_data_supplier',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);
function get_data_supplier($pass){
    $p = 'dienmaycholon5r75476456455634578';
    $p = md5(md5($p));
    if($p == md5(md5($pass)) && !empty($pass)){
        $query = "SELECT fullname, email, phone, address, fax, GPKD, web, is_type, status
                    FROM market_supplier
                    WHERE id>0 LIMIT 10";
        $result = mysql_query($query);
        $a = array();
        while($row = mysql_fetch_assoc($result)){
            $a[] = $row;
        }
        $return=array();
          for($i=0;$i<=count($a)-1;$i++) {
           $return[$i]=$a[$i];
        }
    	return $return;
    }
    mysql_close($dbconn);
}
/*
is_type: 
    - 2: Bình thường
    - 3: Vàng
    - 4: Kim cương
    - 5: DMCL
*/
/***********************************************************END GET DATA SUPPLIER*************************/
/*********************************************************** THONG TIN SẢN PHẨM***************************/
$server->wsdl->addComplexType(
    'InfoproductObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'tensp' => array('name' => 'tensp', 'type' => 'xsd:string'),
        'code'   => array('name' => 'code', 'type' => 'xsd:string'),              
        'sap_code'  => array('name' => 'sap_code', 'type' => 'xsd:string'),        
        'giaban'  => array('name' => 'giaban', 'type' => 'xsd:string'),
        'giathitruong'  => array('name' => 'giathitruong', 'type' => 'xsd:string'),
        'giathitruongkm'  => array('name' => 'giathitruongkm', 'type' => 'xsd:string'),
        'giabankm'  => array('name' => 'giabankm', 'type' => 'xsd:string'),
        'cid_supplier'  => array('name' => 'cid_supplier', 'type' => 'xsd:int'),
        'stock_num'  => array('name' => 'stock_num', 'type' => 'xsd:int'),
        'cid_product'  => array('name' => 'cid_product', 'type' => 'xsd:int'),        
        'mota'  => array('name' => 'mota', 'type' => 'xsd:string'),
        'baohanh'  => array('name' => 'baohanh', 'type' => 'xsd:string'),
        'trongluong'  => array('name' => 'trongluong', 'type' => 'xsd:string'),
   )
);
$server->wsdl->addComplexType('Infoproduct',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:InfoproductObject[]')
    ),
    'tns:InfoproductObject'
);
$server->register('get_info_product',
    array('masap' => 'xsd:string','idprosupplier'=>'xsd:int','pass' =>'xsd:string'),
    array('return' => 'tns:Infoproduct'),
    'urn:orderwsdl2',
    'orderwsdl2#get_info_product',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);
function get_info_product($masap,$idprosupplier=1, $pass="die21nmayceqwewqondeqo31q"){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        $sql = 'Select a.name as tensp, a.code, a.sap_code,
        b.discount as giaban,b.saleprice as giathitruong, b.cid_supplier, b.stock_num, b.cid_product, b.description as mota, b.id as id_pro_supplier_product
        from pro_product a
        LEFT JOIN pro_supplier_product b ON a.id = b.cid_product
        where a.sap_code='.$masap.' and b.cid_supplier='.$idprosupplier.'';
        $result = mysql_query($sql);
    	$a = array();
        while($row = mysql_fetch_assoc($result)){
            $a[] = $row;
        }
        //Giá khuyến mãi nếu có
        $sql1 = mysql_query("Select type_promo,cid_promotion from pro_promotion_product where cid_supplier = ".$idprosupplier." and cid_product=".$a[0]['id_pro_supplier_product']." and status='0' and type_promo <>'4'");
        $row1 = mysql_fetch_row($sql1);
        if(count($row1)>0){
            if($row1[0]==1){
                $sql1_1 = mysql_query("Select saleprice,price from promo_deals where id = ".$row1[1]."");
                $row1_1 = mysql_fetch_row($sql1_1);
            }elseif($row1[0]==2){
                $sql1_1 = mysql_query("Select saleprice,price from promo_online where id = ".$row1[1]."");
                $row1_1 = mysql_fetch_row($sql1_1);
            }elseif($row1[0]==3){
                $sql1_1 = mysql_query("Select saleprice,price from promo_press where id = ".$row1[1]."");
                $row1_1 = mysql_fetch_row($sql1_1);
            }else{
                $row1_1=array();
            }
            if(count($row1_1)>0){
                $a[0]['giathitruongkm'] = $row1_1[0];
                $a[0]['giabankm'] = $row1_1[1];
            } 
        }
               
        //bảo hành
        $sql2 = mysql_query("Select val from comp_elemt_product where cid_product=".$a[0]['cid_product']." and val like '%tháng%' and is_type='0'");
        $row2 = mysql_fetch_row($sql2);
        $a[0]['baohanh'] = $row2[0];
        //Trọng lượng
        $sql3 = mysql_query("Select val from comp_elemt_product where cid_product=".$a[0]['cid_product']." and val like '%kg%' and is_type='0'");
        $row3 = mysql_fetch_row($sql3);
        //print_r($row3); exit;
        $a[0]['trongluong'] = $row3[0];
       
        //print_r($a); exit;
        return $a;
           	
    }
    mysql_close($dbconn);
}
/***********************************************************END  THONG TIN SẢN PHẨM***************************/
/*********************************************************** THONG TIN MỘT NHÀ CUNG CẤP***************************/
$server->wsdl->addComplexType(
    'InforsupplierObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'fullname' => array('name' => 'fullname', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string'),
        'phone'  => array('name' => 'phone', 'type' => 'xsd:string'),
        'address'  => array('name' => 'address', 'type' => 'xsd:string'),
        'fax'  => array('name' => 'fax', 'type' => 'xsd:string'),
        'GPKD'  => array('name' => 'GPKD', 'type' => 'xsd:string'),
        'web'     => array('name' => 'web', 'type' => 'xsd:string'),
        'is_type' => array('name'=>'is_type', 'type' => 'xsd:string'),
        'status' => array('name'=>'status', 'type' => 'xsd:string')
   )
);
$server->wsdl->addComplexType('Inforsupplier',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:InforsupplierObject[]')
    ),
    'tns:InforsupplierObject'
);
$server->register('Informationsupplier',
    array('idnhacungcap' => 'xsd:int', 'pass' =>'xsd:string'),
    array('return' => 'tns:Inforsupplier'),
    'urn:orderwsdl2',
    'orderwsdl2#Informationsupplier',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);
function Informationsupplier($idnhacungcap,$pass){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        $sql="SELECT fullname, email, phone, address, fax, GPKD, web, is_type, status
                    FROM market_supplier
                    WHERE id = ".$idnhacungcap."";
        $result = mysql_query($sql);
    	$a = array();
        while($row = mysql_fetch_assoc($result))
        {
            $a[] = $row;
        }
    	return $a;
    }
    mysql_close($dbconn);
}
/*******************************END  THÔNG TIN MỘT NHÀ CUNG CẤP***************************/


/***********************************************************UPDATE SỐ LƯỢNG*************************/
$server->register("update_stock_num",
    array('masap' => 'xsd:string', 'pass' =>'xsd:string','idnhacungcap'=>'xsd:int','stock_num'=>'xsd:int'),
    array('return' => 'xsd:string'),
    'urn:orderwsdl2',
    'orderwsdl2#update_stock_num');
function update_stock_num($masap, $pass,$idnhacungcap=1,$stock_num){
    $p = 'die21nmayceqwewqondeqo31q';
    $p = md5(md5($p));
    if(md5(md5($pass))== $p){
        //SET NAMES UTF8;
        //mysql_set_charset('utf8');
        $a = mysql_query("Update pro_product INNER JOIN pro_supplier_product ON pro_supplier_product.cid_product = pro_product.id SET pro_supplier_product.stock_num=".$stock_num." WHERE pro_product.sap_code='".$masap."' AND pro_supplier_product.cid_supplier=".$idnhacungcap."");                
        return 'success';
    }else{
        return 'failed';
   }
   mysql_close($dbconn);
}
/***********************************************************END UPDATE SỐ LƯỢNG*************************/

/***********************************************************GET SAPCODE SẢN PHẨM*************************/
/*$server->wsdl->addComplexType(
    'SapcodeObject',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'sap_code' => array('name' => 'sap_code', 'type' => 'xsd:string')
   )
);
$server->wsdl->addComplexType('ListSapcode',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:SapcodeObject[]')
    ),
    'tns:SapcodeObject'
);

$server->register('get_data_sapcode',
    array('pass' =>'xsd:string'),
    array('return' => 'tns:ListSapcode'),
    'urn:orderwsdl2',
    'orderwsdl2#get_data_sapcode',
    'rpc',
    'encoded',
    'Returns array data in php web service'
);
*/
$server->register("get_data_sapcode",
    array('pass' => 'xsd:string'),
    array('return' => 'xsd:string'),
    'urn:orderwsdl2',
    'orderwsdl2#get_data_sapcode');
function get_data_sapcode($pass){
    $p = 'dienmaycholon5r75476456455634578';
    $p = md5(md5($p));
    if($p == md5(md5($pass)) && !empty($pass)){
        $query = "SELECT sap_code FROM pro_product a LEFT JOIN pro_supplier_product b ON a.id = b.cid_product
        where b.cid_supplier=1";
        $result = mysql_query($query);
        $a = array();
        while($row = mysql_fetch_assoc($result)){
            //$a[] = $row;
            $a[] = preg_replace('/\s+/', '', $row['sap_code']);
        }
        return json_encode($a);
    }
    mysql_close($dbconn);
}
/***********************************************************END SAPCODE SẢN PHẨM*************************/
// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
