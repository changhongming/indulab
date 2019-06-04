import MyTest from '././comproments/quiz/my-test.vue';
import store from './store';
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/scss/bootstrap.scss';
import 'bootstrap';

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    store,
    components: {
        MyTest
    }
});
