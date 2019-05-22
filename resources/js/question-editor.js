import QuestionEditor from './comproments/quiz/question-container.vue';
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/scss/bootstrap.scss';
import 'bootstrap';

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    components: {
        QuestionEditor
    }
});
