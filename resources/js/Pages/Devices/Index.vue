<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Plus, Activity, AlertTriangle, MonitorOff } from 'lucide-vue-next';

import DeviceFilters from './Partials/DeviceFilters.vue';
import DeviceTable from './Partials/DeviceTable.vue';
import DeviceModal from './Partials/DeviceModal.vue';

const props = defineProps({
    devices: Object,
    filters: Object,
    clients: Array,
    deviceTypes: Array,
});

const isModalOpen = ref(false);
const editingDevice = ref(null);

const statusOptions = [
    { id: 'activo', name: 'Activo', icon: Activity },
    { id: 'alerta', name: 'Alerta', icon: AlertTriangle },
    { id: 'inactivo', name: 'Inactivo', icon: MonitorOff },
];

const handleFilter = (filters) => {
    router.get(route('devices.index'), filters, { 
        preserveState: true, 
        replace: true, 
        preserveScroll: true 
    });
};

const openCreateModal = () => {
    editingDevice.value = null;
    isModalOpen.value = true;
};

const openEditModal = (device) => {
    editingDevice.value = device;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingDevice.value = null;
};
</script>

<template>
    <Head title="Tipos de Hardware" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Dispositivos</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Gestión integral de infraestructura técnica.</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    <Plus class="w-4 h-4 mr-2" />
                    Registrar dispositivo
                </PrimaryButton>
            </div>

            <DeviceFilters 
                :filters="filters" 
                :clients="clients" 
                :statusOptions="statusOptions"
                :deviceTypes="deviceTypes"
                @filter="handleFilter"
            />

            <DeviceTable 
                :devices="devices" 
                @edit="openEditModal" 
            />

        </div>

        <DeviceModal 
            :show="isModalOpen" 
            :device="editingDevice" 
            :clients="clients" 
            :deviceTypes="deviceTypes"
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
