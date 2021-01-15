<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/

class image_resize {
	var $image;
	var $image_type;

	function __construct($filename = null) {
		if (!is_null ($filename)) {
			$this->load ($filename);
		}
	}

	function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($filename);
		}
	}
	function save($filename, $image_type=null, $compression=75, $permissions=null) {
		if (is_null ($image_type)) {
			$image_type = $this->image_type;
		}
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image,$filename,$compression);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image,$filename);			
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image,$filename);
		}	
		if( $permissions != null) {
			chmod($filename,$permissions);
		}
	}
	function output($image_type=null) {
		if (is_null ($image_type)) {
			$image_type = $this->image_type;
		}
		if( $image_type == IMAGETYPE_JPEG ) {
			header ("Content-Type: image/jpeg");
			imagejpeg($this->image);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			header ("Content-Type: image/gif");
			imagegif($this->image);			
		} elseif( $image_type == IMAGETYPE_PNG ) {
			header ("Content-Type: image/png");
			imagepng($this->image);
		}	
	}
	function get_width() {
		return imagesx($this->image);
	}
	function get_height() {
		return imagesy($this->image);
	}
	function resize_to_height($height) {
		$ratio = $height / $this->get_height();
		$width = $this->get_width() * $ratio;
		$this->resize($width,$height);
	}
	function resize_to_width($width) {
		$ratio = $width / $this->get_width();
		$height = $this->get_height() * $ratio;
		$this->resize($width,$height);
	}
	function scale($scale) {
		$width = $this->get_width() * $scale/100;
		$height = $this->get_height() * $scale/100; 
		$this->resize($width,$height);
	}
	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->get_width(), $this->get_height());
		$this->image = $new_image;	
	}		
}
?>
