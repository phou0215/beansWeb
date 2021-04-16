var draggedEventIsAllDay;
var activeInactiveWeekends = true;

$(document).ready(function() {
        // /* initialize the external events
        // -----------------------------------------------------------------*/
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
            editable: false,
            selectable: false,
            selectHelper: false,
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
        });
});

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

function getDisplayEventDate(event) {

  var displayEventDate = 'All Day';

  if (event.allDay == false) {
    var startTime = moment(event.start).format('HH:mm');
    var endTime = moment(event.end).format('HH:mm');
    displayEventDate = [startTime, endTime];
  }
  return displayEventDate;
}
