import axios from 'axios'

export default {

    namespaced: true,

    state: {
        status: '',
        top: [],
        hashtags: [],
    },

    getters: {
        getHashtags: state => {
            return function(){
                return state.hashtags;
            }
        },
        getTop: state => {
            return function(){
                return state.top;
            }
        }
    },

    mutations : {
        request: (state) => {
            state.status = 'loading';
        },
        error: (state) => {
            state.status = 'error';
        },
        indexSuccess: (state, hashtags) => {
            state.status = 'success';
            state.hashtags = hashtags;
        },
        topSuccess: (state, hashtags) => {
            state.status = 'success';
            state.top = hashtags;
        },
        success: (state) => {
            state.status = 'success';
        },
    },

    actions: {

        index: ({commit, dispatch}) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags', method: 'GET' })
                    .then(resp => {
                        const hashtags = resp.data;
                        commit('indexSuccess', hashtags);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

        top: ({commit, dispatch}, limit) => {
            return new Promise((resolve, reject) => {
                commit('request');
                axios({url: 'hashtags/top/'+limit, method: 'GET' })
                    .then(resp => {
                        const hashtags = resp.data;
                        commit('topSuccess', hashtags);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('error');
                        reject(err)
                    })
            })
        },

    }
}