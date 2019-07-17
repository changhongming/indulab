

import VueKonva from 'vue-konva';

import BootstrapVue from 'bootstrap-vue'

import VueRouter from 'vue-router';



Vue.use(VueRouter);

Vue.use(BootstrapVue);

Vue.use(VueKonva);




import 'bootstrap/scss/bootstrap.scss';
import 'bootstrap';



import simslope from './comproments/simslope';
import surveyslope from './comproments/survey';

// import 'ruler-guides/demo/index.css'
// const Event =  require('ruler-guides/Event');
// const Dragdrop =  require('ruler-guides/Dragdrop');
// import RulersGuides from 'ruler-guides/RulersGuides';
import { Scatter } from 'vue-chartjs'


Vue.component('scatter-chart', {
    extends: Scatter,
    props: ['data', 'options'],
    mounted() {
        this.renderChart(this.data, this.options)
    },
    watch: {
        data: function () {
            this.renderChart(this.data, this.options)
        }
    }
});

// const gs   = new RulersGuides(Event, Dragdrop);

// console.log(gs);

import describeSlopeSim from "./comproments/picture-content.vue";
import tutorData from "./json/slopeTurtor.json.js";

const routes = [
    { path: '/simslope', component: simslope },
    { path: '/expslope', component: surveyslope },
    { path: '/tutorial', component: describeSlopeSim, props: { items: tutorData, href: "simslope" } }
];



const router = new VueRouter({
    routes
});

const app = new Vue({
    router
}).$mount('#app');

// 目前route會造成首個加載的原件"雙大括號"綁定物件無法順利更新，會變成只在生命週期的mounted更新僅一次，暫時先讓首頁uri為空，然後手動重新導向首頁的component
const a = document.createElement('a');
a.style = "display: none";
a.href = "/slope#/tutorial";
a.click();



// var app = new Vue({
//     el: '#surveyElement',
//     data: {
//         survey: survey
//     }
// });
// new Vue({
//     el: '#app',
//     components: {
//         simslope
//     }
// })
