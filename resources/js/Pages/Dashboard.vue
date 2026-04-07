<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, Zap, Calendar, Users, Filter, AlertTriangle } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    stats: Object,
    recent_events: Array,
    recent_incidents: Array,
    filters: Object,
    clients: Array,
});

const liveEvents = ref([...props.recent_events]);
const liveIncidents = ref([...props.recent_incidents]);
const liveStats = ref({ ...props.stats });

const dateFilter = ref(props.filters.date_filter || '');
const typeFilter = ref(props.filters.type || '');
const clientId = ref(props.filters.client_id || '');

let pollingInterval = null;

onMounted(() => {
    pollingInterval = setInterval(() => {
        router.reload({ 
            only: ['stats', 'recent_events', 'recent_incidents', 'charts', 'auth'],
            preserveScroll: true,
            preserveState: true
        });
    }, 5000);
});

onUnmounted(() => { 
    if (pollingInterval) clearInterval(pollingInterval); 
});

watch(() => props.recent_events, (newVal) => liveEvents.value = [...newVal]);
watch(() => props.recent_incidents, (newVal) => liveIncidents.value = [...newVal]);
watch(() => props.stats, (newVal) => liveStats.value = { ...newVal });

watch([dateFilter, typeFilter, clientId], ([d, t, c]) => {
    router.get(route('dashboard'), { date_filter: d, type: t, client_id: c }, { preserveState: true, replace: true, preserveScroll: true });
});

const getStatusBadge = (status) => {
    const badges = {
        'pendiente': 'bg-orange-50 text-orange-600 border border-orange-100',
        'en proceso': 'bg-blue-50 text-blue-600 border border-blue-100',
        'resuelto': 'bg-green-50 text-green-600 border border-green-100',
    };
    return badges[status] || 'bg-slate-50 text-slate-500 border border-slate-100';
};
</script>

<template>
 <Head title="Dispositivos |" />
    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-10 max-w-[1600px] mx-auto pb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 pb-10">
                <div class="space-y-1">
                    <div class="flex items-center space-x-3">
                        <h1 class="text-3xl font-bold text-slate-900">Centro Operativo</h1>
                        <div class="flex items-center space-x-2 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest tracking-tighter">Live Monitor</span>
                        </div>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-[0.25em] font-medium">Radar de seguridad en tiempo real activo</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-orange-50 rounded-xl group-hover:scale-110 transition-transform">
                            <AlertTriangle class="w-5 h-5 text-orange-500" />
                        </div>
                        <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest leading-none">Crítico</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900 tracking-tighter">{{ liveStats.incidencias_pendientes }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-tight">Incidencias Pendientes</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-red-50 rounded-xl group-hover:scale-110 transition-transform">
                            <Zap class="w-5 h-5 text-red-500" />
                        </div>
                        <span class="text-[10px] font-bold text-red-400 uppercase tracking-widest leading-none">Alerta</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900 tracking-tighter">{{ liveStats.dispositivos_alerta }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-tight">Dispostivos en Alerta</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-indigo-50 rounded-xl group-hover:scale-110 transition-transform">
                            <Search class="w-5 h-5 text-indigo-500" />
                        </div>
                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest leading-none">Eventos</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900 tracking-tighter">{{ liveStats.total_eventos }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-tight">Eventos Recibida</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-green-50 rounded-xl group-hover:scale-110 transition-transform">
                            <Users class="w-5 h-5 text-green-500" />
                        </div>
                        <span class="text-[10px] font-bold text-green-400 uppercase tracking-widest leading-none">Flota</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-slate-900 tracking-tighter">{{ stats.dispositivos_activos }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-tight">Dispositivos</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex flex-col gap-3 w-full md:w-64 shrink-0">
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 space-y-4 shadow-sm">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Filtros</h3>
                        
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-500 uppercase ml-1">Periodo de Análisis</label>
                            <div class="relative bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 flex items-center">
                                <Calendar class="w-3.5 h-3.5 text-slate-400 mr-2" />
                                <select v-model="dateFilter" class="w-full bg-transparent border-none text-[10px] font-bold text-slate-700 focus:ring-0 outline-none cursor-pointer">
                                    <option value="">Todo el tiempo</option>
                                    <option value="today">Hoy</option>
                                    <option value="week">Esta semana</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-500 uppercase ml-1">División / Cliente</label>
                            <div class="relative bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 flex items-center">
                                <Users class="w-3.5 h-3.5 text-slate-400 mr-2" />
                                <select v-model="clientId" class="w-full bg-transparent border-none text-[10px] font-bold text-slate-700 focus:ring-0 outline-none cursor-pointer">
                                    <option value="">Todos los clientes</option>
                                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Distribución de Flota</h3>
                    </div>
                    <div class="flex-1 flex items-end justify-around gap-2 px-4 pb-2">
                        <div v-for="(count, status) in $page.props.charts.device_status" :key="status" class="flex flex-col items-center group w-full max-w-[60px]">
                            <div 
                                class="w-full rounded-lg transition-all duration-500 ease-out shadow-lg"
                                :class="[
                                    status === 'alerta' ? 'bg-red-500/80' : 
                                    status === 'activo' ? 'bg-green-500/80' : 'bg-slate-300'
                                ]"
                                :style="{ height: `${(count / Object.values($page.props.charts.device_status).reduce((a, b) => a + b, 0)) * 150 + 20}px` }"
                            ></div>
                            <span class="text-[9px] font-bold text-slate-900 mt-2 uppercase tracking-tighter">{{ status }}</span>
                            <span class="text-[10px] font-bold text-slate-400 leading-none">{{ count }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                    <div class="p-3 bg-slate-50/50 border-b border-slate-200">
                         <h3 class="text-[10px] font-semibold text-slate-400">Incidencias críticas</h3>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/30 border-b border-slate-200">
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400">Tipo</th>
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400 text-center">Estado</th>
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400">Dispositivo</th>
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="inc in liveIncidents" :key="inc.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3 font-semibold text-slate-800 text-xs tracking-tight">{{ inc.type }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span :class="getStatusBadge(inc.status)" class="px-2 py-0.5 rounded text-[9px] font-semibold">
                                        {{ inc.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-xs text-slate-500 font-medium tracking-tighter">{{ inc.device?.name || 'Sistema' }}</td>
                                <td class="px-6 py-3 text-right">
                                    <Link :href="route('incidents.index')" class="text-[10px] font-semibold text-blue-600 hover:underline">Gestionar</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                    <div class="p-3 bg-slate-50/50 border-b border-slate-200">
                         <h3 class="text-[10px] font-semibold text-slate-400 text-center md:text-left">Eventos</h3>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/30 border-b border-slate-200">
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400 w-24">Hora</th>
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400">Dispositivo</th>
                                <th class="px-6 py-2.5 text-[10px] font-semibold text-slate-400">Evento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="event in liveEvents" :key="event.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3 text-[10px] font-semibold  tabular-nums">
                                    {{ new Date(event.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second: '2-digit'}) }}
                                </td>
                                <td class="px-6 py-3 text-xs font-semibold text-slate-800 tracking-tight">{{ event.device.name }}</td>
                                <td class="px-6 py-3 text-[10px] text-slate-500 font-semibold">{{ event.type }}</td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Scrollbar ultra-fina para mantener limpieza Shadcn */
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
