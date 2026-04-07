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
            search: newSearch, 
        });
    }, 300);
});
</script>

<template>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative bg-white border border-slate-200 rounded-xl shadow-sm w-full sm:w-80 h-10 flex items-center shrink-0 focus-within:border-slate-900 transition-all overflow-hidden group">
            <Search class="absolute left-3 w-4 h-4 text-slate-400 group-focus-within:text-slate-900 transition-colors" />
            <input 
                v-model="search" 
                type="text" 
                placeholder="Filtrar por nombre..." 
                class="w-full pl-10 pr-4 h-full bg-transparent border-none focus:ring-0 text-[11px] font-medium text-slate-600 tracking-tight outline-none" 
            />
        </div>
    </div>
</template>
