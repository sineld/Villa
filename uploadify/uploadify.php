<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

if (!empty($_FILES)) {
	$name = $_POST['name'];	
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/'.$name.'/';
	$targetPath = str_replace('//','/',$targetPath);
	$cuenta = count(glob($targetPath . "*.jpg"));
	$cuenta += 1;
	$targetFile =  $targetPath . $name.'-'.$cuenta.'.jpg';
	$targetFileThumb =  $targetPath.'/thumbs/' . $name.'-'.$cuenta.'.jpg';
	if (!is_dir($targetPath)) {
		mkdir(str_replace('//','/',$targetPath), 0777);
		mkdir(str_replace('//','/',$targetPath). "/thumbs", 0777);
	}
	//move_uploaded_file($tempFile,$targetFile);
	
	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	
	$img = imagecreatefromjpeg($tempFile);
	$img_width = imagesx($img);
	$img_height = imagesy($img);
	$tmp_width = 400;
	$tmp_height = 400;
	$tmp_img = imagecreatetruecolor($tmp_width, $tmp_height);
	imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $img_width, $img_height);
	imagejpeg($tmp_img, $targetFile);
	
	$img = imagecreatefromjpeg($targetFile);
	$img_width = imagesx($img);
	$img_height = imagesy($img);
	$tmp_width = 100;
	$tmp_height = 100;
	$tmp_img = imagecreatetruecolor($tmp_width, $tmp_height);
	imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $tmp_width, $tmp_height, $img_width, $img_height);
	imagejpeg($tmp_img, $targetFileThumb);

}
?>