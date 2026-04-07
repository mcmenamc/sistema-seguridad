<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { MoreHorizontal, Trash2, CheckCircle2, XCircle, Clock } from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DeleteConfirmModal from '@/Components/DeleteConfirmModal.vue';

const props = defineProps({
    events: Object,
});

const showDeleteModal = ref(false);
const eventToDelete = ref(null);

const confirmDelete = (event) => {
    eventToDelete.value = event;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route('events.destroy', eventToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            eventToDelete.value = null;
        }
    });
};

const getStatusBadge = (type) => {
    const badges = {
        'Desconexión de Red': 'bg-red-50 text-red-600 border border-red-100',
        'Anomalía de Voltaje': 'bg-orange-50 text-orange-600 border border-orange-100',
        'Alerta Térmica': 'bg-red-50 text-red-600 border border-red-100',
        'Reinicio Forzado': 'bg-blue-50 text-blue-600 border border-blue-100',
        'Actividad Sospechosa': 'bg-red-50 text-red-600 border border-red-100',
    };
    return badges[type] || 'bg-slate-50 text-slate-500 border border-slate-100';
};

</script>

<template>
    <div class="space-y-4">
        <DeleteConfirmModal 
            :show="showDeleteModal"
            :confirmName="eventToDelete?.type || ''"
            title="Eliminar Registro de Evento"
            message="Esta acción es irreversible y eliminará esta señal del historial forense."
            @close="showDeleteModal = false"
            @confirm="executeDelete"
        />

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Sensor / Equipo</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Tipo</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Fecha</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        <tr v-for="event in events.data" :key="event.id" class="hover:bg-slate-50/20 transition-all">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-semibold text-slate-900 tracking-tight uppercase leading-none">{{ event.device?.name || '' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span :class="getStatusBadge(event.type)" class="px-3 py-0.5 rounded-full text-[9px] font-bold tracking-tighter uppercase border shadow-sm">
                                    {{ event.type }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tighter">
                                        {{ new Date(event.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' }) }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-tighter mt-0.5">
                                        {{ new Date(event.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}
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
                                                <div v-if="event.incident_id" class="px-3 py-2 text-[9px] font-bold text-slate-400 uppercase border-b border-slate-50 mb-1">
                                                    Auditoría de Incidencia Activada
                                                </div>
                                                <button 
                                                    @click="confirmDelete(event)"
                                                    class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                >
                                                    <Trash2 class="w-3.5 h-3.5 text-red-400" />
                                                    <span>Borrar Registro</span>
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
                <Pagination :meta="events" />
            </div>
        </div>
    </div>
</template>
