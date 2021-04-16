var eventModal = $('#eventModal');
var modalTitle = $('#modal-title');
var editAllDay = $('#edit-allDay');
var editTitle = $('#edit-title');
var editStart = $('#edit-start');
var editEnd = $('#edit-end');
var editType = $('#edit-type');
var editColor = $('#edit-color');
var editDesc = $('#edit-desc');

var addBtnContainer = $('.modalBtnContainer-addEvent');
var modifyBtnContainer = $('.modalBtnContainer-modifyEvent');
var colors_type = {
  Soft:'#2277bd',
  Field:'#0da84b',
  Hard:'#9317e6',
  Event:'#f01636',
  Etc:'#f542c5'
};
var editEvent = function (event, element, view) {

    // delete-event button에 data key id에 event.id 값 입력
    // $('#delete-event').data('id', event.id);
    // popover hide
    $('.popover.fade.top').remove();
    $('.popover').popover("hide");
    // $(element).popover("hide");
    // set check on event check box
    if (event.allDay === true) {
        editAllDay.prop('checked', true);
    } else {
        editAllDay.prop('checked', false);
    }
    // check event end date
    if (event.end === null) {
        event.end = event.start;
    }
    // Allday인 경우 서버에 하루가 더해져 있는 관계로 보여줄 땐 하루를 빼고 보여줘야 한다
    if (event.allDay === true && event.end !== event.start) {
        editEnd.val(moment(event.end).subtract(1, 'days').format('YYYY-MM-DD HH:mm'))
    } else {
        editEnd.val(event.end.format('YYYY-MM-DD HH:mm'));
    }
    // set values into input or check box
    modalTitle.html('Edit Schedule');
    editTitle.val(event.title);
    editStart.val(event.start.format('YYYY-MM-DD HH:mm'));
    editDesc.val(event.desc);
    // setColor(event.color);
    //modal footer Edit 버튼 구성으로 변경
    addBtnContainer.hide();
    modifyBtnContainer.show();
    $('.selectpicker').selectpicker();
    $('.selectpicker').selectpicker('val', event.type);
    eventModal.modal('show');

    //업데이트 버튼 클릭 시
    $('#update-event').unbind();
    $('#update-event').on('click', function () {

        if (editStart.val() > editEnd.val()) {
            alert('끝나는 날짜가 앞설 수 없습니다.');
            return false;
        }

        if (editTitle.val() === '') {
            alert('작업명은 필수입니다.')
            return false;
        }

        if (editType.val() === ''){
          alert('작업 타입은 필수입니다.')
        }

        var flag = confirm('일정을 업데이트 하시겠습니까?');

        if (flag){
          var statusAllDay;
          var startDate;
          var endDate;

          if (editAllDay.is(':checked')) {
              statusAllDay = 'true';
              startDate = moment(editStart.val()).format('YYYY-MM-DD');
              // endDate = moment(editEnd.val()).format('YYYY-MM-DD');
              endDate = moment(editEnd.val()).add(1, 'days').format('YYYY-MM-DD');
          } else {
              statusAllDay = 'false';
              startDate = editStart.val();
              endDate = editEnd.val();
              // displayDate = endDate;
          }
          var selected_type = $('#edit-type option:selected').val();
          event.allDay = statusAllDay;
          event.title = editTitle.val();
          event.start = startDate;
          event.end = endDate;
          event.type = selected_type;
          event.color = colors_type[selected_type];
          event.desc = editDesc.val();

          var update_data = {
            'id':event.id,
            'title':event.title,
            'start':event.start,
            'end':event.end,
            'desc':event.desc,
            'type':event.type,
            'color':event.color,
            'allDay':event.allDay
          }
          // calendar update
          $("#calendar1").fullCalendar('updateEvent', event);
          eventModal.modal('hide');
          //일정 업데이트
          $.ajax({
            url:"/beans/phpdata/schedule/edit-event.php",
            type:"put",
            dataType:"json",
            data:update_data,
            async: false,
            success: function (data){
              //DB연동시 중복이벤트 방지를 위한
              $('#calendar1').fullCalendar('removeEvents');
              $('#calendar1').fullCalendar('refetchEvents');
              alert("일정을 변경하였습니다.");
            },
            beforeSend:function(){},
            complete:function(){},
            error: function (request, status, error){
              console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
              $('#calendar1').fullCalendar('removeEvents');
              $('#calendar1').fullCalendar('refetchEvents');
            }
          });
        }else{
          return false;
        }
    });

    //삭제 버튼 클릭 시
    $('#delete-event').unbind();
    $('#delete-event').on('click', function () {

        var flag = confirm('일정을 정말로 삭제하시겠습니까?');
        if(flag){
          $("#calendar1").fullCalendar('removeEvents', $(this).data('id'));
          eventModal.modal('hide');
          $.ajax({
            url:"/beans/phpdata/schedule/delete-event.php",
            data:{'id':event.id},
            type:"delete",
            dataType:"json",
            async: false,
            success: function (data){
              //DB연동시 중복이벤트 방지를 위한
              $('#calendar1').fullCalendar('removeEvents');
              $('#calendar1').fullCalendar('refetchEvents');
              alert("일정을 삭제하였습니다.");
            },
            beforeSend:function(){},
            complete:function(){},
            error: function (request, status, error){
              console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
              $('#calendar1').fullCalendar('removeEvents');
              $('#calendar1').fullCalendar('refetchEvents');
            }
          });
        }else{
          return false;
        }
    });
};

// function setColor(hex) {
//   $('.edit-color').each(function() {
//       //
//       // Dear reader, it's actually very easy to initialize MiniColors. For example:
//       //
//       //  $(selector).minicolors();
//       //
//       // The way I've done it below is just for the demo, so don't get confused
//       // by it. Also, data- attributes aren't supported at this time...they're
//       // only used for this demo.
//       //
//       $(this).minicolors({
//           control: $(this).attr('data-control') || 'hue',
//           defaultValue: $(this).attr('data-defaultValue') || '',
//           format: $(this).attr('data-format') || 'hex',
//           keywords: $(this).attr('data-keywords') || '',
//           inline: $(this).attr('data-inline') === 'true',
//           letterCase: $(this).attr('data-letterCase') || 'lowercase',
//           opacity: $(this).attr('data-opacity'),
//           position: $(this).attr('data-position') || 'bottom left',
//           swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
//           change: function(value, opacity) {
//               if (!value) return;
//               if (opacity) value += ', ' + opacity;
//               // if (typeof console === 'object') {
//               //     console.log(value);
//               // }
//           },
//           theme: 'bootstrap'
//       });
//       $(this).minicolors('value',hex);
//   });
// }
