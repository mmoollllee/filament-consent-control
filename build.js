import esbuild from 'esbuild'

// Resolve `@tiptap/core` (and `@tiptap/pm/*`) from the TipTap/ProseMirror instance
// Filament already bundles and exposes on `window.FilamentRichEditor.tiptap`, instead
// of leaving a bare `import ... from "@tiptap/core"` in the output. The browser cannot
// resolve that bare specifier when Filament dynamically imports the extension, and
// bundling our own copy would ship a duplicate ProseMirror and break the `instanceof`
// checks the editor core relies on. See the Filament docs: Rich editor →
// "Sharing the bundled TipTap/ProseMirror instance".
const tiptapSharedPlugin = {
    name: 'tiptap-shared',
    setup(build) {
        const keys = {
            '@tiptap/core': 'core',
            '@tiptap/pm/state': 'pmState',
            '@tiptap/pm/view': 'pmView',
            '@tiptap/pm/model': 'pmModel',
        }

        build.onResolve({ filter: /^@tiptap\/(core|pm\/(state|view|model))$/ }, (args) => ({
            path: args.path,
            namespace: 'tiptap-shared',
        }))

        build.onLoad({ filter: /.*/, namespace: 'tiptap-shared' }, async (args) => {
            const realModule = await import(args.path)
            const namedExports = Object.keys(realModule).filter(
                (key) => key !== '__esModule' && key !== 'default',
            )

            const key = keys[args.path]
            let code = `const __module = window.FilamentRichEditor.tiptap.${key};\n`

            if (namedExports.length) {
                code += `export const { ${namedExports.join(', ')} } = __module;\n`
            }

            code += `export default __module?.default ?? __module;\n`

            return { contents: code, loader: 'js' }
        })
    },
}

esbuild.build({
    entryPoints: ['resources/js/rich-content-plugins/consent-iframe.js'],
    outdir: 'resources/dist/js/rich-content-plugins',
    bundle: true,
    minify: true,
    format: 'esm',
    platform: 'browser',
    plugins: [tiptapSharedPlugin],
}).then(() => {
    console.log('Built: consent-iframe.js')
})
