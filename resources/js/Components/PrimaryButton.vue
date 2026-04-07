<script setup>
import { Loader2 } from 'lucide-vue-next';

defineProps({
    type: { type: String, default: 'submit' },
    processing: { type: Boolean, default: false },
    variant: { type: String, default: 'primary' }, // primary, secondary, outline, ghost
});

const variants = {
    primary: 'bg-slate-900 border-transparent text-white hover:bg-black shadow-sm',
    secondary: 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 border shadow-sm',
    outline: 'bg-transparent border-slate-200 text-slate-600 hover:border-slate-900 hover:text-slate-900 border shadow-sm',
    ghost: 'bg-transparent border-transparent text-slate-500 hover:bg-slate-100',
    danger: 'bg-red-50 border-red-100 text-red-600 hover:bg-red-100 border shadow-sm',
};
</script>

<template>
    <button :type="type" :disabled="processing"
        class="inline-flex items-center justify-center px-4 py-2 border rounded-lg text-xs font-semibold transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none select-none"
        :class="variants[variant]">
        <Loader2 v-if="processing" class="w-3.5 h-3.5 animate-spin mr-2" />
        <slot />
    </button>
</template>
