    google.load("visualization", "1.0", {"packages": ["corechart"]});

$(function() {
    google.setOnLoadCallback(drawCharts());
});


function drawCharts() {
    draw1wAttackBarChart();
    //draw1mAttackBarChart();
    draw1mScatterChart();
}


function draw1wAttackBarChart() {
    
    //get today's date
    var dToday = new Date();
    
    //get array of 7 days prior to (and including) today
    var aTmpData = populateColChartArray(jso1w, 7);

    //convert into google data table
    var data = google.visualization.arrayToDataTable(aTmpData);
        
    var options = {
        title: "Number of Attacks in the Past Week",
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
    
    //figure out how many days it has been since (today-1 month)
    var d1MonthAgo = getTimeAgo(dToday, 1, "month");
    var dDiff = Math.round((dToday - d1MonthAgo)/(1000*60*60*24));
    
    var aTmpData = populateColChartArray(jso1m, dToday, dDiff);
    
    //convert into google data table
    var data = google.visualization.arrayToDataTable(aTmpData);
    
    var options = {
        title: "Number of Attacks in the Past Month",
        titleTextStyle: {fontSize: 14, bold: true},
        chartArea: {"width": "85%", "height": "80%", "left": "40"}, 
        legend: { position: 'none' },
        bar: { groupWidth: '85%' }
    };
    
    var chart = new google.visualization.ColumnChart($("#chart-2")[0]);
    chart.draw(data, options);
}

function draw1mScatterChart() {
    
    //get today's date
    var dToday = new Date();
    
    //figure out how many days it has been since (today-1 month)
    var d1MonthAgo = getTimeAgo(dToday, 1, "month");
    
    var aTmpData = populateScatterArray(jso1m, dToday);
    
    //convert into google data table
    var data = google.visualization.arrayToDataTable(aTmpData);
    
    var options = {
        title: "Level of Attacks in the Past Month",
        titleTextStyle: {fontSize: 14, bold: true},
        chartArea: {"width": "85%", "height": "80%", "left": "40"}, 
        legend: "none",
        hAxis: {title: "Days Ago", titleTextStyle: {bold: true}, gridlines: {count: 6}},
        vAxis: {title: "Level", minValue: 0, maxValue: 10, format: "short",  titleTextStyle: {bold: true}, gridlines: {count: 6}}
    };
    
    var chart = new google.visualization.ScatterChart($("#chart-2")[0]);
    chart.draw(data, options);
}


function drawAttackBarChart(chartWM) {
    
    //get today's date
    var dToday = new Date();


}


function getTimeAgo(today, interval, intervaltype) {
    if (today && interval && intervaltype) {
        var dTimeAgo = new Date();
        
        switch (intervaltype.toLowerCase()) {
            case "month":
                dTimeAgo.setMonth(today.getMonth() - interval);
                break;
            case "day":
                dTimeAgo.setDate(today.getDate() - interval);
                break;
            default:
                dTimeAgo = today;
        }
        
        return dTimeAgo;
    }
}


function populateColChartArray(attackarray, daysago) {

    daysago = daysago || 7;
    daysago--;

    var outputarray = [['Day', 'Attacks: ', {role: 'annotation'} ]];

    for (var i=daysago; i>=0; i--) {
        var dDate = new Date();
        dDate.setDate(dDate.getDate() - i);
        dUTC = dDate.toISOString().match(/[^T]*/);

        var acnt = 0
        for (var n=0; n<attackarray.length; n++) {
            if (attackarray[n].date == dUTC) {
                acnt = parseInt(attackarray[n].cnt);
                break;
            }
        }

        outputarray.push([dDate.getDate() + "/" + (dDate.getMonth()+1), parseInt(acnt), ""]);
    }
    
    return outputarray;
}

function populateScatterArray(attackarray, d2d, d1m) {

    var outputarray = [['Day', 'Level']];

    for (var i=0; i<attackarray.length; i++) {
        var aDate = new Date(attackarray[i].date.substr(0, 10));
        aDate.setHours(parseInt(attackarray[i].date.substr(11, 13)));
        aDate.setMinutes(parseInt(attackarray[i].date.substr(14, 16)));
        
        var dDiff = Math.round((d2d - aDate)/(1000*60*60*24));
        
        console.log(attackarray[i].date + " > " + aDate + " diff > " + dDiff);
        
        outputarray.push([dDiff, parseInt(attackarray[i].level)]);
    }
    
    return outputarray;
}