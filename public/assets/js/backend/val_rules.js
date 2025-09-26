define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'val_rules/index' + location.search,
                    add_url: 'val_rules/add',
                    edit_url: 'val_rules/edit',
                    del_url: 'val_rules/del',
                    multi_url: 'val_rules/multi',
                    import_url: 'val_rules/import',
                    table: 'validation_rules',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'priority',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'rule_id', title: __('Rule_id'), operate: 'LIKE'},
                        {field: 'rule_name', title: __('Rule_name'), operate: 'LIKE'},
                        {field: 'rule_type', title: __('Rule_type'), operate: 'LIKE'},
                        {field: 'source_table', title: __('Source_table'), operate: 'LIKE'},
                        {field: 'source_field', title: __('Source_field'), operate: 'LIKE'},
                        {field: 'target_table', title: __('Target_table'), operate: 'LIKE'},
                        {field: 'target_field', title: __('Target_field'), operate: 'LIKE'},
                        {field: 'expected_value', title: __('Expected_value'), operate: 'LIKE'},
                        {field: 'error_message', title: __('Error_message'), operate: 'LIKE'},
                        {field: 'priority', title: __('Priority')},
                        {field: 'is_active', title: __('Is_active'), searchList: {"Enable":__('Enable'),"Disable":__('Disable')}, formatter: Table.api.formatter.normal},
                        {field: 'created_time', title: __('Created_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'updated_time', title: __('Updated_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
    return Controller;
});
