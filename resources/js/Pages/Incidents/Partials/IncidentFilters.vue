<script setup>
import { ref, watch } from 'vue';
import { Search, Activity, Server, Calendar, X } from 'lucide-vue-next';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    filters: Object,
    devices: Array,
    statusOptions: Array,
});

const emit = defineEmits(['filter']);

const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const deviceFilter = ref(props.filters?.device_id || '');
const fromDate = ref(props.filters?.from || '');
const toDate = ref(props.filters?.to || '');

let searchTimer = null;
watch(search, (newSearch) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        emit('filter', { 
            search: newSearch, 
            status: statusFilter.value, 
            device_id: deviceFilter.value,
            from: fromDate.value,
            to: toDate.value
        });
    }, 300);
});

watch([statusFilter, deviceFilter, fromDate, toDate], ([st, de, fr, to]) => {
    emit('filter', { 
        search: search.value, 
        status: st, 
        device_id: de,
        from: fr,
        to: to
    });
});

const clearFilters = () => {
    search.value = '';
    statusFilter.value = '';
    deviceFilter.value = '';
    fromDate.value = '';
    toDate.value = '';
};
</script>

<template>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative bg-white border border-slate-200 rounded-lg shadow-sm w-72 h-10 flex items-center shrink-0">
            <Search class="absolute left-3 w-4 h-4 text-slate-400" />
            <input 
                v-model="search" 
                type="text" 
                placeholder="Filtrar incidencia o descripción..." 
                class="w-full pl-10 pr-4 h-full bg-transparent border-none focus:ring-0 text-[11px] placeholder:text-slate-300 outline-none font-semibold text-slate-600" 
            />
        </div>

        <SearchableSelect 
            v-model="statusFilter" 
            :options="statusOptions" 
            placeholder="Estado" 
            :icon="Activity" 
            :searchable="false"
        />

        <SearchableSelect 
            v-model="deviceFilter" 
            :options="devices" 
            placeholder="Dispositivos" 
            :icon="Server" 
            :searchable="true"
        />

        <div class="flex items-center space-x-2 bg-white border border-slate-200 rounded-lg shadow-sm px-3 h-10 shrink-0">
            <Calendar class="w-3.5 h-3.5 text-slate-400 mr-1" />
            <div class="flex items-center">
                <input v-model="fromDate" type="date" class="bg-transparent border-none focus:ring-0 text-[11px] font-bold text-slate-600 outline-none cursor-pointer p-0 w-[100px]" />
                <span class="mx-2 text-slate-300 text-[10px] font-bold">AL</span>
                <input v-model="toDate" type="date" class="bg-transparent border-none focus:ring-0 text-[11px] font-bold text-slate-600 outline-none cursor-pointer p-0 w-[100px]" />
            </div>
        </div>

        <button 
            v-if="search || statusFilter || deviceFilter || fromDate || toDate"
            @click="clearFilters"
            class="h-10 px-3 flex items-center space-x-2 text-slate-400 hover:text-red-500 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all active:scale-95"
        >
            <X class="w-4 h-4" />
            <span class="text-[10px] font-bold uppercase tracking-tighter">Limpiar</span>
        </button>
    </div>
</template>
