<script setup>
import { useForm } from '@inertiajs/vue3';
import { X, Zap, Cpu } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    show: Boolean,
    devices: Array,
    eventTypes: Array,
});

const emit = defineEmits(['close']);

const form = useForm({
    device_id: '',
    type: '',
});

const submit = () => {
    form.post(route('events.store'), {
        onSuccess: () => {
            form.reset('device_id');
            emit('close');
        },
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] p-4 font-sans text-slate-900">
        <div class="bg-white w-full max-w-[520px] rounded-2xl border border-slate-200 shadow-2xl overflow-visible animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-tight">Simulador de Evento</h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-6 overflow-y-auto custom-scrollbar">
                
                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Dispositivo</label>
                    <SearchableSelect 
                        v-model="form.device_id" 
                        :options="devices" 
                        placeholder="Seleccionar equipo de la red..." 
                        :icon="Cpu"
                        class="w-full" 
                        :searchable="true" 
                    />
                    <p v-if="form.errors.device_id" class="text-[9px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.device_id }}</p>
                </div>

                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Tipo</label>
                    <SearchableSelect 
                        v-model="form.type" 
                        :options="eventTypes" 
                        placeholder="Tipo de frecuencia..." 
                        :icon="Zap"
                        class="w-full" 
                        :searchable="false" 
                    />
                    <p v-if="form.errors.type" class="text-[9px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.type }}</p>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <p class="text-[10px] text-slate-500 leading-relaxed font-semibold uppercase tracking-tighter">
                        Esta señal será procesada por el motor de autogestión. Si el evento es crítico, se generará una incidencia automática en el panel de control.
                    </p>
                </div>

                <div class="pt-4 flex items-center space-x-3 text-white flex-shrink-0">
                    <button type="button" @click="emit('close')" class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter">
                        Descartar
                    </button>
                    <PrimaryButton type="submit" :processing="form.processing" class="flex-[2] h-11 uppercase tracking-tighter text-[11px] font-bold">
                        Inyectar Señal Técnica
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
