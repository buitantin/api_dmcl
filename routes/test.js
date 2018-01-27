'use strict';

const express= require("express");

const router = express.Router();
router.use(function timeLog(req,res,next){

	next();
});

const fs = require("fs");


    var controllers={};
    var controllers_path = process.cwd() + '/controllers/'
    fs.readdirSync(controllers_path).forEach(function (file) {
        if (file.indexOf('.js') != -1) {
            controllers[file.split('.')[0]] = require(controllers_path + '/' + file);
        }
    });

router.get("/my-test",(req,res)=>{ res.send("API DMCL")});



module.exports = router;