class JS_User {
    constructor(baseUrl, module, action = '') {
        this.baseUrl = baseUrl;
        this.module = module;
        this.action = action;
        this.urlPath = this.baseUrl + '/' + this.module + (this.action != '' ? '/' + this.action : '');
        this.currentPage = 1;
        this.perPage = 15;
        this.oFormIndex = '#frm_index';
        this.oFormAdd = '#frm_add';
    }
    /**
     * Load index
     */
    loadIndex() {
        $('.chzn-select').chosen({ height: '100%', width: '100%', search_contains: true });

        let myClass = this;
        myClass.loadList();

        $("#btn_search").click(() => myClass.search());
        $("#btn_order").click(() => myClass.order());
        $("#btn_add").click(() => myClass.create());
        $("#btn_edit").click(() => myClass.edit());
        $("#btn_delete").click(() => myClass.delete());
        $(myClass.oFormIndex).find('#category_id').change(function () {
            myClass.loadList();
        })
    }
    /**
     * Danh sách
     */
    loadList(currentPage = 1, perPage = 15) {
        let myClass = this;
        myClass.currentPage = currentPage;
        myClass.perPage = perPage;
        let url = myClass.urlPath + '/loadList';
        let data = {
            category_id: $(myClass.oFormIndex).find("#category_id").val(),
            keyword: $("#keyword").val(),
            offset: currentPage,
            limit: perPage,
        };
        Library.showloadding();
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function (result) {
                Library.hideloadding();
                $("#table-container").html(result['arrData']);
                updateColumnByRowWithBlur(myClass.constructor, myClass.action);
                $(myClass.oFormIndex).find('.main_paginate .pagination a').click(function () {
                    let page = $(this).attr('page');
                    let perPage = $('#cbo_nuber_record_page').val();
                    myClass.loadList(page, perPage);
                });
                $(myClass.oFormIndex).find('#cbo_nuber_record_page').change(function () {
                    let page = $(myClass.oFormIndex).find('#_currentPage').val();
                    let perPages = $(myClass.oFormIndex).find('#cbo_nuber_record_page').val();
                    myClass.loadList(page, perPages);
                });
                $(myClass.oFormIndex).find('#cbo_nuber_record_page').val(result['perPage']);
            }, error: function (e) {
                console.log(e);
                Library.hideloadding();
            }
        });
    }
    /**
     * Sự kiện con xảy ra
     */
    loadEvent() {
        let myClass = this;
        $("#btn_update").click(function () {
            myClass.update(false);
        });
        $("#btn_update_close").click(function () {
            myClass.update(true);
        });
        $("#name").on('input', function () {
            let str = Library.convertSlugVNtoEN($(this).val());
            $("#slug").val(str);
        });
    }
    /**
     * Màn hình thêm mới
     */
    create() {
        let myClass = this;
        let url = myClass.urlPath + '/create';
        let data = {
            category_id: $("#category_id").val(),
        };
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function (result) {
                Library.hideloadding();
                if (result['status'] == false) {
                    Library.alertMessage('danger', 'Lỗi', result['message']);
                    return;
                } else {
                    $("#addModal").html(result);
                    $("#addModal").modal('show');
                    $("#status").attr('checked', true);
                    myClass.loadEvent();
                }
            }, error: function (e) {
                console.log(e);
                Library.hideloadding();
            }
        });
    }
    /**
     * Sửa
     */
    edit() {
        let myClass = this;
        let url = myClass.urlPath + '/create';
        let listId = '';
        let chk_item_id = $('#table-data').find('input[name="chk_item_id"]');
        $(chk_item_id).each(function () {
            if ($(this).is(':checked')) {
                if (listId !== '') {
                    listId += ',' + $(this).val();
                } else {
                    listId = $(this).val();
                }
            }
        });
        if (listId == '') {
            Library.alertMessage('warning', 'Cảnh báo', 'Chọn một bản ghi để sửa!');
            return false;
        }
        if ((listId.split(',')).length > 1) {
            Library.alertMessage('warning', 'Cảnh báo', 'Chỉ được chọn một bản ghi để sửa!');
            return false;
        }
        let data = {
            _token: $("#_token").val(),
            id: listId,
        }
        Library.showloadding();
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function (result) {
                Library.hideloadding();
                if (result['status'] == false) {
                    Library.alertMessage('danger', 'Lỗi', result['message']);
                    return;
                } else {
                    $("#addModal").html(result);
                    $("#addModal").modal('show');
                    myClass.loadEvent();
                }
            }, error: function (e) {
                Library.hideloadding();
                Library.alertMessage('danger', 'Lỗi', e);
            }
        });
    }
    /**
     * Lưu thông tin
     * @return string
     */
    update(type = false) {
        let myClass = this;
        let url = myClass.urlPath + '/update';
        let order = $("#order").val();
        let data = new FormData;
        data.append('_token', $("#_token").val());
        data.append('dataUpdate', $(myClass.oFormAdd).serialize());
        Library.showloadding();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                Library.hideloadding();
                if (result['status'] == true) {
                    Library.alertMessage('success', 'Thông báo', result['message']);
                    $(myClass.oFormAdd)[0].reset();
                    $(myClass.oFormAdd).find('#order').val(parseInt(order) + 1);
                    if (type) {
                        $(".modal").modal('hide');
                        myClass.loadList(myClass.currentPage, myClass.perPage);
                    }
                } else {
                    Library.alertMessage('danger', 'Lỗi', result['message']);
                }
            }, error: function (e) {
                Library.hideloadding();
                Library.alertMessage('danger', 'Lỗi', e);
            }
        });
    }
    /**
     * Cập nhật dữ liệu theo cột
     */
    updateByColumn(id, column, value) {
        let myClass = this;
        let url = myClass.urlPath + '/updateByColumn';
        let data = new FormData;
        data.append('_token', $("#_token").val());
        data.append('id', id);
        data.append('column', column);
        data.append('value', value);
        // Library.showloadding();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                Library.hideloadding();
                if (result['status'] == true) {
                    Library.alertMessage('success', 'Thông báo', result['message']);
                } else {
                    Library.alertMessage('danger', 'Lỗi', result['message']);
                }
            }, error: function (e) {
                Library.hideloadding();
                Library.alertMessage('danger', 'Lỗi', e);
            }
        });
    }
    /**
     * Xóa thông tin
     */
    delete() {
        var myClass = this;
        var listId = '';
        var chk_item_id = $('#table-data').find('input[name="chk_item_id"]');
        $(chk_item_id).each(function () {
            if ($(this).is(':checked')) {
                if (listId !== '') {
                    listId += ',' + $(this).val();
                } else {
                    listId = $(this).val();
                }
            }
        });
        if (listId == '') {
            Library.alertMessage('warning', 'Cảnh báo', 'Chọn ít nhất một bản ghi để xoá!');
            return false;
        }
        var url = myClass.urlPath + '/delete';
        $.confirm({
            title: 'Thông báo',
            content: 'Bạn có chắc chắn muốn xóa bản ghi đã chọn!',
            type: 'red',
            closeIcon: true,
            autoClose: 'cancel|9000',
            buttons: {
                delete: {
                    btnClass: 'btn-danger',
                    text: 'Xác nhận',
                    action: function () {
                        Library.showloadding();
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: { _token: $("#_token").val(), listId: listId },
                            success: function (result) {
                                Library.hideloadding();
                                if (result['status'] == true) {
                                    Library.alertMessage('success', 'Thông báo', result['message']);
                                    myClass.loadList(myClass.currentPage, myClass.perPage);
                                } else {
                                    Library.alertMessage('danger', 'Lỗi', result['message']);
                                }
                            }, error: function (e) {
                                console.log(e);
                                Library.hideloadding();
                            }
                        });
                    }
                },
                cancel: {
                    btnClass: 'btn-default',
                    text: 'Đóng',
                    action: function () { }
                },
            }
        });
    }
    /**
     * Cập nhật số thứ tự
     */
    order() {
        let myClass = this;
        $.confirm({
            title: 'Thông báo',
            content: 'Bạn có chắc chắn muốn cập nhật lại tất cả các số thứ tự!',
            type: 'green',
            closeIcon: true,
            autoClose: 'cancel|9000',
            buttons: {
                delete: {
                    btnClass: 'btn-success',
                    text: 'Xác nhận',
                    action: function () {
                        let url = myClass.urlPath + '/updateOrder';
                        Library.showloadding();
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: { _token: $("#_token").val() },
                            success: function (result) {
                                Library.hideloadding();
                                if (result['status'] == true) {
                                    Library.alertMessage('success', 'Thông báo', result['message']);
                                    myClass.loadList(myClass.currentPage, myClass.perPage);
                                } else {
                                    Library.alertMessage('danger', 'Lỗi', result['message']);
                                }
                            }, error: function (e) {
                                Library.hideloadding();
                                Library.alertMessage('danger', 'Lỗi', e);
                            }
                        });
                    }
                },
                cancel: {
                    btnClass: 'btn-default',
                    text: 'Đóng',
                    action: function () { }
                },
            }
        });
    }
    /**
     * Thay đổi trạng thái
     */
    changeStatus(id) {
        let myClass = this;
        let url = myClass.urlPath + '/changeStatus';
        let data = {
            _token: $("#_token").val(),
            status: $("#status_" + id).is(":checked") ? 0 : 1,
            id: id,
        }
        Library.showloadding();
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (result) {
                Library.hideloadding();
                if (result['status'] == true) {
                    Library.alertMessage('success', 'Thông báo', result['message']);
                } else {
                    Library.alertMessage('danger', 'Lỗi', result['message']);
                }
            }, error: function (e) {
                Library.hideloadding();
                Library.alertMessage('danger', 'Lỗi', e);
            }
        });
    }
    /**
     * Tìm kiếm
     */
    search() {
        Library.isBlurEventAttached = false; // Sử dụng trong sự kiện onBlur
        Library.originalValue = ''; // Sử dụng trong sự kiện onBlur
        let myClass = this;
        myClass.loadList(myClass.currentPage, myClass.perPage);
    }
}