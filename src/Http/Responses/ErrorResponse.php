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

namespace Superman2014\JsonApi\Http\Responses;

use Superman2014\JsonApi\Contracts\Http\Responses\ErrorResponseInterface;
use Superman2014\JsonApi\Exceptions\MutableErrorCollection as Errors;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

/**
 * Class ErrorResponse
 * @package Superman2014\JsonApi
 */
class ErrorResponse implements ErrorResponseInterface
{

    /**
     * @var Errors
     */
    private $errors;

    /**
     * @var int
     */
    private $defaultHttpCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * ErrorResponse constructor.
     * @param ErrorInterface|ErrorInterface[]|ErrorCollection $errors
     * @param int|null $defaultHttpCode
     * @param array $headers
     */
    public function __construct($errors, $defaultHttpCode = null, array $headers = [])
    {
        $this->errors = Errors::cast($errors);
        $this->defaultHttpCode = $defaultHttpCode;
        $this->headers = $headers;
    }

    /**
     * @inheritdoc
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function getHttpCode()
    {
        return $this->errors->getHttpStatus($this->defaultHttpCode);
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

}
