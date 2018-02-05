const presto = require("presto-client");
const fs= require("fs");
const Config = require("../config/prestodb");
const Client = new presto.Client(Config);
module.exports.Query=function(query_id,callback){
	Client.query(query_id, callback);
}
module.exports.Kill=function(query_id){
	Client.kill(query_id,function(err){});
}
module.exports.Nodes=function(opts,callback){
	Client.nodes(opts, callback)
}
module.exports.get_Order=function(stats_callback,callback,success_callback,error_callback){
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
								       	 	" LIMIT 20 "

								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },
								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});
							
							
}
module.exports.get_Order_Installment = function(stats_callback,callback,success_callback,error_callback){

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
								       	 	" LIMIT 20 "

								       	 	,
								      state:  stats_callback,
 									  columns: function(error, data){  },

								 	  data: callback ,
									  success: success_callback,
									  error: error_callback

				
					});

                  
}
