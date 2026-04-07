<script setup>
import { ref, watch } from 'vue';
import { Search, Shield } from 'lucide-vue-next';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    filters: Object,
});

const emit = defineEmits(['filter']);

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || '');

const roleOptions = [
    { id: 'Administrador', name: 'Administrador' },
    { id: 'Operador', name: 'Operador' },
    { id: 'Cliente', name: 'Cliente' },
];

let searchTimer = null;
watch(search, (newSearch) => {
    if (searchTimer) clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        emit('filter', { 
            search: newSearch,
            role: roleFilter.value
        });
    }, 300);
});

watch(roleFilter, (newRole) => {
    emit('filter', { 
        search: search.value,
        role: newRole
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
                placeholder="Filtrar por nombre o email..." 
                class="w-full pl-10 pr-4 h-full bg-transparent border-none focus:ring-0 text-[11px] placeholder:text-slate-300 outline-none font-semibold text-slate-600" 
            />
        </div>

        <SearchableSelect 
            v-model="roleFilter" 
            :options="roleOptions" 
            placeholder="Todos los Roles" 
            :icon="Shield" 
            :searchable="false"
        />
    </div>
</template>
