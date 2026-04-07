<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Zap, Activity } from 'lucide-vue-next';

import EventFilters from './Partials/EventFilters.vue';
import EventTable from './Partials/EventTable.vue';
import EventModal from './Partials/EventModal.vue';

const props = defineProps({
    events: Object,
    devices: Array,
    eventTypes: Array,
    filters: Object,
});

const isModalOpen = ref(false);

const handleFilter = (filters) => {
    router.get(route('events.index'), filters, { 
        preserveState: true, 
        replace: true, 
        preserveScroll: true 
    });
};

const openSimulator = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};
</script>

<template>
    <Head title="Eventos" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Eventos del Sistema</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Eventos de infraestructura técnica.</p>
                </div>
                <PrimaryButton @click="openSimulator">
                    <Zap class="w-4 h-4 mr-2" />
                    Simular Señal
                </PrimaryButton>
            </div>

            <EventFilters 
                :filters="filters" 
                :devices="devices"
                :eventTypes="eventTypes"
                @filter="handleFilter"
            />

            <EventTable 
                :events="events" 
            />

        </div>

        <EventModal 
            :show="isModalOpen" 
            :devices="devices"
            :eventTypes="eventTypes"
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
