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

namespace Superman2014\JsonApi\Http\Requests;

use Superman2014\JsonApi\Contracts\Authorizer\AuthorizerInterface;
use Superman2014\JsonApi\Contracts\Http\Requests\RequestInterface;
use Superman2014\JsonApi\Contracts\Http\Requests\RequestInterpreterInterface;
use Superman2014\JsonApi\Exceptions\AuthorizationException;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

/**
 * Class AuthorizesRequests
 * @package Superman2014\JsonApi
 */
trait AuthorizesRequests
{

    /**
     * Authorize the request or throw an exception
     *
     * @param RequestInterpreterInterface $interpreter
     * @param AuthorizerInterface $authorizer
     * @param RequestInterface $request
     * @throws AuthorizationException
     */
    protected function authorize(
        RequestInterpreterInterface $interpreter,
        AuthorizerInterface $authorizer,
        RequestInterface $request
    ) {
        $result = $this->checkAuthorization($interpreter, $authorizer, $request);

        if (true !== $result) {
            throw new AuthorizationException($result);
        }
    }

    /**
     * @param RequestInterpreterInterface $interpreter
     * @param AuthorizerInterface $authorizer
     * @param RequestInterface $request
     * @return ErrorCollection|bool
     *      errors if the request is not authorized, true if authorized.
     */
    protected function checkAuthorization(
        RequestInterpreterInterface $interpreter,
        AuthorizerInterface $authorizer,
        RequestInterface $request
    ) {
        $parameters = $request->getParameters();
        $document = $request->getDocument();
        $record = $request->getRecord();
        $authorized = true;

        /** Index */
        if ($interpreter->isIndex()) {
            $authorized = $authorizer->canReadMany($parameters);
        } /** Create Resource */
        elseif ($interpreter->isCreateResource()) {
            $authorized = $authorizer->canCreate($document->getResource(), $parameters);
        } /** Read Resource */
        elseif ($interpreter->isReadResource()) {
            $authorized = $authorizer->canRead($record, $parameters);
        } /** Update Resource */
        elseif ($interpreter->isUpdateResource()) {
            $authorized = $authorizer->canUpdate($record, $document->getResource(), $parameters);
        } /** Delete Resource */
        elseif ($interpreter->isDeleteResource()) {
            $authorized = $authorizer->canDelete($record, $parameters);
        } /** Read Related Resource */
        elseif ($interpreter->isReadRelatedResource()) {
            $authorized = $authorizer->canReadRelatedResource(
                $interpreter->getRelationshipName(),
                $record,
                $parameters
            );
        } /** Read Relationship Data */
        elseif ($interpreter->isReadRelationship()) {
            $authorized = $authorizer->canReadRelationship(
                $interpreter->getRelationshipName(),
                $record,
                $parameters
            );
        } /** Modify Relationship Data */
        elseif ($interpreter->isModifyRelationship()) {
            $authorized = $authorizer->canModifyRelationship(
                $interpreter->getRelationshipName(),
                $record,
                $document->getRelationship(),
                $parameters
            );
        }

        return $authorized ?: $authorizer->getErrors();
    }
}
