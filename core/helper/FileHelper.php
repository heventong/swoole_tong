<?php
namespace Core\helper;
class FileHelper{
    public static function getFileMd5($dir,$ignore){
        $files=glob($dir);
        $ret=[];
        foreach($files as $file){
            if(is_dir($file) && strpos($file,$ignore)===false)
                $ret[]=self::getFileMd5($file."/*",$ignore); //如果是文件夹，则递归,注意要自己加上/*，否则获取不到内容
            else if(@pathinfo($file)["extension"]=="php")
                $ret[]=md5_file($file);
        }
        return md5(implode("",$ret));//返回文件md5值
    }
}
