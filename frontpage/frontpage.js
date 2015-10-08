google.load("visualization", "1.0", {"packages": ["corechart"]});

$(function() {
    google.setOnLoadCallback(drawCharts());
});


function drawCharts() {
    draw1wAttackBarChart();
    draw1mAttackBarChart();
}


function draw1wAttackBarChart() {
    
    //get today's date
    var dToday = new Date();
    
    //get array of 7 days prior to (and including) today
    var d1WeekAgo = new Date();
    d1WeekAgo.setDate(dToday.getDate() - 7);
    
    //populate array with data from text field - leave non matches at zero
    var aTmpData = [['Day', 'Attacks: ', {role: 'annotation'} ]];
    
    for (var i=6; i>=0; i--) {
        var dDate = new Date();
        dDate.setDate(dToday.getDate() - i);
        dUTC = dDate.toISOString().match(/[^T]*/);

        var acnt = 0
        for (var n=0; n<jso1w.length; n++) {
            acnt = (jso1w[n].date == dUTC) ? parseInt(jso1w[n].cnt) : 0;
        }
        
        aTmpData.push([dDate.getDate() + "/" + dDate.getMonth(), parseInt(acnt), parseInt(acnt)]);
    }
    
    console.log(aTmpData);
    
    //convert into google data table
    var data = google.visualization.arrayToDataTable(aTmpData);
    
    var options = {
        title: "Attacks in the past week",
        titleTextStyle: {fontSize: 14},
        chartArea: {"width": "85%", "height": "80%", "left": "40"}, 
        legend: { position: 'none' },
        bar: { groupWidth: '85%' }
    };
    
    var chart = new google.visualization.ColumnChart($("#chart-1")[0]);
    chart.draw(data, options);
}


function draw1mAttackBarChart() {
    
    //get today's date
    var dToday = new Date();
    
    //get array of 7 days prior to (and including) today
    var d1MonthAgo = new Date();
    d1MonthAgo.setMonth(dToday.getMonth() - 1);
    var dDiff = Math.round((dToday - d1MonthAgo)/(1000*60*60*24));
    
    //populate array with data from text field - leave non matches at zero
    var aTmpData = [['Day', 'Attacks: ', {role: 'annotation'} ]];
    
    for (var i=dDiff; i>=0; i--) {
        var dDate = new Date();
        dDate.setDate(dToday.getDate() - i);
        dUTC = dDate.toISOString().match(/[^T]*/);

        
        var acnt = 0
        for (var n=0; n<jso1m.length; n++) {
            if (jso1m[n].date == dUTC) {
                acnt = parseInt(jso1m[n].cnt);
                break;
            }
        }
        
        aTmpData.push([dDate.getDate() + "/" + dDate.getMonth(), parseInt(acnt), ""]);
    }
    
    //convert into google data table
    var data = google.visualization.arrayToDataTable(aTmpData);
    
    var options = {
        title: "Attacks in the past month",
        titleTextStyle: {fontSize: 14},
        chartArea: {"width": "85%", "height": "80%", "left": "40"}, 
        legend: { position: 'none' },
        bar: { groupWidth: '85%' }
    };
    
    var chart = new google.visualization.ColumnChart($("#chart-2")[0]);
    chart.draw(data, options);
}
