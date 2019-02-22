
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


//require('./jquery.csv.min.js');
//const npmConfig = require('./npm-config');

// dayjs為momentjs的輕量版
window.moment = require('dayjs');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');
// 確認是否為產品模式，如果是則關閉vue-devtools功能 nouse
// if (npmConfig.isProd) {
//     Vue.config.devtools = false;
// }
// else {
//     Vue.config.devtools = true;
// }


// 載入Plotly特定模組
//window.Plotly = require('./custom-plotly');

// 如bootstrap功能異常，請將引入pooper.js
//require('popper.js');

require('bootstrap');
