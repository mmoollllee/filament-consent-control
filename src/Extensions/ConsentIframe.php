<?php

declare(strict_types=1);

namespace Mmoollllee\FilamentConsentControl\Extensions;

use Tiptap\Core\Node;

class ConsentIframe extends Node
{
    public static $name = 'consentIframe';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'consent-iframe-wrapper',
            ],
            'width' => 640,
            'height' => 480,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'src' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('src'),
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
            'style' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('style'),
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'iframe',
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        $width = $node->attrs->width ?? $this->options['width'];
        $height = $node->attrs->height ?? $this->options['height'];

        return [
            'div',
            $this->options['HTMLAttributes'],
            [
                'iframe',
                [
                    'src' => $node->attrs->src,
                    'data-consent' => $node->attrs->{'data-consent'} ?? 'functional',
                    'width' => $width,
                    'height' => $height,
                    'style' => "aspect-ratio:{$width}/{$height}; width: 100%; height: auto;",
                ],
            ],
        ];
    }
}
