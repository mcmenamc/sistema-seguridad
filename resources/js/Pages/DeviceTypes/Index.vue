<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Plus } from 'lucide-vue-next';

import DeviceTypeTable from './Partials/DeviceTypeTable.vue';
import DeviceTypeModal from './Partials/DeviceTypeModal.vue';
import DeviceTypeFilters from './Partials/DeviceTypeFilters.vue';

const props = defineProps({
    types: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const editingType = ref(null);

const handleFilter = (filters) => {
    router.get(route('device-types.index'), filters, { 
        preserveState: true, 
        replace: true, 
        preserveScroll: true 
    });
};

const openCreateModal = () => {
    editingType.value = null;
    isModalOpen.value = true;
};

const openEditModal = (type) => {
    editingType.value = type;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingType.value = null;
};
</script>

<template>
    <Head title="Tipos de Hardware" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Tipos de Hardware</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Catálogo técnico de infraestructura operativa.</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    <Plus class="w-4 h-4 mr-2" />
                    Registrar Tipo
                </PrimaryButton>
            </div>

            <DeviceTypeFilters 
                :filters="filters" 
                @filter="handleFilter"
            />

            <DeviceTypeTable 
                :types="types" 
                @edit="openEditModal" 
            />

        </div>

        <DeviceTypeModal 
            :show="isModalOpen" 
            :type="editingType" 
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
