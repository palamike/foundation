<template>
    <div class="alert alert-{{type}}">
        <button type="button" class="close" @click="handleCloseButtonClick" aria-hidden="true">×</button>
        <h4 v-if="header" ><i class="icon fa fa-{{icon}}"></i> {{header}}</h4>
        <slot></slot>
    </div>
</template>

<style lang="stylus">

</style>

<script>
    export default{
        name : 'alert',

        props : {

            //type of alert currently it is bound to bootstrap type
            //which are info, danger, warning, success
            type : {
                type : String,
                required : true
            },

            //icon to display use font-awesome icon
            icon : {
                type : String,
                required : true
            },

            //header message
            header : {
                type : String,
                required : true
            }
        },

        methods : {
            handleCloseButtonClick : function(){
                this.$dispatch('alert-dismiss');
                this.$remove();
            }
        }
    }
</script>