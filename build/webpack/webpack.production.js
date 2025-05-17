import CssMinimizerPlugin from 'css-minimizer-webpack-plugin';
import TerserPlugin from 'terser-webpack-plugin';
import { merge } from 'webpack-merge';
import common from './webpack.common.js';

/**
 * Production configuration for Webpack.
 * This configuration is used for production builds.
 */
export default merge(common, {
    mode: 'production',
    /**
     * Minify JavaScript and CSS files for production builds.
     * https://webpack.js.org/configuration/optimization/
     */
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin()
        ]
    }
});