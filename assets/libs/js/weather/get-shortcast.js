var vec_deretion = {
  'N':'wi wi-wind towards-0-deg',
  'NNW':'wi wi-wind towards-23-deg',
  'NE':'wi wi-wind towards-45-deg',
  'ENE':'wi wi-wind towards-68-deg',
  'E':'wi wi-wind towards-90-deg',
  'ESE':'wi wi-wind towards-113-deg',
  'SE':'wi wi-wind towards-135-deg',
  'SSE':'wi wi-wind towards-158-deg',
  'S':'wi wi-wind towards-180-deg',
  'SSW':'wi wi-wind towards-203-deg',
  'SW':'wi wi-wind towards-225-deg',
  'WSW':'wi wi-wind towards-248-deg',
  'W': 'wi wi-wind towards-270-deg',
  'WNW':'wi wi-wind towards-293-deg',
  'NW':'wi wi-wind towards-313-deg',
  'NNW':'wi wi-wind towards-336-deg',
}
var weather_day_con = {
  '없음':'wi wi-day-sunny',
  '비':'wi wi-day-rain',
  '비/눈':'wi wi-day-rain-mix',
  '눈':'wi wi-day-snow',
  '소나기':'wi wi wi-day-showers',
  '빗방울':'wi wi-day-sprinkle',
  '빗방울/눈날림':'wi wi-day-rain-wind',
  '눈날림':'wi wi-day-snow-wind'
}
var weather_night_con = {
  '없음':'wi wi-night-clear',
  '비':'wi wi-night-alt-rain',
  '비/눈':'wi-night-alt-rain-mix',
  '눈':'wi wi-night-alt-snow',
  '소나기':'wi wi-night-alt-showers',
  '빗방울':'wi wi-night-alt-sprinkle',
  '빗방울/눈날림':'wi wi-night-alt-rain-wind',
  '눈날림':'wi wi-night-alt-snow-wind'
}

var weather_day_sky_con = {
  '맑음':'wi wi-day-sunny',
  '흐림':'wi wi-cloudy',
  '구름많음':'wi wi-day-cloudy'
}
var weather_night_sky_con = {
  '맑음':'wi wi-night-clear',
  '흐림':'wi wi-cloudy',
  '구름많음':'wi wi-night-alt-cloudy'
}

var weather_card = $('#weather-current-card');
var weather_icon = $('#main-weather-icon');
var loc_value = $('#main-state');
var rise_value = $('#main-rise');
var set_value = $('#main-set');
var temp_value = $('#main-t1h');
var wind_icon = $('#main-wsd-icon');
var wind_value = $('#main-wsd');
var hum_value = $('#main-reh');
var rain_value = $('#main-rn1');
var base_time_value = $('#main-base');
var light_value = $('#main-lgt');
var select_local = $('#local-picker');

$(function(){

  $(document).ready(function() {
      var jsonData = null;
      var selected_location = select_local.val();

      $.ajax({
          url:"/beans/phpdata/shortcast/get-shortcast.php",
          type:"get",
          dataType:"json",
          data:{location:selected_location},
          async: true,
          success: function (data){
            jsonData = data[0];
          },
          beforeSend:function(){},
          complete:function(){
            var sunsetTime = jsonData.sunset;
            var flag = isDaytime(fcst_time=null, sunset_time=sunsetTime);
            var weather_icon_className = '';
            var pty = jsonData.pty;
            var sky = jsonData.sky;
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
            // 현재 날씨 아이콘 결정
            weather_icon.attr('class', weather_icon_className);
            weather_icon.addClass('mt-5');
            //온도
            temp_value.text(jsonData.t1h+'℃');
            //풍향 풍속
            wind_icon.attr('class', vec_deretion[jsonData.vec]);
            wind_value.text(jsonData.vec+' '+jsonData.wsd+' m/s');
            // 습도
            hum_value.text(jsonData.reh+'%');
            // 강수확률
            rain_value.text(jsonData.rn1);
            // 낙뢰유무
            light_value.text(jsonData.lgt);

            //base_time reformed
            var base_value = jsonData.base_datetime.substr(0, jsonData.base_datetime.length - 3);
            //현재 지역 값
            loc_value.text(jsonData.location);
            //현재 시간 값
            base_time_value.text(base_value);
            //일출 정보
            rise_value.text('일출: '+displayTime(jsonData.sunrise));
            //일몰 정보
            set_value.text('일몰: '+displayTime(jsonData.sunset));
            // 해당 카드 노출
            weather_card.attr('style', '');
          },
          error: function (request, status, error){
          console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
          }
        });
  });
  var timer = setInterval(updateWeather, 30000);
  // var timer = setInterval(updateWeather, 10000);
});

function updateWeather(){
  var jsonData = null;
  var selected_location = select_local.val();

  $.ajax({
      url:"/beans/phpdata/shortcast/get-shortcast.php",
      type:"get",
      dataType:"json",
      data:{location:selected_location},
      async: true,
      success: function (data){
        jsonData = data[0];
      },
      beforeSend:function(){},
      complete:function(){
        var sunsetTime = jsonData.sunset;
        var flag = isDaytime(fcst_time=null, sunset_time=sunsetTime);
        var weather_icon_className = '';
        var pty = jsonData.pty;
        var sky = jsonData.sky;
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
        // 현재 날씨 아이콘 결정
        weather_icon.attr('class', weather_icon_className);
        weather_icon.addClass('mt-5');
        //온도
        temp_value.text(jsonData.t1h+'℃');
        //풍향 풍속
        wind_icon.attr('class', vec_deretion[jsonData.vec]);
        wind_value.text(jsonData.vec+' '+jsonData.wsd+' m/s');
        // 습도
        hum_value.text(jsonData.reh+'%');
        // 강수확률
        rain_value.text(jsonData.rn1);
        // 낙뢰유무
        light_value.text(jsonData.lgt);

        //base_time reformed
        var base_value = jsonData.base_datetime.substr(0, jsonData.base_datetime.length - 3);
        //현재 지역 값
        loc_value.html(jsonData.location);
        //현재 시간 값
        base_time_value.text(base_value);
        //일출 정보
        rise_value.text('일출: '+displayTime(jsonData.sunrise));
        //일몰 정보
        set_value.text('일몰: '+displayTime(jsonData.sunset));
        // 해당 카드 노출
        weather_card.attr('style', '');
      },
      error: function (request, status, error){
      console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
}

function isDaytime(fcst_time, sunset_time){

  var flag = true;
  var sunset_hour = 0;
  var sunset_minute = 0;
  var sunset_second = 0;
  var base = null;

  if (fcst_time === null){
    base = new Date();
  }else{
    base = new Date(fcst_time);
  }

  if (sunset_time.length == 4){
    sunset_hour = parseInt(sunset_time.substr(0,2));
    sunset_minute = parseInt(sunset_time.substr(2,2));
  }else{
    sunset_hour = parseInt(sunset_time.substr(0,2));
    sunset_minute = parseInt(sunset_time.substr(2,2));
    sunset_second = parseInt(sunset_time.substr(4,2));
  }
  var sunset = new Date().setHours(sunset_hour, sunset_minute, sunset_second);
  if(base >= sunset){
    var flag = false;
  }
  return flag;
}

function displayTime(time){
  var return_value = '';
  var hour = time.substr(0, 2);
  var minutes = time.substr(2, 2);
  return_value = hour+':'+minutes;
  return return_value;
}
