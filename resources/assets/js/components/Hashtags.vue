<template>
    <div class="hashtags">
        <v-tabs v-model="activeTab" dark slider-color="teal">

            <v-tab ripple>Dashboard</v-tab>
            <v-tab-item class="p-4">
                <v-layout row fill-height align-start justify-space-between :grid-list-xs="true">
                    <v-flex xs24 md3 class="px-2">
                        <v-card>
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Top 100 Hashtags</h3>
                                    <span>Try filtering globally to see more specific lists.</span><br />
                                    <strong><em>This will ignore the "Hashtag" filter.</em></strong>
                                </div>
                            </v-card-title>
                            <v-layout row fill-height align-center justify-center v-if="topListLoading" class="m-4 p-4">
                                <v-progress-circular class="load-filler" indeterminate></v-progress-circular>
                            </v-layout>
                            <div v-if="!topListLoading">
                                <top-list :items="top">
                                    <template slot-scope="listItemScope">
                                        <div class="guage-content" v-on:click="filterByHashtag(listItemScope.item.hashtag);">
                                            <span class="muted">{{listItemScope.index+1}}.</span>
                                            {{listItemScope.item.hashtag}}
                                        </div>
                                        <div class="guage">
                                            <div class="guage__filler" v-bind:style="{width: getTopCountPercentage(listItemScope.item.count)+'%'}"></div>
                                        </div>
                                    </template>
                                </top-list>
                            </div>
                        </v-card>
                    </v-flex>

                    <v-flex xs12 md9 class="pr-4" row wrap>
                        <v-flex xs12>
                            <v-card class="mb-3">
                                <v-card-title primary-title>
                                    <div>
                                        <h3 class="headline mb-0">Hashtags Over Time</h3>
                                        <span>Try filtering globally to see more specific graphs.</span>
                                    </div>
                                </v-card-title>
                                <v-layout row fill-height align-center justify-center v-if="overTimeLoading" class="m-4 p-4">
                                    <v-progress-circular class="load-filler" indeterminate></v-progress-circular>
                                </v-layout>
                                <div v-if="!overTimeLoading" class="p-2">
                                    <line-chart :chart-data="summary" :height="200"></line-chart>
                                </div>
                            </v-card>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-card class="mb-3 mr-3">
                                <v-card-title primary-title>
                                    <div>
                                        <h3 class="headline mb-0">Hashtags By Categories</h3>
                                        <span>Try filtering globally to see more specific graphs.</span>
                                    </div>
                                </v-card-title>
                                <v-layout row fill-height align-center justify-center v-if="categoryTotalsLoading" class="m-4 p-4">
                                    <v-progress-circular class="load-filler" indeterminate></v-progress-circular>
                                </v-layout>
                                <div v-if="!categoryTotalsLoading" class="p-2">
                                    <pie-chart :chart-data="categoryTotalsForChart" :height="200"></pie-chart>
                                </div>
                            </v-card>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-card class="mb-3">
                                <v-card-title primary-title>
                                    <div>
                                        <h3 class="headline mb-0">Hashtags By Category</h3>
                                        <span>Try filtering globally to see more specific graphs.</span>
                                    </div>
                                </v-card-title>
                                <v-layout row fill-height align-center justify-center v-if="categoryTotalsLoading" class="m-4 p-4">
                                    <v-progress-circular class="load-filler" indeterminate></v-progress-circular>
                                </v-layout>
                                <div v-if="!categoryTotalsLoading" class="p-2">
                                    <v-list dense class="hoverable">
                                        <v-list-tile v-for="item in categoryTotals" v-bind:key="item.account_category">
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ item.account_category }}</v-list-tile-title>
                                            </v-list-tile-content>

                                            <v-list-tile-action>
                                                {{item.count}}
                                            </v-list-tile-action>
                                        </v-list-tile>
                                        <v-list-tile class="mt-3">
                                            <v-list-tile-content>
                                                <v-list-tile-title><span class="title">Total</span></v-list-tile-title>
                                            </v-list-tile-content>

                                            <v-list-tile-action>
                                                <span class="title">{{categoryTotal}}</span>
                                            </v-list-tile-action>
                                        </v-list-tile>
                                    </v-list>
                                </div>
                            </v-card>
                        </v-flex>
                    </v-flex>
                </v-layout>
            </v-tab-item>

            <v-tab ripple>Data Browser</v-tab>
            <v-tab-item class="p-4">
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">Data Browser</h3>
                            <span>A table-style data browser is coming soon.</span>
                        </div>
                    </v-card-title>
                </v-card>
            </v-tab-item>

            <global-filter></global-filter>
        </v-tabs>
    </div>
</template>
<style lang="scss">
    .guage {
        height: 100%;
        position: absolute;
        width: 100%;
        opacity: 0.3;
        transition: 300ms opacity;
        left: 0;
        top: 0;
        z-index: 0;

        &__filler {
            background: #000000;
            position: absolute;
            width: 0;
            left: 0;
            top: 0;
            height: 100%;
            transition: 300ms width;
        }
    }
    .guage-content {
        z-index: 2;
        padding: 0 10px;
        position: relative;
    }
    .v-list__tile__title{
        position: relative;

        &:hover{
            .guage {
                opacity: 0.95;
            }
        }
    }
    .load-filler{
        display: block;
        text-align: center;
        vertical-align: middle;
        margin: 0 auto;
    }
</style>
<script>
    import {ScaleLoader} from 'vue-spinner/dist/vue-spinner'
    import TopList from './cards/TopList.vue'
    import GlobalFilter from './GlobalFilter.vue'
    import LineChart from './charts/LineChart.vue'
    import PieChart from './charts/PieChart.vue'
    export default {
        components: {
            ScaleLoader,
            TopList,
            GlobalFilter,
            LineChart,
            PieChart
        },
        data: function(){
            return {
                topListLoading: false,
                overTimeLoading: false,
                categoryTotalsLoading: false,
                activeTab: 'dashboard'
            }
        },
        mounted() {
            this.loadData();
        },
        methods: {
            loadData() {
                this.loadTopList();
                this.loadSummary();
                this.loadCategoryTotals();
            },
            loadTopList(){
                let thisComponent = this;
                thisComponent.setTopListLoading(true);
                this.$store.dispatch('hashtags/top', 100)
                    .then(function(){
                        thisComponent.setTopListLoading(false);
                    });
            },
            loadSummary(){
                let thisComponent = this;
                thisComponent.setOverTimeLoading(true);
                this.$store.dispatch('hashtags/summary')
                    .then(function(){
                        thisComponent.setOverTimeLoading(false);
                    });
            },
            loadCategoryTotals(){
                let thisComponent = this;
                thisComponent.setCategoryTotalsLoading(true);
                this.$store.dispatch('hashtags/categoryTotals')
                    .then(function(){
                        thisComponent.setCategoryTotalsLoading(false);
                    });
            },
            setTopListLoading(loading){
                this.topListLoading = loading;
            },
            setOverTimeLoading(loading){
                this.overTimeLoading = loading;
            },
            setCategoryTotalsLoading(loading){
                this.categoryTotalsLoading = loading;
            },
            getTopCountPercentage(count){
                return Math.round(count/this.topCount * 100);
            },
            filterByHashtag(hashtag){
                let thisComponent = this;
                this.$store.commit('filter/setHashtagFilter', hashtag);
                this.$store.dispatch('filter/save');
            }
        },
        computed: {
            top: function(){
                return this.$store.getters['hashtags/getTop'];
            },
            topCount: function(){
                return this.$store.getters['hashtags/getTopCount'];
            },
            summary: function(){
                return this.$store.getters['hashtags/getSummaryDataForChart'];
            },
            categoryTotalsForChart: function(){
                return this.$store.getters['hashtags/getCategoryTotalsForChart'];
            },
            categoryTotals: function(){
                return this.$store.getters['hashtags/getCategoryTotals'];
            },
            categoryTotal: function(){
                return this.$store.getters['hashtags/getCategoryTotal'];
            },
            isFilterSaved:  function(){
                return this.$store.getters['filter/getSaved'];
            }
        },
        watch: {
            isFilterSaved (newState, oldState){
                if(oldState === true && newState === false){
                    this.loadData();
                }
            }
        }
    }
</script>