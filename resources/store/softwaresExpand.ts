import { ref } from 'vue'

/** Solo una app abierta a la vez: 'iq' | 'shop' | null */
export const openSoftware = ref<'iq' | 'shop' | null>(null)

export function toggleSoftware(id: 'iq' | 'shop') {
  openSoftware.value = openSoftware.value === id ? null : id
}
