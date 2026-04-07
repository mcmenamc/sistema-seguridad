<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Plus, Users } from 'lucide-vue-next';
import UserFilters from './Partials/UserFilters.vue';
import UserTable from './Partials/UserTable.vue';
import UserModal from './Partials/UserModal.vue';

const props = defineProps({
    users: Object,
    filters: Object,
});

const isModalOpen = ref(false);
const editingUser = ref(null);

const handleFilter = (filters) => {
    router.get(route('users.index'), filters, { 
        preserveState: true, 
        replace: true, 
        preserveScroll: true 
    });
};

const openCreateModal = () => {
    editingUser.value = null;
    isModalOpen.value = true;
};

const openEditModal = (user) => {
    editingUser.value = user;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingUser.value = null;
};
</script>

<template>
    <Head title="Identidades | Dashboard" />

    <AuthenticatedLayout :auth="$page.props.auth">
        <div class="space-y-4 max-w-[1600px] mx-auto pb-10">
            
            <div class="flex items-start justify-between px-1">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Usuarios</h2>
                    <p class="text-xs text-slate-400 font-medium tracking-tight">Gestión integral de privilegios y acceso administrativo.</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    <Plus class="w-4 h-4 mr-2" />
                    Registrar usuario
                </PrimaryButton>
            </div>

            <UserFilters 
                :filters="filters" 
                @filter="handleFilter"
            />

            <UserTable 
                :users="users" 
                @edit="openEditModal" 
            />

        </div>

        <UserModal 
            :show="isModalOpen" 
            :user="editingUser" 
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>

<style scoped>
::-webkit-scrollbar { width: 3px; height: 3px; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
