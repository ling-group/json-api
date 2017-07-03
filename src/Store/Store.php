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

namespace Superman2014\JsonApi\Store;

use Superman2014\JsonApi\Contracts\Object\ResourceIdentifierInterface;
use Superman2014\JsonApi\Contracts\Store\AdapterInterface;
use Superman2014\JsonApi\Contracts\Store\StoreInterface;
use Superman2014\JsonApi\Exceptions\InvalidArgumentException;
use Superman2014\JsonApi\Exceptions\RecordNotFoundException;
use Superman2014\JsonApi\Exceptions\RuntimeException;

/**
 * Class Store
 * @package Superman2014\JsonApi
 */
class Store implements StoreInterface
{

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @var AdapterInterface[]
     */
    private $adapters = [];

    /**
     * Store constructor.
     */
    public function __construct()
    {
        $this->identityMap = new IdentityMap();
    }

    /**
     * @inheritdoc
     */
    public function isType($resourceType)
    {
        /** @var AdapterInterface $adapter */
        foreach ($this->adapters as $adapter) {
            if ($adapter->recognises($resourceType)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function exists(ResourceIdentifierInterface $identifier)
    {
        $check = $this->identityMap->exists($identifier);

        if (is_bool($check)) {
            return $check;
        }

        $exists = $this
            ->adapterFor($identifier->getType())
            ->exists($identifier);

        $this->identityMap->add($identifier, $exists);

        return $exists;
    }

    /**
     * @inheritdoc
     */
    public function find(ResourceIdentifierInterface $identifier)
    {
        $record = $this->identityMap->find($identifier);

        if (is_object($record)) {
            return $record;
        } elseif (false === $record) {
            return null;
        }

        $record = $this
            ->adapterFor($identifier->getType())
            ->find($identifier);

        $this->identityMap->add($identifier, is_object($record) ? $record : false);

        return $record;
    }

    /**
     * @inheritdoc
     */
    public function findRecord(ResourceIdentifierInterface $identifier)
    {
        $record = $this->find($identifier);

        if (!$record) {
            throw new RecordNotFoundException($identifier);
        }

        return $record;
    }

    /**
     * @inheritdoc
     */
    public function register(AdapterInterface $adapter)
    {
        $this->adapters[] = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function registerMany(array $adapters)
    {
        foreach ($adapters as $adapter) {
            if (!$adapter instanceof AdapterInterface) {
                throw new InvalidArgumentException('Expecting an array of adapter instances.');
            }

            $this->register($adapter);
        }
    }

    /**
     * @param $resourceType
     * @return AdapterInterface
     */
    protected function adapterFor($resourceType)
    {
        /** @var AdapterInterface $adapter */
        foreach ($this->adapters as $adapter) {
            if ($adapter->recognises($resourceType)) {
                return $adapter;
            }
        }

        throw new RuntimeException("No adapter for resource type: $resourceType");
    }
}
