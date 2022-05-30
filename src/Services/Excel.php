<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle\Services;

use Bugloos\ExportBundle\Contracts\ExporterInterface;
use Bugloos\ExportBundle\Factory\ResponseFactory;
use Bugloos\ExportBundle\Writer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class Excel implements ExporterInterface
{
    public const XLSX = 'Xlsx';

    public const CSV = 'Csv';

    public function download(
        $exportClass,
        string $fileName,
        string $writerType = null,
        array $headers = []
    ): Response {

        $output = (new Writer())->export($exportClass, $writerType);

        $fileName = $fileName.'.'.$this->makeFileNameExtension($writerType);

        return (new ResponseFactory())->makeResponse($writerType, $output, $fileName);
    }

    public function makeFileNameExtension($writerType): string
    {
        return lcfirst($writerType);
    }
}
