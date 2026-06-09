import { ref } from 'vue';

const alert = ref({ show: false, message: '', type: 'success' as 'success' | 'error' });
let timeoutId: ReturnType<typeof setTimeout> | null = null;

export function useAlert() {
    const triggerAlert = (message: string, type: 'success' | 'error') => {
        if (timeoutId) clearTimeout(timeoutId);
        alert.value = { show: true, message, type };
        timeoutId = setTimeout(() => { alert.value.show = false; }, 4000);
    };

    return { alert, triggerAlert };
}
