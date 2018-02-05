const presto = require('presto-client');
const md5 = require("md5");
exports.getToken=function(req,res){
	if( req.session.mytoken){
		req.session.destroy();
		res.send({
			message: "lấy token không thành công",
			errorcode: 1,
			data: "not-token"
		});
	}else{
		req.session.mytoken = md5(Date.now());
		
		res.send({
			message: "lấy token thành công",
			errorcode: 0,
			data: req.session.mytoken
		});
	}
	
}
exports.test=function(req,res){
	
		var client = new presto.Client({user: 'presto',catalog:"mysql",schema:"beta"});
		
		client.execute({
		  query:   
		  	"SELECT  a.* "+
		  	
       	 	" FROM or_order AS a "+
	       		
       	 		" LIMIT 10  ",
		 
		 state:   function(error, query_id, stats){ 
		 		//({message:"status changed", id:query_id, stats:stats});
		 		if(error){res.send(error)}
		 },
		  columns: function(error, data){ 
		  		//console.log({resultColumns: data});
		  		if(error){res.send(error)}
		  		
		  },
		  data:    function(error, data, columns, stats){ 
		  	

		  	res.send(data);
		  		return true;
		  },
		  success: function(error, stats){
		  	if(error){res.send(error);}

		  },
		  error:   function(error){
		  		res.send(error);
		  }
		});

}