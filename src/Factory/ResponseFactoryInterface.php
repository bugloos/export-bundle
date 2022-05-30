<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle\Factory;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
interface ResponseFactoryInterface
{
    public function makeResponse(string $exportFormat, $output, string $fileName): Response;
}
