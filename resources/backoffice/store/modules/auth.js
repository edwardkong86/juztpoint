import axios from 'axios'
import { api } from '~~//config'
import * as types from '../mutation-types'
import VueCookies from 'vue-cookies'

/**
 * Initial state
 */
export const state = {
    user: null,
    access: false,
    store: null,
    verified: false,
    //token: window.localStorage.getItem('token')
    token: null,
}
/**
 * Mutations
 */
export const mutations = {
    [types.SET_USER](state, { user }) {
        state.access = true
        state.user = user
    },

    [types.LOGOUT](state) {
        state.access = false
        state.store = null
        state.verified = false
        state.user = null
        const expire = new Date() //expire now
        VueCookies.set('JXPTBCK', '', expire, true)
        VueCookies.remove('JXPTBCK')



    },


    [types.FETCH_USER_FAILURE](state) {
        state.access = false
        state.user = null
        state.store = null
        state.verified = false
        state.terminal = null
        const expire = new Date() //expire now
        VueCookies.set('JXPTBCK', '', expire, true)
        VueCookies.remove('JXPTBCK')
    },

    [types.SET_TOKEN](state, { token, expires_at, store, user, verified }) {
        // state.token = token
        // window.localStorage.setItem('token', token)

        const expire = new Date(expires_at)
        state.access = true
        state.store = store
        state.user = user
        state.verified = verified
        VueCookies.set('JXPTBCK', token, expire, true)

    }
}
/**
 * Actions
 */
export const actions = {
    saveToken({ commit }, payload) {
        commit(types.SET_TOKEN, payload)
    },

    async fetchUser({ commit }) {
        try {
            const { data } = await axios.get(api.path('me'))
            commit(types.SET_USER, data)
        } catch (e) {
            commit(types.FETCH_USER_FAILURE)
        }
    },

    setUser({ commit }, payload) {
        commit(types.SET_USER, payload)
    },

    async logout({ commit }) {
        commit(types.LOGOUT)
    },

    destroy({ commit }) {
        commit(types.LOGOUT)
    }
}
/**
 * Getters
 */
export const getters = {
    user: state => state.user,
    check: state => state.access,
    registered: state => VueCookies.get('JXPTBCK') !== null,
    token: state => VueCookies.get('JXPTBCK'),
    terminal: state => state.terminal,
    store: state => state.store,
    verified: state => state.verified,
}
