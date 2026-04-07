<script setup>
import { ref, watch } from 'vue';
import { Search } from 'lucide-vue-next';

const props = defineProps({
    filters: Object,
});

const emit = defineEmits(['filter']);

const search = ref(props.filters.search || '');

let searchTimer = null;
watch(search, (newSearch) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        emit('filter', { 
            search: newSearch
        });
    }, 300);
});
</script>

<template>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative bg-white border border-slate-200 rounded-lg shadow-sm w-80 h-10 flex items-center shrink-0">
            <Search class="absolute left-3 w-4 h-4 text-slate-400" />
            <input 
                v-model="search" 
                type="text" 
                placeholder="Filtrar catálogos por nombre..." 
                class="w-full pl-10 pr-4 h-full bg-transparent border-none focus:ring-0 text-[11px] placeholder:text-slate-300 outline-none font-semibold text-slate-600 uppercase tracking-tighter" 
            />
        </div>
    </div>
</template>
