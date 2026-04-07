<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { MoreHorizontal, Edit, Trash2, Mail, ShieldCheck, UserCheck, Shield } from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DeleteConfirmModal from '@/Components/DeleteConfirmModal.vue';

const props = defineProps({
    users: Object,
});

const emit = defineEmits(['edit']);
const page = usePage();

const isDeleteModalOpen = ref(false);
const userToDelete = ref(null);

const getRoleStyles = (roles) => {
    const roleName = roles[0]?.name || 'Cliente';
    const styles = {
        'Administrador': { icon: ShieldCheck, color: 'text-blue-600 bg-blue-50 border-blue-100' },
        'Operador': { icon: UserCheck, color: 'text-green-600 bg-green-50 border-green-100' },
        'Cliente': { icon: Shield, color: 'text-slate-500 bg-slate-50 border-slate-100' },
    };
    return styles[roleName] || styles['Cliente'];
};

const openDeleteModal = (user) => {
    userToDelete.value = user;
    isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
    router.delete(route('users.destroy', userToDelete.value.id), {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        }
    });
};
</script>

<template>
    <div class="space-y-4 font-sans">
        <DeleteConfirmModal 
            :show="isDeleteModalOpen"
            :confirmName="userToDelete?.name || ''"
            title="Purgar Usuario del Sistema"
            message="Esta acción revocará todos los accesos de seguridad a este usuario de forma inmediata."
            @close="isDeleteModalOpen = false"
            @confirm="confirmDelete"
        />

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[950px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter lg:w-[40%]">Usuario</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Privilegios</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-center">Registro</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-tighter text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50/20 transition-all group">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 bg-slate-900 text-white rounded-xl flex items-center justify-center text-[12px] font-bold border border-slate-900/10 shadow-sm uppercase italic">
                                        {{ user.name.charAt(0) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-bold text-slate-800 uppercase tracking-tight leading-none mb-1.5">{{ user.name }}</span>
                                        <div class="flex items-center space-x-1.5 text-[9px] text-slate-400 font-semibold tracking-tighter uppercase">
                                            <Mail class="w-3 h-3 opacity-50" />
                                            <span>{{ user.email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div :class="[getRoleStyles(user.roles).color, 'px-2 py-0.5 rounded-full border border-slate-100 inline-flex items-center space-x-1.5 leading-none']">
                                    <component :is="getRoleStyles(user.roles).icon" class="w-3 h-3" />
                                    <span class="text-[9px] font-bold uppercase tracking-tighter whitespace-nowrap">{{ user.roles[0]?.name || 'Sin Rol' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tighter">{{ new Date(user.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' }) }}</span>
                                    <span class="text-[9px] text-slate-400 font-semibold tracking-tighter uppercase">{{ new Date(user.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end">
                                    <template v-if="user.email === page.props.auth.user.email">
                                        <span class="text-[9px] text-slate-300 font-bold uppercase italic mr-3 tracking-widest">Tú (Actual)</span>
                                    </template>
                                    <Dropdown v-else align="right" width="48">
                                        <template #trigger>
                                            <button class="p-1.5 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-900 transition-all active:scale-90">
                                                <MoreHorizontal class="w-4 h-4" />
                                            </button>
                                        </template>
                                        <template #content>
                                            <div class="p-1 text-slate-900">
                                                <button 
                                                    @click="emit('edit', user)"
                                                    class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-slate-700 hover:bg-slate-50 rounded-lg transition-colors group"
                                                >
                                                    <Edit class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-900" />
                                                    <span>Recalibrar Ficha</span>
                                                </button>
                                                <div class="h-px bg-slate-100 my-1"></div>
                                                <button 
                                                    @click="openDeleteModal(user)"
                                                    class="w-full flex items-center space-x-2 px-3 py-2 text-[11px] font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors group"
                                                >
                                                    <Trash2 class="w-3.5 h-3.5 text-red-400 group-hover:text-red-600" />
                                                    <span>Purgar Usuario</span>
                                                </button>
                                            </div>
                                        </template>
                                    </Dropdown>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100">
                <Pagination :meta="users" />
            </div>
        </div>
    </div>
</template>
