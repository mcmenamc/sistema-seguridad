x<script setup>
import { router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Modal :show="show" @close="emit('close')" maxWidth="md">
        <div class="p-8">
            <h2 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Cerrar sesión</h2>
            <p class="text-sm text-slate-500 mb-8 font-medium leading-relaxed">
                ¿Seguro que quieres cerrar sesión? Tendrás que volver a iniciar sesión para acceder a tu cuenta.
            </p>

            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                <SecondaryButton @click="emit('close')" class="rounded-xl px-6">
                    Cancelar
                </SecondaryButton>

                <DangerButton @click="logout" class="shadow-red-200">
                    Cerrar sesión
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
/* Estilo seguro para centrado sin bloquear el viewport */
:deep(.fixed.inset-0) {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
