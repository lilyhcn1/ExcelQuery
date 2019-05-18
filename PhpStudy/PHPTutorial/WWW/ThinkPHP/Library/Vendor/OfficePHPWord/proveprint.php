<?php
// include_once 'bootstrap.php';
    // require $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/OfficePHPWord/samples/bootstrap.php'; 








    
    require $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/OfficePHPWord/samples/Sample_Header.php';
// include_once 'Sample_Header.php';
// http://r34.cc/Public/offcephpword/resources/template.docx
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./samples/resources/template.docx');
// $phpWord = new \PhpOffice\PhpWord\PhpWord();

// pr($templateProcessor);
$dataarr=$_POST;
if(empty($_POST)){
  $dataarr=$_GET; 
}

if(empty($dataarr)){
    for($i=1;$i<=50;$i++){
        $dataarr[$i]='进利'.$i;
    }
}

print_r($dataarr);

for($i=1;$i<=50;$i++){
    // echo $i;
    $templateProcessor->setValue($i, $dataarr[$i]);
}
$templateProcessor->saveAs('./samples/results/lily_replace.docx');
// echo getEndingNotes(array('Word2007' => 'docx'));
echo '<a href="./samples/results/lily_replace.docx">word</a>';