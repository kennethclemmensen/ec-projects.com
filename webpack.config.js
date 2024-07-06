import path from 'path';
import { fileURLToPath } from 'url';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';

export default {
    mode: 'production',
    entry: {
        default: [
            './public/themes/custom/ec-projects/ts/script.ts',
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
                    {
                        loader: 'less-loader',
                        options: {
                            lessOptions: {
                                compress: true
                            }
                        }
                    }
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
        minimize: true
    }
};