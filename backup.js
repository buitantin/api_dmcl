
 
const express = require("express");

const app =express();

const route = require("./routes/route");

const test= require("./routes/test");
const expressValidator = require('express-validator');

const session =require("express-session");

//const csrf= require("lusca").csrf();
var cookieParser = require('cookie-parser');
app.use(session({
	secret:"dmclapi",
	resave: true,
    saveUninitialized: true
}));

/*
var vhost = require( 'vhost' );
function createVirtualHost(domainName, dirPath) {
    return vhost(domainName, express.static( dirPath ));
}

var potatoHost = createVirtualHost("192.168.10.100", "test");

app.use(potatoHost);*/


//app.use((req, res, next) => csrf(req, res, next))

require('express-async-await')(app)
app.use(expressValidator());
app.use(cookieParser());



app.use("/",route);
app.use("/",test);

var server = app.listen(2000, function() {

	
	console.log('Listening on port %d', server.address().port);
});

/*
app.set('port', process.env.PORT || 2000);
app.set('host', process.env.HOST || '0.0.0.0');

app.listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('host') + ':' + app.get('port'));
});
*/