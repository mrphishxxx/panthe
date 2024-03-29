<?php

/* $Id$ */
if (!class_exists("nc_System")) die("Unable to load file.");

// Read 1,4,8,24,32bit BMP files
// Save 24bit BMP files
// Author: de77
// Licence: MIT
// Webpage: de77.com
// Article about this class: http://de77.com/php/read-and-write-bmp-in-php-imagecreatefrombmp-imagebmp
// First-version: 07.02.2010
// Version: 21.08.2010

class BMP {

    public static function imagebmp(&$img, $filename = false) {
        $wid = imagesx($img);
        $hei = imagesy($img);
        $wid_pad = str_pad('', $wid % 4, "\0");

        $size = 54 + ($wid + $wid_pad) * $hei * 3; //fixed
        //prepare & save header
        $header['identifier'] = 'BM';
        $header['file_size'] = self::dword($size);
        $header['reserved'] = self::dword(0);
        $header['bitmap_data'] = self::dword(54);
        $header['header_size'] = self::dword(40);
        $header['width'] = self::dword($wid);
        $header['height'] = self::dword($hei);
        $header['planes'] = self::word(1);
        $header['bits_per_pixel'] = self::word(24);
        $header['compression'] = self::dword(0);
        $header['data_size'] = self::dword(0);
        $header['h_resolution'] = self::dword(0);
        $header['v_resolution'] = self::dword(0);
        $header['colors'] = self::dword(0);
        $header['important_colors'] = self::dword(0);

        if ($filename) {
            $f = fopen($filename, "wb");
            foreach ($header AS $h) {
                fwrite($f, $h);
            }

            //save pixels
            for ($y = $hei - 1; $y >= 0; $y--) {
                for ($x = 0; $x < $wid; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    fwrite($f, self::byte3($rgb));
                }
                fwrite($f, $wid_pad);
            }
            fclose($f);
        } else {
            foreach ($header AS $h) {
                echo $h;
            }

            //save pixels
            for ($y = $hei - 1; $y >= 0; $y--) {
                for ($x = 0; $x < $wid; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    echo self::byte3($rgb);
                }
                echo $wid_pad;
            }
        }
    }

    public static function imagecreatefrombmp($filename) {
        $f = fopen($filename, "rb");

        //read header
        $header = fread($f, 54);
        $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
                        'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
                        'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);

        if ($header['identifier1'] != 66 or $header['identifier2'] != 77) {
            echo('Not a valid bmp file');
            return false;
        }

        if (!in_array($header['bits_per_pixel'], array(24, 32, 8, 4, 1))) {
            echo('Only 1, 4, 8, 24 and 32 bit BMP images are supported');
            return false;
        }

        $bps = $header['bits_per_pixel']; //bits per pixel
        $wid2 = ceil(($bps / 8 * $header['width']) / 4) * 4;
        $colors = pow(2, $bps);

        $wid = $header['width'];
        $hei = $header['height'];

        $img = imagecreatetruecolor($header['width'], $header['height']);

        //read palette
        if ($bps < 9) {
            for ($i = 0; $i < $colors; $i++) {
                $palette[] = self::undword(fread($f, 4));
            }
        } else {
            if ($bps == 32) {
                imagealphablending($img, false);
                imagesavealpha($img, true);
            }
            $palette = array();
        }

        //read pixels
        for ($y = $hei - 1; $y >= 0; $y--) {
            $row = fread($f, $wid2);
            $pixels = self::str_split2($row, $bps, $palette);
            for ($x = 0; $x < $wid; $x++) {
                self::makepixel($img, $x, $y, $pixels[$x], $bps);
            }
        }
        fclose($f);

        return $img;
    }

    private static function str_split2($row, $bps, $palette) {
        switch ($bps) {
            case 32:
            case 24: return str_split($row, $bps / 8);
            case 8: $out = array();
                $count = strlen($row);
                for ($i = 0; $i < $count; $i++) {
                    $out[] = $palette[ord($row[$i])];
                }
                return $out;
            case 4: $out = array();
                $count = strlen($row);
                for ($i = 0; $i < $count; $i++) {
                    $roww = ord($row[$i]);
                    $out[] = $palette[($roww & 240) >> 4];
                    $out[] = $palette[($roww & 15)];
                }
                return $out;
            case 1: $out = array();
                $count = strlen($row);
                for ($i = 0; $i < $count; $i++) {
                    $roww = ord($row[$i]);
                    $out[] = $palette[($roww & 128) >> 7];
                    $out[] = $palette[($roww & 64) >> 6];
                    $out[] = $palette[($roww & 32) >> 5];
                    $out[] = $palette[($roww & 16) >> 4];
                    $out[] = $palette[($roww & 8) >> 3];
                    $out[] = $palette[($roww & 4) >> 2];
                    $out[] = $palette[($roww & 2) >> 1];
                    $out[] = $palette[($roww & 1)];
                }
                return $out;
        }
    }

    private static function makepixel($img, $x, $y, $str, $bps) {
        switch ($bps) {
            case 32 : $a = ord($str[0]);
                $b = ord($str[1]);
                $c = ord($str[2]);
                $d = 256 - ord($str[3]); //TODO: gives imperfect results
                $pixel = $d * 256 * 256 * 256 + $c * 256 * 256 + $b * 256 + $a;
                imagesetpixel($img, $x, $y, $pixel);
                break;
            case 24 : $a = ord($str[0]);
                $b = ord($str[1]);
                $c = ord($str[2]);
                $pixel = $c * 256 * 256 + $b * 256 + $a;
                imagesetpixel($img, $x, $y, $pixel);
                break;
            case 8 :
            case 4 :
            case 1 : imagesetpixel($img, $x, $y, $str);
                break;
        }
    }

    private static function byte3($n) {
        return chr($n & 255).chr(($n >> 8) & 255).chr(($n >> 16) & 255);
    }

    private static function undword($n) {
        $r = unpack("V", $n);
        return $r[1];
    }

    private static function dword($n) {
        return pack("V", $n);
    }

    private static function word($n) {
        return pack("v", $n);
    }

}

function imagebmp(&$img, $filename = false) {
    return BMP::imagebmp($img, $filename);
}

function imagecreatefrombmp($filename) {
    return BMP::imagecreatefrombmp($filename);
}

/**
 * Класс для изменения изображений
 *
 * <p>Класс содержит статические методы для работы с изображениями:
 * <b>imgResize</b> - созданием уменьшенной копии изображения</p>
 * этот класс наследуется от  <b>nc_System</b>
 *
 * @see nc_ImageTransform::imgResize()
 */
class nc_ImageTransform extends nc_System {

    protected static $_db, $_thumbPostfix;

    // прозрачность
    protected static function setTransparency($new_image, $image_source) {
        $transparencyIndex = imagecolortransparent($image_source);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

        if ($transparencyIndex >= 0)
                $transparencyColor = imagecolorsforindex($image_source, $transparencyIndex);

        $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($new_image, 0, 0, $transparencyIndex);
        imagecolortransparent($new_image, $transparencyIndex);
    }

    /**
     *
     * Создает уменьшенную копию изображения
     * @access public
     * @static
     * @param string $src_img  Путь к исходному изображению
     * @param string $dest_img Путь к создаваемому изображению
     * @param int    $width    Ширина нового изображения
     * @param int    $height   Высота нового изображения
     * @param int    $mode     [optional] Режим уменьшения: 0 - пропорционально уменьшает; 1 - вписывает в указанные размеры, обрезая края
     * @param string $format   [optional] Формат создаваемого изображения (jpg, gif, png, bmp)
     * @param int    $quality  [optional] Качество сжатия изображения (0-100) при $format=='jpg'
     * @return mixed В случае ошибки возвратит false иначе возвратит путь к созданному файлу
     */
    public static function imgResize($src_img, $dest_img, $width, $height, $mode=0, $format=NULL, $quality=90, $message_id = 0, $field = 0) {

        global $classID, $systemTableID;
        $nc_core = nc_Core::get_object();

        if (!file_exists($src_img)) return false;
        $img_size = @getimagesize($src_img);
        if ($img_size === false) return false;

        $img_format = strtolower(substr($img_size['mime'], strpos($img_size['mime'], '/') + 1));
        if (($img_format == 'x-ms-bmp') || ($img_format == 'bitmap')) {
            $img_format = 'bmp';
        }
        if (!function_exists($fn_imgcreatefrom = 'imagecreatefrom'.$img_format))
                return false;

        if (!$format) {
            $format = $img_format;
        }

        $x_ratio = $width / $img_size[0];
        $y_ratio = $height / $img_size[1];
        $new_x = 0;
        $new_y = 0;
        $old_width = $img_size[0];
        $old_height = $img_size[1];

        // just resize to this resolution
        if ($mode == 0) {
            if ($x_ratio < $y_ratio) {
                $new_width = $width;
                $new_height = floor($x_ratio * $img_size[1]);
            } else {
                $new_height = $height;
                $new_width = floor($y_ratio * $img_size[0]);
            }
        }
        // proportionality
        elseif ($mode == 1) {
            $new_height = $height;
            $new_width = $width;
            $new_x_ratio = $old_width / $new_width;
            $new_y_ratio = $old_height / $new_height;
            if ($new_x_ratio < $new_y_ratio) {
                $old_height = floor($new_x_ratio * $new_height);
                $new_y = floor(($img_size[1] - $old_height) / 2);
            } elseif ($new_x_ratio > $new_y_ratio) {
                $old_width = floor($new_y_ratio * $new_width);
                $new_x = floor(($img_size[0] - $old_width) / 2);
            }
        }
		// priorities
        else {
			$new_height = $height;
			$new_width = $width;
			$new_x_ratio = $old_width / $new_width;
			$new_y_ratio = $old_height / $new_height;
			// width priority
			if ($mode == 2) {
				$old_height = floor($new_x_ratio * $new_height);
				$new_y = floor(($img_size[1] - $old_height) / 2);
			}
			// height priority
			elseif ($mode == 3) {
				$old_width = floor($new_y_ratio * $new_width);
				$new_x = floor(($img_size[0] - $old_width) / 2);
			}
		}

        $gd_dest_img = imagecreatetruecolor($new_width, $new_height);
        $gd_src_img = $fn_imgcreatefrom($src_img);

        if (($format == 'png') || ($format == 'gif')) {
            //self::setTransparency($gd_dest_img, $gd_src_img);
            /*PNG FIX 17.06.2012*/
            imagealphablending($gd_dest_img, false);
            imagesavealpha($gd_dest_img, true);
            $transparent = imagecolorallocatealpha($gd_dest_img, 255, 255, 255, 127);
            imagefilledrectangle($gd_dest_img, 0, 0, $new_width, $new_height, $transparent);
            /*PNG FIX END*/
        }

        imagecopyresampled($gd_dest_img, $gd_src_img, 0, 0, $new_x, $new_y, $new_width, $new_height, $old_width, $old_height);
        switch ($format) {
            case 'gif': imagegif($gd_dest_img, $dest_img);
                break;
            case 'png': imagepng($gd_dest_img, $dest_img);
                break;
            case 'bmp': imagebmp($gd_dest_img, $dest_img);
                break;
            default: imagejpeg($gd_dest_img, $dest_img, $quality);
                break;
        }
        imagedestroy($gd_dest_img);
        imagedestroy($gd_src_img);

        // нужно поменять размер в таблице Filetable
        $HTTP_FILES_PATH_PREG = str_replace("/", "\/", $nc_core->HTTP_FILES_PATH);
        // есть файл в защищенной фс
        if (preg_match("/".$HTTP_FILES_PATH_PREG."([0-9uct]+)\/([0-9]+\/)?([0-9A-Z]{32})/i", $dest_img, $matches)) {

            $filename = $matches[3];
            $size = filesize($dest_img);

            $nc_core->db->query("UPDATE `Filetable`
                           SET `File_Size` = '".intval($size)."'
                           WHERE `Virt_Name` = '".$nc_core->db->escape($filename)."'  ");
        }


        // обновление таблицы MessageXX, User
        $message_id = intval($message_id);
        if ($message_id && $field) {
            // информация о поле
            $fld = $nc_core->db->get_row("
      	SELECT `Class_ID`, `System_Table_ID`, `Field_Name`
        FROM `Field`
        WHERE `Field_ID` = '".intval($field)."'
        OR (`Field_Name` = '".$nc_core->db->escape($field)."' AND ( `Class_ID` = '".intval($classID)."' OR `System_Table_ID` = '".intval($systemTableID)."'))
        LIMIT 1", ARRAY_A);

            // определение имени таблицы
            if ($fld['Class_ID']) {
                $table = 'Message'.intval($fld['Class_ID']);
                $where = " `Message_ID` = '".$message_id."' ";
            } else {
                $table = $nc_core->db->get_var("SELECT `System_Table_Name` FROM `System_Table` WHERE `System_Table_ID` = '".intval($fld['System_Table_ID'])."' ");
                $where = " `".$table."_ID` = '".$message_id."'  ";
            }

            if (!$table) {
                return false;
            }
            // текущее значение поля объекта
            $value = $nc_core->db->get_var("SELECT `".$fld['Field_Name']."` FROM `".$table."` WHERE ".$where." ");

            if (!$value) {
                return false;
            }
            // обновляем размер
            $size = filesize($dest_img);
            $value = nc_preg_replace("/:[0-9]+/", ":".$size, preg_quote($value), 1);

            $nc_core->db->query("UPDATE `".$table."` SET `".$fld['Field_Name']."` = '".$nc_core->db->escape($value)."' WHERE ".$where." ");
        }

        return $dest_img;
    }

    /**
     * Функции для создания thumbnails для полей типа файл,
     * в действиях после добавления, после изменения.
     *
     * @global array  $GLOBALS
     * @global string $FILES_FOLDER
     * @param string  $src_field_name - имя поля-источника
     * @param string  $dest_field_name - имя поля-приёмника
     * @param int     $width       Ширина нового изображения
     * @param int     $height      Высота нового изображения
     * @param int     $mode     [optional] Режим уменьшения: 0 - пропорционально уменьшает; 1 - вписывает в указанные размеры, обрезая края
     * @param string  $format   [optional] Формат создаваемого изображения (jpg, gif, png, bmp)
     * @param int     $quality  [optional] Качество сжатия изображения (0-100) при $format=='jpg'
     *
     * @return bool true в случае удачи, false - в случае ошибки.
     * @access public
     * @static
     */
    public static function createThumb($src_field_name, $dest_field_name, $width, $height, $mode=0, $format=NULL, $quality=90) {

        global $GLOBALS;
        global $message, $classID;
        global $nc_core;

        $src_field_id = $GLOBALS['fldID'][array_search($src_field_name, $GLOBALS['fld'])];
        $is_sys = $nc_core->db->get_row("SELECT Class_ID, System_Table_ID from Field WHERE Field_ID = '".$src_field_id."'", ARRAY_A);
        $is_sys = $is_sys["System_Table_ID"];
        $dest_field_id = $GLOBALS['fldID'][array_search($dest_field_name, $GLOBALS['fld'])];

        return self::createThumb_byID($classID, $message, $src_field_id, $dest_field_id, $width, $height, $mode, $format, $quality, $src_field_name, $is_sys);
    }

    /**
     *  Функции для создания thumbnails для полей типа файл
     *
     *
     * @global  object $nc_core
     * @param   int   $classID - идентифиактор класса (компонента)
     * @param   int   $message - номер объекты
     * @param   int   $field_src_id - идентификатор поля источника
     * @param   int   $field_dst_id - идентификатор поля приемника
     * @param   int   $width    Ширина нового изображения
     * @param   int   $height   Высота нового изображения
     * @param   int   $mode     [optional] Режим уменьшения: 0 - пропорционально уменьшает; 1 - вписывает в указанные размеры, обрезая края
     * @param   string $format  [optional] Формат создаваемого изображения (jpg, gif, png, bmp)
     * @param   int   $quality  [optional] Качество сжатия изображения (0-100) при $format=='jpg'
     * @param   string $field_name_src
     * @return  bool true в случае удачи, false - в случае ошибки.
     * @access  public
     * @static
     */
    public static function createThumb_byID($classID, $message, $field_src_id, $field_dst_id, $width, $height, $mode=0, $format=NULL, $quality=90, $field_name_src='', $is_sys=false) {
        global $nc_core;

        // проверка аргументов
        $message += 0;
        $field_src_id += 0;
        $field_dst_id += 0;
        $classID += 0;
        if (!$message || !$field_src_id || !$field_dst_id || !$classID)
                return false;

        # поиск исходного файла
        // латинское имя поля
        if (!$field_name_src) {
            $field_name_src = $nc_core->db->get_var("SELECT `Field_Name` FROM `Field` WHERE `Field_ID` = '".$field_src_id."'");
        }
        if (!$field_name_src) return 0;

        // Значение поля в таблице объектов
        $message_field = ($is_sys ? $nc_core->db->get_row("SELECT * FROM `User` WHERE `User_ID` = '".$message."'", ARRAY_A) : $nc_core->db->get_row("SELECT * FROM `Message".$classID."` WHERE `Message_ID` = '".$message."'", ARRAY_A));
        $file_data = explode(':', $message_field[$field_name_src]);
        $file_name = $file_data[0];
        $file_type = $file_data[1];
        $file_size = $file_data[2];
        $ext = substr($file_name, strrpos($file_name, ".")); // расширение файла
        $file_name = substr($file_name, 0, strrpos($file_name, ".")); // имя файла без расширения.
        // если ли файл в Filetable ?
        $filetable = $nc_core->db->get_row("SELECT * FROM `Filetable`
                                            WHERE `Message_ID` = '".intval($message)."' AND `Field_ID` = '".intval($field_src_id)."'", ARRAY_A);
        // определения полного пути к файлу
        if ($filetable) { // исходный файл в protected
            $path_src = rtrim($nc_core->FILES_FOLDER, '/').$filetable['File_Path'].$filetable['Virt_Name'];
        } else {
            if ($file_data[3]) { // orignal
                $path_src = $nc_core->FILES_FOLDER.$file_data[3];
            } else { // simple
                $path_src = $nc_core->FILES_FOLDER.$field_src_id."_".$message.$ext;
            }
        }

        $img_size = getimagesize($path_src);
        if (!$format) {
            $format = strtolower(substr($img_size['mime'], strpos($img_size['mime'], '/') + 1));
        }

        // получение информации о поле-приемника
        $field_info_desc = $nc_core->db->get_row("SELECT `Field_Name`, `Format` FROM `Field` WHERE `Field_ID` = '".$field_dst_id."'", ARRAY_A);
        if (!$field_info_desc) return false;

        //удаление старого файла
        require_once($nc_core->INCLUDE_FOLDER."s_files.inc.php");
        DeleteFile($field_dst_id, $field_info_desc['Field_Name'], $classID, 0, $message);

        // определение типа фс применика
        $fs = nc_field_parse_format($field_info_desc['Format'], NC_FIELDTYPE_FILE);
        $fs = $fs['fs'];

        $file_name.="_thumb".++self :: $_thumbPostfix;
        // определние имени файла на диске и диретории

        switch ($fs) {
            case NC_FS_PROTECTED:
                $path_dsc = ($is_sys ? 'u/' : $message_field['Subdivision_ID'].'/'.$message_field['Sub_Class_ID'].'/');
                $name_dsc = md5($file_name.date("H:i:s d.m.Y").uniqid("netcat"));
                break;
            case NC_FS_ORIGINAL:
                $path_dsc = $message_field['Subdivision_ID'].'/'.$message_field['Sub_Class_ID'].'/';
                $name_dsc = nc_get_filename_for_original_fs($file_name.($format ? ".$format" : ''), $nc_core->FILES_FOLDER.$path_dsc);
                break;
            case NC_FS_SIMPLE:
                $path_dsc = '';
                $name_dsc = $field_dst_id."_".$message.($format ? ".$format" : '');
                break;
        }

        // копирование файла
        //copy($path_src, $nc_core->FILES_FOLDER.$path_dsc.$name_dsc);
        // создание thumb
        $path_dest = ($is_sys ? $nc_core->FILES_FOLDER.'/u/'.$name_dsc : $nc_core->FILES_FOLDER.$path_dsc.$name_dsc);
        self::imgResize($path_src, $path_dest, $width, $height, $mode, $format, $quality);

        switch ($fs) {
            case NC_FS_PROTECTED :
                $img_size = getimagesize($path_dest);
                if ($img_size === false) {
                    if (file_exists($path_dest)) unlink($path_dest);
                    return false;
                }
                $dst_File_Type = $img_size['mime'];
                $dst_File_Size = filesize($path_dest);
                $insert_filetable_sql = "INSERT INTO `Filetable`(Real_Name, Virt_Name, File_Path, File_Type, File_Size, Message_ID, Field_ID)
                               VALUES('".$nc_core->db->escape($file_name).".".$format."', '".$nc_core->db->escape($name_dsc)."', '/".$nc_core->db->escape($path_dsc)."','".$dst_File_Type."', '".$dst_File_Size."', '".$message."', '".$field_dst_id."')";
                $nc_core->db->query($insert_filetable_sql);

                $in_db = $file_name.":".$dst_File_Type.":".$dst_File_Size; // то, что запишится в БД
                break;
            case NC_FS_ORIGINAL :
                $in_db = $file_name.".".$format.":".$format.":".$file_size; // то, что запишится в БД
                $in_db .= ":".($is_sys ? 'u/'.$name_dsc : $path_dsc.$name_dsc);
                break;
            default :
                $in_db = $file_name.".".$format.":".$format.":".$file_size; // то, что запишится в БД
                break;
        }
        // обновление инфы в БД
        ($is_sys ? $nc_core->db->query("UPDATE `User` SET `".$field_info_desc['Field_Name']."` = '".$nc_core->db->escape($in_db)."' WHERE `User_ID` = '".$message."'") : $nc_core->db->query("UPDATE `Message".$classID."` SET `".$field_info_desc['Field_Name']."` = '".$nc_core->db->escape($in_db)."' WHERE `Message_ID` = '".$message."'"));

        return true;
    }

    public static function putWatermark_file($filepath, $watermark, $mode = 0) {
        $nc_core = nc_Core::get_object();
        // исходный файл
        if (!file_exists($filepath)) {
            $filepath = $nc_core->DOCUMENT_ROOT.$nc_core->SUB_FOLDER.$filepath;
        }
        if (!file_exists($filepath)) {
            trigger_error("File ".$filepath." not found.", E_USER_WARNING);
            return;
        }
        $src = getimagesize($filepath);
        $src_w = $src[0]; // ширина
        $src_h = $src[1]; // высота
        $src_type = strtolower(substr($src['mime'], strpos($src['mime'], '/') + 1)); // тип
        // в зависимости от типа - разные функции
        $func = function_exists("imagecreatefrom".$src_type) ? "imagecreatefrom".$src_type : "imagecreatefromjpeg";
        // ресурс
        $img_src = $func($filepath);

        // ватермарк
        if (!file_exists($watermark)) {
            $watermark = $nc_core->DOCUMENT_ROOT.$nc_core->SUB_FOLDER.$watermark;
        }
        if (!file_exists($watermark)) {
            trigger_error("File ".$watermark." not found.", E_USER_WARNING);
            return;
        }
        $water = getimagesize($watermark);
        $water_w = $water[0]; // ширина
        $water_h = $water[1]; // высота
        $water_type = strtolower(substr($water['mime'], strpos($water['mime'], '/') + 1)); // тип
        // в зависимости от типа - разные функции
        $func = function_exists("imagecreatefrom".$water_type) ? "imagecreatefrom".$water_type : "imagecreatefromjpeg";
        // ресурс
        $img_water = $func($watermark);

        // результат
        $img = imagecreatetruecolor($src_w, $src_h);
        // копируем в результат исходное изображение
        imagecopyresampled($img, $img_src, 0, 0, 0, 0, $src_w, $src_h, $src_w, $src_h);
        // опрделяем, куда копировать ватермарк
        switch ($mode) {
            case 1 : // левый верхний угол
                $x = $y = 3; // c небольшим сдвигом
                break;
            case 2: // правый верхний угол
                $x = $src_w - $water_w - 3;
                $y = 3;
                break;
            case 3: // левый низ
                $y = $src_h - $water_h - 3;
                $x = 3;
                break;
            case 4: // правый низ
                $x = $src_w - $water_w - 3;
                $y = $src_h - $water_h - 3;
                break;
            default: // по центру
                $x = floor(($src_w - $water_w) / 2);
                $y = floor(($src_h - $water_h) / 2);
        }
        if ($x < 0) $x = 0;
        if ($y < 0) $y = 0;
        // копируем  ватермарк
        imagecopy($img, $img_water, $x, $y, 0, 0, $water_w, $water_h);

        //записываем в файл
        $func = function_exists("image".$src_type) ? "image".$src_type : "imagejpg";
        // можно задать качество
        if (func == "imagejpeg" || $func == "imagepng") {
            $r = $func($img, $filepath, 9);
        } else {
            $r = $func($img, $filepath);
        }

        imagedestroy($img);
        imagedestroy($img_src);
        imagedestroy($img_water);

        return $r;
    }

    public static function putWatermark($classID, $field, $message, $watermark, $mode = 0) {
        global $nc_core, $db;
        $message = intval($message);

        $src = nc_file_path($classID, $message, $field);
        if (!$src) return false;

        // вставляем ватермарк
        self::putWatermark_file($src, $watermark, $mode);

        // теперь нужно обновить размер

        $systemTableID = 0;
        $system_tables = array("Catalogue" => 1, "Subdivision" => 2, "User" => 3, "Template" => 4);

        // определяем таблицу и первичный ключ в ней
        if (!is_int($classID)) {
            $table = $db->escape($classID);
            $pk = $db->escape($classID)."_ID";
            $systemTableID = $system_tables[$classID];
        } else {
            $table = "Message".intval($classID);
            $pk = "Message_ID";
        }

        // определяем номер поля и его имя
        if (is_int($field)) {
            $field_id = intval($field);
            $field_name = $db->get_var("SELECT `Field_Name` FROM `Field` WHERE `Field_ID` = '".$field_id."' ");
        } else {
            $field_name = $db->escape($field);
            $field_id = $db->get_var("SELECT `Field_ID` FROM `Field` WHERE `Field_Name` = '".$field_name."' AND ".( $systemTableID ? "`System_Table_ID` = '".$systemTableID."'" : "`Class_ID` = '".$classID."' ")." ");
        }
        // новое значение
        clearstatcache();
        $filesize = filesize($nc_core->DOCUMENT_ROOT.$src);

        $old_value = $db->get_var("SELECT `".$field_name."` FROM `".$table."` WHERE `".$pk."` = '".$message."' ");
        $new_value = preg_replace("/:(\d+):/", ':'.$filesize.':', $old_value);
        $new_value = preg_replace("/:(\d+)$/", ':'.$filesize, $new_value);

        $db->query("UPDATE `".$table."` SET `".$field_name."` = '".$db->escape($new_value)."' WHERE `".$pk."` = '".$message."' ");
        // и в таблице Filetable
        $db->query("UPDATE `Filetable` SET `File_Size` = '".$filesize."' WHERE `Message_ID` = '".$message."' AND `Field_ID` = '".$field_id."' ");

        return true;
    }

}
