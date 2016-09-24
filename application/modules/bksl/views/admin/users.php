<div class="col-md-12">
	  <div class="box">
		<div class="box-header">
			<h3 class="box-title">
				<a href="<?php echo base_url();?>bksl/newuser" class="btn btn-sm btn-primary">New User</a>
			</h3>
		  <div class="box-tools">
			  <?php echo $pagination; ?>
		  </div>
		</div><!-- /.box-header -->
		<div class="box-body no-padding">
		  <table class="table">
			<thead>
				<th>Name</th>
				<th>Email</th>
				<th>Status</th>
				<th>Banned</th>
				<th>Banned Reason</th>
				<th>Type</th>
			  <th style="width: 20px">#</th>
			</tr>
			</thead>
			<tbody>
                    <?php for ($i = 0; $i < count($usertList); ++$i) { ?>
                    <tr>
                        <td><?php echo $usertList[$i]->username; ?></td>
                        <td><?php echo $usertList[$i]->email; ?></td>
						<td><?php echo $usertList[$i]->activated; ?></td>
						<td><?php echo $usertList[$i]->banned; ?></td>
						<td><?php echo $usertList[$i]->ban_reason; ?></td>
						<td><?php echo $usertList[$i]->user_type; ?></td>
                        <td style="width: 20px"><a href="<?php echo base_url();?>bksl/viewuser/<?php echo $usertList[$i]->id; ?>" class="btn btn-sm btn-primary">VIEW</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
		  </tbody></table>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
</div>