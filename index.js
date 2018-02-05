
 
const express = require("express");

const app =express();

const route = require("./routes/route");

const test= require("./routes/test");
const expressValidator = require('express-validator');

const session =require("express-session");

const csrf= require("lusca").csrf();

app.use(session({
	secret:"dienmaycholonAPI"
}));

app.use((req, res, next) => csrf(req, res, next))


app.use(expressValidator());



app.use("/",route);
app.use("/",test);

var s=app.listen(2000,function(){
	console.log("Listen prost: 2000");
});

