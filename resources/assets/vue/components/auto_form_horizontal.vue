<template>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{header}}</h3>
        </div>
        <div class="box-body">
            <form class="form-horizontal auto-form {{formClass}}">
                <template v-for="form in forms">
                    <div class="form-group" v-if="form.type == 'email'">
                        <label :for="form.name" class="col-sm-{{labelWidth}} control-label">{{{form.label}}}</label>
                        <div class="col-sm-{{inputWidth}}">
                            <input type="email" :name="form.name" class="form-control" :placeholder="form.placeholder" v-model="form.value">
                        </div>
                    </div>
                    <div class="form-group" v-if="form.type == 'text'">
                        <label :for="form.name" class="col-sm-{{labelWidth}} control-label">{{{form.label}}}</label>
                        <div class="col-sm-{{inputWidth}}">
                            <input type="text" :name="form.name" class="form-control" :placeholder="form.placeholder" v-model="form.value">
                        </div>
                    </div>
                    <div class="form-group" v-if="form.type == 'checkbox'">
                        <label :for="form.name" class="col-sm-{{labelWidth}} control-label">{{{form.label}}}</label>
                        <div class="col-sm-{{inputWidth}}">
                            <div class="checkbox">
                                <input type="checkbox" :name="form.name" v-model="form.value" :true-value="form.trueValue" :false-value="form.falseValue" >
                            </div>
                        </div>
                    </div>
                </template>
            </form>
        </div>
    </div>
</template>

<style lang="stylus">

</style>

<script>
    export default {
        name : 'auto-form',
        props : {
            labelWidth : {
                type : Number,
                default : 2
            },

            inputWidth : {
                type : Number,
                default : 10
            },

            formClass : {
                type : String,
                required : false
            },

            forms : {
                type : Array,
                required : true
            },

            header : {
                type : String,
                required : true
            }
        },

        methods : {
            initICheck () {
                var self = this;

                var selector = '.auto-form';

                if(self.formClass){
                    selector += '.' + self.formClass;
                }

                selector += ' input[type="checkbox"],.auto-form  input[type="radio"]';
                FoundationUI.icheck(selector);

                $(selector).on('ifChecked',function(){

                    var el = $(this);
                    var el_name = $(el).attr('name');

                    var form = self.forms.find(function(form){
                        return form.name == el_name;
                    });

                    var index = self.forms.findIndex(function(form){
                        return form.name == el_name;
                    });

                    form.value = el.attr('true-value');
                    self.forms.$set(index,form);
                });

                $(selector).on('ifUnchecked',function(){
                    var el = $(this);
                    var el_name = $(el).attr('name');

                    var form = self.forms.find(function(form){
                        return form.name == el_name;
                    });

                    var index = self.forms.findIndex(function(form){
                        return form.name == el_name;
                    });

                    form.value = el.attr('false-value');
                    self.forms.$set(index,form);
                });
            }
        },

        ready() {
            this.initICheck();
        }
    }
</script>