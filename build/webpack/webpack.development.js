import { merge } from 'webpack-merge';
import common from './webpack.common.js';

/**
 * Development configuration for Webpack.
 * This configuration is used for development builds.
 */
export default merge(common, {
    mode: 'development',
    /**
     * Setup devServer for development builds.
     * https://webpack.js.org/configuration/dev-server/
     */
    devServer: {
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
});