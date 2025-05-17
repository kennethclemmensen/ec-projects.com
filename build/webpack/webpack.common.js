import path from 'path';
import { fileURLToPath } from 'url';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';

/**
 * The common configuration for Webpack to compile TypeScript and Less files.
 * This configuration is used for both development and production builds.
 */
export default {
    /**
     * Entry configuration.
     * https://webpack.js.org/guides/entry-advanced/
     */
    entry: {
        default: [
            './public/themes/custom/ec-projects/ts/app.ts',
            './public/themes/custom/ec-projects/less/style.less'
        ]
    },
    /**
     * Output configuration.
     * https://webpack.js.org/concepts/output/
     */
    output: {
        filename: '[name].js',
        path: path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../../public/themes/custom/ec-projects/dist')
    },
    /**
     * Setup Webpack to handle TypeScript and Less files.
     * https://webpack.js.org/concepts/modules/
     */
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
    /**
     * Setup the MiniCssExtractPlugin to extract the css into a file.
     * https://webpack.js.org/plugins/mini-css-extract-plugin
     */
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css'
        })
    ],
    /**
     * Setup the resolve configuration to handle TypeScript and JavaScript files.
     * https://webpack.js.org/concepts/module-resolution
     */
    resolve: {
        extensions: ['.ts', '.js']
    }
};