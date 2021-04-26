// success: function(data) {
// 	$.each(data.arrjson, function(index, arrjson) {
// 		$('#tabList').append("<tr><td>" + arrjson.no + "</td><td>" + arrjson.name + "</td></tr>");
// 	});

var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80", "124,252,000", "238,232,170", "205,133,63", "240,230,140",
  "230,230,250", "106,90,205", "1,191,255", "25,25,112", "64,224,208", "220,20,60"
];
var loc = "";

$(function() {
  $(document).ready(function() {

    var current_date = moment(now_time).format('YYYY-MM-DD');

  });
});

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function getForecastData(type){
  $.ajax({
      url:"/beans/phpdata/forecast/get-forecast.php",
      type:"get",
      dataType:"json",
      data:{location:selected_location},
      async: true,
      success: function (data){
        jsonData = data;
      },
      beforeSend:function(){},
      complete:function(){
        for(var i = 0; i < 6; i++){
          var data  = jsonData[i];
          var sunsetTime = data.sunset;
          var fcstTime = data.fcst_datetime;
          var flag = isDaytime(fcst_time=fcstTime, sunset_time=sunsetTime);
          var weather_icon_className = '';
          var pty = data.pty;
          var sky = data.sky;
          //현재 날씨  icon 선택 day or night을 거친 후 이모티콘 선택
          if(flag){
            if(pty == "없음"){
              weather_icon_className = weather_day_sky_con[sky];
            }else{
              weather_icon_className = weather_day_con[pty];
            }
          }else{
            if(pty == "없음"){
              weather_icon_className = weather_night_sky_con[sky];
            }else{
              weather_icon_className = weather_night_con[pty];
            }
          }
          //날씨 아이콘
          $('#forecast-icon-'+i.toString()).attr('class', weather_icon_className);
          // 예보 기준 시간
          //base_time reformed
          var fsct_value = fcst_time.substr(0, fcst_time.length - 3);
          $('#forecast-base-'+i.toString()).text(fsct_value);
          //온도
          $('#forecast-t1h-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-thermometer" style=font-size:22px;></i></span>&nbsp&nbsp&nbsp'+data.t3h+'℃');
          //풍향
          // $('#forecast-wsd-icon-'+i.toString()).attr('class', vec_deretion[data.vec]);
          $('#forecast-wsd-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i id="forecast-wsd-icon-0" class="'+vec_deretion[data.vec]+'" style=font-size:25px;></i></span>&nbsp'+data.vec+' '+data.wsd+' m/s');
          $('#forecast-reh-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-humidity" style=font-size:21px;></i></span>&nbsp&nbsp'+data.reh+'%');
          $('#forecast-pop-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-storm-showers" style=font-size:21px;></i></span>&nbsp'+data.pop+'%');
          //draw chartjs graph grid
          gridGraph(selected_type);
        }
      },
      error: function (request, status, error){
      console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
}
