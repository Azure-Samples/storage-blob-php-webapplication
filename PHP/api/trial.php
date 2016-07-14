<?php
/*
	This code sample is separated into two tiers. 
	The frontend is built using AngularJs. The AngularJs source code scripts are in `src` folder, and be combined br gulp.
	The backend is built using PHP, implementing with Azure SDK for PHP.
	All the operations on Azure Blob Storage in the frontend, are called via `$http` module against the functions in PHP backend.
	////////////////////////////////////////
	////////////////////////////////////////
	All the functions called via frontend are listed in this single script.
 */
use MicrosoftAzure\Storage\Blob\Models\Block;
use MicrosoftAzure\Storage\Blob\Models\CommitBlobBlocksOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\GetBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\ListContainersOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\ServiceException;
require_once 'settings.php';

if (isset($_GET['init'])) {
	// here are 4 functions totally, for the preparatory work of the complete trial cycle.
	switch ($_GET['init']) {
	case 'container':
		//create the container for the code sample's trial later.
		$createContainerOptions = new CreateContainerOptions();
		$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
		$createContainerOptions->addMetadata('category', 'init-trial-container'); // add a custom metadata
		try {
			// Create container.
			$blobClient->createContainer($containerName, $createContainerOptions);
			echo "Create successfully";
		} catch (ServiceException $e) {
			$code = $e->getCode();
			http_response_code($code);
			$error_message = $e->getMessage();
			echo $error_message;
		}
		break;

	case 'text':
		//upload the text blob to the container created above for the code sample's trial later.
		$chunckSize = 2 * 1024;
		$trialCate = "trial-text-blobs";
		$blobName = "trial.md";
		$createBlobOptions = new CreateBlobOptions();
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$createBlobOptions->setMetadata(["category" => $trialCate]);
		$createBlobOptions->setContentType(finfo_file($finfo, "assets/" . $blobName));
		finfo_close($finfo);
		try {
			$content = fopen("assets/trial.md", "rb");
			$counter = 1;
			while (!feof($content)) {
				$data = fread($content, $chunckSize);
				$blobClient->createBlockBlob($containerName, $trialCate . DELIMITER . $blobName . '-part-' . $counter, $data, $createBlobOptions);
				$counter++;
			}
			fclose($content);
			// Create container.
			echo "successfully create " . ($counter - 1) . " blobs";
		} catch (ServiceException $e) {
			$code = $e->getCode();
			http_response_code($code);
			$error_message = $e->getMessage();
			echo $error_message;
		}
		break;

	case 'video':
		//upload the video blob to the container created above for the code sample's trial later.
		try {
			$chunkSize = 4 * 1024 * 1024;
			$trialCate = "trial-video-blobs";
			$blobName = "video.mp4";
			$content = fopen("assets/" . $blobName, "rb");
			$index = 0;
			$continue = True;
			$counter = 1;
			$blockIds = array();
			while (!feof($content)) {
				$blockId = str_pad($counter, 6, "0", STR_PAD_LEFT);
				$block = new Block();
				$block->setBlockId(base64_encode($blockId));
				$block->setType("Uncommitted");
				array_push($blockIds, $block);
				$data = fread($content, $chunkSize);
				$blobClient->createBlobBlock($containerName, $trialCate . DELIMITER . $blobName, base64_encode($blockId), $data);
				$counter++;
			}
			fclose($content);
			$commitBlobBlocksOptions = new CommitBlobBlocksOptions();
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$commitBlobBlocksOptions->setMetadata(["category" => $trialCate]);
			$commitBlobBlocksOptions->setBlobContentType(finfo_file($finfo, "assets/" . $blobName));
			finfo_close($finfo);
			$blobClient->commitBlobBlocks($containerName, $trialCate . DELIMITER . $blobName, $blockIds, $commitBlobBlocksOptions);
			echo "successfully create 1 blob";
		} catch (ServiceException $e) {
			$code = $e->getCode();
			http_response_code($code);
			$error_message = $e->getMessage();
			echo $error_message;
		}
		break;

	case 'check':
		// check if has initialized the test container
		try {
			$listContainersOptions = new ListContainersOptions();
			$listContainersOptions->setPrefix($trialPrefix);
			$listContainersOptions->setIncludeMetadata(true);
			$result = $blobClient->listContainers($listContainersOptions);
			$container = $result->getContainers();
			if (count($container) > 0) {
				$result = $blobClient->listBlobs($container[0]->getName());
				echo count($result->getBlobs()) > 0 ? 'true' : 'false';
			} else {
				echo 'false';
			}
		} catch (ServiceException $e) {
			$code = $e->getCode();
			http_response_code($code);
			$error_message = $e->getMessage();
			echo $error_message;
		}
	default:
		# code...
		break;
	}

}
if (isset($_GET['trial'])) {
	switch ($_GET['trial']) {
	case 'container':
		// 
		try {
			$listContainersOptions = new ListContainersOptions();
			$listContainersOptions->setPrefix($trialPrefix);
			$listContainersOptions->setIncludeMetadata(true);
			$result = $blobClient->listContainers($listContainersOptions);
			$return = [];
			foreach ($containers = $result->getContainers() as $k => $b) {
				// $containers is an instance of MicrosoftAzure\Storage\Blob\Models\Container
				$tmp = [];
				$tmp['name'] = $b->getName();
				$tmp['url'] = $b->getUrl();
				$tmp['metadata'] = $b->getMetadata();
				$properties = $b->getProperties();
				// $properties is an instance of MicrosoftAzure\Storage\Blob\Models\ContainerProperties
				$tmp['properties']['lastModified'] = $properties->getLastModified()->format('Y-m-d H:i:s');
				array_push($return, $tmp);
			}
			echo json_encode($return);
		} catch (ServiceException $e) {
			$code = $e->getCode();
			http_response_code($code);
			$error_message = $e->getMessage();
			echo $error_message;
		}
		break;
	case 'blob':
		// 
		$listBlobsOptions = new ListBlobsOptions();
		if (isset($_GET['blobPrefixes'])) {
			$listBlobsOptions->setPrefix($_GET['blobPrefixes']);
		}
		if (isset($_GET['next'])) {
			$listBlobsOptions->setMarker($_GET['next']);
		}
		$listBlobsOptions->setMaxResults(5);//pagination
		$listBlobsOptions->setIncludeMetadata(true);
		$listBlobsOptions->setDelimiter(DELIMITER);
		$result = $blobClient->listBlobs($_GET['containerName'], $listBlobsOptions);
		$return = [
			'blobs' => [],
			'delimiter' => $result->getDelimiter(),
			'blobPrefixes' => [],
			'prefixes' => $result->getPrefix(),
			'nextMarker' => $result->getNextMarker(),
		];
		foreach ($result->getBlobPrefixes() as $k => $b) {
			//an instance of MicrosoftAzure\Storage\Blob\Models\BlobPrefix
			array_push($return['blobPrefixes'], $b->getName());
		}
		foreach ($result->getBlobs() as $k => $b) {
			//an instance of MicrosoftAzure\Storage\Blob\Models\Blob
			$tmp = [];
			$tmp['name'] = $b->getName();
			$tmp['url'] = $b->getUrl();
			$tmp['metadata'] = $b->getMetadata();
			$properties = $b->getProperties();
			$tmp['properties']['lastModified'] = $properties->getLastModified()->format('Y-m-d H:i:s');
			$tmp['properties']['contentType'] = $properties->getContentType();
			$tmp['properties']['contentLength'] = $properties->getContentLength();
			array_push($return['blobs'], $tmp);
		}
		echo json_encode($return);
		break;
	case 'content':
		// output the content of blob in stream
		$properties = $blobClient->getBlobProperties($containerName, $_GET['blob']);
		$size = $properties->getProperties()->getContentLength();
		$mime = $properties->getProperties()->getContentType();
		$chunk_size = 1024 * 1024;
		$index = 0;
		//$stream = "";
		// header("Pragma: public");
		// header('Content-Disposition: attachment; filename="' . $blobName . '"');
		// header('Expires: 0');
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Transfer-Encoding: binary");
		header("Content-type: $mime");
		header("Content-length: $size");
		ob_clean();
		while ($index < $size) {
			$option = new GetBlobOptions();
			$option->setRangeStart($index);
			$option->setRangeEnd($index + $chunk_size - 1);
			$blob = $blobClient->getBlob($containerName, $_GET['blob'], $option);
			$stream_t = $blob->getContentStream();
			$length = $blob->getProperties()->getContentLength();
			$index += $length;
			flush();
			fpassthru($stream_t);
		}
		break;
	default:
		# code...
		break;
	}
}