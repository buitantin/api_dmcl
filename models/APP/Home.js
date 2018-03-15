
const Config = require("../../config/prestodb");
const General_Presto = require("../../config/general");
const PrestoClient = require('prestodb');
 
const  Presto = new PrestoClient({
		   url: General_Presto.url+":"+General_Presto.port, 
		   user: Config.user,
		   nextUriTimeout: 200 
		});
const Schema = Config.catalog+"."+Config.schema+".";


module.exports.NewListHome=function(){
	
   return	 Presto.sendStatement("SELECT name,cid,cate_id,item_id,spec_id,level_banner FROM "+Schema+"pro_home_level WHERE status='1 ' ORDER BY position DESC")
		  .then((result) => {
		  		if(result.data){
		  			return result.data.reverse();
		  		}
		  		return null;
		        
		    })
		  .catch((error) => {
		        return error;
		    });
}


module.exports.GetProductHome=async function(data){
	var MyResult =[];
	var value= JSON.parse(data);
	if(value != null && value != undefined){


	for(k in value){
	
		if(value[k].length>0 ){


			var flied_product=" (a.id="+value[k].join(" OR a.id=")+")";
			
			var test= value[k].join(",");

			

				var sql= "SELECT  a.id,a.name,a.cid_series,a.isprice,a.is_icon,a.is_model,a.is_price, "+
						" a.is_red_day,a.is_double_price,a.is_million, "+
						"  a.is_flash_sale,a.is_sale,a.new_description, "+
						" b.discount,  b.saleprice, b.is_promotion, b.id,c.name ,c.alias,a.alias  "+

						" FROM (  "+Schema+"pro_product AS a INNER JOIN "+Schema+"pro_supplier_product AS b ON a.id = b.cid_product  "+
						" INNER JOIN "+Schema+"pro_categories AS c ON c.id= a.cid_cate ) "+
						" WHERE "+
						"  (b.stock_num > 0 OR (b.stock_num = 0 AND a.is_sample='0 ' ) ) "+
						"  AND a.is_status_cate='1 ' AND a.status='1 '  AND b.status='1 '  AND "+
		
						"  "+flied_product+
						//" ORDER BY (a.id,"+test+")	 "+
						" LIMIT 8";
		
					
					

					MyResult.push( await this.fetchAll(sql) ); 
					
				 
			}
			//GET ONLY ONE
			break;
		}		
		return MyResult;				 
	}						
}
module.exports.fetchAll=function(sql){
	
					return	  Presto.sendStatement(sql)
					  .then((result) => {
					  		if(result.data){
					  			return result.data;
					  		}
					  		return null;
					        
					    })
					  .catch((error) => {
					        return error;
					    });
				
}
module.exports.GetSpectHome=function(data){
	return data.split('--');
	var sql= "SELECT DISTINCT a.id AS myid,a.name,a.cid_series,a.isprice,a.is_icon,a.is_model,a.is_price, "+
					" a.is_red_day,a.is_double_price,a.is_million, "+
					"  a.is_flash_sale,a.is_sale,a.new_description, "+
					" check_coupon(a.id,a.cid_cate,2) AS coupons, "+
					" check_coupon(a.id,a.cid_cate,1) AS discountcoupons, "+
					" b.discount,  b.saleprice, b.is_promotion, b.id AS cid_res,c.name AS namecate  "+
					" FROM (  pro_product AS a INNER JOIN pro_supplier_product AS b ON a.id = b.cid_product  "+
					" INNER JOIN pro_categories AS c ON c.id= a.cid_cate )"+
					" WHERE "+
					"  (b.stock_num > 0 OR (b.stock_num = 0 AND a.is_sample='0' ) )"+
					"  AND a.is_status_cate='1' AND a.status='1'  AND b.status='1'  AND "+
					" a.id IN ($value) "+
					" ORDER BY FIELD(a.id,$value)	 "+
					" LIMIT 0,$limit";

	 return	 Presto.sendStatement($sql)
		  .then((result) => {
		  		if(result.data){
		  			return result.data.reverse();
		  		}
		  		return null;
		        
		    })
		  .catch((error) => {
		        return error;
		    });
								 
									
}
module.exports.product_promotion=function(){
		
		return Presto.sendStatement('SELECT id FROM mysql.beta.pro_product ')
		  .then((result) => {
		  	
		        return result;
		    })
		  .catch((error) => {
		        return error;
		    });

}

module.exports.product_promotion_spect=function(){
	
		return Presto.sendStatement('SELECT id FROM mysql.beta.pro_product ')
		  .then((result) => {
		  	
		        return result;
		    })
		  .catch((error) => {
		        return error;
		    });

}

module.exports.product_promotion_banner=function(){
	
		return Presto.sendStatement('SELECT id FROM mysql.beta.pro_product ')
		  .then((result) => {
		  	
		        return result;
		    })
		  .catch((error) => {
		        return error;
		    });

}

