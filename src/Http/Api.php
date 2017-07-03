<?php

/**
 * Copyright 2016 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Superman2014\JsonApi\Http;

use Superman2014\JsonApi\Contracts\Http\ApiInterface;
use Superman2014\JsonApi\Contracts\Http\Requests\RequestInterpreterInterface;
use Superman2014\JsonApi\Contracts\Pagination\PagingStrategyInterface;
use Superman2014\JsonApi\Contracts\Store\StoreInterface;
use Superman2014\JsonApi\Pagination\PagingStrategy;
use Neomerx\JsonApi\Contracts\Codec\CodecMatcherInterface;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\SupportedExtensionsInterface;
use Neomerx\JsonApi\Contracts\Http\HttpFactoryInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface as SchemaContainerInterface;
use Neomerx\JsonApi\Factories\Factory;

/**
 * Class Api
 * @package Superman2014\JsonApi
 */
class Api implements ApiInterface
{

    const CONFIG_URL_PREFIX = 'url-prefix';
    const CONFIG_ROUTE_PREFIX = 'route-prefix';
    const CONFIG_SUPPORTED_EXT = 'supported-ext';

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var HttpFactoryInterface
     */
    private $httpFactory;

    /**
     * @var RequestInterpreterInterface
     */
    private $interpreter;

    /**
     * @var CodecMatcherInterface
     */
    private $codecMatcher;

    /**
     * @var SchemaContainerInterface
     */
    private $schemas;

    /**
     * @param StoreInterface
     */
    private $store;

    /**
     * @var null|string
     */
    private $urlPrefix;

    /**
     * @var SupportedExtensionsInterface|null
     */
    private $supportedExtensions;

    /**
     * @var PagingStrategyInterface
     */
    private $pagingStrategy;

    /**
     * @var array
     */
    private $options;

    /**
     * ApiContainer constructor.
     * @param string $namespace
     * @param RequestInterpreterInterface $interpreter
     * @param CodecMatcherInterface $codecMatcher
     * @param SchemaContainerInterface $schemaContainer
     * @param StoreInterface $store
     * @param SupportedExtensionsInterface|null $supportedExtensions
     * @param PagingStrategyInterface|null $pagingStrategy
     * @param HttpFactoryInterface|null $httpFactory
     * @param string|null $urlPrefix
     * @param array $options
     */
    public function __construct(
        $namespace,
        RequestInterpreterInterface $interpreter,
        CodecMatcherInterface $codecMatcher,
        SchemaContainerInterface $schemaContainer,
        StoreInterface $store,
        SupportedExtensionsInterface $supportedExtensions = null,
        PagingStrategyInterface $pagingStrategy = null,
        HttpFactoryInterface $httpFactory = null,
        $urlPrefix = null,
        array $options = []
    ) {
        $this->namespace = $namespace;
        $this->interpreter = $interpreter;
        $this->codecMatcher = $codecMatcher;
        $this->schemas = $schemaContainer;
        $this->store = $store;
        $this->supportedExtensions = $supportedExtensions;
        $this->pagingStrategy = $pagingStrategy ?: new PagingStrategy();
        $this->httpFactory = $httpFactory ?: new Factory();
        $this->urlPrefix = $urlPrefix;
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @inheritDoc
     */
    public function getHttpFactory()
    {
        return $this->httpFactory;
    }

    /**
     * @inheritdoc
     */
    public function getRequestInterpreter()
    {
        return $this->interpreter;
    }

    /**
     * @inheritdoc
     */
    public function getCodecMatcher()
    {
        return $this->codecMatcher;
    }

    /**
     * @inheritdoc
     */
    public function getEncoder()
    {
        return $this->getCodecMatcher()->getEncoder();
    }

    /**
     * @inheritdoc
     */
    public function hasEncoder()
    {
        return $this->getEncoder() instanceof EncoderInterface;
    }

    /**
     * @inheritdoc
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * @inheritdoc
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @inheritdoc
     */
    public function getUrlPrefix()
    {
        return $this->urlPrefix;
    }

    /**
     * @inheritdoc
     */
    public function getSupportedExts()
    {
        return $this->supportedExtensions;
    }

    /**
     * @inheritdoc
     */
    public function getPagingStrategy()
    {
        return $this->pagingStrategy;
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->options;
    }
}
