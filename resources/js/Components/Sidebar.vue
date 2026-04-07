<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { useSidebar } from '@/Composables/useSidebar';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { 
    LayoutDashboard, Monitor, Zap, AlertTriangle, 
    ScrollText, Users, Server, Tag, LogOut, ChevronsUpDown,
    Shield
} from 'lucide-vue-next';

const { state, closeSidebar } = useSidebar();
const page = usePage();
const isUserMenuOpen = ref(false);
const showLogoutConfirm = ref(false);

const handleLogout = () => {
    isUserMenuOpen.value = false;
    router.post(route('logout'), {}, {
        onSuccess: () => showLogoutConfirm.value = false
    });
};

const userPermissions = computed(() => page.props.auth.user?.permissions || []);
const can = (permission) => userPermissions.value.includes(permission);

const iconMap = {
    LayoutDashboard, Monitor, Zap, AlertTriangle, 
    ScrollText, Users, Server, Tag, Shield
};

const navGroups = computed(() => page.props.sidebar || []);
const getIcon = (name) => iconMap[name] || Shield;
</script>

<template>
    <aside 
        class="flex flex-col h-screen bg-white border-r border-slate-100 fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out font-sans"
        :class="[
            state.isOpen ? 'w-64 translate-x-0 shadow-2xl lg:shadow-none' : 'w-20 lg:translate-x-0 -translate-x-full shadow-none'
        ]"
    >
        <div class="h-16 flex items-center px-6 shrink-0 border-b border-slate-50">
            <div class="flex items-center justify-center min-w-8">
                <div class="bg-black p-1.5 rounded-lg shadow-lg shadow-black/10 group-hover:scale-110 transition-transform">
                    <Shield class="w-5 h-5 text-white" />
                </div>
            </div>
            <div v-if="state.isOpen" class="ml-4 flex flex-col leading-none overflow-hidden animate-in fade-in slide-in-from-left-2 duration-300">
                <span class="text-sm font-bold tracking-tighter uppercase italic leading-none">Softlinkia</span>
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mt-0.5">Sistema de seguridad</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar py-8 space-y-6 px-3">
            <div v-for="group in navGroups" :key="group.title" class="space-y-1.5">
                <h3 v-if="state.isOpen" class="text-[9px] font-semibold text-slate-500 uppercase tracking-widest px-2.5 mb-2 opacity-70">
                    {{ group.title }}
                </h3>

                <div class="space-y-0.5" :class="{ 'flex flex-col items-center': !state.isOpen }">
                    <Link 
                        v-for="item in group.items" 
                        :key="item.name"
                        :href="route().has(item.route) ? route(item.route) : '#'"
                        class="flex items-center h-9 rounded-lg transition-all group relative overflow-hidden"
                        :class="[
                            route().current(item.route) 
                                ? 'bg-slate-50 text-black border-slate-200' 
                                : 'text-slate-600 hover:bg-slate-50/80 hover:text-black border-transparent hover:border-slate-100',
                            state.isOpen ? 'px-2.5 w-full border' : 'w-9 justify-center'
                        ]"
                    >
                        <component 
                            :is="getIcon(item.icon)" 
                            class="w-4 h-4 shrink-0 transition-transform group-hover:scale-105" 
                            :class="route().current(item.route) ? 'text-black stroke-[2.5]' : 'text-slate-500 group-hover:text-black'"
                        />
                        <span 
                            v-if="state.isOpen" 
                            class="ml-2.5 text-[11px] font-semibold tracking-tight animate-in fade-in slide-in-from-left-1 duration-200"
                            :class="route().current(item.route) ? 'font-bold text-black' : 'font-semibold'"
                        >
                            {{ item.name }}
                        </span>

                        <div v-if="!state.isOpen" class="absolute left-full ml-4 px-2 py-1 bg-slate-900 text-white text-[9px] font-semibold rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap shadow-xl">
                            {{ item.name }}
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-slate-50 bg-white shrink-0 relative">
            
            <div 
                v-if="isUserMenuOpen" 
                class="fixed inset-0 z-[55] cursor-default"
                @click="isUserMenuOpen = false"
            ></div>

            <div 
                v-if="isUserMenuOpen" 
                class="absolute bottom-full left-4 mb-3 w-56 bg-white/95 backdrop-blur-md border border-slate-100 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] p-1.5 z-[60] animate-in zoom-in-95 slide-in-from-bottom-2 fade-in duration-200"
            >
                <div class="px-3 py-2 border-b border-slate-50 mb-1">
                    <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest leading-none">Gestión de Cuenta</p>
                </div>
                
                <Link 
                    :href="route('profile.edit')"
                    class="flex items-center w-full px-3 py-2.5 rounded-xl text-[11px] font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors group/item"
                    @click="isUserMenuOpen = false"
                >
                    <Users class="w-3.5 h-3.5 mr-2.5 text-slate-400 group-hover/item:text-slate-900 transition-colors" />
                    Ver Perfil
                </Link>
                <button 
                    @click="showLogoutConfirm = true; isUserMenuOpen = false"
                    class="flex items-center w-full px-3 py-2.5 rounded-xl text-[11px] font-semibold text-red-500 hover:bg-red-50 transition-colors text-left group/logout"
                >
                    <LogOut class="w-3.5 h-3.5 mr-2.5 transition-transform group-hover/logout:-translate-x-0.5" />
                    Cerrar Sesión
                </button>
            </div>

            <button 
                @click="isUserMenuOpen = !isUserMenuOpen"
                class="flex items-center w-full p-1.5 bg-slate-50/50 rounded-xl border border-transparent hover:border-slate-100 transition-all group"
                :class="[
                    isUserMenuOpen ? 'border-slate-200 bg-white shadow-sm' : '',
                    !state.isOpen ? 'justify-center' : ''
                ]"
            >
                <div class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center shrink-0 shadow-md">
                    <span class="text-[10px] font-semibold uppercase italic">{{ $page.props.auth.user.name.charAt(0) }}</span>
                </div>

                <div v-if="state.isOpen" class="ml-2.5 flex-1 min-w-0 text-left animate-in fade-in slide-in-from-left-2 duration-300">
                    <h2 class="text-[10px] font-semibold text-slate-900 truncate uppercase leading-none tracking-tight">
                        {{ $page.props.auth.user.name }}
                    </h2>
                    <p class="text-[8.5px] font-semibold text-slate-400 truncate tracking-tighter mt-1 uppercase italic opacity-70">
                        {{ $page.props.auth.user.roles[0] || 'Usuario' }}
                    </p>
                </div>
                <ChevronsUpDown v-if="state.isOpen" class="w-3.5 h-3.5 text-slate-300 shrink-0 ml-1.5" />
            </button>
        </div>

        <Teleport to="body">
            <ConfirmModal 
                :show="showLogoutConfirm" 
                title="Confirmar Cierre de Sesión"
                message="¿Seguro que quieres cerrar sesión? Tendrás que volver a iniciar sesión para acceder a tu cuenta."
                confirmText="Cerrar sesión"
                cancelText="Cancelar"
                @close="showLogoutConfirm = false" 
                @confirm="handleLogout"
            />
        </Teleport>
    </aside>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 3px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
</style>
