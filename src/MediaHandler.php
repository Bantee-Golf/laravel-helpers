<?php

namespace EMedia\Helpers;

use Exception;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class MediaHandler
{

	public static function uploadFile($fieldName = false, $fileDirPath = false, $fileDir = false)
	{
		if (!$fieldName && !$fileDirPath && !$fileDir) return false;

		// handle images
		if (Input::hasFile($fieldName))
		{
			$file = Input::file($fieldName);

			// \Debug::dump($file->getClientOriginalName()); exit;
			$newFileName = self::getUniqueFileName($fileDirPath, $file->getClientOriginalName());
			$file->move($fileDirPath, $newFileName);

			return $fileDir . $newFileName;
		}
		return false;
	}

	public static function getUniqueFileName($dirPath, $currentFilePath)
	{
		$pathInfo = pathinfo($currentFilePath);
		$newFileName = false;

		for ($i=0; $i < 500; $i++)
		{
			$newFileName = date('Ymd') . str_random(15) . '.' . $pathInfo['extension'];
			if ( ! file_exists($dirPath . $newFileName))
			{
				break;
			}
			if ($i > 499)
			{
				// TODO: throw exception
				return false;
			}
		}
		return $newFileName;
	}

	public static function downloadFile($fileUrl = false, $imageDirPath = false)
	{
		set_time_limit(240);
		if (!$fileUrl || !$imageDirPath)
			throw new Exception('A URL and an imageDirPath is required.');

		$newFileName = false;

		$pathInfo = pathinfo($fileUrl);
		$newFileName = self::getUniqueFileName($imageDirPath, $fileUrl);

		// do we have a new file name?
		if (!$newFileName) return false;

		$filePath = $imageDirPath . $newFileName;
		// replace any funny characters in URL
		$imageUrl = str_replace($pathInfo['filename'], rawurlencode($pathInfo['filename']), $fileUrl);
		//var_dump($imageUrl); exit;

		$proxyEnabled   = Config::get('settings.proxyEnabled');
		if ($proxyEnabled)
		{
			$proxiesRepo = App::make('\Admin\ProxyRepository');
			$proxy = $proxiesRepo->getNextProxy();

			// if we have a proxy, send the request through it
			if ($proxy)
			{
				$context = [
					'http' => [
						'proxy'             => $proxy->proxy . ':' . $proxy->port,
						'request_fulluri'   => true
					]
				];
				$context = stream_context_create($context);
			}
		}

		try
		{
			if (empty($context))
			{
				$imageContents 	= file_get_contents($imageUrl);
			}
			else
			{
				$imageContents = file_get_contents($imageUrl, false, $context);
			}
		}
		catch (Exception $ex)
		{
			if ( ! empty($http_response_header) &&
				strpos($http_response_header[0], '200 OK') === false)
			{
				throw new Exception();
			}
			else
			{
				throw new Exception('Unable to download the file');
			}
		}

		$imageResult 	= file_put_contents($filePath, $imageContents);
		// echo $newFileName;
		// echo '<br />';

		// return the generated file name or FALSE
		if ($imageResult)
		{
			return $newFileName;
		}
		else
		{
			return false;
		}
	}


	public static function resizeImageToMaxWidth($imagePath, $maxWidth = 1200, $newImagePath = false)
	{
		$image = Image::make($imagePath);
		if ( ! $newImagePath) $newImagePath = $imagePath;

		$currentWidth = $image->width();
		if ($currentWidth > $maxWidth)
		{
			$image->resize($maxWidth, null, function($constraint) {
				$constraint->aspectRatio();
			});
			$image->save($newImagePath);
		}

		return true;
	}

	public static function getMediaType($fullPath)
	{
		$mimeType = MimeTypeGuesser::getInstance()->guess($fullPath);

		if (str_contains($mimeType, ['image'])) return 'image';

		return false;
	}

	public static function getThumbnail($fullPath, $relativeDir, $absoluteDir, $thumbnailWidth = 200)
	{
		$pathInfo = pathinfo($fullPath);

		$destinationAbsoluteDir = $absoluteDir . 'thumbs/';

		if (!file_exists($destinationAbsoluteDir))
		{
			mkdir($destinationAbsoluteDir, 755);
		}

		$thumbFilePath  = $destinationAbsoluteDir . $pathInfo['basename'];
		$thumbFileUrl  = $relativeDir . 'thumbs/' . $pathInfo['basename'];
		self::resizeImageToMaxWidth($fullPath, $thumbnailWidth, $thumbFilePath);

		// file should be saved now
		if (file_exists($thumbFilePath))
		{
			return $thumbFileUrl;
		}
		return false;
	}

}