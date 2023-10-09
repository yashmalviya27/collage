<div id="page-wrapper">

							<div class="form-three widget-shadow">
							<form class="form-horizontal" action="<?php echo base_url('Renumeration/insert_role_1');?>" method="post">
							<div class="form-group">
									<label class="col-sm-2 control-label">Roles<span style="color:red;"> *</span></label>
									<div class="col-sm-8">
										<input type="text" name="role" class="form-control" 
										id="category" value="" required/>
									</div>
							</div>
							<!-- <div class="form-group">
									<label class="col-sm-2 control-label">Category Code<span style="color:red;"> *</span></label>
									<div class="col-sm-8">
										<input type="text" name="category_code" class="form-control" 
										id="category_code" value="" required/>
									</div>
							</div> -->
							<div class="col-sm-offset-2">
							 <input type="submit" name="button" value="Add"  class="btn btn-primary"/> 
							 </div>
							</form>
						</div>
						<div class="tables" >
						<div class="table-responsive bs-example widget-shadow" id="table1">
						<h4 style="color: #e94e02;">List of Roles:</h4>
						<table class="table table-bordered table-striped" > 
							<thead>
							<tr>
								<th style="text-align: center;">S.No</th> 
								<th style="text-align: center;">Roles</th>
								<th style="text-align: center;"> Action</th>
							</tr> 
							</thead> 
						    <tbody>
								<?php $i=1; if(!empty($category))  foreach($category as $cat){ ?> <tr> 
									<td style="text-align: center;"><?php echo $i;?></td>							 
									<td><?php echo $cat['category'];?></td>
									<td style="text-align: center;"><?php echo $cat['category_code'];?></td> 
									</tr> <?php $i++;}?>
								</tbody>
						     </table> 
					</div>
					</div>
					
					</div>
				

