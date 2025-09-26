<?php

namespace app\common\model;

use think\Model;


class ValRules extends Model
{

    

    

    // 表名
    protected $name = 'validation_rules';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'is_active_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            if (!$row['priority']) {
                $pk = $row->getPk();
                $row->getQuery()->where($pk, $row[$pk])->update(['priority' => $row[$pk]]);
            }
        });
    }

    
    public function getIsActiveList()
    {
        return ['Enable' => __('Enable'), 'Disable' => __('Disable')];
    }


    public function getIsActiveTextAttr($value, $data)
    {
        $value = $value ?: ($data['is_active'] ?? '');
        $list = $this->getIsActiveList();
        return $list[$value] ?? '';
    }




}
