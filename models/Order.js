

const Config = require("../config/prestodb");
module.exports.Query=function(query_id,callback){
	const presto = require("presto-client");
	var Client = new presto.Client(Config);
	Client.query(query_id, callback);
}
module.exports.Kill=function(query_id,callback){
	const presto = require("presto-client");
	var Client = new presto.Client(Config);
	Client.kill(query_id,callback);
}
module.exports.Nodes=function(opts,callback){
	const presto = require("presto-client");
	var Client = new presto.Client(Config);
	Client.nodes(opts, callback)
}
module.exports.get_Order=function(stats_callback,callback,success_callback,error_callback){
	const presto = require("presto-client");
	var Client = new presto.Client(Config);
					 Client.execute({
									 query:   
										  	"SELECT  "+
										  	"	a.code_order,a.date_bill,a.date_ship,a.pay_type,a.total_or,a.order_info, a.dis_price,a.session, " +
                   							"   b.fullname,b.phone,b.email,b.address,f1.name,f2.name, "+
                   							"   c.fullname,c.phone,c.email,  "+
                   							"   c.company ,c.address ,d2.name ,d1.name,  "+
                   							"    c.addresscompany,c.faxcompany  "+
								       	 	" FROM or_order AS a "+
									       	 	" INNER JOIN or_billing_address b ON a.id=b.cid_order "+
										       	" INNER JOIN or_shipping_address c ON c.cid_order=a.id "+

										       	"  LEFT JOIN tm_cities AS d1 ON d1.id=c.city " +
										       	"  LEFT JOIN tm_cities AS d2  ON d2.id=c.distict "+
	                    						
	                    						" LEFT JOIN tm_cities f1 ON f1.id=b.city "+
	                   							" LEFT JOIN tm_cities f2 ON f2.id=b.district " +
								       	 	" WHERE a.approved=0 AND a.is_ins = '0 '   "+
								       	 	" ORDER BY a.date_bill DESC " + 
								       	 	" LIMIT 10 "

								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },
								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});
							
							
}
module.exports.get_Order_Installment = function(stats_callback,callback,success_callback,error_callback){
			const presto = require("presto-client");
				var Client = new presto.Client(Config);

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	"	a.code_order,a.date_bill,a.date_ship,a.pay_type,a.total_or,a.order_info, a.dis_price,a.session,a.id, " +
                   
                   							"   b.fullname,b.phone,b.email,b.address,f1.name,f2.name, "+
                   							"   c.fullname,c.phone ,c.email,  "+
                   							"   c.company,c.address,d2.name,d1.name,  "+
                   							"    c.addresscompany,c.faxcompany,  "+
                   							"    g.type, g.percent, g.price , g.time_ins, g.price_month, g.gender, g.cmnd, g.work, g.sohopdong, g.note "+
								       	 	" FROM or_order AS a "+
									       	 	" INNER JOIN or_billing_address b ON a.id=b.cid_order "+
										       	" INNER JOIN or_shipping_address c ON c.cid_order=a.id "+

										       	"  LEFT JOIN tm_cities AS d1 ON d1.id=c.city " +
										       	"  LEFT JOIN tm_cities AS d2  ON d2.id=c.distict "+
	                    						
	                    						" LEFT JOIN tm_cities f1 ON f1.id=b.city "+
	                   							" LEFT JOIN tm_cities f2 ON f2.id=b.district " +

	                   							" LEFT JOIN or_installment g ON g.is_order=a.id " +

								       	 	" WHERE a.approved=0 AND a.is_ins = '1 '  AND  g.status='0 '  "+

								       	 	" ORDER BY a.date_bill DESC " + 
								       	 	"  LIMIT 10 "

								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
module.exports.get_Order_Detail = function(cid_order,stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	"	c.code_order, c.code_voucher, c.is_ins , a.amount, " +
										  	"  a.sale_price, a.dis_price, a.total, "+
										  	"  b.name, b.sap_code ,   "+
										  	" a.choose , a.code_coupon, a.cid_gift , a.id , a.cid_supplier  "+
										  	
                   
                   							
								       	 	" FROM or_detail AS  a "+
									       	 	" INNER JOIN pro_product AS b on a.cid_product = b.id     "+
									       	 	" INNER JOIN or_order AS c ON a.cid_order = c.id  		  "+

										       	"  LEFT JOIN promo_text AS p1 ON p1.id = a.cid_promotion  " +
										      	" LEFT JOIN promo_press AS p2 ON p2.id = a.cid_promotion  "+
										      	" LEFT JOIN promo_online AS p3 ON p3.id = a.cid_promotion "+
										      	" LEFT JOIN promo_deals AS p4 ON p4.id = a.cid_promotion  "+

								       	 	" WHERE c.code_order = '"+cid_order+"'"+

								       	 	"  LIMIT 1 "

								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
module.exports.get_Or_Detail_Gift = function(id_or_detail,stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" type , cid_detail ,cid_gift  "+
											"  FROM  or_detail_gift "+
											" WHERE cid_detail="+id_or_detail
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
module.exports.get_Supplier = function(id_supplier,stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" fullname, email,phone,address,username,info  "+
											"  FROM  market_supplier "+
											" WHERE id="+id_supplier
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
module.exports.get_General = function(stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" site_name,smtp_username,smtp_password,smtp_port  "+
											"  FROM  tm_general "+
											" WHERE id= 1"
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}

module.exports.get_Data_Supplier = function(stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" fullname, email, phone, address, fax, GPKD, web, is_type, status  "+
											"  FROM  market_supplier "+
											" WHERE id > 1"
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}

module.exports.get_Info_Product = function(sapcode,cid_supplier,stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);

        		
        

				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" a.name, a.code, a.sap_code,  b.discount,b.saleprice , b.cid_supplier, "+
										  	" b.stock_num, b.cid_product, b.description , b.id  "+
											"  FROM  pro_product a "+
											" 	INNER JOIN pro_supplier_product b ON a.id = b.cid_product "+
											"  WHERE  a.sap_code='"+sapcode+"' AND b.cid_supplier="+cid_supplier+
											" LIMIT 1 "
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
module.exports.Information_Supplier = function(cid_supplier,stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);


				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" fullname, email, phone, address, fax, GPKD, web, is_type, status "+
										  	
											"  FROM  market_supplier "+
											
											"  WHERE   id="+cid_supplier+
											" LIMIT 1 "
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
module.exports.get_Data_Sapcode = function(limit,stats_callback,callback,success_callback,error_callback){
				const presto = require("presto-client");
				var Client = new presto.Client(Config);
				 Client.execute({
									 query:   
										  	"SELECT  "+
										  	" a.sap_code "+
										  	
											"  FROM  pro_product  AS a INNER JOIN pro_supplier_product AS b ON a.id=b.cid_product "+
											
											" WHERE b.cid_supplier=1 "+
											"  LIMIT 100"
								
								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}


