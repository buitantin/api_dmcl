const presto = require("presto-client");
const MOrder=require("../models/Order");
const MOrderMysql=require("../models/OrderMysql");
const Q=require('q');


exports.update_or_installment=function(req,res){
		
	var Tpromise=[];

		var id_order = req.query.id_order ? req.query.id_order.toString() : null;
		var type= req.query.type ? req.query.type*1 : 1 ;
		var note = req.query.note ? req.query.note : "";

		if(type===1){

			MOrderMysql.Statement(
				"UPDATE or_installment SET approve='1', note ='"+note+"' WHERE is_order='"+id_order+"'",
				 			function(error, rows, columns){ 
									if(error){ return res.send(error); }
									return res.send({message:"Đã cập nhật đơn hàng thành công: "+id_order})  			 	 
							}
			 			
			);
		}else{

				var step_first=MOrderMysql.Statement(
						"UPDATE or_order SET approved=3 WHERE id='"+id_order+"'",
				 			function(error, rows, columns){ 
									if(error){ return res.send(error); }
												 	 
							}
				);
				Tpromise.push(step_first);


				


				var step_second = MOrderMysql.Statement(
						"UPDATE or_installment SET status='1', note='"+note+"' WHERE is_order='"+id_order+"'",
				 			function(error, rows, columns){ 
									if(error){ return res.send(error); }
												 	 
							}
				);
				Tpromise.push(step_second);

				Q.all(Tpromise).done(function(){
					 MOrderMysql.Statement(
					"SELECT  cid_order,status FROM or_order_history WHERE cid_order='"+id_order+"' AND  status=3",
					 			function(error, rows, columns){ 

										if(error){ return res.send(error); }
										if(rows && rows.length > 0){
											MOrderMysql.Statement(
													"UPDATE or_order_history SET date_added = now()  WHERE cid_order='"+id_order+"' AND status=3 ",
											 			function(error, rows, columns){ 
																if(error){ return res.send(error); }
																return res.send({message:"1. Đã cập nhật đơn hàng thành công: "+id_order});			 				 	 
														}
											);
										}else{
											MOrderMysql.Statement(
													"INSERT INTO or_order_history(cid_order,status,date_added) VALUES("+id_order+",3,now())",
											 			function(error, rows, columns){ 
																if(error){ return res.send(error); }
																return res.send({message:"2. Đã cập nhật thành công: "+id_order});						 	 
														}
											);
										}
										 
								}
				 			
					);
				
					
				});

		}

}

exports.update_status=function(req,res){
		
		

		var cid_order = req.query.cid_order ? req.query.cid_order : null;
		var status= req.query.status ? req.query.status*1 : null ;
	

		if(cid_order != null && status != null){


				

			
					 MOrderMysql.Statement(
								"SELECT  id FROM or_order WHERE code_order='"+cid_order+"'",
					 			function(error, rows, columns){ 
					 					
										if(error){ return res.send(error); }
										if(rows && rows.length > 0){
											var myid=rows[0].id;
											MOrderMysql.Statement(
													"UPDATE or_order SET approved="+status+"  WHERE code_order='"+cid_order+"' ",
											 			function(error, rows, columns){ 
																if(error){ return res.send(error); }
																MOrderMysql.Statement(
																		" SELECT a.cid_order,a.status "+
																		" FROM  or_order_history AS a INNER JOIN or_order AS b ON a.cid_order=b.id "+
																		" WHERE b.code_order='"+cid_order+"' AND a.status="+status+" ",
																 			function(error, rows, columns){ 
																					if(error){ return res.send(error); }
																					if(rows && rows.length > 0){
																							
																						MOrderMysql.Statement(
																								"UPDATE or_order_history SET date_added = now()  WHERE cid_order="+rows[0].cid_order+" AND status="+status+" ",
																						 			function(error, rows, columns){ 
																											if(error){ return res.send(error); }
																											return res.send({message:"1. Đã cập nhật đơn hàng thành công: "+cid_order,row:rows});			 				 	 
																									}
																						);
																					}else{
																						MOrderMysql.Statement(
																								"INSERT INTO or_order_history(cid_order,status,date_added) VALUES("+myid+","+status+",now())",
																						 			function(error, rows, columns){ 
																											if(error){ return res.send(error); }
																											return res.send({message:"2. thêm mới  thành công: "+cid_order,row:rows});						 	 
																									}
																						);
																					}		 				 	 
																			}
																);	 				 	 
														}
											);
											
										}else{
											return res.send({message:" không có mã đơn hàng "});
										 
										}
									}
				 			
					);
			
				

		}else{
			return res.send({message:"Vui lòng nhập tham số ."});
		}

}
exports.update_stock_num=function(req,res){
		
		

		var stock_num = req.query.stock_num ? req.query.stock_num : null;
		var masap= req.query.masap ? req.query.masap : null ;
		var idnhacungcap= req.query.idnhacungcap ? req.query.idnhacungcap : 1 ;
	

		if(stock_num != null && masap != null && idnhacungcap!= null){


			
					 MOrderMysql.Statement(
								"UPDATE   pro_product  AS a INNER JOIN pro_supplier_product AS b ON b.cid_product = a.id "+
								"  SET b.stock_num="+stock_num+""+
								"   WHERE a.sap_code='"+masap+"' AND b.cid_supplier="+idnhacungcap+" "
								,
					 			function(error, rows, columns){ 
					 					
										if(error){ return res.send(error); }
										return res.send(rows);
									}
				 			
					);
			
				

		}else{
			return res.send({message:"Vui lòng nhập tham số ."});
		}

}
