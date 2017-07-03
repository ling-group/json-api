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

use Superman2014\JsonApi\Contracts\Hydrator\HydratorInterface;
use Superman2014\JsonApi\Contracts\Object\RelationshipInterface;
use Superman2014\JsonApi\Contracts\Object\RelationshipsInterface;
use Superman2014\JsonApi\Contracts\Object\ResourceInterface;
use Superman2014\JsonApi\Contracts\Object\StandardObjectInterface;
use Superman2014\JsonApi\Exceptions\RuntimeException;

/**
 * Class AbstractHydrator
 * @package Superman2014\JsonApi
 */
abstract class AbstractHydrator implements HydratorInterface
{

    use RelationshipHydratorTrait;

    /**
     * @param StandardObjectInterface $attributes
     * @param $record
     * @return void
     */
    abstract protected function hydrateAttributes(StandardObjectInterface $attributes, $record);

    /**
     * Transfer data from a resource to a record.
     *
     * @param ResourceInterface $resource
     * @param object $record
     * @return object
     */
    public function hydrate(ResourceInterface $resource, $record)
    {
        $this->hydrating($resource, $record);
        $this->hydrateAttributes($resource->getAttributes(), $record);
        $this->hydrateRelationships($resource->getRelationships(), $record);
        $this->hydrated($resource, $record);

        return $record;
    }

    /**
     * Transfer data from a resource relationship to a record.
     *
     * @param $relationshipKey
     *      the key of the relationship to hydrate.
     * @param RelationshipInterface $relationship
     *      the relationship object to use for the hydration.
     * @param object $record
     *      the object to hydrate.
     * @return void
     */
    public function hydrateRelationship($relationshipKey, RelationshipInterface $relationship, $record)
    {
        if (!$this->callHydrateRelationship($relationshipKey, $relationship, $record)) {
            throw new RuntimeException("Cannot hydrate relationship: $relationshipKey");
        }
    }

    /**
     * @param RelationshipsInterface $relationships
     * @param $record
     */
    protected function hydrateRelationships(RelationshipsInterface $relationships, $record)
    {
        /** @var RelationshipInterface $relationship */
        foreach ($relationships->getAll() as $key => $relationship) {
            $this->callHydrateRelationship($key, $relationship, $record);
        }
    }

    /**
     * Called before any hydration occurs.
     *
     * Child classes can overload this method if they need to do any logic pre-hydration.
     *
     * @param ResourceInterface $resource
     * @param $record
     * @return void
     */
    protected function hydrating(ResourceInterface $resource, $record)
    {
    }

    /**
     * Called after hydration has occurred.
     *
     * Child classes can overload this method if they need to do any logic post-hydration.
     *
     * @param ResourceInterface $resource
     * @param $record
     * @return void
     */
    protected function hydrated(ResourceInterface $resource, $record)
    {
    }
}
