<?php

namespace app\common\validate;

use think\Validate;

class ThreecProduct extends Validate
{
    /**
     * 验证规则
     */
//    protected $rule = [
//    ];
    protected $rule = [
        'store_name'          => 'require|in:泉州优品家享家居有限公司,泉州小米景明科技有限公司',
        'category'            => 'require|in:手机,平板,智能手表手环',
        'order_amount'        => 'require|number|regex:/^\d+(\.\d{1,2})?$/|elt:6000',
        'gov_subsidy_amount'  => 'require|number|regex:/^\d+(\.\d{1,2})?$/|checkGovSubsidy', // 自定义规则
        'actual_paid_amount'  => 'require|number|regex:/^\d+(\.\d{1,2})?$/|checkActualPaid',  // 自定义规则
        'transaction_time'    => 'require|date|after:2024-12-31|before:2026-01-01',
        'delivery_time'       => 'require|date|after:2024-12-31|before:2026-01-16',
        'brand'               => 'require',
        'product_model'       => 'require',
        'tracking_number'     => 'require',
        'recipient_address'   => 'require',
        'recipient_province'  => 'require|in:福建省',
    ];

    /**
     * 提示消息
     */
//    protected $message = [
//    ];
    protected $message = [
        'store_name.in'           => '店铺名称不符合补贴标准',
        'category.in'             => '品类不符合补贴标准',
        'order_amount.elt'        => '订单金额不符合补贴标准，必须不超6000元',
        'order_amount.regex'      => '订单金额格式不正确，最多保留两位小数',
        'gov_subsidy_amount.regex'=> '政府补贴金额格式不正确，最多保留两位小数',
        'gov_subsidy_amount.checkGovSubsidy' => '政府补贴金额计算不匹配', // 自定义消息
        'actual_paid_amount.regex'=> '实付金额格式不正确，最多保留两位小数',
        'actual_paid_amount.checkActualPaid' => '实付金额计算不匹配', // 自定义消息
        'transaction_time.after'  => '交易时间不符合补贴标准，必须在2025年1月1日之后',
        'transaction_time.before' => '交易时间不符合补贴标准，必须在2025年12月31日之前',
        'delivery_time.after'     => '签收时间不符合补贴标准，必须在2025年1月1日之后',
        'delivery_time.before'    => '签收时间不符合补贴标准，必须在2026年1月15日之前',
        'brand.require'               => '品牌为空，不符合补贴标准',
        'product_model.require'       => '商品型号为空，不符合补贴标准',
        'tracking_number.require'     => '物流单号为空，不符合补贴标准',
        'recipient_address.require'   => '收货地址为空，不符合补贴标准',
        'recipient_province.in'   => '收货省不符合补贴标准，必须为福建省',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    /**
     * 自定义验证规则：校验政府补贴金额 (高精度+四舍五入)
     */
    protected function checkGovSubsidy($value, $rule, $data)
    {
        if (!isset($data['order_amount']) || !is_numeric($data['order_amount'])) {
            return '缺少订单金额，无法计算政府补贴';
        }

        // 1. 使用 bcmul 进行高精度乘法，保留4位小数以确保中间过程的精度
        $expectedSubsidy = bcmul((string)$data['order_amount'], '0.15', 4);

        // 2. 封顶500
        if (bccomp($expectedSubsidy, '500', 4) > 0) {
            $expectedSubsidy = '500.00';
        }

        // 3. 将计算结果四舍五入到2位小数，用于最终比较
        $roundedExpected = round($expectedSubsidy, 2);

        // 4. 将传入的值也转换为统一的2位小数值进行比较
        $valueFormatted = round(floatval($value), 2);

        if ($valueFormatted != $roundedExpected) {
            return "政府补贴金额原始值为:{$value}, 计算值为:{$roundedExpected}, 两者不相等";
        }
        return true;
    }

    /**
     * 自定义验证规则：校验实付金额 (高精度)
     */
    protected function checkActualPaid($value, $rule, $data)
    {
        if (!isset($data['order_amount']) || !is_numeric($data['order_amount']) || !isset($data['gov_subsidy_amount']) || !is_numeric($data['gov_subsidy_amount'])) {
            return '缺少订单金额或政府补贴金额，无法计算实付金额';
        }

        // 1. 使用 bcsub 进行高精度减法，保留4位小数
        $expectedPaid = bcsub((string)$data['order_amount'], (string)$data['gov_subsidy_amount'], 4);

        // 2. 将计算结果四舍五入到2位小数
        $roundedExpected = round($expectedPaid, 2);

        // 3. 将传入的值也转换为统一的2位小数值进行比较
        $valueFormatted = round(floatval($value), 2);

        if ($valueFormatted != $roundedExpected) {
            return "实付金额原始值为:{$value}, 计算值为:{$roundedExpected}, 两者不相等";
        }
        return true;
    }
}
