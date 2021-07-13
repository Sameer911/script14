<?php
// (A) FUNCTION TO FORMULATE SERVER RESPONSE
function verbose($ok=1,$info=""){
  // THROW A 400 ERROR ON FAILURE
  if ($ok==0) { http_response_code(400); }
  die(json_encode(["ok"=>$ok, "info"=>$info]));
}

// (B) INVALID UPLOAD
if (empty($_FILES) || $_FILES['file']['error']) {
  verbose(0, "Failed to move uploaded file.");
}

// (C) UPLOAD DESITINATION
// ! CHANGE FOLDER IF REQUIRED !
if (!file_exists('upload/videos/' . date('Y'))) {
  @mkdir('upload/videos/' . date('Y'), 0777, true);
}
if (!file_exists('upload/videos/' . date('Y') . '/' . date('m'))) {
  @mkdir('upload/videos/' . date('Y') . '/' . date('m'), 0777, true);
}
$folder   = 'videos';
$fileType = 'video';
// $file_name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
// $new_string        = pathinfo($file_name, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
// $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
// $dir         = "upload/{$folder}/" . date('Y') . '/' . date('m');
// $filename    = $dir . '/' . PT_GenerateKey() . '_' . date('d') . '_' . md5(time()) . "_{$fileType}.{$file_extension}";

$filePath = __DIR__ . DIRECTORY_SEPARATOR . "upload" .DIRECTORY_SEPARATOR."{$folder}".DIRECTORY_SEPARATOR. date('Y') . DIRECTORY_SEPARATOR .date('m');


// $filePath = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
// if (!file_exists($filePath)) { 
//   if (!mkdir($filePath, 0777, true)) {
//     verbose(0, "Failed to create $filePath");
//   }
// }

$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
$new_string        = pathinfo($fileName, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
$newFileName = PT_GenerateKey() . '_' .  date('d') . '_' . md5(time()) . "_{$fileType}.{$file_extension}";
$filePath2 = $filePath . DIRECTORY_SEPARATOR . $newFileName; 
$filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

// variables to store path in database;
$dir         = "upload/{$folder}/" . date('Y') . '/' . date('m');
$file_path_for_db    = $dir . '/' . $newFileName;


// (D) DEAL WITH CHUNKS
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
if ($out) {
  $in = @fopen($_FILES['file']['tmp_name'], "rb");
  if ($in) {
    while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
  } else {
    verbose(0, "Failed to open input stream");
  }
  @fclose($in);
  @fclose($out);
  @unlink($_FILES['file']['tmp_name']);
} else {
  verbose(0, "Failed to open output stream");
}

// (E) CHECK IF FILE HAS BEEN UPLOADED
if (!$chunks || $chunk == $chunks - 1) {
  rename("{$filePath}.part", $filePath);
  rename($filePath,$filePath2);
  verbose(1, $file_path_for_db);

}
verbose(1, "Upload OK");

function PT_GenerateKey($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumbers = true, $usespecial = false) {
  $charset = '';
  if ($uselower) {
      $charset .= "abcdefghijklmnopqrstuvwxyz";
  }
  if ($useupper) {
      $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  }
  if ($usenumbers) {
      $charset .= "123456789";
  }
  if ($usespecial) {
      $charset .= "~@#$%^*()_+-={}|][";
  }
  if ($minlength > $maxlength) {
      $length = mt_rand($maxlength, $minlength);
  } else {
      $length = mt_rand($minlength, $maxlength);
  }
  $key = '';
  for ($i = 0; $i < $length; $i++) {
      $key .= $charset[(mt_rand(0, strlen($charset) - 1))];
  }
  return $key;
}