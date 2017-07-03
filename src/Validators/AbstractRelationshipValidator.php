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

namespace Superman2014\JsonApi\Validators;

use Superman2014\JsonApi\Contracts\Object\RelationshipInterface;
use Superman2014\JsonApi\Contracts\Object\ResourceIdentifierCollectionInterface;
use Superman2014\JsonApi\Contracts\Object\ResourceIdentifierInterface;
use Superman2014\JsonApi\Contracts\Object\ResourceInterface;
use Superman2014\JsonApi\Contracts\Store\StoreInterface;
use Superman2014\JsonApi\Contracts\Validators\AcceptRelatedResourceInterface;
use Superman2014\JsonApi\Contracts\Validators\RelationshipValidatorInterface;
use Superman2014\JsonApi\Contracts\Validators\ValidatorErrorFactoryInterface;
use Superman2014\JsonApi\Utils\ErrorsAwareTrait;
use Superman2014\JsonApi\Utils\Pointer as P;

/**
 * Class AbstractRelationshipValidator
 * @package Superman2014\JsonApi
 */
abstract class AbstractRelationshipValidator implements RelationshipValidatorInterface
{

    use ErrorsAwareTrait;

    /**
     * @var ValidatorErrorFactoryInterface
     */
    protected $errorFactory;

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * @var string[]|null
     *      if null, any types are supported.
     */
    private $expectedTypes;

    /**
     * @var bool
     */
    private $allowEmpty;

    /**
     * @var AcceptRelatedResourceInterface|null
     */
    private $acceptable;

    /**
     * HasOneValidator constructor.
     * @param ValidatorErrorFactoryInterface $errorFactory
     * @param StoreInterface $store
     * @param string|string[]|null $expectedType
     * @param bool $allowEmpty
     * @param AcceptRelatedResourceInterface|null $acceptable
     */
    public function __construct(
        ValidatorErrorFactoryInterface $errorFactory,
        StoreInterface $store,
        $expectedType,
        $allowEmpty = false,
        AcceptRelatedResourceInterface $acceptable = null
    ) {
        $this->errorFactory = $errorFactory;
        $this->store = $store;
        $this->expectedTypes = !is_null($expectedType) ? (array) $expectedType : null;
        $this->allowEmpty = $allowEmpty;
        $this->acceptable = $acceptable;
    }

    /**
     * @return bool
     */
    protected function isEmptyAllowed()
    {
        return (bool) $this->allowEmpty;
    }

    /**
     * @param ResourceIdentifierInterface $identifier
     * @return bool
     */
    protected function doesExist(ResourceIdentifierInterface $identifier)
    {
        return $this->store->exists($identifier);
    }

    /**
     * @param $type
     * @return bool
     */
    protected function isKnownType($type)
    {
        return $this->store->isType($type);
    }

    /**
     * @param $type
     * @return bool
     */
    protected function isSupportedType($type)
    {
        if (!is_array($this->expectedTypes)) {
            return true;
        }

        return in_array($type, $this->expectedTypes, true);
    }

    /**
     * Validate that a data member exists and it is either a has-one or a has-many relationship.
     *
     * @param RelationshipInterface $relationship
     * @param string|null $key
     * @return bool
     */
    protected function validateRelationship(RelationshipInterface $relationship, $key = null)
    {
        if (!$relationship->has(RelationshipInterface::DATA)) {
            $this->addError($this->errorFactory->memberRequired(
                RelationshipInterface::DATA,
                $key ? P::relationship($key) : P::data()
            ));
            return false;
        }

        if (!$relationship->isHasOne() && !$relationship->isHasMany()) {
            $this->addError($this->errorFactory->memberRelationshipExpected(
                RelationshipInterface::DATA,
                $key ? P::relationship($key) : P::data()
            ));
            return false;
        }

        if (!$this->validateEmpty($relationship, $key)) {
            return false;
        }

        return true;
    }

    /**
     * Is this a valid has-one relationship?
     *
     * @param RelationshipInterface $relationship
     * @param null $record
     * @param null $key
     * @param ResourceInterface|null $resource
     * @return bool
     */
    protected function validateHasOne(
        RelationshipInterface $relationship,
        $record = null,
        $key = null,
        ResourceInterface $resource = null
    ) {
        if (!$relationship->isHasOne()) {
            $this->addError($this->errorFactory->relationshipHasOneExpected($key));
            return false;
        }

        $identifier = $relationship->getData();

        if (!$identifier) {
            return true;
        }

        /** Validate the identifier */
        if (!$this->validateIdentifier($identifier, $key)) {
            return false;
        }

        /** If an identifier has been provided, the resource it references must exist. */
        if (!$this->validateExists($identifier, $key)) {
            return false;
        }

        /** If an identifier has been provided, is it acceptable for the relationship? */
        if (!$this->validateAcceptable($identifier, $record, $key, $resource)) {
            return false;
        }

        return true;
    }

    /**
     * Is this a valid has-many relationship?
     *
     * @param RelationshipInterface $relationship
     * @param null $record
     * @param null $key
     * @param ResourceInterface|null $resource
     * @return bool
     */
    protected function validateHasMany(
        RelationshipInterface $relationship,
        $record = null,
        $key = null,
        ResourceInterface $resource = null
    ) {
        if (!$relationship->isHasMany()) {
            $this->addError($this->errorFactory->relationshipHasManyExpected($key));
            return false;
        }

        $identifiers = $relationship->getIdentifiers();

        if (!$this->validateIdentifiers($identifiers, $record, $key, $resource)) {
            return false;
        }

        return true;
    }

    /**
     * @param ResourceIdentifierInterface $identifier
     * @param string|null $key
     * @return bool
     */
    protected function validateIdentifier(ResourceIdentifierInterface $identifier, $key = null)
    {
        $valid = true;
        $type = $identifier->hasType() ? $identifier->getType() : null;

        /** Must have a type */
        if (!$type) {
            $this->addError($this->errorFactory->memberRequired(
                ResourceIdentifierInterface::TYPE,
                $key ? P::relationshipData($key) : P::data()
            ));
            $valid = false;
        } /** Check the submitted resource type is a known resource type */
        elseif (!$this->isKnownType($type)) {
            $this->addError($this->errorFactory->relationshipUnknownType($type, $key));
            $valid = false;
        } /** Check type is valid for this relationship */
        elseif (!$this->isSupportedType($type)) {
            $this->addError($this->errorFactory->relationshipUnsupportedType($this->expectedTypes, $type, $key));
            $valid = false;
        }

        /** Must have an id */
        if (!$identifier->hasId()) {
            $this->addError($this->errorFactory->memberRequired(
                ResourceIdentifierInterface::ID,
                $key ? P::relationshipId($key) : P::data()
            ));
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param ResourceIdentifierCollectionInterface $identifiers
     * @param object|null $record
     * @param string|null $key
     * @param ResourceInterface $resource
     * @return bool
     */
    protected function validateIdentifiers(
        ResourceIdentifierCollectionInterface $identifiers,
        $record = null,
        $key = null,
        ResourceInterface $resource = null
    ) {
        /** @var ResourceIdentifierInterface $identifier */
        foreach ($identifiers as $identifier) {

            if (!$this->validateIdentifier($identifier, $key) || !$this->validateExists($identifier, $key)) {
                return false;
            }
        }

        /** @var ResourceIdentifierInterface $identifier */
        foreach ($identifiers as $identifier) {

            if (!$this->validateAcceptable($identifier, $record, $key, $resource)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ResourceIdentifierInterface $identifier
     * @param string|null
     * @return bool
     */
    protected function validateExists(ResourceIdentifierInterface $identifier, $key = null)
    {
        if (!$this->doesExist($identifier)) {
            $this->addError($this->errorFactory->relationshipDoesNotExist($identifier, $key));
            return false;
        }

        return true;
    }

    /**
     * @param ResourceIdentifierInterface $identifier
     * @param object|null
     * @param string|null $key
     * @param ResourceInterface|null $resource
     * @return bool
     */
    protected function validateAcceptable(
        ResourceIdentifierInterface $identifier,
        $record = null,
        $key = null,
        ResourceInterface $resource = null
    ) {
        $result = ($this->acceptable) ? $this->acceptable->accept($identifier, $record, $key, $resource) : true;

        if (true !== $result) {
            $this->addErrors($this->errorFactory->relationshipNotAcceptable(
                $identifier,
                $key,
                !is_bool($result) ? $result : null
            ));
            return false;
        }

        return true;
    }

    /**
     * @param RelationshipInterface $relationship
     * @param string|null $key
     * @return bool
     */
    private function validateEmpty(RelationshipInterface $relationship, $key = null)
    {
        if ($relationship->isHasOne()) {
            $empty = !$relationship->hasIdentifier();
        } else {
            $empty = $relationship->getIdentifiers()->isEmpty();
        }

        if ($empty && !$this->isEmptyAllowed()) {
            $this->addError($this->errorFactory->relationshipEmptyNotAllowed($key));
            return false;
        }

        return true;
    }

}
