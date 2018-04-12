<?php
/**
 * finc Configuration Provider Load Filter
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
 * @category finc
 * @package  finc/config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://finc.info
 */
namespace finc\Config\Provider\Filter;

use finc\Config\Factory;
use Zend\EventManager\Filter\FilterIterator as Chain;

/**
 * finc Configuration Provider Load Filter
 *
 * @category finc
 * @package  finc/config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://finc.info
 */
class Load
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
        $result = array_map([$this, 'load'], $items);
        return $chain->isEmpty() ? $result
            : $chain->next($context, $result, $chain);
    }

    /**
     * Loads an item.
     *
     * @param array $item The item to be loaded.
     *
     * @return array
     */
    protected function load(array $item): array
    {
        $data = Factory::fromFile($item['path']);
        return array_merge($item, compact('data'));
    }
}
