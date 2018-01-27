const presto = require('presto-client');
exports.index=function(req,res){
	
		var client = new presto.Client({user: 'presto',catalog:"mysql",schema:"beta"});
		 
		client.execute({
		  query:   'SELECT id  FROM pro_product LIMIT 1',

		 
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