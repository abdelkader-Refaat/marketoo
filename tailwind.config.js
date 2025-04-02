module.exports = {
    content: [
        './resources/**/*.{html,js,php,vue}',
        './node_modules/your-package/**/*.js' // if using UI packages
    ],
    theme: {
        extend: {}
    },
    plugins: [
        require('tailwindcss-animate') // if using animations
    ]
};
