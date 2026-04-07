<script setup>
import { ref, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    show: Boolean,
    title: { type: String, default: 'Eliminar Registro' },
    message: { type: String, default: 'Esta acción es permanente.' },
    confirmName: { type: String, required: true },
    processing: Boolean,
});

const emit = defineEmits(['close', 'confirm']);

const inputConfirm = ref('');
const isConfirmed = ref(false);

watch(inputConfirm, (newValue) => {
    isConfirmed.value = newValue === props.confirmName;
});

const close = () => {
    inputConfirm.value = '';
    emit('close');
};

const submit = () => {
    if (isConfirmed.value) {
        emit('confirm');
    }
};

watch(() => props.show, (val) => {
    if (!val) inputConfirm.value = '';
});
</script>

<template>
    <Modal :show="show" @close="close" maxWidth="md">
        <div class="p-6 font-sans">
            <h2 class="text-lg text-slate-800 tracking-tight font-semibold mb-2">{{ title }}</h2>
            
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                {{ message }} <br>
                Escribe <span class="text-slate-800 font-medium tracking-tight">"{{ confirmName }}"</span> para confirmar:
            </p>

            <input 
                v-model="inputConfirm"
                type="text" 
                :placeholder="confirmName"
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:ring-1 focus:ring-red-500 focus:border-red-500 outline-none transition-all placeholder:text-slate-300"
            />

            <div class="mt-8 flex justify-end space-x-3">
                <SecondaryButton @click="close" class="px-4 py-2 text-xs">
                    Cancelar
                </SecondaryButton>

                <DangerButton 
                    @click="submit" 
                    :disabled="!isConfirmed || processing"
                    class="px-4 py-2 text-xs shadow-none"
                    :class="{ 'opacity-10 grayscale cursor-not-allowed': !isConfirmed }"
                >
                    Confirmar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
:deep(.fixed.inset-0) {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
