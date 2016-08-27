<!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        <?php echo getUpcomingAppointment($ContactDetails->Salesforce_Id,$ContactDetails->Organization_Id);?>
                                    </h3>
                                    <p>
                                        Upcoming
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-bell-o"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>-->
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>
                                        <?php echo getTotalAppointment($ContactDetails->Salesforce_Id,$ContactDetails->Organization_Id);?>
                                    </h3>
                                    <p>
                                        Total
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-tasks"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>-->
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <?php echo getCompletedAppointment($ContactDetails->Salesforce_Id,$ContactDetails->Organization_Id);?>
                                    </h3>
                                    <p>
                                        Completed
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-check-square-o"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>-->
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        <?php echo getCanceledAppointment($ContactDetails->Salesforce_Id,$ContactDetails->Organization_Id);?>
                                    </h3>
                                    <p>
                                        Cancelled
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-ban"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>-->
                            </div>
                        </div><!-- ./col -->
                    </div><!-- /.row -->
					<!-- top row -->
                    <div class="row">
                        <div class="col-xs-12 connectedSortable">
                            <div class="box box-primary">
                                <div class="box-header" data-toggle="tooltip" title="" data-original-title="Header tooltip">
                                    <h3 class="box-title">Upcoming Appointments</h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-primary btn-xs" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-primary btn-xs" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: block;">
                                   <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tbody><tr>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
												<th style="width:100px;">Action</th>
                                            </tr>
										<?php 

										//print_r($appointmentDetails);exit;

										foreach ($appointmentDetails as $app): ?>
										 <?php 
										//echo $app->Start_Date;
										//echo date();
										if(strtotime($app->Start_Date) > time() && $app->Status != 'Cancelled'){

											$datestring = "%l,%M %d";
											$timestring = "at %h:%i %A";
											$time = strtotime($app->Start_Date); // Get the current timestamps

											// Assign the formatted date to variable so that we can use it to
											// our view file. We can use it on our view as $current_date
											//echo mdate($datestring, $time);exit;
											?>
                                            <tr>
                                                <td><?=$app->Name;?></td>
                                                <td><?=mdate($datestring, $time)?></td>
                                                <td><?=mdate($timestring, $time)?></td>
                                                <td><?=$app->Status;?></td>
												<td>
													<div class="box-tools">
													<a class="label bg-aqua" href="<?php echo base_url(); ?>booking/<?=$app->Id;?>/cancel">Cancel</a>
													<a class="label bg-aqua" href="<?php echo base_url(); ?>bksl/<?=$app->uId;?>">View</a>
													</div>
												</td>
                                            </tr>
											<?php }?>
			  							<?php endforeach; ?>
                                        </tbody></table><!-- /.table -->
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer" style="display: block;">
                                </div><!-- /.box-footer-->
                            </div>
                        </div><!-- /.col -->
                    </div>