import { defineStore } from 'pinia';
import axios from 'axios';


export const useLanguageStore = defineStore('language', {
  state: () => ({
    languages: '' as string,
    additionalInfo: '' as string,
  }),
  actions: {
    async fetchLanguages(language) {
      try {
        const response = await axios.get(`/language/${language}`);
        this.languages = response.data.languages;
        this.additionalInfo = response.data.additional_info;
      } catch (error) {
        console.error('Error al cargar los idiomas:', error);
      }
    }
  }
});