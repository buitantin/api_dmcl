
const Config = require("../config/mysql");
const Mysql = require("mysql");

module.exports.Statement=function(sql,callback){
	var connection =Mysql.createConnection(Config);
	connection.connect();
	connection.query(sql,callback);
	connection.end();
}