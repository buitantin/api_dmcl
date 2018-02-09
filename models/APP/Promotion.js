const Config = require("../../config/prestodb");
const General_Presto = require("../../config/general");
const PrestoClient = require('prestodb');
 
const  Presto = new PrestoClient({
		   url: General_Presto.url+":"+General_Presto.port, 
		   user: Config.user,
		   nextUriTimeout: 200 
		});
const Schema = Config.catalog+"."+Config.schema+".";

const empty = require("empty");

module.exports.getGiftText= async function(cid_res){
	
   var Product_Detail_Promotion= await   Presto.sendStatement(
   		"SELECT type_promo "+
   		"FROM "+Schema+"pro_promotion_product "+
   		" WHERE cid_product="+cid_res+" AND status='0 ' AND (type_promo='2 ' OR type_promo='3 ' OR type_promo='4 ')  ORDER BY type_promo ASC LIMIT 1")
		  .then((result) => {
		  		if(result.data){
		  			return result.data.reverse();
		  		}
		  		return null;
		        
		    })
		  .catch((error) => {
		        return error;
		    });
		  for(k in Product_Detail_Promotion){
		  	return Product_Detail_Promotion[k][0];
		  }

}


public function getGiftText($id){
			$total_price=0;
			$result=array();
			$product_deal_flash=$this->fetchRow("cid_product=$id and status='0' AND type_promo IN (1,5)");

			if(empty($product_deal_flash)){
				
					$product=$this->fetchRow("cid_product=$id and status='0' AND type_promo IN (2,3,4)"," type_promo ASC");


					if(!empty($product)){
						$type_promo=$product->type_promo;
						 if($type_promo=='2'){
				                $sql_online = "
				                    SELECT 
				                  		  d.description,d.price_gift
									FROM 
										 pro_promotion_product as c 
										 INNER JOIN promo_online as d ON d.id=c.cid_promotion
									WHERE 
									 d.active='1' AND c.type_promo='2'  AND d.description!=''
									AND c.cid_product=$id 
				                ";

				                $data_product=$this->TT_DB->fetchRow($sql_online);

				                if(!empty($data_product)){
				                	$total_price=$total_price+$data_product['price_gift'];
				                	
				                	
				                }
				              //  return array("total"=>$total_price,"data"=>$result);
				              
				            }elseif($type_promo=='3'){
				                $sql_press = "
				                    SELECT 
					                    d.price_gift,
										d.description
									FROM 
											pro_promotion_product as c 
												INNER JOIN  promo_press as d ON d.id=c.cid_promotion
									WHERE   d.active='1' AND c.type_promo='3' AND d.description!=''
									AND c.cid_product=$id 
				                ";
				                
				                $data_product=$this->TT_DB->fetchRow($sql_press);
				                if(!empty($data_product)){
				                	$total_price=$total_price+$data_product['price_gift'];
				                }
				            }elseif($type_promo=='4'){
				                $sql="
									SELECT

									d.price_gift,d.name,
									d.description
									FROM 
										pro_promotion_product as c 
										INNER JOIN promo_text as d ON d.id=c.cid_promotion
									WHERE   d.active='1' AND c.type_promo='4'  AND d.description!=''
									AND c.cid_product=$id  AND d.price_gift !=''
								";

								$data_product=$this->TT_DB->fetchAll($sql);
								
				                if(!empty($data_product)){
				                	foreach ($data_product as $value) {
				                		$total_price=$total_price+$value['price_gift'];
				             		
				                		
				                	}
				                	
				                	
				                }
				              
				            }
				                
							
					}
				}

			
		         	if(!empty($total_price)){
		         		
		         		 return array("total"=>$total_price);
		         	}
		          
			return false;
	}