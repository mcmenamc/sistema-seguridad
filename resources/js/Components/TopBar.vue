<script setup>
import { Link } from '@inertiajs/vue3';
import { Bell, LogOut, User, Menu } from 'lucide-vue-next';
import { useSidebar } from '@/Composables/useSidebar';

const { toggleSidebar } = useSidebar();

defineProps({
    user: Object
});
</script>

<template>
    <header class="h-12 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-40">
        <div class="h-full flex items-center space-x-3 text-xs font-medium text-slate-500">
            <button 
                @click="toggleSidebar"
                type="button"
                class="lg:hidden p-1.5 -ml-1 text-slate-900 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition-all active:scale-95 shadow-sm inline-flex items-center justify-center"
            >
                <Menu class="w-4 h-4" />
            </button>

            <div class="flex items-center space-x-2 h-full">
                <span class="hover:text-slate-900 transition-colors cursor-pointer hidden md:inline">Panel Operativo</span>
                <span class="text-slate-300 hidden md:inline text-[10px]">/</span>
                <span class="text-slate-900 font-bold tracking-tight">{{ $page.component }}</span>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <button class="p-1.5 text-slate-400 hover:text-slate-900 transition-colors">
                <Bell class="w-4 h-4 ml-2" />
            </button>

            <div class="h-4 w-px bg-slate-200"></div>

            <div class="flex items-center space-x-3">
                <span class="text-[11px] font-semibold text-slate-600 truncate max-w-[150px]">{{ user?.email }}</span>
                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button"
                    class="p-1.5 text-slate-400 hover:text-red-600 transition-colors"
                    title="Cerrar Sesión"
                >
                    <LogOut class="w-4 h-4" />
                </Link>
            </div>
        </div>
    </header>
</template>
