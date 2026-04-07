<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { MoreHorizontal, Trash2, Eye, User, Clock, AlertTriangle } from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DeleteConfirmModal from '@/Components/DeleteConfirmModal.vue';

const props = defineProps({
    incidents: Object,
});

const emit = defineEmits(['edit']);

const showDeleteModal = ref(false);
const incidentToDelete = ref(null);

const confirmDelete = (incident) => {
    incidentToDelete.value = incident;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route('incidents.destroy', incidentToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            incidentToDelete.value = null;
        }
    });
};

const getStatusBadge = (status) => {
    const badges = {
        'pendiente': 'bg-red-50 text-red-600 border border-red-100',
        'en proceso': 'bg-orange-50 text-orange-600 border border-orange-100',
        'resuelto': 'bg-green-50 text-green-600 border border-green-100',
    };
    return badges[status] || 'bg-slate-50 text-slate-500 border border-slate-100';
};
</script>

<template>
    <div v-if="incidents" class="space-y-4">
        <DeleteConfirmModal 
            :show="showDeleteModal"
            :confirmName="'I-' + (incidentToDelete?.id || '')"
            title="Descartar Incidencia Operativa"
            message="Esta acción purgará la incidencia del historial activo. Solo debe ejecutarse en casos de falsas alarmas debidamente auditadas."
            @close="showDeleteModal = false"
            @confirm="executeDelete"
        />

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Bitácora </th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Dispositivo</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Detalle Forense</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Estado</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Responsable</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Fecha de Alta</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        <tr v-for="incident in incidents?.data" :key="incident.id" class="hover:bg-slate-50/20 transition-all group">
                            
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-tighter mt-1">{{ incident.type }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-bold text-slate-800 uppercase tracking-tight leading-none">{{ incident.device?.name || 'Eq. Externo' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 max-w-[250px]">
                                <p class="text-[10px] text-slate-500 font-medium tracking-tight line-clamp-1 group-hover:line-clamp-none transition-all italic">
                                    "{{ incident.description }}"
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span :class="getStatusBadge(incident.status)" class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-tight shadow-sm border">
                                    {{ incident.status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div v-if="incident.assigned_user" class="flex items-center space-x-2 border rounded-full px-2 py-0.5 border-slate-100 inline-flex bg-slate-50/30">
                                    <span class="text-[10px] text-slate-700 font-bold tracking-tight">{{ incident.assigned_user.name }}</span>
                                </div>
                                <span v-else class="text-[9px] font-semibold text-slate-300 uppercase tracking-tighter italic">Sin Operador</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tighter leading-none">
                                        {{ new Date(incident.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' }) }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-semibold tracking-tighter uppercase mt-1">
                                        {{ new Date(incident.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}
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
                                                    @click="emit('edit', incident)"
                                                    class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                                                >
                                                    <Eye class="w-3.5 h-3.5 text-slate-400" />
                                                    <span>Administrar</span>
                                                </button>
                                                <div class="h-px bg-slate-100 my-1"></div>
                                                <button 
                                                    @click="confirmDelete(incident)"
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
            
            <div class="px-6 py-3 bg-slate-50/20 border-t border-slate-100">
                <Pagination :meta="incidents" />
            </div>
        </div>
    </div>
</template>
