var ele_table = $('#table-data');
var ele_sDate = $('#start-picker');
var ele_eDate = $('#end-picker');
var ele_comp = $('#comp-picker');
var ele_item = $('#item-picker');


$(function(){

  $(document).ready(function() {

      //sDate and eDate initialize to today date
      var now_time = new Date();
      var jsonData = null;
      var current_date = moment(now_time).format('YYYY-MM-DD');
      ele_sDate.val(current_date);
      ele_eDate.val(current_date);
      //get order list chung nam do localfood api
      $.ajax({
          //CORS XSS 도메인 크로스 요청 금지 회피를 위해 직접 호출 대신 php backend server에서 forwarding
          url:"/beans/phpdata/localfood/orderlist/get-orderlist-text.php",
          type:"get",
          dataType:"json",
          async: true,
          data:{sDate:ele_sDate.val(), eDate:ele_eDate.val(), comp:ele_comp.val(), itemcls:ele_item.val()},
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
              data:jsonData,
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
                {data:"QTY"},
                {data:"UNIT_NM"},
                {data:"TOT_AMT"}
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
          },
          error: function (request, status, error){
          console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
          }
        });
  });
});

function setTableData(jsondData){

}

function gridProductCategory(data){
  return;
}
