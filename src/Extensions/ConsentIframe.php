<?php

declare(strict_types=1);

namespace Mmoollllee\FilamentConsentControl\Extensions;

use Tiptap\Core\Node;

/**
 * Server-side TipTap node. Renders a stored consent iframe as the
 * `.consent-message--wrapper` + iframe[data-src] markup understood by the
 * consent-control runtime, so the iframe stays blocked until the visitor grants
 * the chosen consent category (the runtime injects the localised overlay and
 * loads the real src on consent).
 */
class ConsentIframe extends Node
{
    public static $name = 'consentIframe';

    public function addOptions(): array
    {
        return [
            'width' => 640,
            'height' => 480,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'src' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-src') ?: $DOMNode->getAttribute('src'),
            ],
            'data-consent' => [
                'default' => 'functional',
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-consent'),
            ],
            'width' => [
                'default' => $this->options['width'],
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('width'),
            ],
            'height' => [
                'default' => $this->options['height'],
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('height'),
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            ['tag' => 'iframe[data-consent]'],
            ['tag' => 'div.consent-message--wrapper'],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        $src = $node->attrs->src ?? null;
        $consent = $node->attrs->{'data-consent'} ?? 'functional';
        $width = $node->attrs->width ?? $this->options['width'];
        $height = $node->attrs->height ?? $this->options['height'];
        $srcName = $src ? (parse_url($src, PHP_URL_HOST) ?: 'extern') : 'extern';

        return [
            'div',
            [
                'class' => 'consent-message--wrapper consent-iframe-wrapper',
                'data-consent' => $consent,
                'data-src-name' => $srcName,
            ],
            [
                'iframe',
                [
                    'data-src' => $src,
                    'data-consent' => $consent,
                    'width' => $width,
                    'height' => $height,
                    'loading' => 'lazy',
                    'style' => "aspect-ratio:{$width}/{$height}; width: 100%; height: auto;",
                ],
            ],
        ];
    }
}
