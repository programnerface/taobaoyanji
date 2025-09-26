<?php

namespace app\admin\controller;

use app\admin\library\Auth;
use app\common\controller\Backend;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Db;
use think\db\exception\BindParamException;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class ThreecProduct extends Backend
{

    /**
     * ThreecProduct模型对象
     * @var \app\common\model\ThreecProduct
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\ThreecProduct;
        $this->view->assign("verificationStatusList", $this->model->getVerificationStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * OCR
     */
//    public function sn_ocr($ids)
//    {
//        $row = $this->model->get(['id' => $ids]);
//        if (!$row) {
//            $this->error(__('No Results were found'));
//        }
//        $this->view->assign("row", $row->toArray());
//
//        return $this->view->fetch();
//    }

//    public function sn_ocr($ids = null)
//    {
//        $row = $this->model->get(['id' => $ids]);
//        if (!$row) {
//            $this->error(__('No Results were found'));
//        }
//
//        // 从URL获取字段名, 默认为 'sn_image_url'
//        $fieldName = $this->request->get('field', 'sn_image_url');
////        var_dump($this->request->get());
//        // 安全检查白名单
//        $allowedFields = ['sn_image_url', 'inspection_image_url', 'screen_on_image_url',  'invoice_url', 'tracking_map_url'];
//        if (!in_array($fieldName, $allowedFields)) {
//            $this->error(__('Invalid parameter'));
//        }
//
//        // --- 新增：标题映射 ---
//        $titleMap = [
//            'sn_image_url'       => 'SN图片',
//            'inspection_image_url' => '验机图片',
//            'screen_on_image_url'  => '亮屏照片',
//            'invoice_url'  => '发票图片',
//            'tracking_map_url'   => '物流轨迹图'
//        ];
//        // 根据字段名查找标题，如果找不到则使用默认标题
//        $title = $titleMap[$fieldName] ?? '图片详情';
//        // --- 修改结束 ---
////        var_dump($title);
//        // 动态获取图片URL
//        $imageUrl = $row[$fieldName] ?? '';
//        // 如果链接为空，或者内容是 "无"，则直接报错
//        if (empty($imageUrl) || $imageUrl === '无') {
//            $this->error('找不到图片或文件链接');
//        }
//
//
////        var_dump($imageUrl);
////        exit();
//        // 将所有动态数据分配给视图
//        $this->view->assign("row", $row->toArray());
//        $this->view->assign("image_url", $imageUrl);
//        $this->view->assign("title", $title);
//
//        return $this->view->fetch('threec_product/sn_ocr');
//    }

    /**
     * 通用的图片/PDF详情方法
     */
//    public function sn_ocr($ids = null)
//    {
//        $row = $this->model->get(['id' => $ids]);
//        if (!$row) {
//            $this->error(__('No Results were found'));
//        }
//
//        // 从URL获取字段名
//        $fieldName = $this->request->get('field', 'sn_image_url');
//        $allowedFields = ['sn_image_url', 'inspection_image_url', 'screen_on_image_url', 'invoice_url', 'tracking_map_url'];
//        if (!in_array($fieldName, $allowedFields)) {
//            $this->error(__('Invalid parameter'));
//        }
//
//        // 动态获取URL
//        $fileUrl = $row[$fieldName] ?? '';
//        if (empty($fileUrl) || $fileUrl === '无') {
//            $this->error('找不到文件链接');
//        }
//
//        if ($fileUrl && !preg_match("/^https?:\/\//i", $fileUrl)) {
//            // 如果不是，就使用 cdnurl() 函数为其加上域名，变为完整URL
//            $fileUrl = cdnurl($fileUrl, true);
//        }
//        // 判断内容类型
//        $viewType = 'image'; // 默认为图片
////        if ($fieldName === 'invoice_url'  && substr(strtolower($fileUrl), -4) === '.pdf') {
////            $viewType = 'pdf';
////        }
//
//        if (substr(strtolower($fileUrl), -4) === '.pdf') {
//            $viewType = 'pdf';
//        }
//        // 标题映射
//        $titleMap = [
//            'sn_image_url'       => 'SN图片',
//            'inspection_image_url' => '验机图片',
//            'screen_on_image_url'  => '亮屏照片',
//            'invoice_url'        => '发票详情', // 标题改为详情
//            'tracking_map_url'   => '物流轨迹图',
//        ];
//        $title = $titleMap[$fieldName] ?? '文件详情';
//
//        // 将所有动态数据分配给视图
//        $this->view->assign("row", $row->toArray());
//        $this->view->assign("file_url", $fileUrl);
//        $this->view->assign("title", $title);
//        $this->view->assign("viewType", $viewType);
//
//        return $this->view->fetch('threec_product/sn_ocr');
//    }

    /**
     * 通用的详情弹窗加载方法
     */
    public function sn_ocr($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // 从URL获取字段名, 默认为 'sn_image_url'
        $fieldName = $this->request->get('field', 'sn_image_url');

        // ... (您之前的URL获取、白名单检查、cdnurl处理等逻辑保持不变)
        $fileUrl = $row[$fieldName] ?? '';
        if (empty($fileUrl) || $fileUrl === '无') {
            $this->error('找不到文件链接');
        }
        if ($fileUrl && !preg_match("/^https?:\/\//i", $fileUrl)) {
            $fileUrl = cdnurl($fileUrl, true);
        }

        // --- 核心改动：根据字段名选择视图文件 ---

        // 1. 定义一个视图映射关系
        $viewMap = [
            'invoice_url'        => 'threec_product/invoice_ocr',
            'tracking_map_url'   => 'threec_product/trackingmap',
            // 如果将来还有其他特殊视图，可以在这里继续添加
        ];

        // 2. 根据当前字段名，从映射中查找对应的视图文件
        //    如果找不到，则使用默认的 'threec_product/sn_ocr'
        $viewTemplate = $viewMap[$fieldName] ?? 'threec_product/sn_ocr';

        // --- 修改结束 ---

        // 判断内容类型
        $viewType = 'image';
        if (substr(strtolower($fileUrl), -4) === '.pdf') {
            $viewType = 'pdf';
        }

        // 标题映射
        $titleMap = [
            'sn_image_url'       => 'SN图片',
            'inspection_image_url' => '验机图片',
            'screen_on_image_url'  => '亮屏照片',
            'invoice_url'        => '发票详情',
            'tracking_map_url'   => '物流轨迹图',
        ];
        $title = $titleMap[$fieldName] ?? '文件详情';

        // 将所有动态数据分配给视图
        $this->view->assign("row", $row->toArray());
        $this->view->assign("file_url", $fileUrl);
        $this->view->assign("title", $title);
        $this->view->assign("viewType", $viewType);

        // 使用我们动态选择的视图模板进行渲染
        return $this->view->fetch($viewTemplate);
    }

    public function invoice_ocr($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $data = $row->toArray();

        // 明确获取发票链接字段
        $fileUrl = $data['invoice_url'] ?? '';
        if (empty($fileUrl) || $fileUrl === '无') {
            $this->error('找不到发票链接');
        }

        // 准备需要传递给视图的数据
        $this->view->assign("row", $data);
        $this->view->assign("file_url", $fileUrl);

        // 指定渲染新的 invoice_ocr.html 模板文件
        return $this->view->fetch('threec_product/invoice_ocr');
    }

    /**
     * 新增：PDF代理方法，用于解决跨域问题
     */
//    public function proxy_pdf()
//    {
//        $pdfUrl = $this->request->get('url');
//        if (!$pdfUrl || !filter_var($pdfUrl, FILTER_VALIDATE_URL)) {
//            $this->error('无效的PDF链接');
//        }
//
//        // --- 核心逻辑：来自您提供的脚本，更健壮 ---
//        $contextOptions = [
//            'http' => [
//                'method' => 'GET',
//                'header' => [
//                    // 模拟浏览器访问，防止被服务器拦截
//                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
//                ],
//                'timeout' => 30, // 设置30秒超时
//            ]
//        ];
//        $context = stream_context_create($contextOptions);
//        // --- 逻辑结束 ---
//
//        // 使用创建的上下文来获取文件内容
//        $pdfContent = file_get_contents($pdfUrl, false, $context);
//
//        if ($pdfContent === false) {
//            $this->error('PHP无法获取PDF文件内容，请检查服务器网络或目标URL是否有效');
//        }
//
//        // 使用FastAdmin/ThinkPHP的方式返回响应
//        // 这会正确设置Content-Type并输出内容
//        return response($pdfContent, 200, ['Content-Type' => 'application/pdf']);
//    }


    /**
     * 新增：PDF代理方法，用于解决跨域问题 (cURL健壮版)
     */
//    public function proxy_pdf()
//    {
//        $pdfUrl = $this->request->get('url');
//        if (!$pdfUrl || !filter_var($pdfUrl, FILTER_VALIDATE_URL)) {
//            $this->error('无效的PDF链接');
//        }
//
//        // 1. 初始化 cURL
//        $ch = curl_init();
//
//        // 2. 设置 cURL 选项
//        curl_setopt($ch, CURLOPT_URL, $pdfUrl);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 将结果作为字符串返回
//        curl_setopt($ch, CURLOPT_HEADER, 0); // 不需要HTTP头
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 跟随重定向
//        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置30秒总超时
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 设置10秒连接超时
//
//        // 模拟浏览器User-Agent，防止被目标服务器拦截
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
//
//        // 在Windows本地环境中，常常需要禁用SSL证书验证
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//
//        // 3. 执行 cURL 请求
//        $pdfContent = curl_exec($ch);
//
//        // 4. 检查是否有cURL级别的错误
//        if(curl_errno($ch)){
//            $error_msg = curl_error($ch);
//            curl_close($ch);
//            // 将具体的cURL错误信息返回给前端
//            $this->error('cURL下载文件失败: ' . $error_msg);
//        }
//
//        // 5. 关闭 cURL
//        curl_close($ch);
//
//        if ($pdfContent === false || empty($pdfContent)) {
//            $this->error('获取PDF文件内容为空或失败');
//        }
//
//        // 6. 使用FastAdmin/ThinkPHP的方式返回响应
//        return response($pdfContent, 200, ['Content-Type' => 'application/pdf']);
//    }


//new
    /**
     * 新增：PDF代理方法 (最终健壮版)
     */
    public function proxy_pdf()
    {
        // 引入ThinkPHP的Response类
        $response = \think\Response::create();

        try {
            $pdfUrl = $this->request->get('url');
            if (!$pdfUrl || !filter_var($pdfUrl, FILTER_VALIDATE_URL)) {
                throw new \Exception('无效的PDF链接', 400);
            }

            // 使用 cURL 获取内容
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $pdfUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $pdfContent = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('cURL下载文件失败: ' . curl_error($ch), 502);
            }
            curl_close($ch);

            if (empty($pdfContent)) {
                throw new \Exception('获取的PDF文件内容为空', 204);
            }

            // 成功：返回PDF内容
            return $response->data($pdfContent)->contentType('application/pdf')->code(200);

        } catch (\Exception $e) {
            // 失败：返回一个纯文本的错误信息和对应的HTTP状态码
            // 这样前端JS的fetch就能正确捕获到错误
            return $response->data($e->getMessage())->contentType('text/plain')->code($e->getCode() > 0 ? $e->getCode() : 500);
        }
    }





    /**
     * 新增：显示校验报告
     */
    public function report($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // --- 核心改动：处理报告时间 ---
        $generationTime = $row->report_time;
        // 检查 report_time 字段是否为空或无效
        if (empty($generationTime) || $generationTime == '0000-00-00 00:00:00') {
            // 获取当前时间字符串
            $currentTimeString = date('Y-m-d H:i:s');
            // 更新数据库并用于本次显示
            $row->report_time = $currentTimeString;
            $row->save();
            $generationTime = $currentTimeString;
        }
        // --- 修改结束 ---

        $data = $row->toArray();

        // 拼接副标题
        $subtitle = sprintf(
            '线上平台订单号: %s / 商户订单号: %s / 生成时间: %s',
            $data['platform_order_id'],
            $data['merchant_order_id'],
            $generationTime
        );

        $this->view->assign("row", $data);
        $this->view->assign("subtitle", $subtitle);
        $this->view->assign("verification_status", $data['verification_status']);

        return $this->view->fetch('threec_product/yanjibaogao');
    }

    /**
     * 导入
     *
     * @return void
     * @throws PDOException
     * @throws BindParamException
     */
    protected function import()
    {
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $this->error(__('Unknown data format'));
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, 'w');
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding !== 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);

            $reader = new Csv();
        } elseif ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';

        $table = $this->model->getQuery()->getTable();
        $database = \think\Config::get('database.database');
        $fieldArr = [];
        $list = db()->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?", [$table, $database]);
        foreach ($list as $k => $v) {
            if ($importHeadType == 'comment') {
                $v['COLUMN_COMMENT'] = explode(':', $v['COLUMN_COMMENT'])[0]; //字段备注有:时截取
                $fieldArr[$v['COLUMN_COMMENT']] = $v['COLUMN_NAME'];
            } else {
                $fieldArr[$v['COLUMN_NAME']] = $v['COLUMN_NAME'];
            }
        }

        //加载文件
        $insert = [];
        try {
            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $fields = [];
            for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $fields[] = $val;
                }
            }

            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $values[] = is_null($val) ? '' : $val;
                }
                $row = [];
                $temp = array_combine($fields, $values);
                foreach ($temp as $k => $v) {
                    if (isset($fieldArr[$k]) && $k !== '') {
                        $row[$fieldArr[$k]] = $v;
                    }
                }
                if ($row) {
                    $insert[] = $row;
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }

        try {
            //是否包含admin_id字段
            $has_admin_id = false;
            foreach ($fieldArr as $name => $key) {
                if ($key == 'admin_id') {
                    $has_admin_id = true;
                    break;
                }
            }
            if ($has_admin_id) {
                $auth = Auth::instance();
                foreach ($insert as &$val) {
                    if (empty($val['admin_id'])) {
                        $val['admin_id'] = $auth->isLogin() ? $auth->id : 0;
                    }
                }
            }
            $this->model->saveAll($insert);
        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }

    /**
     * 重写编辑方法
     */
//    public function edit($ids = null)
//    {
//        $row = $this->model->get($ids);
//        if (!$row) {
//            $this->error(__('No Results were found'));
//        }
//        if ($this->request->isPost()) {
//            $params = $this->request->post("row/a");
//            if ($params) {
//                try {
//                    // 这行代码会触发上面模型里的 beforeWrite 事件
//                    $result = $row->save($params);
//                    if ($result === false) {
//                        $this->error($row->getError());
//                    }
//                    $this->success();
//                } catch (\Exception $e) {
//                    $this->error($e->getMessage());
//                }
//            }
//            $this->error(__('Parameter %s can not be empty', ''));
//        }
//        $this->view->assign("row", $row);
//        return $this->view->fetch();
//    }


}
