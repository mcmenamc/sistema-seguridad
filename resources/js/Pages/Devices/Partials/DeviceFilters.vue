<script setup>
import { ref, watch } from 'vue';
import { Search, Activity, Users, Server } from 'lucide-vue-next';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    filters: Object,
    clients: Array,
    statusOptions: Array,
    deviceTypes: Array,
});

const emit = defineEmits(['filter']);

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');
const clientFilter = ref(props.filters.client_id || '');
const typeFilter = ref(props.filters.device_type_id || '');

let searchTimer = null;
watch(search, (newSearch) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        emit('filter', { 
            search: newSearch, 
            status: statusFilter.value, 
            client_id: clientFilter.value,
            device_type_id: typeFilter.value 
        });
    }, 300);
});

watch([statusFilter, clientFilter, typeFilter], ([st, cl, ty]) => {
    emit('filter', { 
        search: search.value, 
        status: st, 
        client_id: cl,
        device_type_id: ty 
    });
});
</script>

<template>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative bg-white border border-slate-200 rounded-lg shadow-sm w-80 h-10 flex items-center shrink-0">
            <Search class="absolute left-3 w-4 h-4 text-slate-400" />
            <input 
                v-model="search" 
                type="text" 
                placeholder="Filtrar por nombre o ubicación..." 
                class="w-full pl-10 pr-4 h-full bg-transparent border-none focus:ring-0 text-[11px] placeholder:text-slate-300 outline-none font-semibold text-slate-600" 
            />
        </div>

        <SearchableSelect 
            v-model="typeFilter" 
            :options="deviceTypes" 
            placeholder="Tipo" 
            :icon="Server" 
            :searchable="true"
        />

        <SearchableSelect 
            v-model="statusFilter" 
            :options="statusOptions" 
            placeholder="Estatus" 
            :icon="Activity" 
            :searchable="false"
        />

        <SearchableSelect 
            v-model="clientFilter" 
            :options="clients" 
            placeholder="Cliente" 
            :icon="Users" 
            :searchable="true"
        />
    </div>
</template>
