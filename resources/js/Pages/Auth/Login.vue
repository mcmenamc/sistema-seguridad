<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Shield, Lock, Mail, Loader2 } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión |" />

    <div class="min-h-screen flex items-center justify-center bg-slate-50 p-6 selection:bg-slate-900 selection:text-white">
        <div class="w-full max-w-[380px]">
            <div class="flex flex-col items-center mb-8">
                <div class="p-2.5 bg-slate-900 rounded-lg shadow-sm mb-3">
                    <Shield class="w-6 h-6 text-white" />
                </div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-900">Sistema de seguridad</h1>
                <p class="text-xs font-medium text-slate-400 mt-1  ">Acceso Restringido</p>
            </div>

            <div v-if="status" class="mb-4 text-xs font-semibold text-green-600 bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                {{ status }}
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="email" class="text-[10px]  font-semibold text-slate-400  ml-1">Email Institucional</label>
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
                                placeholder="usuario@test.com"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-[10px] text-red-500 font-semibold ml-1 ">{{ form.errors.email }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="text-[10px]  font-semibold text-slate-400 ">Contraseña</label>
                            <Link 
                                v-if="canResetPassword" 
                                :href="route('password.request')" 
                                class="text-[9px] font-semibold  text-slate-400 hover:text-slate-900 tracking-tighter"
                            >
                                RECUPERAR
                            </Link>
                        </div>
                        <div class="relative group">
                            <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-slate-900 transition-colors" />
                            <input 
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-100 rounded-lg text-sm text-slate-700 focus:ring-2 focus:ring-slate-900/5 focus:bg-white focus:border-slate-900 outline-none transition-all placeholder:text-slate-200"
                                placeholder="••••••••"
                            />
                        </div>
                        <p v-if="form.errors.password" class="text-[10px] text-red-500 font-semibold ml-1 ">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center space-x-2 py-1 px-1">
                        <input 
                            id="remember"
                            type="checkbox" 
                            v-model="form.remember"
                            class="rounded border-slate-200 text-slate-900 focus:ring-slate-900 focus:ring-offset-0 w-3.5 h-3.5"
                        />
                        <label for="remember" class="text-[10px] font-semibold text-slate-400  tracking-wider">Recordar sesión</label>
                    </div>

                    <button 
                        :disabled="form.processing"
                        type="submit" 
                        class="w-full bg-slate-900 hover:bg-black text-white text-xs font-semibold py-2.5 rounded-lg transition-all active:scale-[0.98] flex items-center justify-center space-x-2 shadow-sm disabled:opacity-50"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        <span v-else>ENTRAR AL SISTEMA</span>
                    </button>
                </form>
            </div>

            <p class="mt-8 text-center text-[9px] font-semibold text-slate-300  tracking-[0.2em]">
                &copy; 2026 Global Monitoring. Todos los derechos reservados.
            </p>
        </div>
    </div>
</template>
