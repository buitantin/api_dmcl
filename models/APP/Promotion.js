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

