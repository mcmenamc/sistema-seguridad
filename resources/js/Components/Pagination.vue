<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    meta: { type: Object, required: true }, 
});

const cleanLabel = (label) => {
    if (label.includes('Previous')) return 'Anterior';
    if (label.includes('Next')) return 'Siguiente';
    return label;
};

const isNavLabel = (label) => {
    return label.includes('Previous') || label.includes('Next');
};
</script>

<template>
    <div v-if="meta.links && meta.links.length > 3" class="flex items-center justify-between pt-4 pb-12 w-full font-sans">
        <p class="text-[10px] font-semibold text-slate-400 tracking-tight">
            Mostrando <span class="text-slate-900">{{ meta.from || 0 }}</span> - <span class="text-slate-900">{{ meta.to || 0 }}</span> de <span class="text-slate-900">{{ meta.total }}</span> resultados
        </p>

        <div class="flex items-center space-x-1 bg-white border border-slate-200 rounded-lg p-1 shadow-sm">
            <template v-for="(link, k) in meta.links" :key="k">
                <div v-if="link.url === null" 
                     class="px-3 py-1 text-[10px] rounded font-semibold text-slate-200 pointer-events-none select-none"
                     :class="{ 'hidden sm:block': !isNavLabel(link.label) }"
                     v-html="cleanLabel(link.label)">
                </div>
                
                <Link v-else 
                      :href="link.url" 
                      class="px-3 py-1 text-[10px] rounded font-semibold transition-all select-none flex items-center"
                      :class="[
                          link.active 
                            ? 'bg-slate-900 text-white shadow-sm ring-1 ring-slate-900' 
                            : 'text-slate-400 hover:text-slate-900 hover:bg-slate-50',
                          { 'hidden sm:flex': !isNavLabel(link.label) }
                      ]">
                    <ChevronLeft v-if="link.label.includes('Previous')" class="w-3 h-3 mr-1" />
                    
                    <span v-html="cleanLabel(link.label)"></span>
                    
                    <ChevronRight v-if="link.label.includes('Next')" class="w-3 h-3 ml-1" />
                </Link>
            </template>
        </div>
    </div>
</template>
