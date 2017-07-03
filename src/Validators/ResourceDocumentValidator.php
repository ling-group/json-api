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

use Superman2014\JsonApi\Contracts\Object\DocumentInterface;
use Superman2014\JsonApi\Contracts\Validators\DocumentValidatorInterface;
use Superman2014\JsonApi\Contracts\Validators\ResourceValidatorInterface;
use Superman2014\JsonApi\Contracts\Validators\ValidatorErrorFactoryInterface;
use Superman2014\JsonApi\Utils\ErrorsAwareTrait;
use Superman2014\JsonApi\Utils\Pointer as P;

/**
 * Class ResourceDocumentValidator
 * @package Superman2014\JsonApi
 */
class ResourceDocumentValidator implements DocumentValidatorInterface
{

    use ErrorsAwareTrait;

    /**
     * @var ValidatorErrorFactoryInterface
     */
    private $errorFactory;

    /**
     * @var ResourceValidatorInterface
     */
    private $resourceValidator;

    /**
     * ResourceDocumentValidator constructor.
     * @param ValidatorErrorFactoryInterface $errorFactory
     * @param ResourceValidatorInterface $validator
     */
    public function __construct(
        ValidatorErrorFactoryInterface $errorFactory,
        ResourceValidatorInterface $validator
    ) {
        $this->errorFactory = $errorFactory;
        $this->resourceValidator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function isValid(DocumentInterface $document, $record = null)
    {
        $this->reset();

        if (!$document->has(DocumentInterface::DATA)) {
            $this->addError($this->errorFactory->memberRequired(DocumentInterface::DATA, P::root()));
            return false;
        }

        $data = $document->get(DocumentInterface::DATA);

        if (!is_object($data)) {
            $this->addError($this->errorFactory->memberObjectExpected(DocumentInterface::DATA, P::data()));
            return false;
        }

        if (!$this->resourceValidator->isValid($document->getResource(), $record)) {
            $this->addErrors($this->resourceValidator->getErrors());
            return false;
        }

        return true;
    }
}
