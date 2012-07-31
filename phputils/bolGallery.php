<?php

ini_set("memory_limit","60M");
// BolGalleryCreative - 05/03/2005
// All right reserved to Nicolas d'Haussy
// Use or modify it at your own risk

// Coders : I commented this code as much as possible for you ;)
// Coders : Need help at reducing file URLs expressions according to "current directory" PHP workflow style

// Returns a GD image file resource create and its width and height into an array
// Output array : {image resource, image width, image height}
function getImageResource($imageFile) {

	// Get image info
	$imageFileInfo = getimagesize($imageFile);
	$dataArray[1] = $imageFileInfo[0];
	$dataArray[2] = $imageFileInfo[1];

	// Create a image resource
 	if ($imageFileInfo[2] == 1) { $imageFileResource = imagecreatefromgif($imageFile); }
	if ($imageFileInfo[2] == 2) { $imageFileResource = imagecreatefromjpeg($imageFile); }
 	if ($imageFileInfo[2] == 3) { $imageFileResource = imagecreatefrompng($imageFile); }
	$dataArray[0] = $imageFileResource;
		
	return $dataArray;
}



// Creates a jpeg image sized as you want focusing randomly at a detail of the reference image
function imageDetailExtract($referenceImage, $thumbnail, $thumbnailWidth, $thumbnailHeight, $thumbnailJpegQuality=70) {

	$getImageResource = getImageResource($referenceImage);
	
	// Method to display a image detail
	// Coders : problems with little images (To get rid of it, set those two variables to 0)
	// Coders : could be also enhanced so as to see more details
	$Xposition = round(rand(0, ($getImageResource[1]-$thumbnailWidth)));
	$Yposition = round(rand(0, ($getImageResource[2]-$thumbnailHeight)));

	// Create the detail image
	$thumbnailResource = imagecreatetruecolor($thumbnailWidth,$thumbnailHeight);
	imagecopy($thumbnailResource, $getImageResource[0], 0, 0, $Xposition, $Yposition, $getImageResource[1], $getImageResource[2]);
	imagejpeg($thumbnailResource, $thumbnail, $thumbnailJpegQuality);
	
	// Destroy image resources
	imagedestroy($getImageResource[0]);
	imagedestroy($thumbnailResource);
}



// Resizes the given image outputting a jpeg image
function resizeImage($referenceImage, $thumbnail, $maxWidth, $maxHeight, $thumbnailJpegQuality=70) {
	
	$getImageResource = getImageResource($referenceImage);
	
	// Recompute size for fitting (to be validated)
	if( $getImageResource[1] > $getImageResource[2]) { $maxHeight = round(($getImageResource[2]/$getImageResource[1])*$maxWidth); }
	else { $maxWidth = round(($getImageResource[1]/$getImageResource[2])*$maxHeight); }

	// Create resized image
	$thumbnailResource = imagecreatetruecolor($maxWidth,$maxHeight);

	imagecopyresized($thumbnailResource, $getImageResource[0], 0, 0, 0, 0, $maxWidth, $maxHeight, $getImageResource[1], $getImageResource[2]);
	imagejpeg($thumbnailResource, $thumbnail, $thumbnailJpegQuality);
	
	// Destroy image resources
	imagedestroy($getImageResource[0]);
	imagedestroy($thumbnailResource);
}



// Date sorting method
function mtime_sort($b, $a) { 

	if (filemtime($a) == filemtime($b)) {
		return 0; 
	} else {
		return (filemtime($a) < filemtime($b)) ? -1 : 1; 
	}
} 




// Creates bolGallery files and returns the HTML layout source string
function bolGalleryCreate($imagesList, $referenceImagesDirectory, $referenceLabels, $tableColumnsNb, $thumbnailWidth, $thumbnailHeight, $switchClassic=false, $selectMode=false, $renderer='') {

	if(!file_exists($referenceImagesDirectory.'preview/'))
		mkdir($referenceImagesDirectory.'preview/');
	// Build gallery HTML source
	$HTML = "";

	// Build the HTML table to display all the thumbnails
	$HTML .= "\n<div id=\"imageGallery\">\n";

	$count = 0;
	foreach($imagesList as $currentImage) {
	$count++;
		if(file_exists($currentImage->url))
		{
				// (Re)build thumbnail url string
				$referenceImageName = explode("/", $currentImage->url);
				$referenceImageName = $referenceImageName[count($referenceImageName) - 1];
				$thumbnail = ($referenceImagesDirectory . "preview/thumbnail_" . $referenceImageName); 

				// Get reference image file info
				$referenceImageInfos = getimagesize($currentImage->url);
				$referenceImageWidth = $referenceImageInfos[0];
				$referenceImageHeight = $referenceImageInfos[1];

				// Generate the thumbnail image if doesn't exist
				if(! file_exists($thumbnail)) {
				
					// Generate mode style thumbnail
					if($switchClassic) { resizeImage($currentImage->url, $thumbnail, $thumbnailWidth, $thumbnailHeight); } 
					else { imageDetailExtract($currentImage->url, $thumbnail, $thumbnailWidth, $thumbnailHeight); }
				}

				// Display the thumbnail image and set a popup link to the big one
				$alt ='Photo - '.$referenceImageName;
				foreach($referenceLabels as $label) $alt .= ','.$label;
				$HTML .= "\t\t<div class=\"imageOption\" id=\"".$currentImage->id."\">";
				
				if(!$selectMode) 
				{
					if($renderer=='')
						$HTML .= "<a href=\"" . $currentImage->url . "\" rel=\"facebox\">\n"; // target attribute to be tested
					else
						$HTML .= "<a href=\"" . $renderer . "&url=".$currentImage->url."\" rel=\"facebox\">\n"; // target attribute to be tested
				}
				
				$HTML .= "\t\t\t\t<IMG src=\"" . $thumbnail . "\" title=\"" . $alt . "\" border=0>\n";
				if(!$selectMode) $HTML .= "\t\t\t</A>\n\t";
				
				$HTML .= "\t</div>\n";
		}

	}

	$HTML .= "</div>\n";
	return $HTML;
}


// Main function. Handles bolGalleryCreate(). Call it on your php pages where you want it build a gallery.
// Loads static page or lists reference images directory and launchs gallery creation
function bolGallery($referenceImagesDirectory, $referenceLabels, $tableColumnsNb=4, $thumbnailWidth=100, $thumbnailHeight=100, $switchClassic=false, $selectMode=false,$renderer='') 
{
	$imagesList = array();
	if(file_exists($referenceImagesDirectory))
	{
		if(count($referenceLabels)>0)
		{
			$cont=0;
			foreach($referenceLabels as $currentLabel)
			{
				if($cont>0)
				{
					$tempArray = array();
					$result = mysql_query("SELECT DISTINCT url,id FROM photos,label_photo,labels WHERE photos.id=label_photo.photo_id AND labels.id=label_photo.label_id AND label='".$currentLabel."'");
					
					while($image = mysql_fetch_object($result))
						array_push($tempArray,$image);
						
					$imagesList = array_intersect($imagesList,$tempArray);
				}
				else
				{
					$result = mysql_query("SELECT DISTINCT url,id FROM photos,label_photo,labels WHERE photos.id=label_photo.photo_id AND labels.id=label_photo.label_id AND label='".$currentLabel."'");
					
					while($image = mysql_fetch_object($result))
						array_push($imagesList,$image);
				}
				$cont++;
			}
			
		}
		else
		{

			$referenceLabels = array();
			
			$result = mysql_query("SELECT url,id FROM multimedia WHERE idTipo='image'");
			
			while($image = mysql_fetch_object($result))
				array_push($imagesList,$image);
		}

			// Build gallery
			$HTML = bolGalleryCreate($imagesList, $referenceImagesDirectory, $referenceLabels, $tableColumnsNb, $thumbnailWidth, $thumbnailHeight, $switchClassic, $selectMode, $renderer);

		echo $HTML;
	}
	else
	{
		if($GLOBALS["debugMode"]) echo "<p>El directorio propocionado '$referenceImagesDirectory' no existe.</p>";
		else
			echo "<p>No hay imagenes.</p>";
	
	}
}
?>
