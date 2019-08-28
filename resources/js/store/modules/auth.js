import axios from 'axios'
import { api } from '~/config'
import * as types from '../mutation-types'
import Cookies from 'js-cookie'

/**
 * Initial state
 */
export const state = {
  user: null,
  //token: window.localStorage.getItem('token')
  token: Cookies.get('jxp_token')
}

/**
 * Mutations
 */
export const mutations = {
  [types.SET_USER](state, { user }) { 
    
    state.user = user
  },

  [types.LOGOUT](state) {
    state.user = null
    state.store = null
    state.terminal = null
    // state.token = null
    //window.localStorage.removeItem('token')
     Cookies.remove('JXPT')
  },

  [types.FETCH_USER_FAILURE](state) {
    state.user = null
    state.store = null
    state.terminal = null
    Cookies.remove('JXPT')
  },

  [types.SET_TOKEN](state, { token, expires_at, store, terminal }) { 
    // state.token = token
    // window.localStorage.setItem('token', token)
    const expire = new Date(expires_at)

    state.store = store
    state.terminal = terminal

    
    document.cookie = Cookies.set('JXPT', token, { expires: expire, secure: true })
 


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
    /* await axios.post(api.path('logout')) */
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
  check: state => state.user !== null,
  token: state => state.token
}
