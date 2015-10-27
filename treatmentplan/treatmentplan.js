google.load("visualization", "1.1", {"packages": ["line"]});

$(function() {
    
    //google.setOnLoadCallback(drawCharts());
    
    var today = new Date();
    
    refreshRenderValues();
    
    var cData = prepareChartData(planjso);
    
    var cOptions = {
            chart: {
              title: 'Treatment Plan Chart',
              subtitle: 'Today\'s Date: ' + (today.getMonth()+1) + "/" + today.getDate()
            },
            width: "100%",
            height: 300
          };

    var chart = new google.charts.Line($("#divChart")[0]);
    chart.draw(cData, cOptions);
    
    populateTreatmentList(planjso);
    
    $("#selTreatment").on("change", function(i, v) {
        populateDoseList(planjso, $("#selTreatment option:selected").val());
    });
    
    $("#selTreatment").val($("#selTreatment option:first").val());
    populateDoseList(planjso, $("#selTreatment option:selected").val());
    
    
    $("#btnUpdDos").on("click", function() {
        $("#hidJSO").val(JSON.stringify(planjso));
    });
    
    
});
  
  
function refreshRenderValues() {
    
    var mindate = new Date();
    var maxdate = new Date();
    mindate.setDate(mindate.getDate() - 1); 
    maxdate.setDate(maxdate.getDate() + 1);
    
    //loop through the jso treatments
    for (var t=0; t<planjso.treatments.length; t++) { 
        
        var treatment = planjso.treatments[t];
        var maxdose = 0;
        
        //loop through the doses
        for (var d=0; d<treatment.doses.length; d++) {
            var dose = treatment.doses[d];
            maxdose = (dose.totaldose > maxdose) ? dose.totaldose : maxdose;
            
            var mid = new Date(dose.dfrom);
            var mad = new Date(dose.dto);
            mindate = (mid < mindate) ? mid : mindate;
            maxdate = (mad > maxdate) ? mad : maxdate;
        }
        
        for (var d=0; d<treatment.doses.length; d++) {
            var dose = treatment.doses[d];
            dose.maxdosevalue = maxdose;
            dose.rendervalue = (dose.totaldose / maxdose);
        }
            
    }
    
    mindate.setDate(mindate.getDate() - 1); 
    maxdate.setDate(maxdate.getDate() + 1);
    
    planjso.mindate = mindate;
    planjso.maxdate = maxdate;
    planjso.dayspread = Math.round((maxdate - mindate)/(1000*60*60*24));
    
    //$("#rawJSO").text(JSON.stringify(planjso));
}


function prepareChartData(idata) {
    
    var aData = new google.visualization.DataTable();
    aData.addColumn("string", "Day");
    
    var tlen = idata.treatments.length;
    
    for (var i=0; i<tlen; i++) {
        aData.addColumn("number", idata.treatments[i].name);
    }
    
    
    //for each day in the chart, add a data row with "tlen" columns
    for (var d=0; d<idata.dayspread; d++) {
        
        var diq = new Date(idata.mindate);
        diq.setDate(diq.getDate() + d);
        
        aRow = [diq.getMonth()+1 + "/" + diq.getDate()];
        

        //create the columns
        for (var c=0; c<tlen; c++) {
            
            //the value pushed to the column must be the return value
            //of a function that takes the date (dayspread + d) and the 
            //treatment index (c) and returns the rendervalue of that treatment 
            //on that date
            
            aRow.push( getRenderValue(idata, diq, c) );        
        }
        
        aData.addRows([aRow]);
           
    }
    
    //$("#rawJSO").text(JSON.stringify(aData));
    return aData;
}



function getRenderValue(idata, dayinquestion, treatmentindex) {

    for (var d=0; d<idata.treatments[treatmentindex].doses.length; d++) {
        var thisdose = idata.treatments[treatmentindex].doses[d];
        var dfrom = new Date(thisdose.dfrom);
        var dto = new Date(thisdose.dto);
        dto.setHours(23);
        dto.setMinutes(59);
        
        if (dayinquestion >= dfrom && dayinquestion <= dto) {
            return thisdose.rendervalue;   
        }
    }
    
    return 0;
}


function populateTreatmentList(jso) {
    for (var t=0; t<jso.treatments.length; t++) {
        var thisTre = jso.treatments[t];
        console.log(thisTre);
        $("#selTreatment").append($("<option/>", {value: thisTre.id, text: thisTre.name} ));   
    }
}


function populateDoseList(jso, doseid) {
    var oTable = $("#tblDosages");
    var oTableRows = $("#tblDosages tr[class != 'trHeader']");
    oTableRows.html("");
    
    
    $.each(jso.treatments, function(i, o) {
        if (o.id == doseid) {
            $.each(o.doses, function(ddi, ddo) {
                var fldSelect = "<input type='checkbox' id='chkSelect_" + ddi + "'></input>";
                var fldDfrom = "<input type='text' id='txtDfrom_" + ddi + "' value='" + ddo.dfrom + "'></input>";
                var fldDto = "<input type='text' id='txtDto_" + ddi + "' value='" + ddo.dto + "'></input>";
                var fldUnits = "<input type='text' id='txtUnits_" + ddi + "' value='" + ddo.units + "'></input>";
                var fldDosage = "<input type='text' id='txtDosage_" + ddi + "' value='" + ddo.dosage + "'></input>";
                var fldXday = "<input type='text' id='txtXday_" + ddi + "' value='" + ddo.timesperday + "'></input>";
                
                oTable.append("<tr><td>" + fldSelect + "</td>" + 
                              "<td>" + fldDfrom + "</td>" + 
                              "<td>" + fldDto + "</td>" + 
                              "<td>" + fldUnits + "</td>" + 
                              "<td>" + fldDosage + "</td>" + 
                              "<td>" + fldXday + "</td>" + 
                              "</tr>");
            });
        }
    });
    
    console.log(jso);
    
}
