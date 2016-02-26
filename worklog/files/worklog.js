//TODO:config it
var weeklog_content_template = {
    'type-1': '##原计划工作内容\n' +
    ' - \n' +
    '##实际工作内容\n' +
    ' - \n' +
    '##工作成果说明\n' +
    ' - \n' +
    '##完成率\n' +
    ' - \n' +
    '##下周计划和资源需求'
};

function weeklog_page_add_onLogTypeChange(logType) {
    var contentEl = $('textarea[name="content"]');
    var tpl;
    if ((tpl = weeklog_content_template['type-' + logType]) && contentEl.val() != tpl) {
        if (!$.trim(contentEl.val()) || confirm('是否清空内容?')) {
            contentEl.val(weeklog_content_template['type-' + logType]);
        }
    }
}
$(document).ready(function () {
    $("input[name=log_begin]:not(:read-only),input[name=log_end]:not(:read-only)")
        .datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true});


});
