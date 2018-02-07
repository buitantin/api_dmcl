'use strict';

const express= require("express");

const router = express.Router();
const bodyParser = require('body-parser');
const md5= require('md5');

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


router.post("/get_token",function(req,res){

    var pass=req.body.pass;

    if(pass==='dienmaycholon5r75476456455634578'){
        req.session.mytoken=md5( (new Date()).toString() + (Math.random(1000)).toString() );
        return res.send({token:req.session.mytoken});
    }else{
        return res.send({message:'Not access'});
    }

    
});
router.get("/reset_token",function(req,res){
    
    req.session.destroy();
     return res.send({message:'Success'});
    
});


router.get("/get_data",myAuth,function(req,res){
 	controllers.ServerController.get_data(req,res);
});

router.get("/get_data_tra_gop",myAuth,function(req,res){
 	controllers.ServerController.get_data_installment(req,res);
});


router.get("/get_order_detail",myAuth,function(req,res){
    controllers.ServerController.get_order_detail(req,res);
});
router.get("/get_or_detail_gift",myAuth,function(req,res){
    controllers.ServerController.get_or_detail_gift(req,res);
});
router.get("/get_supplier",myAuth,function(req,res){
    controllers.ServerController.get_supplier(req,res);
});


router.get("/get_general",myAuth,function(req,res){
    controllers.ServerController.get_general(req,res);
});

router.get("/get_data_supplier",myAuth,function(req,res){
    controllers.ServerController.get_data_supplier(req,res);
});

router.get("/get_info_product",myAuth,function(req,res){

    controllers.ServerController.get_info_product(req,res);
});

router.get("/information_supplier",myAuth,function(req,res){
    controllers.ServerController.Informationsupplier(req,res);
});

router.get("/get_data_sapcode",myAuth,function(req,res){
    controllers.ServerController.get_data_sapcode(req,res);
});


/*MYSQL */
router.get("/update_stock_num",myAuth,function(req,res){
    controllers.ServermysqlController.update_stock_num(req,res);
});
router.get("/update_status",myAuth,function(req,res){
    controllers.ServermysqlController.update_status(req,res);
});
router.get("/update_or_installment",myAuth,function(req,res){
    controllers.ServermysqlController.update_or_installment(req,res);
});
function myAuth(req,res,next){

    return next();

    if(req.query.token && req.session.mytoken){
        if(req.query.token === req.session.mytoken){
            return next();
        }
    }
    return res.send({message:"Not access"});
}

module.exports = router;