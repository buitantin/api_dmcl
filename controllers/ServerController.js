const presto = require("presto-client");
const MOrder=require("../models/Order");
const MOrderMysql=require("../models/OrderMysql");
const Q=require('q');

exports.get_data= function(req,res){
		var TPromise=[];
		var View={};
		let MyStats={};
		let MyColumns={};
		var MyResult=[];


		
	
		 if(req.session.queryidorder != undefined ){
			 	//MOrder.Kill(req.session.queryidorder,function(err){});
				 //	res.send("Bạn vừa mới truy vấn rồi .s");
				 //	return true;
		 }	
		 		MOrder.get_Order(
		 			function(error, query_id, stats){ 
								  			req.session.queryidorder =query_id;
								  			
										      	 
					},
		 			function(error, data, columns, stats){
				 		
										 	 if(error){ res.send(error ); return true;}	
										 	 if(!data){
										 	 	res.send({message:" Không tim thấy đơn hàng "});
										 	 	return true;
										 	 }

										 	 
										  				MyStats=stats;
										  				MyColumns=columns;
											  			for (var i = data.length - 1; i >= 0; i--) {
														  		MyResult.push(
														  			{
														  				   'madonhang'			:data[i][0],
																	        'ngaymua'			:data[i][1],
																	        'ngaygiao'			:data[i][2],
																	        'hinhthucthanhtoan':data[i][3],
																	        'tongdonhang'		:data[i][4],
																	        'ghichu'			:data[i][5],
																	        'tonggiam'  		:data[i][6],
																	        'buoigiaohang'     	:data[i][7],
																	        'bill_fullname'     :data[i][8],
																	        'bill_phone'     	:data[i][9],
																	        'bill_email'    	:data[i][10],
																	        'bill_address'     	:data[i][11],
																	        'bill_city'    		:data[i][12],
																	        'bill_district'    	:data[i][13],
																	        'sh_fullname'     	:data[i][14],
																	        'sh_phone'     		:data[i][15],
																	        'sh_email'    		:data[i][16],
																	        'sh_company'    	:data[i][17],
																	        'sh_address'    	:data[i][18],
																	        'sh_district'     	:data[i][19],
																	        'sh_city'     		:data[i][20],
																	        'sh_addresscompany' :data[i][21],
																	        'sh_faxcompany'    	:data[i][22]
														  			}
														  		);
														  	}
													
												return	res.send(MyResult);
										 			
										 			

													
								},
								function(error,stats){
									 if(error){ res.send(error); return true;}	
									  	 if(stats.rootStage.processedRows === 0){
											 	return res.send({message:" Không có dòng nào "});
											 }	
											  		
								},
								 function(error){
											  		return res.send(error);
											  		return true;
								 }
								 
						  );
			

					


}


exports.get_data_installment= function(req,res){
		var View={};
	
		var TPromise=[];
		
		let MyStats={};
		let MyColumns={};
		var MyResult=[];
		
		
		if(req.session.queryidorderinstallment != undefined ){
		 	// MOrder.Kill(req.session.queryidorderinstallment,function(err){});
		 }	
		

						MOrder.get_Order_Installment(
			 					function(error, query_id, stats){ 
									  			req.session.queryidorderinstallment =query_id;
									  			
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ View.error= error; }	
									
									 
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	res.send({message:" - Không tim thấy đơn hàng trả góp. "});
									 	 	return true;
									 	 }
									  				MyStats=stats;
									  				MyColumns=columns;
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															        'madonhang' 					: data[i][0],
															        'ngaymua' 						: data[i][1],
															        'ngaygiao'						: data[i][2],
															        'hinhthucthanhtoan' 			: data[i][3],
															        'tongdonhang'  					: data[i][4],
															        'ghichu'  						: data[i][5],
															        'tonggiam' 						: data[i][6],
															        'buoigiaohang'    				: data[i][7],
															        'bill_fullname'   				: data[i][8],
															        'bill_phone'    				: data[i][9],
															        'bill_email'    				: data[i][10],
															        'bill_address'     				: data[i][11],
															        'bill_city'    					: data[i][12],
															        'bill_district'  				: data[i][13],
															        'sh_fullname'   				: data[i][14],
															        'sh_phone'    					: data[i][15],
															        'sh_email'    					: data[i][16],
															        'sh_company'   					: data[i][17],
															        'sh_address'    				: data[i][18],
															        'sh_district'   				: data[i][19],
															        'sh_city'     					: data[i][20],
															        'sh_addresscompany'  			: data[i][21],
															        'sh_faxcompany'   				: data[i][22],
															        'loaitragop'					: data[i][23],
															        'muctratruoc'					: data[i][24],
															        'sotienconlai'  				: data[i][25],
															        'thoigiantragop' 				: data[i][26],
															        'sotienhangthang' 				: data[i][27],
															        'gender'  						: data[i][28],
															        'cmnd' 							: data[i][29],		
															        'work' 							: data[i][30],
															        'sohopdong' 					: data[i][31],
															        'ghichutragop'				    : data[i][32]
													  			}


													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}		
								  	 if(stats.rootStage.processedRows === 0){
									 	return res.send({message:" Không có dòng nào "});
									 }	
										  		
							},
							 function(error){
										  		return res.send({message:" - Không tim thấy đơn hàng trả góp. "});
									 	 		return true;
							 }
						);	
				
				

}


exports.get_order_detail=function(req,res){
		let MyStats={};
		let MyColumns={};
		var MyResult=[];

		var cid_order = req.query.cid_order ? req.query.cid_order : 1;

		if(cid_order === 1){
			return res.send({message:" - Vui lòng nhập đúng mã đơn hàng "});
		}
		var MyResult=[];
		
		

						MOrder.get_Order_Detail(
								cid_order,
			 					function(error, query_id, stats){ 
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ View.error= error; }	
									
									 
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	res.send({message:" - Không tim thấy đơn hàng. "});
									 	 	return true;
									 	 }
									  				
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															          'madonhang' 					: data[i][0],
															          'code_voucher' 				: data[i][1],
															          'tragop' 						: data[i][2],
															           'soluong' 					: data[i][3],
															           'giasp' 						: data[i][4],
															           'giagiam' 					: data[i][5],
															           'tong' 						: data[i][6],
															           'tensp' 						: data[i][7],
															           'sap_code' 					: data[i][8],
															           'loaikm' 					: data[i][9],
															           'macoupon' 					: data[i][10],
															           'quatang' 					: data[i][11],
															           'id_or_detail' 				: data[i][12],
															           'tennhacungcap' 				: data[i][13],
															           'id_order' 					: data[i][14],
															           'tenctkm' 					: data[i][15]
															     
													  			}

													  
													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}		 
								  if(stats.rootStage.processedRows === 0){
								 	return res.send({message:" Không có dòng nào "});
								 }	 		
							},
							 function(error){
										  		return res.send(error);	
							 }
						);	
}
exports.get_or_detail_gift=function(req,res){
		let MyStats={};
		let MyColumns={};
		var MyResult=[];

		var id_or_detail = req.query.id_or_detail ? req.query.id_or_detail : 1;

		if(id_or_detail === 1){
			return res.send({message:" - Vui lòng nhập đúng mã  "});
		}
		var MyResult=[];
		
	
						MOrder.get_Or_Detail_Gift(
								id_or_detail,
			 					function(error, query_id, stats){ 
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ return res.send(error); }	
									
									 	
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	return res.send({message:" - Không tim thấy. "});
									 	 
									 	 }
									  				
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															          'cid_detail' 				: data[i][0],
															          'cid_gift' 				: data[i][1],
															          'loaikmgift' 				: data[i][2],
															      
													  			}

													  
													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}	
								 if(stats.rootStage.processedRows === 0){
								 	return res.send({message:" Không có dòng nào "});
								 }	
							},
							 function(error){
										  		return res.send(error);	
							 }
						);
}
exports.get_supplier=function(req,res){
		let MyStats={};
		let MyColumns={};
		var MyResult=[];

	var id_supplier = req.query.id_supplier ? req.query.id_supplier : 0;

		if(id_supplier === 0){
			return res.send({message:" - Vui lòng nhập   "});
		}
		var MyResult=[];
		
	
						MOrder.get_Supplier(
								id_supplier,
			 					function(error, query_id, stats){ 
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ return res.send(error); }	
									
									 	
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	return res.send({message:" - Không tim thấy. "});
									 	 
									 	 }
									  				
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															          'fullname' 				: data[i][0],
															          'email' 				: data[i][1],
															          'phone' 				: data[i][2],
															          'address' 				: data[i][3],
															          'username' 				: data[i][4],
															           'info' 				: data[i][5]

													  			}

													  
													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}	
								 if(stats.rootStage.processedRows === 0){
								 	return res.send({message:" Không có dòng nào "});
								 }	
							},
							 function(error){
										  		return res.send(error);	
							 }
						);
}




exports.get_general=function(req,res){
					let MyStats={};	
					let MyColumns={};
					var MyResult=[];
						MOrder.get_General(
			 					function(error, query_id, stats){ 
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ return res.send(error); }	
									
									 	
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	return res.send({message:" - Không tim thấy. "});
									 	 
									 	 }
									  				
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															          'site_name' 			: data[i][0],
															          'smtp_username' 		: data[i][1],
															          'smtp_password' 		: data[i][2],
															          'smtp_port' 			: data[i][3]
															        

													  			}

													  
													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}	
								 if(stats.rootStage.processedRows === 0){
								 	return res.send({message:" Không có dòng nào "});
								 }	
							},
							 function(error){
										  		return res.send(error);	
							 }
						);
}

exports.get_data_supplier=function(req,res){
					let MyStats={};	
					let MyColumns={};
					var MyResult=[];
						MOrder.get_Data_Supplier(
			 					function(error, query_id, stats){ 
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ return res.send(error); }	
									
									 	
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	return res.send({message:" - Không tim thấy. "});
									 	 
									 	 }
									  				
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															          'fullname' 	: data[i][0],
															          'email' 		: data[i][1],
															          'phone' 		: data[i][2],
															          'address' 		: data[i][4],
															          'fax' 		: data[i][5],
															          'GPKD' 		: data[i][6],
															          'web' 		: data[i][7],
															          'is_type' 		: data[i][8],
															          'status' 		: data[i][9]
															        	
													  			}

													  
													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}	
								 if(stats.rootStage.processedRows === 0){
								 	return res.send({message:" Không có dòng nào "});
								 }	
							},
							 function(error){
										  		return res.send(error);	
							 }
						);
}

exports.get_data_supplier=function(req,res){
					let MyStats={};	
					let MyColumns={};
					var MyResult=[];
						MOrder.get_Data_Supplier(
			 					function(error, query_id, stats){ 
											      	 
									},
			 					function(error, data, columns, stats){ 
									 	 if(error){ return res.send(error); }	
									
									 	
									 	 if(!data || data === undefined || data === null){
									 	 	
									 	 	return res.send({message:" - Không tim thấy. "});
									 	 
									 	 }
									  				
										  			for (var i = data.length - 1; i >= 0; i--) {
													  		MyResult.push(
													  			{
													  				
															          'fullname' 	: data[i][0],
															          'email' 		: data[i][1],
															          'phone' 		: data[i][2],
															          'address' 		: data[i][4],
															          'fax' 		: data[i][5],
															          'GPKD' 		: data[i][6],
															          'web' 		: data[i][7],
															          'is_type' 		: data[i][8],
															          'status' 		: data[i][9]
															        	
													  			}

													  
													  		);
													  	}
										
									 			return res.send(MyResult);
									 	
										
										
												
												
							},
							function(error,stats){
								 if(error){ res.send(error); return true;}	
								 if(stats.rootStage.processedRows === 0){
								 	return res.send({message:" Không có dòng nào "});
								 }	
							},
							 function(error){
										  		return res.send(error);	
							 }
						);
}

exports.get_info_product=function(req,res){
					let MyStats={};	
					let MyColumns={};
					var MyResult=[];

					var masap=req.query.masap? req.query.masap : null;
					var idprosupplier=req.query.idprosupplier? req.query.idprosupplier*1 : 1;

							if(masap != null && idprosupplier != null){
						
								MOrder.get_Info_Product(
										masap,
										idprosupplier,
					 					function(error, query_id, stats){ 
													      	 
											},
					 					function(error, data, columns, stats){ 
					 						return res.send(data);
											 	 if(error){ return res.send(error); }	
											
											 	
											 	 if(!data || data === undefined || data === null){
											 	 	
											 	 	return res.send({message:" - Không tim thấy. "});
											 	 
											 	 }
											  				
												  			for (var i = data.length - 1; i >= 0; i--) {
															  		MyResult.push(
															  			{
															  				
																	          'tensp' 	: data[i][0],
																	          'code' 		: data[i][1],
																	          'sap_code' 		: data[i][2],
																	          'giaban' 		: data[i][4],
																	          'giathitruong' 		: data[i][5],
																	          'giathitruongkm' 		: data[i][6],
																	          'giabankm' 		: data[i][7],
																	          'cid_supplier' 		: data[i][8],
																	          'stock_num' 		: data[i][9],
																	          'cid_product' 		: data[i][10],
																	          'mota' 		: data[i][11],
																	          'baohanh' 		: data[i][12],
																	          'trongluong' 		: data[i][13]
																	      

															  			}

															  
															  		);
															  	}
													
														return res.send(MyResult);
													
											 			
											 	
												
												
														
														
									},
									function(error,stats){
										 if(error){ res.send(error); return true;}	
										 
										 if(stats.rootStage.processedRows === 0){
										 	return res.send({message:" Không có dòng nào "});
										 }	
									},
									 function(error){
												  		return res.send(error);	
									 }
								);
						}else{
							return res.send({message:" Không có sản phẩm  "});
						}
}

exports.Informationsupplier=function(req,res){
					let MyStats={};	
					let MyColumns={};
					var MyResult=[];

					var idnhacungcap=req.query.idnhacungcap? req.query.idnhacungcap : null;
				

							if(idnhacungcap != null){
						
								MOrder.Information_Supplier(
										idnhacungcap,
									
					 					function(error, query_id, stats){ 
													      	 
											},
					 					function(error, data, columns, stats){ 
					 					
											 	 if(error){ return res.send(error); }	
											
											 	
											 	 if(!data || data === undefined || data === null){
											 	 	
											 	 	return res.send({message:" - Không tim thấy. "});
											 	 
											 	 }
											  				
												  			for (var i = data.length - 1; i >= 0; i--) {
															  		MyResult.push(
															  			{
															  				
																	          'fullname' 	: data[i][0],
																	          'email' 		: data[i][1],
																	          'phone' 		: data[i][2],
																	          'address' 		: data[i][4],
																	          'fax' 		: data[i][5],
																	          'GPKD' 		: data[i][6],
																	          'web' 		: data[i][7],
																	          'is_type' 		: data[i][8],
																	          'status' 		: data[i][9]
																	         

															  			}

															  
															  		);
															  	}
												
											 			return res.send(MyResult);
											 	
												
												
														
														
									},
									function(error,stats){
										 if(error){ res.send(error); return true;}	
										 
										 if(stats.rootStage.processedRows === 0){
										 	return res.send({message:" Không có dòng nào "});
										 }	
									},
									 function(error){
												  		return res.send(error);	
									 }
								);
						}else{
							return res.send({message:" Không có nha cung cap nao  "});
						}
}

exports.get_data_sapcode=function(req,res){
				
					var MyResult=[];

					var limit = req.query.limit ? req.query.limit : '0,100';
							
								MOrder.get_Data_Sapcode(
										
										limit,
					 					function(error, query_id, stats){ 
													      	 
											},
					 					function(error, data, columns, stats){ 
					 					
											 	 if(error){ return res.send(error); }	
											
											 	
											 	 if(!data || data === undefined || data === null){
											 	 	
											 	 	return res.send({message:" - Không tim thấy. "});
											 	 
											 	 }
											  				
												  			for (var i = data.length - 1; i >= 0; i--) {
															  		MyResult.push(
															  			{
															  				
																	          sap_code:data[i][0]
																	         
																	       

															  			}

															  
															  		);
															  	}
													return res.send(MyResult);
											 	

												
												
														
														
									},
									function(error,stats){
										 if(error){ res.send(error); return true;}	
										 
										 if(stats.rootStage.processedRows === 0){
										 	return res.send({message:" Không có dòng nào "});
										 }	
									},
									 function(error){
												  		return res.send(error);	
									 }
								);
					
}

