/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */,
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(3);


/***/ }),
/* 3 */
/***/ (function(module, exports) {


$('#createRoom').on('click', function () {

    $('.addForm')[0].reset();
    $('.picker').val(moment(new Date()).format("YYYY-MM-DD"));
    $('.picker2').val(moment(new Date()).format("YYYY-MM-DD"));
    var startTime = $('#startTime');
    var endTime = $('#endTime');
    startTime.val(moment(new Date(), "h:mm:ss").format("hh:mm A"));
    endTime.val(moment(new Date(), "h:mm:ss").add(10, 'minutes').format("hh:mm A"));

    $('#myModal').modal('show');
});
$('.btn-manage').on('click', function () {
    var id = $(this).data('task');

    url = url.replace(':id', id);

    $('.manageForm')[0].reset();
    $.get(url, function (data) {

        action = action.replace(':id', data.result.id);
        $('.manageForm').prop('action', action);
        startDate = moment(new Date(data.result.start_date)).format("YYYY-MM-DD");
        endDate = moment(new Date(data.result.end_date)).format("YYYY-MM-DD");
        startTime = moment(new Date(data.result.start_date), "h:mm:ss").format("hh:mm A");
        endTime = moment(new Date(data.result.end_date), "h:mm:ss").format("hh:mm A");
        $('#edit-room-name').val(data.result.name);
        $('#edit-max-people').val(data.result.maximum_people);
        $('.editPicker').val(startDate);
        $('.editPicker2').val(endDate);
        $('.startTime').val(startTime);
        $('.endTime').val(endTime);
        $('.meeting_description').val(data.result.meeting_description);
        $('.welcome_message').val(data.result.welcome_message);

        if (data.result.mute_on_join) {
            $('.mute_on_join').prop("checked", "checked");
        }

        if (data.result.require_moderator_approval) {
            $('.require_moderator_approval').prop("checked", "checked");
        }

        $(".meeting_record option").each(function () {

            if ($(this).val() == data.result.meeting_record) $(this).attr("selected", "selected");
        });
        url = url.replace(id,':id');
        $('#editModal').modal('show');
    });
});

$('#modal').on('click', '.advanceSettings', function () {
    $('.advancedOptions').slideToggle();
});

dateTimePickers();
function dateTimePickers() {
    $('#modal').find('#myModal,#editModal').find('.picker').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'Y-m-d'
        // formatDate


    });

    $('#modal').find('#myModal,#editModal').find('.picker2').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'Y-m-d' // formatDate

    });

    // $('#startTime').timepicker()({
    //     // autoclose: true,
    //     // twelvehour: true,
    //     // placement: 'bottom',
    //     // align: 'left',
    //     // vibrate: true
    //
    // });
    //
    // $('#endTime').timepicker()({
    //     // autoclose: true,
    //     // twelvehour: true,
    //     // placement: 'top',
    //     // align: 'left',
    //     // vibrate: true
    //
    // });
}
$('.btnDeleteConfirm').on('click', function () {

    id = $(this).data('item');
    deleteData(id);
});
function deleteData(id) {
    action = action.replace(':id', id);
    $("#deleteForm").prop('action', action);
}
$('.btnDelete').on('click', function () {
    $("#deleteForm").submit();
});

/***/ })
/******/ ]);