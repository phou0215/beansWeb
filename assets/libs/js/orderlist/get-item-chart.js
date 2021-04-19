function gridItemChart(){
    var ele_item_picker = $('#items-count-picker');
    // var font_size = 11;
    var colors = ['#3DFF92','#47FF9C','#51FFA6','#5BFFB0','#65FFBA','#6FFFC4','#79FFCE','#75FFCA','#7AFFCF','#7FFFD4',
                  '#55EE94','#5FEE9E','#69EEA8','#73EEB2','#7DEEBC','#87EEC6','#91F8D0','#D7F1FA','#9BFFDA','#A5FFE4',
                  '#AFFFEE','#66CDAA','#70D2B4','#7AD7BE','#84DCC8','#8EE1D2','#98EBDC','#9DF0E1','#A2F5E6','#A7FAEB',
                  '#ACFFEF']
    // ==============================================================
    // total keyword
    // ==============================================================
    var count = parseInt(ele_item_picker.val());
    var ctx = document.getElementById("item-chart").getContext('2d');
    //each chart initiation
    if(window.chart_item != undefined){
      window.chart_item.destroy();
    }

    i = 0;
    var backgrounds = new Array();
    while(i < count){
      backgrounds.push(colors[i]);
      i++;
    }
    var labels = jsonData.index_items.slice(0, count);
    var data = jsonData.values_items.slice(0, count);

    window.chart_item = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          backgroundColor: backgrounds,
          data: data
        }]
      },
      options: {
        legend: {
          display: true,
          position:"chartArea",
          align: "start",
          labels:{
            fontColor:"rgb(255,255,255)",
          }
        }
      }
    });
}
