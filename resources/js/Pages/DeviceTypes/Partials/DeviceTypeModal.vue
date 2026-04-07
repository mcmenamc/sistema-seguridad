<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import { X } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    show: Boolean,
    type: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    id: null,
    name: '',
});

watch(() => props.type, (newVal) => {
    if (newVal) {
        form.id = newVal.id;
        form.name = newVal.name;
    } else {
        form.reset();
    }
}, { immediate: true });

const submit = () => {
    if (form.id) {
        form.put(route('device-types.update', form.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('device-types.store'), {
            onSuccess: () => emit('close'),
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] p-4 font-sans text-slate-900">
        <div class="bg-white w-full max-w-[520px] rounded-2xl border border-slate-200 shadow-2xl overflow-visible animate-in fade-in zoom-in duration-200 flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-tight">
                        {{ form.id ? 'Modificar Registro' : 'Nuevo Tipo de Hardware' }}
                    </h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-5">
                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Nombre</label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        placeholder="Ej: Cámara IP, Botón de Pánico..."
                        class="w-full bg-white border border-slate-200 rounded-xl text-[11px] font-semibold py-2.5 px-3 focus:border-slate-900 focus:ring-0 outline-none transition-all tracking-tight"
                        :class="{'border-red-500 bg-red-50/10': form.errors.name}"
                    />
                    <p v-if="form.errors.name" class="text-[9px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.name }}</p>
                </div>

                <div class="pt-2 flex items-center space-x-3 text-white flex-shrink-0">
                    <button 
                        type="button" 
                        @click="emit('close')" 
                        class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter"
                    >
                        Descartar
                    </button>
                    <PrimaryButton 
                        type="submit" 
                        :processing="form.processing" 
                        class="flex-[2] h-11 uppercase tracking-tighter text-[11px] font-bold"
                    >
                        {{ form.id ? 'Actualizar categoría' : 'Confirmar registro' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
