<?php

namespace app\common\model;

use think\Model;


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




}
