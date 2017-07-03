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

namespace Superman2014\JsonApi\Contracts\Repositories;

use Superman2014\JsonApi\Contracts\Document\MutableErrorInterface;
use Superman2014\JsonApi\Contracts\Utils\ConfigurableInterface;

/**
 * Interface ErrorRepositoryInterface
 * @package Superman2014\JsonApi
 */
interface ErrorRepositoryInterface extends ConfigurableInterface
{

    /**
     * Does an error exist in the repository for the supplied key?
     *
     * @param $key
     * @return bool
     */
    public function exists($key);

    /**
     * @param string $key
     * @param array $values
     *      values to substitute into error detail.
     * @return MutableErrorInterface
     */
    public function error($key, array $values = []);

    /**
     * @param $key
     * @param $pointer
     * @param array $values
     *      values to substitute into error detail.
     * @return MutableErrorInterface
     */
    public function errorWithPointer($key, $pointer, array $values = []);

    /**
     * @param $key
     * @param $parameter
     * @param array $values
     *      values to substitute into error detail.
     * @return MutableErrorInterface
     */
    public function errorWithParameter($key, $parameter, array $values = []);
}
