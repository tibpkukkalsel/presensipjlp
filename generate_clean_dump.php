<?php
// Script to generate a clean, 100% hosting-compatible SQL dump of app_presensipjlp

$dbName = 'app_presensipjlp';
$rawDumpFile = __DIR__ . '/temp_raw_dump.sql';
$cleanDumpFile = __DIR__ . '/database_siap_hosting.sql';

// 1. Run mysqldump
$cmd = "\"C:\\xampp\\mysql\\bin\\mysqldump.exe\" -u root --routines --triggers --hex-blob --default-character-set=utf8mb4 {$dbName} > \"{$rawDumpFile}\"";
exec($cmd, $output, $returnVar);

if ($returnVar !== 0) {
    die("mysqldump failed with exit code {$returnVar}\n");
}

$sql = file_get_contents($rawDumpFile);

// 2. Remove any CREATE DATABASE or USE statements
$sql = preg_replace('/CREATE\s+DATABASE\s+[^;]+;/i', '', $sql);
$sql = preg_replace('/USE\s+`?[a-zA-Z0-9_]+`?;/i', '', $sql);

// 3. Remove DEFINER statements:
// Match /*!50013 DEFINER=`...`@`...` SQL SECURITY DEFINER */ or variants
$sql = preg_replace('/\/\*!50013\s+DEFINER=[^*]+\*\//i', '', $sql);
$sql = preg_replace('/DEFINER\s*=\s*`[^`]+`@`[^`]+`/i', '', $sql);
$sql = preg_replace('/DEFINER\s*=\s*[^\s]+/i', '', $sql);
$sql = preg_replace('/SQL\s+SECURITY\s+DEFINER/i', 'SQL SECURITY INVOKER', $sql);

// 4. Ensure foreign key checks are disabled at start and enabled at end
// Remove existing ones if present to standardize
$sql = preg_replace('/SET\s+FOREIGN_KEY_CHECKS\s*=\s*[01];?/i', '', $sql);

$header = "-- ==========================================================\n"
    . "-- DATABASE SIAP HOSTING (CLEAN & HOSTING COMPATIBLE)\n"
    . "-- Database: app_presensipjlp\n"
    . "-- Generated: " . date('Y-m-d H:i:s') . "\n"
    . "-- Fitur: Bebas DEFINER, Bebas USE DATABASE, Bebas Error 1227 / 1449 / 1044\n"
    . "-- Kompatibel: cPanel phpMyAdmin, MariaDB, MySQL 5.7 / 8.0, SSH import\n"
    . "-- ==========================================================\n\n"
    . "SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;\n"
    . "SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;\n"
    . "SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;\n"
    . "SET NAMES utf8mb4;\n"
    . "SET @OLD_TIME_ZONE=@@TIME_ZONE;\n"
    . "SET TIME_ZONE='+00:00';\n"
    . "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;\n"
    . "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;\n"
    . "SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n";

$footer = "\n\n-- ==========================================================\n"
    . "SET TIME_ZONE=@OLD_TIME_ZONE;\n"
    . "SET SQL_MODE=@OLD_SQL_MODE;\n"
    . "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;\n"
    . "SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;\n"
    . "SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT;\n"
    . "SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS;\n"
    . "SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION;\n"
    . "-- Selesai Import Database\n"
    . "-- ==========================================================\n";

$cleanSql = $header . trim($sql) . $footer;

file_put_contents($cleanDumpFile, $cleanSql);
echo "Clean dump saved to: " . basename($cleanDumpFile) . " (" . strlen($cleanSql) . " bytes)\n";

// Remove temp raw dump
if (file_exists($rawDumpFile)) {
    unlink($rawDumpFile);
}
