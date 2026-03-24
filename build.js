import esbuild from 'esbuild'

esbuild.build({
    entryPoints: ['resources/js/rich-content-plugins/consent-iframe.js'],
    outdir: 'resources/dist/js/rich-content-plugins',
    bundle: true,
    minify: true,
    format: 'esm',
    external: ['@tiptap/core'],
    platform: 'browser',
}).then(() => {
    console.log('Built: consent-iframe.js')
})
