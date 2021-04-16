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

var weather_card = $('#weather-current-card');
var weather_icon = $('#main-weather-icon');
var loc_value = $('#main-state');
var temp_value = $('#main-t1h');
var wind_icon = $('#main-wsd-icon');
var wind_value = $('#main-wsd');
var hum_value = $('#main-reh');
var rain_value = $('#main-rn1');
var base_value = $('#main-base');

$(function(){

  $(document).ready(function() {
      var jsonData = null;
      $.ajax({
          url:"/beans/phpdata/weather/get-weather.php",
          type:"get",
          dataType:"json",
          data:{state1:"충청남도", state2:"예산군", state3:"덕산면"},
          async: true,
          success: function (data){
            jsonData = data[0];
          },
          beforeSend:function(){},
          complete:function(){
            var base_time = jsonData.base_time;
            var flag = isDaytime(base_time);
            //현재 날씨  icon 선택 day or night을 거친 후 이모티콘 선택
            if(flag){
              var weather_icon_className = weather_day_con[jsonData.pty];
            }else{
              var weather_icon_className = weather_night_con[jsonData.pty];
            }
            weather_icon.attr('class', weather_icon_className);
            weather_icon.addClass('mt-5');
            //현재 지역 값
            loc_value.text(jsonData.state1+' '+jsonData.state2+' '+jsonData.state3+' ');
            //온도
            temp_value.text(jsonData.t3h);
            //풍향
            wind_icon.attr('class', vec_deretion[jsonData.vec]);
            wind_value.text(jsonData.vec+' '+jsonData.wsd+' m/s');
            hum_value.text(jsonData.reh+'%');
            rain_value.text(jsonData.rn1);
            //base_time reformed
            base_time_arr = base_time.split(':')
            base_time = base_time_arr[0]+':'+base_time_arr[1]
            base_value.text(jsonData.base_date+' '+base_time);
            weather_card.attr('style', '');
          },
          error: function (request, status, error){
          console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
          }
        });
  });
  var timer = setInterval(updateWeather, 3600000);
  // var timer = setInterval(updateWeather, 10000);
});


function updateWeather(){
  var jsonData = null;
  $.ajax({
      url:"/beans/phpdata/weather/get-weather.php",
      type:"get",
      dataType:"json",
      data:{state1:"충청남도", state2:"예산군", state3:"덕산면"},
      async: true,
      success: function (data){
        jsonData = data[0];
      },
      beforeSend:function(){},
      complete:function(){
        var base_time = jsonData.base_time;
        var flag = isDaytime(base_time);
        //현재 날씨  icon 선택 day or night을 거친 후 이모티콘 선택
        if(flag){
          var weather_icon_className = weather_day_con[jsonData.pty];
        }else{
          var weather_icon_className = weather_night_con[jsonData.pty];
        }
        weather_icon.attr('class',weather_icon_className);
        weather_icon.addClass('mt-5');
        //현재 지역 값
        loc_value.text(jsonData.state1+' '+jsonData.state2+' '+jsonData.state3+' ');
        //온도
        temp_value.text(jsonData.t3h);
        //풍향
        wind_icon.attr('class', vec_deretion[jsonData.vec]);
        wind_value.text(jsonData.vec+' '+jsonData.wsd+' m/s');
        hum_value.text(jsonData.reh+'%');
        rain_value.text(jsonData.rn1);
        //base_time reformed
        base_time_arr = base_time.split(':')
        base_time = base_time_arr[0]+':'+base_time_arr[1]
        base_value.text(jsonData.base_date+' '+base_time);
        weather_card.attr('style', '');
      },
      error: function (request, status, error){
      console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
}

function isDaytime(base_time) {

  var flag = true;
  base_time_hour = base_time.split(":")[0];
  base_time = parseInt(base_time_hour, 10);
  if((base_time >= 18 && base_time <= 24) || (base_time >= 0 && base_time <= 6)) {
    flag = false;
  }
  return flag;
}
