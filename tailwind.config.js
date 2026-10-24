/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#f2470c',
          fg: '#ffffff',
          foreground: '#ffffff',
        },
        background: '#fdfdfd',
        foreground: '#000000',
        paragraph: 'oklch(0.708 0 0)',
        accent: '#fff0eb',
        input: '#ebebeb',
        destructive: '#e54b4f',
        btn: {
          1: '#f2470c',
          2: '#ffad9e',
          3: '#fd822b',
          4: '#3276e4',
          5: '#ffd375',
        },
      },
      fontFamily: {
        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
        body: ['"Inter"', 'sans-serif'],
        sans: ['"Inter"', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
