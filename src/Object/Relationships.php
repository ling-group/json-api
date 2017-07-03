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

namespace Superman2014\JsonApi\Object;

use Superman2014\JsonApi\Contracts\Object\RelationshipsInterface;
use Superman2014\JsonApi\Exceptions\RuntimeException;

/**
 * Class Relationships
 * @package Superman2014\JsonApi
 */
class Relationships extends StandardObject implements RelationshipsInterface
{

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        foreach ($this->keys() as $key) {
            yield $key => $this->getRelationship($key);
        }
    }

    /**
     * @inheritdoc
     */
    public function getRelationship($key)
    {
        if (!$this->has($key)) {
            throw new RuntimeException("Relationship member '$key' is not present.");
        }

        $value = parent::get($key);

        if (!is_object($value)) {
            throw new RuntimeException("Relationship member '$key' is not an object.'");
        }

        return new Relationship($value);
    }

}
