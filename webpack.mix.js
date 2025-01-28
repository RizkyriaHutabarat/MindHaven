const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");

// Menyalin style.css dari resources ke public
mix.copy("resources/css/style.css", "public/css/style.css");

// Konfigurasi untuk TailwindCSS
mix.postCss("resources/css/app.css", "public/css", [
    tailwindcss("./tailwind.config.js"), // Menggunakan file konfigurasi tailwind.config.js
    require("autoprefixer"),
])
    .js("resources/js/app.js", "public/js")
    .sourceMaps();
