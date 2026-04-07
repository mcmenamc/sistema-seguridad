<script setup>
import { useForm } from '@inertiajs/vue3';
import { X, Users, Image as ImageIcon } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import ImageUploader from '@/Components/ImageUploader.vue';
import { watch, computed } from 'vue';

const props = defineProps({
    show: Boolean,
    device: Object,
    clients: Array,
    deviceTypes: Array,
});

const emit = defineEmits(['close']);

const clientOptions = computed(() => {
    return [{ id: '', name: 'Sin asignar' }, ...props.clients];
});

const statusOptions = [
    { id: 'activo', name: 'Activo' },
    { id: 'inactivo', name: 'Inactivo' },
    { id: 'alerta', name: 'Alerta' },
];

const form = useForm({
    id: null,
    name: '',
    device_type_id: '',
    status: 'activo',
    location: '',
    client_id: '',
    new_images: [],    
    deleted_images: [],
});

watch(() => props.device, (newDevice) => {
    if (newDevice) {
        form.id = newDevice.id;
        form.name = newDevice.name;
        form.device_type_id = newDevice.device_type_id;
        form.status = newDevice.status;
        form.location = newDevice.location;
        form.client_id = newDevice.client_id;
        form.new_images = [];
        form.deleted_images = [];
    } else {
        form.reset();
        form.id = null;
        form.status = 'activo';
        form.device_type_id = props.deviceTypes?.[0]?.id || '';
    }
}, { immediate: true });

const handleRemoveExisting = (path) => {
    form.deleted_images.push(path);
};

const submit = () => {
    form.clearErrors();
    
    if (form.id) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('devices.update', form.id), {
            onSuccess: () => emit('close'),
            forceFormData: true,
        });
    } else {
        form.post(route('devices.store'), {
            onSuccess: () => emit('close'),
            forceFormData: true,
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] p-4 font-sans text-slate-900">
        <div class="bg-white w-full max-w-[520px] rounded-2xl border border-slate-200 shadow-2xl overflow-visible animate-in fade-in zoom-in duration-200 max-h-[90vh] flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-tight">
                        {{ form.id ? 'Modificar dispositivo' : 'Configurar nuevo equipo' }}
                    </h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>
            
            <form @submit.prevent="submit" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                
                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Nombre identificador</label>
                    <input v-model="form.name" type="text" class="w-full bg-white border border-slate-200 rounded-xl text-[11px] font-semibold py-2.5 px-3 focus:border-slate-900 focus:ring-0 outline-none transition-all" :class="{'border-red-500 bg-red-50/10': form.errors.name}" placeholder="Ej: Cámara PTZ Domo Nort..." />
                    <p v-if="form.errors.name" class="text-[9px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.name }}</p>
                </div>

                <div class="grid gap-4" :class="form.id ? 'grid-cols-2' : 'grid-cols-1'">
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Tipo de equipo</label>
                        <SearchableSelect v-model="form.device_type_id" :options="deviceTypes" placeholder="Tipo..." class="w-full" :searchable="true" />
                        <p v-if="form.errors.device_type_id" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.device_type_id }}</p>
                    </div>
                    
                    <div v-if="form.id" class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Operatividad</label>
                        <SearchableSelect v-model="form.status" :options="statusOptions" placeholder="Estado..." class="w-full" :searchable="false" />
                        <p v-if="form.errors.status" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.status }}</p>
                    </div>
                </div>

                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Cliente</label>
                    <div class="w-full">
                        <SearchableSelect v-model="form.client_id" :options="clientOptions" placeholder="Elegir responsable del equipo..." :icon="Users" class="w-full" :searchable="true" />
                    </div>
                    <p v-if="form.errors.client_id" class="text-[9px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.client_id }}</p>
                </div>

                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter">Ubicación</label>
                    <input v-model="form.location" type="text" class="w-full bg-white border border-slate-200 rounded-xl text-[11px] font-semibold py-2.5 px-3 focus:border-slate-900 focus:ring-0 outline-none transition-all" :class="{'border-red-500 bg-red-50/10': form.errors.location}" placeholder="Ej: Pasillo Elevadores..." />
                    <p v-if="form.errors.location" class="text-[8px] text-red-500 font-bold ml-1 uppercase tracking-tighter">{{ form.errors.location }}</p>
                </div>

                <div class="pt-2 border-t border-slate-50">
                     <ImageUploader 
                        v-model="form.new_images" 
                        :existing="device?.images?.filter(img => !form.deleted_images.includes(img))"
                        @remove-existing="handleRemoveExisting"
                    />
                    <p v-if="form.errors.new_images" class="text-[8px] text-red-500 font-bold mt-1 uppercase tracking-tighter">{{ form.errors.new_images }}</p>
                </div>

                <div class="pt-4 flex items-center space-x-3 text-white flex-shrink-0">
                    <button type="button" @click="emit('close')" class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter">
                        Descartar
                    </button>
                    <PrimaryButton type="submit" :processing="form.processing" class="flex-[2] h-11 uppercase tracking-tighter text-[11px] font-bold">
                        {{ form.id ? 'Actualizar registro' : 'Nuevo Disposititvo' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
