<template>
    <div class="summary">
        <v-jumbotron>
            <v-container fill-height>
                <v-layout align-center>
                    <v-flex>
                        <h3 class="display-3 font-weight-thin">TrollTweets</h3>

                        <span class="subheading">The tweets used on this site are the 3 million tweets shared by <a href="https://fivethirtyeight.com/features/why-were-sharing-3-million-russian-troll-tweets/">FiveThirtyEight</a> on <a href="https://github.com/fivethirtyeight/russian-troll-tweets/">GitHub</a>. This tool simply attempts to make that data easier to visualize and navigate, in the hopes of finding relavent patterns.</span>

                        <v-divider class="my-3"></v-divider>

                    </v-flex>
                </v-layout>
            </v-container>
        </v-jumbotron>
        <v-container>
            <span class="subheading">
                This data includes:
            </span>
            <v-layout flex row wrap>
                <v-flex xs3>
                    <v-card color="cyan darken-1" class="summary__data white--text">
                        <v-card-title primary-title v-on:click="$router.push({'name':'tweets'})">
                            <div class="headline font-weight-thin" v-if="!tweetCountLoading">{{tweetCount}}</div>
                            <div class="summary__data__subheading font-weight-black" v-if="!tweetCountLoading">Tweets</div>
                            <v-progress-circular class="load-filler" indeterminate v-if="tweetCountLoading"></v-progress-circular>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <v-flex xs3>
                    <v-card color="purple darken-1" class="summary__data white--text">
                        <v-card-title primary-title v-on:click="$router.push({'name':'hashtags'})">
                            <div class="headline font-weight-thin" v-if="!hashtagCountLoading">{{hashtagCount}}</div>
                            <div class="summary__data__subheading font-weight-black" v-if="!hashtagCountLoading">Hashtags</div>
                            <v-progress-circular class="load-filler" indeterminate v-if="hashtagCountLoading"></v-progress-circular>
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<style lang="scss">
    .summary{
        &__data{
            margin-left: 17px;
            cursor: pointer;

            .headline {
                text-align: center;
                width: 100%;
            }

            &__subheading{
                display: block;
                text-transform: uppercase;
                text-align: center;
                width: 100%;
            }
        }
    }
    .load-filler{
        display: block;
        text-align: center;
        vertical-align: middle;
        margin: 0 auto;
    }
    .v-jumbotron{
        margin-top: 50px;
        height: auto !important;
    }
</style>
<script>
    export default {
        data: function(){
            return {
                tweetCountLoading: true,
                hashtagCountLoading: true
            }
        },
        methods: {
            setTweetLoad(status){
                this.tweetCountLoading = status;
            },
            setHashtagLoad(status){
                this.hashtagCountLoading = status;
            }
        },
        mounted() {
            let thisComponent = this;
            this.$store.dispatch('tweets/count')
                .then(function(){
                    thisComponent.setTweetLoad(false);
                });
            this.$store.dispatch('hashtags/count')
                .then(function(){
                    thisComponent.setHashtagLoad(false);
                });
        },
        computed: {
            tweetCount: function(){
                return this.$store.getters['tweets/getCount']().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            hashtagCount: function(){
                return this.$store.getters['hashtags/getCount']().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }
</script>
