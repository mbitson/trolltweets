import Vue from 'vue';
import VueRouter from 'vue-router'
Vue.use(VueRouter);

const Summary = () => System.import('./components/Summary.vue');
const Timeline = () => System.import('./components/Timeline.vue');
const Hashtags = () => System.import('./components/Hashtags.vue');
const Links = () => System.import('./components/Links.vue');
const Keywords = () => System.import('./components/Keywords.vue');
const Tweets = () => System.import('./components/Tweets.vue');
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
