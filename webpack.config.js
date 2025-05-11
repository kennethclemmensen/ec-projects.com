import path from 'path';
import { fileURLToPath } from 'url';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import CssMinimizerPlugin from 'css-minimizer-webpack-plugin';
import TerserPlugin from 'terser-webpack-plugin';

export default {
    mode: 'production',
    entry: {
        default: [
            './public/themes/custom/ec-projects/ts/app.ts',
            './public/themes/custom/ec-projects/less/style.less'
        ]
    },
    output: {
        filename: '[name].js',
        path: path.resolve(path.dirname(fileURLToPath(import.meta.url)), 'public/themes/custom/ec-projects/dist')
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: 'ts-loader'
            },
            {
                test: /\.less$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'less-loader'
                ]
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css'
        })
    ],
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin()
        ]
    },
    resolve: {
        extensions: ['.ts', '.js']
    },
    devServer: {
        client: false, // disable webpack-dev-server client injection
        devMiddleware: {
            writeToDisk: true // write compiled files to disk
        },
        hot: false, // disable hot module replacement
        port: 3000,
        // proxy - https://webpack.js.org/configuration/dev-server/#devserverproxy
        proxy: [{
            context: ['/'],
            target: 'http://ec-projects.test',
            changeOrigin: true
        }]
    }
};