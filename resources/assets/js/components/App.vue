<template>
    <div class="app-container">
        <navigation></navigation>
        <v-app dark>
            <router-view></router-view>
        </v-app>
    </div>
</template>
<script>
    import Navigation from './Navigation.vue'
    export default {
        components: {
            Navigation
        },
        mounted(){
            this.$store.dispatch('filter/load');
        },
        watch: {
            $route (to, from){
                this.$ga.page(to);
            }
        }
    }
</script>