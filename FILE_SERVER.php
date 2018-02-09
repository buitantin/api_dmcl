<?php
class ServerController extends Application_Model_App
{
    public function init(){
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); 
        parent::init();
        $this->time_start = microtime(true);
        $this->TTCommentContact         =   new Application_Model_DbTable_Tm_Storereview();
        $this->Store                    =   new Application_Model_DbTable_Tm_Store();
        $this->Server                   =   new Default_Model_Server();
        $this->view->Model_Artreview    =   $this->Model_Artreview              =   new Default_Model_Artreview();
        $this->TTLocation               =   $this->view->TTLocation             =   new Application_Model_DbTable_Tm_Cities ();
        $this->News                     =   $this->view->News                   =   new Default_Model_News();
        $this->TTDefaultBanner          =   $this->view->TTDefaultBanner        =   new Default_Model_Banner();
        $this->TTDefaultPage            =   $this->view->TTDefaultPage          =   new Default_Model_Page();
        $this->view->Model_News         =   $this->Model_News                   =   new Default_Model_News();
        $this->TTNews                   =   $this->view->TTNews                 =   new Default_Model_News();
        $this->TTDefaultReview          =   $this->view->TTDefaultReview        =   new Default_Model_Review();
        $this->TTDefaultProduct         =   $this->view->TTDefaultProduct       =   new Default_Model_Product();
        $this->TTDefaultPromotion       =   $this->view->TTDefaultPromotion     =   new Default_Model_Promotion();
        $this->TTDefalutPayment         =   $this->view->TTDefalutPayment       =   new Default_Model_Payment();
        $this->view->TTDefaultCate      =   $this->TTDefaultCate                =   new Default_Model_Categories();
        $this->TTDefaultTemplate        =   $this->view->TTDefaultTemplate      =   new Default_Model_Template();
        $this->TTDefaultSeries          =   $this->view->TTDefaultSeries        =   new Default_Model_Series();
        $this->view->TTDefaultPayment   =   $this->TTDefaultPayment             =   new Default_Model_Payment();
        $this->TTReview                 =   $this->view->TTReview               =   new Default_Model_Review();
        $this->Tm_Slideshow             =   $this->view->Tm_Slideshow           =   new Application_Model_DbTable_Tm_Slideshow();
        $this->memberbenefits           =   $this->view->memberbenefits         =   new Application_Model_DbTable_Tm_Memberbenefits();
        $this->TTUsers                  =   $this->view->TTUsers                =   new Application_Model_DbTable_Tm_Customer();
        $this->TTComment                =   $this->view->TTComment              =   new Application_Model_DbTable_Ex_Comment();
        $this->TTWarranty               =   $this->view->TTWarranty             =   new Default_Model_Warranty();
        $this->Url_dienmaycholon        =   'https://dienmaycholon.vn';
        $_action                        =   array(
            'gettoken',
            'viewtoken',
            'productdetail',
            'examplecart',
        );
        if(!in_array($this->view->action, $_action))
        {       
            if($token=$this->_getParam("token"))
            {   
                if($token != $_SESSION['token_api_mobile'])
                {   
                    $data['message']     = 'Token không đúng hoặc hết hạn';
                    $data['errorcode']   = -1;
                    $data['data']        = array();
                    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );          
                    exit;               
                }           
            }
            else
            {
                $data['message']     = 'Vui lòng nhập token';
                $data['errorcode']   = -1;
                $data['data']        = array();
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );              
                exit;   
            }   
        }
    }

    public function gettokenAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $_array_payload                 = array(
                                        'country'       => 'Vietnam',
                                        'name'          => 'dienmaycholon',
                                        'password'      => 'info@dienmaycholon.com.vn',
                                        );
        $encoded_header                 = base64_encode('{"alg": "HS256","typ": "JWT"}');
        $encoded_payload                = base64_encode(json_encode($_array_payload));
        $header_payload                 = $encoded_header . '.' . $encoded_payload;
        $secret_key                     = md5( date("Y-m-d H:i:s"));
        $signature                      = base64_encode(hash_hmac('sha256', $header_payload, $secret_key, true));
        $_SESSION['token_api_mobile']   = md5($header_payload . '.' . $signature);  
        $data['message']                = 'lấy token thành công';
        $data['errorcode']              = 0;
        $data['data']                   = $_SESSION['token_api_mobile'];
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    public function gettokenLogin($country = 'Vietnam', $name = 'dienmaycholon', $password = 'info@dienmaycholon.com.vn')
    {
        $_array_payload                 = array(
                                        'country'       => $country,
                                        'name'          => $name,
                                        'password'      => $password,
                                        );
        $encoded_header                 = base64_encode('{"alg": "HS256","typ": "JWT"}');
        $encoded_payload                = base64_encode(json_encode($_array_payload));
        $header_payload                 = $encoded_header . '.' . $encoded_payload;
        $secret_key                     = md5( date("Y-m-d H:i:s"));
        $signature                      = base64_encode(hash_hmac('sha256', $header_payload, $secret_key, true));
        $_SESSION['token_api_mobile']   = $header_payload . '.' . $signature;
        return $_SESSION['token_api_mobile'];
    }

    function checkPhoto($link = '')
    {
        if (strpos($link, 'static.dienmaycholon.vn') !== false) {
            return $link;
        }
        else
        {
            return $this->Url_dienmaycholon.$link;
        }
    }

    function getapiAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $url  = 'https://dienmaycholon.vn/default/server/logindmcl/username/pingo9339@gmail.com/password/123456789';
        $username = "admin";
        $password = "admin@market";
        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,    // for https
            CURLOPT_USERPWD        => $username . ":" . $password,
            CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
            CURLOPT_POST           => true,
        );
        $ch = curl_init();
        curl_setopt_array( $ch, $options );
        try 
        {
            $raw_response  = curl_exec( $ch );
            // validate CURL status
            if(curl_errno($ch))
            {
                throw new Exception(curl_error($ch), 500);
            }
            // validate HTTP status code (user/password credential issues)
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status_code != 200)
            {
                throw new Exception("Response with Status Code [" . $status_code . "].", 500);
            }
        } 
        catch(Exception $ex) 
        {

        }
        if ($ch != null) 
        {
            curl_close($ch);
        }
        echo $raw_response;
    }

    function authentication(){
        $realm = 'Điện máy chợ lớn';
        $users = array('admin' => 'admin@market');
        if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Digest realm="'.$realm.
                   '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
            die('Thoát thành công');
        }
        // analyze the PHP_AUTH_DIGEST variable
        if (!($data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
            !isset($users[$data['username']]))
        {
            header('HTTP/1.1 401 Unauthorized');
            die('Sai username!');
        }
        // generate the valid response
        $A1                    = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
        $A2                    = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $valid_response        = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
        if ($data['response'] != $valid_response)
        {
            header('HTTP/1.1 401 Unauthorized');
            die('Sai password!');
        }
    }

    function stripTagsNew($string, $allowed_tags = null, $allowed_attributes = null)
    {
        $string = $this->filter->stripTags($string);
        $string = trim(preg_replace('/\s\s+/', ' ', $string));
        $string = str_replace("&aacute", "á", $string);
        $string = str_replace("&agrave", "à", $string);
        $string = str_replace("&acirc",  "â", $string);
        $string = str_replace("&atilde", "ã", $string);
        $string = str_replace("&egrave", "è", $string);
        $string = str_replace("&eacute", "é", $string);
        $string = str_replace("&ecirc",  "ê", $string);
        $string = str_replace("&igrave", "ì", $string);
        $string = str_replace("&iacute", "í", $string);
        $string = str_replace("&eth",    "đ", $string);
        $string = str_replace("&ograve", "ò", $string);
        $string = str_replace("&oacute", "ó", $string);
        $string = str_replace("&otilde", "õ", $string);
        $string = str_replace("&ocirc",  "ô", $string);
        $string = str_replace("&ugrave", "ù", $string);
        $string = str_replace("&uacute", "ú", $string);
        $string = str_replace("&yacute", "ý", $string);
        $string = str_replace("&nbsp",   " ", $string);
        $string = str_replace("&amp",    "&", $string);
        $string = str_replace("&ldquo",  '"', $string);
        $string = str_replace("&rdquo",  '"', $string);
        $string = str_replace("&ndash",  '–', $string);
        $string = str_replace("&hellip", '…', $string);
        return $string;
    }

    function changeHtml($string = '')
    {
        $string = str_replace("/public", $this->Url_dienmaycholon.'/public', $string);
        return $string;
    }

    function http_digest_parse($txt)
    {
        // protect against missing data
        $needed_parts   = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data           = array();
        $keys           = implode('|', array_keys($needed_parts));
        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
        foreach ($matches as $m)
        {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }
        return $needed_parts ? false : $data;
    }

    // thay đổi url chi tiết sản phẩm
    public function changeUrlDetail($cate,$product){
        $cate     =  $this->filter->toAlias2($cate);
        $product  =  $this->filter->toAlias2($product);
        return '/cate/'.$cate.'/product/'.$product;
    }

    public function viewtokenAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $data['message']              = 'get token';
        $data['errorcode']            = 0;
        $data['data']                 = $_SESSION['token_api_mobile'];
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    public function cleantokenAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        unset($_SESSION['token_api_mobile']);
        $data['message']     = 'clean token';
        $data['errorcode']   = 0;
        $data['data']        = 'Xóa token thành công';
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); 
    }

    //lay thong tin don hang
    function get_data($from_date,$to_date,$pass)
    {
        $where  = '';
        $p      = 'dienmaycholon5r75476456455634578';
        $p      = md5(md5($p));
        if($p == md5(md5($pass)) && !empty($pass))
        {      
            $where .= " or_or.date_bill >= '".$from_date." 00:00:00' and or_or.date_bill <= '".$to_date." 23:59:59' and approved=0";    
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
                        WHERE".$where;
            $result     = mysql_query($query);
            $a          = array();
            while($row  = mysql_fetch_assoc($result))
            {
                $a[]    = $row;
            }
            $return     = array();
              for($i=0;$i<=count($a)-1;$i++) {
               $return[$i]=$a[$i];
            }
            return $return;
        }
        mysql_close($dbconn);
    }

    public function startAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        /*$server = new Zend_Soap_Server(null, $options);
        // Bind Class to Soap Server
        $server->addFunction('get_data');
        // Bind already initialized object to Soap Server
        $server->handle();*/
        $number=1;
        if (PHP_OS == 'Linux' AND @file_exists('/proc/loadavg') AND $filestuff =@file_get_contents('/proc/loadavg')){ 
            $loadavg = explode(' ', $filestuff); 

            var_dump($loadavg);exit;
            if (trim($loadavg[0]) > $numer) { 
                print 'server busy, quay lại sau….'; 
                exit(0); 
            } 
        } 
        return true;
    }

    // DANH MUC SẢN PHÂM NGOÀI TRANG CHỦ
    public function loadmenuAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $list       =   array(1, 2, 9, 15, 19, 21, 120);
        $spec_list  =   $this->TTCate->getListMenu($list);
        $data       =   array();
        $result     =   array();
        $_count     =   1;
        foreach ($list as $parent_id) 
        {
            $result_parent                      = array();
            $Parent                             = $this->view->DTCATE_PARENT[$parent_id];
            $result_parent['id']                = $Parent->id;
            $result_parent['name']              = $Parent->name;
            $result_parent['alias']             = $Parent->alias;
            $result_parent['cate_description']  = $Parent->cate_description;
            // $result_parent['cate_banner']    = (array)json_decode($Parent->cate_banner);
            $banner                             = (array)json_decode($Parent->cate_banner);
            if(!empty($banner))
            {
                if(!empty($banner[0]))
                {
                    $_array_banner = array();
                    $_array_banner['name']                  = $banner[0]->name_banner_1;
                    $_array_banner['link']                  = $banner[0]->links_banner_1;
                    $_array_banner['order']                 = $banner[0]->order_banner_1;
                    $_array_banner['status']                = $banner[0]->status_banner_1;
                    $_array_banner['photo']                 = $this->Url_dienmaycholon.'/public/picture/cate/'.$banner[0]->bannerName;
                    $result_parent['cate_banner'][]         = $_array_banner;
                }
                if(!empty($banner[1]))
                {
                    $_array_banner = array();
                    $_array_banner['name']                  = $banner[1]->name_banner_2;
                    $_array_banner['link']                  = $banner[1]->links_banner_2;
                    $_array_banner['order']                 = $banner[1]->order_banner_2;
                    $_array_banner['status']                = $banner[1]->status_banner_2;
                    $_array_banner['photo']                 = $this->Url_dienmaycholon.'/public/picture/cate/'.$banner[1]->bannerName;
                    $result_parent['cate_banner'][]         = $_array_banner;
                }
                if(!empty($banner[2]))
                {
                    $_array_banner = array();
                    $_array_banner['name']                  = $banner[2]->name_banner_3;
                    $_array_banner['link']                  = $banner[2]->links_banner_3;
                    $_array_banner['order']                 = $banner[2]->order_banner_3;
                    $_array_banner['status']                = $banner[2]->status_banner_3;
                    $_array_banner['photo']                 = $this->Url_dienmaycholon.'/public/picture/cate/'.$banner[2]->bannerName;
                    $result_parent['cate_banner'][]         = $_array_banner;
                }
            }
            foreach ($this->view->DTCATE_CHILD[$Parent->id] as $item){
                $_array_child                               = array();
                $_array_child['id']                         = $item['id'];
                $_array_child['name']                       = $item['name'];
                $_array_child['alias']                      = $item['alias'];
                $_array_child['ordering']                   = $item['ordering'];
                $_array_child['status']                     = $item['status'];
                $_array_child['cid_parent']                 = $item['cid_parent'];
                $_array_child['has_coupon']                 = $item['has_coupon'];
                $_array_child['tag_title']                  = $item['tag_title'];
                $_array_child['tag_keyword']                = $item['tag_keyword'];
                $_array_child['tag_description']            = $item['tag_description'];
                $_array_child['links_left']                 = $item['links_left'];
                $_array_child['links_right']                = $item['links_right'];
                $_array_child['links_banner_top']           = $item['links_banner_top'];
                $_array_child['link_banner_left_bottom']    = $item['link_banner_left_bottom'];
                $_array_child['cate_description']           = $item['cate_description'];
                $_array_child['cate_banner']                = $item['cate_banner'];
                $_array_child['created']                    = $item['created'];
                $photo_one = (!empty($item['photo_one']))?'https://dienmaycholon.vn/public/picture/cate/'.$item['photo_one']:'';
                $_array_child['photo_one']                  = $photo_one;
                $photo_two = (!empty($item['photo_two']))?'https://dienmaycholon.vn/public/picture/cate/'.$item['photo_two']:'';
                $_array_child['photo_two']                  = $photo_two;
                $photo_tg = (!empty($item['picture_tg']))?'https://dienmaycholon.vn/public/picture/cate/'.$item['picture_tg']:'';
                $_array_child['photo_tg']                   = $photo_tg;
                $_array_child['icon']                       = $this->checkPhoto('/public/picture/cate/cate_child_'.$item['id'].'.png');
                $result_parent['child'][]                   = $_array_child;
            }
            $result[] = $result_parent;
        }   
        $data['message']     = 'menu top';
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // DANH MỤC SẢN PHÂM DƯƠÍ SLIDER (GIAO DIÊN MOBIE)
    public function morethanmenuAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $list   = array(1, 2, 9, 15, 19, 21, 120);
        $result = array();
        $_count = 1;
        foreach ($list as $l) {
            $result[$_count]['name'] = $this->view->DTCATE_CHILD[$l][0]['name'];
            $result[$_count]['href'] = $this->filter->toAlias2($this->view->DTCATE_CHILD[$l][0]['name']);
            $_count++;
        }
        $data['message']     = 'more than menu';
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //SLIDER NGOÀI TRANG CHỦ
    public function boxslidehomeAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $_count = 0;
        foreach ($this->view->DTSlideshow as $slide) 
        {
            $result[$_count]['links']             = $slide->links;
            $result[$_count]['name']              = $slide->name;
            $result[$_count]['id']                = $slide->id;
            $result[$_count]['slide_description'] = $slide->slide_description;
            $result[$_count]['photo']             = $this->Url_dienmaycholon.'/public/picture/slideshow/'.$slide->slide_name;
            $_count++;
        }
        $data['message']     = 'Lấy kết quả banner slide thành công';
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // DANH SÁCH SẢN PHÂM KHUYÊN MÃI
    public function listpromotionAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $list_cate = $this->TTDefaultProduct->NewlistHome();
        $mydata = $list_cate[0];
        $result['name'] = $mydata['data']['name'];
        $list_product = $this->TTDefaultProduct->List_Proudct($mydata['data'],8);
        $_count = 1;
        foreach($list_product[0] as $product){
            $_result_product = array();
            $product=$this->TTDefaultPromotion->getPriceParent($product);
            // $result[0]['child'][$_count]['name']                 = $product['name'];
            $_result_product['myid']                = $product['myid'];
            $_result_product['name']                = $product['name'];
            $_result_product['cid_series']          = $product['cid_series'];
            $_result_product['isprice']             = $product['isprice'];
            $_result_product['is_icon']             = $product['is_icon'];
            $_result_product['is_model']            = $product['is_model'];
            $_result_product['is_price']            = $product['is_price'];
            $_result_product['is_flash_sale']       = $product['is_flash_sale'];
            $_result_product['is_sale']             = $product['is_sale'];
            $_result_product['new_description']     = $this->filter->NewDescription($product['new_description']);
            $_result_product['coupons']             = $product['coupons'];
            $_result_product['discountcoupons']     = $product['discountcoupons'];
            $_result_product['discount']            = $product['discount'];
            $_result_product['saleprice']           = $product['saleprice'];
            $_result_product['is_promotion']        = $product['is_promotion'];
            $_result_product['cid_res']             = $product['cid_res'];
            $_result_product['namecate']            = $product['namecate'];
            $_result_product['id_detail']           = $this->changeUrlDetail($product['namecate'],$product['name']);
            $_result_product['photo']               = $this->Url_dienmaycholon.$this->filter->get_image_product_lcd_home($product['myid'],'',150,150);
            $gift=$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
            if(empty($gift['total']))
            {
                $_result_product['gift'] = '';
            }
            else
            {
                $_result_product['gift'] = $gift['total'];
            }
            $attribute  = $this->TTDefaultTemplate->getSpecial($product['myid']);
            $_count_att = 1;
            foreach ($attribute as $attr)
            {
                $_result_attr           = array();
                $_result_attr['id']     = $attr['id'];
                $_result_attr['val']    = $attr['val'];
                $_result_product['element_special'][] = $_result_attr;
            }
            if(empty($_result_product['element_special']))
            {
                $_result_product['element_special'] = array();
            }
            $result['child'][]  = $_result_product;
        }
        $banner=(array)json_decode($mydata['data']['level_banner']);
        if(!empty($banner))
        {
            if(!empty($banner[0]))
            {
                $_array_banner = array();
                $_array_banner['name']                  = $banner[0]->name_banner_1;
                $_array_banner['link']                  = $banner[0]->links_banner_1;
                $_array_banner['order']                 = $banner[0]->order_banner_1;
                $_array_banner['status']                = $banner[0]->status_banner_1;
                $_array_banner['photo']             = '/public/picture/banner/'.$banner[0]->bannerName;
                $result['banner'][]                     = $_array_banner;
            }
            if(!empty($banner[1]))
            {
                $_array_banner = array();
                $_array_banner['name']                  = $banner[1]->name_banner_2;
                $_array_banner['link']                  = $banner[1]->links_banner_2;
                $_array_banner['order']                 = $banner[1]->order_banner_2;
                $_array_banner['status']                = $banner[1]->status_banner_2;
                $_array_banner['photo']             = '/public/picture/banner/'.$banner[1]->bannerName;
                $result['banner'][]                     = $_array_banner;
            }
            if(!empty($banner[2]))
            {
                $_array_banner = array();
                $_array_banner['name']                  = $banner[2]->name_banner_3;
                $_array_banner['link']                  = $banner[2]->links_banner_3;
                $_array_banner['order']                 = $banner[2]->order_banner_3;
                $_array_banner['status']                = $banner[2]->status_banner_3;
                $_array_banner['photo']                 = '/public/picture/banner/'.$banner[2]->bannerName;
                $result['banner'][]                     = $_array_banner;
            }
        }
        $message                            = (!empty($result))?'Trả về sản phẩm khuyến mãi thành công':'Không có dữ liệu';
        $data['message']                    = $message;
        $data['errorcode']                  = 0;
        $data['data']                       = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    public function containerproductAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start = microtime(true);
        $list_cate      = $this->TTDefaultProduct->NewlistHome();
        $mydata         = $list_cate[0];
        $list           = array(1, 2, 9, 15, 19, 21);
        $_count_parent  = 0;
        $result         = array();

        
        foreach($list as $l)
        {
            $_result_data                     = array();
            $_result_data['name']             = $mydata['data']['name'];
            $_result_data['alias']            = $this->filter->toAlias2($mydata['data']['name']);
            $list_product                     = $this->TTDefaultProduct->List_Proudct($mydata['data'],8);

            $_count_list                      = 0;
            foreach($list_product[0] as $product){
                $_result_list                       = array();
                $product                            = $this->TTDefaultPromotion->getPriceParent($product);
                $_result_list['myid']               = $product['myid'];
                $_result_list['name']               = $product['name'];
                $_result_list['series']             = $this->checkPhoto($this->filter->resizeSeries($product['cid_series'],71,15));
                $_result_list['cid_series']         = $product['cid_series'];
                $_result_list['isprice']            = $product['isprice'];
                $_result_list['is_icon']            = $product['is_icon'];
                $_result_list['is_model']           = $product['is_model'];
                $_result_list['is_price']           = $product['is_price'];
                $_result_list['is_flash_sale']      = $product['is_flash_sale'];
                $_result_list['is_sale']            = $product['is_sale'];
                $_result_list['new_description']    = $product['new_description'];
                $_result_list['coupons']            = $product['coupons'];
                $_result_list['discountcoupons']    = $product['discountcoupons'];
                $_result_list['discount']           = $product['discount'];
                $_result_list['saleprice']          = $product['saleprice'];
                $_result_list['is_promotion']       = $product['is_promotion'];
                $_result_list['cid_res']            = $product['cid_res'];
                $_result_list['namecate']           = $product['namecate'];
                $_result_list['id_detail']          = $this->changeUrlDetail($product['namecate'],$product['name']);
                $_result_list['detail_link_web']    = $this->view->url(array("cate"=>$this->filter->toAlias2($product['namecate']),"product"=>$this->filter->toAlias2($product['name']) ) ,"detail" );
                $_result_list['photo']              = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',150,150));
                $gift=$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                if(empty($gift['total']))
                {
                    $_result_list['gift'] = null;
                }
                else
                {
                    $_result_list['gift'] = $gift['total'];
                }
                $attribute=$this->TTDefaultTemplate->getSpecial($product['myid']);
                foreach ($attribute as $attr)
                {
                    $_result_attr = array();
                    $_result_attr['id']                 = $attr['id'];
                    $_result_attr['val']                = $attr['val'];
                    $_result_list['element_special'][]  = $_result_attr;
                    $_count_att++;
                }
                if(empty($_result_list['element_special']))
                {
                    $_result_list['element_special']    = array();
                }
                $_result_data['child'][] = $_result_list;
            }
            $spect=$this->TTDefaultProduct->getSpec($mydata['data']['spec_id']);
            if(count($spect)>0)
            {
                $_count_spect = 0;
                foreach($spect as $sp)
                {
                    $_result_spect = array();
                    $_result_spect['content']           = $sp['content'];
                    $_result_spect['spec_description']  = $sp['spec_description'];
                    $_result_spect['title']             = $sp['title'];
                    $_result_data['spect'][]            = $_result_spect;
                }
            }
            else
            {
                $_result_data['spect'] = array();
            }
            $banner=(array)json_decode($mydata['data']['level_banner']);
            if(!empty($banner))
            {
                if(!empty($banner[0]))
                {
                    $_array_banner = array();
                    $_array_banner['name']                  = $banner[0]->name_banner_1;
                    $_array_banner['link']                  = $banner[0]->links_banner_1;
                    $_array_banner['order']                 = $banner[0]->order_banner_1;
                    $_array_banner['status']                = $banner[0]->status_banner_1;
                    $_array_banner['photo']                 = $this->Url_dienmaycholon.'/public/picture/banner/'.$banner[0]->bannerName;
                    $_result_data['banner'][]               = $_array_banner;
                }
                if(!empty($banner[1]))
                {
                    $_array_banner = array();
                    $_array_banner['name']                  = $banner[1]->name_banner_2;
                    $_array_banner['link']                  = $banner[1]->links_banner_2;
                    $_array_banner['order']                 = $banner[1]->order_banner_2;
                    $_array_banner['status']                = $banner[1]->status_banner_2;
                    $_array_banner['photo']                 = $this->Url_dienmaycholon.'/public/picture/banner/'.$banner[1]->bannerName;
                    $_result_data['banner'][]               = $_array_banner;
                }
                if(!empty($banner[2]))
                {
                    $_array_banner = array();
                    $_array_banner['name']                  = $banner[2]->name_banner_3;
                    $_array_banner['link']                  = $banner[2]->links_banner_3;
                    $_array_banner['order']                 = $banner[2]->order_banner_3;
                    $_array_banner['status']                = $banner[2]->status_banner_3;
                    $_array_banner['photo']                 = $this->Url_dienmaycholon.'/public/picture/banner/'.$banner[2]->bannerName;
                    $_result_data['banner'][]               = $_array_banner;
                }
            }
            else
            {
                $_result_data['banner'] = array();
            }
            $mydata=$list_cate[$l];
            $result[] = $_result_data;
        }
        $message                            = (!empty($result))?'Trả về sản phẩm trang chủ thành công':'Không có dữ liệu';
        $data['message']                    = $message;
        $data['errorcode']                  = (!empty($result))?0:1;
        $data['data']                       = $result;
        echo json_encode((array)$data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // danh mục sản phẩm cha
    public function getsubcategoryAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($cate=$this->_getParam("cate"))
        {
            $TCate=$this->TTDefaultCate->getCurrentCate($cate,$this->view->DTCATE_PARENT,$this->view->DTCATE_CHILD,$this->view->filter);
            if($TCate)
            {
                if($TCate->cid_parent=='0'){
                    $this->view->Cate=$TCate;
                    $product=array();
                    foreach($this->view->DTCATE_CHILD[$TCate->id] as $cate){
                       $product[$cate['id']] =$this->TTDefaultProduct->listCateProductParent($cate['id']);
                    }
                    $this->view->List_Product=$product;
                }else{
                    $this->_helper->viewRenderer->setScriptAction("catechild");
                    $this->view->name_page          = $TCate['name'];
                    $this->view->id_cate            = $TCate['id'];
                    $this->view->title_name         = "";
                    $this->view->cate               = $TCate;
                    $this->view->parent             = $this->view->DTCATE_PARENT[$TCate['cid_parent']];
                    $this->view->Template           = $this->TTDefaultTemplate->getFilter_Child($TCate['id']);
                    $this->view->Series             = $this->TTDefaultSeries->getChildCate($TCate['id']);
                    $this->view->Utility            = $this->TTDefaultProduct->getFilterUtility($TCate['id']);
                    $this->view->Price              = $this->TTDefaultProduct->getFilterPrice($TCate['id']);
                    $this->view->getArticleFooter   = $this->TTDefaultProduct->getArticleFooter($cate);
                    $sql                            = "";
                    if($g=$this->_getParam("g")){
                        $sql=$this->TTDefaultProduct->FilterAttr($g);
                        $this->view->title_name=$this->TTDefaultTemplate->FilterAttrName($g);
                    }
                    if($t=$this->_getParam("t")){
                        if($sql=$sql.$this->TTDefaultTemplate->FilterTemp($t,$this->view->Template) ){
                            $this->view->title_name=$this->TTDefaultTemplate->NameFilterTemp($t,$this->view->Template);
                        }else{
                            $error_301 = 'HTTP/1.1 301 Not Found';
                            $this->getResponse()->setRawHeader($error_301);
                            http_response_code(301);
                            $this->_redirect("/{$TCate['alias']}");
                        }
                    }
                    if($u  = $this->_getParam("u")){
                      $sql = $sql.$this->TTDefaultTemplate->FilterUtility($u,$this->view->Utility);
                      $this->view->title_name=$this->TTDefaultTemplate->NameFilterUtility($u,$this->view->Utility);
                    }
                    if($s  = $this->_getParam("s")){
                      $sql = $sql.$this->TTDefaultTemplate->FilterSeries($s,$this->view->Series);
                    }
                    if($p  = $this->_getParam("p")){
                      $sql = $sql.$this->TTDefaultTemplate->FilterPrice($p,$this->view->Price);
                      $this->view->title_name=$this->TTDefaultTemplate->FilterPriceName($p,$this->view->Price);
                    }
                    $this->view->page=$this->_getParam("page",1);
                    $list  = Zend_Paginator::factory($this->TTDefaultProduct->Product_Cate($TCate['id'],$sql) );
                    $list->setCurrentPageNumber($this->view->page);
                    $list->setItemCountPerPage(12);
                    $list->setPageRange(5);
                    $this->view->list=$list;
                    if(!empty($this->view->title_name) && empty($this->view->headTitle()[0])){
                        $this->view->headTitle($this->filter->subTitle($this->view->name_page." ".$this->view->title_name,65) );
                    }
                }
            }
            else
            {  
                if(strpos($cate, "san-pham-tra-gop")!==false){
                    throw new Exception("Error Processing Request", 1);
                    $this->_helper->viewRenderer->setScriptAction("paymentproduct");
                    $this->view->Price=$this->TTDefaultProduct->getFilterPrice(8);
                    $this->view->Cate=$this->TTDefaultPayment->getCate();
                    $this->view->Series=$this->TTDefaultPayment->getSeries();
                    $sql="";
                    $this->view->name_page="Sản phẩm trả góp ";
                    $Myseries=$this->TTDefaultTemplate->FilterSeriesName(str_replace("san-pham-tra-gop-", "", $cate),$this->view->Series);
                    if(empty($Myseries)){
                       echo "installment not series ";exit;
                    }
                    $this->view->title_name = $Myseries['name'];
                    $this->view->myseries   = true;
                    $this->view->is_series  = $Myseries['id']; 
                    $sql     = ""; 
                    if($ss   = $this->_getParam("s")){
                        $s   = array();
                        $s[] = $ss;
                        $s[] = $this->filter->toAlias2($Myseries['name']);
                        $sql = $sql.$this->TTDefaultTemplate->FilterSeries($s,$this->view->Series) ;
                    }else{
                        $sql = " AND a.cid_series = ".$Myseries['id']." ";
                    }
                    if($c    = $this->_getParam("c")){
                        $sql = $this->TTDefaultTemplate->FilterCate($c,$this->view->Cate);
                          $this->view->title_name .= $this->TTDefaultTemplate->FilterCateName($c,$this->view->Cate);
                    }
                    if($g    = $this->_getParam("g")){
                        $sql = $sql.$this->TTDefaultProduct->FilterAttr($g);
                        $this->view->title_name .= $this->TTDefaultTemplate->FilterAttrName($g);
                        $this->view->title_name = str_replace("Trả góp", "", $this->view->title_name );
                    }
                    if($p    = $this->_getParam("p")){
                        $sql = $sql.$this->TTDefaultTemplate->FilterPrice($p,$this->view->Price);
                        $this->view->title_name .= $this->TTDefaultTemplate->FilterPriceName($p,$this->view->Price);
                    }
                    $this->view->page = $this->_getParam("page",1);
                    $list             = Zend_Paginator::factory($this->TTDefaultPayment->getAllProductOfInstallment($sql,$orderby));
                    $list->setCurrentPageNumber($this->view->page);
                    $list->setItemCountPerPage(12);
                    $list->setPageRange(5);
                    $this->view->list = $list;
                    $this->view->total_comment = $this->TTDefaultPayment->getTotalComment();
                    $this->view->list_article  = $this->TTDefaultPayment->getReview();
                }
                $this->_helper->viewRenderer->setScriptAction("catechild");
                $All = $this->TTDefaultCate->getCateAndSeries($cate,$this->view->DTCATE_CHILD,$this->TTDefaultSeries,$this->view->filter);
                if(!empty($All)){
                    $TCate                  = $All['c'];
                    $this->view->myseries   = true;
                    $this->view->cate       = $TCate;
                    $this->view->name_page  = $TCate['name'];
                    $this->view->id_cate    = $TCate['id'];
                    $this->view->is_series  = $All['s']['cid_series'];
                    $this->view->Series     = $this->TTDefaultSeries->getChildCate($TCate['id']);
                    $this->view->title_name = $All['s']['name'];
                    $this->view->parent     = $this->view->DTCATE_PARENT[$TCate['cid_parent']];
                    $this->view->Template   = $this->TTDefaultTemplate->getFilter_Child($TCate['id']);
                    $this->view->Utility    = $this->TTDefaultProduct->getFilterUtility($TCate['id']);
                    $this->view->Price      = $this->TTDefaultProduct->getFilterPrice($TCate['id']);
                    if(empty($this->view->Page_Seo_Footer)){
                        $check_seo          = $this->TTCate->checkSeo("/".$TCate['alias']);
                        if(!empty($check_seo)){
                            $this->view->Page_Seo_Footer=$check_seo;
                            $this->view->getArticleFooter=$this->TTDefaultProduct->getArticleFooter($TCate['alias']);
                        }
                    }
                    $sql=""; 
                    if($ss=$this->_getParam("s")){
                        $s=array();
                        $s[]=$ss;
                        $s[]=$this->filter->toAlias2($All['s']['name']);
                        $sql=$sql.$this->TTDefaultTemplate->FilterSeries($s,$this->view->Series) ;
                    }else{
                        $sql=" AND a.cid_series = ".$All['s']['idseries']." ";
                    }
                    if($g=$this->_getParam("g")){
                        $sql = $sql.$this->TTDefaultProduct->FilterAttr($g);
                        $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->FilterAttrName($g);
                    }
                    if($t=$this->_getParam("t")){
                        $check_t=$this->TTDefaultTemplate->FilterTemp($t,$this->view->Template);
                        if($check_t!=""){
                            $sql=$sql.$check_t;
                            $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->NameFilterTemp($t,$this->view->Template);
                        }
                        else
                        {
                            $error_301 = 'HTTP/1.1 301 Not Found';
                            $this->getResponse()->setRawHeader($error_301);
                            http_response_code(301);
                            $this->_redirect("/{$cate}");
                        }
                    }
                    if($u=$this->_getParam("u")){
                        $sql=$sql.$this->TTDefaultTemplate->FilterUtility($u,$this->view->Utility);
                        $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->NameFilterUtility($u,$this->view->Utility);
                    }
                    if($p=$this->_getParam("p")){
                        $sql=$sql.$this->TTDefaultTemplate->FilterPrice($p,$this->view->Price);
                        $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->FilterPriceName($p,$this->view->Price);
                    }
                    $this->view->page=$this->_getParam("page",1);
                    $list=Zend_Paginator::factory($this->TTDefaultProduct->Product_Cate($TCate['id'],$sql) );
                    $list->setCurrentPageNumber($this->view->page);
                    $list->setItemCountPerPage(12);
                    $list->setPageRange(5);
                    $this->view->list=$list;
                    if(empty($this->view->headTitle()[0])){
                        $this->view->headTitle( $this->filter->subTitle($this->view->name_page." ".$this->view->title_name ) );
                    }
                }
            }
        }

        $result = array();
        if($TCate){
            if($TCate->cid_parent=='0'){
                $result['name'] = (!empty($TCate->name))?$TCate->name:$TCate['name'];
                foreach ($this->view->DTCATE_CHILD[$TCate->id] as $cate)
                {
                    $_result_cate = array();
                    if(!empty($this->view->List_Product[$cate['id']]))
                    {
                        $_result_cate['name'] = $cate['name'];
                        $_result_cate['id']   = $cate['id'];
                    }
                    $result['cate_child'][] = $_result_cate;
                }
                foreach($this->view->DTCATE_CHILD[$TCate->id] as $cate)
                {
                    $_result_cate = array();
                    if($_d==1 && $TCate->alias=='dien-tu'){
                        $_result_cate['name']    = 'Loa Amply Cao Cấp';
                        $_result_cate['id']      = '';
                        $_result_cate['alias']   =  $cate['alias'];
                        foreach($this->view->TTDefaultProduct->getAmply() as $product)
                        {
                            $_result_product             = array();
                            $product=$this->view->TTDefaultPromotion->getPriceParent($product);
                            $_result_product             = $product;
                            $_result_product['series']   = $this->checkPhoto('/public/picture/series/dienmay_' .$product['cid_series'].'.png');
                            $_result_product['photo']    = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',150,150));
                            $_result_product['id_detail']= $this->changeUrlDetail($product['namecate'],$product['name']);
                            $gift=$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                            if(empty($gift['total']))
                            {
                                $_result_product['gift'] = null;
                            }
                            else
                            {
                                $_result_product['gift'] = $gift['total'];
                            }
                            $attribute=$this->TTDefaultTemplate->getSpecial($product['myid']);
                            foreach ($attribute as $attr)
                            {
                                $_result_attr        = array();
                                $_result_attr['id']  = $attr['id'];
                                $_result_attr['val'] = $attr['val'];
                                $_result_product['element_special'][]  =  $_result_attr;
                            }
                            if(empty($_result_product['element_special']))
                            {
                                $_result_product['element_special'] = array();
                            }
                            $_result_cate['product'][]  = $_result_product;
                        }
                    }
                    if(!empty($this->view->List_Product[$cate['id']]))
                    {
                        $_result_cate['name']    =  $cate['name'];
                        $_result_cate['id']      =  $cate['id'];
                        $_result_cate['alias']   =  $cate['alias'];
                        foreach($this->view->List_Product[$cate['id']] as $product)
                        {
                            $_result_product              = array();
                            $product=$this->view->TTDefaultPromotion->getPriceParent($product);
                            $_result_product              = $product;
                            $_result_product['series']    = $this->checkPhoto('/public/picture/series/dienmay_' .$product['cid_series'].'.png');
                            $_result_product['photo']     = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',150,150));
                            $_result_product['id_detail'] = $this->changeUrlDetail($product['namecate'],$product['name']);
                            $gift=$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                            if(empty($gift['total']))
                            {
                                $_result_product['gift']  = '';
                            }
                            else
                            {
                                $_result_product['gift']  = $gift['total'];
                            }
                            $attribute=$this->TTDefaultTemplate->getSpecial($product['myid']);
                            foreach ($attribute as $attr)
                            {
                                $_result_attr        = array();
                                $_result_attr['id']  = $attr['id'];
                                $_result_attr['val'] = $attr['val'];
                                $_result_product['element_special'][]  =  $_result_attr;
                            }
                            if(empty($_result_product['element_special']))
                            {
                                $_result_product['element_special'] = array();
                            }
                            $_result_cate['product'][]           = $_result_product;
                        }
                    }
                    $result['list_child'][]          = $_result_cate;
                }
                $message    = (!empty($result))?'Trả về danh mục sản phẩm thành công':'Không có dữ liệu';
            }
            else
            {
                $message    = 'Chỉ trả về danh mục cấp cha';
                $result     = array();
            }
        }
        $data['message']                    = $message;
        $data['errorcode']                  = (!empty($result))?0:1;
        $data['data']                       = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }
    // danh mục sản phẩm con
    public function getfeaturecateAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($cate=$this->_getParam("cate"))
        {
            $TCate=$this->TTDefaultCate->getCurrentCate($cate,$this->view->DTCATE_PARENT,$this->view->DTCATE_CHILD,$this->view->filter);
            if($TCate)
            {
                if($TCate->cid_parent=='0'){
                    $this->view->Cate=$TCate;
                    $product=array();
                    foreach($this->view->DTCATE_CHILD[$TCate->id] as $cate){
                       $product[$cate['id']] =$this->TTDefaultProduct->listCateProductParent($cate['id']);
                    }
                    $this->view->List_Product=$product;
                }else{
                    $this->_helper->viewRenderer->setScriptAction("catechild");
                    $this->view->name_page=  $TCate['name'];
                    $this->view->id_cate=$TCate['id'];
                    $this->view->title_name="";
                    $this->view->cate=$TCate;
                    $this->view->parent=$this->view->DTCATE_PARENT[$TCate['cid_parent']];
                    $this->view->Template=$this->TTDefaultTemplate->getFilter_Child($TCate['id']);
                    $this->view->Series=$this->TTDefaultSeries->getChildCate($TCate['id']);
                    $this->view->Utility=$this->TTDefaultProduct->getFilterUtility($TCate['id']);
                    $this->view->Price=$this->TTDefaultProduct->getFilterPrice($TCate['id']);
                    $this->view->getArticleFooter=$this->TTDefaultProduct->getArticleFooter($cate);
                    $sql="";
                    if($g=$this->_getParam("g")){
                        $sql=$this->TTDefaultProduct->FilterAttr($g);
                        $this->view->title_name=$this->TTDefaultTemplate->FilterAttrName($g);
                    }
                    if($t=$this->_getParam("t")){
                        if($sql=$sql.$this->TTDefaultTemplate->FilterTemp($t,$this->view->Template) ){
                            $this->view->title_name=$this->TTDefaultTemplate->NameFilterTemp($t,$this->view->Template);
                        }else{
                            $error_301 = 'HTTP/1.1 301 Not Found';
                            $this->getResponse()->setRawHeader($error_301);
                            http_response_code(301);
                            $this->_redirect("/{$TCate['alias']}");
                        }
                    }
                    if($u=$this->_getParam("u")){
                      $sql=$sql.$this->TTDefaultTemplate->FilterUtility($u,$this->view->Utility);
                      $this->view->title_name=$this->TTDefaultTemplate->NameFilterUtility($u,$this->view->Utility);
                    }
                    if($s=$this->_getParam("s")){
                      $sql=$sql.$this->TTDefaultTemplate->FilterSeries($s,$this->view->Series);
                    }
                    if($p=$this->_getParam("p")){
                      $sql=$sql.$this->TTDefaultTemplate->FilterPrice($p,$this->view->Price);
                      $this->view->title_name=$this->TTDefaultTemplate->FilterPriceName($p,$this->view->Price);
                    }
                    $this->view->page=$this->_getParam("page",1);
                    $list=Zend_Paginator::factory($this->TTDefaultProduct->Product_Cate($TCate['id'],$sql) );
                    $list->setCurrentPageNumber($this->view->page);
                    $list->setItemCountPerPage(12);
                    $list->setPageRange(5);
                    $this->view->list=$list;
                    if(!empty($this->view->title_name) && empty($this->view->headTitle()[0])){
                        $this->view->headTitle($this->filter->subTitle($this->view->name_page." ".$this->view->title_name,65) );
                    }
                }
            }
            else
            {  
                if(strpos($cate, "san-pham-tra-gop")!==false){
                    throw new Exception("Error Processing Request", 1);
                    $this->_helper->viewRenderer->setScriptAction("paymentproduct");
                    $this->view->Price=$this->TTDefaultProduct->getFilterPrice(8);
                    $this->view->Cate=$this->TTDefaultPayment->getCate();
                    $this->view->Series=$this->TTDefaultPayment->getSeries();
                    $sql="";
                    $this->view->name_page="Sản phẩm trả góp ";
                    $Myseries=$this->TTDefaultTemplate->FilterSeriesName(str_replace("san-pham-tra-gop-", "", $cate),$this->view->Series);
                    if(empty($Myseries)){
                       echo "installment not series ";exit;
                    }
                    $this->view->title_name=$Myseries['name'];
                    $this->view->myseries=true;
                    $this->view->is_series=$Myseries['id']; 
                    $sql=""; 
                    if($ss=$this->_getParam("s")){
                        $s=array();
                        $s[]=$ss;
                        $s[]=$this->filter->toAlias2($Myseries['name']);
                        $sql=$sql.$this->TTDefaultTemplate->FilterSeries($s,$this->view->Series) ;
                    }else{
                        $sql=" AND a.cid_series = ".$Myseries['id']." ";
                    }
                    if($c=$this->_getParam("c")){
                        $sql =$this->TTDefaultTemplate->FilterCate($c,$this->view->Cate);
                          $this->view->title_name .= $this->TTDefaultTemplate->FilterCateName($c,$this->view->Cate);
                    }
                    if($g=$this->_getParam("g")){
                        $sql= $sql.$this->TTDefaultProduct->FilterAttr($g);
                        $this->view->title_name .= $this->TTDefaultTemplate->FilterAttrName($g);
                        $this->view->title_name = str_replace("Trả góp", "", $this->view->title_name );
                    }
                    if($p=$this->_getParam("p")){
                        $sql=$sql.$this->TTDefaultTemplate->FilterPrice($p,$this->view->Price);
                        $this->view->title_name .= $this->TTDefaultTemplate->FilterPriceName($p,$this->view->Price);
                    }
                    $this->view->page=$this->_getParam("page",1);
                    $list=Zend_Paginator::factory($this->TTDefaultPayment->getAllProductOfInstallment($sql,$orderby));
                    $list->setCurrentPageNumber($this->view->page);
                    $list->setItemCountPerPage(12);
                    $list->setPageRange(5);
                    $this->view->list=$list;
                    $this->view->total_comment=$this->TTDefaultPayment->getTotalComment();
                    $this->view->list_article=$this->TTDefaultPayment->getReview();
                }
                $this->_helper->viewRenderer->setScriptAction("catechild");
                $All=$this->TTDefaultCate->getCateAndSeries($cate,$this->view->DTCATE_CHILD,$this->TTDefaultSeries,$this->view->filter);
                if(!empty($All)){
                    $TCate=$All['c'];
                    $this->view->myseries=true;
                    $this->view->cate=$TCate;
                    $this->view->name_page= $TCate['name'];
                    $this->view->id_cate=$TCate['id'];
                    $this->view->is_series=$All['s']['cid_series'];
                    $this->view->Series=$this->TTDefaultSeries->getChildCate($TCate['id']);
                    $this->view->title_name=$All['s']['name'];
                    $this->view->parent=$this->view->DTCATE_PARENT[$TCate['cid_parent']];
                    $this->view->Template=$this->TTDefaultTemplate->getFilter_Child($TCate['id']);
                    $this->view->Utility=$this->TTDefaultProduct->getFilterUtility($TCate['id']);
                    $this->view->Price=$this->TTDefaultProduct->getFilterPrice($TCate['id']);
                    if(empty($this->view->Page_Seo_Footer)){
                        $check_seo=$this->TTCate->checkSeo("/".$TCate['alias']);
                        if(!empty($check_seo)){
                            $this->view->Page_Seo_Footer=$check_seo;
                            $this->view->getArticleFooter=$this->TTDefaultProduct->getArticleFooter($TCate['alias']);
                        }
                    }
                    $sql=""; 
                    if($ss=$this->_getParam("s")){
                        $s=array();
                        $s[]=$ss;
                        $s[]=$this->filter->toAlias2($All['s']['name']);
                        $sql=$sql.$this->TTDefaultTemplate->FilterSeries($s,$this->view->Series) ;
                    }else{
                        $sql=" AND a.cid_series = ".$All['s']['idseries']." ";
                    }
                    if($g=$this->_getParam("g")){
                        $sql = $sql.$this->TTDefaultProduct->FilterAttr($g);
                        $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->FilterAttrName($g);
                    }
                    if($t=$this->_getParam("t")){
                        $check_t=$this->TTDefaultTemplate->FilterTemp($t,$this->view->Template);
                        if($check_t!=""){
                            $sql=$sql.$check_t;
                            $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->NameFilterTemp($t,$this->view->Template);
                        }
                        else
                        {
                            $error_301 = 'HTTP/1.1 301 Not Found';
                            $this->getResponse()->setRawHeader($error_301);
                            http_response_code(301);
                            $this->_redirect("/{$cate}");
                        }
                    }
                    if($u=$this->_getParam("u")){
                        $sql=$sql.$this->TTDefaultTemplate->FilterUtility($u,$this->view->Utility);
                        $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->NameFilterUtility($u,$this->view->Utility);
                    }
                    if($p=$this->_getParam("p")){
                        $sql=$sql.$this->TTDefaultTemplate->FilterPrice($p,$this->view->Price);
                        $this->view->title_name=$All['s']['name']." ".$this->TTDefaultTemplate->FilterPriceName($p,$this->view->Price);
                    }
                    $this->view->page=$this->_getParam("page",1);
                    $list=Zend_Paginator::factory($this->TTDefaultProduct->Product_Cate($TCate['id'],$sql) );
                    $list->setCurrentPageNumber($this->view->page);
                    $list->setItemCountPerPage(12);
                    $list->setPageRange(5);
                    $this->view->list=$list;
                    if(empty($this->view->headTitle()[0])){
                        $this->view->headTitle( $this->filter->subTitle($this->view->name_page." ".$this->view->title_name ) );
                    }
                }
            }
        }

        $result = array();
        if($TCate){
            if($TCate->cid_parent=='0'){
                $message                = 'Chỉ trả về danh mục cấp con';
                $result = array();
            }
            else
            {
                $result = array();
                $result['parent_name']  = $this->view->parent->name;
                $result['id_cate']      = $this->view->id_cate;
                $result['name_page']    = $this->view->name_page;
                //$result['series']         = $this->view->Series;
                foreach ($this->view->Series as $item)
                {
                    $_array_series                  = array();
                    $_array_series['id']            = $item['id'];
                    $_array_series['name']          = $item['name'];
                    $_array_series['cid_series']    = $item['cid_series'];
                    $_array_series['idseries']      = $item['idseries'];
                    $_array_series['cid_cate']      = $item['cid_cate'];
                    $_array_series['alias']         = $this->filter->toAlias2($item['name']);
                    $result['series'][]             = $_array_series;
                }
                if(!empty($this->view->Price))
                {
                    foreach ($this->view->Price as $item) {
                        $_array_price                   = array();
                        $_array_price['id']             = $item['id'];
                        $_array_price['name']           = $item['name'];
                        $_array_price['min_price']      = $item['min_price'];
                        $_array_price['max_price']      = $item['max_price'];
                        $_array_price['alias']          = $this->filter->toAlias2($item['name']);
                        $result['price'][]              = $_array_price;
                    }
                }
                
                //$result['template'] = $this->view->Template;
                foreach($this->view->Template as $value)
                {
                    $_array_template = array();
                    $_array_template['parent']['myid']          = $value['parent']['myid'];
                    $_array_template['parent']['val']           = $value['parent']['val'];
                    $_array_template['parent']['cid_element']   = $value['parent']['cid_element'];
                    $_array_template['parent']['id']            = $value['parent']['id'];
                    $_array_template['parent']['name']          = $value['parent']['name'];
                    $_array_template['parent']['alias']         = $this->filter->toAlias2($value['parent']['name']);
                    foreach($value['child'] as $element)
                    {
                        if(!empty($element['val']))
                        {
                            $_array_template_child                = array();
                            $_array_template_child['val']         = $element['val'];
                            $_array_template_child['cid_element'] = $element['cid_element'];
                            $_array_template_child['alias']       = $this->filter->toAlias2($element['val']);
                            $_array_template['child'][]           = $_array_template_child;
                        }
                    }
                    $result['template'][]    = $_array_template;
                }
                if(!empty($this->view->Utility))
                {
                    foreach($this->view->Utility as $item)
                    {
                        $_array_utility                     =  array();
                        $_array_utility['id']               =  $item['id'];
                        $_array_utility['name']             =  $item['name'];
                        $_array_utility['alias']            =  $this->filter->toAlias2($item['name']);
                        $_array_utility['description']      =  $item['description'];
                        $result['utility'][]                =  $_array_utility;
                    }
                }
                else
                {
                    $result['utility']                      =  array();
                }
                $message                            = (!empty($result))?'Trả về danh mục sản phẩm thành công':'Không có dữ liệu';
            }
        }
        $data['message']                    = $message;
        $data['errorcode']                  = (!empty($result))?0:1;
        $data['data']                       = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // CHI TIÊT SẢN PHÂM
    public function productdetailAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($cate=$this->_getParam("cate")){
            $TCate=$this->TTDefaultCate->getCurrentCateChild($cate,$this->view->DTCATE_CHILD,$this->view->filter);
            if($TCate)
            {
                $alias=$this->_getParam("product");
                $this->view->default_color=$color=$this->_getParam("color",null);
                $this->view->cate=$TCate;
                $this->view->parent=$this->view->DTCATE_PARENT[$TCate['cid_parent']];
                $this->view->product_detail=$product=$this->TTDefaultProduct->DetailNew($alias,$color);
                if($product)
                {               
                    setcookie("dxdmcholonnew[".$product['myid']."]",$product['myid'], time()+60*24*6004,"/",".".$_SERVER['SERVER_NAME']);
                    $this->view->daxem=$_COOKIE['dxdmcholonnew'];
                    if(isset($_COOKIE['dxdmcholonnew']))
                    {
                        if(count($this->view->daxem) > 15 ){
                            $dd=1;
                            foreach($this->view->daxem as $key=>$value){
                                $dd++;
                                if($dd > 15){
                                    unset($_COOKIE["dxdmcholonnew[$key]"]);
                                    setcookie("dxdmcholonnew[$key]",null,-1 ,"/",".".$_SERVER['SERVER_NAME']);
                                }
                            }
                        }
                    }
                    $this->view->color=$this->TTDefaultProduct->getColor($product['myid'],$product['cid_supplier']);
                    $this->view->Series=$this->TTDefaultSeries->fetchRow("id={$product['cid_series']}");
                    $this->view->Buy_Together =$this->TTDefaultProduct->Buy_Together($product['myid']);
                    $this->view->Element=$this->TTDefaultTemplate->Compare_Cate($TCate['id'],$product['myid']);
                    $this->view->Element_of_Product=$this->TTDefaultProduct->Detail_Element($product['myid']);
                    $this->view->Element_Hot=$this->TTDefaultTemplate->getHotElement($this->view->Element_of_Product);
                    $this->view->SPTT=$this->TTDefaultProduct->List_Proudct_Cate($TCate['id'],$product['discount'],$product['myid'],$product['cid_series']);
                    $this->view->Article=$this->TTDefaultProduct->getArticle($product['article']);
                    $this->view->Product_History=$this->TTDefaultProduct->List_Proudct_Array($this->view->daxem);       
                    $this->view->promotionTextSpecial=$this->TTDefaultPromotion->getPromotionTextSpecial($product['cid_res']);
                    $this->view->promotionText=$this->TTDefaultPromotion->getGiftTextDetail($product['cid_res']);
                    $this->view->Payment=$this->TTDefaultPayment->checkPayment($product['cid_cate']);
                    if(!empty($product['tag_title'])){
                        $this->view->headTitle($product['tag_title']);
                    }else{
                        $this->view->headTitle($product['name']);
                    }                   
                    if(!empty($product['tag_description'])){
                        $this->view->headMeta()->setName('description',$product['tag_description']);
                    }else{
                            $this->view->headMeta()->setName('description', $product['name']. "| Siêu thị Điện máy Nội Thất Chợ Lớn - điện tử, máy lạnh, gia dụng, di động | dienmaycholon.vn");
                    }
                    $this->view->canonical="https://dienmaycholon.vn/".$this->filter->toAlias2($TCate['name'])."/".$this->filter->toAlias2($product['name']);
                }
            }
        }
        $result = array();
        if($TCate)
        {
            if($product)
            {
                $result['parent_cate_name']                     = $this->view->parent['name'];
                $result['cate_name']                            = $this->view->cate['name'];
                $result['product']['myid']                      = $product['myid'];
                $result['product']['payment']                   = ($this->view->Payment)?'1':'';
                $result['product']['name']                      = $product['name'];
                $result['product']['alias']                     = $this->filter->toAlias2($product['name']);
                $result['product']['series']                    = $this->checkPhoto($this->filter->resizeSeries($product['cid_series'],71,15));
                $result['product']['cid_series']                = $product['cid_series'];
                $result['product']['brand']                     = $this->view->Series['name'];
                $result['product']['isprice']                   = $product['isprice'];
                $result['product']['is_icon']                   = $product['is_icon'];
                $result['product']['is_model']                  = $product['is_model'];
                $result['product']['is_price']                  = $product['is_price'];
                $result['product']['is_flash_sale']             = $product['is_flash_sale'];
                $result['product']['is_sale']                   = $product['is_sale'];
                $result['product']['new_description']           = $product['new_description'];
                $result['product']['cid_cate']                  = $product['cid_cate'];
                $result['product']['rate']                      = $product['rate'];
                $result['product']['note_em']                   = $this->stripTagsNew($this->view->product_detail['note_em']);
                $result['product']['of_type']                   = $product['of_type'];
                $result['product']['status']                    = $product['status'];
                $result['product']['stock_num']                 = $product['stock_num'];
                $result['product']['is_shopping']               = $product['is_shopping'];
                $result['product']['is_sample']                 = $product['is_sample'];
                $result['product']['note_price']                = $product['note_price'];
                $result['product']['code']                      = $product['code'];
                $result['product']['sap_code']                  = $product['sap_code'];
                $result['product']['is_vat']                    = $product['is_vat'];
                $result['product']['canonical']                 = $product['canonical'];
                $result['product']['article']                   = $product['article'];
                $result['product']['slideshow']                 = (array)json_decode($this->view->product_detail['slideshow']);
                $result['product']['noteunderprice']            = $this->stripTagsNew($this->view->product_detail['noteunderprice']);
                if($this->view->product_detail['stock_num'] > 0 || $this->view->product_detail['is_shopping'] =='1')
                {
                    $result['product']['status'] = ($this->view->product_detail['stock_num'] < 10)?( ($this->view->product_detail['is_sample'] =='1')?"1":"2" ):"3";
                }
                else
                {
                    $result['product']['status'] = ($this->product_detail['is_sample'] =='1' )? "4":"5";
                }
                $_count_slideshow = 0;
                for($_count_slideshow ; $_count_slideshow<count($result['product']['slideshow']);$_count_slideshow++) {
                    $result['product']['slideshow']["$_count_slideshow"]->photo         = $this->Url_dienmaycholon.'/public/picture/slideshow_product/'.$result['product']['slideshow']["$_count_slideshow"]->name;
                    ;
                    unset($result['product']['slideshow']["$_count_slideshow"]->name);
                }
                $result['product']['coupons']                   = $product['coupons'];
                $result['product']['discountcoupons']           = $product['discountcoupons'];
                $result['product']['discount']                  = $product['discount'];
                $result['product']['saleprice']                 = $product['saleprice'];
                $result['product']['cid_res']                   = $product['cid_res'];
                $result['product']['is_pre_order']              = $product['is_pre_order'];
                $result['product']['pre_order']                 = $product['pre_order'];
                $result['product']['is_promotion']              = $product['is_promotion'];
                $result['product']['tag_title']                 = $product['tag_title'];
                $result['product']['tag_keyword']               = $product['tag_keyword'];
                $result['product']['tag_description']           = $product['tag_description'];
                // $result['product']['content']                    = $this->changeHtml('<div style="font-size:20px"><h3> <img alt="Internet Tivi LED SONY 40 Inch KDL-40W650D VN3" class=" lazyloaded" data-src="/public/userupload/images/internet-tiv-sony-40w650d.png" src="/public/userupload/images/internet-tiv-sony-40w650d.png"> </h3><h3> Thiết kế hiện đại, cứng cáp, phù hợp với nhiều dạng không gian</h3> <p> Kích thước màn hình 40 inch vừa phải, đặt ở phòng khách, phòng làm việc hay phòng ngủ... đều phù hợp và thẩm mỹ.</p> <p> <img alt="Internet Tivi LED SONY 40 Inch KDL-40W650D VN3" class=" lazyloaded" data-src="/public/userupload/images/internet-tivi-sony-kdl-40w650d-1.jpg" src="/public/userupload/images/internet-tivi-sony-kdl-40w650d-1.jpg"></p> <h3> &nbsp;</h3> <h3> Độ phân giải Full HD cùng cường độ sáng tăng cường</h3> <p> <a href="https://dienmaycholon.vn/tivi-led/internet-tivi-led-sony-40-inch-kdl40w650d-vn3">Internet Tivi LED SONY 40 Inch KDL-40W650D</a> trải nghiệm chất lượng hình ảnh Full HD tuyệt vời bất kể nội dung gì. Bộ xử lý X-Reality PRO độc đáo của Sony phân tích, làm sạch và tinh chỉnh các hình ảnh để có chi tiết cực đẹp. Ngay cả nội dung phát ở độ phân giải thấp cũng được tăng cường bằng X-Reality PRO để mang đến cho bạn hình ảnh sống động như thật.</p> <p> <img alt="Internet-Tivi-LED-SONY-KDL-W650D" class=" lazyloaded" data-src="/public/userupload/images/Internet-Tivi-LED-SONY-KDL-W650D-1-1.png" src="/public/userupload/images/Internet-Tivi-LED-SONY-KDL-W650D-1-1.png"></p> <h3> Mỏng và thon gọn hơn với dòng W65/60D</h3> <p> Những đường viền đẹp mắt chạy dọc chân đế TV Full HD W65/60D, mang đến sự gọn nhẹ tuyệt vời từ chất liệu nhựa ở cả hai phần. Khi đặt ở phòng khách, những chiếc TV này nổi bật như một phiến đá tinh giản tựa vào tường. Trông như phần mở rộng của sàn nhà hoặc tủ kệ mà chúng được đặt lên &nbsp;</p> <p> <img alt="Internet-Tivi-LED-SONY-KDL-W650D" class=" lazyloaded" data-src="/public/userupload/images/Internet-Tivi-LED-SONY-KDL-W650D-1-2.png" src="/public/userupload/images/Internet-Tivi-LED-SONY-KDL-W650D-1-2.png"></p> <h3> Chia sẻ dữ liệu tiện lợi, dễ dàng</h3> <p> Nhằm đáp ứng nhu cầu truyền tải nội dung nhanh chóng lên tivi của người dùng, Sony cũng đã tích hợp trên Tivi của mình các cổng kết nối hiện đại:</p> <p> + Cổng USB: Giúp bạn có thể nghe nhạc, xem hình ảnh, video trực tiếp trên tivi bằng cách cắm USB vào tivi.</p> <p> + Cổng HDMI: Cho phép kết nối, truyền tải nội dung từ laptop, điện thoại, máy ảnh… lên màn hình tivi thông qua cáp kết nối HDMI.</p> <p> <img alt="" class="lazyload" data-src="/public/userupload/images/ket-noi-da-phuong-tien(1).png"></p><h3></h3></div>');
                $result['product']['content']                   = '<html lang="en"><head><title>ABC | XYZ</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><style>img { width: 100% !important;height: auto !important;}</style></head><body>'.$this->changeHtml($product['content']).'</body></html>';
                $result['product']['is_tranc']                  = $product['is_tranc'];
                $result['product']['is_old']                    = $product['is_old'];
                $result['product']['is_change']                 = $product['is_change'];
                $result['product']['is_double_price']           = $product['is_double_price'];
                $result['product']['is_red_day']                = $product['is_red_day'];
                $result['product']['is_million']                = $product['is_million'];
                $result['product']['description']               = $this->stripTagsNew($this->view->product_detail['description']);
                $result['product']['namecate']                  = $product['namecate'];
                $result['product']['id_detail']                 = $this->changeUrlDetail($product['namecate'],$product['name']);
                $result['product']['date_start']                = date("Y-m-d H:i:s");
                if(empty($product['idcolor']))
                {
                    $list_image = $this->view->filter->get_image_product_all_lcd($product['myid']);
                }
                else
                {
                    $list_image = $this->view->filter->get_image_color_all_lcd($product['idcolor']);
                }
                // $result['product']['main_photo'] = $this->Url_dienmaycholon.$this->filter->get_image_product_lcd_home($this->view->product_detail['myid']);
                foreach($list_image as $k=>$img)
                {
                    $_array_image  = array();
                    $_array_image['small']  = $this->checkPhoto(PRODUCT_URL."product".$this->view->product_detail['myid']."/small/".$img);
                    $_array_image['normal'] = $this->checkPhoto(PRODUCT_URL."product".$this->view->product_detail['myid']."/".$img);
                    $result['product']['photo'][] = $_array_image;
                    // $this->filter->stripTags()
                }
                if(empty($result['product']['photo']))
                {
                    $result['product']['photo'] = array();
                }
                foreach($this->view->Element_Hot as $e)
                {
                    $result['element_hot'][] = $e;
                }
                foreach($this->view->SPTT as $item)
                {
                    $item=$this->TTDefaultPromotion->getPriceParent($item);
                    $item['id_detail'] = $this->changeUrlDetail($item['namecate'],$item['name']);
                    $gift=$this->view->TTDefaultPromotion->getGiftText($item['cid_res']);
                    if(empty($gift['total']))
                    {
                        $item['gift'] = '';
                    }
                    else
                    {
                        $item['gift'] = $gift['total'];
                    }
                    $attribute=$this->TTDefaultTemplate->getSpecial($item['myid']);
                    foreach ($attribute as $attr)
                    {
                        $_result_attr = array();
                        $_result_attr['id']                 = $attr['id'];
                        $_result_attr['val']                = $attr['val'];
                        $item['element_special'][]  = $_result_attr;
                        $_count_att++;
                    }
                    if(empty($item['element_special']))
                    {
                        $item['element_special']    = array();
                    }
                    $item['photo']          = $this->checkPhoto($this->filter->get_image_product_lcd_home($item['myid'],'',150,150));
                    $item['description']    = $this->stripTagsNew($item['description']);
                    $result['similar_product'][] = $item;
                }
                foreach($this->view->Element_of_Product as $element)
                {
                    $result['element_product'][] = $element;
                }
                foreach($this->view->Product_History as $product)
                {
                    $_result_product = array();
                    $_result_product = $product;
                    $attribute=$this->view->TTDefaultTemplate->getSpecial($product['myid']);
                    foreach ($attribute as $attr)
                    {
                        $_result_product['attribute'][] = $attr;
                    }
                    $result['product_history'][] = $_result_product;
                }
                $url_color="";
                if(!empty($this->view->product_detail['idcolor'])){
                    $url_color="?color=".$this->view->default_color;
                }
                $TDetail=$this->TTDefaultPromotion->getDeal($this->view->product_detail['cid_res']);
                $result['detail_element']['cate_name']    = $this->view->cate['name'];
                $result['detail_element']['series_name']  = $this->view->Series['name'];
                $result['detail_element']['sap_code']     = $this->view->product_detail['sap_code'];
                if($review=$this->TTDefaultReview->getReview_Search($this->view->product_detail['myid']))
                {
                    $result['detail_element']['review']   = $review;
                    $result['detail_element']['rate']     = $this->view->product_detail['rate'];
                }
                $result['detail_element']['TDetail']      = $TDetail;
                if($TDetail)
                {
                    $result['detail_element']['TDetail'] = $TDetail;
                }
                else
                {
                    $result['detail_element']['TDetail']['date_end'] = '';
                    $result['detail_element']['TDetail']['of_type'] = '';
                }
                if($this->view->promotionTextSpecial)
                {
                    foreach ($this->view->promotionTextSpecial as $special)
                    {
                        $_array_special                         = array();
                        $_array_special['name']                 = $special['name'];
                        $result['detail_element']['special'][]  = $_array_special;
                    }
                }
                else
                {
                    $result['detail_element']['special'] = array();
                }
                if(!empty($this->view->promotionText['data']))
                {
                    $result['detail_element']['promotionText'] = $this->view->promotionText;
                    $_length_promotion = count($result['detail_element']['promotionText']['data']);
                    for ($i=0; $i < $_length_promotion; $i++) { 
                        $result['detail_element']['promotionText']['data'][$i]['name']  = $this->stripTagsNew($result['detail_element']['promotionText']['data'][$i]['name']);
                    }
                }
                else
                {
                    $result['detail_element']['promotionText'] = array(
                        'data'  => array(),
                        'total' => 0,
                    );
                }
                if(!empty($this->view->Buy_Together))
                {
                    $result['detail_element']['Buy_Together']  = $this->view->Buy_Together; 
                }
                foreach($this->view->color as $color)
                {
                    $_array_color                   = array();
                    $_array_color['id']             = $color['id'];
                    $_array_color['is_status_cate'] = $color['is_status_cate'];
                    $_array_color['status']         = $color['status'];
                    $_array_color['isprice']        = $color['isprice'];
                    $_array_color['code']           = $color['code'];
                    $_array_color['sap_code']       = $color['sap_code'];
                    $_array_color['price']          = $color['price'];
                    $_array_color['name']           = $color['color'];
                    $_array_color['cid_product']    = $color['cid_product'];
                    $_array_color['option']         = $color['option'];
                    $_array_color['cid_supplier']   = $color['cid_supplier'];
                    $_array_color['color_code']     = $color['color_code'];
                    if($color['option'] == 1)
                    {
                        $_array_color['photo'] = array();
                    }
                    else
                    {
                        // $_array_color['photo']       = $this->checkPhoto($this->filter->get_image_color_lcd($color['id']));
                        $list_image                 = $this->filter->get_image_color_all_lcd($color['id']);
                        foreach($list_image as $img)
                        {
                            $_array_each = array();
                            $_array_each['normal']  = $this->checkPhoto(COLOR_URL."color".$color['id']."/".$img);
                            $_array_each['small']   = $this->checkPhoto(COLOR_URL."color".$color['id']."/small/".$img);
                            $_array_color['photo'][] = $_array_each;
                        }
                        if(empty($_array_color['photo']))
                        {
                            $_array_color['photo'] = array();
                        }
                    }
                    $result['color'][]              = $_array_color;
                }
                if(empty($result['color']))
                {
                    $result['color'] = array();
                }
            }
        }
        //header('Content-Type: text/html; charset=UTF-8');
        $message             = (!empty($result))?'Trả về chi tiết sản phẩm thành công':'Không có dữ liệu';
        $data['message']     = $message;
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        
        // $data = html_entity_decode($data['data']['product']['noteunderprice']);
        // header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // listcommentproduct
    public function listcommentproductAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error = 0; 
        $result = array();  
        if($id=$this->_getParam("id")){
            $myorder=$this->_getParam("order",1);
            $this->view->myorder=$myorder;
            $sql_order="";
            if($myorder==1){
                $sql_order=" likes DESC ";
            }
            if($myorder==2){
                $sql_order=" created DESC ";
            }
            $list = Zend_Paginator::factory($this->TTReview->List_Review($id,$sql_order));
            $list->setItemCountPerPage(7);
            $list->setPageRange(3);
            $list->setCurrentPageNumber($this->_getParam("page", 1));
            $count_comment = count($this->TTReview->List_Review($id,$sql_order))/7;
            $this->view->review             =   $list;
            $this->view->product            =   $this->TTDefaultProduct->fetchRow("id=$id");
            $this->view->cate               =   $this->TTDefaultCate->fetchRow("id=".$this->view->product['cid_cate']);
            $result['page']                 =   (int)$this->_getParam("page", 1);
            $result['pageSize']             =   $list->count();
            $result['items']['product']['name']      =  $this->view->product['name'];
            $result['items']['product']['id']       =   $this->view->product['id'];
            $result['items']['product']['code']      =  $this->view->product['code'];
            $_count_reivew                  =   1;
            foreach($this->view->review as $review)
            {
                $_array_review                  = array();
                $TComment                       = $this->TTReview->getComment($review['id']);
                $_array_review['id']            = $review['id'];
                $_array_review['name']          = $review['name'];
                $_array_review['description']   = $this->stripTagsNew($review['description']);
                $_array_review['likes']         = $review['likes'];
                $_array_review['created']       = $review['created'];
                if(!empty($TComment))
                {
                    $_count_comment                  =   1;
                    foreach($TComment as $comment)
                    {
                        $_array_comment              = array();
                        $_array_comment['id']        = $comment['id'];
                        $_array_comment['name']      = $comment['name'];
                        $_array_comment['is_admin']  = $comment['is_admin'];
                        $_array_comment['comment']   = $this->stripTagsNew($comment['comment']);
                        $_array_comment['likes']     = (empty($comment['likes']))?'0':$comment['likes'];
                        $_array_comment['created']   = $comment['created'];
                        $_array_review['TComment'][] = $_array_comment;
                    }
                }
                else
                {
                    $_array_review['TComment']       = array();
                }
                $result['items']['review'][]         = $_array_review;
            }
            if(empty($result['items']['review']))
            {
                $result['items']['review'][] = array();
            }   
        }else{
            throw new Exception("Error Processing Request: not id", 1);
        }
        $data['message']     = (!empty($result))?'Lấy danh sách bình luận sản phẩm thành công':'Không có dữ liệu';
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // post comment sản phẩm
    public function postcommentAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error      =   array();
        $name       =   $this->filter->inject($this->_getParam("name"));
        $email      =   $this->filter->inject($this->_getParam("email"));
        $sex        =   $this->filter->inject($this->_getParam("sex"));
        $comment    =   $this->filter->inject($this->_getParam("comment"));
        $id         =   $this->filter->inject($this->_getParam("id"));
        $file       =   $this->filter->inject($this->_getParam("file"));
        $rate       =   $this->filter->inject($this->_getParam("rate"));
        $message    =   '';
        if(empty($name)){
            $error                  =   1;
            $message               .=   " Tên bị trống";
        }
        if(empty($comment)){
            $error                  =   1;
            $message               .=   " Nội dung bị trống";
        }
        if(empty($rate)){
            $error                  =   1;
            $message               .=   " Đánh gía bị trống";
        }
        $result = array();
        if(empty($error)){
            $new                    =   $this->TTReview->fetchNew();
            $new->cid_product       =   $result['cid_product']      =   $id;
            $new->email             =   $result['email']            =   $email;
            $new->is_admin          =   $result['is_admin']         =   '1';
            if($this->view->user){
                $new->cid_user=$this->view->user->id;
                if($this->view->user->role < 6){
                    $new->is_admin  =   $result['is_admin']         =   '3';
                }
            }
            $new->description       =   $result['description']      =   $comment;
            $new->created           =   $result['created']          =   date("Y-m-d H:i:s");
            $new->status            =   $result['status']           =   '1';
            $new->name              =   $result['name']             =   $name;
            $new->gender            =   $result['gender']           =   $sex;
            $new->title             =   $result['title']            =   '';
            $message                =   'Thêm bình luận sản phẩm thành công';
            $new->save();
            $this->TTDefaultProduct->updateVote($rate,$id);
            $message                =   'Đánh gía thành công';
            $error                  =   0;
            $this->TTReview->clean();
        }
        $data['message']            =   $message;
        $data['errorcode']          =   $error;
        $data['data']               =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // repliedcomment sản phẩm
    public function repliedcommentAction(){
        $TComment=new Application_Model_DbTable_Pro_Reviewcomment();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error=array();
        $this->TTReview->clean();
        $name=$this->filter->inject($this->_getParam("name"));
        $email=$this->filter->inject($this->_getParam("email"));
        $sex=$this->filter->inject($this->_getParam("sex"));
        $comment=$this->_getParam("comment");
        $id=$this->filter->inject($this->_getParam("id"));
        $message                     =  '';
        $result                      =  array();
        if(empty($name)){
            $error['name']           =  "NOT EMPTY";
            $message                .=  " Tên bị trống";
        }
        if(empty($comment)){
            $error['comment']        =  "NOT EMPTY";
            $message                .=  " Nội dung bị trống";
        }
        if(empty($error)){
            $new                     =  $TComment->fetchNew();
            $new->cid_review         =  $result['cid_review']     = $id;
            $new->email              =  $result['email']          = $email;
            $new->is_admin           =  $result['is_admin']       = '1';
            if($this->view->user){
                $new->cid_user=$this->view->user->id;
                if($this->view->user->role < 9){
                    $new->is_admin   =  '2';
                }
            }
            $new->comment            =  $result['comment']        = $comment;
            $new->created            =  $result['created']        = date("Y-m-d H:i:s");
            $new->name               =  $result['name']           = $name;
            $new->save();
            $message                 =  'Trả lời bình luận sản phẩm thành công';
            $error                   =  0;
        }
        $data['message']     = $message;
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    public function likecommentAction(){
        $TComment=new Application_Model_DbTable_Pro_Reviewcomment();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($id=$this->_getParam("id")){
            $this->TTReview->clean();
            $review=$TComment->fetchRow("id=$id");
            $review->likes=$review->likes+1;
            $result = 'like comment thành công';
            $review->save();
        }
        $data['message']     = 'like review review';
        $data['errorcode']   = 0;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //like review
    public function likereviewAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($id=$this->_getParam("id")){
            $review=$this->TTDefaultReview->fetchRow("id=$id");
            if($like=$this->_getParam("like")){
                $review->likes=$review->likes+1;
                $review->save();
                $result = 'like comment thành công';
                $error  = 0;
            }else{
                $review->unlikes=$review->unlikes+1;
                $result = 'unlike comment thành công';
                $review->save();
                $error  = 0;
            }
        }
        $data['message']     = 'like review';
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // DANH SACH TIN TƯC
    public function newspromotionAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->Top_Week         = $this->Model_News->art_list(4,1);
        $this->Promotion_Week   = $this->Model_News->art_list(6,1);
        $this->Promotion_Online = $this->Model_News->art_list(6,3);
        $this->Promotion_Bank   = $this->Model_News->art_list(6,2);
        $result                 = array();
        foreach($this->Top_Week as $news)
        {
            $_array_top_week                        = array();
            $_array_top_week['id']                  = $news['id'];
            $_array_top_week['name']                = $news['name'];
            $_array_top_week['countview']           = $news['countview'];
            $_array_top_week['alias']               = $news['alias'];
            $_array_top_week['photo']['big']        = $this->Url_dienmaycholon.'/public/picture/news/dienmay_'.$news['id'].'.png';
            $_array_top_week['photo']['small']      = $this->Url_dienmaycholon.'/public/picture/news/big/dienmay_'.$news['id'].'.png';
            $result['top_week'][]                   = $_array_top_week;
        }
        foreach($this->Promotion_Week as $news)
        {
            $_array_promotion_week                  = array();
            $_array_promotion_week['id']            = $news['id'];
            $_array_promotion_week['name']          = $news['name'];
            $_array_promotion_week['countview']     = $news['countview'];
            $_array_promotion_week['alias']         = $news['alias'];
            $_array_promotion_week['photo']         = $this->Url_dienmaycholon.'/public/picture/news/big/dienmay_'.$news['id'].'.png';
            $result['promotion_week'][]             = $_array_promotion_week;
        }
        foreach($this->Promotion_Online as $news)
        {
            $_array_promotion_online                = array();
            $_array_promotion_online['id']          = $news['id'];
            $_array_promotion_online['name']        = $news['name'];
            $_array_promotion_online['countview']   = $news['countview'];
            $_array_promotion_online['alias']       = $news['alias'];
            $_array_promotion_online['photo']           = $this->Url_dienmaycholon.'/public/picture/news/big/dienmay_'.$news['id'].'.png';
            $result['promotion_online'][]           = $_array_promotion_online;
        }
        foreach($this->Promotion_Bank as $news)
        {
            $_array_promotion_bank                  = array();
            $_array_promotion_bank['id']            = $news['id'];
            $_array_promotion_bank['name']          = $news['name'];
            $_array_promotion_bank['countview']     = $news['countview'];
            $_array_promotion_bank['alias']         = $news['alias'];
            $_array_promotion_bank['photo']         = $this->Url_dienmaycholon.'/public/picture/news/big/dienmay_'.$news['id'].'.png';
            $result['promotion_bank'][]             = $_array_promotion_bank;
        }
        $data['message']     = 'Lấy tin tức khuyến mãi thành công';
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // TÌM KIÊM
    public function searchAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($key=$this->_getParam("key")){
            $alias = $key;
            $this->view->key=$alias;
            $key=$this->filter->injectSql($alias);
            $key=$this->filter->toAlias2($key);
            $page=$this->view->page=$this->_getParam("page",1);
            $macth=$this->TTDefaultProduct->getSearch($key);
            if($macth){
                $list=Zend_Paginator::factory($macth);
                $list->setCurrentPageNumber($page);
                $list->setItemCountPerPage(8);
                $this->view->product=$list;
                $this->view->no_search=array();
            }else{
                $this->view->no_search=true;
            }
            $this->view->my_article=$this->TTNews->Expert_Article_Search($key,10,false);
            $TSeo=$this->TTDefaultProduct->getKeySearch($key);
            if(!empty($TSeo['seo_product'])){
                $seo_product=(array)json_decode($TSeo['seo_product']);
                if(empty($this->view->headTitle()[0])){
                    $this->view->headTitle($seo_product['seo_title']);
                    $this->view->headMeta()->setName('description',$seo_product['seo_description']);
                }
                $this->view->index_page=$seo_product['index_page'];
            }else{
                $this->view->headTitle(str_replace("-", " ", $key)." – Tìm kiếm ".str_replace("-", " ", $key)." tại Điện Máy Chợ Lớn");
                $this->view->headMeta()->setName('description',"Bạn đang tìm kiếm thông tin, sản phẩm ".str_replace("-", " ", $key)." tại Điện Máy ");
            }
        }
        $result                          = array();
        // $result['no_search']          = $this->view->no_search;
        $result['key']                   = $this->view->key;
        if($macth)
        {
            $result['page']                  = 1;
            $result['pageSize']              = $list->count();
            $result['count_item']            = $this->view->product->getTotalItemCount();
            $count = 0;
            if($this->_getParam("getproduct",0))
            {
                foreach($this->view->product as $product)
                {
                    $_result_product  =  array();
                    $product=(!empty($product['attrs']))? $product['attrs'] : $product;
                    $product=$this->TTDefaultProduct->getRealPrice($product);
                    $_result_product =  $product;
                    $_result_product['photo'] = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',220,220));
                    $_result_product['id_detail'] = $this->changeUrlDetail($product['namecate'],$product['name']);
                    $gift=$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                    if(empty($gift['total']))
                    {
                        $_result_product['gift'] = '';
                    }
                    else
                    {
                        $_result_product['gift'] = $gift['total'];
                    }
                    $attribute=$this->TTDefaultTemplate->getSpecial($product['myid']);
                    $_count_att = 1;
                    if(count($attribute)>0)
                    {
                        foreach ($attribute as $attr)
                        {
                            $_result_attr = array();
                            $_result_attr['id']                     = $attr['id'];
                            $_result_attr['val']                    = $attr['val'];
                            $_result_product['element_special'][]   = $_result_attr;
                        }
                    }
                    else
                    {
                        $_result_product['element_special']       = array();
                    }
                    $result['items']['list_product'][]  =  $_result_product;
                    $count++;
                }
            }
            if($this->_getParam("getarticle",0))
            {
                foreach($this->view->my_article as $article)
                {
                    $_result_article = array();
                    $article = (!empty($article['attrs'])) ? $article['attrs'] : $article ;
                    if(!empty($article['myid']))
                    {
                        $article['id']=$article['myid'];
                    }
                    $_result_article['name']                = $article['name'];
                    $_result_article['alias']               = $article['alias'];
                    $_result_article['id']                  = $article['id'];
                    $_result_article['description']         = $this->stripTagsNew($article['description']);
                    $_result_article['count_view']          = $article['count_view'];
                    $result['items']['list_article'][]      = $_result_article;
                }
            }
            $message                         = 'Lấy kết quả thành công';
            $error                           = 0;
        }
        else
        {
            $result['page']                  = $page;
            $result['pageSize']              = 0;
            $result['items']['list_product'] = array();
            $result['items']['list_article'] = array();
            $message                         = 'Không có dữ liệu';
            $error                           = 1;
        }
        $data['message']     = $message;
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // PHÂN TRANG TÌM KIÊM
    public function searchajaxAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $alias=$this->_getParam("key","");
        $this->view->key=$alias;    
        $key=$this->filter->injectSql($alias);
        $page                       = $this->_getParam("page",1);
        $macth                      = $this->TTDefaultProduct->getSearch($key);
        $result                     = array();
        $pageSize                   = 0;
        $error                      = 1;
        $message                    = 'Không tìm thấy kết quả tìm kiếm';
        if($macth)
        {
            $list                   = Zend_Paginator::factory($macth);
            $list->setCurrentPageNumber($page);
            $list->setItemCountPerPage(8);
            $this->view->product    = $list;
            $this->view->page       = $page;
            $pageSize               = $list->count();
            $error                  = 0;
            $message                = 'Tìm kiếm thành công';
        }
        $result['page']             = (int)$page;
        $result['pageSize']         = $pageSize;
        $result['key']              = $alias;
        if($macth)
        {
            foreach($list as $product)
            {
                $_result_product = array();
                $product=(!empty($product['attrs']))? $product['attrs'] : $product;
                $product=$this->TTDefaultProduct->getRealPrice($product);
                $_result_product =  $product;
                $_result_product['photo'] = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',220,220));
                $_result_product['id_detail']  = $this->changeUrlDetail($product['namecate'],$product['name']);
                $gift =$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                if(empty($gift['total']))
                {
                    $_result_product['gift'] = '';
                }
                else
                {
                    $_result_product['gift'] = $gift['total'];
                }
                $attribute=$this->TTDefaultTemplate->getSpecial($product['myid']);
                if(count($attribute)>0)
                {
                    foreach ($attribute as $attr)
                    {
                        $_result_attr                           = array();
                        $_result_attr['id']                     = $attr['id'];
                        $_result_attr['val']                    = $attr['val'];
                        $_result_product['element_special'][]   = $_result_attr;
                    }
                }
                else
                {
                    $_result_product['element_special']       = array();
                }
                $result['items'][]  = $_result_product;
            }
        }
        else
        {
            $result['product'] = array();
        }
        $data['message']     = $message;
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // LOGIN BANG TAI KHOẢN DCML //update listorder , chi tiet order
    public function logindmclAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        // $this->authentication();
        $error              = 'Không tìm thấy thông tin tài khoản';
        $result             = array();
        $data               = file_get_contents("php://input");
        $data               = json_decode($data);
        $uname              = $data->username;
        $paswd              = $data->password;
        $User               = $this->Server->getUserInfo($uname);
        if(!empty($User))
        {
            if(md5($paswd)==$User['password'])
            {
                $mydate                 =   explode("/", $User['birthday']);
                $result['id']           =   $User['id'];
                $result['username']     =   $username             = $User['username'];
                $result['email']        =   $email                = $User['email'];
                $result['fullname']     =   $User['fullname'];
                $result['phone']        =   $User['phone'];
                $result['myday']        =   $mydate[0];
                $result['mymonth']      =   $mydate[1];
                $result['myyear']       =   $mydate[2];
                $result['city']         =   $User['city'];
                $result['address']      =   $User['address'];
                $error                  =   0;
                $message                =   'Chúc mừng! Bạn đã đăng nhập thành công.';
                $gettoken               =   $this->gettokenLogin('Vietnam',$username,$User['password']);
                $_SESSION['id_user']    =   $User['id'];
                $result['gettoken']     =   $_SESSION['token_api_mobile']  = md5($gettoken);    
            }
            else
            {
                $message                =   'Password không chính xác';
                $error                  =   3;
            }   
        }
        else
        {
            $message                    =   'Tên tài khoản không chính xác';
            $error                      =   2;
        }
        $datajson['message']        = $message;
        $datajson['errorcode']      = $error;
        $datajson['data']           = $result;
        echo json_encode($datajson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); 
        // header('HTTP/1.1 401 Unauthorized');
    }

    // LOGIN BĂNG FACEBOOK
    public function loginfacebookAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->authentication();
        $name       =   $this->filter->injectSql($this->_getParam("name",""));
        $id         =   $this->filter->injectSql($this->_getParam("id"));
        $email      =   $this->filter->injectSql($this->_getParam("email"));
        $Check_User =   $this->TTUsers->fetchRow("facebook_id = '$id'");
        $message    =   "Chúc mừng! Bạn đã đăng nhập thành công.";
        $result     =   array();
        if(empty($Check_User))
        {
            $Check_User              = $this->TTUsers->fetchNew();
            $Check_User->username    = $result['username']          = md5( date("Y-m-d H:i:s"));
            $Check_User->password    = $result['password']          = md5($id);
            $Check_User->fullname    = $result['fullname']          = $name;
            $Check_User->email       = $result['email']             = $email;
            $Check_User->city        = $result['city']              = 30;
            $Check_User->status      = $result['status']            = 1;
            $Check_User->address     = $result['address']           = "Lô G- Tản đà";
            $Check_User->is_facebook = $result['is_facebook']       = '1';
            $Check_User->facebook_id = $result['facebook_id']       = $id;
            $Check_User->date_login  = $result['date_login']        = date("Y-m-d");
            $Check_User->save();
            $message                 = "Chúc mừng! Bạn đã tạo tài khoản thành công.";
        }
        else
        {
            $result['username']      = $Check_User->username;
            $result['password']      = $Check_User->password;
            $result['fullname']      = $Check_User->fullname;
            $result['email']         = $Check_User->email;
            $result['city']          = $Check_User->city;
            $result['status']        = $Check_User->status;
            $result['address']       = $Check_User->address;
            $result['is_facebook']   = $Check_User->is_facebook;
            $result['facebook_id']   = $Check_User->facebook_id;
            $result['date_login']    = $Check_User->date_login;
        }
        $datajson['message']         = $message;
        $datajson['errorcode']       = 0;
        $datajson['data']            = $result;
        echo json_encode($datajson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
        header('HTTP/1.1 401 Unauthorized');
    }

    // LÂÝ THÔNG TIN CỦA USER
    public function infouserAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        // $this->authentication();
        if(!empty($_SESSION['id_user']))
        {
            $id=$_SESSION['id_user'];
            $error=1;
            $result = array();
            $User                       =   $this->TTUsers->getID($id);
            $message                    =   'Không có dữ liệu';
            if(!empty($User))
            {
                $mydate                 =   explode("/", $User['birthday']);
                $result['username']     =   $User['username'];
                $result['email']        =   $User['email'];
                $result['fullname']     =   $User['fullname'];
                $result['phone']        =   $User['phone'];
                $result['myday']        =   $mydate[0];
                $result['mymonth']      =   $mydate[1];
                $result['myyear']       =   $mydate[2];
                $result['city']         =   $User['city'];
                $result['address']      =   $User['address'];
                $result['date_cre']     =   $User['date_cre'];
                $error                  =   0;
                $message                =   'Thông tin của user';
                $this->Or_Order         =   new Application_Model_DbTable_Or_Order();
                $select                 =   $this->Or_Order->select();
                $select->where('id_cus = ?', $id);  
                $select->order("date_bill DESC");
                $list                   =   Zend_Paginator::factory($select);
                $totalorders            =   0;
                $totalproducts          =   0;
                $totalprice             =   0;
                foreach($list as $data)
                {
                    ++$totalorders;
                    $id                 =   $data['id'];
                    $totalprice         =   $totalprice + $data['total_or'];
                    $TDetail            =   new Application_Model_DbTable_Or_Detail();
                    $sql_detail         =   $TDetail->fetchAll("cid_order = $id");
                    foreach ($sql_detail as $item) {
                        $totalproducts  =   $totalproducts + $item['amount'];
                    }
                }
                $result['totalorders']      =   $totalorders;
                $result['totalproducts']    =   $totalproducts;
                $result['totalprice']       =   $totalprice;
            }
        }
        else
        {
            $message                    = 'Chưa đăng nhập';
            $error                      = 1;
            $result                     = array();
        }
        $datajson['message']            =   $message;
        $datajson['errorcode']          =   $error;
        $datajson['data']               =   $result;
        echo json_encode($datajson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); 
    }

    // LÂÝ THÔNG TIN CỦA USER
    public function updateuserAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $id=$this->filter->injectSql($this->_getParam("id"));
        $error=array();
        $resultjson = 'Cập nhật tài khoản không thành công';
        $User=$this->TTUsers->getInfo($id);
        if(!empty($User))
        {
            $username=$this->filter->injectSql($this->_getParam("username"));
            $password=$this->filter->injectSql($this->_getParam("password"));
            $reset=$this->filter->injectSql($this->_getParam("reset"));
            $email=$this->filter->injectSql($this->_getParam("email"));
            $fullname=$this->filter->injectSql($this->_getParam("fullname"));
            $phone=$this->filter->injectSql($this->_getParam("phone"));
            $myday=$this->filter->injectSql($this->_getParam("myday"));
            $mymonth=$this->filter->injectSql($this->_getParam("mymonth"));
            $myyear=$this->filter->injectSql($this->_getParam("myyear"));
            $city=$this->filter->injectSql($this->_getParam("city"));
            $address=$this->filter->injectSql($this->_getParam("address"));
            if(empty($username)){
                $error["username"]=" Vui lòng nhập tên đăng nhập của bạn";
            }else{
                if(strlen($username) < 7 )
                {
                    $error['username']=" Vui lòng nhập nhiều hơn 6 ký tự ";
                }
                else
                {
                    $check_user=$this->TTUsers->fetchRow("username LIKE '{$username}' AND id != ".$id);
                    if(!empty($check_user))
                    {
                        $error['username']=" Tên đăng nhập đã tồn tại ";
                    }
                }
            }
            if(!empty($password))
            {
                if(strlen($password) < 7)
                {
                    $error['password']=" Vui lòng nhập mật khẩu trên 6 ký tự ";
                }
                if(empty($reset))
                {
                    $error["reset"]=" Vui lòng nhập lại mật khẩu của bạn "; 
                }
                else
                {
                    if(strlen($reset) < 7)
                    {
                        $error['reset']=" Vui lòng nhập mật khẩu trên 6 ký tự ";
                    }
                }
                if(!empty($password) && !empty($reset))
                {
                    if($password!=$reset)
                    {
                        $error['reset']=" Vui lòng nhập lại mật khẩu chính xác ";
                    }
                }
            }

            if(!$this->filter->checkEmailType($email))
            {
                $error['email']=" Vui lòng nhập đúng định dạng email ";
            }
            else
            {
                $check_email=$this->TTUsers->fetchRow("email LIKE '{$email}'  AND id != ".$id);
                if(!empty($check_email))
                {
                    $error['email']=" Địa chỉ E-mail đã tồn tại ";
                }
            }
            if(empty($fullname))
            {
                $error['fullname']=" Vui lòng nhập đúng họ và tên của bạn ";
            }
            if(empty($phone))
            {
                $error['phone']="Vui lòng nhập số điện thoại của bạn ";
            }
            if(empty($error))
            {
                $updated=$this->TTUsers->fetchRow("id=".$id);
                $updated->username=$username;
                if(!empty($password))
                {
                    $updated->password=md5($password);
                }
                $updated->email=$email;
                $updated->phone=$phone;
                $updated->fullname=$fullname;
                if(empty($city))
                {
                    $updated->city=$city;
                }
                $updated->address=$address;
                $updated->birthday=$myday."/".$mymonth."/".$myyear;
                $updated->save();
                $this->view->success=true;
                $db = Zend_Db_Table::getDefaultAdapter ();
                $auth = Zend_Auth::getInstance ();
                $authAdapter = new Zend_Auth_Adapter_DbTable ( $db );
                $authAdapter->setTableName ( 'tm_customer' )->setIdentityColumn ( 'username' )->setCredentialColumn ( 'password' );
                $authAdapter->setIdentity ( $updated->username );
                $authAdapter->setCredential ( $updated->password );
                $select = $authAdapter->getDbSelect ();
                $select->where ( 'status = 1' );
                $result = $auth->authenticate ( $authAdapter );
                if ($result->isValid ()) {
                    $data = $authAdapter->getResultRowObject ( null, array (
                            'password' 
                    ) );
                    $auth->getStorage ()->write ( $data );    
                    $this->user=$this->view->user = $data;
                }
                $resultjson = 'Cập nhật tài khoản thành công';
                $error      = 0;
            }
        }
        $datajson['message']     = 'update user';
        $datajson['errorcode']   = $error;
        $datajson['data']        = $resultjson;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); 
    }

    // TẠO TÀI KHOẢN KHÁCH HÀNG
    public function registerAction(){
        //{"username":"nhanvien01","password":"1234567","email":"nguyenleduykhang29111994@gmail.com","fullname":"nhân viên 01","phone":"0917171049","myday":"29","mymonth":"11","myyear":"1994","city":"30","address":"1 Lương Ngọc Quyến , Phường 5 , Quận Bình Thạnh , TP. Hồ Chí Minh"}
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start          =   microtime(true);
        $error          =   0;
        $resultjson     =   'Tạo tài khoản không thành công';
        $data           =   file_get_contents("php://input");
        $data           =   json_decode($data);
        $username       =   $data->username;
        $password       =   $data->password;
        $email          =   $data->email;
        $fullname       =   $data->fullname;
        $phone          =   $data->phone;
        $myday          =   $data->myday;
        $mymonth        =   $data->mymonth;
        $myyear         =   $data->myyear;
        $city           =   $this->filter->injectSql($data->city);
        $address        =   $this->filter->injectSql($data->address);
        $resultjson     =   array();
        if(empty($username))
        {
            $error      =   1;
            $message    =   "Chưa nhập username";
        }
        else
        {
            if(strlen($username)<7)
            {
                $error      =   1;
                $message    =   "Vui lòng nhập nhiều hơn 6 ký tự";
            }
            else
            {
                $check_user     =   $this->TTUsers->fetchRow("username = '{$username}'");
                if(!empty($check_user)){
                    $error      =   1;
                    $message    =   "Tên đăng nhập đã tồn tại";
                }
            }
        }
        if(empty($password))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập mật khẩu của bạn ";
        }
        else
        {
            if(strlen($password) < 7)
            {
                $error          =   1;
                $message        =   "Vui lòng nhập mật khẩu trên 6 ký tự ";
            }
        }
        if(!$this->filter->checkEmailType($email))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập đúng định dạng email ";
        }
        else
        {
            $check_email=$this->TTUsers->fetchRow("email = '{$email}'");
            if(!empty($check_email))
            {
                $error              =   1;
                $message            =   "Địa chỉ E-mail đã tồn tại ";
            }
        }
        if(empty($fullname))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập đúng họ và tên của bạn ";
        }
        if(empty($phone))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập số điện thoại của bạn ";
        }
        if(empty($city))
        {
            $error              =   1;
            $message            =   " Vui lòng nhập địa chỉ của bạn ";
        }
        if($error == 0)
        {
            $new                    =   $this->TTUsers->fetchNew();
            $new->username          =   $resultjson['username']         =   $username;
            $new->password          =   $resultjson['password']         =   md5($password);
            $new->email             =   $resultjson['email']            =   $email;
            $new->phone             =   $resultjson['phone']            =   $phone;
            $new->fullname          =   $resultjson['fullname']         =   $fullname;
            $new->city              =   $resultjson['city']             =   $city;
            $new->status            =   1;
            $new->date_login        =   date("Y-m-d");
            $new->date_cre          =   date("d-m-Y");
            $new->address           =   $resultjson['address']          =   $address;
            $new->birthday          =   $resultjson['birthday']         =   $myday."/".$mymonth."/".$myyear;
            $message                =   "Tạo tài khoản thành công";
            $new->save();
        }
        $datajson['message']     = $message;
        $datajson['errorcode']   = $error;
        $datajson['data']        = $resultjson;
        echo json_encode($datajson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); 
    }

    // TÌM KIÊM THEO TINH NANG VA PHAN TRANG DANH MUC SAN PHAM
    public function searchfeatureAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        if($alias=$this->_getParam("cate",8)){
            $TCate=$this->TTDefaultCate->fetchRow("alias='$alias'");
            if(!empty($TCate))
            {
                $message = 'Lấy kết quả danh mục sản phẩm thành công';
                $error   = 0;
                $this->view->title_name=$this->_getParam("title","");
                $this->view->cate=$TCate;
                $this->view->parent=$this->view->DTCATE_PARENT[$TCate['cid_parent']];
                $Template=$this->TTDefaultTemplate->getFilter_Child($TCate['id']);
                $Utility=$this->TTDefaultProduct->getFilterUtility($TCate['id']);
                $Series=$this->TTDefaultSeries->getChildCate($TCate['id']);
                $Price=$this->TTDefaultProduct->getFilterPrice($TCate['id']);
                $sql="";
                $orderby=" ORDER BY a.id DESC ";
                $data = file_get_contents("php://input");
                // $data = '{"s":["sony","samsung"],"p":[],"u":[],"t":[],"g":[]}';
                $data = json_decode($data);
                if(!empty($data->g)){
                    $g = array();
                    foreach ($data->g as $item) {
                        $g[]  = $item;
                    }
                    $sql    = $this->TTDefaultProduct->FilterAttr($g);
                }
                if(!empty($data->t)){
                    $t = array();
                    foreach ($data->t as $item) {
                        $t[]  = $item;
                    }
                    $sql    = $sql.$this->TTDefaultTemplate->FilterTemp($t,$Template);
                }
                if(!empty($data->u)){
                    $u = array();
                    foreach ($data->u as $item) {
                        $u[]  = $item;
                    }
                    $sql    = $sql.$this->TTDefaultTemplate->FilterUtility($u,$Utility);
                }
                if(!empty($data->s)){
                    $s = array();
                    foreach ($data->s as $item) {
                        $s[]  = $item;
                    }
                    $sql    = $sql.$this->TTDefaultTemplate->FilterSeries($s,$Series);
                }
                if(!empty($data->p)){
                    $p = array();
                    foreach ($data->p as $item) {
                        $p[]  = $item;
                    }
                    $sql    = $sql.$this->TTDefaultTemplate->FilterPrice($p,$Price);
                }   
                if($order=$this->_getParam("order")){
                    if($order=='1'){
                        $orderby = " ORDER BY b.discount ASC";
                    }
                    if($order=='2'){
                        $orderby = " ORDER BY b.discount DESC";
                    }
                    $this->view->order=$order;
                }else{
                    $orderby=" ORDER BY b.is_promotion DESC,b.stock_num DESC";
                }
                $this->view->page=$this->_getParam("page",1);
                $list=Zend_Paginator::factory($this->TTDefaultProduct->Product_Cate($TCate['id'],$sql,$orderby) );
                $list->setCurrentPageNumber($this->view->page);
                $list->setItemCountPerPage(12);
                $list->setPageRange(5);
                $this->list             = $list;
                $result['page']         = (int)$this->view->page;
                $result['pageSize']     = $list->count(); 
                if($this->list->getPages()->pageCount > 0)
                {
                    $_count_product = 1;
                    foreach($this->list as $product)
                    {
                        $_result_product                 = array();
                        $product                         = $this->TTDefaultPromotion->getPrice($product);
                        $_result_product                 = $product;
                        $_result_product['series']       = $this->checkPhoto('/public/picture/series/dienmay_' .$product['cid_series'].'.png');
                        $_result_product['id_detail']    = $this->changeUrlDetail($product['namecate'],$product['name']);
                        $gift                            = $this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                        if(empty($gift['total']))
                        {
                            $_result_product['gift']     = '';
                        }
                        else
                        {
                            $_result_product['gift']     = $gift['total'];
                        }
                        $attribute                       = $this->TTDefaultTemplate->getSpecial($product['myid']);
                        $_count_att                      = 1;
                        foreach ($attribute as $attr)
                        {
                            $_result_attr = array();
                            $_result_attr['id']  = $attr['id'];
                            $_result_attr['val'] = $attr['val'];
                            $_result_product['element_special'][] = $_result_attr;
                        }
                        if(empty($_result_product['element_special']))
                        {
                            $_result_product['element_special'] = array();
                        }
                        $_result_product['photo'] = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',220,220));
                        $result['items'][] = $_result_product;
                    }
                }
            }
            else
            {
                $message = 'Không có dữ liệu';
                $error   = 1;
            }
        }
        $datajson['message']     = $message;
        $datajson['errorcode']   = $error;
        $datajson['data']        = $result;
        echo json_encode($datajson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // KINH NGHIÊM MUA SAM
    public function expeindexAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $data = array();
        $result = array();
        $this->view->style_page  =  "experience/index";
        $this->view->expe_hot    =  $this->Model_News->Expe_hot();
        $this->view->expe_only   =  $this->Model_News->Expe_Only();
        $this->view->list_cate   =  $this->Model_News->Expe_All_Cate(0);
        $this->view->youtube     =  $this->Model_News->getYoutube(8);
        $result['expe_only']     =  $this->view->expe_only;
        $_count_hot = 0;
        foreach($this->view->expe_hot as $article)
        {
            $result['expe_hot'][$_count_hot]   = $article;
            $_count_hot++;
        }
        $_count_cate = 0;
        foreach($this->view->list_cate as $cate)
        {
            $result['list_cate'][$_count_cate] = $cate;
            $TArticle=$this->view->Model_News->Expe_Article_Cate($cate['id']);
            $_count_article = 1;
            foreach($TArticle as $article)
            {
                $result['list_cate'][$_count_cate]['article'][$_count_article] = $article;
                $_count_article++;
            }
            $_count_cate++;
        }
        $result['youtube']   =  $this->view->youtube;
        $_count_youtube++;
        foreach($this->youtube['entries'] as $youtube)
        {
            $result['youtube']['entries'][$_count_youtube] = $youtube;
            $_count_youtube++;
        }
        $data['message']     = 'kinh nghiệm mua sắm';
        $data['errorcode']   = 0;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // chi tiết bài viết kinh nghiệm mua sắm
    public function expecateAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $data = array();
        $result = array();
        if($alias=$this->_getParam("alias"))
        {
            $this->view->List_Parent=$this->Model_News->Expe_All_Cate(0);
            //PARENT 
           if($result=$this->Model_News->Expe_Check_Parent($alias))
           {
                $this->view->view_canonical=true;
                $this->view->style_page="experience/parent";
                $this->_helper->viewRenderer->setScriptAction("expeparent");
                $this->view->Parent=$result;
                $list=Zend_Paginator::factory($this->Model_News->Expert_Article_Parent($result['id']));
                $list->setCurrentPageNumber($this->_getParam("page",1));
                $list->setItemCountPerPage(10);
                $list->setPageRange(5);
                
                $this->view->list=$list;
                $seo=(array)json_decode($result['seo']);
                $this->view->list_article=$this->Model_News->Expe_Article_Cate($result['id'],7);
                if(empty($this->view->headTitle()[0])){
                    $this->view->headTitle($result['name']);
                }
                $result_api                         =   array();
                $result_api['page']                 =   (int)$this->_getParam("page", 1);
                $result_api['pageSize']             =   $list->count();
                $result_api['name'] = $this->view->Parent['name'];
                // $_number_cate = 0;
                // foreach($this->view->List_Parent as $cate)
                // {
                //  $result_api['cate'][$_number_cate]['id']   = $cate['id'];
                //  $result_api['cate'][$_number_cate]['name'] = $cate['name'];
                //  $child=$this->view->Model_News->Expe_All_Cate($cate['id']);
                //  $_number_cate_child = 0;
                //  foreach($child as $c)
                //  {
                //      $result_api['cate'][$_number_cate]['child'][$_number_cate_child]['alias'] = $c['alias'];
                //      $result_api['cate'][$_number_cate]['child'][$_number_cate_child]['name']  = $c['name'];
                //      ++$_number_cate_child;
                //  }
                //  ++$_number_cate;
                // }
                $_number_article = 0;
                foreach($this->view->list as $article)
                {
                    $result_api['items'][$_number_article]['alias']     = $article['alias'];
                    $result_api['items'][$_number_article]['name']      = $article['name'];
                    $result_api['items'][$_number_article]['photo']     = $this->checkPhoto(PICTURE_URL.'experience/article_'.$article['id'].'.jpg');
                    $result_api['items'][$_number_article]['comment']   = $this->view->Model_News->Expe_Cout_Comment($article['id']);
                    $result_api['items'][$_number_article]['countview']     = $article['count_view'];
                    $result_api['items'][$_number_article]['description'] = $article['description'];
                    ++$_number_article;
                }
                //orther
      //           if(!empty($this->view->product))
      //           {
      //            $_number_product = 0;
      //            foreach($this->view->product as $product)
      //            {
      //                $result_api['orther']['product'][$_number_product]['namecate'] = $product['namecate'];
      //                $result_api['orther']['product'][$_number_product]['photo']     = $this->view->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',170,170));
                        // $result_api['orther']['product'][$_number_product]['is_model'] = $product['is_model'];
      //                $result_api['orther']['product'][$_number_product]['is_flash_sale'] = $product['is_flash_sale'];
      //                $result_api['orther']['product'][$_number_product]['is_icon']  = $product['is_icon'];
      //                $result_api['orther']['product'][$_number_product]['is_sale']   = $product['is_sale'];
      //                $result_api['orther']['product'][$_number_product]['is_price'] = $product['is_price'];
      //                $result_api['orther']['product'][$_number_product]['alias']     = $this->url(array("cate"=>$this->filter->toAlias2($product['namecate']),"product"=>$this->filter->toAlias2($product['name']) ) ,"detail" );
      //                $result_api['orther']['product'][$_number_product]['isprice']  = $product['isprice'];
      //                $result_api['orther']['product'][$_number_product]['discount'] = $this->filter->toPrice($product['discount']);
      //                $result_api['orther']['product'][$_number_product]['coupons']  = $product['coupons'];
      //                $result_api['orther']['product'][$_number_product]['discountcoupons']  = $this->filter->toPrice($product['discountcoupons']);
      //                $result_api['orther']['product'][$_number_product]['Percent']  = $this->filter->Percent($product['discount'],$product['saleprice']);
      //                $result_api['orther']['product'][$_number_product]['saleprice']= $this->filter->toPrice($product['saleprice']);
      //                $result_api['orther']['product'][$_number_product]['is_promotion']  = $product['is_promotion'];
      //                $result_api['orther']['product'][$_number_product]['new_description']  = $this->filter->NewDescription($product['new_description']);
      //                ++$_number_product;
      //            }
      //           }
      //           else
      //           {
      //            $_number_orther_article = 0;
      //            foreach($this->view->list_article  as $article)
      //            {
      //                $result_api['orther']['article'][$_number_orther_article]['alias']   = $article['alias'];
      //                $result_api['orther']['article'][$_number_orther_article]['name']    = $article['name'];
      //                $result_api['orther']['article'][$_number_orther_article]['description']  = $article['description'];
      //                $result_api['orther']['article'][$_number_orther_article]['photo']   = $this->checkPhoto(PICTURE_URL.'experience/article_'.$article['id'].'.jpg');
      //                $result_api['orther']['article'][$_number_orther_article]['comment'] = $this->view->Model_News->Expe_Cout_Comment($article['id']);
      //                $result_api['orther']['article'][$_number_orther_article]['count_view'] = $article['count_view'];
      //                ++$_number_orther_article;
      //            }
      //           }
                $data['message']     = 'chi tiết kinh nghiệm mua sắm';
                $data['errorcode']   = 0;
                $data['data']        = $result_api;
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
                return;
                // return true;
            }

            //CHILD 
            if($result=$this->Model_News->Expe_Check_Child($alias))
            {
                $this->view->view_canonical=true;
                $this->view->style_page="experience/child";
                $this->_helper->viewRenderer->setScriptAction("expechild");
                $this->view->Child=$result["C"];
                $this->view->Parent=$result["P"];
                $list=Zend_Paginator::factory($this->Model_News->Expert_Article_Child($result["C"]['id']));
                $list->setCurrentPageNumber($this->_getParam("page",1));
                $list->setItemCountPerPage(10);
                $list->setPageRange(5);
                $this->view->list=$list;
                $seo=(array)json_decode($result["C"]['seo']);
                $this->view->list_article=$this->Model_News->Expe_Article_Child($result["C"]['id'],5);
                if(empty($this->view->headTitle()[0])){
                    $this->view->headTitle($result["C"]['name']);
                }
                $result_api = array();
                $result_api['page']                 =   (int)$this->_getParam("page", 1);
                $result_api['pageSize']             =   $list->count();
                $result_api['Parent']['name'] = $this->view->Parent['name'];
                $result_api['Child']['name']  = $this->view->Child['name'];
                // $_number_cate = 0;
     //            foreach($this->view->List_Parent as $cate)
     //            {
     //             $result_api['cate'][$_number_cate]['id']      = $cate['id'];
     //             $result_api['cate'][$_number_cate]['alias']   = $cate['alias'];
     //             $result_api['cate'][$_number_cate]['name']    = $cate['name'];
     //             $child=$this->view->Model_News->Expe_All_Cate($cate['id']);
     //             $_number_cate_child = 0;
                    // foreach($child as $c)
                    // {
                    //  $result_api['cate'][$_number_cate]['child'][$_number_cate_child]['alias'] = $c['alias'];
     //                 $result_api['cate'][$_number_cate]['child'][$_number_cate_child]['name']  = $c['name'];
     //                 ++$_number_cate_child;
                    // }
     //             ++$_number_cate;
     //            }
                $_number_article = 0;
                foreach($this->view->list as $article)
                {
                    $result_api['items'][$_number_article]['alias']     = $article['alias'];
                    $result_api['items'][$_number_article]['name']      = $article['name'];
                    $result_api['items'][$_number_article]['photo']     = $this->checkPhoto(PICTURE_URL.'experience/article_'.$article['id'].'.jpg');
                    $result_api['items'][$_number_article]['comment']   = $this->view->Model_News->Expe_Cout_Comment($article['id']);
                    $result_api['items'][$_number_article]['countview']     = $article['count_view'];
                    $result_api['items'][$_number_article]['description'] = $article['description'];
                    ++$_number_article;
                }
                //orther
      //           if(!empty($this->view->product))
      //           {
      //            $_number_product = 0;
      //            foreach($this->view->product as $product)
      //            {
      //                $result_api['orther']['product'][$_number_product]['namecate'] = $product['namecate'];
      //                $result_api['orther']['product'][$_number_product]['photo']     = $this->view->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',170,170));
                        // $result_api['orther']['product'][$_number_product]['is_model'] = $product['is_model'];
      //                $result_api['orther']['product'][$_number_product]['is_flash_sale'] = $product['is_flash_sale'];
      //                $result_api['orther']['product'][$_number_product]['is_icon']  = $product['is_icon'];
      //                $result_api['orther']['product'][$_number_product]['is_sale']   = $product['is_sale'];
      //                $result_api['orther']['product'][$_number_product]['is_price'] = $product['is_price'];
      //                $result_api['orther']['product'][$_number_product]['alias']     = $this->url(array("cate"=>$this->filter->toAlias2($product['namecate']),"product"=>$this->filter->toAlias2($product['name']) ) ,"detail" );
      //                $result_api['orther']['product'][$_number_product]['isprice']  = $product['isprice'];
      //                $result_api['orther']['product'][$_number_product]['discount'] = $this->filter->toPrice($product['discount']);
      //                $result_api['orther']['product'][$_number_product]['coupons']  = $product['coupons'];
      //                $result_api['orther']['product'][$_number_product]['discountcoupons']  = $this->filter->toPrice($product['discountcoupons']);
      //                $result_api['orther']['product'][$_number_product]['Percent']  = $this->filter->Percent($product['discount'],$product['saleprice']);
      //                $result_api['orther']['product'][$_number_product]['saleprice']= $this->filter->toPrice($product['saleprice']);
      //                $result_api['orther']['product'][$_number_product]['is_promotion']  = $product['is_promotion'];
      //                $result_api['orther']['product'][$_number_product]['new_description']  = $this->filter->NewDescription($product['new_description']);
      //                ++$_number_product;
      //            }
      //           }
      //           else
      //           {
      //            $_number_orther_article = 0;
      //            foreach($this->view->list_article  as $article)
      //            {
      //                $result_api['orther']['article'][$_number_orther_article]['alias']   = $article['alias'];
      //                $result_api['orther']['article'][$_number_orther_article]['name']    = $article['name'];
      //                $result_api['orther']['article'][$_number_orther_article]['description']  = $article['description'];
      //                $result_api['orther']['article'][$_number_orther_article]['photo']   = $this->checkPhoto(PICTURE_URL.'experience/article_'.$article['id'].'.jpg');
      //                $result_api['orther']['article'][$_number_orther_article]['comment'] = $this->view->Model_News->Expe_Cout_Comment($article['id']);
      //                $result_api['orther']['article'][$_number_orther_article]['count_view'] = $article['count_view'];
      //                ++$_number_orther_article;
      //            }
      //           }
                $data['message']     = 'chi tiết kinh nghiệm mua sắm';
                $data['errorcode']   = 0;
                $data['data']        = $result_api;
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
                return;
                // return true;
            }
            //END CHILD 
            if(strpos($alias,"tag-")!==false)
            {
                $this->view->view_canonical=true;
                $this->view->style_page="experience/search";
                $this->_helper->viewRenderer->setScriptAction("expesearch");
                $this->view->tag=$tag=str_replace("tag-", "", $alias);
                $key=str_replace("-"," ",$tag);
                $this->view->product=$this->Model_News->getProductforTag($key);
                $march=$this->Model_News->Expert_Article_Search($key,100,false);
                $value_tag_index_page=$this->Model_News->getTagAlias($tag);
                if(!empty($value_tag_index_page)){
                  $this->view->index_page=$value_tag_index_page['index_page'];
                }else{
                  $this->view->index_page=1;
                }
                if($march)
                {
                   $list=Zend_Paginator::factory($march);
                   $list->setCurrentPageNumber($this->_getParam("page",1));
                   $list->setItemCountPerPage(13);
                   $list->setPageRange(5);
                   $this->view->list=$list;
                }
                else
                {
                   $this->view->no_search=true;
                }
                $title=str_replace("-", " ", $tag);
                if(!empty($value_tag_index_page['seo']))
                {
                    $myseo=(array)json_decode($value_tag_index_page['seo'] );
                    $this->view->headTitle( $myseo['seo_title']);
                    if(!empty($myseo['seo_description'])){
                        $this->view->headMeta()->setName('description', $myseo['seo_description']);
                    }
                    if(!empty($myseo['name'])){
                        $this->view->Page_Seo_Footer=array();
                        $this->view->Page_Seo_Footer['title']=$myseo['name'];
                        $this->view->Page_Seo_Footer['content']=$myseo['cont'];
                        $this->view->Page_Seo_Footer['image_content']=$myseo['picture'];           
                    }
                }
                else
                {
                    $this->view->headTitle( "Tin tức về ".$title." tại Điện Máy Chợ Lớn  ");
                    $this->view->headMeta()->setName('description',"Tổng hợp danh sách các bài viết hay, tin tức về ".$title." tại Điện Máy Chợ Lớn ");
                }
                return true;
            }
            //END SERACH;
            //DETAIL
            $article=$this->Model_News->Expe_Get_Article($alias);
            if(!empty($article))
            {
                $this->Model_News->listProductInContent($this->article['product']);
                $this->view->style_page="experience/detail";
                $this->_helper->viewRenderer->setScriptAction("expedetail");
                $result=$this->Model_News->Expe_Get_Cate($article['cid_cate']);
                $this->view->Child=$result["C"];
                $this->view->Parent=$result["P"];
                $seo=(array)json_decode($article['seo']);
                $this->view->article=$article;
                $this->view->product=$this->Model_News->List_Proudct_Array();
                if(!empty($article['canonical'])){
                    $this->view->canonical=$article['canonical'];
                }
                else
                {
                   $this->view->canonical="https://dienmaycholon.vn/kinh-nghiem-mua-sam/".$this->filter->toAlias2($article['name']);
                } 
                $this->view->index_page=$article['index_page'];
                $this->view->tag=$this->Model_News->getTag($article['id']);
                $this->view->list_article=$this->Model_News->Expe_Article_Child($article['cid_cate']);
                $this->view->other_article=$this->Model_News->getOtherArticle($article['cid_cate'],$article['id']);
                $seo_title= (empty($seo['title'])) ? $article['name'] : $seo['title'];
                $seo_des= (empty( $seo['description'])) ? $this->filter->subString($article['description'],200) : $seo['description'];
                $this->view->headTitle( $seo_title);
                $this->view->headMeta()->setName('description', $seo_des);
                $result['parent']['name']   = $this->view->Parent['name'];
                $result['parent']['alias']  = $this->view->Parent['alias'];
                $result['parent']['id']     = $this->view->Parent['id'];
                $result['child']['name']    = $this->view->Child['name'];
                $_count_sidebar             = 0;
                foreach($this->view->List_Parent as $cate)
                {
                    $result['sidebar'][$_count_sidebar]['id']         =   $cate['id'];
                    $result['sidebar'][$_count_sidebar]['alias']      =   $cate['alias'];
                    $result['sidebar'][$_count_sidebar]['name']       =   $cate['name'];
                    $child=$this->view->Model_News->Expe_All_Cate($cate['id']);
                    $_count_child           = 0;
                    foreach($child as $c)
                    {
                        $result['sidebar'][$_count_sidebar]['child'][$_count_child]['id']     =  $c['id'];
                        $result['sidebar'][$_count_sidebar]['child'][$_count_child]['alias']  =  $c['alias'];
                        $result['sidebar'][$_count_sidebar]['child'][$_count_child]['name']   =  $c['name'];
                        $_count_child++;
                    }
                    $_count_sidebar++;
                }
                $result['article']['id']                    = $this->view->article['id'];
                $result['article']['name']                  = $this->view->article['name'];
                $array_content= explode("#DMCL", $this->view->article['content']);
                $result['article']['content']               = $array_content[0];
                $result['article']['name_user']             = $this->view->article['name_user'];
                $result['article']['created']               = $this->view->article['created'];
                $result['article']['count_view']            = $this->view->article['count_view'];
                $result['article']['total_rate']            = $this->view->article['total_rate'];
                $result['article']['alias']                 = $this->view->article['alias'];
                $result['article']['rate']                  = $this->view->article['rate'];
                $_count_other_article                       = 0;
                foreach($this->view->other_article as $article)
                {
                    $result['article']['other_article'][$_count_other_article]['id']          =  $article['id'];
                    $result['article']['other_article'][$_count_other_article]['alias']       =  $article['alias'];
                    $result['article']['other_article'][$_count_other_article]['name']        =  $article['name'];
                    $result['article']['other_article'][$_count_other_article]['count_view']  =  $article['count_view'];
                    $comment = $this->view->Model_News->Expe_Cout_Comment($article['id']);
                    $result['article']['other_article'][$_count_other_article]['count_comment'] =  $comment;
                    $_count_other_article++;
                }
                $_count_list_article                        = 0;
                foreach($this->view->list_article  as $article)
                {
                    $result['article']['list_article'][$_count_list_article]['id']             =  $article['id'];
                    $result['article']['list_article'][$_count_list_article]['alias']          =  $article['alias'];
                    $result['article']['list_article'][$_count_list_article]['name']           =  $article['name'];
                    $result['article']['list_article'][$_count_list_article]['count_view']     =  $article['count_view'];
                    $comment = $this->view->Model_News->Expe_Cout_Comment($article['id']);
                    $result['article']['other_article'][$_count_list_article]['count_comment'] =  $comment;
                    $result['article']['other_article'][$_count_list_article]['description']   = $article['description'];
                    $_count_list_article++;
                }
                $_count_key_tags                            = 0;
                foreach($this->view->tag as $tag)
                {
                    $result['keys_tags'][$_count_key_tags]['alias']  =  $tag['alias'];
                    $result['keys_tags'][$_count_key_tags]['name']   =  $tag['name'];
                    $_count_key_tags++;
                }
            }
        }
        $data['message']     = 'chi tiết kinh nghiệm mua sắm';
        $data['errorcode']   = 0;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // list comment kinh nghiệm mua sắm
    public function listcommentAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $data   = array();
        $error  = 'not view';
        if($id=$this->_getParam("id"))
        {
            $order=$this->_getParam("order",1);
            $this->view->myorder=$order;
            $list=Zend_Paginator::factory($this->Model_News->getComment($id,0,$order));
            $list->setCurrentPageNumber($this->_getParam("page",1));
            $list->setItemCountPerPage(3);
            $list->setPageRange(5);
            $this->view->review=$list;
            $this->view->id_article=$id;
            $error = 0;
        }
        $_count_comment = 1;
        foreach($this->view->review as $review)
        {
            $TComment=$this->Model_News->getComment($this->view->id_article,$review['id']);
            if($this->view->user)
            {
                $result[$_count_comment]['user_id']  = $this->view->user->id;
            }
            $result[$_count_comment]['id']           = $review['id'];
            $result[$_count_comment]['name']         = $review['name'];
            $result[$_count_comment]['content']      = $review['content'];
            $result[$_count_comment]['likes']        = $review['likes'];
            $result[$_count_comment]['created']      = $review['created'];
            $_count_review                           = 1;
            foreach($TComment as $comment)
            {
                $result[$_count_comment]['review'][$_count_review]['id']        = $comment['id'];
                $result[$_count_comment]['review'][$_count_review]['name']      = $comment['name'];
                $result[$_count_comment]['review'][$_count_review]['is_admin']  = $comment['is_admin'];
                $result[$_count_comment]['review'][$_count_review]['content']   = $comment['content'];
                $result[$_count_comment]['review'][$_count_review]['likes']     = $comment['likes'];
                $result[$_count_comment]['review'][$_count_review]['created']   = $comment['created'];
                $_count_review++;
            }
            $_count_comment++;
        }
        $data['message']     = 'list comment tin khuyến mãi';
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //like comment kinh nghiệm mua sắm
    public function likecommentarticleAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = 'Thích bình luận không thành công';
        $error  = 'Không tìm thấy id của bình luận';
        if($id=$this->_getParam("id")){
            $id         = $this->filter->inject($id);
            $this->Model_News->updateLike('1',$id);
            $result     = "Thích bình luận thành công";
            $error      = 0;
        } 
        $data['message']     = 'Like comment article';
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );   
    }

    //post comment kinh nghiệm mua sắm
    public function postcommentexpAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error=array();
        $content    = $this->filter->injectSql($this->_getParam("content") );
        $gender     = $this->_getParam("gender");
        $email      = $this->filter->injectSql( $this->_getParam("email") );
        $name       = $this->filter->injectSql( $this->_getParam("name") );
        $id         = $this->_getParam("id");
        $cid_parent = $this->_getParam("parent",0);
        if(empty($name))
        {
          $error['name']="Not Empty";
        }
        if(empty($email))
        {
          $error['email']="Not Empty";
        }
        if(empty($content))
        {
          $error['content']="Not Empty";
        }
        if(empty($error))
        {
            $comment                =   $this->TTComment->fetchNew();
            $comment->name          =   $name;
            $comment->email         =   $email;
            $comment->content       =   $content;
            $comment->gender        =   $gender;
            $comment->created       =   date("Y-m-d H:i:s");
            $comment->cid_parent    =   $cid_parent;
            $comment->status        =   '1';
            $comment->cid_article   =   $id;
            if($this->view->user)
            {
                $comment->cid_user  =   $this->view->user->id;
                if($this->view->user->role < 8 )
                {
                    $comment->is_admin='1';
                }
            }
            else
            {
                $comment->is_admin='0';
            }
            $comment->save();
            $result     = "Thêm bình luận thành công";
            $error      = 0;
        }
        $data['message']     = 'Post comment article experience';
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );  
    }

    // Tin tức khuyến mãi
    public function listnewspromotionAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $data['errorcode'] = 'Không tìm thấy danh mục tin tức';
        $result = array();
        if($alias=$this->_getParam("alias")){
            $alias=$this->filter->injectSql($alias);
            //CATE
            if($Cate=$this->Model_News->getCate($alias)){
                $this->view->style_page="news/index";
                $this->view->view_canonical=true;
                $this->_helper->viewRenderer->setScriptAction("listpromotion");
                $this->view->cate=$Cate;
                $list=Zend_Paginator::factory($this->Model_News->art_list(null,$Cate['id']));
                $list->setCurrentPageNumber($this->_getParam("page",1));
                $list->setItemCountPerPage(12);
                $list->setPageRange(5);
                $this->view->news=$list;
                if(empty($this->view->headTitle()[0])){
                    $this->view->headTitle($Cate['name']);
                }
                $result['cate']      = $this->view->cate;
                $_count = 0;
                foreach($this->view->news as $news)
                {
                    $result['cate']['news'][$_count]['id']   = $news['id'];
                    $result['cate']['news'][$_count]['name'] = $news['name'];
                    $result['cate']['news'][$_count]['photo'] = $this->checkPhoto('/public/picture/news/dienmay_'.$news['id'].'.png');
                    $result['cate']['news'][$_count]['countview'] = $news['countview'];
                    $_count++;
                }
                $data['message']     = 'list tin khuyến mãi';
                $data['errorcode']   = 0;
                $data['data']        = $result;
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
                exit();
            }
            //DETAIL 
            else
            {
                if($Detail=$this->Model_News->getDetail($alias))
                {
                    $this->Model_News->updateView($Detail['id']);
                    $this->view->style_page="news/detail";
                    $this->_helper->viewRenderer->setScriptAction("detailpromotion");
                    $this->view->detail=$Detail;
                    $this->view->news=$this->Model_News->art_list(5,$detail['cid_cate']);
                    $this->view->countreview=$this->Model_Artreview->fetchAll("status='1' AND cid_article=".$Detail->id);
                    $result['detail']['id']              = $this->view->detail['id'];
                    $result['detail']['name']            = $this->view->detail['name'];
                    $result['detail']['date_mod']        = $this->view->detail['date_mod'];
                    $result['detail']['countview']       = $this->view->detail['countview'];
                    $result['detail']['summary']         = $this->view->detail['summary'];
                    $result['detail']['total_rate']      = $this->view->detail['total_rate'];
                    $result['detail']['rate']            = $this->view->detail['rate'];
                    $result['detail']['alias']           = $this->view->detail['alias'];
                    $_count_news = 0;
                    foreach($this->view->news as $news)
                    {
                        $result['news'][$_count_news]['id']         =  $news['id'];
                        $result['news'][$_count_news]['name']       =  $news['name'];
                        $result['news'][$_count_news]['photo']      =  $this->checkPhoto('/public/picture/news/big/dienmay_'.$news['id'].'.png');
                        $result['news'][$_count_news]['date_mod']   =  $news['date_mod'];
                        $_count_news++;
                    }
                    $data['message']     = 'chi tiết tin khuyến mãi';
                    $data['errorcode']   = 0;
                    $data['data']        = $result;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
                    exit(); 
                }
            }
        }
        $data['message']     = 'list tin khuyến mãi';
        $data['errorcode']   = 0;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // mua trả góp
    public function paymentindexAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $this->view->style_page="installment/index";
        $popup = new Application_Model_DbTable_Tm_Popup();
        $this->view->imgbanner = $popup->fetchAll("category='13' and active='1'");
        $this->view->sql="";
        $this->view->name_series="";
        $this->view->url_series="";
        if($g=$this->_getParam("g")){
            $g=$this->filter->injectSql($g);
            $Data_series=$this->TTDefaultSeries->fetchRow("name LIKE '$g'");
            if(!empty($Data_series)){
                $this->view->name_series=strtolower($Data_series['name']);
                $this->view->sql=" AND a.cid_series=".$Data_series['id']." ";
                $this->view->url_series="-".$this->filter->toAlias($Data_series['name']);
            }
        }   
        $Tpay                           =   new Application_Model_DbTable_Tm_Installment();
        $this->view->listpay            =   $Tpay->fetchAll()->toArray();
        $this->view->total              =   $this->TTDefaultPayment->getCount()['total'];
        $this->view->slideshow          =   $this->TTDefaultPayment->getSlide();
        $this->view->banner             =   $this->TTDefaultPayment->getBanner();
        foreach($this->view->slideshow as $slide)
        {
            $_array_silde['id']                 =   $slide['id'];
            $_array_silde['name']               =   $slide['name'];
            $_array_silde['description']        =   $slide['description'];
            $_array_silde['links']              =   $slide['links'];
            $_array_silde['photo']['big']       =   $this->Url_dienmaycholon.PAYMENT_URL.$slide['slide_name'];
            $_array_silde['photo']['small']     =   $this->Url_dienmaycholon.PAYMENT_URL.$slide['icon_name'];
            $result['slide'][]                  =   $_array_silde;
        }
        foreach($this->view->DTCATE_PARENT as $parent)
        {
            $_array_parent = array();
            $count=$this->TTDefaultPayment->getCount(" AND  c.cid_parent={$parent['id']} ".$this->view->sql);
            if(!empty($count['total']))
            {
                $list=$this->TTDefaultPayment->getAllProductOfInstallment("  AND  c.cid_parent={$parent['id']} ".$this->view->sql," ORDER BY b.is_promotion DESC,b.stock_num DESC  LIMIT 0,8");
                $_array_parent['id']            =  $parent['id'];
                $_array_parent['name']          =  $parent['name'];
                $_array_parent['count']         =  $count['total'];
                foreach($list as $product)
                {
                    $_array_product                     = array();
                    $product                            = $this->TTDefaultPromotion->getPriceParent($product);
                    $_array_product['id']               = $product['id'];
                    $_array_product['myid']             = $product['myid'];
                    $_array_product['name']             = $product['name'];
                    $_array_product['alias']            = $product['alias'];
                    $_array_product['cid_series']       = $product['cid_series'];
                    $_array_product['isprice']          = $product['isprice'];
                    $_array_product['is_icon']          = $product['is_icon'];
                    $_array_product['is_model']         = $product['is_model'];
                    $_array_product['is_price']         = $product['is_price'];
                    $_array_product['is_flash_sale']    = $product['is_flash_sale'];
                    $_array_product['is_sale']          = $product['is_sale'];
                    $_array_product['is_double_price']  = (!empty($product['is_double_price']))?$product['is_double_price']:'1';
                    $_array_product['is_red_day']       = (!empty($product['is_red_day']))?$product['is_red_day']:'1';
                    $_array_product['is_million']       = (!empty($product['is_million']))?$product['is_million']:'1';
                    $_array_product['new_description']  = $product['new_description'];
                    $_array_product['coupons']          = $product['coupons'];
                    $_array_product['discountcoupons']  = $product['discountcoupons'];
                    $_array_product['discount']         = $product['discount'];
                    $_array_product['saleprice']        = $product['saleprice'];
                    $_array_product['is_promotion']     = $product['is_promotion'];
                    $_array_product['cid_res']          = $product['cid_res'];
                    $_array_product['namecate']         = $product['namecate'];
                    $_array_product['photo']            = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',220,220));
                    $gift=$this->view->TTDefaultPromotion->getGiftText($product['cid_res']);
                    if(empty($gift['total']))
                    {
                        $_array_product['gift'] = '';
                    }
                    else
                    {
                        $_array_product['gift'] = $gift['total'];
                    }
                    $attribute=$this->TTDefaultTemplate->getSpecial($product['myid']);
                    $_count_att = 1;
                    foreach ($attribute as $attr)
                    {
                        $_array_attr                         = array();
                        $_array_attr['id']                   = $attr['id'];
                        $_array_attr['val']                  = $attr['val'];
                        $_array_product['element_special'][] = $_array_attr;
                    }
                    $_array_parent['product'][]         =  $_array_product;
                } 
            }
            foreach($this->view->DTCATE_CHILD[$parent['id']] as $child)
            {
                $_array_child       = array();
                $c=$this->TTDefaultPayment->getCount(" AND  c.id={$child['id']} ".$this->view->sql);
                if($c['total']> 0)
                {
                    $_array_child['name']       = $child['name'];
                    $_array_child['photo']      = $this->checkPhoto($this->filter->get_image_product_lcd_home('/public/picture/cate/'.$child['picture_tg']));
                    $_array_child['total']      = $c['total'];
                    $_array_parent['child'][]   = $_array_child;
                }
            }
            if(!empty($_array_parent))
            {
                $result['cate_parent'][] = $_array_parent;
            }
        }
        $result['series']               =   $this->view->name_series;
        $data['message']                =   'Lấy thông tin sản phẩm trả góp thành công';
        $data['errorcode']              =   0;
        $data['data']                   =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Quyền lợi thẻ thành viên
    public function memberbenefitsAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $this->view->data = $this->Tm_Slideshow->fetchAll("type='2'", "position ASC");
        $t=$this->_getParam("alias");
        $type=0;
        if($t=="quyen-loi"){
            $type=1;
        }
        if($t=="tich-luy-diem"){
            $type=2;
        }
        if($t=="ngay-hoi-thanh-vien-thang-1"){
            $type=3;
        }
        if($t=="ngay-hoi-thanh-vien-thang-2"){
            $type=4;
        }
        if($t=="ngay-hoi-thanh-vien-thang-3"){
            $type=5;
        }
        $data = $this->memberbenefits->fetchRow("type='".$type."' AND active='1'", "id DESC");
        $this->view->datamemberbenefits  = $data;
        $this->view->type = $type;
        if($type==3){
            $month=$this->_getParam("month");
            if($month==0) $month = 1;
            $this->view->month = $month;
        }
        if(!empty($this->DTGeneral->name))
        {
           $this->view->headTitle(" Quyền lợi thẻ thành viên| ".$this->DTGeneral->name);
        }
        if(!empty($this->DTGeneral->keywords))
        {
           $this->view->headMeta()->setName('keywords', $this->DTGeneral->keywords);
        }
        if(!empty($this->DTGeneral->description))
        {
           $this->view->headMeta()->setName('description', $this->DTGeneral->description);
        }
        $_count_banner_cate = 0;
        foreach($this->view->data as $banner_cate)
        {
            $result['banner_cate'][$_count_banner_cate]['id']                 =   $banner_cate['id'];
            $result['banner_cate'][$_count_banner_cate]['name']               =   $banner_cate['name'];
            $result['banner_cate'][$_count_banner_cate]['slide_description']  =   $banner_cate['slide_description'];
            $result['banner_cate'][$_count_banner_cate]['slide_icon_url']     =   $banner_cate['slide_icon_url'];
            $result['banner_cate'][$_count_banner_cate]['slide_name']         =   $banner_cate['slide_name'];
            $result['banner_cate'][$_count_banner_cate]['links']              =   $banner_cate['links'];
            $result['banner_cate'][$_count_banner_cate]['position']           =   $banner_cate['position'];
            $result['banner_cate'][$_count_banner_cate]['type']               =   $banner_cate['type'];
            $result['banner_cate'][$_count_banner_cate]['b_id']               =   $banner_cate['b_id'];
            $result['banner_cate'][$_count_banner_cate]['photo']              =   $this->checkPhoto(PICTURE_URL.'slideshow/dienmay_'.$banner_cate['id'].'.png');
            $_count_banner_cate++;
        }
        $result['type']                     =   $this->view->type;
        if($this->view->type=='0')
        {
            if(!empty($this->view->datamemberbenefits->conditions)){
                $result['conditions']           =   $this->view->datamemberbenefits->conditions;
            }
            else
            {
                $result['conditions']           =   '';
            }
            if(!empty($this->view->datamemberbenefits->rules)){
                $result['rules']            =   $this->view->datamemberbenefits->rules;
            }
            else
            {
                $result['rules']            =   '';
            }
            if(!empty($this->view->datamemberbenefits->terms)){
                $result['terms']            =   $this->view->datamemberbenefits->terms;
            }
            else
            {
                $result['terms']            =   '';
            }
        }
        if($this->view->type=='1')
        {
            if(!empty($this->view->datamemberbenefits->discount)){
                $result['discount']         =   $this->view->datamemberbenefits->discount;
            }
            else
            {
                $result['discount']         =   '';
            }
            $result['message']              =   "Chút mừng sinh nhật";
        }
        if($this->view->type=='2')
        {
            if(!empty($this->view->datamemberbenefits->points)){
                $result['points']           =   $this->view->datamemberbenefits->points;
            }
            else
            {
                $result['points']           =   'Vui lòng quay lại sau khi có chương trình';
            }
        }
        if($this->view->type=='3')
        {
            if(!empty($this->view->datamemberbenefits->month1)){
                $result['month1']           =   $this->view->datamemberbenefits->month1;
            }
            else
            {
                $result['month1']           =   '';
            }
        }
        if($this->view->type=='4')
        {
            $result['month1']           =   $this->view->datamemberbenefits->month2;
        }
        if($this->view->type=='5')
        {
            if(!empty($this->view->datamemberbenefits->month3)){
                $result['month3']           =   $this->view->datamemberbenefits->month3;
            }
            else
            {
                $result['month3']           =   '';
            }
        }
        $datajson['message']                =   'Quyền lợi thẻ thành viên';
        $datajson['errorcode']              =   0;
        $datajson['data']                   =   $result;
        echo json_encode($datajson, JSON_HEX_QUOT | JSON_HEX_TAG );
    }

    // FOOTER
    public function footerAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $_count = 0;
        $result = array();
        foreach ($this->view->DTStore[1] as $store) {
            $result[0][$_count]['name']         =   $store->name;
            $result[0][$_count]['address']      =   $store->address;
            $result[0][$_count]['phone']        =   $store->phone;
            $_count++;
        }
        $_count1 = 0;
        foreach ($this->view->DTStore[2] as $store) {
            $result[1][$_count1]['name']        =   $store->name;
            $result[1][$_count1]['address']     =   $store->address;
            $result[1][$_count1]['phone']       =   $store->phone;
            $_count1++;
        }
        $_count2 = 0;
        foreach ($this->view->DTStore[3] as $store) {
            $result[2][$_count2]['name']        =   $store->name;
            $result[2][$_count2]['address']     =   $store->address;
            $result[2][$_count2]['phone']       =   $store->phone;
            $_count2++;
        }
        $data['message']     = 'Chi nhánh siêu thị';
        $data['errorcode']   = (!empty($result))?0:1;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }
    // liên hệ  
    public function contactAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $name=$this->filter->injectSql($this->_getParam("name"));
        $phone=$this->filter->injectSql($this->_getParam("phone"));
        $note=$this->filter->injectSql($this->_getParam("note"));
        $error = null;
        if(empty($name)){
            $error['name']=" Vui lòng nhập họ và tên";
        }
        if(empty($phone)){
            $error['phone']=" Vui lòng nhập số điện thoại ";
        }
        if(empty($note)){
            $error['note']=" Vui lòng nhập nội dung liên hệ ";
        }
        if(empty($error)){
            $TContact =new  Application_Model_DbTable_Tm_Contact();
            $new=$TContact->fetchNew();
            $new->name=$name;
            $new->note=$note;
            $new->phone=$phone;
            $new->created=date("Y-m-d H:i:s");
            $new->save();
            $this->view->success=true;
            $result = 'tạo liên hệ thành công';
            $error  = 0;
        }else{
            $result = 'tạo liên hệ không thành công';
        }
        $data['message']     = 'liên hệ';
        $data['errorcode']   = $error;
        $data['data']        = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // thẻ quà tặng
    public function giftcardAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page=$this->News->art_pagecontent("Giftcard - Thẻ Quà Tặng");
        $data['message']        =   'thẻ quà tặng';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Hỗ trợ
    public function supportmemberAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page=$this->News->art_pagecontent("Hỗ Trợ Thành Viên");
        $data['message']        =   'Hỗ trợ thành viên';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Chuyển khoản
    public function transferAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page                   =   $this->News->art_pagecontent("Thông tin chuyển khoản");
        $data['message']        =   'Chuyển Khoản';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Câu hỏi thường gặp
    public function questionAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page                   =   $this->News->art_pagecontent("Câu Hỏi Thường Gặp");
        $data['message']        =   'Câu hỏi thường gặp';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Lợi ích mua hàng online
    public function benefitAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page                   =   $this->News->art_pagecontent("Lợi Ích Mua Hàng Online");
        $data['message']        =   'Lợi ít mua hàng';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Hướng dẫn mua hàng online
    public function instructionAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page                           =   $this->News->art_pagecontent("Hướng Dẫn Mua Hàng Online");
        $data['message']                =   'Hướng dẫn mua hàng online';
        $data['errorcode']              =   0;
        $data['data']                   =   $page;
        echo json_encode($data , JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Bảo trì
    public function maintenanceAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page=$this->News->art_pagecontent("Bảo Trì - Bảo Hành - Đổi Trả");
        $data['message']        =   'Bảo Trì - Bảo Hành - Đổi Trả';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Qui định giao hàng
    public function regulationAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page                   =   $this->News->art_pagecontent("Quy Định Giao Hàng");
        $data['message']        =   'Quy Định Giao Hàng';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    // Điều khoản người dùng
    public function provisionAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start                  = microtime(true);
        $page                   =   $this->News->art_pagecontent("Điều khoản sử dụng");
        $data['message']        =   'Điều khoản sử dụng';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    // Thỏa thuận người dùng
    public function agreementAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start                  = microtime(true);
        $page                   =   $this->News->art_pagecontent("Thoả thuận người dùng");
        $data['message']        =   'Thoả thuận người dùng';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Giới thiệu công ty
    public function introduceAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start                  =   microtime(true);
        $page                   =   $this->News->art_pagecontent("Giới Thiệu Công Ty");
        $data['message']        =   'Giới Thiệu Công Ty';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    // Hệ thống siêu thị
    public function systemsupermarketAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($element=$this->_getParam("element")){
            $this->view->store=$this->Store->fetchRow("id=$element AND status='1'");
        }else{
            $this->view->store=$this->Store->fetchRow("status='1' AND is_area='1'");
        }
        $this->view->slide=$this->TTDefaultBanner->getSlideOfBrand();
        $this->view->total_comment=$this->TTDefaultPage->getTotal();
        $result = array();
        $_count_opening = 1;
        foreach ($this->view->DTStore as $StoreThree)
        {
            foreach($StoreThree as $store)
            {
                if($store['opening']=='1')
                {
                    $_array_opening                     = array();
                    $_array_opening['address']          = $store['address'];
                    $_array_opening['date_start']       = $store['date_start'];
                    $result['opening'][]                = $_array_opening;
                }
            }
        }
        $_count_open_soon = 1;
        foreach ($this->view->DTStore as $StoreThree)
        {
            foreach($StoreThree as $store)
            {
                if($store['open_soon']=='1')
                {
                    $_array_soon                        = array();
                    $_array_soon['address']             = $store['address'];
                    $_array_soon['date_start']          = $store['date_start'];
                    $result['opensoon'][]               = $_array_soon;
                }
            }
        }
        $_count_store1 = 1;
        foreach($this->view->DTStore[1] as  $store)
        {
            $_array_store1                  =   array();
            $_array_store1['id']            =   $store['id'];
            $_array_store1['name']          =   $store['name'];
            $_array_store1['address']       =   $store['address'];
            $result['store1'][]             =   $_array_store1;
        }
        $_count_store2 = 1;
        foreach($this->view->DTStore[2] as  $store)
        {
            $_array_store2                  =   array();
            $_array_store2['id']            =   $store['id'];
            $_array_store2['name']          =   $store['name'];
            $_array_store2['address']       =   $store['address'];
            $result['store2'][]             =   $_array_store2;
        }
        $_count_store3 = 1;
        foreach($this->view->DTStore[3] as  $store)
        {
            $_array_store3                  =   array();
            $_array_store3['id']            =   $store['id'];
            $_array_store3['name']          =   $store['name'];
            $_array_store3['address']       =   $store['address'];
            $result['store3'][]             =   $_array_store3;
        }
        if(!empty($this->view->slide))
        {
            $_count_slide = 1;
            foreach($this->view->slide as $slide)
            {
                $result['slide'][]          =   $slide;
            }
        }

        // $data['message']['title']        =   'Hệ thống siêu thị';
        // $data['message']['opening']      =   'Mới khai trương';
        // $data['message']['opensoon']     =   'Sắp khai trương';
        // $data['message']['store1']       =   'TP Hồ Chí Minh và lân cận';
        // $data['message']['store2']       =   'Các tỉnh miền Tây';
        // $data['message']['store3']       =   'Các tỉnh miền Trung';
        $data['message']                    =   'Lấy danh sách hệ thống siêu thị thành công';
        $data['errorcode']                  =   0;
        $data['data']                       =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Chi tiết hệ thống siêu thị
    public function detailsystemsupermarketAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $message = 'Lấy thông tin hệ thống siêu thị không thành công';
        $error   = 1;
        if($key=$this->_getParam("alias")){ 
            foreach ($this->view->DTStore[1] as $value) {
                if($this->filter->toAlias2($value['address'])==$key){
                    $this->view->data=$value;
                    break;
                }
            }
            if(empty($this->view->data)){
                foreach ($this->view->DTStore[2] as $value) {
                    if($this->filter->toAlias2($value['address'])==$key){
                        $this->view->data=$value;
                        break;
                    }
                }
            }
            if(empty($this->view->data)){
                foreach ($this->view->DTStore[3] as $value) {
                    if($this->filter->toAlias2($value['address'])==$key){
                        $this->view->data=$value;
                        break;
                    }
                }
            }
            $message                = 'Lấy thông tin hệ thống siêu thị thành công';
            $error                  = 0;
        }
        $this->view->slide          = $this->TTDefaultBanner->getSlideOfBrandChild($this->view->data['id']);
        $this->view->total_comment  = $this->TTDefaultPage->getTotal();
        $result                     = array();
        $result['address']          = $this->view->data['address'];  
        $result['photo']            = $this->Url_dienmaycholon.'/public/picture/store/big/dienmay_'.$this->view->data['id'].'.png';     
        $result['phone']            = $this->view->data['phone'];       
        $result['email']            = $this->view->data['email'];   
        $result['summary']          = $this->view->data['summary'];         

        $data['message']            = $message;
        $data['errorcode']          = $error;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }
    // Comment bài báo
    public function commentcontactAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $order=$this->_getParam("order",1);
        $this->view->myorder=$order;
        $list=Zend_Paginator::factory($this->TTDefaultPage->getComment(0,$order));
        $list->setCurrentPageNumber($this->_getParam("page",1));
        $list->setItemCountPerPage(10);
        $list->setPageRange(5);
        $this->view->review=$list;
        $_count_review = 1;
        foreach($this->view->review as $review)
        {
            $TComment                                            =      $this->TTDefaultPage->getComment($review['id']);
            $result['review'][$_count_review]['id']              =      $review['id'];
            $result['review'][$_count_review]['title']           =      $review['title'];
            $result['review'][$_count_review]['comment']         =      $review['comment'];
            $result['review'][$_count_review]['likes']           =      $review['likes'];
            $result['review'][$_count_review]['date_created']    =      $review['date_created'];
            if(!empty($TComment))
            {
                $_count_tcomment = 1;
                foreach($TComment as $comment)
                {
                    $result['review'][$_count_review]['TComment'][$_count_tcomment]['id']           =    $comment['id'];
                    $result['review'][$_count_review]['TComment'][$_count_tcomment]['title']        =    $comment['title'];
                    $result['review'][$_count_review]['TComment'][$_count_tcomment]['comment']      =    $comment['comment'];
                    $result['review'][$_count_review]['TComment'][$_count_tcomment]['likes']        =    $comment['likes'];
                    $result['review'][$_count_review]['TComment'][$_count_tcomment]['date_created'] =    $comment['date_created'];
                    $result['review'][$_count_review]['TComment'][$_count_tcomment]['type_user']    =    $comment['type_user'];
                    $_count_tcomment++;
                }
            }
            $_count_review++;
        }
        // $data['message']['title']                =   'Comment contact';
        // $data['message']['type_user'][1]         =   'Khách hàng';
        // $data['message']['type_user'][2]         =   'Seller';
        // $data['message']['type_user'][3]         =   'Quản trị viên';
        $data['message']        =   (!empty($result))?'Trả về danh sách bình luận liên hệ':'Không có dữ liệu';
        $data['errorcode']      =   0;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //Like comment
    public function likecommentcontactAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($id=$this->_getParam("id")){
           $id=$this->filter->inject($id);
           $result = $this->TTDefaultPage->updateLike('1',$id);
           $error = 0;
        }
        else
        {
           $error = 'không thấy';
        }
        $data['message']        =   'Like bình luận thành công';
        $data['errorcode']      =   0;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //  post comment liên hệ
    public function postcommentcontactAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start = microtime(true);
        $error=array();
        $content    =   $this->filter->injectSql($this->_getParam("content") );
        $gender     =   $this->_getParam("gender");
        $email      =   $this->filter->injectSql( $this->_getParam("email") );
        $name       =   $this->filter->injectSql( $this->_getParam("name") );
        $id         =   $this->_getParam("id",0);
        $cid_parent =   $this->_getParam("parent",0);
        $message    =   'Gửi bình luận thành công';
        if(empty($name))
        {
           $error['name']       =   1;
           $message             =   'Tên bị trống';
        }
        if(empty($email))
        {
           $error['email']      =   1;
           $message             =   'Email bị trống';
        }
        if(empty($content))
        {
           $error['content']    =   1;
           $message             =   'Nội dung bị trống';
        }
        if(empty($error))
        {
            $comment=$this->TTCommentContact->fetchNew();
            $comment->title=$name;
            $comment->email=$email;
            $comment->comment=$content;
            $comment->gender=$gender;
            $comment->date_created=date("Y-m-d H:i:s");
            $comment->cid_store=$cid_parent;
            $comment->status='1';
            $comment->cid_brand=0;
            if($this->view->user){
                $comment->cid_user=$this->view->user->id;
                if($this->view->user->role < 8 ){
                    $comment->type_user='3';
                }
            }
            else
            {
                $comment->type_user='1';
            }
            $comment->save();
            $result = 'Post comment thành công';
        }
        $data['message']        =   $message;
        $data['errorcode']      =   $error;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // Phương châm bán hàng
    public function mottosaleAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $page                   =   $this->News->art_pagecontent("Phương Châm Bán Hàng");
        $data['message']        =   'Phương Châm Bán Hàng';
        $data['errorcode']      =   0;
        $data['data']           =   $page;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //Chi tiết thông tin siêu thị
    public function detailinfoAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $error  =  'Không tìm thấy thông tin siêu thị';
        if($key=$this->_getParam("alias"))
        {
            foreach ($this->view->DTStore[1] as $value) 
            {
                if($this->filter->toAlias2($value['address'])==$key)
                {
                        $this->view->data=$value;
                        break;
                }
            }
            if(empty($this->view->data))
            {
                foreach ($this->view->DTStore[2] as $value)
                {
                    if($this->filter->toAlias2($value['address'])==$key)
                    {
                        $this->view->data=$value;
                        break;
                    }
                }
            }
            if(empty($this->view->data))
            {
                foreach ($this->view->DTStore[3] as $value)
                {
                    if($this->filter->toAlias2($value['address'])==$key)
                    {
                            $this->view->data=$value;
                            break;
                    }   
                }
            }
        }
        if(!empty($this->view->data))
        {
            $result['id']       = $this->view->data['id'];
            $result['address']  = $this->view->data['address'];
            $result['name']     = $this->view->data['name'];
            $result['phone']    = $this->view->data['phone'];
            $result['email']    = $this->view->data['email'];
            $result['lng']      = $this->view->data['lng'];
            $result['lat']      = $this->view->data['lat'];
            $result['summary']  = $this->view->data['summary'];
            $error              = 0;
        }
        

        $data['message']        =   'Chi tiết thông tin siêu thị';
        $data['errorcode']      =   $error;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //compare
    public function compareAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error      = '';
        $result     = array();
        if(!empty($this->_getParam("id1"))||!empty($this->_getParam("id2")))
        {
            $product[$this->_getParam("id1")]     = $this->_getParam("id1");
            $product[$this->_getParam("id2")]     = $this->_getParam("id2");
            if(!empty($this->_getParam("id3")))
            {
                $product[$this->_getParam("id3")] = $this->_getParam("id3");
            }
            $this->view->product            = $this->TTDefaultProduct->List_Product_Compare($product);
            $this->view->cate               = $this->TTCate->fetchRow("id=".$this->view->product[0]['cid_cate']);
            $this->view->parent             = $this->view->DTCATE_PARENT[$this->view->cate['cid_parent']];
            $result['parent']               = $this->view->parent['name'];
            $result['cate']                 = $this->view->cate['name'];
            $_count_product = 1;
            foreach($this->view->product as $product)
            {
                $_array_product                         =    array();
                $product                                =    $this->TTDefaultPromotion->getPriceCompare($product);
                $_array_product                         =    $product;
                $list_image                             =    $this->filter->get_image_product_all_lcd($product['myid']);
                $Element_of_Product                     =    $this->TTDefaultProduct->Detail_Element($product['myid']);
                foreach($list_image as $key=>$img)
                {
                    $_array_image                       =    array();
                    $_array_image['key']                =    $key;
                    $_array_image['img']                =    $this->Url_dienmaycholon.PRODUCT_URL."product".$product['myid']."/".$img;
                    $_array_product['photo'][]          =    $_array_image;
                } 
                $_array_product['main_photo'] = $this->checkPhoto($this->filter->get_image_product_lcd_home($product['myid'],'',250,250));     
                foreach($Element_of_Product as $element)
                {
                    $_array_element                     =    array();
                    $_array_element['name']             = $element['name'];
                    $_count_value = 1;
                    foreach($element['value'] as $e)
                    {
                        $_array_e                       =   array();
                        $_array_e['name']               =   $e['name'];
                        $_array_e['links']              =   $e['links'];
                        $_array_e['val']                =   $e['val'];
                        $_array_element['value'][]      =   $_array_e;
                    }
                    $_array_product['element'][]        =   $_array_element;
                }
                $result['product'][]        =    $_array_product;
            }
            $message    = 'Trả về kết quả thành công';
        }
        else
        {
            $message    = 'Phải có ít nhất 2 sản phẩm để so sánh';
            $error      = '1';
        }
        $this->view->style_page="compare";
        if(!empty($this->SESSIONDM->Compare)){
            $this->view->product=$this->TTDefaultProduct->List_Product_Compare($this->SESSIONDM->Compare);
            $this->view->cate   =$this->TTCate->fetchRow("id=".$this->view->product[0]['cid_cate']);
            $this->view->parent =$this->view->DTCATE_PARENT[$this->view->cate['cid_parent']];
        }
        $data['message']        =   $message;
        $data['errorcode']      =   $error;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // post comment article
    public function postcommentarticleAction()
    {
        $error=array();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($id=$this->_getParam("id"))
        {
            $this->view->id=$id;
            $name=$this->filter->injectSql($this->_getParam("name"));
            $email=$this->filter->injectSql($this->_getParam("email"));
            $content=$this->filter->injectSql($this->_getParam("content"));
            $gender=$this->_getParam("gender");
            if(empty($name)){
                $error['name']="Not empty name";
            }
            if(empty($content)){
                $error['content']="Not empty content";
            }
            if(empty($error))
            {
                $news=$this->Model_Artreview->fetchNew();
                $news->name=$name;
                $news->email=$email;
                $news->gender=$gender;
                $news->comment=$content;
                if($this->view->user){
                    $news->cid_user=$this->view->user->id;
                    if($this->view->user->role != 9)
                    {
                        $news->is_user='3';
                    }
                }
                else
                {
                    $news->cid_user='0';
                    $news->is_user='1';
                }
                $news->date_created=date("Y-m-d H:i:s");
                $news->status='1';
                $news->likes='0';
                $news->cid_article=$id;
                $news->cid_parent=0;
                $news->save();
                $result = 'Thêm review comment thành công';
                $error  = 0;
            }
        }
        $data['message']        =   'Post comment article';
        $data['errorcode']      =   $error;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // thích comment tin tức
    public function likednewsAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        if($id=$this->_getParam("id"))
        {
            $id=$this->filter->inject($id);
            $this->Model_Artreview->updateLike($id);
            $result = 'Like comment thành công';
        }
        $data['message']        =   'Like comment article';
        $data['errorcode']      =   0;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // post comment article
    public function commentreviewarticleAction()
    {
        $error=array();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $start = microtime(true);
        if($id=$this->_getParam("id"))
        {
            $this->view->id=$id;
            $name=$this->filter->injectSql($this->_getParam("name"));
            $email=$this->filter->injectSql($this->_getParam("email"));
            $content=$this->filter->injectSql($this->_getParam("content"));
            $gender=$this->_getParam("gender");
            $parent=$this->filter->injectSql($this->_getParam("parent") );
            if(empty($name)){
                $error['name']="Not empty name";
            }
            if(empty($content)){
                $error['content']="Not empty content";
            }
            if(empty($error))
            {
                $news=$this->Model_Artreview->fetchNew();
                $news->name=$name;
                $news->email=$email;
                $news->gender=$gender;
                $news->comment=$content;
                if($this->view->user){
                    $news->cid_user=$this->view->user->id;
                    if($this->view->user->role != 9)
                    {
                        $news->is_user='3';
                    }
                }
                else
                {
                    $news->cid_user='0';
                    $news->is_user='1';
                }
                $news->date_created=date("Y-m-d H:i:s");
                $news->status='1';
                $news->likes='0';
                $news->cid_article=$id;
                $news->cid_parent=$parent;
                $news->save();
                $result = 'Thêm review comment thành công';
                $error  = 0;
            }
        }
        $data['message']        =   'Post comment review article';
        $data['errorcode']      =   $error;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // danh sách công việc có nhu cầu tuyển dụng
    public function jobAction(){
        $sql="";
        $error=array();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $r=$this->filter->injectSql($this->_getParam("r"));
        $p=$this->filter->injectSql($this->_getParam("p"));
        $n=$this->filter->injectSql($this->_getParam("n"));
        if(!empty($r)){
            $sql =" AND cid_position=".$r;
        }
         if(!empty($p)){
            $sql .= " AND  city_id=".$p;
        }
        if(!empty($n)){
            $sql .= " AND title LIKE '%{$n}%'";
        }
        $this->view->r=$r;
        $this->view->p=$p;
        $this->view->n=$n;
        $this->TTDefaultRecruit=new Default_Model_Recruit();
        $this->TTLocation = $this->view->TTLocation =  new Application_Model_DbTable_Tm_Cities ();
        $this->view->valuePlace = $this->TTLocation->getSelect();
        $this->view->valueRoom=$this->TTDefaultRecruit->getSelectPosition();
        $list=Zend_Paginator::factory($this->TTDefaultRecruit->getListAPI($sql));
        $list->setCurrentPageNumber($this->_getParam("page",1));
        $list->setItemCountPerPage(9);
        $list->setPageRange(5);
        $this->view->list=$list;
        $this->view->headTitle( "Tuyển dụng  | ".$this->DTGeneral->name);
        $result['valueRoom']    =   $this->view->valueRoom;
        $result['valuePlace']   =   $this->view->valuePlace;
        $_count_list = 1;
        foreach($this->view->list as $job)
        {
            $city                                               =   $this->TTLocation->getID($job['city_id']);
            $dis                                                =   $this->TTLocation->getID($job['dis_id']);
            $result['list'][$_count_list]['city']['name']       =   $city['name'];
            $result['list'][$_count_list]['dis']['name']        =   $dis['name'];
            $result['list'][$_count_list]['job']['salary']      =   $job['salary'];
            $result['list'][$_count_list]['job']['num_job']     =   $job['num_job'];
            $result['list'][$_count_list]['job']['id']          =   $job['id'];
            $_count_list++;
        }
        $data['message']        =   'Tuyển dụng';
        $data['errorcode']      =   0;
        $data['data']           =   $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    // nộp đơn xin việc vào công việc muốn ứng tuyển
    public function postjobAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->TTDefaultRecruit=new Default_Model_Recruit();
        $id=$this->filter->injectSql($this->_getParam("id",'0') );
        $data=$this->view->data=$this->TTDefaultRecruit->fetchRow("id=$id");
        if(empty($data)){
            echo "Công việc bạn ứng tuyển đã hết hạn";exit;
        }
        $result =   'Nộp hồ sơ không thành công';
        $TTRec  =   new Application_Model_DbTable_Tm_Recruitcandidate();
        $error  =   array();
        $name   =   $this->filter->injectSql($this->_getParam("name"));
        $phone  =   $this->filter->injectSql($this->_getParam("phone"));
        $email  =   $this->filter->injectSql($this->_getParam("email"));
        $links  =   $this->filter->injectSql($this->_getParam("links"));
        if(empty($name))
        {   
            $error['name']=" Vui lòng nhập họ và tên của bạn ";
        }
        if(empty($phone))
        {
            $error['phone']=" Vui lòng nhập số điện thoại của bạn ";
        }
        else
        {
            if(strlen($phone) >12 ){
                $error['phone']=" Vui lòng nhập đúng số điện thoại của bạn";
            }
        }
        if(empty($error))
        {
            $name_file          =   '';
            if(!empty($this->_getParam("file")))
            {
                $name_file      =   $this->_getParam("file");
            }
            $new                =   $TTRec->fetchNew();
            $new->full_name     =   $name;
            $new->mobile        =   $phone;
            $new->email         =   $email;
            $new->url_porfolio  =   $links;
            $new->datetime      =   date("Y-m-d H:i:s");
            $new->status        =   '0';
            $new->cid_job       =   $id;
            $new->url_cv        =   $name_file;
            $new->save();
            $result             =   " Cảm ơn bạn đã ứng tuyển đến công ty chúng tôi. Chúng tôi sẽ phản hồi đến bạn một cách sớm nhất. ";
            $error              =   0;
        }
        $data_api['message']        =   'Tuyển dụng';
        $data_api['errorcode']      =   $error;
        $data_api['data']           =   $result;
        echo json_encode($data_api, JSON_HEX_QUOT | JSON_HEX_TAG );
    }

    // Đăng ký nhận tin nhắn
    public function newsletterAction(){
        $TNewsletter=new Application_Model_DbTable_Tm_Newsletter();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $name=$this->filter->injectSql($this->_getParam("name"));
        $email=$this->filter->injectSql($this->_getParam("email"));
        $check_email=$TNewsletter->fetchRow("email LIKE '$email'");
        $error=0;
        if(empty($check_email))
        {
            if(!empty($name) && !empty($email))
            {
                $news=$TNewsletter->fetchNew();
                $news->fullname=$name;
                $news->email=$email;                       
                $news->date_cre=date("Y-m-d H:i:s");
                $news->date_cancel=date("Y-m-d H:i:s");
                $error=0;
                $news->save();
                $result = 'Thêm tài khoản thành công';
            }
            else
            {
                $error = "Tên hoặc email bị trống";
            }
        }
        else
        {
            $result = 'Email đã được đăng ký';
        }
        $data_api['message']        =   'Đăng ký nhận tin tức';
        $data_api['errorcode']      =   $error;
        $data_api['data']           =   $result;
        echo json_encode($data_api, JSON_HEX_QUOT | JSON_HEX_TAG );
    }

    // Tìm kiếm chi nhánh của siêu thị
    public function searchbrandAction()
    {
        $TNewsletter=new Application_Model_DbTable_Tm_Newsletter();
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $this->view->data=array();
        if($key=$this->_getParam("key"))
        {
            $key=$this->filter->injectSql($key);
            foreach ($this->view->DTStore[1] as $value) {
                if(strpos($value['address'], $key)!==false){
                        $this->view->data[]=$value;
                }elseif(strpos($value['name'], $key)!==false){
                        $this->view->data[]=$value;
                }elseif(strpos(str_replace("-", " ", $this->filter->toAlias2($value['address'])), $key)!==false){
                        $this->view->data[]=$value;
                }   
            }
            foreach ($this->view->DTStore[2] as $value) {
                if(strpos($value['address'], $key)!==false){
                        $this->view->data[]=$value;
                }elseif(strpos($value['name'], $key)!==false){
                        $this->view->data[]=$value;
                }elseif(strpos(str_replace("-", " ", $this->filter->toAlias2($value['address'])), $key)!==false){
                        $this->view->data[]=$value;
                }
            }
            foreach ($this->view->DTStore[3] as $value) {
                if(strpos($value['address'], $key)!==false){
                        $this->view->data[]=$value;
                }elseif(strpos($value['name'], $key)!==false){
                        $this->view->data[]=$value;
                }elseif(strpos(str_replace("-", " ", $this->filter->toAlias2($value['address'])), $key)!==false){
                        $this->view->data[]=$value;
                }
            }
        }
        $_count_data = 1;
        foreach($this->view->data as $data)
        {
            $result[$_count_data]['address'] = $data['address'];
            $_count_data++;
        }
        $data_api['message']        =   'Tìm kiếm chi nhánh';
        $data_api['errorcode']      =   0;
        $data_api['data']           =   $result;
        echo json_encode($data_api, JSON_HEX_QUOT | JSON_HEX_TAG );
    }

    //chính sách bảo hành
    public function warrantyAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->view->series         = $this->TTWarranty->getSeries();
        $this->view->article        = $this->TTWarranty->getArticle();
        $this->view->valuesearch    = $this->TTWarranty->getSelectSeries();
        $this->view->valuesearch[0] = "Nhập tên hãng cần tìm";
        if($s=$this->_getParam("s")){
            $this->view->s      = $s;
            $this->view->series = $this->TTWarranty->getSeriesSearch($this->view->s,$this->view->series);
        }
        $result                     = array();
        foreach($this->view->series as $series)
        {
            $_array_series          = array();
            $_array_series['id']    = $series['id'];
            $_array_series['name']  = $series['name'];
            $_array_series['photo'] = '/public/picture/series/dienmay_'.$series['id'].'.png';
            $_array_series['alias'] = $this->filter->toAlias2($series['name']);
            $result['series'][]     = $_array_series;           
        }
        foreach($this->view->article as $article)
        {
            $_array_article                 = array();
            $_array_article['id']           = $article['id'];
            $_array_article['alias']        = $article['alias'];
            $_array_article['name']         = $article['name'];
            $_array_article['description']  = $article['description'];
            $_array_article['count_view']   = $article['count_view'];
            $_array_article['photo']        = PICTURE_URL.'experience/article_'.$article['id'].'.jpg';
            $result['article'][]            = $_array_article;  
        }
        $data['message']            = (!empty($result))?'Lấy kết quả thành công':'Không có dữ liệu';
        $data['errorcode']          = (!empty($result))?0:1;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }
    //Danh mục bảo hành
    public function catewarrantyAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->view->style_page="warranty";
        if($alias=$this->_getParam("alias")){
            $this->view->warranty=$this->TTWarranty->getRow($alias);
            if(!empty($this->view->warranty))
            {
                $this->view->address=$this->TTWarranty->getAllWarranty($this->view->warranty['id']);
                $this->view->series=$this->TTWarranty->getSeries();
                $this->view->article=$this->TTWarranty->getArticle();
                $this->view->valueCity=$this->TTWarranty->getSelectCity($this->view->warranty['id']);
                $this->view->valuesearch=$this->TTWarranty->getSelectSeries();
                $this->view->valuesearch[0]="Nhập tên hãng cần tìm";
                if($s=$this->_getParam("s")){
                    $this->view->s=$s;
                    $this->view->series=$this->TTWarranty->getSeriesSearch($this->view->s,$this->view->series);
                }
                $this->view->policy=$this->TTWarranty->getPolicy(0,4);
            }
        }
        $result                     = array();
        $result['name']             = $this->view->warranty['name'];
        foreach($this->view->address as $add)
        {
            $_array_address                    = array();
            foreach( $this->view->DTCATE_CHILD[$add['cid_cate']] as $cate) 
            {
                $_array_address['name'][]      = $cate['name'];
            }
            $_array_address['address']         = $add['address'];
            $_array_address['phone']           = $add['phone'];
            $result['address'][]               = $_array_address;
        }
        foreach($this->view->policy as $policy)
        {
            $_array_policy                     = array();
            $_array_policy['title']            = $policy['title'];
            $_array_policy['content']          = $policy['content'];
            $result['policy'][]                = $_array_policy;
        }
        $data['message']            = (!empty($result))?'Lấy kết quả thành công':'Không có dữ liệu';
        $data['errorcode']          = (!empty($result))?0:1;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //Danh sách bình luận chính sách bảo hành
    public function listcommentwarrantyAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $id=$this->_getParam("id",0);
        $order=$this->_getParam("order",1);
        $this->view->myorder=$order;
        $list=Zend_Paginator::factory($this->TTWarranty->getComment($id,$order));
        $list->setCurrentPageNumber($this->_getParam("page",1));
        $list->setItemCountPerPage(6);
        $list->setPageRange(5);
        $this->view->review=$list;
        $this->view->id_article=$id;
        $result         =   array();
        foreach($this->view->review as $review)
        {
            $_array_review     =    array();
            $TComment          =    $this->view->TTWarranty->getComment($review['id']);
            if(!empty($review['cid_user']))
            {
                if(is_file(PICTURE_PATH."/user/user_".$review['cid_user'].".png"))
                {
                    $_array_review['photo'] = '/public/picture/user/user_'.$review['cid_user'].'.png';
                }
                else
                {
                    $_array_review['photo'] = '/public/dienmaycholon/general/img/icon_account.png';
                }
            }
            else
            {
                if(is_file(PICTURE_PATH."/user/warranty_".$review['id'].".png"))
                {
                    $_array_review['photo'] = '/public/picture/user/warranty_'.$review['id'].'.png';
                }
                else
                {
                    $_array_review['photo'] = '/public/dienmaycholon/general/img/icon_account.png';
                }
            }
            $_array_review['name']          = $review['name'];
            $_array_review['comment']       = $review['comment'];
            $_array_review['id']            = $review['id'];
            $_array_review['likes']         = $review['likes'];
            $_array_review['date_created']  = $review['date_created'];
            $_array_review['count_comment'] = count($TComment)==0 ? "0" : count($TComment) < 10 ? "0".count($TComment): count($TComment);
            if(!empty($TComment))
            {
                foreach($TComment as $comment)
                {
                    $_array_comment         = array();
                    if(!empty($comment['cid_user']))
                    {
                        if(is_file(PICTURE_PATH."/user/user_".$comment['cid_user'].".png"))
                        {
                            $_array_comment['photo']='/public/picture/user/user_'.$comment['cid_user'].'.png';
                        }
                        else
                        {
                            $_array_comment['photo']='/public/dienmaycholon/general/img/icon_account.png';
                        }
                    }
                    else
                    {
                        if(is_file(PICTURE_PATH."/user/warranty_".$comment['id'].".png"))
                        {
                            $_array_comment['photo']='/public/picture/user/warranty_'.$comment['id'].'.png';
                        }
                        else
                        {
                            $_array_comment['photo']='/public/dienmaycholon/general/img/icon_account.png';
                        }
                    }
                    $_array_comment['name']         = $comment['name'];
                    $_array_comment['type_user']    = $comment['type_user'];
                    $_array_comment['comment']      = $comment['comment'];
                    $_array_comment['likes']        = $comment['likes'];
                    $_array_comment['date_created'] = $comment['date_created']; 
                    $_array_review['TComment'][]    = $_array_comment;
                }
            }
            else
            {
                $_array_review['TComment']      = array();
            }
            $result[] = $_array_review;
        }
        $data['message']            = (!empty($result))?'Lấy kết quả thành công':'Không có dữ liệu';
        $data['errorcode']          = (!empty($result))?0:1;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    }

    //Gửi bình luận chính sách bảo hành
    public function postcommentwarrantyAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->TTComment        = new Application_Model_DbTable_Tm_Warrantyreview();
        $error                  = array();
        $content                = $this->filter->injectSql($this->_getParam("content") );
        $gender                 = $this->_getParam("gender");
        $email                  = $this->filter->injectSql( $this->_getParam("email") );
        $name                   = $this->filter->injectSql( $this->_getParam("name") );
        $cid_parent             = $this->_getParam("parent",0);
        $message                = '';
        if(empty($name))
        {
            $error              = 1;
            $message['name']    = 'Tên bị trống';
        }
        if(empty($email))
        {
            $error              = 1;
            $message['email']   = 'Email bị trống';
        }
        if(empty($content))
        {
            $error              = 1;
            $message['content'] = 'Nội dung bị trống';
        }
        if(empty($error))
        {
            $comment                = $this->TTComment->fetchNew();
            $comment->name          = $result['name']               = $name;
            $comment->email         = $result['email']              = $email;
            $comment->comment       = $result['comment']            = $content;
            $comment->gender        = $result['gender']             = $gender;
            $comment->date_created  = $result['date_created']       = date("Y-m-d H:i:s");
            $comment->cid_review    = $result['cid_review']         = $cid_parent;
            $comment->status        = $result['status']             = '1';
            $comment->likes         = $result['likes']              = '0';
            if($this->view->user)
            {
                $comment->cid_user  = $this->view->user->id;
                if($this->view->user->role < 6)
                {
                    $comment->type_user = '3';
                }
            }
            else
            {
                $comment->type_user = '1';
            } 
            $comment->save();
            $this->TTWarranty->clean();
            $message = 'Gửi bình luận thành công';
        }
        $data['message']            = $message;
        $data['errorcode']          = (!empty($result))?0:1;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    //DỊCH VỤ HỖ TRỢ “TƯ VẤN MIỄN PHÍ"
    public function formcallAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error=0;
        $result = array();
        $message='Gửi thông tin thành công';
        $TC=new Application_Model_DbTable_Tm_Call();
        if($id=$this->_getParam("id")){
            $id=$this->filter->injectSql($id);
            $gioi_tinh_gl=$this->_getParam("gender");
            $name=$this->filter->injectSql($this->_getParam("name"));
            $phone=$this->filter->injectSql($this->_getParam("phone"));
            if(empty($name)){
                $message['name']="vui lòng nhập tên của bạn";
                $error=1;
            }
            if(empty($phone)){
                $message['phone']="vui lòng nhập số điện thoại";
                $error=1;
            }
            if(empty($error))
            {
                $news=$TC->fetchNew();
                $news->name         = $result['name']        =  $name;
                $news->gender       = $result['gender']      =  $gioi_tinh_gl;
                $news->phone        = $result['phone']       =  $phone;
                $news->created      = $result['created']     =  date("Y-m-d H:i:s");
                $news->cid_product  = $result['cid_product'] =  $id;
                $news->save();
            }
        }
        $data['message']            = $message;
        $data['errorcode']          = $error;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    //Danh sách siêu thị còn hàng
    public function branchproductAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $TTStore=new Application_Model_DbTable_Tm_Store();
        $message = '';
        $error   = '';
        if($id=$this->_getParam("id")){
            $id=$this->filter->injectSql($id);
            $this->view->product=$product=$this->TTDefaultProduct->fetchRow("id=$id");
            $product=$this->TTDefaultProduct->DetailNew($product['alias'],null);
            if(empty($product)){
                $message = 'Không tìm thấy sản phẩm';
                $error   = '1';
            }
        }else{
            $message = 'Id sản phẩm bị rổng';
            $error   = '1';
        }
        $server=$_SERVER["SERVER_NAME"];
        $options = array();
        $options['cache_wsdl'] = WSDL_CACHE_NONE;
        $options['soap_version']=SOAP_1_1;
        if($server=='dienmaycholon.local'){
            $client = new Zend_Soap_Client("http://192.168.1.100:8686/tonkho.svc?WSDL",$options);
    
        }else{
            $client = new Zend_Soap_Client("http://112.78.12.245:8686/tonkho.svc?WSDL",$options);
        }
        $sql="";
        try {
            $e= $client->GetTonItemAllSite(
                    array(
                        "ItemId"=>"{$product['sap_code']}",
                        "distinationName"=>"PD",
                        "mKey"=>"5AE0582CAB0643565BD166C566404243"
                    )
            );
            if(!empty($e->GetTonItemAllSiteResult)){
                $value= json_decode($e->GetTonItemAllSiteResult);
        
                if(!empty($value)){
                    foreach ($value as $v){
                        if(!empty($v->SiteId)){
                            if (strpos($v->SiteId,'DC') !== false) {
                                $u='H001';
                            }else{
                                $u=$v->SiteId;
                            }
                            $sql=$sql." code LIKE '{$u}' OR";
                        }
                    }
                }
            }
            if($sql!=""){
                $sql=substr($sql, 0,strlen($sql)-2);
            }
        } catch (Exception $x) {
            echo 'not connect';
            exit;
        }
        if($sql==""){
            $this->view->store=$TTStore->fetchAll("id=4",'is_area ASC');
            $this->view->list_1=array("0"=>"Tất Cả Khu Vực");
            $this->view->list_1[1]="TP Hồ Chí Minh";
            $this->view->list_2=array("0"=>"Tất Cả Quận/Huyện");
            $this->view->list_2[4]='Quận 5';
        }else{
            $this->view->store=$TTStore->fetchAll($sql,'is_area ASC');
            $this->view->list_1=array("0"=>"Tất Cả Khu Vực");
            $this->view->list_2=array("0"=>"Tất Cả Quận/Huyện");
            foreach($this->view->store as $store){
                if($store['is_area']=='1'){
                    $this->view->list_1[1]="TP Hồ Chí Minh";
                }
                if($store['is_area']=='2'){
                    $this->view->list_1[2]="Các Tỉnh Miền Tây";
                }
                if($store['is_area']=='3'){
                    $this->view->list_1[3]="Các Tỉnh Miền Trung";
                }
                $this->view->list_2[$store['id']]=$store['name'];
            }
            if($this->_request->isPost()){
                $chinh_nhanh_1=$this->_getParam("chinh_nhanh_1");
                if(!empty($chinh_nhanh_1)){
                    $this->view->list_2=array("0"=>"Tất Cả Quận/Huyện");
                    $this->view->store=$TTStore->fetchAll("(".$sql." ) AND is_area=$chinh_nhanh_1",'is_area ASC');
                    foreach($this->view->store as $store){
                        $this->view->list_2[$store['id']]=$store['name'];
                    }
                    $this->view->ch1=$chinh_nhanh_1;
                }
            }
        }
        $result = array();
        if(!empty($product))
        {
            $result['product']['name']          = $product['name'];
            $result['product']['discount']      = $product['discount'];
            $result['product']['saleprice']     = $product['saleprice'];
        }
        if(!empty($this->view->store))
        {
            $result['number_store'] = count($this->view->store);
            foreach ($this->view->list_1 as $item) {
                $result['area'][]       = $item;
            }
            foreach ($this->view->list_2 as $item) {
                $result['county'][]     = $item;
            }
            foreach ($this->view->store as $store)
            {
                $_array_store                   = array();
                $_array_store['is_area']        = $store->is_area;
                $_array_store['id']             = $store->id;
                $_array_store['code']           = $store->code;
                $_array_store['address']        = $store->address;
                $_array_store['phone']          = $store->phone;
                $_array_store['fax']            = $store->fax;
                $_array_store['email']          = $store->email;
                $_array_store['area']           = $store->area;
                $_array_store['description']    = $this->stripTagsNew($store->description);
                $_array_store['summary']        = $this->stripTagsNew($store->summary);
                $_array_store['status']         = $store->status;
                $_array_store['time1']          = $store->time1;
                $_array_store['time2']          = $store->time2;
                $_array_store['opening']        = $store->opening;
                $_array_store['open_soon']      = $store->open_soon;
                $_array_store['date_start']     = $store->date_start;
                $_array_store['keyword']        = $store->keyword;
                $_array_store['name']           = $store->name;
                $result['store'][]              = $_array_store;
            }
        }
        $data['message']            = $message;
        $data['errorcode']          = $error;
        $data['data']               = $result;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    public function installmentAction(){
        $message =  '';
        $error   = 0;
        if(!isset($this->SESSIONDM->number_order)){
            $this->SESSIONDM->number_order=$this->filter->dateToNumber(date("d-m-Y H:i:s")).$this->filter->randd(8);
        }
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $this->view->style_page="order_installment";
        if($cate=$this->_getParam("cate")){
            $TCate=$this->TTDefaultCate->getCurrentCateChild($cate,$this->view->DTCATE_CHILD,$this->view->filter);
            if($TCate){
            $alias=$this->_getParam("alias");
            $color=$this->_getParam("color",null);
            $this->view->cate=$TCate;
            $this->view->parent=$this->view->DTCATE_PARENT[$TCate['cid_parent']];
            $this->view->product_detail=$product=$this->TTDefaultProduct->DetailNew($alias,$color);
            if($product){   
                $this->view->promotionText=$this->TTDefaultPromotion->getGiftText($product['cid_res']);
                $this->view->Payment=$this->TTDefalutPayment->getAllPyament($product['cid_cate']);
                $this->view->headTitle( " Mua trả góp ".$product['name']);
                if($this->_getParam("send",0)){
                    $myid=$this->filter->injectSql($this->_getParam('myid'));
                    $mymonth=$this->filter->injectSql($this->_getParam('mymonth'));
                    $mypercent=$this->filter->injectSql($this->_getParam('mypercent'));
                    $mytype=$this->filter->injectSql($this->_getParam('mytype'));
                    $name=$this->filter->injectSql($this->_getParam('name'));
                    $phone=$this->filter->injectSql($this->_getParam('phone'));
                    $email=$this->filter->injectSql($this->_getParam('email'));
                    $city=$this->filter->injectSql($this->_getParam('city'));
                    $address=$this->filter->injectSql($this->_getParam('address'));
                    $agree=$this->_getParam("agree");
                    $sex=$this->_getParam("sex");
                    if(empty($myid)){
                        $message             .= " ID Not Empty";
                        $error                = 1;
                    }   
                    if(empty($mymonth)){
                        $message             .= " month Not Empty";
                        $error                = 1;
                    }   
                    if(empty($mypercent)){
                        $message             .= " percent Not Empty";
                        $error                = 1;
                    }
                    if(empty($name)){
                        $message             .= " name Not Empty";
                        $error                = 1;
                    }
                    if(empty($phone)){
                        $message             .= " phone Not Empty";
                        $error                = 1;
                    }
                    if(empty($agree)){
                        $message             .= " agree Not Empty";
                        $error                = 1;
                    }
                    if(empty($error)){
                        $this->putorder($product);
                        $Data_Month=$this->TTDefalutPayment->getAllPay($mytype);
                        $coin= floor($product['discount']*$mypercent/100);
                        $rate= $this->TTDefalutPayment->getRate($Data_Month,$mymonth);
                        $money_month= floor( ($product['discount']-$coin)*$rate );
                        $this->SESSIONDM->payment=
                        array(
                            "installment"=>array(
                                'type'=> ( (!empty($mytype) ) ? $mytype : "acs" ),
                                'percent'=>$this->filter->toPrice($coin),
                                "time_ins"=>$mymonth,
                                "price"=>$this->filter->toPrice($product['discount']*1-$coin),
                                "price_month"=>$this->filter->toPrice($money_month),
                                "gender"=> $sex
                            ),
                            "info"=>array(
                                "name"=>$name,
                                "email"=>$email,
                                "phone"=>$phone,
                                "address"=>$address,
                                "city"=>$city,
                                "sex"=>$sex,
                            )
                        );
                        $message =  'Lập đơn hành mua trả góp thành công';
                        $error   = 0;
                        $this->addorder();
                        $data['message']            = $message;
                        $data['errorcode']          = $error;
                        $data['data']               = $result;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        return;
                    }else{
                        $this->view->form=array(
                            "name"=>$name,
                            "phone"=>$phone,
                            "email"=>$email,
                            "city"=>$city,
                            "address"=>$address,
                            "sex"=>$sex
                        );
                    }
                }
            }
        }
        if($cate=$this->_getParam("cate"))
        {
            $result['parent_name']                  = $this->view->parent['name'];
            $result['cate_name']                    = $this->view->cate['name'];
            $result['product_detail']['name']       = $this->view->product_detail['name'];
            $result['product_detail']['image']      = $this->checkPhoto($this->filter->get_image_product_lcd_home($this->product_detail['myid'],"big"));
            $result['product_detail']['isprice']    = $this->view->product_detail['isprice'];
            $result['product_detail']['discount']   = $this->view->product_detail['discount'];
            $result['product_detail']['saleprice']  = $this->view->product_detail['saleprice'];
            $result['product_detail']['discount']   = $this->view->product_detail['discount'];
            $result['product_detail']['is_icon']    = $this->view->product_detail['is_icon'];
            if($this->view->promotionText)
            {
                if(!empty($this->view->promotionText['total']))
                {
                    $result['promotion']['total'] = $this->view->promotionText['total'];
                    if(!empty($this->view->promotionText['data']))
                    {
                        foreach($this->view->promotionText['data'] as $text)
                        {
                            $_array_promotion                   = array();
                            $_array_promotion['name']           = $text['name'];
                            $_array_promotion['description']    = $text['description'];
                            $result['promotion']['data'][]      = $_array_promotion;
                        }
                    }
                    else
                    {
                        $result['promotion']['data']            = array();
                    }
                }
                else
                {
                    $result['promotion'] = array(
                        'total' => 0,
                        'data'  => array(),
                    );
                }
            }
            else
            {
                $result['promotion'] = array(
                        'total' => 0,
                        'data'  => array(),
                    );
            }
            foreach($this->view->Payment as $payment)
            {
                $_array_payment                 = array();
                $_array_payment['id'] = $payment['id'];
                $_array_payment['permin']       = $payment['permin'];
                $_array_payment['permax']       = $payment['permax'];
                $_array_payment['step']         = $payment['step'];
                $_array_payment['cid_pay_type'] = $payment['cid_pay_type'];
                $_array_payment['pay']          = $this->TTDefalutPayment->getAllPay($payment['cid_pay_type']);
                $result['payment']              = $_array_payment;
            }
        }
    }
    else
    {
        $message = 'Không có dữ liệu';
        $error   = 1;
    }
    $message = 'Lấy dữ liệu thành công';
    $data['message']            = $message;
    $data['errorcode']          = $error;
    $data['data']               = $result;
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    //Tạo đơn hàng
    function addorder(){
        if(isset($this->SESSIONDM->shopcart)){
            $TTshopcart=new Application_Model_DbTable_Or_Shopcart();
            $TTShopcartproduct=new Application_Model_DbTable_Or_Shopcartproduct();
            $removeTTshopcart=$TTshopcart->fetchRow("id=".$this->SESSIONDM->shopcart);
            $removeTTshopcart->delete();
            $removeTTshopcartproduct=$TTShopcartproduct->delete("cid_shop=".$this->SESSIONDM->shopcart);
            unset($this->SESSIONDM->shopcart);
        }
        $TDetail=new Application_Model_DbTable_Or_Detail();
        $TGift=new Application_Model_DbTable_Or_Gift();
        $TOrder=new Application_Model_DbTable_Or_Order();
        $TShipping=new Application_Model_DbTable_Or_Shippingaddress();
        $TBilling=new Application_Model_DbTable_Or_Billingaddress();
        $TInstallment=new Application_Model_DbTable_Or_Orderinstallment();
        $this->view->style_page="order";
        $this->view->number_order=$number_order= $this->SESSIONDM->number_order;
        if(empty($this->SESSIONDM->payment['info']['ship'])){
            $this->SESSIONDM->payment['info']['ship']='1';
        }
        $MyOrder = $this->SESSIONDM->payment;
        //ORDER 
        $N_Order = $TOrder->fetchNew();
        $N_Order->code_order = $number_order;
        $N_Order->id_cus = (!empty($_SESSION['id_user'])) ? $_SESSION['id_user'] : 0;
        $N_Order->total_or=$this->getTotal();
        $N_Order->date_bill=date("Y-m-d H:i:s");
        $N_Order->date_ship = (!empty($MyOrder['info']['getday']))? date("Y-m-d H:i:s",strtotime(str_replace("/", "-", $MyOrder['info']['getday'])) )  : date("Y-m-d H:i:s");
        $N_Order->order_info=$MyOrder['info']['note'];
        $N_Order->approved='0';
        $N_Order->type_payment= 4;
        $N_Order->cid_bank=0;
        $N_Order->flag='2';
        $N_Order->session='1';
        if(!empty($this->SESSIONDM->coupon["coupon"])){
            $N_Order->code_coupon=$this->SESSIONDM->coupon['coupon'];
        }
        if(!empty($this->SESSIONDM->coupon["voucher"])){
            $N_Order->code_voucher=$this->SESSIONDM->coupon['voucher'];
            foreach ($this->SESSIONDM->cart as $key => $product) {
                $this->SESSIONDM->cart[$key]['disprice']=$product['saleprice'] - $product['discount'];
            }
        }
        $N_Order->dis_price=$this->getDisPrice();
        if($MyOrder['info']['ship']=='1')
        {
            $N_Order->pay_type = "Mua qua mobie app , Thanh toán tại nhà ";
        }
        elseif($MyOrder['info']['ship']=='2')
        {
            $N_Order->pay_type = "Mua qua mobie app , Thanh toán tại siêu thị  ";
        }
        elseif($MyOrder['info']['ship']=='3')
        {
            $N_Order->pay_type = "Mua qua mobie app , Thanh toán online ";
        }
        else
        {
            $N_Order->pay_type = "Mua qua mobie app , Thanh toán tại nhà ";
        }
        $N_Order->save();
        $N_Billing=$TBilling->fetchNew();
        $N_Billing->cid_order=$N_Order->id;
        $N_Billing->fullname=$MyOrder['info']['name'];
        $N_Billing->phone=$MyOrder['info']['phone'];
        $N_Billing->email=$MyOrder['info']['email'];
        $N_Billing->address=$MyOrder['info']['myaddress'];
        $N_Billing->district=$MyOrder['info']['district'];
        $N_Billing->city=$MyOrder['info']['city'];
        $N_Billing->save();
        $N_Shipping=$TShipping->fetchNew();
        $N_Shipping->cid_order=$N_Order->id;
        $N_Shipping->fullname=$MyOrder['info']['name'];
        $N_Shipping->phone=$MyOrder['info']['phone'];
        $N_Shipping->email=$MyOrder['info']['email'];
        $N_Shipping->company=$MyOrder['info']['name_company'];
        $N_Shipping->address=$MyOrder['info']['myaddress'];
        $N_Shipping->distict=$MyOrder['info']['district'];
        $N_Shipping->city=$MyOrder['info']['city'];
        $N_Shipping->addresscompany=$MyOrder['info']['address_company'];
        $N_Shipping->faxcompany=$MyOrder['info']['code_company'];
        $N_Shipping->storebranch=$MyOrder['info']['storebranch'];
        $N_Shipping->save();
        foreach($this->SESSIONDM->cart as $product){
            $N_Detail=$TDetail->fetchNew();
            $N_Detail->cid_order=$N_Order->id;
            $N_Detail->cid_product=$product['id'];
            $N_Detail->cid_color= (empty($product['cid_color'])? '0' : $product['cid_color']);
            $N_Detail->amount=$product['limit'];
            $N_Detail->sale_price=$product['discount'];
            $N_Detail->dis_price=$product['disprice'];
            $N_Detail->total=$product['discount']*$product['limit'];
            $N_Detail->choose=(empty($product['type_promo'])? '0' : $product['type_promo']);
            $N_Detail->cid_promotion= (empty($product['cid_promotion'])? '0' : $product['cid_promotion']);
            if(!empty($this->SESSIONDM->coupon["coupon"])){
                $N_Detail->code_coupon=$this->SESSIONDM->coupon['coupon'];
            }
            $N_Detail->cid_gift=(empty($product['cid_gift'])? '0' : $product['cid_gift']);
            $N_Detail->cid_supplier=(empty($product['cid_supplier'])? 1 : $product['cid_supplier']);
            $N_Detail->is_success='0';
            $N_Detail->save();
            if($only_gift=$this->TTDefaultPromotion->getGiftOnly($product['cid_res'])){
                foreach($only_gift as $v){
                    $N_Detail_Gift=$TGift->fetchNew();
                    $N_Detail_Gift->cid_detail=$N_Detail->id;
                    $N_Detail_Gift->cid_gift=$v['id'];
                    $N_Detail_Gift->type='0';
                    $N_Detail_Gift->save();
                }
            }
            if($only_gift=$this->TTDefaultPromotion->getGiftForProduct($product['cid_res'])){
                foreach($only_gift as $v){  
                    $N_Detail_Gift=$TGift->fetchNew();
                    $N_Detail_Gift->cid_detail=$N_Detail->id;
                    $N_Detail_Gift->cid_gift=$v['id'];
                    $N_Detail_Gift->type='1';
                    $N_Detail_Gift->save();
                }   
            }
        }
        if(!empty($MyOrder['installment'])) {
            $N_Installment=$TInstallment->fetchNew();
            $N_Installment->type=$MyOrder['installment']['type'];
            $N_Installment->percent=$MyOrder['installment']['percent'];
            $N_Installment->price=$MyOrder['installment']['price'];
            $N_Installment->time_ins=$MyOrder['installment']['time_ins'];
            $N_Installment->price_month=$MyOrder['installment']['price_month'];
            $N_Installment->gender=$MyOrder['installment']['sex'];
            $N_Installment->name=$MyOrder['info']['name'];
            $N_Installment->cmnd=" 0 ";
            $N_Installment->address=$MyOrder['info']['myaddress'];
            $N_Installment->phone=$MyOrder['info']['phone'];
            $N_Installment->email=$MyOrder['info']['email'];
            $N_Installment->is_order=$N_Order->id;
            $N_Installment->work=0;
            $N_Installment->save();
            $N_Order->is_ins='1';
            $N_Order->save();
        }
        $this->view->headTitle( " Đơn hàng đã hoàn tất ");
        if($this->filter->checkEmailType($this->SESSIONDM->payment['info']['email']) ){
            $Buy_Together = array();
            $contentemail = file_get_contents ( PUBLIC_PATH . "/email/maìl.htm" );
            $file_quantam = file_get_contents ( PUBLIC_PATH . "/email/product/other.html" );
            $file_product = file_get_contents ( PUBLIC_PATH . "/email/product/product.html" );
            $file_total   = file_get_contents ( PUBLIC_PATH . "/email/product/total.html" );
            $typepayment  = (!empty($this->SESSIONDM->payment['installment']))? "Mua trả góp" :" Mua tại nhà ";
            $vat          = "";
            $value_email  = array(
                "IDORDER"=>$number_order,
                "URL"=>"http://www." . $_SERVER ['SERVER_NAME'] . "/",
                "NAME"=>$this->SESSIONDM->payment['info']['name'],
                "EMAIL"=>$this->SESSIONDM->payment['info']['email'],
                "ADDRESS"=>$this->SESSIONDM->payment['info']['address'],
                "CITY"=>$this->view->valuecity[$this->SESSIONDM->payment['info']['city'] ],
                "TYPEPAYMENT"=>$typepayment,
                "DATEBILL"=>date("d/m/Y"),
                "GETDATE"=>date("d/m/Y"),
                "COMMENT"=>$this->SESSIONDM->payment['info']['note'],
                "VAT"=>$vat,
            );
            $email_product_content="";
            $email_total_product=0;
            $email_total_price=0;
            $Buy_Together=array();
            foreach($this->SESSIONDM->cart as $p){
                if(empty($Buy_Together)){
                    $one_tag=$this->TTDefaultProduct->getOneTag($p['id']);
                    $Buy_Together=$this->TTDefaultProduct->getSearch($one_tag['name'],12);
                }
                $x = array(
                    "PRODUCT_URL"=>"/".$this->filter->toAlias2($p['namecate'])."/".$this->filter->toAlias2($p['name']),
                    "PRODUCT_SAP"=>$TProduct['sap_code'],
                    "PRODUCT_TK"=>$this->filter->toPrice($p['saleprice']-$p['discount']),
                    "PRODUCT_PERCENT"=>100-floor($p['discount']/$p['saleprice']*100),
                    "PRODUCT_LIMIT"=>$p['limit'],
                    "PRODUCT_IMG"=>$this->filter->get_image_product_lcd($p['id'],"small"),
                    "PRODUCT_NAME"=>$p['name'],
                    "PRODUCT_DISCOUNT"=>$this->filter->toPrice($p['discount'])
                );
                $a = $file_product;
                foreach($x as $key=>$value){
                     $a = str_replace ($key,$value , $a );
                }
                $email_product_content .= $a;
                $email_total_product++;
                $email_total_price=$email_total_price+($p['discount']*$p['limit']);
            }
            $value_email['CONTENT'] = $email_product_content;
            $value_email['TONGDH']  = str_replace(array("PRODUCT_TOTAL","PRODUCT_PRICE"), array($email_total_product,$this->filter->toPrice($email_total_price) ), $file_total);
            $spquantamemail         = "";
            foreach($Buy_Together as $p){
                $p=(empty($p['attrs']))? $p : $p['attrs'];
                $x=array(
                    "PRODUCT_URL"=>"/".$this->filter->toAlias2($p['namecate'])."/".$this->filter->toAlias2($p['name']),
                    "PRODUCT_IMG"=>$this->filter->get_image_product_lcd($p['myid'],"small"),
                    "PRODUCT_NAME"=>$p['name'],
                    "PRODUCT_SALEPRICE"=>$this->filter->toPrice($p['saleprice']),
                    "PRODUCT_DISCOUNT"=>$this->filter->toPrice($p['discount'])
                );
                $a= $file_quantam;
                foreach($x as $key=>$value){
                    $a = str_replace ($key,$value , $a );
                }
                $spquantamemail .= $a;
            }
            $value_email['SPQUANTAM']=$spquantamemail;
            foreach($value_email as $key=>$value){
                $contentemail = str_replace ($key,$value , $contentemail );
            }
            try 
            {
                $this->view->Mail_Config=$this->Mail_Config = array (
                    //  'ssl'=>"tls",
                        'port' => $this->view->DTGeneral->smtp_port,
                        'auth' => 'login',
                        'username' => $this->view->DTGeneral->smtp_username,
                        'password' => $this->view->DTGeneral->smtp_password
                );
                $transport = new Zend_Mail_Transport_Smtp ( $this->view->DTGeneral->smtp_host, $this->Mail_Config );
                $mail = new Zend_Mail ("UTF-8");
                $mail->setBodyHtml ( $contentemail );
                $mail->setFrom ( $this->view->DTGeneral->smtp_username, 'Điện máy chợ lớn' );
                $mail->addTo ( $data['info']['email'],$data['info']['name'] );
                $mail->addCc("sales@dienmaycholon.vn",'Điện máy chợ lớn');
                $mail->setSubject ( 'Đơn hàng - Điện máy chợ lớn' );
                if($mail->send($transport)){
                }
            }catch(Exception $e){
            }
        }
        unset($this->SESSIONDM->cart);
        unset($this->SESSIONDM->coupon);
        unset($this->SESSIONDM->number_order);
    }

    public function getTotal(){
        $total=0;
        foreach($this->SESSIONDM->cart as $product){
             $total=$total+$product['discount']*$product['limit'];
        }
        return $total;
    }

    public function getDisPrice(){
        $total=0;
        foreach($this->SESSIONDM->cart as $product){
            $total=$total+ ($product['disprice']*1);
        }
        return $total;
    }

    public function putorder($product,$limit=1){
        if($product)
        {
            if(array_key_exists($product['myid'], $this->SESSIONDM->cart))
            {
                $this->SESSIONDM->cart[$product['myid']]["limit"]=$this->SESSIONDM->cart[$product['myid']]['limit']*1+1;
            }
            else
            {
                $put_product=array(
                    "id"=>$product['myid'],
                    "name"=>$product['name'],
                    "code"=>$product['code'],
                    "sap_code"=>$product['sap_code'],
                    "discount"=>$product['discount'],
                    "saleprice"=>$product['saleprice'],
                    "cid_color"=>$product['cid_color'],
                    "limit"=>$limit,
                    "namecate"=>$product['namecate'],
                    "cid_res"=>$product['cid_res'],
                    "cid_supplier"=>$product['cid_supplier'],
                    "isprice"=>$product['isprice'],
                    "disprice"=>"",
                    "cid_promotion"=>"",
                    "type_promo"=>""
                );      
                if(!empty($product['cid_promotion']))
                {
                    $put_product['cid_promotion']=$product['cid_promotion'];
                    $put_product['type_promo']='6';
                    $put_product['limit_quantity']='0';         
                }
                else
                {
                    if($getTypePromotion=$this->TTDefaultPromotion->getTypePromotion($product['cid_res']))
                    {
                        $put_product['cid_promotion']=$getTypePromotion['cid_promotion'];
                        $put_product['type_promo']=$getTypePromotion['type_promo'];
                        $put_product['limit_quantity']=$getTypePromotion['limit_quantity'];
                        if($put_product['limit_quantity']=='1')
                        {
                            $put_product['limit']=1;
                        }   
                    }
                }
                if(!empty($product['coupons']))
                {
                    $put_product['coupons']=$product['coupons'];
                    $put_product['discountcoupons']=$product['discountcoupons'];
                }   
                $put_product['disprice']=$product['saleprice'] - $product['discount'];  
                $this->SESSIONDM->cart[$product['myid']]=$put_product;
            } 
        }
    }

    public function examplecartAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $_data = array(
            'product'    => array(
                array(
                    'id'    => '11078',
                    'color' =>  null,
                    'qt'    =>  1, 
                ),
                array(
                    'id'    => '141223',
                    'color'     =>  null,
                    'qt'    =>  2,
                ),
                array(
                    'id'    => '10083',
                    'color'     =>  324,
                    'qt'    =>  3,
                ),
            ),
            'customer'   => array(
                    'sex'               => 1,
                    'name'              => 'Nguyễn Văn C',
                    'phone'             => '0910000000',
                    'email'             => 'nguyenleduykhang29111994@gmail.com',
                    'note'              => 'test 1',
                    'ship'              => '4',
                    'city'              => '0',
                    'district'          => '0',
                    'myaddress'         => null,
                    'code_company'      => null,
                    'name_company'      => null,
                    'address_company'   => null,
                    'branch'            => 1,
                    'storebranch'       => 'lô g,chung cư hùng vương,p.11',
                    'getday'            => '08/01/2018',
            ),
        );
        echo json_encode($_data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
    //index order
    public function citydistrictAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        $this->TTDefaultProduct->getLocationSelect();
        $_number_city = 0;
        foreach ($this->TTDefaultProduct->getLocationSelect() as $key => $value) {
            if($key !=0)
            {
                $result['city'][$_number_city]['id']        = $key;
                $result['city'][$_number_city]['name']  = $value;
                $district=$this->TTDefaultProduct->getLocationSelect($key);
                $_number_district = 0;
                foreach ($district as $key1=>$value1) {
                    if($key1 !=0)
                    {   
                        $result['city'][$_number_city]['district'][$_number_district]['id'] = $key1;
                        $result['city'][$_number_city]['district'][$_number_district]['name'] = $value1;
                        ++$_number_district;
                    }
                }
                ++$_number_city;
            }
        }
        $message                        = 'Thành phố và quận huyện';
        $data_json['message']           = $message;
        $data_json['errorcode']         = 0;
        $data_json['data']              = $result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    //index order
    public function districtAction()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $result = array();
        if($id=$this->_getParam("id")){
                $district=$this->TTDefaultProduct->getLocationSelect($id);
                
        }
        foreach ($district as $key=>$value) {
            $result['district'][$key] = $value; 
        }
        $message                        = 'Quận huyện theo thành phố';
        $data_json['message']           = $message;
        $data_json['errorcode']         = 0;
        $data_json['data']              = $result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    public function cartAction()
    {
        $message = 'them don hang ko thanh cong';
        $error   = 0;
        if(!isset($this->SESSIONDM->number_order)){
            $this->SESSIONDM->number_order = $this->filter->dateToNumber(date("d-m-Y H:i:s")).$this->filter->randd(8);
        }
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $_data = file_get_contents("php://input");
        if(!empty($_data ))
        {
            $_data = json_decode($_data);
            if(!empty($_data->product) && !empty($_data->customer))
            {
                foreach ($_data->product as $item) {
                    $_alias     = (!empty($item->id))?$item->id:null;
                    $_color     = (!empty($item->color))?$item->color:null;
                    $_number    = (!empty($item->qt))?$item->qt:null;
                    $this->addproducttocart($_alias,$_color,$_number);
                }
                $data['sex']=(!empty($this->filter->injectSql($_data->customer->sex)))?$this->filter->injectSql($_data->customer->sex):null;
                $data['name']=(!empty($this->filter->injectSql($_data->customer->name)))?$this->filter->injectSql($_data->customer->name):null;
                $data['phone']=(!empty($this->filter->injectSql($_data->customer->phone)))?$this->filter->injectSql($_data->customer->phone):null;
                $data['email']=(!empty($this->filter->injectSql($_data->customer->email)))?$this->filter->injectSql($_data->customer->email):null;
                $data['note']=(!empty($this->filter->injectSql($_data->customer->note)))?$this->filter->injectSql($_data->customer->note):null;
                $data['ship']=(!empty($this->filter->injectSql($_data->customer->ship)))?$this->filter->injectSql($_data->customer->ship):null;
                $data['city']=(!empty($this->filter->injectSql($_data->customer->city)))?$this->filter->injectSql($_data->customer->city):null;
                $data['district']=(!empty($this->filter->injectSql($_data->customer->district)))?$this->filter->injectSql($_data->customer->district):null;
                $data['myaddress']=(!empty($this->filter->injectSql($_data->customer->myaddress)))?$this->filter->injectSql($_data->customer->myaddress):null;
                $data['code_company']=(!empty($this->filter->injectSql($_data->customer->code_company)))?$this->filter->injectSql($_data->customer->code_company):null;
                $data['name_company']=(!empty($this->filter->injectSql($_data->customer->name_company)))?$this->filter->injectSql($_data->customer->name_company):null;
                $data['address_company']=(!empty($this->filter->injectSql($_data->customer->address_company)))?$this->filter->injectSql($_data->customer->address_company):null;
                $data['branch']=(!empty($this->filter->injectSql($_data->customer->branch)))?$this->filter->injectSql($_data->customer->branch):null;
                $data['storebranch']=(!empty($this->filter->injectSql($_data->customer->storebranch)))?$this->filter->injectSql($_data->customer->storebranch):null;
                $data['getday']=(!empty($this->filter->injectSql($_data->customer->getday)))?$this->filter->injectSql($_data->customer->getday):null;
                if(empty($data['name'])){
                    $error         = 1;
                    $message      .= 'Vui lòng nhập tên';
                }
                if(empty($data['phone'])){
                    $error         = 1;
                    $message      .= 'Vui lòng nhập số điện thoại';
                }
                if(empty($error)){
                    $this->SESSIONDM->payment = array(
                                                    "installment"=>0,
                                                    "info"=>$data
                                                );


                    $this->addorder();
                    $message       = 'Thêm đơn hàng thành công';
                    // $message    = "test";
                }
            }
        }

        $data_json['message']           = $message;
        $data_json['errorcode']         = $error;
        $data_json['data']              = '';
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    public function addproducttocart($alias=null, $color=null , $number=1)
    {
        if(!empty($alias))
        {
            $color=$this->_getParam("color",null);
            $product=$this->TTDefaultProduct->DetailNewById($alias,$color);
            $product['cid_color']=$color;
            if($onlinebefore=$this->_getParam("onlinebefore")){
                $onlinebefore=$this->filter->injectSql($onlinebefore);
                $PromoSpecial=$this->TTPromotionSpecial->fetchRow("id=$onlinebefore");
                if(!empty($PromoSpecial)){
                    if($PromoSpecial['type']=='1'){
                        $product['discount']=$PromoSpecial['price']*1-$PromoSpecial['content'];
                    }else{
                        $product['discount']=round($PromoSpecial['price']*(100-$PromoSpecial['content']*1)/100);
                    }   
                    $product['cid_promotion']=$PromoSpecial['id'];
                    $product['saleprice']=$PromoSpecial['saleprice'];
                }   
            }
            $this->putorder($product,$number);
            if($buytogether=$this->_getParam("buytogether")){
                foreach($buytogether as $bt){
                    $BTproduct=$this->TTDefaultProduct->DetailNew($bt);
                    $this->putorder($BTproduct);
                }
            }
        }
    }

    public function commentarticleAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error   = 0;
        $message = '';
        if($id=$this->_getParam("id")){
            $this->view->id=$id;
            if($this->_request->isPost()){
                $token=$this->_getParam("token");
                if($token==$_SESSION['token']){
                    $name=$this->filter->injectSql($this->_getParam("name"));
                    $email=$this->filter->injectSql($this->_getParam("email"));
                    $content=$this->filter->injectSql($this->_getParam("content"));
                    $gender=$this->_getParam("gender");
                    $picture=$_FILES['picture'];
                    if(empty($name)){
                        $error    = 1;
                        $message .= ' name not empty '; 
                    }
                    if(empty($content)){
                        $error    = 0;
                        $message .= ' content name not empty '; 
                    }
                    if(empty($error)){
                        $news=$this->Model_Artreview->fetchNew();
                        $news->name=$name;
                        $news->email=$email;
                        $news->gender=$gender;
                        $news->comment=$content;
                        if($this->view->user){
                            $news->cid_user=$this->view->user->id;
                            if($this->view->user->role != 9){
                                $news->is_user='3';
                            }
                        }
                        else
                        {
                            $news->cid_user='0';
                            $news->is_user='1';
                        }
                        $news->date_created=date("Y-m-d H:i:s");
                        $news->status='1';
                        $news->likes='0';
                        $news->cid_article=$id;
                        $news->cid_parent=0;
                        $news->save();
                        if($this->filter->check_File($picture['name'],"png|jpg")){
                            if(filesize($picture['tmp_name']) < 1024*2*1024){                              
                                if(!$this->view->user){
                                    move_uploaded_file($picture['tmp_name'],PICTURE_PATH."/user/news_".$news->id.".png");
                                }
                                else
                                {
                                    move_uploaded_file($picture['tmp_name'],PICTURE_PATH."/user/user_".$this->view->user->id.".png");
                                }
                            }
                        }     
                    }
                }
            }
            $order=$this->view->order=$this->_getParam("order",1);
            if($order==1){
                $order_by="likes DESC";
            }
            else
            {
                $order_by="id DESC";
            }
            $list=Zend_Paginator::factory($this->Model_Artreview->fetchAll("status='1' AND cid_parent=0 AND cid_article=$id",$order_by));
            $list->setCurrentPageNumber($this->_getParam("page",1));
            $list->setItemCountPerPage(10);
            $list->setPageRange(5);
            $this->view->review=$list;
        }
        $_array_review = array();
        $count         = 0;
        $message       = 'Không có dữ liệu';
        if(count($this->view->review)>0)
        {
            foreach($this->view->review as $review)
            {
                $child=$this->Model_Artreview->fetchAll("status='1' AND cid_parent=".$review['id'],'likes DESC');
                if(!empty($review['cid_user']))
                {
                    $_array_review[$count]['photo_customer'] = $this->checkPhoto(PICTURE_URL.'/user/user_'.$review['cid_user'].'.png');
                }
                else
                {
                    if(is_file(PICTURE_PATH."/user/news_".$review['id'].".png"))
                    {
                        $_array_review[$count]['photo_customer'] = $this->checkPhoto(PICTURE_URL.'/user/news_'.$review['id'].'.png');
                    }
                    else
                    {
                        $_array_review[$count]['photo_customer'] = $this->checkPhoto('/public/dienmaycholon/general/img/icon_account.png');
                    }
                }
                $_array_review[$count]['id']            = $review['id'];
                $_array_review[$count]['name']          = $review['name'];
                $_array_review[$count]['comment']       = $review['comment'];
                $_array_review[$count]['likes']         = $review['likes'];
                $_array_review[$count]['date_created']  = $this->filter->showTime($review['date_created']);
                $_array_review[$count]['child']         = array();
                if(count($child)>0)
                {
                    $count_child = 0;
                    foreach($child as $c)
                    {
                        if(!empty($c['cid_user']))
                        {
                            $_array_review[$count]['child'][$count_child]['photo_customer'] = $this->checkPhoto(PICTURE_URL.'/user/user_'.$c['cid_user'].'.png');   
                        }
                        else
                        {
                            if(is_file(PICTURE_PATH."/user/news_".$c['id'].".png"))
                            {
                                $_array_review[$count]['child'][$count_child]['photo_customer'] = $this->checkPhoto(PICTURE_URL.'/user/news_'.$c['id'].'.png'); 
                            }
                            else
                            {
                                $_array_review[$count]['child'][$count_child]['photo_customer'] = $this->checkPhoto('/public/dienmaycholon/general/img/icon_account.png');
                            }
                        }
                        $_array_review[$count]['child'][$count_child]['id']              = $c['id'];
                        $_array_review[$count]['child'][$count_child]['name']            = $c['name'];
                        $_array_review[$count]['child'][$count_child]['comment']         = $c['comment'];
                        $_array_review[$count]['child'][$count_child]['likes']           = $c['likes'];
                        $_array_review[$count]['child'][$count_child]['date_created']    = $this->filter->showTime($c['date_created']);
                        $count_child++;
                    }
                }
                ++$count;
            }
            $count         = 0;
            $message       = 'Lấy dữ liệu thành công';
        }
        $data_json['message']           = $message;
        $data_json['errorcode']         = $error;
        $data_json['data']              = $_array_review;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);   
    }

    // đánh gía sản phẩm
    public function saverateAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $message = 'Không có dữ liệu sản phẩm';
        $error   = 0;
        if($rate=$this->_getParam("rate")){
           $id=$this->_getParam("id");
           $this->TTDefaultProduct->updateVote($rate,$id);
           $message = 'Đánh gía thành công';
           $error   = 1;
        }
        $data_json['message']           = $message;
        $data_json['errorcode']         = $error;
        $data_json['data']              = $_array_review;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);   
    }

    // đánh gía sản phẩm
    public function ordercustomerAction(){
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $message = 'Lấy danh sách đơn hàng của khách hàng';
        $error   = 0;
        $this->view->style_page    = "user/order";
        $this->view->TTOrder       = new Default_Model_Order();
        // if(!$_SESSION['id_user']){
        //   $message = "Chưa có thông tin tài khoản";
        // }                        
        $this->view->headTitle("Lịch sử đơn hàng - Điện máy chợ lớn");
        $this->Or_Order = new Application_Model_DbTable_Or_Order();
        // $cusid = $this->user->id;
        $cusid  = (!empty($_SESSION['id_user']))?$_SESSION['id_user']:0;
        $select = $this->Or_Order->select();
        $select->where('id_cus = ?', $cusid);  
        $select->order("date_bill DESC");
        $list=Zend_Paginator::factory($select);
        $list->setCurrentPageNumber($this->_request->getParam('page',1));
        $list->setItemCountPerPage(10);
        $list->setPageRange(5);
        $_array_result = array();
        $_number       = 0;
        foreach($list as $data)
        {
            $_array_result[$_number]['code_order'] = $data['code_order'];
            $date_bill                             = strtotime($data['date_bill']);
            $date_bill                             = date('d-m-Y', $date_bill);
            $_array_result[$_number]['date_bill']  = $date_bill;
            $pr                                    = $this->view->TTOrder->getAllProduct($data['id']);
            $_number_product                       = 0;
            foreach($pr as $p)
            {
                $_array_result[$_number]['product'][$_number_product]['namecate'] = $p['namecate'];
                $_array_result[$_number]['product'][$_number_product]['name']     = $p['name'];
                ++$_number_product;
            }
            ++$_number;
        }
        $data_json['message']           = $message;
        $data_json['errorcode']         = $error;
        $data_json['data']              = $_array_result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);   
    }

    //lấy thông tin đơn hàng 
    public function loadorderAction ()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->Or_Order = new Application_Model_DbTable_Or_Order();
        $cid_order =$this->_request->getParam("madh");
        $message = 'Mã đơn hàng không tồn tại';
        $result  = array();
        $error   = 1;
        if(!empty($cid_order) && is_numeric($cid_order))
        {
            $this->order = $this->Or_Order->fetchRow("code_order = '$cid_order'");
            if(count($this->order)>0)
            {
                $result['code_order']   = $this->order['code_order'];
                $date_bill              = $this->order['date_bill'];                            
                $date_bill              = strtotime($date_bill);
                $date_bill              = date('d-m-Y', $date_bill);
                $result['date_bill']    = $date_bill;
                $result['pay_type']     = $this->order['pay_type'];
                $date_ship              = $this->order['date_ship'];
                $date_ship              = strtotime($date_ship);
                $date_ship              = date('d-m-Y', $date_ship);
                $result['date_ship']    = $date_ship;
                $result['order_info']   = $this->order['order_info'];
                $result['total_or']     = $this->filter->toPice($this->order['total_or']);
                $result['approved']     = $this->order['approved'];
                $message                = 'Trả về kết quả đơn hàng thành công';
                $error                  = 0;
            }
        }
        $data_json['message']           = $message;
        $data_json['errorcode']         = $error;
        $data_json['data']              = $result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    //chi tiet don hang
    public function orderdetailAction ()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $this->Or_Order                 = new Application_Model_DbTable_Or_Order();
        $this->order_history            = new Application_Model_DbTable_Or_Orderhistory();
        $this->ship                     = new Application_Model_DbTable_Or_Shippingaddress();
        $this->bill                     = new Application_Model_DbTable_Or_Billingaddress();
        $this->Or_Gift                  =  $this->view->Or_Gift           = new Application_Model_DbTable_Or_Gift();
        $this->promotion_text           =  $this->view->promotion_text = new Application_Model_DbTable_Promotion_Text();
        $this->view->promotion_online   = $this->promotion_online = new Application_Model_DbTable_Promotion_Online();
        $this->view->promotion_press    = $this->promotion_press = new Application_Model_DbTable_Promotion_Press();
        $this->view->promotion_deal     = $this->promotion_deal = new Application_Model_DbTable_Promotion_Deal();
        $cid_order                      = $param                = $this->_request->getParam("cid_order");
        $listOrder                      = $this->Or_Order->fetchRow("code_order = $cid_order");
        if(count($listOrder)>0)
        {
            $cid_cus   = $listOrder->id_cus;
            $cid_order = $listOrder->code_order;
            $id = $listOrder->id;
            $date_bill = $ngaymua = $listOrder->date_bill;
            $date_bill = strtotime($date_bill);
            $date_bill = date('d-m-Y', $date_bill);
            
            $date_ship = $listOrder->date_ship;
            $date_ship = strtotime($date_ship);
            $date_ship = date('d-m-Y', $date_ship);
            
            $pay_type  = $listOrder->pay_type;
            $order_info= $listOrder->order_info;
            $approved  = $listOrder->approved;
            $total_or  = $listOrder->total_or;
            @$ngay_1 =$this->order_history->fetchRow("cid_order = $id and status=1")->date_added;
            @$ngay_4 =$this->order_history->fetchRow("cid_order = $id and status=4")->date_added;
            @$ngay_6 =$this->order_history->fetchRow("cid_order = $id and status=6")->date_added;
            @$ngay_7 =$this->order_history->fetchRow("cid_order = $id and status=7")->date_added;
            @$ngay_2 =$this->order_history->fetchRow("cid_order = $id and status=2")->date_added;
            @$ngay_3 =$this->order_history->fetchRow("cid_order = $id and status=3")->date_added;
            $stop='';
            if($ngaymua <$ngay_3 && ($ngay_3<$ngay_1 or empty($ngay_1)) ){ $huy = 12; $stop ="stop1";}            
            else if($ngay_1 < $ngay_3 && ($ngay_3<$ngay_4 or empty($ngay_4))) { $huy = 30; $stop ="stop2";}
            else if($ngay_4 < $ngay_3 && ($ngay_3<$ngay_6 or empty($ngay_6))){ $huy = 50; $stop ="stop3";}
            else if($ngay_6 < $ngay_3 && ($ngay_3<$ngay_7 or empty($ngay_7))){ $huy = 68; $stop ="stop4";}
            else if($ngay_7 < $ngay_3 && ($ngay_3<$ngay_2 or empty($ngay_2))){ $huy = 85; $stop ="stop5";}
            
            
            if($approved==0){ $phan = 5*1;}
            else if($approved==1){ $phan = 11.3*2;}
            else if($approved==5 or $approved==4){ $phan = 13.7*3;}
            else if($approved==6){ $phan = 14.7*4;}
            else if($approved==7){ $phan = 15.4*5;}
            else if($approved==2){ $phan = 16.6*6;}
            else{ $phan=$huy;}
            $flag = $listOrder->flag;
            // if($flag==0)
            // {
                $sql_bill=$this->bill->fetchRow("cid_order = $id");
                $fullname_bill = $sql_bill->fullname;
                $phone_bill = $sql_bill->phone;
                $email_bill = $sql_bill->email;
                $address_bill = $sql_bill->address;
                $district_bill = $sql_bill->district;
                if(!empty($district_bill)) 
                {
                    $district_bill  = $this->TTLocation->getID($district_bill)->name; 
                }                   
                $city_bill = $sql_bill->city;
                if(!empty($city_bill)) 
                {
                    $city_bill  = $this->TTLocation->getID($city_bill)->name;
                }
            // }
            $session = $listOrder->session;
            if($session =='1')
            {
                $text_session = "Buổi sáng";
            }else
            {
                $text_session = "Buổi chiều";
            }
            $cid_bank = $listOrder->cid_bank;
            $sql_ship = $this->ship->fetchRow("cid_order = $id");
            $fullname_ship = $sql_ship->fullname;
            $phone_ship = $sql_ship->phone;
            $email_ship = $sql_ship->email;
            $address_ship = $sql_ship->address;
            $district_ship = $sql_ship->distict;
            if(!empty($district_bill)) 
            {
                @$district_ship  = $this->TTLocation->getID($district_ship)->name;  
            }                              
            $city_ship = $sql_ship->city;
            if(!empty($city_ship)) 
            {
                @$city_ship  = $this->TTLocation->getID($city_ship)->name;
            }
            $t = $this->filter->toPice($listOrder->total_or);
            $code_coupon = $listOrder->code_coupon;
        }
        else
        {
            $message    = 'Mã đơn hàng không tồn tại';
            $result     = array();
            $error      = 1;
            $data_json['message']           = $message;
            $data_json['errorcode']         = $error;
            $data_json['data']              = $result;
            echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            return;
        }
        if($this->view->user) 
        { 
            $idu    = $this->view->user->id;
        }
        $TDetail    = new Application_Model_DbTable_Or_Detail();
        $sql_detail = $TDetail->fetchAll("cid_order = $id");
        $message    = 'Mã đơn hàng không tồn tại';
        $result     = array();
        $error      = 1;
        $result['time'][0]['name']              = 'Ngày mua hàng';
        $result['time'][0]['date']              = $ngaymua;
        $result['time'][1]['name']              = 'Ngày xác nhận đơn hàng';
        $result['time'][1]['date']              = $ngay_1;
        $result['time'][2]['name']              = 'Ngày xử lý đơn hàng';
        $result['time'][2]['date']              = $ngay_4;
        $result['time'][3]['name']              = 'Ngày đóng gói đơn hàng';
        $result['time'][3]['date']              = $ngay_6;
        $result['time'][4]['name']              = 'Ngày giao hàng';
        $result['time'][4]['date']              = $ngay_7;
        $result['time'][5]['name']              = 'Ngày giao hàng thành công';
        $result['time'][5]['date']              = $ngay_2;
        // $result['time']['xac_nhan_don_hang']         = $ngay_1;
        // $result['time']['xu_ly']                     = $ngay_4;
        // $result['time']['dong_goi_don_hang']         = $ngay_6;
        // $result['time']['giao_hang']                 = $ngay_7;
        // $result['time']['giao_hang_thanh_cong']  = $ngay_2;
        // if(!empty($cid_cus))
        // {
            $result['customer_infor']['fullname_bill']  = $fullname_bill;
            $result['customer_infor']['address_bill']   = $address_bill;
            $result['customer_infor']['district_bill']  = $district_bill;
            $result['customer_infor']['city_bill']      = $city_bill;
            $result['customer_infor']['phone_bill']     = $phone_bill;
            $result['customer_infor']['email_bill']     = $email_bill;
        // }
        // else
        // {
        //  $result['customer_infor']   = array();
        // }
        $result['order_infor']['date_bill']         = $date_bill;
        $result['order_infor']['date_ship']         = $date_ship;
        $result['order_infor']['approved_note']     = $approved;
        $result['order_infor']['pay_type']          = $pay_type;
        $result['order_infor']['order_info']        = $order_info;
        if(!empty($code_coupon))
        {
            $this->coupon   = new Application_Model_DbTable_Tm_Coupon();
            $dis_price_s    = $this->coupon->getcode($code_coupon)->dis_price;                    
            $dis_percent_s  = $this->coupon->getcode($code_coupon)->dis_percent;
            $result['coupon_infor']['code_coupon']      = $code_coupon;
            $result['coupon_infor']['dis_price_s']      = $dis_price_s;
            $result['coupon_infor']['dis_percent_s']    = $dis_percent_s;
        }
        else
        {
            $result['coupon_infor'] = array();
        }
        if($sql_detail)
        {
            $_number_product        = 0;
            foreach($sql_detail as $detail)
            {
                $sap_pro    = $this->TTDefaultProduct->fetchRow("id=".$detail['cid_product'])->sap_code;
                $name_pro   = $this->TTDefaultProduct->fetchRow("id=".$detail['cid_product'])->name;
                $id         = $this->TTDefaultProduct->fetchRow("id=".$detail['cid_product'])->id;
                // $gia         = $this->filter->toPice($detail['sale_price']);
                // $tong        = $this->filter->toPice($detail['total']);
                $r          = $this->filter->get_image_product_lcd($id,'small');
                if($detail['cid_gift']=='1'){
                    $a = $this->Or_Gift->fetchAll("cid_detail=".$detail['id']);
                    if(!empty($a))
                    {
                        $qua='';
                        foreach($a as $gifts)
                        {
                            if($gifts['type']=='1')
                            {
                                $Pro_Gift = new Application_Model_DbTable_Pro_Gift();  
                                $a1 = $Pro_Gift->fetchRow("id=".$gifts['cid_gift']);
                                $qua = $qua. "-".$a1->name."<br>";
                            }
                            else
                            {  
                                if($detail['choose']=='2')
                                {
                                    $qua  = $qua."- ".$this->promotion_online->getID($gifts['cid_gift'])->description."<br>";
                                }
                                elseif($detail['choose']=='3')
                                {
                                    $qua  = $qua."- ".$this->promotion_press->getID($gifts['cid_gift'])->description."<br>";
                                }
                                elseif($detail['choose']=='4' || $detail['choose']=='1')
                                {
                                    $qua  = $qua."- ".$this->promotion_text->getID($gifts['cid_gift'])->name."<br>";
                                }
                            }
                        }
                    }
                }
                else
                {
                    $qua = "Không có quà";
                }
                $result['order_product'][$_number_product]['name_pro']      = $name_pro;
                $result['order_product'][$_number_product]['photo']         = $this->checkPhoto($r);
                $result['order_product'][$_number_product]['gift']          = $qua;
                $result['order_product'][$_number_product]['amount']        = $detail['amount'];
                $result['order_product'][$_number_product]['price']         = $detail['sale_price'];
                $result['order_product'][$_number_product]['totalprice']    = $detail['total'];
                ++$_number_product;
            }
            $message = 'Trả về chi tiết đơn hàng thành công';
            $error = 0;
        }
        $data_json['message']           = $message;
        $data_json['errorcode']         = $error;
        $data_json['data']              = $result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    // Lấy version của app mobile
    public function getconfigAction ()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        // $query = "select version_ios,version_android,is_show_ads from app_mobile limit 1";
        $query          = "select * from app_mobile limit 1";
        $result         = $this->Server->getversion();
        $popup          = $this->view->TTDefaultProduct->getPopup();
        $result['bannerAds']['link_photo']      = $this->checkPhoto('/public/picture/popup/'.$popup['picture_name']);
        $result['bannerAds']['link_content']    = $popup['link'];
        $message                                = "Trả về phiên bản di động thành công";
        $error                                  = 0;
        $data_json['message']                   = $message;
        $data_json['errorcode']                 = $error;
        $data_json['data']                      = $result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

 //    // TẠO TÀI KHOẢN KHÁCH HÀNG
    // public function registerAction(){
    //  //{"username":"nhanvien01","password":"1234567","email":"nguyenleduykhang29111994@gmail.com","fullname":"nhân viên 01","phone":"0917171049","myday":"29","mymonth":"11","myyear":"1994","city":"30","address":"1 Lương Ngọc Quyến , Phường 5 , Quận Bình Thạnh , TP. Hồ Chí Minh"}
    //  Zend_Layout::resetMvcInstance();
    //  $this->_helper->viewRenderer->setNoRender();
    //  $start          =   microtime(true);
    //  $error          =   0;
 //        $resultjson  =   'Tạo tài khoản không thành công';
        // $data            =   file_get_contents("php://input");
        // $data            =   json_decode($data);
        // $username        =   $data->username;
        // $password        =   $data->password;
        // $email           =   $data->email;
        // $fullname        =   $data->fullname;
        // $phone           =   $data->phone;
     //    $myday           =   $data->myday;
     //    $mymonth         =   $data->mymonth;
     //    $myyear      =   $data->myyear;
     //    $city            =   $this->filter->injectSql($data->city);
     //    $address         =   $this->filter->injectSql($data->address);
 //        $resultjson  =   array();
        // if(empty($username))
        // {
        //     $error       =   1;
        //     $message     =   "Chưa nhập username";
        // }
        // else
        // {
        //     if(strlen($username)<7)
        //     {
        //      $error      =   1;
        //      $message    =   "Vui lòng nhập nhiều hơn 6 ký tự";
        //     }
        //     else
        //     {
        //         $check_user  =   $this->TTUsers->fetchRow("username = '{$username}'");
        //         if(!empty($check_user)){
        //          $error      =   1;
        //          $message    =   "Tên đăng nhập đã tồn tại";
        //         }
        //     }
        // }
        // if(empty($password))
        // {
        //  $error              =   1;
        //     $message             =   "Vui lòng nhập mật khẩu của bạn ";
        // }
        // else
        // {
           //  if(strlen($password) < 7)
           //  {
           //   $error          =   1;
        //      $message        =   "Vui lòng nhập mật khẩu trên 6 ký tự ";
           //  }
        // }
        // if(!$this->filter->checkEmailType($email))
        // {
        //  $error              =   1;
        //     $message             =   "Vui lòng nhập đúng định dạng email ";
        // }
        // else
        // {
        //      $check_email=$this->TTUsers->fetchRow("email = '{$email}'");
        //     if(!empty($check_email))
        //     {
        //      $error              =   1;
        //      $message            =   "Địa chỉ E-mail đã tồn tại ";
        //     }
        // }
        // if(empty($fullname))
        // {
        //  $error              =   1;
        //     $message             =   "Vui lòng nhập đúng họ và tên của bạn ";
        // }
        // if(empty($phone))
        // {
        //  $error              =   1;
        //     $message             =   "Vui lòng nhập số điện thoại của bạn ";
        // }
        // if(empty($city))
        // {
        //  $error              =   1;
        //     $message             =   " Vui lòng nhập địa chỉ của bạn ";
        // }
 //        if($error == 0)
 //        {
 //            $new                     =   $this->TTUsers->fetchNew();
 //            $new->username           =   $resultjson['username']         =   $username;
 //            $new->password           =   $resultjson['password']         =   md5($password);
 //            $new->email          =   $resultjson['email']            =   $email;
 //            $new->phone          =   $resultjson['phone']            =   $phone;
 //            $new->fullname           =   $resultjson['fullname']         =   $fullname;
 //            $new->city               =   $resultjson['city']             =   $city;
 //            $new->status             =   1;
 //            $new->date_login         =   date("Y-m-d");
 //            $new->date_cre           =   date("d-m-Y");
 //            $new->address            =   $resultjson['address']          =   $address;
 //            $new->birthday           =   $resultjson['birthday']         =   $myday."/".$mymonth."/".$myyear;
 //            $message                 =   "Tạo tài khoản thành công";
 //            $new->save();
 //         }
    //  $datajson['message']     = $message;
    //  $datajson['errorcode']   = $error;
    //  $datajson['data']        = $resultjson;
    //  echo json_encode($datajson, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); 
    // }

    // Thay đổi thông tin customer
    public function edituserAction ()
    {
        Zend_Layout::resetMvcInstance();
        $this->_helper->viewRenderer->setNoRender();
        $error          =   0;
        $message        =   'Thay đổi thông tin khách hàng thành công';
        $data           =   file_get_contents("php://input");
        $data           =   json_decode($data);
        $username       =   $data->username;
        $password       =   $data->password;
        $email          =   $data->email;
        $fullname       =   $data->fullname;
        $phone          =   $data->phone;
        $myday          =   $data->myday;
        $mymonth        =   $data->mymonth;
        $myyear         =   $data->myyear;
        $city           =   $this->filter->injectSql($data->city);
        $address        =   $this->filter->injectSql($data->address);
        $result         =   array();
        if(empty($username))
        {
            $error      =   1;
            $message    =   "Chưa nhập username";
        }
        else
        {
            if(strlen($username)<7)
            {
                $error      =   1;
                $message    =   "Vui lòng nhập nhiều hơn 6 ký tự";
            }
            else
            {
                $check_user=$this->TTUsers->fetchRow("username LIKE '{$username}' AND id != ".$_SESSION['id_user']);
                if(!empty($check_user)){
                    $error      =   1;
                    $message    =   "Tên đăng nhập đã tồn tại";
                }
            }
        }
        if(empty($password))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập mật khẩu của bạn ";
        }
        else
        {
            if(strlen($password) < 7)
            {
                $error          =   1;
                $message        =   "Vui lòng nhập mật khẩu trên 6 ký tự ";
            }
        }
        if(!$this->filter->checkEmailType($email))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập đúng định dạng email ";
        }
        else
        {
            $check_email=$this->TTUsers->fetchRow("email LIKE '{$email}'  AND id != ".$_SESSION['id_user']);
            if(!empty($check_email))
            {
                $error              =   1;
                $message            =   "Địa chỉ E-mail đã tồn tại ";
            }
        }
        if(empty($fullname))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập đúng họ và tên của bạn ";
        }
        if(empty($phone))
        {
            $error              =   1;
            $message            =   "Vui lòng nhập số điện thoại của bạn ";
        }
        if(empty($city))
        {
            $error              =   1;
            $message            =   " Vui lòng nhập địa chỉ của bạn ";
        }
        if($error == 0)
        {
            $updated=$this->TTUsers->fetchRow("id=".$_SESSION['id_user']);
            $updated->username=$username;
            if(!empty($password))
            {
                $updated->password=md5($password);
            }
            $updated->email=$email;
            $updated->phone=$phone;
            $updated->fullname=$fullname;
            if(empty($city))
            {
                $updated->city=$city;
            }
            $updated->address=$address;
            $updated->birthday=$myday."/".$mymonth."/".$myyear;
            $updated->save();
        }
        $data_json['message']                   = $message;
        $data_json['errorcode']                 = $error;
        $data_json['data']                      = $result;
        echo json_encode($data_json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
}
