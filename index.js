
 
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

//app.use((req, res, next) => csrf(req, res, next))

require('express-async-await')(app)
app.use(expressValidator());
app.use(cookieParser());



app.use("/",route);
app.use("/",test);

var s=app.listen(2000,function(){
	console.log("Listen prost: 2000");
});

