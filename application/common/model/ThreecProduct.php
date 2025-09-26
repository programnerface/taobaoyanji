<?php

namespace app\common\model;

use think\Model;
use think\Log;
use app\common\validate\ThreecProduct as ThreecProductValidate;
class ThreecProduct extends Model
{

    

    

    // 表名
    protected $name = 'treecproduct';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'verification_status_text'
    ];

    protected static function init()
    {
        self::beforeInsert(function ($row) {
            // 只在 report_time 为空时才设置，防止覆盖导入的数据
            if (empty($row->report_time)) {
                $row->report_time =  date('Y-m-d H:i:s');
            }
        });

        // 在数据写入数据库之前，触发这个匿名函数
        self::beforeWrite(function ($row) {
            Log::record('[自动校验日志] ==> beforeWrite 事件已触发。');

            $validate = new \app\common\validate\ThreecProduct;
            $errors = [];
            if (!$validate->check($row->getData())) {
                $errors = $validate->getError();
                Log::record('[自动校验日志] 校验结果: 失败。错误信息: ' . (is_array($errors) ? implode('; ', $errors) : $errors));
            } else {
                Log::record('[自动校验日志] 校验结果: 成功。');
            }

            if (!empty($errors)) {
                $row->verification_status = 'Failure';
                $row->verification_result = is_array($errors) ? implode("\n", $errors) : $errors;
            } else {
                $row->verification_status = 'Success';
                $row->verification_result = '校验通过';
            }
            Log::record('[自动校验日志] <== 校验字段已更新完毕。');
        });
    }
    
    public function getVerificationStatusList()
    {
        return ['Success' => __('Success'), 'Failure' => __('Failure')];
    }


    public function getVerificationStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['verification_status'] ?? '');
        $list = $this->getVerificationStatusList();
        return $list[$value] ?? '';
    }


    /**
     * “写入前”事件钩子，用于自动执行校验
     * @param Model $row 当前要写入的数据行对象
     */
    // in app/common/model/ThreecProduct.php

//    public static function onBeforeWrite($row)
//    {
//        // --- 日志记录开始 ---
//        Log::record('[自动校验日志] ==> onBeforeWrite 事件已触发。');
//        // 1. 获取已改变的数据
//        $changedData = $row->getChangedData();
//
//        // 2. 定义不需要触发重新校验的字段列表
//        $ignoreFields = ['verification_status', 'verification_result'];
//
//        // 3. 判断是否需要跳过校验
//        // 如果改变的字段【只有】我们忽略的字段，则跳过
//        if (!empty($changedData) && count(array_diff(array_keys($changedData), $ignoreFields)) === 0) {
//            return; // 直接退出，不执行任何校验
//        }
//
//        // --- 只要是新增数据，或修改了关键数据，就执行下面的校验逻辑 ---
//        $validate = new \app\common\validate\ThreecProduct;
//
//        $errors = [];
//        if (!$validate->check($row->getData())) {
//            $errors = $validate->getError();
//        }
//
//        if (!empty($errors)) {
//            $row->verification_status = 'Failure';
//            $row->verification_result = is_array($errors) ? implode("\n", $errors) : $errors;
//        } else {
//            $row->verification_status = 'Success';
//            $row->verification_result = '校验通过';
//        }
//    }

    public static function onBeforeWrite($row)
    {
        // --- 日志记录开始 ---
        Log::record('[自动校验日志] ==> onBeforeWrite 事件已触发。');

        $changedData = $row->getChangedData();
        // 将变化的字段数组转换为字符串以便记录
        Log::record('[自动校验日志] 检测到变化的字段: ' . json_encode($changedData, JSON_UNESCAPED_UNICODE));

        $ignoreFields = ['verification_status', 'verification_result'];

        if (!empty($changedData) && count(array_diff(array_keys($changedData), $ignoreFields)) === 0) {
            Log::record('[自动校验日志] 判断结果: 只修改了忽略字段，跳过本次校验。');
            return; // 直接退出，不执行任何校验
        }

        Log::record('[自动校验日志] 判断结果: 需要执行校验。正在实例化验证器...');
        $validate = new \app\common\validate\ThreecProduct;

        $errors = [];
        if (!$validate->check($row->getData())) {
            $errors = $validate->getError();
            Log::record('[自动校验日志] 校验结果: 失败。错误信息: ' . (is_array($errors) ? implode('; ', $errors) : $errors));
        } else {
            Log::record('[自动校验日志] 校验结果: 成功。');
        }

        if (!empty($errors)) {
            $row->verification_status = 'Failure';
            $row->verification_result = is_array($errors) ? implode("\n", $errors) : $errors;
        } else {
            $row->verification_status = 'Success';
            $row->verification_result = '校验通过';
        }
        Log::record('[自动校验日志] <== 校验字段已更新完毕。');
        // --- 日志记录结束 ---
    }

}
