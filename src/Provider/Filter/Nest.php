<?php
/**
 * VuFind Configuration Provider Nest Filter
 *
 * Copyright (C) 2018 Leipzig University Library <info@ub.uni-leipzig.de>
 *
 * PHP version 7
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc. 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @category VuFind
 * @package  vufind-org/vufindconfig
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
namespace VuFind\Config\Provider\Filter;

use Zend\EventManager\Filter\FilterIterator as Chain;

/**
 * VuFind Configuration Provider Nest Filter
 *
 * @category VuFind
 * @package  vufind-org/vufindconfig
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
class Nest
{
    /**
     * Invokes this filter.
     *
     * @param mixed $context Reference to filter context.
     * @param array $items   List of items to be processed.
     * @param Chain $chain   The remaining filter chain.
     *
     * @return array
     */
    public function __invoke($context, array $items, Chain $chain): array
    {
        $result = array_map([$this, 'nest'], $items);

        return $chain->isEmpty() ? $result
            : $chain->next($context, $result, $chain);
    }

    /**
     * Nests the items data according to its relative path w.r.t. its base path.
     *
     * @param array $item The item to be processed.
     *
     * @return array
     */
    protected function nest(array $item)
    {
        $baseLen = strlen($item['base']) + 1;
        $path = substr_replace($item['path'], "", 0, $baseLen);
        $offset = strlen(pathinfo($path, PATHINFO_EXTENSION)) + 1;
        $path = trim(substr_replace($path, '', -$offset), '/');
        foreach (array_reverse(explode('/', $path)) as $key) {
            $item['data'] = [$key => $item['data']];
        }

        return $item;
    }
}
