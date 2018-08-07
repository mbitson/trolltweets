<template>
    <v-bottom-sheet v-model="isFilterOpen">
        <v-btn slot="activator" absolute dark right color="purple global-filter white--text" class="global-filter">
            <v-icon>fa-gear</v-icon>
            <span>Filter All Data</span>
            <span v-if="activeCount>0"> - {{activeCount}} Active Filters</span>
        </v-btn>

        <v-list>
            <v-layout column>
                <v-layout row align-content-center justify-end class="p-3 px-4">
                    <v-btn dark color="purple global-filter white--text" v-on:click="clearFilters()">
                        Clear Filters
                    </v-btn>
                </v-layout>
                <v-layout row class="px-3">
                    <v-flex xs12 sm6 md3 class="m-3">
                        <v-text-field
                                label="Hashtag (Exact Matches Only)"
                                placeholder="i.e. #MAGA"
                                box
                                v-model="activeFilters.hashtag[0]"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 class="m-3">
                        <v-text-field
                                label="All Hashtags with this text"
                                placeholder="i.e. livesmatter"
                                box
                                v-model="activeFilters.hashtagText[0]"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 class="m-3">
                        <v-text-field
                                label="Author (Exact Matches Only)"
                                placeholder="i.e. screamymonkey"
                                box
                                v-model="activeFilters.author[0]"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 class="m-3">
                        <v-text-field
                                label="All Authors with this text"
                                placeholder="i.e. voice"
                                box
                                v-model="activeFilters.authorText[0]"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 class="m-3">
                        <v-text-field
                                label="All Tweets with this text"
                                placeholder="i.e. witch hunt"
                                box
                                v-model="activeFilters.tweetText[0]"
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-layout>
        </v-list>
    </v-bottom-sheet>
</template>
<style lang="scss">
    .global-filter {
        z-index:2;
        top: 6px;

        .fa{
            margin-right: 5px;
        }

        .fa:before{
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            transition: 600ms text-shadow;
        }
        &:hover{
            .fa:before{
                text-shadow: 0 2px 20px rgba(0,0,0,0.5);
            }
        }
    }
</style>
<script>
    export default {
        computed: {
            isFilterOpen: {
                get: function(){
                    return this.$store.getters['filter/getSheetOpen'];
                },
                set: function(value){
                    return this.$store.commit('filter/setSheetOpen', value);
                }
            },
            activeFilters: {
                get: function(){
                    return this.$store.getters['filter/getActive'];
                },
                set: function(value){
                    return this.$store.commit('filter/setActive', value);
                }
            },
            activeCount: function(){
                return this.$store.getters['filter/getActiveCount'];
            }
        },
        methods: {
            clearFilters(){
                this.$store.commit('filter/clear');
            }
        },
        watch: {
            isFilterOpen (newState, oldState){
                if(oldState === true && newState === false){
                    this.$store.dispatch('filter/save');
                }
            }
        }
    }
</script>