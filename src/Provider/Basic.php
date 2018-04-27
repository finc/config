<?php
/**
 * VuFind Basic Configuration Provider
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
namespace VuFind\Config\Provider;

/**
 * VuFind Configuration Basic Provider
 *
 * @category VuFind
 * @package  vufind-org/vufind-config
 * @author   Sebastian Kehr <kehr@ub.uni-leipzig.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU GPLv2
 * @link     https://vufind.org
 */
class Basic extends Base
{
    /**
     * Basic constructor.
     *
     * @param array $patterns List of glob patterns.
     */
    public function __construct(array $patterns)
    {
        parent::__construct($patterns);
        $this->attach(new Filter\Glob, 4000000);
        $this->attach(new Filter\Load, 3000000);
        $this->attach(new Filter\Nest, 2000000);
        $this->attach(new Filter\Merge, 1000000);
    }
}
