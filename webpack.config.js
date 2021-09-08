const path = require('path');
const { VueLoaderPlugin } = require('vue-loader')

module.exports = {
    watch: true,
    module: {
        rules: [
            { test: /\.js$/, use: 'babel-loader' },
            { test: /\.vue$/, use: 'vue-loader' },
            { test: /\.css$/, use: ['vue-style-loader', 'css-loader']},
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
    ]
};