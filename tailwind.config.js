import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#3498db",
                secondary: "#f1c40f",
                success: "#2ecc71",
                danger: "#e74c3c",
                warning: "#f1c40f",
                info: "#3498db",
                // Tambahkan variable warna baru
                yellow: "#ffa434",
            },

            borderColor: {
                yellow: "#ffa434",
            },
            backgroundImage: {
                "hero-main":
                    "url('https://storage.googleapis.com/a1aa/image/8m5zXIApxeWWLKkuekxu8hKQDaFF5hrkNmNIGV0egYG0vNHnA.jpg')",
            },
        },
    },

    plugins: [forms, typography],
};
