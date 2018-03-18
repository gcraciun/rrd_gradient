<?php
 if (!isset($_GET['reportrange'])) {
     $from = date("d/m/Y H:i", time()-86400);
     $to = date("d/m/Y H:i", time());
 } else {
     $my_arr = explode("-", $_GET['reportrange']);
     $from = trim($my_arr[0]);
     $to = trim($my_arr[1]);
   }
?>


<!DOCTYPE html>
<html dir="ltr" lang="en-US">
   <head>
      <meta charset="UTF-8" />
      <title>Temperature Readings</title>
      <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" media="all" href="daterangepicker/daterangepicker.css" />
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
      <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="daterangepicker/moment.js"></script>
      <script type="text/javascript" src="daterangepicker/daterangepicker.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
   </head>
   <body style="margin: 10px 0">


<form action="/temp.php" method="get" class="form-inline">

<div class="form-group has-feedback">
    <input type="text" name="reportrange" size="30" class="form-control" />
    <i class="glyphicon glyphicon-calendar fa fa-calendar form-control-feedback"></i>
</div>



<script type="text/javascript">
$(function() {

<?php
        print "var start =\"".$from."\";\n";
        print "var end =\"".$to."\";\n";
?>

    function cb(start, end) {
        $('#reportrange span').html(start.format('DD/MM/YYYY HH:mm') + ' - ' + end.format('DD/MM/YYYY HH:mm'));
    }

    $('input[name="reportrange"]').daterangepicker({
        startDate: start,
        endDate: end,
        timePicker: true,
        timePicker24Hour: true,
        linkedCalendars: false,
        locale: {
            format: 'DD/MM/YYYY HH:mm'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
});
</script>
<input type="submit" value="Submit">
</form>
</br>

<?php

function create_graph($output, $start, $end, $title, $rrdpath, $ds, $uplimit, $adjust, $wtr) {

    $format = "d/m/Y H:i";
    
    $from = date_create_from_format($format, $start);
    $to = date_create_from_format($format, $end);
    $from = date_timestamp_get($from);
    $to = date_timestamp_get($to);
    $start = str_replace(":","\:", $start);
    $end = str_replace(":","\:", $end);
    
    $options = array("--imgformat=PNG", "--start", $from, "--end", $to, "--title=$title",
                        "--slope-mode",
                        "--vertical-label=Degrees Celsius", "--height=200", "--width=900", "--alt-autoscale-max",
                        "-X 0",
                        "-W$wtr",
                        "-c", "BACK#2f3949",
                        "-c", "FONT#FFFFFF",
                        "-c", "AXIS#05ad24",
                        "-c", "FRAME#05ad24",
                        "-c", "ARROW#05ad24",
                        "DEF:temp=$rrdpath:$ds:AVERAGE",

#                        "HRULE:23#00000077",
                        "VDEF:max_temp=temp,MAXIMUM",
                        "CDEF:uplmt_temp=$uplimit,temp,POP",
                        "CDEF:dnlmt_temp=0,temp,POP",
                        "CDEF:adj=$adjust,temp,POP",

                        "CDEF:uplmt_05=uplmt_temp,.05,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#4
                        "CDEF:uplmt_10=uplmt_temp,.1,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#4
                        "CDEF:uplmt_15=uplmt_temp,.15,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#4
                        "CDEF:uplmt_20=uplmt_temp,.2,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#8
                        "CDEF:uplmt_25=uplmt_temp,.25,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#8
                        "CDEF:uplmt_30=uplmt_temp,.3,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#12
                        "CDEF:uplmt_35=uplmt_temp,.35,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#12
                        "CDEF:uplmt_40=uplmt_temp,.4,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#16
                        "CDEF:uplmt_45=uplmt_temp,.45,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#16
                        "CDEF:uplmt_50=uplmt_temp,.5,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#20
                        "CDEF:uplmt_55=uplmt_temp,.55,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#20
                        "CDEF:uplmt_60=uplmt_temp,.6,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#24#
                        "CDEF:uplmt_65=uplmt_temp,.65,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#24#
                        "CDEF:uplmt_70=uplmt_temp,.7,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#28
                        "CDEF:uplmt_75=uplmt_temp,.75,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#28
                        "CDEF:uplmt_80=uplmt_temp,.8,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#32
                        "CDEF:uplmt_85=uplmt_temp,.85,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#32
                        "CDEF:uplmt_90=uplmt_temp,.9,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#36
                        "CDEF:uplmt_95=uplmt_temp,.95,*,dnlmt_temp,max_temp,adj,+,LIMIT",	#36
                        "CDEF:uplmt_100=uplmt_temp,1,*,dnlmt_temp,max_temp,LIMIT",		#40

                        "AREA:uplmt_100#FF0000",
                        "AREA:uplmt_95#FB0D00",
                        "AREA:uplmt_90#F81A00",
                        "AREA:uplmt_85#F42800",
                        "AREA:uplmt_80#F13500",
                        "AREA:uplmt_75#EE4300",
                        "AREA:uplmt_70#EA5000",
                        "AREA:uplmt_65#E75D00",
                        "AREA:uplmt_60#E46B00",#
                        "AREA:uplmt_55#E07800",
                        "AREA:uplmt_50#DD8600",
                        "AREA:uplmt_45#D99300",
                        "AREA:uplmt_40#D6A100",
                        "AREA:uplmt_35#D3AE00",
                        "AREA:uplmt_30#CFBB00",
                        "AREA:uplmt_25#CCC900",
                        "AREA:uplmt_20#C9D600",
                        "AREA:uplmt_15#C5E400",
                        "AREA:uplmt_10#C2F100",
                        "AREA:uplmt_05#BFFF00",
                        "AREA:dnlmt_temp#FFFFFF",

                        "LINE1:temp#2f3949",
                        "CDEF:infinity=temp,POP,INF",
                        "AREA:infinity#2f3949:STACK",
                        
                        "COMMENT:From $start To $end\c",
                        "COMMENT:\\n",
                        "COMMENT: \\n",

                        "COMMENT:Temperature",
                        "GPRINT:temp:LAST:Current\:%8.2lf",
                        "GPRINT:temp:AVERAGE:Average\:%8.2lf",
                        "GPRINT:temp:MIN:Minimum\:%8.2lf",
                        "GPRINT:temp:MAX:Maximum\:%8.2lf",
);
    $ret = rrd_graph($output, $options);
    
    if ( !is_array($ret) ) {
        $err = rrd_error();
        echo "rrd_graph() ERROR: $err\n";
    }
}

function create_humid($output, $start, $end, $title, $rrdpath, $ds, $uplimit, $adjust, $wtr) {

    $format = "d/m/Y H:i";
    
    $from = date_create_from_format($format, $start);
    $to = date_create_from_format($format, $end);
    $from = date_timestamp_get($from);
    $to = date_timestamp_get($to);
    $start = str_replace(":","\:", $start);
    $end = str_replace(":","\:", $end);
    
    $options = array("--imgformat=PNG", "--start", $from, "--end", $to, "--title=$title",
                        "--slope-mode",
                        "--vertical-label=Humidity %", "--height=200", "--width=900", "--alt-autoscale-max",
                        "-X 0",
                        "-W$wtr",
                        "-c", "BACK#2f3949",
                        "-c", "FONT#FFFFFF",
                        "-c", "AXIS#05ad24",
                        "-c", "FRAME#05ad24",
                        "-c", "ARROW#05ad24",
                        "DEF:humid=$rrdpath:$ds:AVERAGE",

                        "LINE1:humid#2f3949",
                        
                        "COMMENT:From $start To $end\c",
                        "COMMENT:\\n",
                        "COMMENT: \\n",

                        "COMMENT:Humidity",
                        "GPRINT:humid:LAST:Current\:%8.2lf",
                        "GPRINT:humid:AVERAGE:Average\:%8.2lf",
                        "GPRINT:humid:MIN:Minimum\:%8.2lf",
                        "GPRINT:humid:MAX:Maximum\:%8.2lf",
);
    $ret = rrd_graph($output, $options);
    
    if ( !is_array($ret) ) {
        $err = rrd_error();
        echo "rrd_graph() ERROR: $err\n";
    }
}

create_graph ("out.png", $from, $to, "Living Room Temperature (DS18B20)", "/mnt/my-rrd/rrd/pi-ext-temp.rrd", "temp", 40, 2, "Sensor: DS18B20");
create_graph ("cpu.png", $from, $to, "CPU Temperature", "/mnt/my-rrd/rrd/pi-one-temp.rrd", "temp", 60, 3, "Sensor: CPU");

create_graph ("ext.png", $from, $to, "External Sensor", "/pi-three/my-rrd/rrd/2303-ext-data.rrd", "temp", 40, 2, "Sensor: External");
create_humid ("exth.png", $from, $to, "External Sensor", "/pi-three/my-rrd/rrd/2303-ext-data.rrd", "humid", 40, 2, "Sensor: External");

echo "<img src='out.png'></br></br>";
echo "<img src='cpu.png'></br></br>";
echo "<img src='ext.png'></br></br>";
echo "<img src='exth.png'></br></br>";

print "
   </body>
</html>
";
