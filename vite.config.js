import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/admin.scss",
                "resources/sass/app.scss",
                "resources/js/admin.js",
                "resources/js/app.js",
                "resources/js/profile/main.js",
            ],
            refresh: true,
        }),
        vue(),
    ],
});
