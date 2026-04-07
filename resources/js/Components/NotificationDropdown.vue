<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Bell, CheckCheck, Trash2, CheckCircle2, ChevronRight, Info, AlertTriangle, ShieldCheck } from 'lucide-vue-next';

const page = usePage();
const isOpen = ref(false);
const dropdownRef = ref(null);

const notifications = computed(() => page.props.auth.user.notifications || []);
const count = computed(() => page.props.auth.user.unread_notifications_count || 0);

const toggleDropdown = () => isOpen.value = !isOpen.value;

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        isOpen.value = false;
    }
};

onMounted(() => document.addEventListener('click', closeDropdown));
onUnmounted(() => document.removeEventListener('click', closeDropdown));

const markAsRead = (id) => {
    router.patch(route('notifications.read', id), {}, { 
        preserveScroll: true,
        onSuccess: () => {
        }
    });
};

const markAllAsRead = () => {
    router.patch(route('notifications.read-all'), {}, { preserveScroll: true });
};

const clearAll = () => {
    router.delete(route('notifications.clear'), { preserveScroll: true });
};

const getIcon = (type) => {
    const icons = {
        'success': { component: CheckCircle2, class: 'bg-emerald-50 text-emerald-500 border-emerald-100' },
        'error': { component: AlertTriangle, class: 'bg-red-50 text-red-500 border-red-100' },
        'warning': { component: AlertTriangle, class: 'bg-orange-50 text-orange-500 border-orange-100' },
        'info': { component: Info, class: 'bg-blue-50 text-blue-600 border-blue-100' }
    };
    return icons[type] || icons['info'];
};

const formatTime = (date) => {
    const diff = Math.floor((new Date() - new Date(date)) / 1000);
    if (diff < 60) return 'Hace un momento';
    if (diff < 3600) return `Hace ${Math.floor(diff/60)}m`;
    if (diff < 86400) return `Hace ${Math.floor(diff/3600)}h`;
    return `Hace ${Math.floor(diff/86400)}d`;
};
</script>

<template>
    <div class="relative font-sans" ref="dropdownRef">
        <button 
            @click="toggleDropdown"
            class="relative p-1.5 text-slate-400 hover:text-slate-900 transition-all active:scale-95 group"
        >
            <Bell class="w-4 h-4 group-hover:rotate-12 transition-transform" />
            <span v-if="count > 0" class="absolute top-1 right-1 w-3 h-3 bg-red-600 text-white text-[8px] font-bold rounded-full border border-white flex items-center justify-center animate-pulse">
                {{ count > 9 ? '+' : count }}
            </span>
        </button>

        <div 
            v-if="isOpen"
            class="fixed md:absolute right-0 md:right-0 top-20 md:top-full mt-2 w-full md:w-[400px] max-h-[500px] bg-white border border-slate-200 rounded-2xl md:rounded-3xl shadow-2xl z-[100] overflow-hidden animate-in fade-in zoom-in slide-in-from-top-2 duration-200"
        >
            <div class="p-5 border-b border-slate-100 bg-white sticky top-0 z-10">
                <h3 class="text-base font-bold text-slate-900 tracking-tight mb-4 flex items-center justify-between">
                    <span>Notificaciones</span>
                    <span v-if="count > 0" class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-100">
                        {{ count }} pendientes
                    </span>
                </h3>
                <div class="flex items-center justify-between">
                    <button 
                        @click="clearAll"
                        class="text-[10px] font-bold text-slate-400 hover:text-red-500 uppercase tracking-widest transition-colors flex items-center"
                    >
                        <Trash2 class="w-3 h-3 mr-1.5" />
                        LIMPIAR TODAS
                    </button>
                    <button 
                        @click="markAllAsRead"
                        class="text-[10px] font-bold text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-colors flex items-center"
                    >
                        <CheckCheck class="w-3 h-3 mr-1.5" />
                        TODAS LEÍDAS
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto max-h-[350px] custom-scrollbar">
                <div v-if="notifications.length > 0" class="divide-y divide-slate-100 border-b border-slate-100">
                    <div 
                        v-for="notification in notifications" 
                        :key="notification.id"
                        @click="markAsRead(notification.id)"
                        class="p-5 flex items-start space-x-4 hover:bg-slate-50/50 transition-all group relative cursor-pointer"
                        :class="[notification.read_at ? 'opacity-60' : 'opacity-100']"
                    >
                        <div :class="[getIcon(notification.data.type).class, 'flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center border shadow-sm transition-transform group-hover:scale-105']">
                            <component :is="getIcon(notification.data.type).component" class="w-5 h-5" />
                        </div>

                        <div class="flex-1 min-w-0 pr-4">
                            <h4 class="text-[11px] font-bold text-slate-900 uppercase tracking-tight mb-1">
                                {{ notification.data.title || 'ALERTA DE SISTEMA' }}
                            </h4>
                            <p class="text-[12px] text-slate-500 font-medium leading-relaxed mb-2">
                                {{ notification.data.description || notification.data.message }}
                            </p>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ formatTime(notification.created_at) }}
                            </span>
                        </div>

                        <div v-if="!notification.read_at" class="absolute right-6 bottom-6 w-2.5 h-2.5 bg-orange-500 rounded-full border-2 border-white shadow-sm shadow-orange-200"></div>
                    </div>
                </div>

                <div v-else class="p-12 flex flex-col items-center justify-center text-center space-y-3 opacity-30">
                    <CheckCircle2 class="w-10 h-10 text-slate-400" />
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest italic">Consola de alertas despejada</span>
                </div>
            </div>

            <div v-if="notifications.length > 0" class="p-3 bg-slate-50/30 flex justify-center sticky bottom-0 border-t border-slate-50">
                <button class="text-[11px] font-bold text-slate-500 hover:text-slate-900 flex items-center space-x-1 uppercase tracking-tighter transition-all">
                    <span>Acceder a Bitácora Central</span>
                    <ChevronRight class="w-3.5 h-3.5" />
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
