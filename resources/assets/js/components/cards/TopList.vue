<template>
    <div>
        <v-list dense>
            <v-list-tile v-for="(item, key) in items" v-bind:key="key">
                <v-list-tile-content>
                    <v-list-tile-title>
                        <slot v-bind:index="key" v-bind:item="item"></slot>
                    </v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </div>
</template>
<script>
    export default {
        props: [ 'items' ]
    }
</script>
<style lang="scss">
    .loader{
        width: 100%;
    }
    .muted{
        opacity: 0.3;
        transition: 600ms opacity;
    }
    .v-list__tile__title{
        &:hover{
            .muted {
                opacity: 0.75;
            }
        }
    }
    .v-list--dense .v-list__tile:not(.v-list__tile--avatar) {
        height: 24px;
    }
</style>