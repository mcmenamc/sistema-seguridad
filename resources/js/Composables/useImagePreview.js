import { reactive } from 'vue';

const state = reactive({
    show: false,
    images: [],
    currentIndex: 0,
    title: ''
});

export function useImagePreview() {
    const openPreview = (images, index = 0, title = 'Evidencia técnica') => {
        state.images = Array.isArray(images) ? images : [images];
        state.currentIndex = index;
        state.title = title;
        state.show = true;
    };

    const closePreview = () => {
        state.show = false;
        state.images = [];
        state.currentIndex = 0;
    };

    const nextImage = () => {
        if (state.currentIndex < state.images.length - 1) {
            state.currentIndex++;
        } else {
            state.currentIndex = 0; 
        }
    };

    const prevImage = () => {
        if (state.currentIndex > 0) {
            state.currentIndex--;
        } else {
            state.currentIndex = state.images.length - 1; 
        }
    };

    return {
        state,
        openPreview,
        closePreview,
        nextImage,
        prevImage
    };
}
