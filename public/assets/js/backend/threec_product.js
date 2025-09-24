define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'threec_product/index' + location.search,
                    add_url: 'threec_product/add',
                    edit_url: 'threec_product/edit',
                    del_url: 'threec_product/del',
                    multi_url: 'threec_product/multi',
                    import_url: 'threec_product/import',
                    import_log_url: 'import/log/add',
                    table: 'treecproduct',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'store_name', title: __('Store_name'), operate: 'LIKE'},
                        {field: 'platform_order_id', title: __('Platform_order_id'), operate: 'LIKE'},
                        {field: 'merchant_order_id', title: __('Merchant_order_id'), operate: 'LIKE'},
                        {field: 'category', title: __('Category'), operate: 'LIKE'},
                        {field: 'energy_efficiency_level', title: __('Energy_efficiency_level'), operate: 'LIKE'},
                        {field: 'brand', title: __('Brand'), operate: 'LIKE'},
                        {field: 'product_model', title: __('Product_model'), operate: 'LIKE'},
                        {field: 'order_amount', title: __('Order_amount'), operate:'BETWEEN'},
                        {field: 'actual_paid_amount', title: __('Actual_paid_amount'), operate:'BETWEEN'},
                        {field: 'transaction_time', title: __('Transaction_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'gov_subsidy_amount', title: __('Gov_subsidy_amount'), operate:'BETWEEN'},
                        {field: 'sn_image_url', title: __('Sn_image_url'), operate: 'LIKE',
                            buttons:[
                                {
                                    name: 'sn_ocr',
                                    title: __('SN图片OCR识别'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'threec_product/sn_ocr?field=sn_image_url&title=SN图片\'',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ],
                            table: table, formatter: Table.api.formatter.operate
                           },
                        {field: 'inspection_image_url', title: __('Inspection_image_url'),
                            buttons:[
                                {
                                    name: 'sn_ocr',
                                    title: __('验机图片OCR识别'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'threec_product/sn_ocr?field=inspection_image_url&title=验机图片\'',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ],
                            table: table, formatter: Table.api.formatter.operate,
                            exportFormatter: function (value, row, index) {
                                console.log("SN img");
                                console.log(row.sn_image_url);
                                return row.sn_image_url;
                            }
                        },
                        {field: 'screen_on_image_url', title: __('Screen_on_image_url'),
                            buttons:[
                                {
                                    name: 'sn_ocr',
                                    title: __('正面亮屏幕OCR识别'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'threec_product/sn_ocr?field=screen_on_image_url',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ],
                            table: table, formatter: Table.api.formatter.operate
                        },
                        {field: 'ean_code', title: __('Ean_code'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'serial_number', title: __('Serial_number'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'imei1', title: __('Imei1'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'imei2', title: __('Imei2'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'invoice_number', title: __('Invoice_number'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'invoice_date', title: __('Invoice_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'invoice_url', title: __('Invoice_url'),
                            buttons:[
                                {
                                    name: 'sn_ocr',
                                    title: __('发票链接OCR识别'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'threec_product/sn_ocr?field=invoice_url',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ],
                            table: table, formatter: Table.api.formatter.operate
                        },
                        {field: 'invoice_header', title: __('Invoice_header'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'invoice_amount', title: __('Invoice_amount'), operate:'BETWEEN'},
                        {field: 'seller_tax_id', title: __('Seller_tax_id'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'tracking_number', title: __('Tracking_number'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'tracking_map_url', title: __('Tracking_map_url'),
                            buttons:[
                                {
                                    name: 'sn_ocr',
                                    title: __('物流轨迹图OCR识别'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'threec_product/sn_ocr?field=tracking_map_url',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },
                            ],
                            table: table, formatter: Table.api.formatter.operate
                        },
                        {field: 'shipping_company', title: __('Shipping_company'), operate: 'LIKE'},
                        {field: 'recipient_province', title: __('Recipient_province'), operate: 'LIKE'},
                        {field: 'recipient_city', title: __('Recipient_city'), operate: 'LIKE'},
                        {field: 'recipient_district', title: __('Recipient_district'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'recipient_address', title: __('Recipient_address'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'delivery_time', title: __('Delivery_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'verification_status', title: __('Verification_status'), searchList: {"Success":__('Success'),"Failure":__('Failure')}, formatter: Table.api.formatter.status},
                        // {field: 'operate', title: __('Operate'), table: table,events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    //fast已经帮助我们引入了layer并暴露在window对象上
    $(document).on("click", "#sn-image-native", function () {
        // 1. 直接从被点击的图片(`this`)的 `src` 属性获取URL
        var imageUrl = $(this).attr('src');

        // 2. 确保我们成功获取到了URL
        if (!imageUrl) {
            console.error("无法获取图片URL!");
            return;
        }

        // 3. 调用Layer.photos，数据源直接使用我们获取到的URL
        //    就像您代码注释里提到的，使用 window.top.Layer 可以确保弹窗在最外层打开
        var Layer = window.top.Layer || window.Layer;
        Layer.photos({
            photos: {
                "start": 0, // 因为只有一张图，所以从第一张（索引0）开始
                "data": [
                    {
                        "src": imageUrl
                    }
                ]
            },
            anim: 5 // 指定动画类型
        });
    });
    return Controller;
});
