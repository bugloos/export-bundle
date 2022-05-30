<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle\Response;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class XlsxResponse extends GeneralResponse
{
    public function __construct(
        $content,
        $fileName = 'output.xlsx',
        $contentType = 'text/x-csv; charset=windows-1251',
        $contentDisposition = 'attachment',
        $status = self::HTTP_OK,
        $headers = []
    ) {
        parent::__construct(
            $content,
            $fileName,
            $contentType,
            $contentDisposition,
            $status,
            $headers
        );
    }
}
