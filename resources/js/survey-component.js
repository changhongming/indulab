

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
    extends: Scatter ,
    props: ['data', 'options'],
    mounted () {
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


const routes = [
  { path: '/simslope', component: simslope },
  { path: '/expslope', component: surveyslope }
];



const router = new VueRouter({
  routes
});

const app = new Vue({
  router
}).$mount('#app');




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
