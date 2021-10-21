import Vue from 'vue'
import Vuex from 'vuex'
import axios from "./axios";

Vue.use(Vuex)


const module = {
    state: {
        user: {}
    },
    getters: {
        user(state) {
            return state.user;
        }
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user
        },
    },
    actions: {
        async updateUser({ commit }) {

            try {
              if(localStorage.getItem("token")){
                  const response = await axios.get('admin/v1/details');
                  commit('SET_USER', response.data)
                  console.log(response.data,'user')
                  return response.data
              }
            } catch (error) {
              commit('SET_USER', null)
              localStorage.removeItem("token");
              localStorage.removeItem("user_type");
            }
          }
    }
};
export const store = new Vuex.Store({
    strict: true,
    modules: {
        module
    }
});