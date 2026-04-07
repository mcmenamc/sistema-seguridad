<script setup>
import { ref } from 'vue';
import { MoreHorizontal, Trash2, Eye } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DeleteConfirmModal from '@/Components/DeleteConfirmModal.vue';

const props = defineProps({
    types: Object,
});

const emit = defineEmits(['edit']);

const showDeleteModal = ref(false);
const typeToDelete = ref(null);

const confirmDelete = (type) => {
    typeToDelete.value = type;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route('device-types.destroy', typeToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            typeToDelete.value = null;
        },
        onError: (errors) => alert(errors.error)
    });
};
</script>

<template>
    <div class="space-y-4">
        <DeleteConfirmModal 
            :show="showDeleteModal"
            :confirmName="typeToDelete?.name || ''"
            title="Eliminar Clasificación Técnica"
            message="Si eliminas esta categoría, asegúrate de que no tenga dispositivos vinculados o el sistema impedirá el borrado."
            @close="showDeleteModal = false"
            @confirm="executeDelete"
        />
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Categoría Técnica</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Conteo Equipos</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Alta en Catálogo</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-sans">
                    <tr v-for="type in types.data" :key="type.id" class="hover:bg-slate-50/20 transition-all group">
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 text-[11px] tracking-tight uppercase">{{ type.name }}</span>
                                <span class="text-[9px] text-slate-400 font-semibold tracking-tight uppercase">Hardware de Seguridad</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2 border rounded-full px-3 py-0.5 border-slate-100 inline-flex bg-slate-50/30">
                                <span class="text-[10px] text-slate-600 font-bold tracking-tight uppercase">{{ type.devices_count || 0 }} Equipos</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                             <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tighter">
                                    {{ new Date(type.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' }) }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-semibold tracking-tighter uppercase">
                                    {{ new Date(type.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end">
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
                                                <Eye class="w-3.5 h-3.5 text-slate-400" />
                                                <span>Ver Detalles</span>
                                            </button>
                                            <div class="h-px bg-slate-100 my-1"></div>
                                            <button 
                                                @click="confirmDelete(type)"
                                                class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            >
                                                <Trash2 class="w-3.5 h-3.5 text-red-400" />
                                                <span>Eliminar Registro</span>
                                            </button>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            
            <div class="px-6 py-4 bg-slate-50/20 border-t border-slate-100">
                <Pagination :meta="types" />
            </div>
        </div>
    </div>
</template>
