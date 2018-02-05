'use strict';

const express= require("express");

const router = express.Router();
const bodyParser = require('body-parser');

/*router.use(function timeLog(req,res,next){
	next();
});*/

const fs = require("fs");


    var controllers={};
    var controllers_path = process.cwd() + '/controllers/'
    fs.readdirSync(controllers_path).forEach(function (file) {
        if (file.indexOf('.js') != -1) {
            controllers[file.split('.')[0]] = require(controllers_path + '/' + file);
        }
    });
    
router.use(bodyParser.urlencoded({
    extended: true
}));

router.get("/",(req,res)=>{ res.send("API DMCL")});
router.get("/default/server/gettoken",(req,res)=>controllers.IndexController.getToken(req,res))


router.get("/test-presto",function(req,res){
 	controllers.IndexController.test(req,res);
});


router.get("/server/get_data",function(req,res){
 	controllers.ServerController.get_data(req,res);
});

router.get("/server/get_data_tra_gop",function(req,res){
 	controllers.ServerController.get_data_installment(req,res);
});

module.exports = router;