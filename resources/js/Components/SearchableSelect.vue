<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Search, ChevronDown, Check, X } from 'lucide-vue-next';

const props = defineProps({
    modelValue: [String, Number],
    options: { type: Array, default: () => [] }, 
    placeholder: { type: String, default: 'Seleccionar...' },
    label: { type: String, default: '' },
    icon: { type: Object, default: null },
    searchable: { type: Boolean, default: true },
});

const emit = defineEmits(['update:modelValue', 'change']);

const isOpen = ref(false);
const searchQuery = ref('');
const selectRef = ref(null);

const toggle = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) searchQuery.value = '';
};

const close = () => {
    isOpen.value = false;
};

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options;
    return props.options.filter(opt => 
        String(opt.name || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectedOption = computed(() => {
    return props.options.find(opt => opt.id == props.modelValue);
});

const select = (option) => {
    emit('update:modelValue', option.id);
    emit('change', option.id);
    close();
};

const clear = (e) => {
    e.stopPropagation();
    emit('update:modelValue', '');
    emit('change', '');
};

const handleClickOutside = (e) => {
    if (selectRef.value && !selectRef.value.contains(e.target)) {
        close();
    }
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>

<template>
    <div ref="selectRef" class="relative inline-block text-left w-full sm:w-auto">
        <div @click="toggle" 
            class="flex items-center bg-white border border-slate-200 rounded-lg shadow-sm px-3 h-10 cursor-pointer hover:border-slate-400 transition-all active:scale-[0.98] select-none min-w-[140px] justify-between">
            <div class="flex items-center space-x-2 overflow-hidden mr-2">
                <component v-if="icon" :is="icon" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                <span class="text-[11px] font-semibold text-slate-600 truncate tracking-tight">
                    {{ selectedOption ? selectedOption.name : placeholder }}
                </span>
            </div>
            <div class="flex items-center space-x-1">
                <X v-if="modelValue && modelValue !== ''" @click="clear" class="w-3 h-3 text-slate-300 hover:text-red-400 transition-colors" />
                <ChevronDown class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': isOpen }" />
            </div>
        </div>

        <div v-if="isOpen" 
            class="absolute z-[100] mt-1.5 w-full min-w-[200px] bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-200 origin-top">
            
            <div v-if="searchable" class="p-2 border-b border-slate-50 bg-slate-50/50">
                <div class="relative">
                    <Search class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-400" />
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Buscar..." 
                        class="w-full pl-8 pr-2 py-1.5 bg-white border border-slate-200 rounded-md text-[11px] font-medium focus:ring-1 focus:ring-slate-900 outline-none placeholder:text-slate-300"
                        @click.stop
                        autofocus
                    />
                </div>
            </div>

            <div class="max-h-[250px] overflow-y-auto py-1 custom-scrollbar">
                <div v-if="filteredOptions.length === 0" class="px-4 py-3 text-[10px] text-slate-400 text-center font-medium italic">
                    No se encontraron resultados
                </div>
                
                <div 
                    v-for="option in filteredOptions" 
                    :key="option.id"
                    @click="select(option)"
                    class="px-3 py-2 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors group"
                >
                    <div class="flex items-center space-x-2">
                        <component v-if="option.icon" :is="option.icon" class="w-3 h-3 text-slate-400 group-hover:text-slate-900" />
                        <span class="text-[11px] font-semibold text-slate-600 group-hover:text-slate-900 tracking-tight whitespace-nowrap">
                            {{ option.name }}
                        </span>
                    </div>
                    <Check v-if="modelValue == option.id" class="w-3.5 h-3.5 text-blue-600" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
