<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Plus, Tag, Search, X } from 'lucide-vue-next';

import TypeFilters from './Partials/TypeFilters.vue';
import TypeTable from './Partials/TypeTable.vue';
import TypeModal from './Partials/TypeModal.vue';

const props = defineProps({
    types: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const editingType = ref(null);

const handleFilter = (filters) => {
    router.get(route('incident-types.index'), filters, { 
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
    <Head title="Catálogo de Protocolos" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight flex items-center">
                        Tipos de Alerta
                    </h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Gestión integral de catálogos.</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    <Plus class="w-4 h-4 mr-2" />
                    Nuevo Tipo
                </PrimaryButton>
            </div>

            <TypeFilters 
                :filters="filters" 
                @filter="handleFilter"
            />

            <TypeTable 
                :types="types" 
                @edit="openEditModal" 
            />

        </div>

        <TypeModal 
            :show="isModalOpen" 
            :type="editingType" 
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
