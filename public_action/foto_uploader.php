<?php
/**
 *
 */
class foto_uploader {
	/**
	 * Subir fotos a la carpeta especificada y modificar el tamaño
	 * solo para fotos NxN
	 */
	function upload($nombre, $direccion, $foto, $tamaño) {
		$name = $nombre;
		$tempFile = $foto;
		$targetPath = $direccion;
		$targetFile = $targetPath . $name .'.jpg';
		if (!is_dir($targetPath)) {
			mkdir($targetPath);
			chmod($targetPath,0777);
		}
		
		$img = imagecreatefromjpeg($tempFile);
		$img_width = imagesx($img);
		$img_height = imagesy($img);
		$tmp_width = $tamaño;
		$tmp_height = $tamaño;
		$tmp_img = imagecreatetruecolor($tmp_width, $tmp_height);
		imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $img_width, $img_height);
		return imagejpeg($tmp_img, $targetFile);
	}

}
?>