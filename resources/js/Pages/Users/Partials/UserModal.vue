<script setup>
import { useForm } from '@inertiajs/vue3';
import { X, User, Mail, Lock, ShieldCheck } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    user: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    id: null,
    name: '',
    email: '',
    role: 'Operador',
    password: '',
    password_confirmation: '',
});

watch(() => props.user, (newUser) => {
    if (newUser) {
        form.id = newUser.id;
        form.name = newUser.name;
        form.email = newUser.email;
        form.role = newUser.roles[0]?.name || 'Operador';
        form.password = '';
        form.password_confirmation = '';
    } else {
        form.reset();
        form.id = null;
        form.role = 'Operador';
    }
}, { immediate: true });

const submit = () => {
    if (form.id) {
        form.put(route('users.update', form.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('users.store'), {
            onSuccess: () => emit('close'),
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-[2px] p-4 font-sans text-slate-900 leading-tight">
        <div class="bg-white w-full max-w-[520px] rounded-3xl border border-slate-200 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 flex flex-col max-h-[90vh]">
            
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-tight">
                        {{ form.id ? 'Modificar usuario' : 'Registrar nuevo usuario' }}
                    </h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                
                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter italic">Nombre del Operador</label>
                    <div class="relative group">
                        <User class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                        <input 
                            v-model="form.name"
                            type="text" 
                            placeholder="Ej: Lic. Antonio M..." 
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl text-[11px] font-bold py-2.5 pl-10 pr-4 focus:bg-white focus:border-slate-900 focus:ring-0 outline-none transition-all uppercase tracking-tighter"
                        />
                    </div>
                </div>

                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter italic">Canal de Acceso (Email)</label>
                    <div class="relative group">
                        <Mail class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                        <input 
                            v-model="form.email"
                            type="email" 
                            placeholder="admin@seguridad.com..." 
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl text-[11px] font-bold py-2.5 pl-10 pr-4 focus:bg-white focus:border-slate-900 focus:ring-0 outline-none transition-all"
                        />
                    </div>
                </div>

                <div class="flex flex-col space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter italic">Rol Administrativo / Operativo</label>
                    <div class="relative group">
                        <ShieldCheck class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                        <select 
                            v-model="form.role"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-xl text-[11px] font-bold py-2.5 pl-10 pr-4 focus:bg-white focus:border-slate-900 focus:ring-0 outline-none transition-all appearance-none uppercase tracking-tighter"
                        >
                            <option value="Administrador">Administrador</option>
                            <option value="Operador">Operador</option>
                            <option value="Cliente">Cliente</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter italic">{{ form.id ? 'Nueva Clave (Opc)' : 'Clave de Acceso' }}</label>
                        <div class="relative group">
                            <Lock class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                            <input 
                                v-model="form.password"
                                type="password" 
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl text-[11px] font-bold py-2.5 pl-10 pr-4 focus:bg-white focus:border-slate-900 focus:ring-0 outline-none transition-all"
                            />
                        </div>
                    </div>
                    <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 ml-1 uppercase tracking-tighter italic">Confirmar Clave</label>
                        <div class="relative group">
                            <Lock class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                            <input 
                                v-model="form.password_confirmation"
                                type="password" 
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-xl text-[11px] font-bold py-2.5 pl-10 pr-4 focus:bg-white focus:border-slate-900 focus:ring-0 outline-none transition-all"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="Object.keys(form.errors).length > 0" class="p-3 bg-red-50 border border-red-100 rounded-xl">
                    <ul class="list-disc list-inside">
                        <li v-for="error in form.errors" :key="error" class="text-[9px] text-red-600 font-bold uppercase tracking-tighter leading-none mb-1">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="pt-6 flex items-center space-x-3 border-t border-slate-50 leading-tight flex-shrink-0">
                    <button type="button" @click="emit('close')" class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter">
                        Cancelar
                    </button>
                    <PrimaryButton type="submit" :processing="form.processing" class="flex-[2] h-11 uppercase tracking-tighter text-[11px] font-bold">
                        {{ form.id ? 'Inyectar Cambios' : 'Registrar Operador' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
