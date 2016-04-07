var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var ElixirUtil = {
    vendorSrcDir : 'resources/assets/vendor/',
    vendorPublicDir : 'public/assets/vendor/',
    assetSrcDir : 'resources/assets/',
    assetPublicDir : 'public/assets/',
    cssPublicDir : 'public/assets/css/',
    jsPublicDir : 'public/assets/js/',
    vendorSrcPath :
        function (path){
            return this.vendorSrcDir+ path;
        }//function getVendorPath
    ,

    vendorPublicPath :
        function (path) {
            return this.vendorPublicDir+ path;
        },

    assetSrcPath :
        function (path) {
            return this.assetSrcDir + path;
        },

    assetPublicPath :
        function (path) {
            return this.assetPublicDir + path;
        },

    cssPublicPath :
        function (path) {
            return this.cssPublicDir + path;
        },

    jsPublicPath :
        function (path) {
            return this.jsPublicDir + path;
        }
};

elixir(function(mix) {
    mix
        .copy(ElixirUtil.vendorSrcPath('jquery/dist/jquery.min.js'), ElixirUtil.vendorPublicPath('jquery/jquery.min.js'))
        .copy(ElixirUtil.vendorSrcPath('bootstrap/dist/js/bootstrap.min.js'), ElixirUtil.vendorPublicPath('bootstrap/js/bootstrap.min.js'))
        .copy(ElixirUtil.vendorSrcPath('bootstrap/dist/css/bootstrap.min.css'), ElixirUtil.vendorPublicPath('bootstrap/css/bootstrap.min.css'))
        .copy(ElixirUtil.vendorSrcPath('bootstrap/dist/fonts'), ElixirUtil.vendorPublicPath('bootstrap/fonts'))
        .copy(ElixirUtil.vendorSrcPath('font-awesome/css/font-awesome.min.css'), ElixirUtil.vendorPublicPath('font-awesome/css/font-awesome.min.css'))
        .copy(ElixirUtil.vendorSrcPath('font-awesome/fonts'), ElixirUtil.vendorPublicPath('font-awesome/fonts'))
        .copy(ElixirUtil.vendorSrcPath('iCheck/skins'), ElixirUtil.vendorPublicPath('icheck/skins'))
        .copy(ElixirUtil.vendorSrcPath('iCheck/icheck.min.js'), ElixirUtil.vendorPublicPath('icheck/icheck.min.js'))
        .copy(ElixirUtil.vendorSrcPath('animate.css/animate.min.css'), ElixirUtil.vendorPublicPath('animate/animate.min.css'))
        .copy(ElixirUtil.vendorSrcPath('AdminLTE/dist/css'), ElixirUtil.vendorPublicPath('admin-lte/css'))
        .copy(ElixirUtil.vendorSrcPath('AdminLTE/dist/img'), ElixirUtil.vendorPublicPath('admin-lte/img'))
        .copy(ElixirUtil.vendorSrcPath('AdminLTE/dist/js/app.min.js'), ElixirUtil.vendorPublicPath('admin-lte/js/app.min.js'))
        .copy(ElixirUtil.vendorSrcPath('AdminLTE/dist/js/pages/dashboard.js'), ElixirUtil.vendorPublicPath('admin-lte/js/dashboard.js'))
        .copy(ElixirUtil.vendorSrcPath('Ionicons/css/ionicons.min.css'), ElixirUtil.vendorPublicPath('ionicons/css/ionicons.min.css'))
        .copy(ElixirUtil.vendorSrcPath('Ionicons/fonts'), ElixirUtil.vendorPublicPath('ionicons/fonts'))
        .copy(ElixirUtil.vendorSrcPath('Ionicons/png'), ElixirUtil.vendorPublicPath('ionicons/png'))
        .copy(ElixirUtil.vendorSrcPath('html5shiv/dist/html5shiv.min.js'), ElixirUtil.vendorPublicPath('html5shiv/html5shiv.min.js'))
        .copy(ElixirUtil.vendorSrcPath('respond/dest/respond.min.js'), ElixirUtil.vendorPublicPath('respond/respond.min.js'))
        .copy(ElixirUtil.vendorSrcPath('fastclick/lib/fastclick.js'), ElixirUtil.vendorPublicPath('fastclick/fastclick.js'))
        .copy(ElixirUtil.vendorSrcPath('moment/min/moment.min.js'), ElixirUtil.vendorPublicPath('moment/moment.min.js'))
        .copy(ElixirUtil.vendorSrcPath('eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'), ElixirUtil.vendorPublicPath('datetimepicker/datetimepicker.min.js'))
        .copy(ElixirUtil.vendorSrcPath('eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'), ElixirUtil.vendorPublicPath('datetimepicker/datetimepicker.min.css'))
        .copy(ElixirUtil.vendorSrcPath('dropzone/dist/min/dropzone.min.css'), ElixirUtil.vendorPublicPath('dropzone/dropzone.min.css'))
        .copy(ElixirUtil.vendorSrcPath('foundation/fonts/th_k2d_july8'), ElixirUtil.assetPublicPath('fonts/th_k2d_july8'))
        .copy(ElixirUtil.assetSrcPath('images'), ElixirUtil.assetPublicPath('images'));

    var storedDir = elixir.config.assetsDir;
    elixir.config.assetsPath = ElixirUtil.vendorSrcPath('foundation');
    mix.sass(['foundation.scss','loading.scss'],ElixirUtil.vendorPublicPath('foundation/foundation.css'));
    elixir.config.assetsPath = storedDir;
    mix.scripts(['library/common.js','library/ui.js'],ElixirUtil.vendorPublicPath('foundation/foundation.js'),ElixirUtil.vendorSrcPath('foundation/js'));
});
