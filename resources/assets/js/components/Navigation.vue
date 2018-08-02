<template>
    <div class="navigation">
        <v-toolbar class="cyan">
            <v-toolbar-title>Troll Tweets</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat v-on:click="$router.push({'name':'tweets'})">Tweets</v-btn>
                <v-btn flat v-on:click="$router.push({'name':'timeline'})">Timeline</v-btn>
                <v-btn flat v-on:click="$router.push({'name':'hashtags'})">Hashtags</v-btn>
                <v-btn flat v-on:click="$router.push({'name':'links'})">Links</v-btn>
                <v-btn flat v-on:click="$router.push({'name':'keywords'})">Keywords</v-btn>
                <v-btn flat v-on:click="$router.push({'name':'summary'})">About This Tool</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-btn absolute dark bottom right color="purple global-filter white--text">
            <v-icon>fa-gear</v-icon>
            <span>Filter All Data</span>
        </v-btn>
    </div>
</template>
<script>
    export default {
        data: function(){
            return {
            }
        },
        methods: {
        }
    }
</script>
<style lang="scss">
    .navigation{
        position: relative;
        overflow: visible;

        .global-filter {
            bottom: -42px !important;
            z-index:2;

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
    }
</style>