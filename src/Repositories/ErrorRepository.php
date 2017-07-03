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

namespace Superman2014\JsonApi\Repositories;

use Superman2014\JsonApi\Contracts\Repositories\ErrorRepositoryInterface;
use Superman2014\JsonApi\Contracts\Utils\ReplacerInterface;
use Superman2014\JsonApi\Document\Error;

/**
 * Class ErrorRepository
 * @package Superman2014\JsonApi
 */
class ErrorRepository implements ErrorRepositoryInterface
{

    /**
     * @var ReplacerInterface|null
     */
    private $replacer;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * ErrorRepository constructor.
     * @param ReplacerInterface|null $replacer
     *      if provided, will be used to replace values into error detail.
     */
    public function __construct(ReplacerInterface $replacer = null)
    {
        $this->replacer = $replacer;
    }

    /**
     * Add error configuration.
     *
     * @param array $config
     * @return $this
     */
    public function configure(array $config)
    {
        $this->errors = array_merge($this->errors, $config);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return isset($this->errors[$key]);
    }

    /**
     * @inheritdoc
     */
    public function error($key, array $values = [])
    {
        return $this->make($key, $values);
    }

    /**
     * @inheritdoc
     */
    public function errorWithPointer($key, $pointer, array $values = [])
    {
        $error = $this->make($key, $values);
        $error->setSourcePointer($pointer);

        return $error;
    }

    /**
     * @inheritdoc
     */
    public function errorWithParameter($key, $parameter, array $values = [])
    {
        $error = $this->make($key, $values);
        $error->setSourceParameter($parameter);

        return $error;
    }

    /**
     * @param $key
     * @return array
     */
    protected function get($key)
    {
        return isset($this->errors[$key]) ? (array) $this->errors[$key] : [];
    }

    /**
     * @param $key
     * @param array $values
     * @return Error
     */
    protected function make($key, array $values)
    {
        $error = Error::create($this->get($key));

        if ($this->replacer && $error->hasDetail()) {
            $detail = $this->replacer->replace($error->getDetail(), $values);
            $error->setDetail($detail);
        }

        return $error;
    }

}
