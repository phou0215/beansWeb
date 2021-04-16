var eventModal = $('#eventModal');
var modalTitle = $('#modal-title');
var editAllDay = $('#edit-allDay');
var editTitle = $('#edit-title');
var editStart = $('#edit-start');
var editEnd = $('#edit-end');
// var editColor = $('#edit-color');
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

var addEvent = function (start, end) {

    $('.popover').popover("hide");
    modalTitle.html('Add Schedule');
    // resetColor();
    // editAllDay.prop('checked', false);
    editTitle.val('');
    editStart.val(start);
    editEnd.val(end);
    editDesc.val('');

    addBtnContainer.show();
    modifyBtnContainer.hide();
    $('.selectpicker').selectpicker();
    $('.selectpicker').selectpicker('val', 'Soft');
    eventModal.modal('show');

    //새로운 일정 저장버튼 클릭
    $('#save-event').unbind();
    $('#save-event').on('click', function () {
        var selected_type = $('#edit-type option:selected').val();
        console.log(selected_type);
        console.log(colors_type[selected_type]);
        var new_event = {
            title: editTitle.val(),
            start: editStart.val(),
            end: editEnd.val(),
            desc: editDesc.val(),
            type: selected_type,
            color: colors_type[selected_type],
            allDay: 'false'
        };

        if (new_event.start > new_event.end) {
            alert('끝나는 날짜가 앞설 수 없습니다.');
            return false;
        }
        if (new_event.title === '') {
            alert('작업명은 필수입니다.');
            return false;
        }

        if (new_event.type === ''){
          alert('작업 타입 입력은 필수 입니다.')
          return false;
        }
        var flag = confirm("저장할까요?");
        //set start and end values depend on date All day option
        if(flag){
          var realEndDay;
          if (editAllDay.is(':checked')) {
              new_event.start = moment(new_event.start).format('YYYY-MM-DD');
              //render시 날짜표기수정
              new_event.end = moment(new_event.end).add(1, 'days').format('YYYY-MM-DD');
              // //DB에 넣을때(선택)
              // realEndDay = moment(new_event.end).format('YYYY-MM-DD');
              new_event.allDay = 'true';
          }

          $("#calendar1").fullCalendar('renderEvent', new_event, true);
          eventModal.find('input, textarea').val('');
          editAllDay.prop('checked', true);
          eventModal.modal('hide');
          //Add new Event to DB through on add-schedule.php
          $.ajax({
            url:"/beans/phpdata/schedule/add-event.php",
            type:"post",
            dataType:"json",
            data:new_event,
            async: false,
            success: function (data){
              //DB연동시 중복이벤트 방지를 위한
              $('#calendar1').fullCalendar('removeEvents');
              $('#calendar1').fullCalendar('refetchEvents');
              alert("일정을 추가하였습니다.");
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

// function resetColor() {
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
//       $(this).minicolors('value','#ff6161');
//   });
// }
