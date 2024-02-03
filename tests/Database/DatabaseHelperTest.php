<?php

spl_autoload_extensions(".php");
// autoloadはこのファイルを実行するプロセスの作業ディレクトリを基準にする
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $file = 'src/' . $class . '.php';
    // file_put_contents("../test/debug.txt", $file . PHP_EOL, FILE_APPEND);
    if (file_exists($file)) {
        require_once $file;
    }
});

use PHPUnit\Framework\TestCase;
use Database\DatabaseHelper;
use Database\MySQLWrapper;
use Settings\Settings;

class DatabaseHelperTest extends TestCase
{
    public function testDeleteNotAccessedImages(): void
    {
        $imageStorageDays = intval(Settings::env('IMAGE_STORAGE_DAYS'));
        $now = new DateTime();
        $now->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $expirationDateMinus2 = (clone $now)->sub(new DateInterval('P' . ($imageStorageDays + 2) . 'D'))->format('Y-m-d H:i:s');
        $expirationDateMinus1 = (clone $now)->sub(new DateInterval('P' . ($imageStorageDays + 1) . 'D'))->format('Y-m-d H:i:s');
        $expirationDatePlus0 = (clone $now)->sub(new DateInterval('P' . ($imageStorageDays) . 'D'))->format('Y-m-d H:i:s');
        $expirationDatePlus1 = (clone $now)->sub(new DateInterval('P' . ($imageStorageDays - 1) . 'D'))->format('Y-m-d H:i:s');
        $expirationDatePlus2 = (clone $now)->sub(new DateInterval('P' . ($imageStorageDays - 2) . 'D'))->format('Y-m-d H:i:s');

        $db = new MySQLWrapper();
        $query = <<<SQL
        INSERT INTO images VALUES
        ('A', '', '', NOW(), '$expirationDateMinus2', 0, '', '', '127.0.0.1'),
        ('B', '', '', NOW(), '$expirationDateMinus1', 0, '', '', '127.0.0.1'),
        ('C', '', '', NOW(), '$expirationDatePlus0', 0, '', '', '127.0.0.1'),
        ('D', '', '', NOW(), '$expirationDatePlus1', 0, '', '', '127.0.0.1'),
        ('E', '', '', NOW(), '$expirationDatePlus2', 0, '', '', '127.0.0.1');
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute();

        $DBHelper = new DatabaseHelper();
        $numOfDeletedImages = $DBHelper->deleteNotAccessedImages($imageStorageDays);

        $stmt = $db->prepare("DELETE FROM images WHERE image_hash in ('A','B','C','D','E')");
        $stmt->execute();

        $expectedResult = 3;
        $this->assertSame($expectedResult, $numOfDeletedImages);
    }
}
