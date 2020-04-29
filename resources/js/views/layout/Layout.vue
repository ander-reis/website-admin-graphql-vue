<template>
    <div>
        <toolbar-component v-model="drawer"></toolbar-component>

        <menu-component v-model="drawer"></menu-component>

        <v-spacer></v-spacer>

        <v-container grid-list-md>
            <v-layout>
                <v-flex xs12>
                    <slot></slot>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import MenuComponent from '../../components/MenuComponent';
    import ToolbarComponent from "../../components/ToolbarComponent";

    export default {
        name: "Layout",
        components: {
            ToolbarComponent,
            MenuComponent,
        },
        data: () => ({
            drawer: false
        })
    }
</script>

<style scoped>

</style>
