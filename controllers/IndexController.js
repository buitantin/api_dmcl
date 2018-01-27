exports.index=function(req,res){
	var presto = require('presto-client');
		var client = new presto.Client({user: 'presto',catalog:"mysql",schema:"beta"});
		 
		client.execute({
		  query:   'SELECT id,status,name  FROM pro_product',

		 
		 state:   function(error, query_id, stats){ 
		 		//({message:"status changed", id:query_id, stats:stats});
		 },
		  columns: function(error, data){ 
		  		//console.log({resultColumns: data});
		  },
		  data:    function(error, data, columns, stats){ 
		  	res.send(data); 
		  },
		  success: function(error, stats){},
		  error:   function(error){}
		});

}