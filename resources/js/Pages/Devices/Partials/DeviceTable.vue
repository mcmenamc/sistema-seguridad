<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { MoreHorizontal, Eye, Trash2 } from 'lucide-vue-next';
import * as Icons from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';
import { useImagePreview } from '@/Composables/useImagePreview';
import Dropdown from '@/Components/Dropdown.vue';

const props = defineProps({
    devices: Object,
});

const emit = defineEmits(['edit']);

const { openPreview } = useImagePreview();

const showDeleteModal = ref(false);
const deviceToDelete = ref(null);

const confirmDelete = (device) => {
    deviceToDelete.value = device;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(route('devices.destroy', deviceToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            deviceToDelete.value = null;
        }
    });
};

const getStatusBadge = (status) => {
    const badges = {
        'activo': 'bg-green-50/50 text-green-600 border border-green-100/50',
        'inactivo': 'bg-slate-50 text-slate-500 border border-slate-100',
        'alerta': 'bg-orange-50/50 text-orange-600 border border-orange-100/50',
    };
    return badges[status] || 'bg-slate-50 text-slate-500 border border-slate-100';
};
</script>

<template>
    <div class="space-y-4">
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[850px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter lg:w-[40%]">Inventario Hardware</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Galería</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Clientes</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Alta en Sistema</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Estado</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-sans">
                    <tr v-if="devices.data.length === 0">
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-slate-50 p-3 rounded-2xl mb-3">
                                    <Icons.MonitorOff class="w-6 h-6 text-slate-300" />
                                </div>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-tight">Sin señales tácticas</h3>
                                <p class="text-[10px] text-slate-400 font-semibold tracking-tight mt-1 max-w-[200px]">No se encontraron dispositivos que coincidan con los filtros activos.</p>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="device in devices.data" :key="device.id" class="hover:bg-slate-50/20 transition-all group">
                      
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 text-[11px] tracking-tight">{{ device.name }}</span>
                                <span class="text-[9px] text-slate-400 font-semibold tracking-tight">{{ device.device_type?.name }} • {{ device.location }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="relative group inline-flex items-center justify-center">
                                <div 
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden transition-all shadow-sm cursor-zoom-in"
                                    @click="device.images?.length > 0 ? openPreview(device.images.map(img => '/storage/' + img), 0, device.name) : null"
                                >
                                     <img v-if="device.images && device.images.length > 0" :src="'/storage/' + device.images[0]" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                     <Icons.Monitor v-else class="w-4 h-4 text-slate-400 opacity-60" />
                                     
                                     <div v-if="device.images?.length > 1" class="absolute bottom-1 right-1 bg-slate-900/80 backdrop-blur-sm text-white text-[7px] font-bold px-1 rounded-md border border-white/10">
                                         +{{ device.images.length - 1 }}
                                     </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 border rounded-full px-2 py-0.5 border-slate-100 inline-flex bg-slate-50/30">
                                <span class="text-[10px] text-slate-700 font-bold tracking-tight">{{ device.client?.name || 'Sin asignar' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tighter">{{ new Date(device.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' }) }}</span>
                                <span class="text-[9px] text-slate-400 font-semibold tracking-tighter uppercase">{{ new Date(device.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span :class="getStatusBadge(device.status)" class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-tight">
                                {{ device.status }}
                            </span>
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
                                                @click="emit('edit', device)"
                                                class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                                            >
                                                <Eye class="w-3.5 h-3.5 text-slate-400" />
                                                <span>Editar Dispositivo</span>
                                            </button>
                                            
                                            <div class="h-px bg-slate-100 my-1"></div>
                                            <button 
                                                @click="confirmDelete(device)"
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
    </div>

    <Pagination :meta="devices" />
</div>
</template>
