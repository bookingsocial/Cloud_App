		<style>
			#legendDiv li{
				list-style: none;
				margin-bottom: 2px;
				display: block;
				float: left;
				width: 85px;
				text-transform: uppercase;
				margin: 10px 0px;
				font-size:10px;
			 }
			canvas#serviceGraph,
   			 canvas#serRequestGraph{position:relative; z-index:0;} 
		</style>
		<!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo getAllServices($userDetails->Organization_Id);?></h3>
                  <p>Services</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo getAllExperts($userDetails->Organization_Id);?></h3>
                  <p>Experts</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo getAllUsers($userDetails->Organization_Id);?></h3>
                  <p>Users</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo getAllAppointments($userDetails->Organization_Id);?></h3>
                  <p>Appointments</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
			<!-- Main row 
          <div class="row">
            <section class="col-lg-12">
                <ul class="nav nav-tabs pull-right">
                  <li class="pull-left header"><i class="fa fa-inbox"></i> Appointment History</li>
                </ul>
                <div class="tab-content no-padding">
                  <div class="chart tab-pane active" style="position: relative; height: 300px;">
					<canvas id="line-SerReq" width="600" height="300"/>
					<div id="legendDiv"></div>
					<div style="bottom: 0px;right: 6px;font-weight: bold;position: absolute;display: inline-block;text-align: right" class="servivceList-block">
						<a class="btn2 btn2-link pull-right" href="./CONF_ServiceRequestListing?prv={!account}&bc=dashboard"><strong>SEE ALL</strong></a>
					</div>
				  </div>
                </div>
              </div>
            </section>
          </div> /.row (main row) -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="<?php echo base_url();?>js/lib/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
		 <!-- <script type="text/javascript">
			  var result=<?php //echo getAppointmentReport($userDetails->Organization_Id);?>;
			  		var colors={};
						colors['0'] ='#447190';
						colors['1'] ='#5FBE6D';
						colors['2'] ='#E45C5C';
						colors['3'] ='#F68C26';
						colors['4'] ='#000000';

					var highAlert={};
						highAlert['0'] ='#B00000';
						highAlert['1'] ='#E20000';
						highAlert['2'] ='#E22300';
						highAlert['3'] ='#FF3A16';
						highAlert['4'] ='#FF664A';
			  
					var highlight={};
						highlight['0'] ='#447199';
						highlight['1'] ='#5FBE6F';
						highlight['2'] ='#E45C5E';
						highlight['3'] ='#F68C26';
						highlight['4'] ='#000fff';

					var strokeColor={};
						strokeColor['In Progress'] ='rgba(68, 113, 153, 1)';
						strokeColor['Cancelled'] ='rgba(95, 190, 111, 1)';
						strokeColor['Canceled'] ='rgba(95, 190, 111, 1)';
						strokeColor['Completed'] ='rgba(228, 92, 94, 1)';
						strokeColor['No Show'] ='rgba(246, 140, 38, 1)';

					  var fillColor={};
						fillColor['In Progress'] ='rgba(68, 113, 153, 0.2)';
						fillColor['Cancelled'] ='rgba(95, 190, 111, 0.2)';
						fillColor['Canceled'] ='rgba(95, 190, 111, 0.2)';
						fillColor['Completed'] ='rgba(228, 92, 94, 0.2)';
						fillColor['No Show'] ='rgba(246, 140, 38, 0.2)';

					function gethighlight(key){
						return highlight[key];
					}

					function getcolor(key){
						return colors[key];
					}
					function gethighAlert(key){
						return highAlert[key];
					}
				  var labels =result.monthNames;
                            var  datasets=[];
                            var i= 0;
                            for(key in result.statusCounts){
                                datasets.push({

                                   'label': key,

                                   'fillColor': fillColor[key],

                                   'strokeColor': strokeColor[key],

                                   'pointColor': strokeColor[key],

                                   'pointHighlightFill':"#fff",

                                   'pointStrokeColor': "#fff",

                                   'pointHighlightStroke': strokeColor[key],

                                   'data': result.statusCounts[key],

                               });

                               i++;

                            }

                            var data = {

                                labels: labels,

                                datasets: datasets

                            };

                        var options = {
                           bezierCurve: true,
                          legendTemplate : '<ul>'
                                          +'<% for (var i=0; i<datasets.length; i++) { %>'
                                            +'<li>'
                                            +'<span style=\"border-left: 4px solid <%=datasets[i].strokeColor%>\"></span>&nbsp;'
                                            +'<% if (datasets[i].label) { %><%= datasets[i].label %><% } %>'
                                          +'</li>'
                                        +'<% } %>'
                                      +'</ul>'
                          }
                             var ctxserReqLine = document.getElementById("line-SerReq").getContext("2d");

                            var myLineChart = new Chart(ctxserReqLine).Line(data,options);

                       document.getElementById("legendDiv").innerHTML = myLineChart.generateLegend();
		  </script>-->