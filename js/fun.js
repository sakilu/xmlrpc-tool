	$(function(){
		$(document.body).on('click', '.add', function() {
			
			var type = $(this).prev('select').val();
			var tbody = $(this).next('table').children('tbody').eq(0);
			
			var tr = "<tr>";
			var tr = tr + "<td><a href='javascript: void(0)'><i class='icon-arrow-up'></i></a> <a href='javascript: void(0)'><i class='icon-arrow-down'></i></a> <a href='javascript: void(0)'><i class='icon-remove'></i></a></td><td>"+type+"</td>";
			if(type != 'array' && type != 'struct'){
				tr = tr + "<td><input type='text' /></td><td><input type='text' data-type='"+type+"' /></td>";
			}else{
				tr = tr + '<td><input type="text" /></td><td><select style="margin-top:10px"><option>string</option><option>integer</option><option>double</option><option>array</option><option>struct</option><option>base64</option><option>boolean</option><option>dateTime</option></select><button class="btn add">add param</button><table class="table table-bordered"><thead><tr><td width="60px">#</td><td>type</td><td>name</td><td>value</td></tr></thead><tbody></tbody></table></td>';
			}
			tr = tr + "</tr>";
			$(tbody).append(tr);
		});
		
		// up, down, remove
		$(document.body).on('click', '.icon-arrow-up, .icon-arrow-down', function() {
	        var row = $(this).parents("tr:first");
	        if ($(this).is(".icon-arrow-up")) {
	            row.insertBefore(row.prev());
	        } else {
	            row.insertAfter(row.next());
	        }
		});
		
		$(document.body).on('click', '[class="icon-remove"]', function() {
			$(this).closest("tr").remove();
		});
		
		$('#saveSubmit').click(function(){
			var name = $('#saveModal').find('input').eq(0).val();
			if(!name){
				alert('empty!');
				return;
			}
			
			$('#saveModal').modal('hide');
			blockUI();
			$('#section').find('input').each(function(){
				$(this).attr('data-value', $(this).val());
			});
			var html = $('#section').clone();
			var htmlString = html.html();

			$.post('/ajax.php', {html:htmlString, name:name, method:'save'}, function(){
				$('#save-list').children().each(function(){
					if($(this).text() == name){
						$(this).remove();
					}
				});
				$('#save-list').append('<option>'+name+'</option>');
				unblockUI();
			});
		});
		
		$('#loadSubmit').click(function(){
			var name = $('#loadModal').find('select').eq(0).val();
			if(!name){
				alert('empty!');
				return;
			}
			$('#loadModal').modal('hide');
			blockUI();
			$.post('/ajax.php', {name:name,method:'load'}, function(data){
				if(data != 'error'){
					$('#section').empty();
					$('#section').html(data);
					$('#section').find('input').each(function(){
						$(this).val($(this).attr('data-value'));
					});
				}
				unblockUI();
			});
		});
		
		$('.save-remove').click(function(){
			alert('1234');
			blockUI();
			var name = $('#save-list').val();
			$.post('/ajax.php', {name:name,method:'remove'}, function(){
				$('#save-list').find('option:selected').remove();
				unblockUI();
			});
		});
		
		$(document.body).on('click', '#go', function() {
			blockUI();
			var method = $.trim($('#inputMethod').val());
			var server = $.trim($('#inputServer').val());
			var data = createXmlByArray($('table').eq(0).find('tbody').eq(0));
			var xml = $.xmlrpc.document(method, data);
			var xmlString = (new XMLSerializer()).serializeToString(xml);
			$('#request').text(xmlString);
			$.post('ajax.php', {server:server, method:'send', callMethod:method, xmlString:xmlString}, function(data){
				$('#response').text(data);
				unblockUI();
			});
			
		});
	});
	
	function createXmlByArray(TreeNode){
		var data = new Array();
		$(TreeNode).children('tr').each(function(index){
			var type  = $.trim($(this).find('td').eq(1).text());
			var name  = $(this).children('td').eq(2).find('input').eq(0).val();
			var value = $(this).children('td').eq(3).find('input').eq(0).val();
			
			if(type == 'array'){
				data.push(createXmlByArray($(this).children('td').eq(3).find('tbody').eq(0)));
			}else if(type == 'struct'){
				data.push(createXmlByStruct($(this).children('td').eq(3).find('tbody').eq(0)));
			}else if(type == 'string'){
				data.push($.xmlrpc.force('string', value));
			}else if(type == 'integer'){
				data.push($.xmlrpc.force('int', value));
			}else if(type == 'double'){
				data.push($.xmlrpc.force('double', value));
			}else if(type == 'base64'){
				data.push(str2ab(value));
			}else if(type == 'boolean'){
				data.push($.xmlrpc.force('boolean', value));
			}else if(type == 'dateTime'){
				data.push(new Date(value));
			}
		});
		return data;
	}
	
	function createXmlByStruct(TreeNode){
		var data = {};
		$(TreeNode).find('tr').each(function(index){
			var type  = $.trim($(this).find('td').eq(1).text());
			var name  = $(this).find('td').eq(2).find('input').eq(0).val();
			var value = $(this).find('td').eq(3).find('input').eq(0).val();
			
			if(type == 'array'){
				data[name] = createXmlByArray($(this).children('td').eq(3).find('tbody').eq(0));
			}else if(type == 'struct'){
				data[name] = createXmlByStruct($(this).children('td').eq(3).find('tbody').eq(0));
			}else if(type == 'string'){
				data[name] = $.xmlrpc.force('string', value);
			}else if(type == 'integer'){
				data[name] = $.xmlrpc.force('int', value);
			}else if(type == 'double'){
				data[name] = $.xmlrpc.force('double', value);
			}else if(type == 'base64'){
				data[name] = str2ab(value);
			}else if(type == 'boolean'){
				data[name] = $.xmlrpc.force('boolean', value);
			}else if(type == 'dateTime'){
				data[name] = new Date(value);
			}
		});
		return data;
	}
	
	function str2ab(str) {
		  var buf = new ArrayBuffer(str.length*2); // 2 bytes for each char
		  var bufView = new Uint16Array(buf);
		  for (var i=0, strLen=str.length; i<strLen; i++) {
		    bufView[i] = str.charCodeAt(i);
		  }
		  return buf;
	}
	
	function blockUI(){
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
	}
	
	function unblockUI(){
		$.unblockUI();
	}