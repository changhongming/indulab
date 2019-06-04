import uuid from '../../uuid-gen';

class Question {
    constructor({ answer, id = null, order = 0, wronganswer = '', question = '', choices = [new Choice, new Choice], isSave = false } = { answer, id, order, wronganswer, question, choices, isSave }) {
        // 如果有指定答案ID，就用答案ID，否則調用第一個選項做為答案ID
        this.answer = answer || choices[0].id;
        this.id = id;
        this.order = order;
        this.question = question;
        this.wronganswer = wronganswer;
        this.choices = choices;
        this.isSave = isSave;
    }
}


class Choice {
    constructor({ content = '', id = uuid() } = { content, id }) {
        this.content = content;
        this.id = id;
    }
}

// 深拷貝覆蓋資料(並且會刪除多的內容) => 註：由於JS內物件是採用參考(reference)方式，所以需要將物件內各個數值一一覆蓋(因數值是by value)。
const copyData = (obj, overObj) => {
    // 轉換為沒有觀察者模式的JSON物件
    overObj = JSON.parse(JSON.stringify(overObj));
    Object.keys(obj).forEach(key => {
        // 刪除原本物件沒有的key
        if (!overObj.hasOwnProperty(key)) {
            // 跳過本次迭代
            return delete obj[key];
        }
        // 如果為型別為Object，則遞迴往該物件下去搜尋(註：因為null的typeof也是回傳Object，所以加入obj[key]來判斷null)
        if (typeof obj[key] === "object" && obj[key]) {
            copyData(obj[key], overObj[key]);
        }
        // 如果不是上述情況，則執行一般覆蓋
        obj[key] = overObj[key];
    });
};

const state = {
    selectId: 0,
    questions: [],
    initQuestions: [],
    isLoading: false,
    isLoadedFail: false,
    isLoaded: false,
    isSaveSuccess: null,
    isRecovery: false,
    isQuestionLoaded: false,
    isPreviewQuestionMode: false,
    isPreviewTestMode: false,
    isEditorQuestionMode: true,
    qid: undefined,
}

const getters = {
    questionNumber: (state) => {
        return state.selectId + 1;
    },

    getQuestion: (state) => {
        return state.questions[state.selectId];
    },

    getIsSave(state) {
        return index => {
            const id = index ? index : state.selectId;
            return state.questions[id].isSave
        }
    }
}

const mutations = {
    SET_QID(state, id) {
        state.qid = id;
    },

    /* 問題選項 */
    SET_SELECT_ID(state, id) {
        state.selectId = id;
    },

    /* 問題 */
    ADD_QUESTION(state, question) {
        state.questions.push(question);
        state.initQuestions.push(JSON.parse(JSON.stringify(question)));
    },

    REMOVE_QUESTION(state, targetIndex) {
        state.questions.splice(targetIndex, 1);
        state.initQuestions.splice(targetIndex, 1);
    },

    SET_QUESTIONS(state, questions) {
        state.questions = questions;
        state.initQuestions = JSON.parse(JSON.stringify(questions));
    },

    UPDATE_QUESTION(state, content) {
        state.questions[state.selectId].question = content;
    },

    RECOVERY_QUESTION(state) {
        copyData(state.questions[state.selectId], state.initQuestions[state.selectId]);
    },

    BACKUP_QUESTION(state) {
        copyData(state.initQuestions[state.selectId], state.questions[state.selectId]);
    },

    ADD_CHOICE(state, { choice, index }) {
        state.questions[state.selectId].choices.splice(index + 1, 0, choice);
    },

    REMOVE_CHOICE(state, targetIndex) {
        state.questions[state.selectId].choices.splice(targetIndex, 1);
    },

    INSERT_CHOICE(state, { targetIndex, choice }) {
        state.questions[state.selectId].choices.splice(targetIndex, 0, choice);
    },

    SWAP_CHOICES(state, { sourceIndex, targetIndex }) {
        const sourceData = state.questions[state.selectId].choices[sourceIndex];
        state.questions[state.selectId].choices.splice(sourceIndex, 1);
        state.questions[state.selectId].choices.splice(targetIndex, 0, sourceData);
    },

    UPDATE_CHOICE(state, { index, content }) {
        state.questions[state.selectId].choices[index].content = content;
    },

    UPDATE_WRONG_ANSWER_EXPLAIN(state, content) {
        state.questions[state.selectId].wronganswer = content;
    },

    UPDATE_ANSWER_ID(state, uuid) {
        state.questions[state.selectId].answer = uuid;
    },

    /* 存取狀態 */
    SET_IS_LOADING(state, status) {
        state.isLoading = status;
    },

    SET_IS_LOADED(state, status) {
        state.isLoaded = status;
    },

    SET_IS_LOADED_FAIL(state, status) {
        state.isLoadedFail = status;
    },

    SET_IS_SAVE_SUCCESS(state, status) {
        state.isSaveSuccess = status;
    },

    SET_IS_SAVE(state, status) {
        state.questions[state.selectId].isSave = status;
    },

    SET_IS_RECOVERY(state, status) {
        state.isRecovery = status;
    },

    SET_IS_QUESTION_LOADED(state, status) {
        state.isQuestionLoaded = status;
    },

    SET_IS_PREVIEW_QUESTION_MODE(state, status) {
        state.isPreviewQuestionMode = status;
    },

    SET_IS_PREVIEW_PREVIEW_TEST_MODE(state, status) {
        state.isPreviewTestMode = status;
    },

    SET_IS_EDITOR_QUESTION_MODE(state, status) {
        state.isEditorQuestionMode = status;
    }
}

const actions = {
    changeSelectId({ commit }, id) {
        commit('SET_SELECT_ID', id);
        commit('SET_IS_RECOVERY', true);
    },

    getQuestions({ commit, dispatch }) {
        commit('SET_IS_LOADING', true);
        axios.get(`/question?id=${state.qid}`)
            .then(res => {
                const questions = [];
                res.data.forEach(row => {
                    questions.push(new Question({
                        id: row.id,
                        order: row.order,
                        question: row.question,
                        wronganswer: row.wrong_answer_message,
                        answer: row.answer_id,
                        choices: JSON.parse(row.choices).map(x => new Choice(x)),
                        isSave: true
                    }));
                });

                commit('SET_SELECT_ID', 0);
                commit('SET_QUESTIONS', questions);

                // 此問題庫資料為空
                if (res.data.length === 0) {
                    // 等待空陣列初始化完成直接加入一個新問題
                    setTimeout(() => {
                        dispatch('addQuestion');
                    }, 0);
                }
                commit('SET_IS_LOADING', false);
            })
            .catch(err => {
                commit('SET_IS_LOADED_FAIL', true);
                console.log(err);
            })
            .finally(() => {
                commit('SET_IS_LOADED', true);
            });
    },

    saveQuestion({ commit, state }) {
        commit('SET_IS_LOADING', true);
        const question = state.questions[state.selectId];
        const payload = {
            answer_id: question.answer,
            question: question.question,
            wrong_answer: question.wronganswer,
            choices: JSON.stringify(question.choices),
            qid: state.qid,
            order: 0
        };

        if (question.id) {
            payload.id = question.id;
        }

        axios.post('/question', payload)
            .then(res => {
                // 回傳資料庫的PK在id內
                console.log(res.data.id)
                commit('SET_IS_SAVE_SUCCESS', true);
                commit('SET_IS_SAVE', true);
                commit('BACKUP_QUESTION');
            })
            .catch(err => {
                console.log(err)
                commit('SET_IS_SAVE_SUCCESS', false);
                commit('SET_IS_SAVE', false);
            })
            .finally(() => commit('SET_IS_LOADING', false))
    },

    addQuestion({ commit, dispatch }, question = new Question) {
        if (!(question instanceof Question)) {
            question = new Question(question);
        }
        commit('ADD_QUESTION', question);
        setTimeout(() => {
            dispatch('changeSelectId', state.questions.length - 1);
            commit('SET_IS_SAVE', false);
        }, 0);
    },

    removeQuestion({ commit, dispatch }, { selId, qid }) {
        // 如果問題已建立，但尚未保存置資料庫(資料庫還沒有索引)
        if (qid === null) {
            setTimeout(() => {
                dispatch('changeSelectId', 0);
                commit('REMOVE_QUESTION', selId);
            }, 0);
        }
        // 如果資料庫已建立索引
        else {
            axios
                .delete(`/question/${qid}`)
                .then(res => {
                    console.log(res);
                    setTimeout(() => {
                        dispatch('changeSelectId', 0);
                        commit('REMOVE_QUESTION', selId);
                    }, 0);
                })
                .catch(err => {
                    console.log(err);
                });
        }
    },

    addChoice({ commit, dispatch }, { choice = new Choice, index = 0 }) {
        commit('ADD_CHOICE', { choice, index });
        dispatch('doNotSave');
    },

    updateQuestionContent({ commit, dispatch }, content) {
        commit('UPDATE_QUESTION', content);
        dispatch('doNotSave');
    },

    updateWrongAnswerExplain({ commit, dispatch }, content) {
        commit('UPDATE_WRONG_ANSWER_EXPLAIN', content);
        dispatch('doNotSave');
    },

    updateQuestionAnswerId({ commit, dispatch }, uuid) {
        commit('UPDATE_ANSWER_ID', uuid);
        dispatch('doNotSave');
    },

    swapChoice({ commit, dispatch }, { sourceIndex, targetIndex }) {
        commit('SWAP_CHOICES', { sourceIndex: sourceIndex, targetIndex: targetIndex });
        dispatch('doNotSave');
    },

    removeChoice({ commit, dispatch }, target) {
        commit('REMOVE_CHOICE', target);
        dispatch('doNotSave');
    },

    updateChoice({ commit, dispatch }, { index, content }) {
        commit('UPDATE_CHOICE', { index, content });
        dispatch('doNotSave');
    },

    recoveyQuestion({ commit }) {
        commit('SET_IS_RECOVERY', true);
        commit('RECOVERY_QUESTION');
    },

    doNotSave({ commit }) {
        if (state.isQuestionLoaded) {
            if (!state.isRecovery) {
                // 確認是否為復原狀態，如果是則代表沒有變更
                commit('SET_IS_SAVE', false);
            }
            // 復位isRecovery數值，使它可以重新判斷是否儲存
            commit('SET_IS_RECOVERY', false);
        }
    },

    setIsSaveSucess({ commit }, state) {
        commit('SET_IS_SAVE_SUCCESS', state);
    },

    setIsQuestionLoaded({ commit }, state) {
        commit('SET_IS_QUESTION_LOADED', state);
    },

    setIsEditorQuestionMode({ commit }, state) {
        commit('SET_IS_EDITOR_QUESTION_MODE', state);
    },

    setIsPreviewTestMode({ commit }, state) {
        commit('SET_IS_PREVIEW_PREVIEW_TEST_MODE', state);
    },

    setIsPreviewQuestionMode({ commit }, state) {
        commit('SET_IS_PREVIEW_QUESTION_MODE', state);
    },

    setTestId({commit}, state) {
        commit('SET_QID', state);
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
