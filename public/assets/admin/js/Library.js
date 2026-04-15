class Library {
	constructor() {
		this.isBlurEventAttached = false; // Sử dụng trong sự kiện onBlur
		this.originalValue = ''; // Sử dụng trong sự kiện onBlur

	}
	/** Hiển thị loading */
	showloadding() {
		$(".main_loadding").show();
	}
	/** Ẩn loading */
	hideloadding() {
		$("#loadding").hide();
		$(".main_loadding").hide();
	}
	/** Hiện thị thông báo client */
	alertMessageFrontend(type, label, message, s = 3000) {
		// var vclass = 'alert alert-' + type;
		var vclass = 'border-start border-' + type;

		iclass = 'fa-solid text-' + type;
		mclass = 'text-' + type;
		lclass = 'text-' + type;
		let arrCheck = ['primary', 'secondary', 'success', 'light'];
		let arrError = ['warning', 'dark', 'white'];
		if (arrCheck.includes(type)) {
			iclass += ' fa-circle-check';
		} else if (arrError.includes(type)) {
			iclass += ' fa-circle-exclamation';
		} else if (type == 'danger') {
			iclass += ' fa-circle-xmark';
		} else if (type == 'info') {
			iclass += ' fa-circle-info';
		}
		$("#message-icon").removeClass();
		$("#message-icon").addClass(iclass);

		$("#message-label").removeClass();
		$("#message-label").addClass(lclass);
		$("#message-label").html(label);

		$("#message-infor").html(message);

		$("#message-alert").alert();
		$("#message-alert").removeClass();
		$("#message-alert").addClass(vclass);
		$("#message-alert").fadeTo(s, 500).slideUp(500, function () {
			$("#message-alert").slideUp(500);
		})
	}
	/** Hiển thị thông báo */
	alertMessage(type, label, message, s = 3000) {
		$.toast({
			title: label,
			content: message,
			type: type,
			delay: s,
			dismissible: true,
			positionDefaults: 'top-left'
		});
		$(document).on('hidden.bs.toast', '.toast', function (e) {
			$(this).remove();
		});
	}
	/** Chuyển đổi dữ liệu */
	convertVNtoEN(str) {
		str = str.toLowerCase();
		str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		str = str.replace(/đ/g, "d");
		str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, ""); // Huyền sắc hỏi ngã nặng
		str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // Â, Ê, Ă, Ơ, Ư
		str = str.replace(/ /g, "-");
		str = str.replace(/[`~!@#$%^&*()_+=\[\]{};:'"\|<>,.\/\\?]/g, "");
		str = str.replaceAll(/-/g, "");
		return str;
	}
	/** Chuyển đổi dữ liệu */
	convertSlugVNtoEN(str) {
		let myClass = this;
		str = str.toLowerCase();
		str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		str = str.replace(/đ/g, "d");
		str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, ""); // Huyền sắc hỏi ngã nặng
		str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // Â, Ê, Ă, Ơ, Ư
		str = str.replace(/ /g, "-");
		str = str.replace(/[`~!@#$%^&*()_+=\[\]{};:'"\|<>,.\/\\?]/g, "");
		str = str.replaceAll(/-+/g, "-");
		return str;
	}
	/** Load TinyMCE */
	loadTinyMCE(boolen = true) {
		if (boolen) {
			$(document).ready(function () {
				tinymce.init({
					selector: 'textarea#note',
					license_key: 'gpl',
					directionality: 'ltr',
					language_url: '/assets/tinymce/langs/vi_VN.js',
					language: 'vi_VN',
					plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap nonbreaking insertdatetime lists wordcount help charmap quickbars emoticons',
					mobile: {
						plugins: 'preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap nonbreaking insertdatetime lists wordcount help charmap quickbars emoticons',
					},
					promotion: false,
					menu: {
						tc: {
							title: 'Comments',
							items: 'addcomment showcomments deleteallconversations'
						}
					},
					setup: function (editor) {
						// Add a custom event handler for the 'BeforeRenderUI' event
						editor.on('BeforeRenderUI', function () {
							// Add the CSRF token to TinyMCE's settings
							editor.settings.headers = {
								'X-CSRF-TOKEN': csrfToken
							};
						});
					},
					menubar: 'file edit view insert format tools table tc help',
					toolbar: "undo redo | aidialog aishortcuts | blocks fontsizeinput | bold italic | align numlist bullist | link image | table media | lineheight  outdent indent | strikethrough forecolor backcolor formatpainter removeformat | charmap emoticons | code fullscreen preview | save print | footnotes mergetags | addtemplate inserttemplate | addcomment showcomments | ltr rtl | spellcheckdialog a11ycheck", // Note: if a toolbar item requires a plugin, the item will not present in the toolbar if the plugin is not also loaded.
					autosave_ask_before_unload: true,
					autosave_interval: '30s',
					autosave_prefix: '{path}{query}-{id}-',
					autosave_restore_when_empty: false,
					autosave_retention: '2m',
					image_advtab: true,
					typography_ignore: ['code'],
					importcss_append: true,
					height: 467,
					image_caption: true,
					quickbars_selection_toolbar: false,
					quickbars_insert_toolbar: false,
					noneditable_class: 'mceNonEditable',
					toolbar_mode: 'sliding',
					spellchecker_ignore_list: ['Ephox', 'Moxiecode', 'tinymce', 'TinyMCE'],
					tinycomments_mode: 'embedded',
					content_style: '.mymention{ color: gray; }',
					contextmenu: 'link image table configurepermanentpen',
					a11y_advanced_options: true,
					branding: false,
					automatic_uploads: false,
					images_upload_handler: function (blobInfo, success, failure) {
						return new Promise((resolve, reject) => {
							var formData = new FormData();
							formData.append('file', blobInfo.blob(), blobInfo.filename());
							formData.append('_token', $("#_token").val());
							fetch(window.location.origin + '/system/files/uploads', {
								method: 'POST',
								body: formData
							})
								.then(response => response.json())
								.then(data => {
									success(data.location);
									// Đóng thẻ thông báo upload ảnh
									document.querySelectorAll('.tox-notifications-container').forEach(el => el.remove());
								})
								.catch(() => failure('Upload failed'));
						});
					},
					file_picker_types: 'image',
					file_picker_callback: function (callback, value, meta) {
						var input = document.createElement('input');
						input.type = 'file';
						input.accept = 'image/*';
						input.onchange = function () {
							var file = input.files[0];
							var formData = new FormData();
							formData.append('file', file);
							formData.append('_token', $("#_token").val());

							fetch(window.location.origin + '/system/files/uploads', { method: 'POST', body: formData })
								.then(response => response.json())
								.then(data => callback(data.location))
								.catch(() => alert('Upload failed'));
						};
						input.click();
					}
				});
			});
		} else {
			setTimeout(function () {
				tinymce.remove('textarea#note');
			}, 200);
		}
	}
}

Library = new Library();

function select_row(obj) {
	var oTable = $(obj).parent().parent().parent();
	$(oTable).find('td').parent().removeClass('selected');
	$(oTable).find('td').parent().find('input[name="chk_item_id"]').prop('checked', false);
	$(obj).parent().addClass('selected');
	var attDisabled = $(obj).parent().find('input[name="chk_item_id"]').prop('disabled');
	if (typeof (attDisabled) === 'undefined' || attDisabled == '') {
		$(obj).parent().find('input[name="chk_item_id"]').prop('checked', true);
		$(obj).parent().find('input[name="chk_item_id"]').prop('checked', 'checked');
	}
}
function checkbox_all_item_id(p_chk_obj) {
	var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
	var v_count = p_chk_obj.length;
	//remove class cua tat ca cac tr trong table
	if ($('[name="chk_all_item_id"]').is(':checked')) {
		$(p_chk_obj).each(function () {
			$(this).prop('checked', true);
			$(this).parent().parent().addClass('selected');
		});
	} else {
		$(p_chk_obj).each(function () {
			$(this).prop('checked', false);
			$(this).parent().parent().removeClass('selected');
		});
	}
}
function checkbox_all_item_id_delete(p_chk_obj) {
	var p_chk_obj = $('#table-data-delete').find('input[name="chk_item_id"]');
	var v_count = p_chk_obj.length;
	//remove class cua tat ca cac tr trong table
	if ($('[name="chk_all_item_id"]').is(':checked')) {
		$(p_chk_obj).each(function () {
			$(this).prop('checked', true);
			$(this).parent().parent().addClass('selected');
		});
	} else {
		$(p_chk_obj).each(function () {
			$(this).prop('checked', false);
			$(this).parent().parent().removeClass('selected');
		});
	}
}
function select_checkbox_row(obj) {
	if (obj.checked) {
		$(obj).parent().parent().addClass('selected');
		$(obj).prop('checked', true);
		$(obj).prop('checked', 'checked');
	}
	else {
		$(obj).parent().parent().removeClass('selected');
		$(obj).prop('checked', false);
	}
}
/**
 * Hiển thị hình ảnh
 * @param _this Đối tượng
 */
//function showImage(_this) {
//	//const files = input.files;
//	//if (!files || files.length) {
//	//	return;
//	//}

//	console.log($(_this)[0].files[0]);

//    var reader = new FileReader();
//    var img = document.createElement("img");
//    reader.readAsDataURL($(_this)[0].files[0]);
//    reader.onload = function () {
//        var dataURL = reader.result;
//        img.src = dataURL;
//        img.style.width = '100%';
//        img.style.height = '100%';
//        //img.style.padding = '5px';
//    };
//    let span = document.createElement("span");
//    span.appendChild(img); // Thêm ảnh vào span
//    $("#show_images").html(span); // Thêm span vào danh sách ảnh
//}

function showImage(input, elementPreview = 'preview-image') {
	const files = input.files;
	const classElementPreview = `.${elementPreview}`;
	if (!files) {
		input.val(null);
		$(classElementPreview).html('').removeClass('preview');
		return;
	}

	var reader = new FileReader();
	var img = document.createElement("img");
	reader.readAsDataURL(files[0]);
	reader.onload = function () {
		var dataURL = reader.result;
		img.src = dataURL;
		img.style.width = '100%';
		img.style.height = '100%';
	};
	let span = document.createElement("span");
	span.appendChild(img); // Thêm ảnh vào span
	$(classElementPreview).html(span).addClass('preview'); // Thêm span vào danh sách ảnh
}


/**
 * Hiển thị danh sách hình ảnh
 * @param _this Đối tượng
 */
function showImageList(_this, width = 100, height = 100) {
	$("#show_images_list").html('');
	$.each($(_this)[0].files, function (i, file) {
		var reader = new FileReader();
		var img = document.createElement("img");
		reader.onloadend = function () {
			img.id = "images_list" + (i + 1);
			img.src = reader.result;
			img.style.maxWidth = '100%';
			img.style.maxHeight = '100%';
			img.style.padding = '5px';
		};
		reader.readAsDataURL(file);

		let span = document.createElement("span");
		span.style.width = width + "px";
		span.style.height = height + "px";
		span.style.border = "1px solid #ccc";
		span.style.marginBottom = "5px";
		span.style.display = 'flex';
		span.style.alignItems = 'center';
		span.style.justifyContent = 'center';

		span.appendChild(img); // Thêm ảnh vào span
		$("#show_images_list").append(span); // Thêm span vào danh sách ảnh
	});
}
/**
 * Định dạng số theo dấu chấm (96.20253164556962 -> 96.2)
 * @param {*} number Số truyền vào
 */
function formatNumber(number, n = 1) {
	return number.toFixed(n);
}
/**
 * Định dạng số theo dấu phẩy (1234567 -> 1,234,567)
 * @param {*} number Số truyền vào
 */
function formatNumberByComma(number) {
	let str = number.toString().replace(',', '');
	return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
/**
 * Định dạng số theo dấu chấm (1234567 -> 1.234.567)
 * @param {*} number Số truyền vào
 */
function formatNumberByDot(number) {
	let str = number.toString().replaceAll('.', '');
	return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

