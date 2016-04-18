/**
 * Created by palagornp on 4/18/2016 AD.
 */
var Vue = require('vue');
var i18n = require('vue-i18n');

export default {
    start : function (components,options){
        "use strict";

        var defaults = {

            //html element id which indicate application id
            applicationId : 'application',

            declareI18n : true,

            debug : false
        };

        var options = $.extend(true,defaults,options);

        return new Promise(function(resolve, reject) {
            $.ajax({
                url : options.url,
                method : 'get',
                dataType : 'json'
            }).done(function( data, textStatus, jqXHR ) {

                if(options.debug){
                    Vue.config.debug = true;
                    console.log('======= starting vue application ======= ');
                    console.log('Data : ');
                    console.log(data);
                    console.log('Text Status : ');
                    console.log(textStatus);
                    console.log('jqXHR : ');
                    console.log(jqXHR);
                }//if

                var vueData = clone_object(data);
                delete vueData.lang;
                delete vueData.locales;

                if(options.declareI18n){
                    Vue.use(i18n, {
                        lang : clone_object(data.lang),
                        locales : clone_object(data.locales)
                    });
                }//if

                //return new Vue instance
                resolve(new Vue({
                    el : '#' + options.applicationId,
                    components : components,
                    data : vueData
                }));

            }).fail(function( jqXHR, textStatus, errorThrown ){

            }).always(function(){
                if(options.debug){
                    console.log('======= vue application started ======= ');
                }
            });
        });

    }, //start

    debug :function(object,options){
        if(options.debug){
            console.log(object);
        }//if
    }//debug
}

