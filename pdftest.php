<?php
//
//// 错误报告，方便调试
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//
//echo "--- Imagick PDF终极清晰度转换测试 ---<br>";
//
//if (!extension_loaded('imagick')) {
//    die("测试失败：服务器未安装 Imagick 扩展。");
//}
//echo "检查通过：Imagick 扩展已安装。<br>";
//
//$pdfUrl = "https://inv.alicdn.com/cb/OSTB_3248442096MM7a0k0flyjIYFf.pdf";
//// --- 核心改动 1：修改输出文件名为 .png ---
//$outputPath = __DIR__ . '/invoice_ultra_quality.png';
//
//echo "准备转换的PDF链接：" . htmlspecialchars($pdfUrl) . "<br>";
//echo "准备将图片保存到：" . htmlspecialchars($outputPath) . "<br>";
//
//try {
//    $pdfContent = file_get_contents($pdfUrl);
//    if ($pdfContent === false) {
//        die("<strong>--- 失败！---</strong><br>无法下载PDF文件。");
//    }
//    echo "PDF文件内容已下载到内存。<br>";
//
//    $imagick = new Imagick();
//
//    // --- 核心改动 2：使用更高的分辨率 ---
//    $imagick->setResolution(800, 800);
//    echo "设置分辨率为 400 DPI...<br>";
//
//    // 从内存中读取PDF数据
//    $imagick->readImageBlob($pdfContent);
//    echo "成功从内存加载PDF。<br>";
//
//    // 确保有一个白色背景
//    $imagick->setImageBackgroundColor('white');
//    $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
//
//    // --- 核心改动 3：输出为高质量的PNG格式 ---
//    $imagick->setImageFormat('png');
//    echo "设置输出格式为 PNG...<br>";
//
//    if ($imagick->writeImage($outputPath)) {
//        echo "<strong>--- 成功！---</strong><br>";
//        echo "超清PNG图片已成功转换并保存。请查看新文件 `invoice_ultra_quality.png`。";
//    } else {
//        echo "<strong>--- 失败！---</strong><br>";
//        echo "writeImage() 方法执行失败。";
//    }
//
//} catch (Exception $e) {
//    echo "<strong>--- 转换失败！捕获到异常 ---</strong><br>";
//    echo "错误信息: " . $e->getMessage();
//}
//
//// 释放资源
//if (isset($imagick)) {
//    $imagick->clear();
//    $imagick->destroy();
//}
//
//echo "<br>--- 测试结束 ---";
//?>


<?php
// 要下载的PDF链接
$pdfUrl = "https://inv.alicdn.com/cb/OSTB_3248442096MM7a0k0flyjIYFf.pdf";
// 要保存到本地的文件名
$localFilename = "local_invoice.pdf";

echo "正在从: " . htmlspecialchars($pdfUrl) . " 下载...<br>";

// 使用file_get_contents下载文件内容
$pdfContent = file_get_contents($pdfUrl);

if ($pdfContent === false) {
    die("下载失败！请检查URL是否正确以及PHP的allow_url_fopen配置。");
}

// 将内容写入本地文件
$result = file_put_contents($localFilename, $pdfContent);

if ($result === false) {
    die("保存到本地失败！请检查目录 '" . __DIR__ . "' 的写入权限。");
}

echo "成功！文件已保存为: " . htmlspecialchars($localFilename);
?>
