<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Shield, Mail, Loader2, ArrowLeft } from 'lucide-vue-next';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Recuperar Contraseña" />

    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-6 selection:bg-slate-900 selection:text-white font-sans">
        <div class="w-full max-w-[380px]">
            <div class="flex flex-col items-center mb-8 text-center">
                <div class="p-2.5 bg-slate-900 rounded-lg shadow-sm mb-3">
                    <Shield class="w-6 h-6 text-white" />
                </div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-900">Restablecer Acceso</h1>
                <p class="text-xs font-medium text-slate-400 mt-2 tracking-tight px-4 leading-relaxed   leading-none">
                    Recuperación segura de credenciales
                </p>
            </div>

            <div v-if="status" class="mb-4 text-xs font-semibold text-green-600 bg-green-50 p-3 rounded-lg border border-green-100 text-center  tracking-tight">
                {{ status }}
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <p class="text-[11px] text-slate-500 font-medium mb-6 leading-relaxed">
                    Ingresa tu correo institucional registrado. Te enviaremos un enlace seguro para restablecer tu contraseña operativa por correo electrónico.
                </p>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="email" class="text-[10px]  font-semibold text-slate-400  ml-1">Email Registrado</label>
                        <div class="relative group">
                            <Mail class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                            <input 
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-sm text-slate-700 focus:ring-2 focus:ring-slate-900/5 focus:bg-white focus:border-slate-900 outline-none transition-all placeholder:text-slate-200"
                                placeholder="email@institucion.dev"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-[10px] text-red-500 font-semibold ml-1 ">{{ form.errors.email }}</p>
                    </div>

                    <button 
                        :disabled="form.processing"
                        type="submit" 
                        class="w-full bg-slate-900 hover:bg-black text-white text-xs font-semibold py-2.5 rounded-lg transition-all active:scale-[0.98] flex items-center justify-center space-x-2 shadow-sm disabled:opacity-50 mt-4"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        <span v-else>RECUPERAR MI ACCESO</span>
                    </button>
                    
                    <div class="pt-4 mt-6 border-t border-slate-50 flex justify-center">
                        <Link :href="route('login')" class="flex items-center space-x-1.5 text-[10px] font-semibold text-slate-400 hover:text-slate-900   transition-colors">
                            <ArrowLeft class="w-3.5 h-3.5" />
                            <span>Regresar al Login</span>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
