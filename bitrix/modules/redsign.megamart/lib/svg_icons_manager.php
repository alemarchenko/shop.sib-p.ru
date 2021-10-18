<?php

namespace Redsign\MegaMart;

use Bitrix\Main\IO;
use Bitrix\Main\Application;

class SVGIconsManager
{
    protected static $svgIconsPaths = array();
    protected static $icons = array();
    protected static $isChanged = false;
    protected static $xml = '';
    protected static $spriteX = 0;
    protected static $spriteY = 0;

    protected static $svgAttributes = array(
        'version' => 1.1,
        'xmlns' => 'http://www.w3.org/2000/svg',
        'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
    );

    const EXT = '.svg';
    const PREFIX_ID = 'svg-';

    public static function addPath($path)
    {
        if (!in_array($path, self::$svgIconsPaths)) {
            self::$svgIconsPaths[] = $path;
            self::$isChanged = true;
        }
    }

    public static function pushIcon($key)
    {
        if (!self::isExistIcon($key)) {
            self::$icons[] = $key;
            self::$isChanged = true;
        }
    }

    public static function removeIcon($key)
    {
        if (!self::isExistIcon($key)) {
            self::$icons[] = $key;
            self::$isChanged = true;
        }
    }

    public static function isExistIcon($key)
    {
        return in_array($key, self::$icons);
    }

    public static function releaseSVG()
    {
        if (!self::$isChanged) {
            return self::$xml;
        }

        $xml = self::createSVG();
        $xmlTree = $xml->GetTree();


        foreach (self::$icons as $iconKey) {
            $icon = self::getIcon($iconKey);

            if (!$icon) {
                continue;
            }

            $symbol = self::getSymbol($icon, $iconKey);
            $xmlTree->children[] = $symbol;

            $view = self::getSymbolView($symbol);
            $xmlTree->children[] = $view;

            $use = self::getSymbolUse($symbol);
            $xmlTree->children[] = $use;
        }

        self::$xml = $xml->GetString();
        self::$isChanged = false;

        return self::$xml;
    }

    public static function minify($svg) {
        $search = array(
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s',
            '/<!--(.|\s)*?-->/'
        );

        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );

        return preg_replace($search, $replace, $svg);
    }

    protected static function createSVG()
    {
        $xml = new \CDataXML();
        $xml->loadString('<svg>');

        $tree = $xml->GetTree();

        foreach (self::$svgAttributes as $attrName => $attrValue) {
            $attribute = new \CDataXMLNode();
            $attribute->name = $attrName;
            $attribute->content = $attrValue;
            $tree->attributes[] = $attribute;
        }

        return $xml;
    }

    public static function getIcon($key)
    {
        foreach (self::$svgIconsPaths as $iconPath) {
            $file = new IO\File($iconPath.DIRECTORY_SEPARATOR.$key.self::EXT);
            if ($file->isExists()) {
                return $file->getContents();
            }
        }

        return false;
    }

    protected static function getSymbol($svg, $key)
    {
        $iconXML = new \CDataXML();
        $iconXML->loadString($svg);
        $iconXMLTree = $iconXML->GetTree();
        $iconXMLTreeChildren = $iconXMLTree->children[0];

        $idAttribute = new \CDataXMLNode();
        $idAttribute->name = 'id';
        $idAttribute->content = self::PREFIX_ID.$key;

        $viewboxAttribute = new \CDataXMLNode();
        $viewboxAttribute->name = 'viewBox';
        $viewboxAttribute->content = $iconXMLTreeChildren->getAttribute('viewBox');

        $symbol = new \CDataXMLNode();
        $symbol->name = 'symbol';
        $symbol->attributes = array($idAttribute, $viewboxAttribute);
        $symbol->children = $iconXMLTreeChildren->children();

        return $symbol;
    }

    protected static function getSymbolView($symbol)
    {
        $idAttribute = new \CDataXMLNode();
        $idAttribute->name = 'id';
        $idAttribute->content = $symbol->getAttribute('id').'-view';

        $symbolViewBox = explode(' ', $symbol->getAttribute('viewBox'));

        $viewViewbox = implode(
            ' ',
            array(
                $symbolViewBox[0] + self::$spriteX,
                $symbolViewBox[1] + self::$spriteY,
                $symbolViewBox[2],
                $symbolViewBox[3],
            )
        );

        $viewboxAttribute = new \CDataXMLNode();
        $viewboxAttribute->name = 'viewBox';
        $viewboxAttribute->content = $viewViewbox;


        $view = new \CDataXMLNode();
        $view->name = 'view';
        $view->attributes = array($idAttribute, $viewboxAttribute);

        return $view;
    }

    protected static function getSymbolUse($symbol)
    {
        $idAttribute = new \CDataXMLNode();
        $idAttribute->name = 'xlink:href';
        $idAttribute->content = '#'.$symbol->getAttribute('id');

        $xAttribute = new \CDataXMLNode();
        $xAttribute->name = 'x';
        $xAttribute->content = self::$spriteX;

        $yAttribute = new \CDataXMLNode();
        $yAttribute->name = 'y';
        $yAttribute->content = self::$spriteY;

        $symbolViewBox = explode(' ', $symbol->getAttribute('viewBox'));
        $widthAttribute = new \CDataXMLNode();
        $widthAttribute->name = 'width';
        $widthAttribute->content = $symbolViewBox[2];

        $heightAttribute = new \CDataXMLNode();
        $heightAttribute->name = 'height';
        $heightAttribute->content = $symbolViewBox[3];

        self::$spriteX += ceil($symbolViewBox[2]);
        self::$spriteY += ceil($symbolViewBox[3]);

        $use = new \CDataXMLNode();
        $use->name = 'use';
        $use->attributes = array($idAttribute, $xAttribute, $yAttribute, $widthAttribute, $heightAttribute);

        return $use;
    }
}
