<template>
    <div class="hashtags">
        <v-tabs v-model="activeTab" dark slider-color="teal">

            <v-tab ripple>Dashboard</v-tab>
            <v-tab-item class="p-4">
                <v-layout row fill-height align-start justify-space-between :grid-list-xs="true">
                    <v-flex xs24 md3>
                        <v-card class="mx-2">
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Top 100 Hashtags</h3>
                                </div>
                            </v-card-title>
                            <v-layout row fill-height align-center justify-center v-if="isLoading" class="loader m-4 p-4">
                                <scale-loader :loading="isLoading"></scale-loader>
                            </v-layout>
                            <div v-if="!isLoading">
                                <top-list :items="top">
                                    <template slot-scope="listItemScope">
                                        <span class="muted">{{listItemScope.index+1}}.</span> {{listItemScope.item.hashtag}}
                                    </template>
                                </top-list>
                            </div>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 md9>
                        <v-card class="mx-2">
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Hashtags Over Time</h3>
                                    <div>Coming Soon</div>
                                </div>
                            </v-card-title>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>

            <v-tab ripple>Data Browser</v-tab>
            <v-tab-item class="p-4">
                Data Grid Here.
            </v-tab-item>
        </v-tabs>
    </div>
</template>
<script>
    import {ScaleLoader} from 'vue-spinner/dist/vue-spinner'
    import TopList from './cards/TopList.vue'
    export default {
        components: {
            ScaleLoader,
            TopList
        },
        data: function(){
            return {
                isLoading: false,
                activeTab: 'dashboard'
            }
        },
        mounted() {
            let thisComponent = this;
            thisComponent.loading(true);
            this.$store.dispatch('hashtags/top', 100)
                .then(function(){
                    thisComponent.loading(false);
                });
        },
        methods: {
            loading(loading){
                this.isLoading = loading;
            }
        },
        computed: {
            top: function(){
                return this.$store.getters['hashtags/getTop']();
            }
        }
    }
</script>