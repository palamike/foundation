<template>
    <div class="all-test">
        <div class="alert-test">
            <alert v-for="alert in alerts"
                   :type="alert.type"
                   :header="alert.header"
                   :icon="alert.icon"
                   @alert-dismiss="removeAlert(alert)"
            >
                {{{alert.msg}}}
            </alert>
        </div>
        <div class="auto-form-test">
            <auto-form form-class="form-class" :forms="forms" header="Test General Form"></auto-form>
            <auto-form form-class="server-form-class" :forms="$root.test_forms" header="Test Server General Form"></auto-form>
        </div>
    </div>
</template>

<style lang="stylus"></style>

<script>
    import AlertComponent from '../components/alert.vue';
    import AutoForm from '../components/auto_form_horizontal.vue';

    export default {
        name : 'test-general',

        components : {
            alert : AlertComponent,
            'auto-form' : AutoForm
        },

        data() {
            return {
                alerts : [
                    { type : 'danger', header : 'Danger Alert', icon : 'ban', msg : 'This is Danger Alert' },
                    { type : 'warning', header : 'Warning Alert', icon : 'exclamation-triangle', msg : 'This is Warning Alert' },
                    { type : 'info', header : 'Info Alert', icon : 'info-circle', msg : 'This is Info Alert' }
                ],

                forms : [
                    { type : 'text', name : 'text_el',  label : 'Text Element', value : 'Text Value'},
                    { type : 'email', name : 'email_el', label : 'Email Element', value : '', placeholder : 'user@domain.tld'},
                    { type : 'text', name : 'text_el2', label : 'Text Element', value : ''},
                    { type : 'checkbox', name : 'checkbox_el1', label : 'Checkbox Element', trueValue : 'isCheck', falseValue : 'isUncheck', value : 'isCheck'},
                    { type : 'checkbox', name : 'checkbox_el2', label : 'Checkbox Element', trueValue : 'isCheck', falseValue : 'isUncheck', value : ''}
                ]
            };
        },

        methods : {
            removeAlert(alert) {
                this.alerts.$remove(alert);
            }
        }
    }
</script>