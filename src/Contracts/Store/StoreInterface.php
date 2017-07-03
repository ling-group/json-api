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

namespace Superman2014\JsonApi\Contracts\Store;

use Superman2014\JsonApi\Contracts\Object\ResourceIdentifierInterface;
use Superman2014\JsonApi\Exceptions\RecordNotFoundException;

/**
 * Interface StoreInterface
 * @package Superman2014\JsonApi
 */
interface StoreInterface
{

    /**
     * Attach an adapter to the store
     *
     * @param AdapterInterface $adapter
     * @return void
     */
    public function register(AdapterInterface $adapter);

    /**
     * Attach many adapters to the store
     *
     * @param AdapterInterface[] $adapters
     * @return void
     */
    public function registerMany(array $adapters);

    /**
     * Is the supplied resource type valid?
     *
     * @param $resourceType
     * @return bool
     */
    public function isType($resourceType);

    /**
     * Does the record this resource identifier refers to exist?
     *
     * @param ResourceIdentifierInterface $identifier
     * @return bool
     */
    public function exists(ResourceIdentifierInterface $identifier);

    /**
     * @param ResourceIdentifierInterface $identifier
     * @return object|null
     *      the record, or null if it does not exist.
     */
    public function find(ResourceIdentifierInterface $identifier);

    /**
     * @param ResourceIdentifierInterface $identifier
     * @return object
     *      the record
     * @throws RecordNotFoundException
     *      if the record does not exist.
     */
    public function findRecord(ResourceIdentifierInterface $identifier);

}
