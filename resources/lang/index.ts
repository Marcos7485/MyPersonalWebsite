import esp from './esp'
import pt from './pt'
import eng from './eng'

export type LocaleCode = 'esp' | 'pt' | 'eng'

export const locales = { esp, pt, eng } as const

export const localeMeta: Record<LocaleCode, { flag: string; label: string }> = {
  esp: { flag: 'esp.webp', label: 'Español' },
  pt: { flag: 'pt.webp', label: 'Português' },
  eng: { flag: 'eng.webp', label: 'English' },
}

export type MessageSchema = typeof esp
