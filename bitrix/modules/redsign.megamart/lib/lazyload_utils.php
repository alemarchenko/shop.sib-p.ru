<?php
namespace Redsign\MegaMart;

class LazyloadUtils {
	public static function getEmptyImage(
		$nWidth = 1600,
		$nHeight = 1200,
		$sTemplatePath = SITE_TEMPLATE_PATH,
		$sImagePath = '/assets/images/empty/',
		$sImageFileFormat = 'png'
	)
	{
		$sDestFile = SITE_TEMPLATE_PATH.$sImagePath.$nWidth.'_'.$nHeight.'.'.$sImageFileFormat;
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$sDestFile)) {
			return $sDestFile;
		} else {
			$sSourceFilePath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.$sImagePath.'1600_1200.'.$sImageFileFormat;
			$sDestFilePath = $_SERVER['DOCUMENT_ROOT'].$sDestFile;

			if ($sSourceFilePath) {
				$arSizes = array(
					'width' => $nWidth,
					'height' => $nHeight
				);

				$arImage = \CFile::ResizeImageFile(
					$sSourceFilePath,
					$sDestFilePath,
					$arSizes,
					BX_RESIZE_IMAGE_EXACT
				);

				if ($arImage) {
					return $sDestFile;
				}
			}
		}

		return null;
	}
}
