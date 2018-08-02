<template>
    <div class="global-filter">
        <v-btn absolute dark right color="purple global-filter white--text">
            <v-icon>fa-gear</v-icon>
            <span>Filter All Data</span>
        </v-btn>
    </div>
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