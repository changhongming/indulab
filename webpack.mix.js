const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix
 // 整個Indulab所需的全域js庫都包在這
 .js('resources/js/app.js', 'public/js/app.js')

 // /draw頁面使用，主要針對建模所需的js檔進行打包
 .js('resources/js/modelling-page.js', 'public/js')

 .js('resources/js/survey-component.js', 'public/js')

 .js('resources/js/drawData-page.js', 'public/js')

 .js('resources/js/question-editor.js', 'public/js')
 // 將js庫進行抽取(vendor.js)，以利於不常變更的js庫不用重新下載
 .extract([
	'jquery',
    'bootstrap',
    'vue'
 ])

 // 自動加載js庫到特定變數 https://github.com/JeffreyWay/laravel-mix/blob/master/docs/autoloading.md
 .autoload({
    jquery: ['$', 'window.jQuery', 'jQuery', 'jquery'],
    vue: ['Vue','window.Vue'],
    quill: ['window.Quill']
 })

 .browserSync({
    ui: {
        port: 3002
    },
    port: 3001,
    // 監聽的伺服器
    proxy: 'localhost:8000',
    host: 'localhost',
    // 打包完成不開啟網頁
    open: false,
    // 關閉對外連線
    online: false
 })

 // 打包css
 .sass('resources/sass/app.scss','public/css/site.css')
 // devtool: "inline-source-map" 可以將打包的程式碼還原(source map)，以便看到打包前的程式碼
 //.webpackConfig({ devtool: "inline-source-map" });
 // 如果為產品版本，進行版本號管控(用於cache-control讓客戶端可以即時更新新版本的檔案)
 if (mix.inProduction()) {
    mix.version();
 }
 else {
    mix.webpackConfig({ devtool: "inline-source-map" });
 }

