import axios from 'axios'
export default {

    namespaced: true,

    state: {
        active: {
            hashtag: [],
            hashtagText: [],
            author: [],
            authorText: [],
            tweetText: []
        },
        saved: false,
        sheetOpen: false
    },

    getters: {
        getActive: state => {
            return state.active;
        },
        getSaved: state => {
            return state.saved;
        },
        getSheetOpen: state => {
            return state.sheetOpen;
        },
        getActiveHashtag: state => {
            return state.active.hashtag;
        },
        getActiveHashtagText: state => {
            return state.active.hashtagText;
        },
        getActiveAuthor: state => {
            return state.active.author;
        },
        getActiveAuthorText: state => {
            return state.active.authorText;
        },
        getActiveTweetText: state => {
            return state.active.tweetText;
        },
        getActiveCount: state => {
            let count = 0;
            for(let filterType in state.active){
                if(state.active.hasOwnProperty(filterType)){
                    state.active[filterType].forEach(function(filter){
                        if(typeof filter !== 'undefined' && filter !== null && filter.length>0){
                            count++;
                        }
                    });
                }
            }
            return count;
        }
    },

    mutations : {
        open: (state) => {
            state.sheetOpen = true;
        },
        close: (state) => {
            state.sheetOpen = false;
        },
        clear: (state) => {
            state.active = {
                hashtag: [],
                hashtagText: [],
                author: [],
                authorText: [],
                tweetText: []
            };
        },
        setActive: (state, active) => {
            state.active = active;
        },
        setSheetOpen: (state, status) => {
            state.sheetOpen = status;
        },
        addHashtagFilter: (state, hashtag) => {
            state.saved = false;
            state.active.hashtag.push(hashtag);
        },
        setHashtagFilter: (state, hashtag) => {
            state.saved = false;
            state.active.hashtag = [hashtag];
        },
        addHashtagTextFilter: (state, hashtag) => {
            state.saved = false;
            state.active.hashtagText.push(hashtag);
        },
        setHashtagTextFilter: (state, hashtag) => {
            state.saved = false;
            state.active.hashtagText = [hashtag];
        },
        addAuthorFilter: (state, author) => {
            state.saved = false;
            state.active.author.push(author);
        },
        setAuthorFilter: (state, author) => {
            state.saved = false;
            state.active.author = [author];
        },
        addAuthorTextFilter: (state, hashtag) => {
            state.saved = false;
            state.active.authorText.push(hashtag);
        },
        addTweetTextFilter: (state, hashtag) => {
            state.saved = false;
            state.active.tweetText.push(hashtag);
        },
        saving: (state) => {
            state.saved = false;
        },
        saveSuccess: (state) =>{
            state.saved = true;
        },
        saveError: (state) => {
            state.saved = false;
        },
        loadSuccess: (state, filters) => {
            state.active = filters;
            state.saved = true;
        }
    },

    actions: {

        openFilter: ({commit}) => {
            commit('open');
        },
        closeFilter: ({commit}) => {
            commit('close');
        },
        toggleFilter: ({commit, state}) => {
            (state.sheetOpen === false)?commit('open'):commit('close');
        },
        save: ({commit, state}) => {
            return new Promise((resolve, reject) => {
                commit('saving');
                axios({url: 'filters', method: 'POST', data: {active: state.active} })
                    .then(resp => {
                        commit('saveSuccess');
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('saveError');
                        reject(err)
                    })
            })
        },
        load: ({commit, state}) => {
            return new Promise((resolve, reject) => {
                axios({url: 'filters', method: 'GET' })
                    .then(resp => {
                        commit('loadSuccess', resp.data);
                        resolve(resp)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        },

    }
}