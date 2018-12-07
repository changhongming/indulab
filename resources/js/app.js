
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./jquery.csv.min.js');

// dayjs為momentjs的輕量版
window.moment = require('dayjs');

window.$ = window.jQuery = require('jquery');

// 載入Plotly特定模組
//window.Plotly = require('./custom-plotly');

// 如bootstrap功能異常，請將引入pooper.js
//require('popper.js');

require('bootstrap');
