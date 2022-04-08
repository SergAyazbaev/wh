<?php
require_once "../../vendor/autoload.php";

use thiagoalessio\TesseractOCR\TesseractOCR;


$cmd = (new TesseractOCR('D:\Guidejet\8055.png'))->command;
echo "$cmd";

ddd($cmd);

echo (new TesseractOCR('D:\Guidejet\8055.png'))->run();
