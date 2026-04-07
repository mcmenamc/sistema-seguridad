<script setup>
import Sidebar from '@/Components/Sidebar.vue';
import ImagePreviewModal from '@/Components/ImagePreviewModal.vue';
import NotificationDropdown from '@/Components/NotificationDropdown.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { useSidebar } from '@/Composables/useSidebar';
import { router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { Menu, PanelLeft, LogOut, Users, ChevronDown, LayoutDashboard } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const { state, toggleSidebar, closeSidebar } = useSidebar();
const isLogoutModalOpen = ref(false);
const isUserMenuOpen = ref(false);

let notificationInterval = null;

onMounted(() => {
    notificationInterval = setInterval(() => {
        router.reload({ 
            only: ['auth'],
            preserveScroll: true,
            preserveState: true
        });
    }, 5000);
});

onUnmounted(() => {
    if (notificationInterval) clearInterval(notificationInterval);
});

const handleLogout = () => {
    isUserMenuOpen.value = false;
    router.post(route('logout'), {}, {
        onSuccess: () => isLogoutModalOpen.value = false
    });
};
</script>

<template>
    <div class="flex h-screen bg-[#FDFDFD] overflow-hidden font-sans relative text-slate-900">
        
        <Teleport to="body">
            <ConfirmModal 
                :show="isLogoutModalOpen"
                title="Confirmar Cierre de Sesión"
                message="¿Seguro que quieres cerrar sesión? Tendrás que volver a iniciar sesión para acceder a tu cuenta."
                confirmText="Cerrar sesión"
                cancelText="Cancelar"
                @close="isLogoutModalOpen = false"
                @confirm="handleLogout"
            />
        </Teleport>

        <Sidebar class="flex-shrink-0" />

        <div 
            class="flex-1 flex flex-col min-w-0 relative bg-white transition-all duration-300 ease-in-out"
            :class="[state.isOpen ? 'lg:pl-64' : 'lg:pl-20']"
        >
            
            <header class="h-14 border-b border-slate-100 flex items-center justify-between px-6 bg-white shrink-0 sticky top-0 z-40">
                <div class="flex items-center space-x-4">
                    <button @click="toggleSidebar" class="p-1 px-2 text-slate-400 hover:text-slate-900 transition-colors border border-slate-100 rounded-md">
                        <PanelLeft class="w-3.5 h-3.5" />
                    </button>
                </div>

                <div class="flex items-center space-x-4">
                    <NotificationDropdown />

                    <div class="relative shrink-0">
                        <div v-if="isUserMenuOpen" class="fixed inset-0 z-40 cursor-default" @click="isUserMenuOpen = false"></div>

                        <div 
                            v-if="isUserMenuOpen" 
                            class="absolute top-full right-0 mt-3 w-56 bg-white/95 backdrop-blur-md border border-slate-100 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] p-1.5 z-50 animate-in zoom-in-95 slide-in-from-top-2 fade-in duration-200"
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
                                @click="isLogoutModalOpen = true; isUserMenuOpen = false"
                                class="flex items-center w-full px-3 py-2.5 rounded-xl text-[11px] font-semibold text-red-500 hover:bg-red-50 transition-colors text-left group/logout"
                            >
                                <LogOut class="w-3.5 h-3.5 mr-2.5 transition-transform group-hover/logout:translate-x-0.5" />
                                Cerrar Sesión
                            </button>
                        </div>

                        <button 
                            @click="isUserMenuOpen = !isUserMenuOpen"
                            class="flex items-center px-1 py-1 pr-1.5 bg-white border border-slate-100 rounded-full shadow-sm hover:shadow-md transition-all active:scale-95 group"
                            :class="{ 'border-slate-200 shadow-md': isUserMenuOpen }"
                        >
                            <div class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-[10px] font-bold uppercase italic tracking-tighter shrink-0">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            
                            <div class="mx-3 flex flex-col items-start leading-none">
                                <span class="text-[10px] font-semibold text-slate-950 uppercase tracking-tight">{{ $page.props.auth.user.name }}</span>
                                <span class="text-[9px] font-semibold text-slate-400 italic uppercase tracking-tighter mt-0.5">Administrador</span>
                            </div>

                            <ChevronDown class="w-3 h-3 text-slate-300 ml-1 transition-transform" :class="{ 'rotate-180': isUserMenuOpen }" />
                        </button>
                    </div>
                </div>
            </header>


            <main class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-10">
                <Transition name="fade" mode="out-in" appear>
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </main>
        </div>

        <div 
            v-if="state.isOpen" 
            class="fixed inset-0 bg-slate-900/10 backdrop-blur-[1px] z-40 lg:hidden animate-in fade-in duration-300"
            @click="closeSidebar"
        ></div>

        <ImagePreviewModal />
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Tipografía premium */
:global(body) {
    font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
    -webkit-font-smoothing: antialiased;
}
</style>
