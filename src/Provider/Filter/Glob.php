<?php
/**
 * VuFind Configuration Provider Glob Filter
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

use Webmozart\Glob\Glob as Globber;
use Zend\EventManager\Filter\FilterIterator as Chain;

/**
 * VuFind Configuration Provider Glob Filter
 *
 * @category VuFind
 * @package  vufind-org/vufind-config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
class Glob
{
    /**
     * Invokes this filter.
     *
     * @param mixed $context  Reference to filter context.
     * @param array $patterns List of glob patterns to be processed.
     * @param Chain $chain    The remaining filter chain.
     *
     * @return array
     */
    public function __invoke($context, array $patterns, Chain $chain)
    {
        $result = array_merge(...array_map([$this, 'load'], $patterns));

        return $chain->isEmpty() ? $result
            : $chain->next($context, $result, $chain);
    }

    /**
     * Loads a single glob pattern.
     *
     * @param string $pattern The glob pattern to be loaded.
     *
     * @return array
     */
    public function load(string $pattern)
    {
        $base = Globber::getBasePath($pattern);

        return array_map(
            function ($path) use ($pattern, $base) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);

                return compact('base', 'ext', 'path', 'pattern');
            }, Globber::glob($pattern)
        );
    }
}
