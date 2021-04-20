var ele_sData = $('#sData');
var ele_cData = $('#cData');
// var ele_dema = $('#dema-chart')
function gridSuppChart(){
    // var font_size = 11;
    // var colors = ['#93DAFF','#98DFFF','#9DE4FF','#A2E9FF','#A7EEFF','#ACF3FF','#B0F7FF','#B4FBFF','#B9FFFF','#C0FFFF',
    //               '#87CEFA','#91D8FA','#A5D8FA','#AFDDFA','#B9E2FA','#C3E7FA','#CDECFA','#D7F1FA','#E1F6FA','#EBFBFF',
    //               '#00BFFF','#0AC9FF','#14D3FF','#1EDDFF','#28E7FF','#32F1FF','#3CFBFF','#46FFFF','#96FFFF','#C8FFFF',
    //               '#00A5FF','#00AFFF','#00B9FF','#00C3FF','#00CDFF','#00D7FF','#00E1FF','#00EBFF','#00F5FF','#00FFFF',
    //               '#1EA4FF','#28AEFF','#32B8FF','#3CC2FF','#46CCFF','#50D6FF','#5AE0FF','#6EE0FF','#6EEAFF','#78F3FF',
    //               '#1E90FF','#289AFF','#32A4FF','#3CAEFF','#46B8FF','#50C2FF','#5ACCFF','#64D6FF','#6EE0FF','#78EAFF']

    // ==============================================================
    // total keyword
    // ==============================================================
    var count = jsonData.index_supply.length;
    var totNum = jsonData.values_supply.reduce((a, b) => a + b);
    var datas = new Array();

    //each chart initiation
    $("#supp-chart").empty();
    $("#supp-chart svg").remove();

    var i = 0;
    var backgrounds = new Array();
    while(i < count){
      datas.push({
        value:jsonData.values_supply[i],
        label:jsonData.index_supply[i]
      })
      i++;
    }

    Morris.Donut({
            resize: true,
            element: 'supp-chart',
            data: datas,
            labelColor: '#FFFFFF',
            colors: [
              "#5969ff",
              "#ff407b",
              "#25d5f2",
              "#ffc750"
            ],
            formatter: function(x) { return getPercent(x, totNum) + "%" }
    });
    ele_sData.text(jsonData.values_demand[0]);
    ele_cData.text(jsonData.values_demand[1]);

}

function getPercent(num, totNum){
  var return_num = (num / totNum)*100;
  return return_num.toFixed(2);
}
