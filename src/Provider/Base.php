<?php
/**
 * finc Base Configuration Provider
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
namespace finc\Config\Provider;

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\EventManager\FilterChain;

/**
 * finc Configuration Base Provider
 *
 * @category finc
 * @package  finc/config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://finc.info
 */
class Base extends FilterChain
{
    /**
     * Initial arguments passed to the filter chain
     *
     * @var array
     */
    protected $seed;

    /**
     * Base constructor.
     *
     * @param array $seed {@see Base::$seed}
     */
    public function __construct(array $seed)
    {
        parent::__construct();
        $this->seed = $seed;
    }

    /**
     * Executes the filter chain
     *
     * @return array
     */
    public function __invoke(): array
    {
        $metaConfiguration = [ConfigAggregator::ENABLE_CACHE => true];
        $configuration = $this->run($this, $this->seed) ?: [];
        return array_replace($metaConfiguration, $configuration);
    }
}
