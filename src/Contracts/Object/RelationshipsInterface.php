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

namespace Superman2014\JsonApi\Contracts\Object;

use Superman2014\JsonApi\Exceptions\RuntimeException;
use IteratorAggregate;
use Traversable;

/**
 * Interface RelationshipsInterface
 * @package Superman2014\JsonApi
 */
interface RelationshipsInterface extends StandardObjectInterface, IteratorAggregate
{

    /**
     * Get a traversable object of keys to relationship objects.
     *
     * This iterator will return all keys with values cast to `RelationshipInterface` objects.
     *
     * @return Traversable
     */
    public function getAll();

    /**
     * @param $key
     * @return RelationshipInterface
     * @throws RuntimeException
     *      if the key is not present, or is not an object.
     */
    public function getRelationship($key);

}
