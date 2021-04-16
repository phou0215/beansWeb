var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170",
              "205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112",
              "64,224,208","220,20,60","167,128,80","80,110,200","20,120,68","10,40,128",
              "32,60,20","1,20,200","45,170,220","250,60,12","30,76,120","210,10,185"];

// metric-label d-inline-block float-right text-success
// metric-label d-inline-block float-right text-danger
// $( 'h1' ).attr( 'title', 'Hello' );
// fa fa-fw fa-caret-up

//  ajax 로딩 처리
// 함수명 = function()
//      {
//
//
//          $.ajax({
// "timeout": 10000, // 시간 제한
//              "url"   : "" ,
//              "method": "POST" ,
//              "data"  : {"파라미터":값 , "파라미터":값} , // 전송할 자료
// "dataType" :"JSON", // 반환되는 값의 형식 json, text
//              beforeSend : function() {
//                  xShowAjaxLoading(true); // ajax 화면 load
//              }, // end beforeSend
//              success : function ( strRtn ) {
//
//                  if ( typeof(json) == "undefined" ) {return;} // 개체 확인
//
//          var listSize = json.list.length;
//          alert (listSize) ;
//
//
//              },  // end success
//              complete : function(){
// // 성공여부와 상관없이 ajax 완료후 작업
// xShowAjaxLoading(false); // ajax 화면 load
//              }, // end complete
// error : function(request,status,error){
//                  xShowAjaxLoading(false); // ajax 화면 close
//                  xShowAjaxError(request,status,error);
//              } // end error
//          }); // end ajax
//      }

//페이지 시작 시
$(function() {
  xAddAjaxLoading('');
  xAddAjaxLoading('');
  getWeather();
  getForecast();
});

//로딩 표시 보여주기
function xShowAjaxLoading( bln ){
     if ( bln == true ){
         $("#xAjax").removeClass("xDivDisplayNone"); // 보여주지 xDivDisplayNone:css.css정의
     }else{
         $("#xAjax").addClass("xDivDisplayNone"); // 보이지 말기
     }
 }

// AJAX 로딩중 표시
function xAddAjaxLoading(ele)
{
    var strAjax = "<div id='xAjax' class='xDivAjax xDivDisplayNone'>";
    strAjax+="<div><img src=/beans/img/loading.gif'/></div>";
    strAjax+="</div>";
    $(ele).append(strAjax);
}
//각 영역 호출 함수
