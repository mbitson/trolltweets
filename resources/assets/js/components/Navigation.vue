<template>
    <div class="navigation">
        <v-toolbar dark scroll-off-screen class="deep-purple darken-2">
            <v-toolbar-title class="white--text">Troll Tweets</v-toolbar-title>
            <v-spacer></v-spacer>
            <div>
                <v-btn small flat v-on:click="$router.push({'name':'tweets'})">Tweets</v-btn>
                <v-btn small flat v-on:click="$router.push({'name':'hashtags'})">Hashtags</v-btn>
                <!--<v-btn small flat v-on:click="$router.push({'name':'links'})">Links</v-btn>-->
                <!--<v-btn small flat v-on:click="$router.push({'name':'keywords'})">Keywords</v-btn>-->
                <!--<v-btn small flat v-on:click="$router.push({'name':'timeline'})">Timeline</v-btn>-->
                <v-btn small flat v-on:click="$router.push({'name':'summary'})">About This Tool</v-btn>
            </div>
        </v-toolbar>
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