import { Node, mergeAttributes } from '@tiptap/core'

/**
 * Editor-side node for consent-gated iframes. The toolbar action (PHP) dispatches
 * the `setConsentIframe` command defined here. In the editor we show the real
 * iframe for preview; the server-side TipTap extension re-renders stored content
 * as a blocked `.consent-message--wrapper` (data-src) for the frontend.
 */
export default Node.create({
    name: 'consentIframe',
    group: 'block',
    atom: true,
    selectable: true,
    draggable: true,

    addOptions() {
        return {
            HTMLAttributes: { class: 'consent-iframe-wrapper' },
            width: 640,
            height: 480,
        }
    },

    addAttributes() {
        return {
            src: {
                default: null,
                parseHTML: (element) => element.getAttribute('data-src') || element.getAttribute('src'),
            },
            'data-consent': {
                default: 'functional',
                parseHTML: (element) => element.getAttribute('data-consent'),
            },
            width: {
                default: this.options.width,
                parseHTML: (element) => element.getAttribute('width'),
            },
            height: {
                default: this.options.height,
                parseHTML: (element) => element.getAttribute('height'),
            },
        }
    },

    parseHTML() {
        return [{ tag: 'iframe[data-consent]' }]
    },

    addCommands() {
        return {
            setConsentIframe: (attributes) => ({ commands }) =>
                commands.insertContent({
                    type: this.name,
                    attrs: attributes,
                }),
        }
    },

    renderHTML({ HTMLAttributes }) {
        const width = HTMLAttributes.width || this.options.width
        const height = HTMLAttributes.height || this.options.height

        return [
            'div',
            mergeAttributes(this.options.HTMLAttributes),
            [
                'iframe',
                {
                    src: HTMLAttributes.src,
                    'data-consent': HTMLAttributes['data-consent'],
                    width,
                    height,
                    style: `aspect-ratio:${width}/${height}; width: 100%; height: auto; pointer-events: none;`,
                },
            ],
        ]
    },
})
