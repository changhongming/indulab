import Test from '././comproments/quiz/test.vue';
// import store from './store';
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/scss/bootstrap.scss';
import 'bootstrap';

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    components: {
        Test
    }
});
