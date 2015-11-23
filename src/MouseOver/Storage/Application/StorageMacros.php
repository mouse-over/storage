<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Application;

use Nette\Utils\Arrays;
use Nette\Utils\Strings;


/**
 * Class StorageMacros
 * @package MouseOver\Storage\Application
 */
class StorageMacros extends \Nette\Latte\Macros\MacroSet
{

    /** @var  StorageLinkResolver */
    private static $linkResolver;

    public static function install(\Nette\Latte\Compiler $compiler, StorageLinkResolver $linkResolver = null)
    {
        self::$linkResolver = $linkResolver;
        $me = new static($compiler);
        $me->addMacro('slink', array($me, 'macroStorageLink'));
    }

    /**
     * n:slink="..."
     */
    public function macroStorageLink(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer)
    {
        return $writer->write(
            'echo rtrim(\MouseOver\Storage\Application\StorageMacros::link(%node.word, %node.array, $_presenter));'
        );
    }

    public static function link($storageName, $options, $presenter)
    {
        if (!self::$linkResolver) {
            // HACK
            self::$linkResolver = $presenter->getContext()->getByType('\MouseOver\Storage\Application\StorageLinkResolver');
        }
        $file = isset($options['file']) ? $options['file'] : (isset($options[0]) ? $options[0] : null);
        return self::$linkResolver->link($storageName, $file, $options);
    }

}