var ele_table = $('#table-data');
var ele_category = $('#category-data');
var ele_sDate = $('#start-picker');
var ele_eDate = $('#end-picker');
var ele_comp = $('#comp-picker');
var ele_item = $('#item-picker');
// var data_table = null;
var jsonData = null;

$(function(){

  $(document).ready(function() {

      //sDate and eDate initialize to today date
      var now_time = new Date();
      var current_date = moment(now_time).format('YYYY-MM-DD');
      ele_sDate.val(current_date);
      ele_eDate.val(current_date);
      // CSV Download Click Event Listener
      $('#csv-download').click(function(){
          if(jsonData === null){
              alert('조회된 데이터가 없습니다.');
              return;
          }
          JSONToCSVConvertor();
      });
      //get order list chung nam do localfood api
      $.ajax({
          //CORS XSS 도메인 크로스 요청 금지 회피를 위해 직접 호출 대신 php backend server에서 forwarding
          url:"/beans/phpdata/localfood/orderlist/get-orderlist.php",
          type:"get",
          dataType:"json",
          async: true,
          data:{sDate:changeDateFormat(ele_sDate.val()), eDate:changeDateFormat(ele_eDate.val()), comp:ele_comp.val(), itemcls:ele_item.val()},
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
                {data:"ITEMCLS1_NM"},
                {data:"ITEMCLS2_NM"},
                {data:"ITEMCLS3_NM"},
                {
                  data:"EST_DATE",
                  render:function(data, type, full, meta){
                      return moment(data).format('YYYY-MM-DD');
                  }
                },
                {data:"ITEM_NM"},
                {data:"SPEC"},
                {data:"PU_CUST_NM"},
                {
                  data:"QTY",
                  render:function(data, type, full, meta){
                    return String(data)+' '+full.UNIT_NM;
                  }
                },
                {
                  data:"FREQ_CNT",
                  render:function(data, type, full, meta){
                    return String(data)+' 회';
                  }
                },
                {
                  data:"TOT_AMT",
                  render:function(data, type, full, meta){
                    var num = parseInt(data);
                    var price = withComma(num);
                    return String(price)+' 원';
                  }
                }
              ],
              scrollY: "350px",
              // scrollX: true,
              scrollCollapse: true,
              paging:         true,
              columnDefs: [
                  { width: '7%', targets: 0 },
                  { width: '7%', targets: 1 },
                  { width: '7%', targets: 2 },
                  { width: '9%', targets: 3 },
                  { width: '9%', targets: 4 },
                  { width: '14%', targets: 5 },
                  { width: '19%', targets: 6 },
                  { width: '9%', targets: 7 },
                  { width: '7%', targets: 8 },
                  { width: '5%', targets: 9 },
                  { width: '7%', targets: 10 },
              ],
              fixedColumns: true,
              pageLength: 25,
            });
            ele_category.DataTable({
              data:jsonData.categories,
              // 표시 건수기능 숨기기
              lengthChange: false,
              // 검색 기능 숨기기
              searching: false,
              // 정렬 기능 숨기기
              ordering: true,
              // 정보 표시 숨기기
              info: true,
              // 페이징 기능 숨기기
              paging: true,
              columns:[
                {data:"class"},
                {data:"count"},
              ],
              scrollY: "250px",
              // scrollX: true,
              scrollCollapse: true,
              paging:         true,
              columnDefs: [
                  { width: '50%', targets: 0 },
                  { width: '50%', targets: 1 },
              ],
              fixedColumns: true,
              pageLength: 8,
            });
            gridCenterChart();
            gridItemChart();

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
      url:"/beans/phpdata/localfood/orderlist/get-orderlist.php",
      type:"get",
      dataType:"json",
      async: true,
      data:{sDate:changeDateFormat(ele_sDate.val()), eDate:changeDateFormat(ele_eDate.val()), comp:ele_comp.val(), itemcls:ele_item.val()},
      // headers: {
      //   "accept":"application/json",
      //   "Access-Control-Allow-Origin":"*",
      //   "User-Agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36",
      // },
      success: function (data){
        jsonData = data;
      },
      beforeSend:function(jqXHR, settings){
        console.log(settings.url);
      },
      complete:function(){
        //table
        // table data relaod
        ele_table.DataTable().clear().destroy();
        ele_table.DataTable({
          data:jsonData.tables,
          columns:[
            {data:"COMP_NM"},
            {data:"ITEMCLS1_NM"},
            {data:"ITEMCLS2_NM"},
            {data:"ITEMCLS3_NM"},
            {
              data:"EST_DATE",
              render:function(data, type, full, meta){
                  return moment(data).format('YYYY-MM-DD');
              }
            },
            {data:"ITEM_NM"},
            {data:"SPEC"},
            {data:"PU_CUST_NM"},
            {
              data:"QTY",
              render:function(data, type, full, meta){
                return String(data)+' '+full.UNIT_NM;
              }
            },
            {
              data:"FREQ_CNT",
              render:function(data, type, full, meta){
                return String(data)+' 회';
              }
            },
            {
              data:"TOT_AMT",
              render:function(data, type, full, meta){
                var num = parseInt(data);
                var price = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return String(price)+' 원';
              }
            }
          ],
          scrollY: "450px",
          // scrollX: true,
          scrollCollapse: true,
          paging:         true,
          columnDefs: [
              { width: '7%', targets: 0 },
              { width: '7%', targets: 1 },
              { width: '7%', targets: 2 },
              { width: '9%', targets: 3 },
              { width: '9%', targets: 4 },
              { width: '14%', targets: 5 },
              { width: '19%', targets: 6 },
              { width: '9%', targets: 7 },
              { width: '7%', targets: 8 },
              { width: '5%', targets: 9 },
              { width: '7%', targets: 10 },
          ],
          fixedColumns: true,
          pageLength: 25,
        });
        gridCenterChart();
        gridItemChart();
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
    CSV += 'OrderList Report('+current_date+')\r\n\n';

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
    var fileName = 'OrderList Report('+current_date+')';
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
