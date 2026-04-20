<?php

namespace App\Services\Content;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizerService
{
    private array $allowedTags = [
        'p',
        'br',
        'strong',
        'b',
        'em',
        'i',
        'u',
        's',
        'strike',
        'ul',
        'ol',
        'li',
        'h1',
        'h2',
        'h3',
        'blockquote',
        'code',
        'pre',
        'mark',
        'span',
    ];

    private array $allowedHighlight = [
        'yellow',
        'orange',
        'green',
        'blue',
        'purple',
        'pink',
        'gray',
    ];

    public function sanitize(string $html): string
    {
        $html = trim($html);

        if ($html === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $wrapped = '<div>'.$html.'</div>';
        $wrapped = mb_convert_encoding($wrapped, 'HTML-ENTITIES', 'UTF-8');
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $dom->documentElement;

        if (! $root) {
            return '';
        }

        $this->sanitizeNode($root);

        return $this->innerHtml($root);
    }

    public function extractTitle(string $html): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');

        if ($text === '') {
            return '';
        }

        $firstLine = explode(' ', $text, 200);
        $title = implode(' ', array_slice($firstLine, 0, 12));

        return mb_substr($title, 0, 120);
    }

    private function sanitizeNode(DOMNode $node): void
    {
        if ($node instanceof DOMElement) {
            $tag = strtolower($node->tagName);

            if (! in_array($tag, $this->allowedTags, true) && $tag !== 'div') {
                $this->unwrapNode($node);

                return;
            }

            if ($tag === 'span') {
                $dataFont = $node->getAttribute('data-font');
                $keepFont = in_array($dataFont, ['serif', 'mono'], true) ? $dataFont : null;

                while ($node->attributes->length > 0) {
                    $node->removeAttributeNode($node->attributes->item(0));
                }

                if ($keepFont !== null) {
                    $node->setAttribute('data-font', $keepFont);
                }
            } elseif ($tag === 'mark') {
                $dataHl = $node->getAttribute('data-hl');
                $keepHl = in_array($dataHl, $this->allowedHighlight, true) ? $dataHl : null;

                while ($node->attributes->length > 0) {
                    $node->removeAttributeNode($node->attributes->item(0));
                }

                if ($keepHl !== null) {
                    $node->setAttribute('data-hl', $keepHl);
                }
            } else {
                while ($node->attributes->length > 0) {
                    $node->removeAttributeNode($node->attributes->item(0));
                }
            }
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            $this->sanitizeNode($child);
        }
    }

    private function unwrapNode(DOMElement $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private function innerHtml(DOMNode $node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument->saveHTML($child);
        }

        return $html;
    }
}
