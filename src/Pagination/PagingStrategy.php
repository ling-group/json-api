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

namespace Superman2014\JsonApi\Pagination;

use Superman2014\JsonApi\Contracts\Pagination\PagingStrategyInterface;
use Superman2014\JsonApi\Contracts\Utils\ConfigurableInterface;

/**
 * Class PagingStrategy
 * @package Superman2014\JsonApi
 */
final class PagingStrategy implements PagingStrategyInterface, ConfigurableInterface
{

    const OPTION_PAGE = 'page';
    const OPTION_PER_PAGE = 'per-page';

    /**
     * @var string|null
     */
    private $page;

    /**
     * @var string|null
     */
    private $perPage;

    /**
     * PagingStrategy constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    /**
     * @param array $input
     * @return $this
     */
    public function configure(array $input)
    {
        if (isset($input[self::OPTION_PAGE])) {
            $this->page = (string) $input[self::OPTION_PAGE];
        }

        if (isset($input[self::OPTION_PER_PAGE])) {
            $this->perPage = (string) $input[self::OPTION_PER_PAGE];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return !empty($this->page) ? $this->page : 'number';
    }

    /**
     * @return string
     */
    public function getPerPage()
    {
        return !empty($this->perPage) ? $this->perPage : 'size';
    }
}
