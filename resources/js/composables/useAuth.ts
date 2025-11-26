import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { Auth, User } from '@/types';

export function useAuth() {
    const page = usePage();

    const auth = computed<Auth>(() => page.props.auth as Auth);

    const user = computed<User | null>(() => auth.value.user);

    const hasRole = ( role: string): boolean => {
        return auth.value.roles?.includes(role) || false;
    }

    const hasPermission = (permission: string): boolean => {
        return auth.value.permissions?.includes(permission) || false;
    }

    return {
        auth,
        user,
        hasRole,
        hasPermission,
    };
}