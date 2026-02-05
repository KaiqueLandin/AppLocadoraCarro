<script setup>
import { ref } from 'vue'

const drawer = ref(null)
</script>

<script>
export default {
    data: () => ({ drawer: null }),
}

const link = ref([
    { icon: 'mdi-home', text: 'Home', to: '/app/index' },
    { icon: 'mdi mdi-car-info', text: 'Marca', to: '/app/marca/index' },
    { icon: 'mdi-car-arrow-right', text: 'Modelo', to: '/app/modelo/index' },
    { icon: 'mdi-car', text: 'Carro', to: '/app/carro/index' },
    { icon: 'mdi mdi-car-select', text: 'Locação', to: '/app/locacao/index' },
    { icon: 'mdi-cog', text: 'Settings', to: '/app/configuracao/index' },
]);

</script>
<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer">
            <v-list>
                <v-list-item prepend-avatar="https://cdn.vuetifyjs.com/images/john.png" subtitle="john@google.com"
                    title="John Leider">
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <!-- PROFILE USER -->
            <v-list :lines="false" density="compact" nav>
                <v-list-item v-for="(item, i) in items" :key="i" :value="item" color="primary">
                    <template v-slot:prepend>
                        <v-icon :icon="item.icon"></v-icon>
                    </template>
                    <v-list-item-title v-text="item.text"></v-list-item-title>
                </v-list-item>
            </v-list>

            <!-- LINKS MENU -->
            <v-list density="compact">
                <v-list-item v-for="(item, index) in link" :key="index" :value="item" :to="item.to" color="primary">
                    <template v-slot:prepend>
                        <v-icon :icon="item.icon"></v-icon>
                    </template>

                    <v-list-item-title v-text="item.text"></v-list-item-title>
                </v-list-item>
                
            </v-list>

        </v-navigation-drawer>

        <v-app-bar>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

            <v-app-bar-title>Dashboard</v-app-bar-title>
        </v-app-bar>

        <v-main>
            <slot></slot>
        </v-main>
    </v-app>
</template>