<script setup>
import { X, AlertTriangle } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    show: Boolean,
    title: { type: String, default: '¿Confirmar Acción Crítica?' },
    message: { type: String, default: 'Esta acción no se puede deshacer una vez confirmada.' },
    confirmText: { type: String, default: 'Confirmar' },
    cancelText: { type: String, default: 'Cancelar' },
    type: { type: String, default: 'danger' }, // danger, warning, info
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 font-sans leading-tight">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] animate-in fade-in duration-300" @click="emit('close')"></div>
        
        <div class="relative bg-white w-full max-w-[420px] rounded-3xl border border-slate-200 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            
            <div class="p-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <AlertTriangle :class="['w-4 h-4', type === 'danger' ? 'text-red-500' : 'text-orange-500']" />
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-tight italic">{{ title }}</h3>
                </div>
                <button @click="emit('close')" class="text-slate-400 hover:text-slate-900 transition-colors p-1">
                    <X class="w-4 h-4" />
                </button>
            </div>

            <div class="p-6">
                <p class="text-[12px] font-bold text-slate-500 leading-relaxed mb-6">
                    {{ message }}
                </p>

                <div class="flex items-center space-x-3 leading-tight">
                    <button 
                        @click="emit('close')" 
                        class="flex-1 h-11 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-400 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-tighter"
                    >
                        {{ cancelText }}
                    </button>
                    <PrimaryButton 
                        @click="emit('confirm')" 
                        :class="['flex-1 h-11 uppercase tracking-tighter text-[11px] font-bold', type === 'danger' ? 'bg-red-600 hover:bg-red-700' : '']"
                    >
                        {{ confirmText }}
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>
