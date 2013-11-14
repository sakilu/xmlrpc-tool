<?php 
$filename = 'data';
if(file_exists($filename)){
	$s = file_get_contents($filename);
	$configs = unserialize($s);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script src="js/jquery-1.9.0.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.xmlrpc.min.js"></script>
	<script src="js/jquery.blockUI.js"></script>
	<script src="js/fun.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<title>XML-RPC tool</title>
</head>
<body>
<div class="row-fluid">
  <div class="span12">
  
	<div class="btn-group">
	  <a href="#saveModal" role="button" id="save" class="btn" data-toggle="modal">Save</a>
	  <a href="#loadModal" role="button" id="load" class="btn" data-toggle="modal">Load</a>
	</div>
	
	
	<!-- table -->
	<div class="span10 offset1" id="section">
	
  		<!-- form -->
		<form class="form-horizontal">
		  <div class="control-group">
		    <label class="control-label" for="inputServer">Server</label>
		    <div class="controls">
		      <input type="text" class="span6" id="inputServer" name="inputServer" placeholder="Server">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputMethod">Method</label>
		    <div class="controls">
		      <input type="text" class="span6" id="inputMethod" placeholder="Method">
		    </div>
		  </div>  
		</form>
		
		<select style="margin-top:10px">
		    <option>string</option>
		    <option>integer</option>
		    <option>double</option>
		    <option>array</option>
		    <option>struct</option>
		    <option>base64</option>
		    <option>boolean</option>
		    <option>dateTime</option>
		</select>
		<button class="btn add">add param</button>
		<table class="table table-bordered">
			<thead>
				<tr>
					<td width="60px">#</td>
					<td>type</td>
					<td>name</td>
					<td>value</td>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		<p style="text-align: center">
			<button class="btn btn-primary" id="go">submit</button>
		</p>
	</div>
	
	<!-- output -->
	<div class="span10 offset1">
		<dl>
		  <dt>request</dt>
		  <dd id="request">...</dd>
		  <dt>response</dt>
		  <dd id="response">...</dd>
		</dl>
	</div>
  </div>
   
	<div id="saveModal" class="modal hide fade">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3>save</h3>
	  </div>
	  <div class="modal-body">
		<form class="form-horizontal">
		  <div class="control-group">
		    <label class="control-label" for="inputNote">name</label>
		    <div class="controls">
		      <input type="text" />
		    </div>
		  </div>
		</form>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn btn-primary" id="saveSubmit">submit</a>
	  </div>
	</div>
	
	<div id="loadModal" class="modal hide fade">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3>load</h3>
	  </div>
	  <div class="modal-body">
		<form class="form-horizontal">
		  <div class="control-group">
		    <label class="control-label" for="inputNote">name</label>
		    <div class="controls">
		      <select id="save-list">
				<?php 
					foreach($configs as $key => $html){
						echo "<option>$key</option>";
					}
				?>		      	
		      </select>
		      <?php if(isset($configs) && count($configs)){ ?>
		     	 <a href='javascript: void(0)'><i class="icon-remove save-remove"></i></a>
		      <?php } ?>  
		    </div>
		  </div>
		</form>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn btn-primary" id="loadSubmit">submit</a>
	  </div>
	</div>
	
</div>
</body>
</html>