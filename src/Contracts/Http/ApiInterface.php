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

namespace Superman2014\JsonApi\Contracts\Http;

use Superman2014\JsonApi\Contracts\Http\Requests\RequestInterpreterInterface;
use Superman2014\JsonApi\Contracts\Pagination\PagingStrategyInterface;
use Superman2014\JsonApi\Contracts\Store\StoreInterface;
use Neomerx\JsonApi\Contracts\Codec\CodecMatcherInterface;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\SupportedExtensionsInterface;
use Neomerx\JsonApi\Contracts\Http\HttpFactoryInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface as SchemaContainerInterface;

/**
 * Interface ApiInterface
 * @package Superman2014\JsonApi
 */
interface ApiInterface
{

    /**
     * Get the unique name for the API instance.
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Get the HTTP factory for this API instance
     *
     * @return HttpFactoryInterface
     */
    public function getHttpFactory();

    /**
     * Get the request interpreter to use for this API instance.
     *
     * @return RequestInterpreterInterface
     */
    public function getRequestInterpreter();

    /**
     * Get the codec matcher for this API.
     *
     * @return CodecMatcherInterface
     */
    public function getCodecMatcher();

    /**
     * Get the encoder for this API.
     *
     * @return EncoderInterface|null
     */
    public function getEncoder();

    /**
     * Has an encoder been matched to the Accept headers?
     *
     * @return bool
     */
    public function hasEncoder();

    /**
     * Get the schema container for this API.
     *
     * @return SchemaContainerInterface
     */
    public function getSchemas();

    /**
     * Get the URL prefix for this API.
     *
     * @return string|null
     */
    public function getUrlPrefix();

    /**
     * Get the supported extensions for this API.
     *
     * @return SupportedExtensionsInterface|null
     */
    public function getSupportedExts();

    /**
     * Get the store for domain record objects for this API.
     *
     * @return StoreInterface
     */
    public function getStore();

    /**
     * Get the paging strategy that is being used for this API.
     *
     * @return PagingStrategyInterface
     */
    public function getPagingStrategy();

    /**
     * Get other options for this API
     *
     * This allows injection of options for any framework-specific components as needed.
     *
     * @return array
     */
    public function getOptions();
}
