<script setup>
import { useForm } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    type: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    id: null,
    name: '',
});

watch(() => props.type, (newType) => {
    if (newType) {
        form.id = newType.id;
        form.name = newType.name;
    } else {
        form.reset();
        form.id = null;
    }
}, { immediate: true });

const submit = () => {
    if (form.id) {
        form.put(route('incident-types.update', form.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('incident-types.store'), {
            onSuccess: () => emit('close'),
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] p-4 font-sans text-slate-900">
        <div class="bg-white w-full max-w-[520px] rounded-3xl border border-slate-200 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-tight">
                        {{ form.id ? `Actualizar tipo de alerta${form.id}` : 'Nuevo tipo de alerta' }}
                    </h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-8 space-y-6">
                
                <div class="flex flex-col space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter italic">Nombre </label>
                    <input 
                        v-model="form.name"
                        type="text" 
                        placeholder="Ej: Anomalía Electromagnética..." 
                        class="w-full bg-slate-50/50 border border-slate-200 rounded-xl text-[11px] font-bold py-2.5 px-3 focus:bg-white focus:border-slate-900 focus:ring-0 outline-none transition-all uppercase tracking-tighter italic"
                    />
                    <p v-if="form.errors.name" class="text-[9px] text-red-500 font-bold ml-1 uppercase tracking-tighter italic">{{ form.errors.name }}</p>
                </div>

                <div class="pt-6 flex items-center space-x-3 border-t border-slate-50 text-white leading-tight">
                    <button type="button" @click="emit('close')" class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter">
                        Descartar
                    </button>
                    <PrimaryButton type="submit" :processing="form.processing" class="flex-[2] h-11 uppercase tracking-tighter text-[11px] font-bold">
                        {{ form.id ? 'Actualizar Protocolo' : 'Nuevo tipo' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
