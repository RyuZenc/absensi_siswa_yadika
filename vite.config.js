import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/components.css",
                "resources/js/app.js",
                "resources/js/guru-absensi.js",
                "resources/js/admin-jadwal.js",
            ],
            refresh: true,
        }),
    ],
});
