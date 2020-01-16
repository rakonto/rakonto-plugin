<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita0c14474025ff4f178bb0e42e00c8224
{
    public static $classMap = array (
        'pQuery' => __DIR__ . '/..' . '/tburry/pquery/pQuery.php',
        'pQuery\\AspEmbeddedNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\CSSQueryTokenizer' => __DIR__ . '/..' . '/tburry/pquery/gan_selector_html.php',
        'pQuery\\CdataNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\CommentNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\ConditionalTagNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\DoctypeNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\DomNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\EmbeddedNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\Html5Parser' => __DIR__ . '/..' . '/tburry/pquery/gan_parser_html.php',
        'pQuery\\HtmlFormatter' => __DIR__ . '/..' . '/tburry/pquery/gan_formatter.php',
        'pQuery\\HtmlParser' => __DIR__ . '/..' . '/tburry/pquery/gan_parser_html.php',
        'pQuery\\HtmlParserBase' => __DIR__ . '/..' . '/tburry/pquery/gan_parser_html.php',
        'pQuery\\HtmlSelector' => __DIR__ . '/..' . '/tburry/pquery/gan_selector_html.php',
        'pQuery\\IQuery' => __DIR__ . '/..' . '/tburry/pquery/IQuery.php',
        'pQuery\\TextNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
        'pQuery\\TokenizerBase' => __DIR__ . '/..' . '/tburry/pquery/gan_tokenizer.php',
        'pQuery\\XML2ArrayParser' => __DIR__ . '/..' . '/tburry/pquery/gan_xml2array.php',
        'pQuery\\XmlNode' => __DIR__ . '/..' . '/tburry/pquery/gan_node_html.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInita0c14474025ff4f178bb0e42e00c8224::$classMap;

        }, null, ClassLoader::class);
    }
}
