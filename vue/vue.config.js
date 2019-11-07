module.exports = {
    publicPath: '../../plugins_packages/upa/vuelistplugin/views/users',
    outputDir: '../views/users',
    assetsDir: '../../assets',
    filenameHashing: false,
    pages: {
        index: {
            entry: 'src/main.js',
            template: 'public/index.php',
            filename: 'index.php'
        }
    }
}
