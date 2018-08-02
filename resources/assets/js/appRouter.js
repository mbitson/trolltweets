import Vue from 'vue';
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import Summary from './components/Summary.vue'
import Timeline from './components/Timeline.vue'
import Hashtags from './components/Hashtags.vue'
import Links from './components/Links.vue'
import Keywords from './components/Keywords.vue'
import Tweets from './components/Tweets.vue'
export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'summary',
            component: Summary
        },
        {
            path: '/tweets',
            name: 'tweets',
            component: Tweets
        },
        {
            path: '/timeline',
            name: 'timeline',
            component: Timeline
        },
        {
            path: '/hashtags',
            name: 'hashtags',
            component: Hashtags
        },
        {
            path: '/links',
            name: 'links',
            component: Links
        },
        {
            path: '/keywords',
            name: 'keywords',
            component: Keywords
        }
    ]
})
