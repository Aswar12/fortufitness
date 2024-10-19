<?php

return [
    'executable' => env('TESSERACT_EXECUTABLE', 'C:\Program Files\Tesseract-OCR\tesseract.exe'),
    'path' => '/usr/bin/tesseract',
    'lang' => 'ind',
    'executable_mode' => 'TesseractOCR',
    'timeout' => 60,
];
