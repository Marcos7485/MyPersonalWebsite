/// <reference types="vite/client" />

interface ImportMeta {
    glob: (glob: string, options?: { eager?: boolean }) => Record<string, () => Promise<any>>;
  }
  