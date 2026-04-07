<script setup>
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { RefreshCcw } from 'lucide-vue-next';

import LogFilters from './Partials/LogFilters.vue';
import LogTable from './Partials/LogTable.vue';

const props = defineProps({
    logs: Object,
    users: Array,
    filters: Object,
});

const handleFilter = (filters) => {
    router.get(route('logs.index'), filters, { 
        preserveState: true, 
        replace: true, 
        preserveScroll: true 
    });
};

const refreshLogs = () => {
    router.reload({ preserveScroll: true });
};
</script>

<template>
    <Head title="Bitácora de Auditoría | Infraestructura" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Bitácora de Auditoría</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Historial integral de acciones en el sistema.</p>
                </div>
                <button 
                    @click="refreshLogs"
                    class="p-2 text-slate-400 hover:text-slate-900 bg-white border border-slate-200 rounded-lg shadow-sm transition-all active:scale-95"
                >
                    <RefreshCcw class="w-4 h-4" />
                </button>
            </div>

            <LogFilters 
                :filters="filters" 
                :users="users"
                @filter="handleFilter"
            />

            <LogTable 
                :logs="logs" 
            />

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
