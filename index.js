
 
const express = require("express");

const app =express();

const route = require("./routes/route");

const bodyParser= require("body-parser");

const session =require("express-session");

const csrf= require("lusca").csrf();

app.use(session({
	secret:"dmclapi"
}));

app.use((req, res, next) => csrf(req, res, next))

app.use(bodyParser.json());

app.use(bodyParser.urlencoded({extended: true}));

app.use("/",route);

var s=app.listen(2000,function(){
	console.log("Listen prost: 2000");
});

