var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];
var draggedEventIsAllDay;
var activeInactiveWeekends = true;
var ele_filePath = $('#file_path');
var start_date = null;
var end_date = null;

$(document).ready(function() {
        // /* initialize the external events
        // -----------------------------------------------------------------*/
        ele_filePath.val();
        $('#external-events .fc-event').each(function() {

                  // store data so the calendar knows to render an event upon drop
                  $(this).data('event', {
                      title: $.trim($(this).text()), // use the element's text as the event title
                      stick: true // maintain when user navigates (see docs on the renderEvent method)
                  });

                  // make the event draggable using jQuery UI
                  $(this).draggable({
                      zIndex: 999,
                      revert: true, // will cause the event to go back to its
                      revertDuration: 0 //  original position after the drag
                  });

              });
        // /* initialize the calendar
        // -----------------------------------------------------------------*/
        // hide dialog
        $('#calendar1').fullCalendar({
            header: {
                // agendaWeek ,agendaDay
                left: 'prev,next today hideweekend',
                center: 'title',
                right: 'month, listWeek'
            },
            /* Set fullCalendar options
            -----------------------------------------------------------------*/
            // defaultDate: '2018-03-12',
            locale: 'ko',
            timezone: "local",
            // nextDayThreshold: "09:00:00",
            allDaySlot: true,
            displayEventTime: false,
            displayEventEnd: true,
            defaultView: 'month',
            firstDay: 0,// Monday first
            weekNumbers: false,
            weekNumberCalculation: "ISO",
            weekends: true,
            eventLimit: true,
            views: {
              month : { eventLimit : 12 } // max event 12, rest displayed '+'
            },
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            selectable: true,
            selectHelper: true,
            height: 650,
            eventLimitClick: 'week', //popover
            timeFormat: 'HH:mm',
            defaultTimedEventDuration:'01:00:00',
            minTime: '00:00:00',
            maxTime: '24:00:00',
            slotLabelFormat: 'HH:mm',
            nowIndicator: false,
            dayPopoverFormat: 'MM/DD dddd',
            longPressDelay: 0,
            eventLongPressDelay: 0,
            selectLongPressDelay: 0,
            customButtons: {
              //hide weekend
              hideweekend : {
                text  : 'hide',
                click : function() {
                  activeInactiveWeekends ? activeInactiveWeekends = false : activeInactiveWeekends = true;
                  $('#calendar1').fullCalendar('option', {
                    weekends: activeInactiveWeekends
                  });
                }
              }
            },
            events: '/beans/phpdata/schedule/schedule.php',
            select: function(start, end, jsEvent, view){
              var flag = confirm('일정을 추가하시겠습니까?');
              if (flag){
                var today = moment();
                //case of month view
                if (view.name == "month"){
                  start.set({
                    hours: today.hours(),
                    minute: today.minutes()
                  });
                  start = moment(start).format('YYYY-MM-DD HH:mm');
                  end = moment(end).subtract(1, 'days');
                  end.set({
                    hours: today.hours() + 1,
                    minute: today.minutes()
                  });
                  end = moment(end).format('YYYY-MM-DD HH:mm');
                }else{
                  start = moment(start).format('YYYY-MM-DD HH:mm');
                  end = moment(end).format('YYYY-MM-DD HH:mm');
                }
                //add popup menu display
                addEvent(start, end);
                $('#calendar1').fullCalendar('unselect');
              }
              $('#calendar1').fullCalendar('unselect');
            },
            dayRender: function (date, cell) {
              cell.css("background-color", "rgba(255,255,255,0.8)");
            },
            eventRender:function(event, element, view){

              //일정에 hover시 요약
              var start_date;
              var end_date;
              var start_time;
              var end_time;

              if (event.allDay){
                start_date =  moment(event.start).format('YYYY-MM-DD');
                end_date = moment(event.end).subtract(1, 'days').format('YYYY-MM-DD');
                start_time = '';
                end_time = '';
              }else{
                start_date =  moment(event.start).format('YYYY-MM-DD');
                end_date = moment(event.end).format('YYYY-MM-DD');

                var dates = getDisplayEventDate(event);
                start_time = dates[0];
                end_time = dates[1];
              }
              element.find('.fc-title').html('[' + event.type + ']&nbsp'+ event.title);
              element.find('.fc-content').css('text-align','center');
              element.popover({
                title: $('<div />', {
                  class: 'popoverTitleCalendar',
                  text: '[' + event.type + '] '+ event.title
                }).css({
                  'background': event.color,
                  'color': 'white',
                }),
                content: $('<div />', {
                    class: 'popoverInfoCalendar'
                  }).append('<p><strong>구분:</strong> ' + event.type + '</p>')
                  .append('<p><strong>시작:</strong> ' + start_date +' '+ start_time + '</p>')
                  .append('<p><strong>종료:</strong> ' + end_date +' '+ end_time + '</p>')
                  .append('<div class="popoverDescCalendar"><strong>설명:</strong></br>' + descDisplay(event.desc) + '</div>'),
                delay: {
                  show: "800",
                  hide: "50"
                },
                trigger: 'hover',
                placement: 'top',
                html: true,
                container: 'body'
              });
            },
            eventDragStart: function (event, jsEvent, ui, view) {
              draggedEventIsAllDay = event.allDay;
            },
            eventAfterAllRender: function (view) {
              if (view.name == "month"){
                $(".fc-content").css('height', 'auto');
              }
            },
            eventResize: function(event, delta, revertFunc, jsEvent, ui, view ) {

              $('.popover.fade.top').remove();
              $('.popover').popover("hide");
              var reformDate = calDateWhenResize(event);
              var title = event.title;
              var type = event.type;
              var id = event.id;
              var desc = event.desc;
              var color = event.color;
              var allDay = event.allDay;

              $.ajax({
                url:"/beans/phpdata/schedule/edit-event.php",
                type:"put",
                dataType:"json",
                data:{"start":reformDate.start, "end":reformDate.end, "title":title, "type":type, "id":id, "desc":desc, "color":color, "allDay":allDay},
                async: true,
                success: function (data){
                  $('.popover').popover("hide");
                  alert(event.title + " 일정 " +reformDate.start+ " ~ " +reformDate.displayEnd+ "으로 변경완료");
                },
                beforeSend:function(){},
                complete:function(){},
                error: function (request, status, error){
                  $('.popover').popover("hide");
                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                }
              });
            },
            eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {

              $('.popover').popover("hide");
              $('.popover.fade.top').remove();
              var reformDate = calDateWhenDragnDrop(event);

              //This only when week,day view set disabled from allday to schedule time
              if (view.type === 'agendaWeek' || view.type === 'agendaDay') {
                if (draggedEventIsAllDay !== event.allDay) {
                  alert('드래그앤드롭으로 종일<->시간 변경은 불가합니다.');
                  location.reload();
                  return false;
                }
              }

              var title = event.title;
              var type = event.type;
              var id = event.id;
              var color = event.color;
              var allDay = event.allDay;
              var desc = event.desc;

              $.ajax({
                url:"/beans/phpdata/schedule/edit-event.php",
                type:"put",
                dataType:"json",
                data:{"start":reformDate.start, "end":reformDate.end, "title":title, "type":type, "id":id, "desc":desc, "color":color, "allDay":allDay},
                async: false,
                success: function (data){
                  $('.popover').popover("hide");
                  alert(event.title + " 일정 " +reformDate.start+ " ~ " +reformDate.displayEnd+ "으로 변경완료");
                },
                beforeSend:function(){},
                complete:function(){},
                error: function (request, status, error){
                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                  $('.popover').popover("hide");
                }
              });
            },
            viewRender: function(view, element) {
              start_date = $('#calendar1').fullCalendar('getView').start.format();
              end_date = $('#calendar1').fullCalendar('getView').end.format();
            },
            eventClick: function (event, element, view) {
              editEvent(event, element, view);
            },
        });

        // ==============================================================
        // minicolor select script
        // ==============================================================
        //
        // $('.edit-color').each(function() {
        //     //
        //     // Dear reader, it's actually very easy to initialize MiniColors. For example:
        //     //
        //     //  $(selector).minicolors();
        //     //
        //     // The way I've done it below is just for the demo, so don't get confused
        //     // by it. Also, data- attributes aren't supported at this time...they're
        //     // only used for this demo.
        //     //
        //     $(this).minicolors({
        //         control: $(this).attr('data-control') || 'hue',
        //         defaultValue: $(this).attr('data-defaultValue') || '',
        //         format: $(this).attr('data-format') || 'hex',
        //         keywords: $(this).attr('data-keywords') || '',
        //         inline: $(this).attr('data-inline') === 'true',
        //         letterCase: $(this).attr('data-letterCase') || 'lowercase',
        //         opacity: $(this).attr('data-opacity'),
        //         position: $(this).attr('data-position') || 'bottom left',
        //         swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
        //         change: function(value, opacity) {
        //             if (!value) return;
        //             if (opacity) value += ', ' + opacity;
        //             // if (typeof console === 'object') {
        //             //     console.log(value);
        //             // }
        //         },
        //         theme: 'bootstrap'
        //     });
        // });
});

function calDateWhenDragnDrop(event) {

   var reformDate = {start: '', end: '', displayEnd: ''};
   // Dosen't exsist end date => just one day
   if(!event.end) {
     event.end = event.start;
   }
   // just one day and has allDay ontion in event
   if (event.allDay == true && event.end === event.start) {
     reformDate.start = moment(event.start).format('YYYY-MM-DD');
     reformDate.end = reformDate.start;
     reformDate.displayEnd = reformDate.start;
   }
   // more than two days and has allDay option in event
   else if (event.allDay == true && event.end !== null) {
     reformDate.start = moment(event.start).format('YYYY-MM-DD');
     reformDate.end = moment(event.end).format('YYYY-MM-DD');
     reformDate.displayEnd = moment(event.end).subtract(1, 'days').format('YYYY-MM-DD');
   }
   //all day가 아님
   else if (event.allDay != true) {
     reformDate.start = moment(event.start).format('YYYY-MM-DD HH:mm');
     reformDate.end = moment(event.end).format('YYYY-MM-DD HH:mm');
     reformDate.displayEnd = moment(event.end).format('YYYY-MM-DD HH:mm');
   }
   return reformDate;
 }

function calDateWhenResize(event) {
   // var start = $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD');
   // var end = $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD');
   var reformDate = {start: '',end: '', displayEnd:''};
   if (event.allDay === true) {
     reformDate.start = moment(event.start).format('YYYY-MM-DD');
     reformDate.end = moment(event.end).format('YYYY-MM-DD');
     reformDate.displayEnd = moment(event.end).subtract(1, 'days').format('YYYY-MM-DD');
   }else{
     reformDate.start = moment(event.start).format('YYYY-MM-DD HH:mm');
     reformDate.end = moment(event.end).format('YYYY-MM-DD HH:mm');
     reformDate.displayEnd = moment(event.end).format('YYYY-MM-DD HH:mm');
   }
   return reformDate;
}

function getDisplayEventDate(event) {

  var displayEventDate = 'All Day';

  if (event.allDay == false) {
    var startTime = moment(event.start).format('HH:mm');
    var endTime = moment(event.end).format('HH:mm');
    displayEventDate = [startTime, endTime];
  }
  return displayEventDate;
}

function descDisplay(desc) {

  var returnText = '';
  var array_parse = desc.split(/[1-9]{1,}\./);
  if(array_parse.length > 1){
    array_parse = array_parse.filter(function(item){
      return item != "";
    });
    for(var i=0; i<array_parse.length; i++){
      if(array_parse[i] !== ""){
        returnText = returnText + (i+1).toString()+". "+array_parse[i].trim()+"<br/>";
      }
    }
  }else{
    returnText = array_parse[0];
  }
  return returnText.trim();
}

function uploadFileCheck(){
  if(ele_filePath.val() === ''){
    alert('파일을 선택해주세요')
    return false;
  }
  return true;
}

function onChangePartType(){
  return;
}
function onChangeTestType(){
  return;
}
