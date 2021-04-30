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
  kg:['강원도 영서', '강원도 영동'],
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
var weather_con = {
  '맑음':'wi wi-day-sunny',
  '구름많음':'wi wi-cloudy',
  '구름많고 비':'wi wi-rain',
  '구름많고 눈':'wi wi-snow',
  '구름많고 비/눈':'wi wi-rain-mix',
  '구름많고 소나기':'wi wi-showers',
  '흐림':'wi wi-day-cloudy',
  '흐리고 비':'wi wi-day-rain',
  '흐리고 눈':'wi wi-day-snow',
  '흐리고 비/눈':'wi wi-day-rain-mix',
  '흐리고 소나기':'wi wi-day-storm-showers',
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

// main function of middle forecast
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
        var reformed = reformedLandDataTable(obj_loc_landFcst[loc], land_col, land);

        $('#'+loc+'-land-table').DataTable({
          data: reformed,
          columns:[
            {data:"LOC"},
            {
              data:land_col[0],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[1],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[2],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[3],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[4],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[5],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[6],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[7],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[8],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[9],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[10],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[11],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
              }
            },
            {
              data:land_col[12],
              render:function(data, type, full, meta){
                  var sky_icon = data[1];
                  var rain_drop = data[0];
                  var source = '<div class="row">'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="color:rgba(117,163,102,0.8);">'+
                              '<i class="'+weather_con[sky_icon]+'" style=font-size:30px;></i></div>'+
                              '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ml-1 mt-1">'+rain_drop+'%</div></div>';
                  return source;
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
        //set land table columns
        setColTable(loc+'-land-', land_col);


        //set Table of Temp forecast
        $('#'+loc+'-temp-table').DataTable().clear().destroy();
        var temp = jsonData.temp;
        var temp_col = jsonData.index_temp;
        reformed = reformedTempDataTable(obj_loc_tempFcst[loc], temp_col, temp);
        $('#'+loc+'-temp-table').DataTable({
          data: reformed,
          columns:[
            {data:"LOC"},
            {
              data:temp_col[0],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[1],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[2],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[3],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[4],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[5],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[6],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            },
            {
              data:temp_col[7],
              render:function(data, type, full, meta){
                  var min = data[0];
                  var max = data[1];
                  var source = '<span class="text-primary" style="font-size:15px;">'+
                  min+'</span>&nbsp/&nbsp<span class="text-danger" style="font-size:15px;">'+
                  max+'</span>'
                  return source;
              }
            }
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
              { width: '12%', targets: 0 },
              { width: '10%', targets: 1 },
              { width: '10%', targets: 2 },
              { width: '10%', targets: 3 },
              { width: '10%', targets: 4 },
              { width: '10%', targets: 5 },
              { width: '10%', targets: 6 },
              { width: '10%', targets: 7 },
              { width: '10%', targets: 8 },
          ],
          fixedColumns: true,
          pageLength: 25,
        });
        //set land table columns
        setColTable(loc+'-temp-', temp_col);
      },
      error: function (request, status, error){
      console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
}
// replace from \n to br tag
function parseNewline(text){
  var return_text = text.replace(/\n/g,"<br/>");
  return return_text;
}
//land data reformed
function reformedLandDataTable(loc_index, date_index, land_data){
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
//temp data reformed
function reformedTempDataTable(loc_index, date_index, temp_data){
  var return_array = [];
  for(var i = 0; i < loc_index.length; i++){
    var obj_row = {};
    obj_row['LOC'] = loc_index[i];
    // load rain data and sky data on obj_row
    for (var j = 0; j < temp_data[i]['max'].length; j++){
      obj_row[date_index[j]] = [temp_data[i]['min'][j], temp_data[i]['max'][j]];
    }
    return_array.push(obj_row);
  }
  return return_array;
}
//set table th column name
function setColTable(id, index_data){
  $('#'+id+'0').text('지역');
  for( var i = 0; i < index_data.length; i++){
    $('#'+id+(i+1)).text(index_data[i]);
  }
}
