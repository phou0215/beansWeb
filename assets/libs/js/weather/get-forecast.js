var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63",
"240,230,140","230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];
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
var jsonData = null;
var selected_type = 't3h';
var select_local = $('#local-picker');

$(function(){

  $(document).ready(function() {
      var selected_location = select_local.val();
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
  });
  var timer_forecast = setInterval(updateforecast, 3600000);
  // var timer_forecast = setInterval(updateforecast, 10000);
});


function updateforecast() {

  var selected_location = select_local.val();
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
          $('#forecast-t1h-'+i.toString()).html('<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-thermometer" style=font-size:22px;></i></span>&nbsp'+data.t3h+'<span style="color:rgba(117,163,102,0.8);"><i class="wi wi-celsius" style=font-size:27px;></i></span>');
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

function gridGraph(type) {

  var chart_ele = $("#chartjs_line_weather");
  var index = get_index();
  var object = null;
  //set initial graph
  if(window.chart != undefined){
      window.chart.destroy();
  }
  //set datas by type
  if(type == 't3h'){
    selected_type = 't3h';
    $('#chart-t3h').attr('class','nav-link active show');
    $('#chart-reh').attr('class','nav-link');
    $('#chart-pop').attr('class','nav-link');

    var i = 0;
    var data_array = [];
    while(i < jsonData.length){
      data_array.push(jsonData[i].t3h);
      i++;
    }
    object = {
      fill:true,
      lineTension: 0,
      label:'기온(℃)',
      data: data_array,
      backgroundColor: "rgba("+colors[3]+",0.5)",
      borderColor: "rgba("+colors[3]+",0.9)",
      borderWidth: 2
    };
  }else if(type == 'reh'){
    selected_type = 'reh';
    $('#chart-t3h').attr('class','nav-link');
    $('#chart-reh').attr('class','nav-link active show');
    $('#chart-pop').attr('class','nav-link');
    var i = 0;
    var data_array = [];
    while(i < jsonData.length){
      data_array.push(jsonData[i].reh);
      i++;
    }
    object = {
      fill:true,
      label:'습도(%)',
      lineTension: 0,
      data: data_array,
      backgroundColor: "rgba("+colors[0]+",0.5)",
      borderColor: "rgba("+colors[0]+",0.9)",
      borderWidth: 2
    };
  }else{
    selected_type = 'pop';
    $('#chart-t3h').attr('class','nav-link');
    $('#chart-reh').attr('class','nav-link');
    $('#chart-pop').attr('class','nav-link active show');
    var i = 0;
    var data_array = [];
    while(i < jsonData.length){
      data_array.push(jsonData[i].pop);
      i++;
    }
    object = {
      fill:true,
      lineTension: 0,
      label:'강수확률(%)',
      data: data_array,
      backgroundColor: "rgba("+colors[9]+",0.5)",
      borderColor: "rgba("+colors[9]+",0.9)",
      borderWidth: 2
    };
  }
  // set grid ctx graph
  if(chart_ele.length){
    var ctx = document.getElementById("chartjs_line_weather").getContext('2d');
    //set grid graph
    window.chart = new Chart(ctx, {
        type: 'line',
        data: {
            // labels: jsonData.fcst_date,
            labels:index,
            datasets: [object]
        },
        options: {
            responsive: true,
            // tooltips: {
            //     callbacks: {
            //         label: function(tooltipItem, data){
            //           if(type == 't1h') {
            //             var dsLabel = data.datasets[tooltipItem.datasetIndex].label;
            //             var y_value = tooltipItem.yLabel;
            //             return dsLabel + '기온 : '+y_value+'℃';
            //           } else if(type == 'reh') {
            //             var dsLabel = data.datasets[tooltipItem.datasetIndex].label;
            //             var y_value = tooltipItem.yLabel;
            //             return dsLabel + '습도 : '+y_value+'%';
            //           }else {
            //             var dsLabel = data.datasets[tooltipItem.datasetIndex].label;
            //             var y_value = tooltipItem.yLabel;
            //             return dsLabel + '강수확률 : '+y_value+'%';
            //           }
            //         }
            //     }
            // },
            elements: {
              point: {
                pointStyle: "circle",
                backgroundColor : "rgba(255,255,225,0.9)",
                hoverRadius: 5,
                borderWidth: 8
              }
            },
            scales: {
                xAxes: [{
                    // type:'time',
                    gridLines: {display: false,},
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 20,
                        fontSize: font_size,
                        fontFamily: 'Circular Std Book',
                        fontColor: '#71748d',
                        fontSize: 13,
                    }
                }],
                yAxes: [{
                    gridLines: {display: false,},
                    ticks: {
                      fontSize: font_size,
                      maxTicksLimit: 7,
                      fontFamily: 'Circular Std Book',
                      fontColor: '#71748d',
                      beginAtZero: true,
                    },
                    position: "left",
                    id: "y_axis_0",
                    }
                  // ,{
                  //   ticks: {
                  //     fontSize: font_size,
                  //     fontFamily: 'Circular Std Book',
                  //     fontColor: '#71748d',
                  //     beginAtZero: true,
                  //     display:false
                  //   },
                  //   position: "right",
                  //   id: "y_axis_1",
                  //   gridLines: { display: false}
                  // }
                ]
            },
            legend: {
              display: true,
              position: 'bottom',
              labels: {
                  fontColor: '#71748d',
                  fontFamily: 'Circular Std Book',
                  fontSize: font_size,
              }
            },
        }
    });
  }
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

function get_index() {

  var return_array = [];
  for(var i = 0; i < jsonData.length; i++){
    var dateString = jsonData[i].fcst_datetime;
    var dateType = new Date(dateString);
    var date = (dateType.getMonth()+1)+'/'+dateType.getDate();
    var time = dateType.getHours()+'시';
    return_array.push(date+' '+time);
  }
  return return_array;
}

function changeLocal(){
  updateWeather();
  updateforecast();
}
