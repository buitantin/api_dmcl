
const DTHome = require("../models/APP/Home");
const DTPromotion = require("../models/APP/Promotion");
const Q 	= require('q');
const Mr_Data= require("../lib/Data");
exports.containerproduct=async function(req,res){
	var TData= require("../template/data");
	TData.message = "Trả về sản phẩm trang chủ thành công";
	TData.errorcode=0;

	var NewListHome= await DTHome.NewListHome();
	
	for (var j = 0; j< NewListHome.length; j++) {
		
		var MyNewListHome=await  DTHome.GetProductHome(NewListHome[j][3]);
		
		/**/
		
		var MyResult=[];
		if(MyNewListHome){
			var data=MyNewListHome[0];
		 	for(var i=0;i<data.length ; i++){
		 		
		 		
		 		MyResult.push(
			 		{
			 			myid: data[i][0],
			 			name: data[i][1],
			 			series: "https://dienmaycholon.vn/public/picture/series/dienmay_"+data[i][2]+".png",
			 		
			 			cid_series: data[i][2],
			 			isprice: (data[i][3]+" ").trim(),
			 			is_icon: (data[i][4]+" ").trim(),
			 			is_model: (data[i][5]+" ").trim(),
			 			is_price: (data[i][6]+" ").trim(),
			 			is_red_day: (data[i][7]+" ").trim(),
			 			is_double_price: (data[i][8]+" ").trim(),
			 			is_million: (data[i][9]+" ").trim(),
			 			is_flash_sale: (data[i][10]+" ").trim(),
			 			is_sale: (data[i][11]+" ").trim(),

			 			new_description: data[i][12],
			 			discount: data[i][13],
			 			saleprice: data[i][14],
			 			is_promotion: data[i][15],
			 			cid_res: data[i][16],
			 			namecate: data[i][17],
			 			id_detail: "/cate/"+data[i][18]+"/product/"+data[i][19],
			 			detail_link_web: "/"+data[i][18]+"/"+data[i][19],
			 			photo: "https://static.dienmaycholon.vn/tmp/product_"+data[i][18]+"_150_150.jpg",
			 			// gift:   DTPromotion.getGiftText(data[i][16]),
			 			element_special: data[i][0],
			 			coupons: '',
			 			discountcoupons: ''


			 		

			 		}

		 		);
		 	}
	 	}

		/*MAIN*/
		var Data_Init={};
		Data_Init.name = NewListHome[j][0];
		Data_Init.alias = Mr_Data.toAlias(NewListHome[j][0]);	
		Data_Init.child  = MyResult;
	//	Data_Init.spect  = await  DTHome.GetSpectHome(NewListHome[j][4]);
		Data_Init.banner  = NewListHome[j][5];
		TData.data.push(Data_Init);
	}
	

	 
	return res.send(TData);

	
}
