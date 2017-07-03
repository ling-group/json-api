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

namespace Superman2014\JsonApi\Contracts\Http\Requests;

/**
 * Interface RequestInterpreterInterface
 * @package Superman2014\JsonApi
 */
interface RequestInterpreterInterface
{

    /**
     * What resource type is in the URI?
     *
     * @return string
     */
    public function getResourceType();

    /**
     * What resource id was sent by the client?
     *
     * @return string|null
     *      the resource id or null if none for the current request
     */
    public function getResourceId();

    /**
     * What relationship name was sent by the client?
     *
     * @return string|null
     *      the relationship name or null if none for the current request
     */
    public function getRelationshipName();

    /**
     * Is this an index request?
     *
     * E.g. `GET /posts`
     *
     * @return bool
     */
    public function isIndex();

    /**
     * Is this a create resource request?
     *
     * E.g. `POST /posts`
     *
     * @return bool
     */
    public function isCreateResource();

    /**
     * Is this a read resource request?
     *
     * E.g. `GET /posts/1`
     *
     * @return bool
     */
    public function isReadResource();

    /**
     * Is this an update resource request?
     *
     * E.g. `PATCH /posts/1`
     *
     * @return bool
     */
    public function isUpdateResource();

    /**
     * Is this a delete resource request?
     *
     * E.g. `DELETE /posts/1`
     *
     * @return bool
     */
    public function isDeleteResource();

    /**
     * Is this a request for a related resource or resources?
     *
     * E.g. `GET /posts/1/author` or `GET /posts/1/comments`
     *
     * @return bool
     */
    public function isReadRelatedResource();

    /**
     * Is this a request to read the data of a relationship?
     *
     * E.g. `GET /posts/1/relationships/author` or `GET /posts/1/relationships/comments`
     *
     * @return bool
     */
    public function isReadRelationship();

    /**
     * Is this a request to modify the data of a relationship?
     *
     * I.e. is this a replace relationship, add to relationship or remove from relationship
     * request.
     *
     * @return bool
     */
    public function isModifyRelationship();

    /**
     * Is this a request to replace the data of a relationship?
     *
     * E.g. `PATCH /posts/1/relationships/author` or `PATCH /posts/1/relationships/comments`
     */
    public function isReplaceRelationship();

    /**
     * Is this a request to add to the data of a has-many relationship?
     *
     * E.g. `POST /posts/1/relationships/comments`
     *
     * @return bool
     */
    public function isAddToRelationship();

    /**
     * Is this a request to remove from the data of a has-many relationship?
     *
     * E.g. `DELETE /posts/1/relationships/comments`
     *
     * @return bool
     */
    public function isRemoveFromRelationship();

    /**
     * Is this a request for relationship data?
     *
     * E.g. `/posts/1/relationships/author` or `/posts/1/relationships/comments`
     *
     * I.e. the URL request contains the pattern `/relationships/` after the
     * resource id and before the relationship name.
     *
     * @return bool
     * @see http://jsonapi.org/format/#fetching-relationships
     */
    public function isRelationshipData();

    /**
     * Is this a request where we expect a document to be sent by the client?
     *
     * We expect a document from the client if this is a:
     * - create resource request
     * - update resource request
     * - replace relationship request
     * - add to relationship request
     * - remove from relationship request
     *
     * @return bool
     */
    public function isExpectingDocument();
}
