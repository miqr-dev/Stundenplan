const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['scandia-web', ...defaultTheme.fontFamily.sans],
            },
            colors:{
              'miqr-crimson' : '#671926',
              'h-gray' : '#232323',
              'p-gray' : '#565454',
              'place-gray' : '#222222',
              'secondary-gray' : '#777777',
              'link-red' : '#eb4432',
            },
            fontSize: {
              h1: ['40px', '45px'], 
              h2: ['28px', '32px'],
              p:  ['16px', '29px'],
              ul: ['14px', '21px'],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
