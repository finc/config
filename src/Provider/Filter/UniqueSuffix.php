<?php
/**
 * VuFind Configuration Provider UniqueSuffix Filter
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
 * @package  vufind-org/vufind-config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
namespace VuFind\Config\Provider\Filter;

use Zend\EventManager\Filter\FilterIterator as Chain;

/**
 * VuFind Configuration Provider UniqueSuffix Filter
 *
 * @category VuFind
 * @package  vufind-org/vufind-config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
class UniqueSuffix
{
    /**
     * Invokes this filter.
     *
     * @param mixed $context Reference to filter context.
     * @param array $items   List of items to be filtered.
     * @param Chain $chain   The remaining filter chain.
     *
     * @return array
     */
    public function __invoke($context, array $items, Chain $chain)
    {
        $suffixes = array_map([$this, 'stripBase'], $items);
        $result = array_values(array_combine($suffixes, $items));
        return $chain->isEmpty() ? $result
            : $chain->next($context, $result, $chain);
    }

    /**
     * Gets the items path having its base being stripped off.
     *
     * @param array $item The processed items.
     *
     * @return mixed
     */
    protected function stripBase(array $item)
    {
        $baseLen = strlen($item['base']) + 1;
        return substr_replace($item['path'], '', 0, $baseLen);
    }
}
