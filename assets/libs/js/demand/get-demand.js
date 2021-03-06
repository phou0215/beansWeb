var ele_table = $('#table-data');
var ele_comp = $('#comp-picker');
var ele_dema = $('#dema-picker');
var ele_supp = $('#supp-picker');

// var data_table = null;
var jsonData = null;

$(function(){

  $(document).ready(function() {

      //sDate and eDate initialize to today date
      // var now_time = new Date();
      // var current_date = moment(now_time).format('YYYY-MM-DD');
      // ele_sDate.val(current_date);
      // ele_eDate.val(current_date);
      // CSV Download Click Event Listener
      $('#csv-download').click(function(){
        var proceed = confirm("다운로드를 진행하시겠습니까?");
        if(proceed){
          if(jsonData === null){
              alert('조회된 데이터가 없습니다.');
              return;
          }
          JSONToCSVConvertor();
        }else{
          return;
        }
      });
      //get order list chung nam do localfood api
      $.ajax({
          //CORS XSS 도메인 크로스 요청 금지 회피를 위해 직접 호출 대신 php backend server에서 forwarding
          url:"/beans/phpdata/localfood/demand/get-demand.php",
          type:"get",
          dataType:"json",
          async: true,
          data:{comp:ele_comp.val(), dema:ele_dema.val(), supp:ele_supp.val()},
          // headers: {
          //   "accept":"application/json",
          //   "Access-Control-Allow-Origin":"*",
          //   "User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",
          // },
          success: function (data){
            jsonData = data;
          },
          beforeSend:function(jqXHR, settings){
            // console.log(settings.url);
          },
          complete:function(){
            ele_table.DataTable({
              data:jsonData.tables,
              columns:[
                {data:"COMP_NM"},
                {data:"DEMA_TYPE"},
                {data:"CUST_KIND_NM"},
                {data:"SUPPLY_TYPE_NM"},
                {data:"CUST_NM"},
                {data:"BIZ_NO"},
                {data:"ADDR"},
                {
                  data:"HOME_PAGE",
                  render:function(data, type, full, meta){
                      if(data != "정보없음"){
                        return "<a href="+data+"><i class='fas fa-home'></i>홈페이지 이동</a>"
                      }else{
                        return data;
                      }
                  }
                },
                {
                  data:"TEL_NO",
                  render:function(data, type, full, meta){
                    return changeTelNum(data);
                  }
                }
              ],
              scrollY: "350px",
              // scrollX: true,
              scrollCollapse: true,
              paging:         true,
              columnDefs: [
                  { width: '8%', targets: 0 },
                  { width: '8%', targets: 1 },
                  { width: '8%', targets: 2 },
                  { width: '8%', targets: 3 },
                  { width: '15%', targets: 4 },
                  { width: '12%', targets: 5 },
                  { width: '18%', targets: 6 },
                  { width: '10%', targets: 7 },
                  { width: '13%', targets: 8 }
              ],
              fixedColumns: true,
              pageLength: 25,
            });
            gridDemaChart();
            gridSuppChart();
            gridCustChart();

          },
          error: function (request, status, error){
          console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
          }
        });
  });
});

function setTableData(){
  //sDate and eDate initialize to today date
  jsonData = null;
  //get order list chung nam do localfood api
  $.ajax({
      //CORS XSS 도메인 크로스 요청 금지 회피를 위해 직접 호출 대신 php backend server에서 forwarding
      url:"/beans/phpdata/localfood/demand/get-demand.php",
      type:"get",
      dataType:"json",
      async: true,
      data:{comp:ele_comp.val(), dema:ele_dema.val(), supp:ele_supp.val()},
      // headers: {
      //   "accept":"application/json",
      //   "Access-Control-Allow-Origin":"*",
      //   "User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",
      // },
      success: function (data){
        jsonData = data;
      },
      beforeSend:function(jqXHR, settings){
        // console.log(settings.url);
      },
      complete:function(){
        ele_table.DataTable().clear().destroy();
        ele_table.DataTable({
          data:jsonData.tables,
          columns:[
            {data:"COMP_NM"},
            {data:"DEMA_TYPE"},
            {data:"CUST_KIND_NM"},
            {data:"SUPPLY_TYPE_NM"},
            {data:"CUST_NM"},
            {data:"BIZ_NO"},
            {data:"ADDR"},
            {
              data:"HOME_PAGE",
              render:function(data, type, full, meta){
                  if(data != "정보없음"){
                    return "<a href="+urlFormatter(data)+" target='_blank'><i class='fas fa-home'></i>&nbsp&nbsp홈페이지</a>"
                  }else{
                    return data;
                  }
              }
            },
            {
              data:"TEL_NO",
              render:function(data, type, full, meta){
                return changeTelNum(data);
              }
            }
          ],
          scrollY: "350px",
          // scrollX: true,
          scrollCollapse: true,
          paging:         true,
          columnDefs: [
              { width: '8%', targets: 0 },
              { width: '8%', targets: 1 },
              { width: '8%', targets: 2 },
              { width: '8%', targets: 3 },
              { width: '15%', targets: 4 },
              { width: '12%', targets: 5 },
              { width: '18%', targets: 6 },
              { width: '10%', targets: 7 },
              { width: '13%', targets: 8 }
          ],
          fixedColumns: true,
          pageLength: 25,
        });
        gridDemaChart();
        gridSuppChart();
        gridCustChart();
      },
      error: function (request, status, error){
      console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
}

function changeDateFormat(date){
  // var changeFormat = date.toDateString();
  var changeFormat = date.replace(/-/gi, '');
  return changeFormat;
}

function withComma(data){
  var number_with_comma = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return number_with_comma;
}

function JSONToCSVConvertor(ShowLabel=true) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof jsonData.tables != 'object' ? JSON.parse(jsonData.tables) : jsonData.tables;
    var CSV = '';
    //Set Report title in first row or line
    var now_time = new Date();
    var current_date = moment(now_time).format('YYYY-MM-DD HH mm ss');
    CSV += 'Demand Report('+current_date+')\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            //Now convert each value to string and comma-seprated
            row += index + ',';
        }
        row = row.slice(0, -1);
        //append Label row with line break
        CSV += row + '\r\n';
    }

    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
        }
        row.slice(0, row.length - 1);
        //add a line break after each row
        CSV += row + '\r\n';
    }
    if (CSV == '') {
        alert("Invalid data");
        return;
    }
    //Generate a file name
    var fileName = 'Demand Report('+current_date+')';
    //this will remove the blank-spaces from the title and replace it with an underscore
    // fileName += ReportTitle.replace(/ /g,"_");

    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,\uFEFF' + encodeURI(CSV);

    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension

    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");
    link.href = uri;

    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";

    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function changeTelNum(text, type=1){

  var return_data = "";
  if(text){
    if(text.length == 11){
      if(type == 0){
        return_data = text.replace(/(\d{3})(\d{4})(\d{4})/, '$1-****-$3');
      }else{
        return_data = text.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
      }
    }else if(text.length == 8){
      return_data = text.replace(/(\d{4})(\d{4})/, '$1-$2');
    }else{
      if(text.indexOf('02') == 0){
        if(type == 0){
          return_data = text.replace(/(\d{2})(\d{4})(\d{4})/, '$1-****-$3');
        }else{
          return_data = text.replace(/(\d{2})(\d{4})(\d{4})/, '$1-$2-$3');
        }
      }else{
        if(type==0){
          return_data = text.replace(/(\d{3})(\d{3})(\d{4})/, '$1-***-$3');
        }else{
          return_data = text.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
      }
    }
  }
  return return_data;
}

function urlFormatter(address){
  var return_address = address;
  if(address.indexOf('http://') == -1){
    return_address = "http://"+address;
  }
  return return_address;
}
