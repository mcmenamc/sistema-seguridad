<script setup>
import { Clock, User as UserIcon, ShieldAlert, Activity, Database, Key } from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    logs: Object,
});

const getActionStyles = (action) => {
    const act = action.toLowerCase();
    
    if (act.includes('sesion') || act.includes('login')) 
        return { icon: Key, color: 'text-emerald-500 bg-emerald-50 border-emerald-100' };
    
    if (act.includes('creado') || act.includes('registrado')) 
        return { icon: Database, color: 'text-sky-500 bg-sky-50 border-sky-100' };
    
    if (act.includes('actualizado') || act.includes('recalibrado')) 
        return { icon: Activity, color: 'text-indigo-500 bg-indigo-50 border-indigo-100' };
    
    if (act.includes('eliminado') || act.includes('purgado') || act.includes('alerta')) 
        return { icon: ShieldAlert, color: 'text-rose-500 bg-rose-50 border-rose-100' };
    
    return { icon: Clock, color: 'text-slate-500 bg-slate-50 border-slate-100' };
};
</script>

<template>
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter w-44">Registro Temporal</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter w-52">Acción / Evento</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter w-56">Actor Responsable</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Detalle Forense</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-sans">
                    <tr v-for="log in logs.data" :key="log.id" class="hover:bg-slate-50/20 transition-all group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tighter">{{ new Date(log.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' }) }}</span>
                                <span class="text-[9px] text-slate-400 font-semibold tracking-tighter uppercase">{{ new Date(log.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div :class="[getActionStyles(log.action).color, 'px-2 py-0.5 rounded-full border flex items-center space-x-1.5 w-fit leading-none']">
                                <component :is="getActionStyles(log.action).icon" class="w-3 h-3" />
                                <span class="text-[9px] font-bold uppercase tracking-tighter whitespace-nowrap">{{ log.action }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 border rounded-full px-2 py-0.5 border-slate-100 inline-flex bg-slate-50/30">
                                <div class="w-4 h-4 bg-slate-900 text-white rounded-full flex items-center justify-center text-[8px] font-bold uppercase italic mr-1">
                                    {{ (log.user?.name || 'S').charAt(0) }}
                                </div>
                                <span class="text-[10px] text-slate-700 font-bold tracking-tight uppercase truncate max-w-[120px]">{{ log.user?.name || 'Sistema' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-[11px] text-slate-600 font-semibold tracking-tight leading-relaxed line-clamp-1 group-hover:line-clamp-none transition-all duration-300">
                                {{ log.description }}
                            </p>
                        </td>
                    </tr>

                    <tr v-if="logs.data.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center text-[10px] font-bold text-slate-400 uppercase italic">Sin registros de auditoría</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 text-right">
            <Pagination :meta="logs" />
        </div>
    </div>
</template>
