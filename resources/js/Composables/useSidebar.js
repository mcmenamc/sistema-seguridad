import { reactive } from 'vue';

const state = reactive({
    isOpen: true, // Controla el estado en móviles
});

export function useSidebar() {
    const toggleSidebar = () => {
        state.isOpen = !state.isOpen;
    };

    const closeSidebar = () => {
        state.isOpen = false;
    };

    const openSidebar = () => {
        state.isOpen = true;
    };

    return {
        state,
        toggleSidebar,
        closeSidebar,
        openSidebar
    };
}
