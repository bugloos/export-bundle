<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class GeneralResponse extends Response
{
    public function __construct(
        $content,
        $fileName,
        $contentType,
        $contentDisposition = 'attachment',
        $status = self::HTTP_OK,
        $headers = []
    ) {
        $contentDispositionDirectives = [
            ResponseHeaderBag::DISPOSITION_INLINE,
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        ];

        if (!\in_array($contentDisposition, $contentDispositionDirectives)) {
            throw new \InvalidArgumentException(
                sprintf('Expected one of the following directives: "%s", but "%s" given.',
                    implode('", "', $contentDispositionDirectives), $contentDisposition)
            );
        }

        parent::__construct($content, $status, $headers);

        $this->headers->add(['Content-Type' => $contentType]);

        $this->headers->add([
            'Content-Disposition' => $this->headers->makeDisposition($contentDisposition, $fileName),
        ]);
    }
}
