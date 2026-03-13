<?php
function detectRoot($projectRootPath, $docRoot) {
    $projectRootPath = str_replace('\\', '/', realpath($projectRootPath));
    $docRoot = $docRoot ? str_replace('\\', '/', realpath($docRoot)) : '';
    
    $rootPath = '';
    if ($docRoot) {
        $p = rtrim($projectRootPath, '/') . '/';
        $d = rtrim($docRoot, '/') . '/';
        if (stripos($p, $d) === 0) {
            $rootPath = '/' . trim(substr($p, strlen($d)), '/');
        }
    } else {
        if (preg_match('/htdocs\/(.+)$/i', $projectRootPath, $matches)) {
            $rootPath = '/' . $matches[1];
        } else {
            $rootPath = '/' . basename($projectRootPath);
        }
    }
    return $rootPath;
}

echo "Web Simulation (Windows): " . detectRoot('c:/xampp/htdocs/ibcctrip', 'c:/xampp/htdocs') . "\n";
echo "Web Simulation (Case Diff): " . detectRoot('C:/xampp/htdocs/ibcctrip', 'c:/xampp/htdocs') . "\n";
echo "CLI Simulation: " . detectRoot('c:/xampp/htdocs/ibcctrip', '') . "\n";
