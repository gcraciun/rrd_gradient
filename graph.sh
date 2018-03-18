#!/bin/sh

TITLE="Temp Gradient"
VLABEL="Degrees Celsius"
WTRMRK="Watermark_Sensor_Source"
RRDFILE="/pi-three/my-rrd/rrd/2303-ext-data.rrd"
DS="temp"
UPLIMIT="40"
ADJUST="2"

rrdtool graph x.png --imgformat=PNG --start=-864000 --end=now --title="$TITLE" --slope-mode --vertical-label="$VLABEL" --height=200 --width=900 \
	--alt-autoscale-max -X 0 -W "WTRMRK" \
	-c "BACK#2f3949" \
	-c "FONT#FFFFFF" \
	-c "AXIS#05ad24" \
	-c "FRAME#05ad24" \
	-c "ARROW#05ad24" \
	"DEF:temp=$RRDFILE:$DS:AVERAGE"	\
	"VDEF:max_temp=temp,MAXIMUM" \
	"CDEF:uplmt_temp=$UPLIMIT,temp,POP" \
	"CDEF:dnlmt_temp=0,temp,POP" \
	"CDEF:adj=$ADJUST,temp,POP" \
	"CDEF:uplmt_05=uplmt_temp,.05,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_10=uplmt_temp,.1,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_15=uplmt_temp,.15,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_20=uplmt_temp,.2,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_25=uplmt_temp,.25,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_30=uplmt_temp,.3,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_35=uplmt_temp,.35,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_40=uplmt_temp,.4,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_45=uplmt_temp,.45,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_50=uplmt_temp,.5,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_55=uplmt_temp,.55,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_60=uplmt_temp,.6,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_65=uplmt_temp,.65,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_70=uplmt_temp,.7,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_75=uplmt_temp,.75,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_80=uplmt_temp,.8,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_85=uplmt_temp,.85,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_90=uplmt_temp,.9,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_95=uplmt_temp,.95,*,dnlmt_temp,max_temp,adj,+,LIMIT" \
	"CDEF:uplmt_100=uplmt_temp,1,*,dnlmt_temp,max_temp,LIMIT" \
	"AREA:uplmt_100#FF0000" \
	"AREA:uplmt_95#FB0D00" \
	"AREA:uplmt_90#F81A00" \
	"AREA:uplmt_85#F42800" \
	"AREA:uplmt_80#F13500" \
	"AREA:uplmt_75#EE4300" \
	"AREA:uplmt_70#EA5000" \
	"AREA:uplmt_65#E75D00" \
	"AREA:uplmt_60#E46B00" \
	"AREA:uplmt_55#E07800" \
	"AREA:uplmt_50#DD8600" \
	"AREA:uplmt_45#D99300" \
	"AREA:uplmt_40#D6A100" \
	"AREA:uplmt_35#D3AE00" \
	"AREA:uplmt_30#CFBB00" \
	"AREA:uplmt_25#CCC900" \
	"AREA:uplmt_20#C9D600" \
	"AREA:uplmt_15#C5E400" \
	"AREA:uplmt_10#C2F100" \
	"AREA:uplmt_05#BFFF00" \
	"AREA:dnlmt_temp#FFFFFF" \
	"LINE1:temp#2f3949" \
	"CDEF:infinity=temp,POP,INF" \
	"AREA:infinity#2f3949:STACK" \
	"COMMENT:From START To END\c" \
	"COMMENT:\\n" \
	"COMMENT: \\n" \
	"COMMENT:Temperature" \
	"GPRINT:temp:LAST:Current\:%8.2lf" \
	"GPRINT:temp:AVERAGE:Average\:%8.2lf" \
	"GPRINT:temp:MIN:Minimum\:%8.2lf" \
	"GPRINT:temp:MAX:Maximum\:%8.2lf" \
