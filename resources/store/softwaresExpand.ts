import { ref } from 'vue'

/** Solo una app abierta a la vez: 'iq' | 'shop' | 'zankou' | null */
export const openSoftware = ref<'iq' | 'shop' | 'zankou' | null>(null)

export function toggleSoftware(id: 'iq' | 'shop' | 'zankou') {
  openSoftware.value = openSoftware.value === id ? null : id
}
