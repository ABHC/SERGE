.graphContainer
{
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	align-items: flex-start;
	width: 78%;
	height: 75vh;
	background-color: rgba(0, 0, 0, 0.4);
	border-radius: 3px;
	border: 1px solid rgba(255,255,255,0.1);
	padding: 1%;
	overflow: hidden;
}

.graphSubContainer
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	width: 33.3%;
}

.graph
{
	display: flex;
	flex-direction: column;
	justify-content: space-around;
	align-items: flex-end;
	height: 75vh;
	width: 100%;
}

.graphSubContainer input
{
	display: none;
}

.graphSubContainer input:checked + .graph
{
	order: 1;
}

.graphSubContainer input:not(:checked) + .graph
{
	order: 2;
	display: none;
}

div.prev,
div.next
{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	height: 7%;
	background-color: rgba(255,255,255,0.1);
	text-decoration: none;
	color: rgba(255, 255, 255, 0.4);
	font-size: 30px;
	cursor: pointer;
	user-select: none;
	-moz-user-select: none;
	-webkit-user-select: none;
	opacity: 0;
	transition: .7s;
}

div.prev label.arrow,
div.next label.arrow
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	height: 3vw;
	transform: rotate(90deg);
	text-decoration: none;
	font-size: 3vw;
	color: rgba(255, 255, 255, 0.4);
	cursor: pointer;
	user-select: none;
	-moz-user-select: none;
	-webkit-user-select: none;
}

div.prev:hover,
div.next:hover
{
	opacity: 1;
	transition: .5s;
}

div.prev::selection,
div.next::selection
{
	background-color: rgba(0, 0, 0, 0);
}

/* Charts */
/* BEGIN 00-00 Chart */

.containerChart
{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	width: 90%;
	height: 85%;
}

ul
{
	list-style-type: none;
	margin: 0;
	padding-left: 0;
}

.titleChart
{
	display: block;
	margin: 0;
	text-align: center;
	font-size: 15px;
	color: #f9f9ff;
}

.axis-x,
.axis-y
{
	left: 0;
	width: 100%;
	height: 80%;
}

[data-axisy]:before
{
	content: attr(data-axisy);
	display: inline-block;
	position: relative;
	width: 2rem;
	text-align: right;
	line-height: 0;
	left: -15%;
	top: 80%;
}

.axis-x
{
	height: 18.7%;
}

.axis-x li
{
	width: 33%;
	float: left;
	text-align: center;
}

.bar-chart
{
	width: 95%;
	height: 95%;
	position: relative;
	color: #f9f9ff;
	font-size: 80%;
}

.bar-chart .axis-x
{
	bottom: 0;
}

.axis-y li
{
	height: calc(20% - 1px);
	border-bottom: 1px solid rgba(255,255,255,0.15);
}

.bar-chart .axis-x li
{
	width: 12.5%;
	height: 40%;
	position: relative;
	text-align: left;
	bottom: 0;
}

.bar-chart .axis-x li i
{
	display: flex;
	flex-direction: row-reverse;
	transform: rotatez(-45deg);
	transform-origin: 70% 220%;
	white-space : nowrap;
}

.bar-chart .axis-x li:before
{
	content: '';
	position: absolute;
	bottom: 100%;
	width: 70%;
}
/* TODO Replace id selectro # by class . selector */
#graph_00_00 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 570%;
	animation-duration: 5s;
	animation-name: charts00_01;
}

@keyframes charts00_01
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 570%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 885%;
	animation-duration: 5s;
	animation-name: charts00_02;
}

@keyframes charts00_02
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 885%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 400%;
	animation-duration: 5s;
	animation-name: charts00_03;
}

@keyframes charts00_03
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 400%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 290%;
	animation-duration: 5s;
	animation-name: charts00_04;
}

@keyframes charts00_04
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 290%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 680%;
	animation-duration: 5s;
	animation-name: charts00_05;
}

@keyframes charts00_05
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 680%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 885%;
	animation-duration: 5s;
	animation-name: charts00_06;
}

@keyframes charts00_06
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 885%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 520%;
	animation-duration: 5s;
	animation-name: charts00_07;
}

@keyframes charts00_07
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 520%;
	}
}

#graph_00_00 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 620%;
	animation-duration: 5s;
	animation-name: charts00_08;
}

@keyframes charts00_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 620%;
	}
}

#graph_00_00 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_00_00 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}

#graph_01_00 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 500%;
	animation-duration: 5s;
	animation-name: charts01_01;
}

@keyframes charts01_01
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 500%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 445%;
	animation-duration: 5s;
	animation-name: charts01_02;
}

@keyframes charts01_02
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 445%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 670%;
	animation-duration: 5s;
	animation-name: charts01_03;
}

@keyframes charts01_03
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 670%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 205%;
	animation-duration: 5s;
	animation-name: charts01_04;
}

@keyframes charts01_04
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 205%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 450%;
	animation-duration: 5s;
	animation-name: charts01_05;
}

@keyframes charts01_05
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 450%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 825%;
	animation-duration: 5s;
	animation-name: charts01_06;
}

@keyframes charts01_06
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 825%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 420%;
	animation-duration: 5s;
	animation-name: charts01_07;
}

@keyframes charts01_07
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 420%;
	}
}

#graph_01_00 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 600%;
	animation-duration: 5s;
	animation-name: charts01_08;
}

@keyframes charts01_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 600%;
	}
}

#graph_01_00 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_01_00 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}

#graph_02_00 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 450%;
	animation-duration: 5s;
	animation-name: charts02_01;
}

@keyframes charts02_01
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 450%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 50%;
	animation-duration: 5s;
	animation-name: charts02_02;
}

@keyframes charts02_02
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 50%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 400%;
	animation-duration: 5s;
	animation-name: charts02_03;
}

@keyframes charts02_03
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 400%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 190%;
	animation-duration: 5s;
	animation-name: charts02_04;
}

@keyframes charts02_04
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 190%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 420%;
	animation-duration: 5s;
	animation-name: charts02_05;
}

@keyframes charts02_05
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 420%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 205%;
	animation-duration: 5s;
	animation-name: charts02_06;
}

@keyframes charts02_06
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 205%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 480%;
	animation-duration: 5s;
	animation-name: charts02_07;
}

@keyframes charts02_07
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 480%;
	}
}

#graph_02_00 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 600%;
	animation-duration: 5s;
	animation-name: charts02_08;
}

@keyframes charts02_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 600%;
	}
}

#graph_02_00 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_02_00 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}


/* END 00-00 chart */
/* BEGIN 00-01 Chart */
.donut-chart *
{
	box-sizing: border-box;
}

.torChart
{
	position: relative;
	width: 200px;
	height: 200px;
	margin: 0 auto 2rem;
	border-radius: 100%
}

p.center-date
{
	position: absolute;
	top:0;
	left:0;
	bottom:0;
	right:0;
	display: flex;
	justify-content: center;
	align-items: center;
	background: #333b44;
	text-align: center;
	font-size: 28px;
	width: 67%;
	height: 67%;
	margin: auto;
	border-radius: 50%;
}

.resultPart
{
	border-radius: 50%;
	clip: rect(0px, 200px, 200px, 100px);
	height: 100%;
	position: absolute;
	width: 100%;
	transition: transform .8s;
}

.source
{
	border-radius: 50%;
	clip: rect(0px, 100px, 200px, 0px);
	height: 100%;
	position: absolute;
	width: 100%;
	font-family: monospace;
	font-size: 1.5rem;
	transition: transform .8s;
}

.resultPart:nth-child(1)
{
	transform: rotate(0deg);
}

.resultPart:nth-child(1) .source
{
	background-color: rgb(0,85,127);
	transform: rotate(76deg);
	opacity: 0.7;
}

.resultPart:nth-child(1) .source:hover
{
	opacity: 1;
}

.resultPart:nth-child(2)
{
	transform: rotate(76deg);
}

.resultPart:nth-child(2) .source
{
	background-color: rgb(32,117,32);
	transform: rotate(139deg);
	opacity: 0.7;
	z-index: 300;
}

.resultPart:nth-child(2):hover
{
	opacity: 1;
}

.resultPart:nth-child(3)
{
	transform: rotate(215deg);
}

.resultPart:nth-child(3) .source
{
	background-color: rgb(64,22,64);
	transform: rotate(113deg);
	opacity: 0.7;
}

.resultPart:nth-child(3) .source:hover
{
	opacity: 1;
}

.resultPart:nth-child(4)
{
	transform:rotate(-32deg);
}

.resultPart:nth-child(4) .source
{
	background-color: rgb(96,54,96);
	transform: rotate(32deg);
	opacity: 0.7;
}

.resultPart:nth-child(4) .source:hover
{
	opacity: 1;
}
/* END 00-01 chart */
/**/
.torChart,
.circle
{
	width: 200px;
	height: 200px;
	border-radius: 50%;
	overflow: hidden;
}

#graph_00_01 .source1
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s1
{
	position: relative;
	left: 50%;
	top: 0;
	height: 50%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(-30deg);
	transform-origin: left bottom;
}

#graph_00_01 .s1:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(60deg);
	transform-origin: right bottom;
}

#graph_00_01 .s1:hover
{
	background-color: rgb(0,120,150);
}

#graph_00_01 .s1:hover:before
{
	background-color: rgb(0,120,150);
}

#graph_00_01 .source2
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s2
{
	position: relative;
	left: -50%;
	top: 0;
	height: 50%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(-58deg);
	transform-origin: left bottom;
	opacity: 0.7;
}

#graph_00_01 .s2:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(29deg);
	transform-origin: right bottom;
}

#graph_00_01 .s2:hover
{
	background-color: rgb(41,150,41);
}

#graph_00_01 .s2:hover:before
{
	background-color: rgb(41,150,41);
}

#graph_00_01 .source3
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s3
{
	position: relative;
	top: 0;
	left: -150%;
	height: 50%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(-172deg);
	transform-origin: left bottom;
}

#graph_00_01 .s3:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(114deg);
	transform-origin: right bottom;
}

#graph_00_01 .s3:hover
{
	background-color: rgb(80,27,80);

}

#graph_00_01 .s3:hover:before
{
	background-color: rgb(80,27,80);

}

#graph_00_01 .source4
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_00_01 .s4
{
	position: relative;
	top: -50%;
	left: -250%;
	height: 100%;
	width: 80%;
	background-color: rgb(96,54,96);
	transform: rotate(98deg);
	transform-origin: left bottom;
}

#graph_00_01 .s4:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100%;
	background-color: rgb(96,54,96);
	transform: rotate(22deg);
	transform-origin: right bottom;
}

#graph_00_01 .s4:hover
{
	background-color: rgb(110,61,110);
}

#graph_00_01 .s4:hover:before
{
	background-color: rgb(110,61,110);
}

#graph_01_02 .source1
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s1
{
	position: relative;
	left: 50%;
	top: 0;
	height: 50%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(-30deg);
	transform-origin: left bottom;
}

#graph_01_02 .s1:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(60deg);
	transform-origin: right bottom;
}

#graph_01_02 .s1:hover
{
	background-color: rgb(0,120,150);
}

#graph_01_02 .s1:hover:before
{
	background-color: rgb(0,120,150);
}

#graph_01_02 .source2
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s2
{
	position: relative;
	left: -50%;
	top: 0;
	height: 50%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(-58deg);
	transform-origin: left bottom;
	opacity: 0.7;
}

#graph_01_02 .s2:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(29deg);
	transform-origin: right bottom;
}

#graph_01_02 .s2:hover
{
	background-color: rgb(41,150,41);
}

#graph_01_02 .s2:hover:before
{
	background-color: rgb(41,150,41);
}

#graph_01_02 .source3
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s3
{
	position: relative;
	top: 0;
	left: -150%;
	height: 50%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(-172deg);
	transform-origin: left bottom;
}

#graph_01_02 .s3:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(114deg);
	transform-origin: right bottom;
}

#graph_01_02 .s3:hover
{
	background-color: rgb(80,27,80);

}

#graph_01_02 .s3:hover:before
{
	background-color: rgb(80,27,80);

}

#graph_01_02 .source4
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_01_02 .s4
{
	position: relative;
	top: -50%;
	left: -250%;
	height: 100%;
	width: 80%;
	background-color: rgb(96,54,96);
	transform: rotate(98deg);
	transform-origin: left bottom;
}

#graph_01_02 .s4:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100%;
	background-color: rgb(96,54,96);
	transform: rotate(22deg);
	transform-origin: right bottom;
}

#graph_01_02 .s4:hover
{
	background-color: rgb(110,61,110);
}

#graph_01_02 .s4:hover:before
{
	background-color: rgb(110,61,110);
}

#graph_02_02 .source1
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s1
{
	position: relative;
	left: 50%;
	top: 0;
	height: 50%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(-30deg);
	transform-origin: left bottom;
}

#graph_02_02 .s1:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 50px;
	background-color: rgb(0,85,127);
	transform: rotate(60deg);
	transform-origin: right bottom;
}

#graph_02_02 .s1:hover
{
	background-color: rgb(0,120,150);
}

#graph_02_02 .s1:hover:before
{
	background-color: rgb(0,120,150);
}

#graph_02_02 .source2
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s2
{
	position: relative;
	left: -50%;
	top: 0;
	height: 50%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(-58deg);
	transform-origin: left bottom;
	opacity: 0.7;
}

#graph_02_02 .s2:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 25px;
	background-color: rgb(32,117,32);
	transform: rotate(29deg);
	transform-origin: right bottom;
}

#graph_02_02 .s2:hover
{
	background-color: rgb(41,150,41);
}

#graph_02_02 .s2:hover:before
{
	background-color: rgb(41,150,41);
}

#graph_02_02 .source3
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s3
{
	position: relative;
	top: 0;
	left: -150%;
	height: 50%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(-172deg);
	transform-origin: left bottom;
}

#graph_02_02 .s3:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100px;
	background-color: rgb(64,22,64);
	transform: rotate(114deg);
	transform-origin: right bottom;
}

#graph_02_02 .s3:hover
{
	background-color: rgb(80,27,80);

}

#graph_02_02 .s3:hover:before
{
	background-color: rgb(80,27,80);

}

#graph_02_02 .source4
{
	position: relative;
	width: 100%;
	height: 100%;
	transform: rotate(90deg);
}

#graph_02_02 .s4
{
	position: relative;
	top: -50%;
	left: -250%;
	height: 100%;
	width: 80%;
	background-color: rgb(96,54,96);
	transform: rotate(98deg);
	transform-origin: left bottom;
}

#graph_02_02 .s4:before
{
	content: '';
	position: absolute;
	left: -100%;
	right: 0;
	height: 100%;
	width: 100%;
	background-color: rgb(96,54,96);
	transform: rotate(22deg);
	transform-origin: right bottom;
}

#graph_02_02 .s4:hover
{
	background-color: rgb(110,61,110);
}

#graph_02_02 .s4:hover:before
{
	background-color: rgb(110,61,110);
}

.center
{
	position: relative;
	left: 12.5%;
	top: -87.5%;
	background-color: rgb(51,59,68);
	width: 150px;
	height: 150px;
	border-radius: 50%;
	z-index: 1000;
}
/**/

/*Chart point curve*/
.backChart
{
	width: 95%;
	height: 95%;
	font-size: 80%;
}


[data-time]:before
{
	content: attr(data-time);
	display: inline-block;
	position: relative;
	width: 2rem;
	text-align: right;
	line-height: 0;
	left: -15%;
	top: 80%;
}

.axis-time
{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	width: 95%;
	margin-top: 5%;
}

.axis-time li
{
	display: flex;
	flex-direction: row-reverse;
	transform: rotatez(-45deg);
	transform-origin: 70% 220%;
	white-space : nowrap;
}

.curveChart
{
	position: relative;
	top: -68%;
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
	width: 100%;
	height: 64%;
}

#graph_01_01 .curveChartPart
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: center;
	width: 16.7%;
	height: 100%;
}

#graph_01_01 .curveChartPart:nth-child(1) .query01
{
	position: relative;
	top: calc(10% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}


#graph_01_01 .curveChartPart:nth-child(1) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 850%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(65deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(1) .query02
{
	position: relative;
	top: calc(20% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(1) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(-39deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(1) .query03
{
	position: relative;
	top: calc(3% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(1) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 600%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(55deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(2) .query01
{
	position: relative;
	top: calc(50% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(2) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-15deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(2) .query02
{
	position: relative;
	top: calc(5% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(2) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 950%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(67deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(2) .query03
{
	position: relative;
	top: calc(30% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(2) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 550%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(3) .query01
{
	position: relative;
	top: calc(45% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(3) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 700%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(58deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(3) .query02
{
	position: relative;
	top: calc(50% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(3) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(28deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(3) .query03
{
	position: relative;
	top: calc(10% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(3) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-10deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(4) .query01
{
	position: relative;
	top: calc(75% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(4) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(13.5deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(4) .query02
{
	position: relative;
	top: calc(60% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(4) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(27deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(4) .query03
{
	position: relative;
	top: calc(7% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(4) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(43deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(5) .query01
{
	position: relative;
	top: calc(80% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(5) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(5) .query02
{
	position: relative;
	top: calc(70% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(5) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(-16deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(5) .query03
{
	position: relative;
	top: calc(25% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(5) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(-28.5deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_01_01 .curveChartPart:nth-child(6) .query01
{
	position: relative;
	top: calc(60% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_01_01 .curveChartPart:nth-child(6) .query02
{
	position: relative;
	top: calc(65% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_01_01 .curveChartPart:nth-child(6) .query03
{
	position: relative;
	top: calc(15% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart
{
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: center;
	width: 16.7%;
	height: 100%;
}

#graph_02_01 .curveChartPart:nth-child(1) .query01
{
	position: relative;
	top: calc(10% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}


#graph_02_01 .curveChartPart:nth-child(1) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 850%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(65deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(1) .query02
{
	position: relative;
	top: calc(20% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(1) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(-39deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(1) .query03
{
	position: relative;
	top: calc(3% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(1) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 600%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(55deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(2) .query01
{
	position: relative;
	top: calc(50% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(2) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-15deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(2) .query02
{
	position: relative;
	top: calc(5% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(2) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 950%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(67deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(2) .query03
{
	position: relative;
	top: calc(30% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(2) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 550%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(3) .query01
{
	position: relative;
	top: calc(45% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(3) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 700%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(58deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(3) .query02
{
	position: relative;
	top: calc(50% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(3) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 450%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69, 0.8);
	transform: rotate(28deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(3) .query03
{
	position: relative;
	top: calc(10% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(3) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255, 0.8);
	transform: rotate(-10deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(4) .query01
{
	position: relative;
	top: calc(75% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(4) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 350%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(13.5deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(4) .query02
{
	position: relative;
	top: calc(60% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(4) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(27deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(4) .query03
{
	position: relative;
	top: calc(7% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(4) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(43deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(5) .query01
{
	position: relative;
	top: calc(80% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(5) .query01:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 500%;
	height: 0.6vw;
	background-color: rgba(67,127,255,0.8);
	transform: rotate(-47deg);
	transform-origin: left center;
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(5) .query02
{
	position: relative;
	top: calc(70% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(5) .query02:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(68, 255, 69,0.8);
	transform: rotate(-16deg);
	transform-origin: left center;
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(5) .query03
{
	position: relative;
	top: calc(25% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(5) .query03:after
{
	content: '';
	position: absolute;
	top: 25%;
	left: 50%;
	width: 400%;
	height: 0.6vw;
	background-color: rgba(161, 68, 255,0.8);
	transform: rotate(-28.5deg);
	transform-origin: left center;
	z-index: 3;
}

#graph_02_01 .curveChartPart:nth-child(6) .query01
{
	position: relative;
	top: calc(60% - 0.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(67,127,255,1);
	z-index: 1;
}

#graph_02_01 .curveChartPart:nth-child(6) .query02
{
	position: relative;
	top: calc(65% - 1.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(68, 255, 69, 1);
	z-index: 2;
}

#graph_02_01 .curveChartPart:nth-child(6) .query03
{
	position: relative;
	top: calc(15% - 2.5vw);
	width: 1vw;
	height: 1vw;
	border-radius: 50%;
	background-color: rgba(161, 68, 255, 1);
	z-index: 3;
}

/* Double bart chart */

.bar-chart .axis-x li:after
{
	content: '';
	position: absolute;
	left: 0;
	width: 70%;
}

#graph_00_02 .bar-chart .axis-x li:nth-child(1):before
{
	background: rgba(67,127,255,0.8);
	height: 520%;
	animation-duration: 5s;
	animation-name: doubleCharts00_01before;
}

@keyframes doubleCharts00_01before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 520%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(1):after
{
	top: -220%;
	background: rgba(68, 255, 148, 0.9);
	height: 220%;
	animation-duration: 5s;
	animation-name: doubleCharts00_01after;
}

@keyframes doubleCharts00_01after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -220%;
		height: 220%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2):before
{
	background: rgba(52,99,200,0.8);
	height: 285%;
	animation-duration: 5s;
	animation-name: doubleCharts00_02before;
}

@keyframes doubleCharts00_02before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 285%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2):after
{
	top: -255%;
	background: rgba(85,170,127,0.8);
	height: 255%;
	animation-duration: 5s;
	animation-name: doubleCharts00_02after;
}

@keyframes doubleCharts00_02after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -255%;
		height: 255%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(3):before
{
	background: rgba(67,127,255,0.8);
	height: 420%;
	animation-duration: 5s;
	animation-name: doubleCharts00_03before;
}

@keyframes doubleCharts00_03before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 420%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(3):after
{
	top: -55%;
	background: rgba(68, 255, 148, 0.9);
	height: 55%;
	animation-duration: 5s;
	animation-name: doubleCharts00_03after;
}

@keyframes doubleCharts00_03after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -55%;
		height: 55%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(4):before
{
	background: rgba(52,99,200,0.8);
	height: 220%;
	animation-duration: 5s;
	animation-name: doubleCharts00_04before;
}

@keyframes doubleCharts00_04before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 220%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(4):after
{
	top: -110%;
	background: rgba(85,170,127,0.8);
	height: 110%;
	animation-duration: 5s;
	animation-name: doubleCharts00_04after;
}

@keyframes doubleCharts00_04after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -110%;
		height: 110%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(5):before
{
	background: rgba(67,127,255,0.8);
	height: 282%;
	animation-duration: 5s;
	animation-name: doubleCharts00_05before;
}

@keyframes doubleCharts00_05before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 282%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(5):after
{
	top: -210%;
	background: rgba(68, 255, 148, 0.9);
	height: 210%;
	animation-duration: 5s;
	animation-name: doubleCharts00_05after;
}

@keyframes doubleCharts00_05after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -210%;
		height: 210%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(6):before
{
	background: rgba(52,99,200,0.8);
	height: 285%;
	animation-duration: 5s;
	animation-name: doubleCharts00_06before;
}

@keyframes doubleCharts00_06before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 285%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(6):after
{
	top: -110%;
	background: rgba(85,170,127,0.8);
	height: 110%;
	animation-duration: 5s;
	animation-name: doubleCharts00_06after;
}

@keyframes doubleCharts00_06after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -110%;
		height: 110%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(7):before
{
	background: rgba(67,127,255,0.8);
	height: 20%;
	animation-duration: 5s;
	animation-name: doubleCharts00_07before;
}

@keyframes doubleCharts00_07before
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 20%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(7):after
{
	top: -10%;
	background: rgba(68, 255, 148, 0.9);
	height: 10%;
	animation-duration: 5s;
	animation-name: doubleCharts00_07after;
}

@keyframes doubleCharts00_07after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -10%;
		height: 10%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(8):before
{
	background: rgba(52,99,200,0.8);
	height: 220%;
	animation-duration: 5s;
	animation-name: doubleCharts00_08;
}

@keyframes doubleCharts00_08
{
	from
	{
		height: 2%;
	}

	to
	{
		height: 220%;
	}
}

#graph_00_02 .bar-chart .axis-x li:nth-child(8):after
{
	top: -100%;
	background: rgba(85,170,127,0.9);
	height: 100%;
	animation-duration: 5s;
	animation-name: doubleCharts00_08after;
}

@keyframes doubleCharts00_08after
{
	from
	{
		top: -2%;
		height: 2%;
	}

	to
	{
		top: -100%;
		height: 100%;
	}
}

#graph_00_02 .bar-chart .axis-x li:hover::before
{
	background: rgba(67,127,255,1);
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2n):hover::before
{
	background: rgba(52,99,200,1);
}

#graph_00_02 .bar-chart .axis-x li:hover::after
{
	background: rgba(68, 255, 148, 1);
}

#graph_00_02 .bar-chart .axis-x li:nth-child(2n):hover::after
{
	background: rgba(85,170,127,1);
}
