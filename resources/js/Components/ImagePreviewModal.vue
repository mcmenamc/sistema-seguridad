<script setup>
import { useImagePreview } from '@/Composables/useImagePreview';
import { X, ExternalLink, Download, ChevronLeft, ChevronRight, Image as ImageIcon } from 'lucide-vue-next';
import { onMounted, onUnmounted } from 'vue';

const { state, closePreview, nextImage, prevImage } = useImagePreview();

const downloadImage = () => {
    const src = state.images[state.currentIndex];
    const link = document.createElement('a');
    link.href = src;
    link.download = state.title || 'evidencia_tecnica';
    link.click();
};

const handleKeys = (e) => {
    if (!state.show) return;
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
    if (e.key === 'Escape') closePreview();
};

onMounted(() => window.addEventListener('keydown', handleKeys));
onUnmounted(() => window.removeEventListener('keydown', handleKeys));
</script>

<template>
    <div 
        v-if="state.show && state.images.length > 0" 
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/95 backdrop-blur-xl p-4 animate-in fade-in duration-300"
        @click.self="closePreview"
    >
        <div class="relative w-full max-w-6xl flex flex-col space-y-4 animate-in zoom-in duration-300">
            
            <div class="flex items-center justify-between bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-3 text-white shadow-2xl">
                <div class="flex items-center space-x-3">
                    <div class="p-1.5 bg-white/10 rounded-lg">
                        <ImageIcon class="w-4 h-4 text-slate-400" />
                    </div>
                    <div>
                        <h3 class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Inspección técnica de evidencia</h3>
                        <p class="text-[11px] font-semibold text-slate-200 tracking-tight">
                            {{ state.title }} 
                            <span v-if="state.images.length > 1" class="ml-2 text-slate-500 font-bold tracking-widest">{{ state.currentIndex + 1 }} DE {{ state.images.length }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button @click="downloadImage" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                        <Download class="w-4 h-4 text-slate-300" />
                    </button>
                    <a :href="state.images[state.currentIndex]" target="_blank" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                        <ExternalLink class="w-4 h-4 text-slate-300" />
                    </a>
                    <div class="w-px h-6 bg-white/10 mx-1"></div>
                    <button @click="closePreview" class="p-2 hover:bg-white/20 bg-white/10 rounded-xl transition-all active:scale-95 group">
                        <X class="w-4 h-4 text-white group-hover:rotate-90 transition-transform" />
                    </button>
                </div>
            </div>

            <div class="relative w-full aspect-video md:aspect-auto md:h-[75vh] flex items-center justify-center p-2 group">
                
                <button 
                    v-if="state.images.length > 1"
                    @click="prevImage" 
                    class="absolute left-4 z-10 p-4 bg-black/20 hover:bg-black/60 backdrop-blur-md rounded-2xl text-white border border-white/10 transition-all opacity-0 group-hover:opacity-100 -translate-x-4 group-hover:translate-x-0 group-active:scale-90 shadow-2xl"
                >
                    <ChevronLeft class="w-4 h-4" />
                </button>

                <div class="relative max-w-full max-h-full rounded-2xl overflow-hidden border border-white/5 shadow-2xl bg-black/40 flex items-center justify-center">
                    <img 
                        :key="state.currentIndex"
                        :src="state.images[state.currentIndex]" 
                        class="max-w-full max-h-full object-contain animate-in fade-in zoom-in-95 duration-500"
                        :alt="state.title"
                    />
                </div>

                <button 
                    v-if="state.images.length > 1"
                    @click="nextImage" 
                    class="absolute right-4 z-10 p-4 bg-black/20 hover:bg-black/60 backdrop-blur-md rounded-2xl text-white border border-white/10 transition-all opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 group-active:scale-90 shadow-2xl"
                >
                    <ChevronRight class="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
</template>
