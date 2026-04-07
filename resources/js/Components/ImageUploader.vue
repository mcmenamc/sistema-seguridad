<script setup>
import { ref } from 'vue';
import { UploadCloud, X, FileImage, Image as ImageIcon } from 'lucide-vue-next';
import { useImagePreview } from '@/Composables/useImagePreview';

const props = defineProps({
    modelValue: { type: Array, default: () => [] }, // Archivos nuevos para subir
    existing: { type: Array, default: () => [] }, // Rutas de imágenes ya guardadas
});

const emit = defineEmits(['update:modelValue', 'remove-existing']);
const { openPreview } = useImagePreview();

const isDragging = ref(false);
const previews = ref([]);

const handleFiles = (files) => {
    const newFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
    
    newFiles.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => previews.value.push(e.target.result);
        reader.readAsDataURL(file);
    });

    emit('update:modelValue', [...props.modelValue, ...newFiles]);
};

const handleDrop = (e) => {
    isDragging.value = false;
    handleFiles(e.dataTransfer.files);
};

const removeNew = (index) => {
    const updatedFiles = [...props.modelValue];
    updatedFiles.splice(index, 1);
    previews.value.splice(index, 1);
    emit('update:modelValue', updatedFiles);
};

const removeExisting = (path) => {
    emit('remove-existing', path);
};
</script>

<template>
    <div class="space-y-4 font-sans">
        <div 
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            class="relative border-2 border-dashed rounded-2xl p-6 transition-all cursor-pointer group flex flex-col items-center justify-center space-y-2 bg-slate-50/20"
            :class="isDragging ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-400'"
        >
            <input 
                type="file" 
                multiple 
                accept="image/*" 
                class="absolute inset-0 opacity-0 cursor-pointer"
                @change="(e) => handleFiles(e.target.files)"
            />
            
            <div class="p-3 bg-white rounded-xl shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                <UploadCloud class="w-6 h-6 text-slate-400 group-hover:text-slate-900" />
            </div>
            
            <div class="text-center">
                <p class="text-[11px] font-bold text-slate-700 tracking-tight">Captura de evidencia técnica</p>
                <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-tighter">Arrastra tus fotos o haz clic aquí</p>
            </div>
        </div>

        <div v-if="previews.length > 0 || existing.length > 0" class="flex flex-wrap gap-3">
            <div v-for="path in existing" :key="path" class="relative w-20 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 group cursor-zoom-in">
                <img @click="openPreview('/storage/' + path, 'Evidencia guardada')" :src="'/storage/' + path" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all hover:scale-110" />
                <button @click="removeExisting(path)" class="absolute top-1 right-1 bg-white/90 p-1 rounded-lg text-red-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <X class="w-3 h-3" />
                </button>
                <div class="absolute bottom-0 inset-x-0 bg-slate-900/60 p-1 flex justify-center pointer-events-none">
                    <span class="text-[7px] text-white font-bold uppercase tracking-widest">Server</span>
                </div>
            </div>

            <div v-for="(preview, idx) in previews" :key="idx" class="relative w-20 h-20 rounded-xl overflow-hidden border-2 border-slate-900 bg-slate-50 group cursor-zoom-in">
                <img @click="openPreview(preview, 'Nueva evidencia')" :src="preview" class="w-full h-full object-cover hover:scale-110 transition-transform" />
                <button @click="removeNew(idx)" class="absolute top-1 right-1 bg-white p-1 rounded-lg text-red-500 shadow-sm border border-slate-100 hover:scale-110 transition-transform z-10">
                    <X class="w-3 h-3" />
                </button>
                 <div class="absolute bottom-0 inset-x-0 bg-blue-500 p-1 flex justify-center pointer-events-none">
                    <span class="text-[7px] text-white font-bold uppercase tracking-widest">Nuevo</span>
                </div>
            </div>
        </div>
    </div>
</template>
