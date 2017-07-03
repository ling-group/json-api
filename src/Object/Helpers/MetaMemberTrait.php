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

namespace Superman2014\JsonApi\Object\Helpers;

use Superman2014\JsonApi\Contracts\Object\StandardObjectInterface;
use Superman2014\JsonApi\Exceptions\RuntimeException;
use Superman2014\JsonApi\Object\StandardObject;
use Neomerx\JsonApi\Contracts\Document\DocumentInterface;

/**
 * Class MetaMemberTrait
 * @package Superman2014\JsonApi
 */
trait MetaMemberTrait
{

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    abstract public function get($key, $default = null);

    /**
     * @param $key
     * @return bool
     */
    abstract public function has($key);

    /**
     * Get the meta member of the document.
     *
     * @return StandardObjectInterface
     * @throws RuntimeException
     *      if the meta member is present and is not an object or null.
     */
    public function getMeta()
    {
        $meta = $this->get(DocumentInterface::KEYWORD_META);

        if ($this->has(DocumentInterface::KEYWORD_META) && !is_object($meta)) {
            throw new RuntimeException('Data member is not an object.');
        }

        return new StandardObject($meta);
    }

    /**
     * @return bool
     */
    public function hasMeta()
    {
        return $this->has(DocumentInterface::KEYWORD_META);
    }

}
