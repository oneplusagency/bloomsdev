<?php 

function checkFolderSize($path) {
    $totalSize = 0;
    $files = scandir($path);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $totalSize += checkFolderSize($filePath);
            } else {
                $totalSize += filesize($filePath);
            }
        }
    }

    return $totalSize;
}




$currentFolder = __DIR__ .'/development3';
$folderSize = checkFolderSize($currentFolder);

// Convert bytes to more readable format
function formatSize($bytes) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}

echo "Current folder size: " . formatSize($folderSize);
die;


$foldersToRemove = [
	'salongutscheindev2',
	'salongutscheindev__',
	'../1plus07122020__',
	'../1plus07122020_09_07_21',
	'../1plus07122020_1plus_alt',
	'../1plus07122020_10_09_21',
	'../1plus07122020_15_11_2021',
	'../1plus07122020_22_06_22',
	'../1plus07122020_29_06_22',
	'../1plus071220200-8_12_22'

];


foreach($foldersToRemove as $folderName){

	$path = realpath( __DIR__. '/'.$folderName );

	try{

		if(file_exists($path)){
			$r = deleteDirectory($path);
			echo "Removed Success : ". $path .PHP_EOL;
		}
	
	}catch(\Exception $e){
		echo $e->getMessage() . PHP_EOL;
	}


}




function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
			// echo $path.PHP_EOL;
            unlink($path);
        }
    }

    return rmdir($dir);
}


die("DISK CLEAN UP");