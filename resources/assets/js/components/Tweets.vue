<template>
    <div class="tweets">
        <v-tabs v-model="activeTab" dark slider-color="teal">

            <v-tab ripple>Dashboard</v-tab>
            <v-tab-item class="p-4">
                <v-layout row fill-height align-start justify-space-between :grid-list-xs="true">
                    <v-flex xs24 md3>
                        <v-card class="mx-2">
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Top 100 Tweet Authors</h3>
                                    <span>Try filtering globally to see more specific lists.</span>
                                </div>
                            </v-card-title>
                            <v-layout row fill-height align-center justify-center v-if="topListLoading" class="m-4 p-4">
                                <v-progress-circular class="load-filler" indeterminate></v-progress-circular>
                            </v-layout>
                            <div v-if="!topListLoading">
                                <top-list :items="top">
                                    <template slot-scope="listItemScope">
                                        <div class="guage-content">
                                            <span class="muted">{{listItemScope.index+1}}.</span>
                                            {{listItemScope.item.author}}
                                        </div>
                                        <div class="guage">
                                            <div class="guage__filler" v-bind:style="{width: getTopCountPercentage(listItemScope.item.count)+'%'}"></div>
                                        </div>
                                    </template>
                                </top-list>
                            </div>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 md9>
                        <v-card class="mx-2">
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Tweets Over Time</h3>
                                    <span>Try filtering globally to see more specific graphs.</span>
                                </div>
                            </v-card-title>
                            <v-layout row fill-height align-center justify-center v-if="overTimeLoading" class="m-4 p-4">
                                <v-progress-circular class="load-filler" indeterminate></v-progress-circular>
                            </v-layout>
                            <div v-if="!overTimeLoading" class="p-2">
                                <line-chart :chart-data="summary" :height="300"></line-chart>
                            </div>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>

            <v-tab ripple>Data Browser</v-tab>
            <v-tab-item class="p-4">
                Data Grid Here.
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
    export default {
        components: {
            ScaleLoader,
            TopList,
            GlobalFilter,
            LineChart
        },
        data: function(){
            return {
                topListLoading: false,
                overTimeLoading: false,
                activeTab: 'dashboard'
            }
        },
        mounted() {
            // Prep to load all cards
            let thisComponent = this;

            // Load top list
            thisComponent.setTopListLoading(true);
            this.$store.dispatch('tweets/top', 100)
                .then(function(){
                    thisComponent.setTopListLoading(false);
                });

            // Load over time
            thisComponent.setOverTimeLoading(true);
            this.$store.dispatch('tweets/summary')
                .then(function(){
                    thisComponent.setOverTimeLoading(false);
                });
        },
        methods: {
            setTopListLoading(loading){
                this.topListLoading = loading;
            },
            setOverTimeLoading(loading){
                this.overTimeLoading = loading;
            },
            getTopCountPercentage(count){
                return Math.round(count/this.topCount * 100);
            }
        },
        computed: {
            top: function(){
                return this.$store.getters['tweets/getTop']();
            },
            topCount: function(){
                return this.$store.getters['tweets/getTopCount']();
            },
            summary: function(){
                return this.$store.getters['tweets/getSummaryDataForChart']();
            }
        }
    }
</script>