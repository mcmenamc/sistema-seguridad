<script setup>
import { useForm } from '@inertiajs/vue3';
import { X, AlertTriangle, User, Server, FileText, Zap } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    incident: Object,
    devices: Array,
    users: Array,
    eventTypes: Array,
    statusOptions: Array,
});

const emit = defineEmits(['close']);

const form = useForm({
    id: null,
    device_id: '',
    type: '',
    status: 'pendiente',
    description: '',
    assigned_to: '',
});

watch(() => props.incident, (newIncident) => {
    if (newIncident) {
        form.id = newIncident.id;
        form.device_id = newIncident.device_id;
        form.type = newIncident.type;
        form.status = newIncident.status;
        form.description = newIncident.description;
        form.assigned_to = newIncident.assigned_to || '';
    } else {
        form.reset();
        form.id = null;
        form.status = 'pendiente';
    }
}, { immediate: true });

const submit = () => {
    if (form.id) {
        form.put(route('incidents.update', form.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('incidents.store'), {
            onSuccess: () => emit('close'),
        });
    }
};

const closeModal = () => {
    form.reset();
    emit('close');
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] p-4 font-sans text-slate-900">
        <div class="bg-white w-full max-w-[520px] rounded-2xl border border-slate-200 shadow-2xl overflow-visible animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col">
            
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-tight">
                        {{ form.id ? `Administrar incidencia I-${form.id}` : 'Registar nueva incidencia ' }}
                    </h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Dispositivo</label>
                        <SearchableSelect v-model="form.device_id" :options="devices" placeholder="Buscar dispositivo..." class="w-full" :searchable="true" :icon="Server" />
                        <p v-if="form.errors.device_id" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.device_id }}</p>
                    </div>
                    
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Tipo</label>
                        <SearchableSelect v-model="form.type" :options="eventTypes" placeholder="Tipo de alerta..." class="w-full" :searchable="true" :icon="Zap" />
                        <p v-if="form.errors.type" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.type }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Estatus Operativo</label>
                        <SearchableSelect v-model="form.status" :options="statusOptions" placeholder="Definir..." class="w-full" :searchable="false" :icon="Activity" />
                        <p v-if="form.errors.status" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.status }}</p>
                    </div>
                    
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Operador asignado</label>
                        <SearchableSelect v-model="form.assigned_to" :options="users" placeholder="Asignar..." class="w-full" :searchable="true" :icon="User" />
                        <p v-if="form.errors.assigned_to" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.assigned_to }}</p>
                    </div>
                </div>

                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Descripción de bitácora</label>
                    <textarea 
                        v-model="form.description"
                        rows="4"
                        placeholder="Detalle los hallazgos técnicos y acciones realizadas..."
                        class="w-full bg-white border border-slate-200 rounded-xl text-[11px] font-semibold py-2.5 px-3 focus:border-slate-900 focus:ring-0 outline-none transition-all resize-none shadow-sm"
                    ></textarea>
                    <p v-if="form.errors.description" class="text-[8px] text-red-500 font-bold mt-1 uppercase tracking-tighter">{{ form.errors.description }}</p>
                </div>

                <div v-if="incident?.history?.length" class="pt-4 border-t border-slate-100 flex flex-col space-y-3">
                    <div class="flex items-center space-x-1.5 ml-1">
                        <Activity class="w-3 h-3 text-slate-400" />
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-tighter italic">Línea de Tiempo Operativa</label>
                    </div>
                    
                    <div class="space-y-3 pl-4 border-l-2 border-slate-50">
                        <div v-for="log in incident.history" :key="log.id" class="relative flex flex-col space-y-1 pb-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-300 transform -translate-x-[18px] absolute"></div>
                                <span class="text-[9px] font-black text-slate-800 uppercase tracking-tighter italic">{{ log.user?.name || 'Sistema' }}</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter">
                                    {{ new Date(log.created_at).toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit', hour12: true }) }}
                                </span>
                            </div>
                            <div class="bg-slate-50/50 p-2 rounded-xl border border-slate-100/50">
                                <p class="text-[9px] text-slate-500 font-medium leading-tight tracking-tight italic">
                                    {{ log.from_status ? `Estado: ${log.from_status} → ${log.to_status}` : `Incidencia Aperturada: ${log.to_status}` }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center space-x-3 text-white flex-shrink-0">
                    <button type="button" @click="emit('close')" class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter">
                        Descartar
                    </button>
                    <PrimaryButton type="submit" :processing="form.processing" class="flex-[2] h-11 uppercase tracking-tighter text-[11px] font-bold">
                        {{ form.id ? 'Actualizar bitácora' : 'Registrar incidencia' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
