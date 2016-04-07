/**
 * Created by palagornp on 4/7/2016 AD.
 */
(function(window,$){

    var UI = {
        icheck : function(){
            $('input[type="checkbox"],input[type="radio"]').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        },//icheck

        block : function(param){
            $('body').addClass('loading');
        },

        unblock : function(param){
            $('body').removeClass('loading');
        }
    };

    window.FoundationUI = UI;
})(window,jQuery);