// success: function(data) {
// 	$.each(data.arrjson, function(index, arrjson) {
// 		$('#tabList').append("<tr><td>" + arrjson.no + "</td><td>" + arrjson.name + "</td></tr>");
// 	});

var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80", "124,252,000", "238,232,170", "205,133,63", "240,230,140",
  "230,230,250", "106,90,205", "1,191,255", "25,25,112", "64,224,208", "220,20,60"
];
var obj_loc_midFcst = {
  sk:['서울.인천.경기도'],
  kg:['강원도'],
  ch:['충청북도', '대전.세종.충청남도'],
  jl:['전라북도','광주.전라남도'],
  ks:['대구.경상북도', '부산.울산.경상남도'],
  je:['제주도']
}
var obj_loc_landFcst = {
  sk:['서울.인천.경기도'],
  kg:['강원도영서', '강원도영동'],
  ch:['충청북도', '대전.세종.충청남도'],
  jl:['전라북도','광주.전라남도'],
  ks:['대구.경상북도', '부산.울산.경상남도'],
  je:['제주도']
}
var obj_loc_tempFcst = {
  sk:['서울','인천','수원','파주','고양','가평'],
  kg:['춘천','원주','강릉','대관령','철원','삼척'],
  ch:['대전','서산','세종','청주','태안','단양'],
  jl:['광주','목포','여수','전주','군산','해남'],
  ks:['부산','울산','창원','대구','안동','포항'],
  je:['제주','서귀포','성산','이어도','추자도','고산']
}

//page start
$(function() {
  $(document).ready(function() {
    var now_time = new Date();
    var current_date = moment(now_time).format('YYYY-MM-DD');
    getForecastData('ch');
    $('.nav-link').click(function(){
      var loc = $(this).attr('loc');
      getForecastData(loc);
    });
  });
});

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function getForecastData(loc){
  var jsonData = null;
  $.ajax({
      url:"/beans/phpdata/forecast/get-middle-forecast.php?",
      type:"get",
      dataType:"json",
      data:{loc:loc},
      async: true,
      success: function (data){
        jsonData = data;
      },
      beforeSend:function(){},
      complete:function(){

        //set forecast Base time
        var base_time = '<h3>발표시각 '+jsonData.base_time+'</h3>';
        $('#'+loc+'-base-time').html(base_time);

        //empty 중장기 전망 요소
        $('#'+loc+'-wfSv').empty();

        //set 중장기 날씨 전망
        var wfSv = jsonData.wfSv;
        for(var i = 0; i < wfSv.length; i++){
          var source =
                      '<div class="alert alert-primary" role="alert">'+
                      '<h3 class="alert-heading">'+obj_loc_midFcst[loc][i]+'</h3>'+
                      '<p>'+parseNewline(wfSv[i])+'</p>'+
                      '</div>';
          $('#'+loc+'-wfSv').append(source);
        }

        //set Table of land forecast
        $('#'+loc+'-land-table').DataTable().clear().destroy();
        var land = jsonData.land;
        var land_col = jsonData.index_land;
        var reformed = reformedDataTable(obj_loc_landFcst[loc], land_col, land);
        console.log(reformed);
        setColTable(loc+'-land-', land_col);
          $('#'+loc+'-land-table').DataTable({
          data: reformed,
          columns:[
            {data:"LOC"},
            {
              data:land_col[0],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[1],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[2],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[3],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[4],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[5],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[6],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[7],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[8],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[9],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[10],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[11],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
            {
              data:land_col[12],
              render:function(data, type, full, meta){
                  return '강수:'+data[0]+'하늘상태:'+data[1];
              }
            },
          ],
          scrollY: "450px",
          // scrollX: true,
          scrollCollapse: true,
          // 페이징 기능 숨기기
          paging: false,
          // 표시 건수기능 숨기기
          lengthChange: false,
          // 검색 기능 숨기기
          searching: false,
          // 정렬 기능 숨기기
          ordering: false,
          // 정보 표시 숨기기
          info: false,

          columnDefs: [
              { width: '10%', targets: 0 },
              { width: '7%', targets: 1 },
              { width: '7%', targets: 2 },
              { width: '7%', targets: 3 },
              { width: '7%', targets: 4 },
              { width: '7%', targets: 5 },
              { width: '7%', targets: 6 },
              { width: '7%', targets: 7 },
              { width: '7%', targets: 8 },
              { width: '7%', targets: 9 },
              { width: '7%', targets: 10 },
              { width: '7%', targets: 11 },
              { width: '7%', targets: 12 },
              { width: '7%', targets: 13 },
          ],
          fixedColumns: true,
          pageLength: 25,
        });

        // for(var i = 0; i < 6; i++){
        //   var data  = jsonData[i];
        //   var sunsetTime = data.sunset;
        //   var fcstTime = data.fcst_datetime;
        //   var flag = isDaytime(fcst_time=fcstTime, sunset_time=sunsetTime);
        //   var weather_icon_className = '';
        //   var pty = data.pty;
        //   var sky = data.sky;
        //   //현재 날씨  icon 선택 day or night을 거친 후 이모티콘 선택
        //   if(flag){
        //     if(pty == "없음"){
        //       weather_icon_className = weather_day_sky_con[sky];
        //     }else{
        //       weather_icon_className = weather_day_con[pty];
        //     }
        //   }else{
        //     if(pty == "없음"){
        //       weather_icon_className = weather_night_sky_con[sky];
        //     }else{
        //       weather_icon_className = weather_night_con[pty];
        //     }
        //   }
        //   //날씨 아이콘
        //   $('#forecast-icon-'+i.toString()).attr('class', weather_icon_className);
        //   // 예보 기준 시간
        //   //base_time reformed
        //   var fsct_value = fcst_time.substr(0, fcst_time.length - 3);
        //   $('#forecast-base-'+i.toString()).text(fsct_value);
        //   //온도
        //   $('#forecast-t1h-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-thermometer" style=font-size:22px;></i></span>&nbsp&nbsp&nbsp'+data.t3h+'℃');
        //   //풍향
        //   // $('#forecast-wsd-icon-'+i.toString()).attr('class', vec_deretion[data.vec]);
        //   $('#forecast-wsd-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i id="forecast-wsd-icon-0" class="'+vec_deretion[data.vec]+'" style=font-size:25px;></i></span>&nbsp'+data.vec+' '+data.wsd+' m/s');
        //   $('#forecast-reh-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-humidity" style=font-size:21px;></i></span>&nbsp&nbsp'+data.reh+'%');
        //   $('#forecast-pop-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-storm-showers" style=font-size:21px;></i></span>&nbsp'+data.pop+'%');
        //   //draw chartjs graph grid
        //   gridGraph(selected_type);
        // }
      },
      error: function (request, status, error){
      console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
}

function parseNewline(text){
  var return_text = text.replace(/\n/g,"<br/>");
  return return_text;
}

function reformedDataTable(loc_index, date_index, land_data){
  var return_array = [];
  for(var i = 0; i < loc_index.length; i++){
    var obj_row = {};
    obj_row['LOC'] = loc_index[i];
    // load rain data and sky data on obj_row
    for (var j = 0; j < land_data[i]['rain'].length; j++){
      obj_row[date_index[j]] = [land_data[i]['rain'][j], land_data[i]['sky'][j]];
    }
    return_array.push(obj_row);
  }
  return return_array;
}

function setColTable(id, index_data){
  for( var i = 1; i < index_data.length; i++){
    $('#id'+i).text(index_data[i]);
  }
}

// function landData(reforedData){
//   var return_obj = null;
//   for (var item in reforedData){
//     return null;
//   }
// }
