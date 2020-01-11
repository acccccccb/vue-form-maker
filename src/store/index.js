import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

let devUrl = 'https://www.ihtmlcss.com/phpcrm/tp5/public/index.php';

export default new Vuex.Store({
    state: {
        baseURL: devUrl,
        token: '5e6b4e5461b4cd69b45396e0a5a9b98a',
    },
    mutations: {

    },
    strict: process.env.NODE_ENV !== 'production'
})

