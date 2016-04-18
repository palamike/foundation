import Application from '../../../vue/app.js'
import GeneralComponent from '../../../vue/test/general.vue';

var vm = null;
Application.start({ 'test-general' : GeneralComponent },options).then(function(v){
    Application.debug(v,options);
    vm = v;
});
