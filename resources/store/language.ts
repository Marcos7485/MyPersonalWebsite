import { defineStore } from 'pinia'
import { locales, localeMeta, type LocaleCode, type MessageSchema } from '../lang'

const STORAGE_KEY = 'drs-locale'

function resolvePath(messages: MessageSchema, path: string): string {
  const parts = path.split('.')
  let current: unknown = messages

  for (const part of parts) {
    if (current && typeof current === 'object' && part in current) {
      current = (current as Record<string, unknown>)[part]
    } else {
      return path
    }
  }

  return typeof current === 'string' ? current : path
}

function readStoredLocale(): LocaleCode {
  try {
    const stored = localStorage.getItem(STORAGE_KEY)
    if (stored === 'esp' || stored === 'pt' || stored === 'eng') return stored
  } catch {
    /* ignore */
  }
  return 'esp'
}

export const useLanguageStore = defineStore('language', {
  state: () => ({
    locale: (typeof window !== 'undefined' ? readStoredLocale() : 'esp') as LocaleCode,
  }),

  getters: {
    /** Compatibilidad con el código anterior que usaba `languages` */
    languages: (state): LocaleCode => state.locale,

    messages: (state): MessageSchema => locales[state.locale],

    flagFile: (state): string => localeMeta[state.locale].flag,

    availableLocales: (): LocaleCode[] => ['esp', 'pt', 'eng'],
  },

  actions: {
    t(key: string): string {
      return resolvePath(locales[this.locale], key)
    },

    setLocale(locale: LocaleCode) {
      this.locale = locale
      try {
        localStorage.setItem(STORAGE_KEY, locale)
      } catch {
        /* ignore */
      }
    },

    /** Compatibilidad con el selector anterior */
    async fetchLanguages(language: string) {
      if (language === 'esp' || language === 'pt' || language === 'eng') {
        this.setLocale(language)
      }
    },
  },
})
