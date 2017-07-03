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

namespace Superman2014\JsonApi\Hydrator;

use Superman2014\JsonApi\Contracts\Object\RelationshipInterface;
use Superman2014\JsonApi\Utils\Str;

/**
 * Class RelationshipHydratorTrait
 * @package Superman2014\JsonApi
 */
trait RelationshipHydratorTrait
{

    /**
     * Hydrate a relationship by invoking a method on this hydrator.
     *
     * @param $relationshipKey
     * @param RelationshipInterface $relationship
     * @param $record
     * @return bool
     *      whether a method was invoked.
     */
    protected function callHydrateRelationship($relationshipKey, RelationshipInterface $relationship, $record)
    {
        $method = $this->methodForRelationship($relationshipKey);

        if (empty($method) || !method_exists($this, $method)) {
            return false;
        }

        call_user_func([$this, $method], $relationship, $record);

        return true;
    }

    /**
     * Return the method name to call for hydrating the specific relationship.
     *
     * If this method returns an empty value, or a value that is not callable, hydration
     * of the the relationship will be skipped.
     *
     * @param $key
     * @return string|null
     */
    protected function methodForRelationship($key)
    {
        return sprintf('hydrate%sRelationship', Str::classify($key));
    }
}
