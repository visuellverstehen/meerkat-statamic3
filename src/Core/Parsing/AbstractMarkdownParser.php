<?php

namespace Stillat\Meerkat\Core\Parsing;

use Stillat\Meerkat\Core\Configuration;
use Stillat\Meerkat\Core\Contracts\Comments\CommentContract;
use Stillat\Meerkat\Core\Contracts\Parsing\MarkdownParserContract;

/**
 * Provides a base abstract implementation of MarkdownParserContract
 *
 * This abstract implementation handles all but the parseDocument
 * responsibility of the MarkdownParserContract. Implementations
 * should only have to provide an actual Markdown parser.
 * 
 * @since 2.0.0
 */
abstract class AbstractMarkdownParser implements MarkdownParserContract
{

    /**
     * An instance of the shared Meerkat Configuration context.
     *
     * @var \Stillat\Meerkat\Core\Configuration
     */
    private $configuration = null;

    /**
     * Constructs an instance of the MarkdownParserContract implementation
     *
     * @param Configuration $config The Meerkat configuration object.
     */
    public function __construct(Configuration $config)
    {
        $this->configuration = $config;
    }

    /**
     * Removes problematic HTML elements from the provided document.
     *
     * @param  string $content
     *
     * @return string
     */
    public function cleanDocument($content)
    {
        return strip_tags($content, $this->configuration->getFormattingConfiguration()->htmlTagsToClean);
    }

    /**
     * Parses and cleans the provided content and returns the result.
     *
     * @param  string $content
     *
     * @return string
     */
    public function parse($content)
    {
        return $this->cleanDocument($this->parseDocument($content));
    }

    /**
     * Parses the provided string content and merges the results into the provided data container array.
     *
     * @param string $stringContent The content to parse.
     * @param array $dataContainer
     * @return void
     */
    public function parseStringAndMerge($stringContent, array &$dataContainer)
    {
        $parsedContent = $this->parse($stringContent);

        $dataContainer[CommentContract::KEY_CONTENT] = $parsedContent;
        $dataContainer[CommentContract::KEY_COMMENT] = $parsedContent;
        $dataContainer[CommentContract::KEY_RAW_CONTENT] = $stringContent;
    }
}