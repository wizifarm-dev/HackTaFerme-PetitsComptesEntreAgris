var Encore = require("@symfony/webpack-encore");
let CopyWebpack = require('copy-webpack-plugin');

Encore.setOutputPath("public/build/")
    .setPublicPath("/build")
    .addEntry("js/app", "./assets/js/app.js")
    .addStyleEntry("css/app", "./assets/scss/app.scss")
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })
    .enablePostCssLoader(options => {
        options.config = {
            path: "postcss.config.js"
        };
    })
    .addPlugin(
        CopyWebpack([
            { from: './assets/images', to: 'images' }
        ])
    )
    .autoProvidejQuery();

module.exports = Encore.getWebpackConfig();
