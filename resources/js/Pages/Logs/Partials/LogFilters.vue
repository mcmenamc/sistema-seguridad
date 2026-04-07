<script setup>
import { ref, watch } from 'vue';
import { Search, Calendar, X, User } from 'lucide-vue-next';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    filters: Object,
    users: Array,
});

const emit = defineEmits(['filter']);

const search = ref(props.filters.search || '');
const fromDate = ref(props.filters.from || '');
const toDate = ref(props.filters.to || '');
const userFilter = ref(props.filters.user_id || '');

let searchTimer = null;
watch(search, (newSearch) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        emit('filter', { 
            search: newSearch,
            from: fromDate.value,
            to: toDate.value,
            user_id: userFilter.value
        });
    }, 300);
});

watch([fromDate, toDate, userFilter], ([newFrom, newTo, newUser]) => {
    emit('filter', { 
        search: search.value,
        from: newFrom,
        to: newTo,
        user_id: newUser
    });
});

const clearFilters = () => {
    search.value = '';
    fromDate.value = '';
    toDate.value = '';
    userFilter.value = '';
};
</script>

<template>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative bg-white border border-slate-200 rounded-lg shadow-sm w-64 h-10 flex items-center shrink-0">
            <Search class="absolute left-3 w-4 h-4 text-slate-400" />
            <input 
                v-model="search" 
                type="text" 
                placeholder="Filtrar bitácora..." 
                class="w-full pl-10 pr-4 h-full bg-transparent border-none focus:ring-0 text-[11px] placeholder:text-slate-300 outline-none font-semibold text-slate-600" 
            />
        </div>

        <SearchableSelect 
            v-model="userFilter" 
            :options="users" 
            placeholder="Responsable" 
            :icon="User" 
            :searchable="true"
        />

        <div class="flex items-center space-x-2 bg-white border border-slate-200 rounded-lg shadow-sm px-3 h-10 shrink-0">
            <Calendar class="w-3.5 h-3.5 text-slate-400 mr-1" />
            <div class="flex items-center">
                <input 
                    v-model="fromDate" 
                    type="date" 
                    title="Fecha Inicial"
                    class="bg-transparent border-none focus:ring-0 text-[11px] font-bold text-slate-600 outline-none cursor-pointer p-0 w-[100px]" 
                />
                <span class="mx-2 text-slate-300 text-[10px] font-bold">AL</span>
                <input 
                    v-model="toDate" 
                    type="date" 
                    title="Fecha Final"
                    class="bg-transparent border-none focus:ring-0 text-[11px] font-bold text-slate-600 outline-none cursor-pointer p-0 w-[100px]" 
                />
            </div>
        </div>

        <button 
            v-if="search || fromDate || toDate || userFilter"
            @click="clearFilters"
            class="h-10 px-3 flex items-center space-x-2 text-slate-400 hover:text-red-500 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition-all active:scale-95"
        >
            <X class="w-4 h-4" />
            <span class="text-[10px] font-bold uppercase tracking-tighter">Limpiar</span>
        </button>
    </div>
</template>
