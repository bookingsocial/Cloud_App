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
                  <i class="ion ion-pie-graph"></i>
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
                  <i class="iion ion-pie-graph"></i>
                </div>
                <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo getAllUsers($userDetails->Organization_Id,$userDetails->id);?></h3>
                  <p>Users</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
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