<template>
    <v-jumbotron>
        <v-container fill-height>
            <v-layout align-center>
                <v-flex>
                    <h3 class="display-3">TrollTweets</h3>

                    <span class="subheading">The tweets used on this site are the 3 million tweets shared by <a href="https://fivethirtyeight.com/features/why-were-sharing-3-million-russian-troll-tweets/">FiveThirtyEight</a> on <a href="https://github.com/fivethirtyeight/russian-troll-tweets/">GitHub</a>. This tool simple attemps to make that data easier to visualize and navigate, in the hopes of finding relavent patterns.</span>

                    <v-divider class="my-3"></v-divider>
                </v-flex>
            </v-layout>
        </v-container>
    </v-jumbotron>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
