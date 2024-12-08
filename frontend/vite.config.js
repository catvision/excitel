import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173, // Default port for Vite
  },
  build: {
    manifest: true, // Generate a manifest.json file
    outDir: '../public/assets', // Match your PHP public directory
    rollupOptions: {
      input: './src/main.jsx',
    },
  },
});
