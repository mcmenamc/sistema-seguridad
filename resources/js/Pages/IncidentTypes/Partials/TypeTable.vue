<script setup>
import { MoreHorizontal, Edit, Trash2, Zap, Network, Thermometer, RefreshCw, Eye, Tag } from 'lucide-vue-next';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import DeleteConfirmModal from '@/Components/DeleteConfirmModal.vue';
import Dropdown from '@/Components/Dropdown.vue';

const props = defineProps({
    types: Object,
});

const emit = defineEmits(['edit']);

const iconMap = {
    Zap, Network, Thermometer, RefreshCw, Eye, Tag
};

const isDeleteModalOpen = ref(false);
const typeToDelete = ref(null);

const openDeleteModal = (type) => {
    typeToDelete.value = type;
    isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
    router.delete(route('incident-types.destroy', typeToDelete.value.id), {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        }
    });
};
</script>

<template>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden font-sans">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Protocolo de Alerta</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="type in types.data" :key="type.id" class="hover:bg-slate-50/20 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-slate-800 uppercase tracking-tight">{{ type.name }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="p-1.5 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-900 transition-all active:scale-90">
                                        <MoreHorizontal class="w-4 h-4" />
                                    </button>
                                </template>
                                <template #content>
                                    <div class="p-1">
                                        <button 
                                            @click="emit('edit', type)"
                                            class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                                        >
                                            <Edit class="w-3.5 h-3.5 text-slate-400" />
                                            <span>Editar</span>
                                        </button>
                                        <div class="h-px bg-slate-100 my-1"></div>
                                        <button 
                                            @click="openDeleteModal(type)"
                                            class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                        >
                                            <Trash2 class="w-3.5 h-3.5 text-red-400" />
                                            <span>Eliminar Registro</span>
                                        </button>
                                    </div>
                                </template>
                            </Dropdown>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
            <Pagination :meta="types" />
        </div>

        <DeleteConfirmModal 
            :show="isDeleteModalOpen" 
            title="¿Confirmar Purga Técnica?"
            message="El protocolo será eliminado del catálogo central de incidentes."
            @close="isDeleteModalOpen = false" 
            @confirm="confirmDelete" 
        />
    </div>
</template>
