import { DirectiveBinding } from 'vue';
import { useIntersectionObserver } from '@vueuse/core';


const options = {
  root: null, // El viewport por defecto es el viewport del navegador
  rootMargin: '0px', // Márgenes alrededor del viewport
  threshold: 0.4 
};

export default {
  mounted(el: HTMLElement, binding: DirectiveBinding) {
    const { value: callback } = binding;
    useIntersectionObserver(el, ([{ isIntersecting }]) => {
      if (isIntersecting) {
        callback(el);
      }
    }, options);
  }
};