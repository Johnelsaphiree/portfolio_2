<?php
function resize($newWidth, $targetFile, $originalFile, $xheight = '') {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

if($xheight == '')
{
    $newHeight = ($height / $width) * $newWidth;
}
else
{
	 $newHeight = $xheight;
}
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");
}

class upload
{
	var $error_code;
	var $success_code;
	var $error_msg;
	var $success_msg;	
	var $img_name_array;
}

class image_validate extends upload{
function valid($pix, $pix_type)
{
	$exp_pix = explode(',', $pix);
	$exp_type = explode(',', $pix_type);
	
	if (sizeof($exp_pix) > 0)
		{
	$sterror = array();
			for($x = 0; $x < sizeof($exp_pix); $x++)
			{
			if ($exp_type[$x] != 'image/jpeg')
			{
				$sterror[] = $exp_pix[$x];
			}
			}
			//error check
			if (sizeof($sterror) > 0)
			{
				$sterror_message = '';
				foreach($sterror as $sterror_item)
				{
				$sterror_message = $sterror_message.$sterror_item.', ';
				}
				$this->error_code = 7;
			$this->error_msg = $sterror_message.error($this->error_code);
			}
		}
}
}

class file_validate extends upload
{
	function valid($file_type, $file_size, $max, $ftype)
{
	switch($ftype)
	{
		case 'text';
		$xtype = 'text/plain';
		break;
		case 'text_num';
		$xtype = 'text/plain';
		break;
	}
	
			if ($file_type != $xtype)
			{
				$this->error_code = 27;
				$this->error_msg = error($this->error_code);
			}
			elseif($file_size > $max)
			{
				if($ftype != 'text_num')
				{
				$this->error_code = 28;
				$this->error_msg = error($this->error_code);
				}
			}
}

}

class now_upload extends upload
{	
	function up($file, $table, $xpath, $uptype, $mark_text, $function, $function_filename)
	{
		$ext = '.jpg';
		
	$exp_file = explode(',', $file);	
		if (sizeof($exp_file) > 0)
		{
				//insert file name into unique file table and setting file name to max value
				$file_num = array();
				foreach($exp_file as $file_item)
				{
					if($uptype != 'logo' && $uptype != 'favicon' && $uptype != 'slider')
					{
					switch($function)
					{
						case 'add':
					$fileno_query = mysql_query("insert into $table (img_no) values (0)");
					$fileno_query2 = mysql_query("select max(img_no) from $table");
					$file_row = mysql_fetch_row($fileno_query2);
					$file_name = $file_row[0];
					break;
					case 'edit':
					$file_name = $function_filename;
					break;
					}
					}//uptype
					elseif($uptype == 'logo')
					{
						$file_name = 'logo';
					}
					elseif($uptype == 'favicon')
					{
						$file_name = 'favicon';
					}
					elseif($uptype == 'slider')
					{
						$file_name = $function_filename;
					}
					
				move_uploaded_file($file_item, $xpath.$file_name.$ext);
			if($uptype == 'logo')
			{
				resize(150, $xpath.$file_name, $xpath.$file_name.$ext, 50);
			}
			elseif($uptype == 'favicon')
			{
				resize(32, $xpath.$file_name, $xpath.$file_name.$ext, 32);
			}
			elseif($uptype == 'slider')
			{
				resize(950, $xpath.$file_name, $xpath.$file_name.$ext, 350);
			}
			elseif($uptype == 'bank')
			{
				resize(50, $xpath.$file_name, $xpath.$file_name.$ext, 50);
			}
				
				$file_num[] = $file_name;
				}
				$this->img_name_array = $file_num;
		}
	}
}

class wipe{
function file_wipe($file, $path, $ext)
{
@unlink($path.$file.$ext);	
}
}
?>