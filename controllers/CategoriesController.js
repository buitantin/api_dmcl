const presto 			= require("presto-client");
const Q 				= require('q');
const MOrder			= require("../models/Order");
const Cate				= require("../models/APP/Categories");
const Mr_Data 			= require("../lib/Data");
exports.loadmenu 		= async function(req,res){
	console.time("Thời gian thực thi: ");
	var TData 			= require("../template/data");
	TData.message 		= "Trả về danh mục menu top thành công";
	var result 			= new Array();
	TData.errorcode 	= 0;
	var TPromise 		= [];
	var View 			= {};
	let MyStats 		= {};
	let MyColumns 		= {};
	var MyResult 		= [];
	var DTCATE_PARENT 	= new Array();
	var _DTCATE_PARENT 	= new Array();
	var DTCATE_CHILD 	= new Array();
	var DT_PARENT 		= new Array();
	var list 			= [ '1', '2', '9', '15', '19', '21', '120' ];
	var test 			= new Array();
	var _DTCATE_PARENT 	= await Cate.get_cate();
	var count = 0;
	for(var i=0;i<_DTCATE_PARENT.length ; i++){
		DTCATE_PARENT[_DTCATE_PARENT[i][0]] =  await Cate.get_dtcate_parent(_DTCATE_PARENT[i][0]);
	}
	var _count_number   = 0;
	for(var i=0;i<DTCATE_PARENT.length ; i++){
		if(DTCATE_PARENT[i] != null)
		{
			DTCATE_CHILD[DTCATE_PARENT[i][0][0]] 	= await Cate.get_catechild(DTCATE_PARENT[i][0][0]);
			count++;
		}
	}    
	for(var j=0;j<list.length ; j++)
	{    
		var Parent 				= DTCATE_PARENT[list[j]][0];
		var banner 				= JSON.parse(Parent[15]);
		var Child 				= DTCATE_CHILD[list[j]];
		var _child_buffer 		= new Array();
		var _banner_buffer 		= new Array();
		for(var i=Child.length-1;i>=0 ; i--){
			var _photo_one		= '';
			var _photo_two		= '';
			var _photo_tg		= '';
			var _icon			= '';	
			if(Child[i][17] 	!= null)
			{
				_photo_one 		= await Cate.checkPhoto('/public/picture/cate/' + Child[i][17]);
			}
			if(Child[i][18] 	!= null)
			{
				_photo_two 		= await Cate.checkPhoto('/public/picture/cate/' + Child[i][18]);
			}
			if(Child[i][19] 	!= null)
			{
				_photo_tg 		= await Cate.checkPhoto('/public/picture/cate/' + Child[i][19]);
			}
			if(Child[i][0]  	!= null)
			{
				_icon 			= await Cate.checkPhoto('/public/picture/cate/cate_child_' + Child[i][0] + '.png');
			}
			_child_buffer.push({
				id:   						Child[i][0],
				name: 						Child[i][1],
				alias: 						Child[i][2],
				ordering: 					Child[i][3],
				status: 					Child[i][4],
				cid_parent: 				Child[i][5],
				has_coupon: 				Child[i][6],
				tag_title: 					Child[i][7],
				tag_keyword: 				Child[i][8],
				tag_description: 			Child[i][9],
				links_left: 				Child[i][10],
				links_right: 				Child[i][11],
				links_banner_top: 			Child[i][12],
				link_banner_left_bottom: 	Child[i][13],
				cate_description: 			Child[i][14],
				cate_banner: 				Child[i][15],
				created: 					Child[i][16],
				photo_one: 					_photo_one,
				photo_two: 					_photo_two,
				photo_tg: 					_photo_tg,
				icon: 						_icon
			});
		} 
		if(banner != null)
		{
			if (banner[0] != null)
			{
				_banner_buffer.push({
					name: 					banner[0]["name_banner_1"], 
					link: 					banner[0]["links_banner_1"], 
					order: 					banner[0]["order_banner_1"], 
					status: 				banner[0]["status_banner_1"], 
					photo: 					await Cate.checkPhoto('/public/picture/cate/'+banner[0]["bannerName"]),
				});
			}
			if (banner[1] != null)
			{
				_banner_buffer.push({
					name: 					banner[1]["name_banner_2"], 
					link: 					banner[1]["links_banner_2"], 
					order: 					banner[1]["order_banner_2"], 
					status: 				banner[1]["status_banner_2"], 
					photo: 					await Cate.checkPhoto('/public/picture/cate/'+banner[1]["bannerName"]),
				});
			}
			if (banner[2] != null)
			{
				_banner_buffer.push({
					name: 					banner[2]["name_banner_3"], 
					link: 					banner[2]["links_banner_3"], 
					order: 					banner[2]["order_banner_3"], 
					status: 				banner[2]["status_banner_3"], 
					photo: 					await Cate.checkPhoto('/public/picture/cate/'+banner[2]["bannerName"]),
				});
			}
		}
		result.push({
			id: Parent[0], 
			name: Parent[1], 
			alias: Parent[2], 
			cate_description: Parent[14], 
			cate_banner: _banner_buffer, 
			child: _child_buffer
		});
	}    
	TData.data=result;
	res.send(JSON.stringify(TData));
	console.timeEnd("Thời gian thực thi: ");
}    
exports.boxslidehome 	= async function(req,res){
	var TData 		 	= require("../template/data");
	TData.message 	 	= "Trả về banner slide thành công";
	TData.errorcode  	= 0;
	var result 		 	= new Array();
	var DTSLIDESHOW 	= new Array();
	var DTSLIDESHOW 	= await Cate.get_slideshow();
	for(var i=DTSLIDESHOW.length-1;i>=0 ; i--)
	{	
		result.push({
			links: 						DTSLIDESHOW[i][5],
			name: 						DTSLIDESHOW[i][1],
			id: 						DTSLIDESHOW[i][0],
			slide_description: 			DTSLIDESHOW[i][2],
			photo: 						await Cate.checkPhoto('/public/picture/slideshow/'+DTSLIDESHOW[i][4]),
		});
	}
	TData.data 			= result;
	res.send(JSON.stringify(TData));
}    
exports.getsubcategory 	= async function(req,res){
	var TData 		 	= require("../template/data");
	var DTCATE_PARENT 	= new Array();
	var DTCATE_CHILD 	= new Array();
	var _DTCATE_PARENT 	= new Array();
	var cate 			= req.params.cate;
	var TCate 			= new Array();
	var _DTCATE_PARENT 	= await Cate.get_cate();
	var result 		 	= new Array();
	for(var i=0;i<_DTCATE_PARENT.length ; i++){
		DTCATE_PARENT[_DTCATE_PARENT[i][0]] =  await Cate.get_dtcate_parent(_DTCATE_PARENT[i][0]);
	} 
	for(var i=0;i<DTCATE_PARENT.length ; i++){
		if(DTCATE_PARENT[i] != null)
		{
			DTCATE_CHILD[DTCATE_PARENT[i][0][0]] 	= await Cate.get_catechild(DTCATE_PARENT[i][0][0]);
		}
	}  
	var product = new Array();
	if(cate != '')
	{
		TCate = await Cate.getCurrentCate(cate,DTCATE_PARENT,DTCATE_CHILD);
		if(TCate != '')
		{
			if(TCate[5]=='0')
			{
				for(var i=0;i<DTCATE_CHILD[TCate[0]].length ; i++)
				{
					product[DTCATE_CHILD[TCate[0]][i][0]] = await Cate.listCateProductParent(cate,DTCATE_PARENT,DTCATE_CHILD);
				}
			}
		}
	}
	var _result_cate = new Array();
	for(var i = DTCATE_CHILD[TCate[0]].length-1;i>=0 ; i--)
	{
		if(DTCATE_CHILD[TCate[0]][i] != null)
		{
			if(product[DTCATE_CHILD[TCate[0]][i][0]] != '')
			{
				_result_cate.push({
					name: 	DTCATE_CHILD[TCate[0]][i][1], 
					id: 	DTCATE_CHILD[TCate[0]][i][0]	
				});
			}
		}
	}
	result.push({
		name: 			TCate[1],	
		cate_child: 	_result_cate 
	});
	res.send(JSON.stringify(DTCATE_CHILD));
	var _d = 0;
	for(var i=0 ; i<DTCATE_CHILD[TCate[0]].length ; i++)
	{
		var _result_product = array();
		if(_d==1 && TCate[2] == 'dien-tu')
		{
			_result_product.push({
					name: 	'Loa Amply Cao Cấp', 
					id: 	'',
					alias:  DTCATE_CHILD[TCate[0]][2]	
				});
			var _product_amply = await Cate.getAmply();
			for(var i=0 ; i<DTCATE_CHILD[TCate[0]].length ; i++)
			{

			}
		}
		_d++;
	}
	res.send(JSON.stringify(result));
	TData.message 	 	= "Trả về banner slide thành công";
	TData.errorcode  	= 0;
}

exports.test 	= async function(req,res){
	// var _product_amply  = await Cate.check_coupon(4929);
	var result  = Cate.get_cate();
	res.send(JSON.stringify(_product_amply));
}