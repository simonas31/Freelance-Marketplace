import {createStore} from 'vuex'

export default createStore({
    state: {
        authenticated: false,
        user: {}
    },
    mutations: {
        SET_AUTH(state, auth) {
            state.authenticated = auth
        },
        SET_USER(state, user) {
            state.user = user
        }
    },
    actions: {
        setAuth({commit}, auth) {
            commit('SET_AUTH', auth)
        },
        setUser({commit}, user) {
            commit('SET_USER', user)
        }
    },
    modules: {}
})