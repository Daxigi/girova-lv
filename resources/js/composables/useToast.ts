
import { useToast as useToastification } from 'vue-toastification';

export function useToast() {
    const toast = useToastification();

    return {
        success: (message: string) => {
            toast.success(message, {
            });
        },

        error: (message: string) => {
            toast.error(message, {
                timeout: 4000,
            });
        },

        warning: (message: string) => {
            toast.warning(message);
        },

        info: (message: string) => {
            toast.info(message);
        },

        clear: () => {
            toast.clear();
        },
    };
}
