<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Plus, Activity, MonitorOff } from 'lucide-vue-next';

import IncidentFilters from './Partials/IncidentFilters.vue';
import IncidentTable from './Partials/IncidentTable.vue';
import IncidentModal from './Partials/IncidentModal.vue';

const props = defineProps({
    incidents: Object,
    filters: Object,
    devices: Array,
    users: Array,
    eventTypes: Array,
});

const isModalOpen = ref(false);
const editingIncident = ref(null);

const statusOptions = [
    { id: 'pendiente', name: 'Pendiente', icon: MonitorOff },
    { id: 'en proceso', name: 'En Proceso', icon: Activity },
    { id: 'resuelto', name: 'Resuelto', icon: Activity },
];

const handleFilter = (filters) => {
    router.get(route('incidents.index'), filters, { 
        preserveState: true, 
        replace: true, 
        preserveScroll: true 
    });
};

const openCreateModal = () => {
    editingIncident.value = null;
    isModalOpen.value = true;
};

const openEditModal = (incident) => {
    editingIncident.value = incident;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingIncident.value = null;
};
</script>

<template>
    <Head title="Bitácora de Incidencias" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Incidencias</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Resolución técnica y seguimiento forense de anomalías.</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    <Plus class="w-4 h-4 mr-2" />
                    Reportar incidencia
                </PrimaryButton>
            </div>

            <IncidentFilters 
                :filters="filters" 
                :devices="devices"
                :statusOptions="statusOptions"
                @filter="handleFilter"
            />

            <IncidentTable 
                :incidents="incidents" 
                @edit="openEditModal" 
            />

        </div>

        <IncidentModal 
            :show="isModalOpen" 
            :incident="editingIncident" 
            :devices="devices"
            :users="users"
            :eventTypes="eventTypes"
            :statusOptions="statusOptions"
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
