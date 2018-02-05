const presto = require("presto-client");
const Q = require('q');
var waterfall = require('async-waterfall');
const MOrder=require("../models/Order");
var wait=require('wait.for-es6');

exports.get_data= function(req,res){
		var TPromise=[];
		var View={};
		let MyStats={};
		let MyColumns={};
		var MyResult=[];


		
	
		 if(req.session.queryidorder !== undefined ){
		 	MOrder.Kill(req.session.queryidorder);
		 }	
		 		MOrder.get_Order(
		 			function(error, query_id, stats){ 
								  			req.session.queryidorder =query_id;
								  			
										      	 
					},
		 			function(error, data, columns, stats){
				 		if(stats.state==="FINISHED"){
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
													
													res.send(MyResult);
										 			
										 			return true;
									}				
													
								},
								function(error,stats){
									 if(error){ res.send(error); return true;}	
									  	
											  		
								},
								 function(error){
											  		res.send(error);
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
		
		 if(req.session.queryidorderinstallment !== undefined ){
		 	MOrder.Kill(req.session.queryidorderinstallment);
		 }	
		 MOrder.get_Order_Installment(
		 					function(error, query_id, stats){ 
								  			req.session.queryidorderinstallment =query_id;
								  			
										      	 
								},
		 					function(error, data, columns, stats){ 
								 	 if(error){ View.error= error; }	
								
								 if(stats.state==="FINISHED"){
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
									
								 			res.send(MyResult);
								 			
								 			return true;	
									
									}
											
											
						},
						function(error,stats){
							 if(error){ res.send(error); return true;}		
							  	if(stats.state==="FINISHED"){

							  		

							  	}
									  		
						},
						 function(error){
									  		res.send({message:" - Không tim thấy đơn hàng trả góp. "});
								 	 		return true;
						 });		
}
