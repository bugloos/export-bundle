<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle;

use Bugloos\ExportBundle\DependencyInjection\ExportExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class ExportBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new ExportExtension();
        }

        return $this->extension;
    }
}
