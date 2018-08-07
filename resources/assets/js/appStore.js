import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);

import HashtagStore from './stores/hashtagStore'
import TweetStore from './stores/tweetStore'
import AnnotationsStore from './stores/annotationsStore'
import FilterStore from './stores/filterStore'
export default new Vuex.Store({
    modules: {
        hashtags: HashtagStore,
        tweets: TweetStore,
        annotations: AnnotationsStore,
        filter: FilterStore
    }
})