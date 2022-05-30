<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle\Factory;

use Bugloos\ExportBundle\Exception\InvalidResponseTypeException;
use Bugloos\ExportBundle\Response\CsvResponse;
use Bugloos\ExportBundle\Response\XlsxResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class ResponseFactory implements ResponseFactoryInterface
{
    public function makeResponse(string $exportFormat, $content, string $fileName): Response
    {
        switch ($exportFormat) {
            case 'Xlsx':
                return new XlsxResponse($content, $fileName);

            case 'Csv':
                return new CsvResponse($content, $fileName);

            default:
                throw new InvalidResponseTypeException();
        }
    }
}
