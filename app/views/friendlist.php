<?php $this->showView("header"); ?>

<div class="hero-unit" style="background-color: #F8E0F7">
		<p >Select five of your friends</p>
		<input type="text" id="input" />
		<div id="result"></div>
			<form action ="/interests" method="POST">
			<ul id="friendlist">
			<?php foreach($this->data["friends"]["friends"] as $friend): ?>
				<li class="friend nothidden hiddenfriend" id="friend_<?php echo $friend["uid"] ?>"  frndid="<?php echo $friend["uid"] ?>">
					<input name="friend[]" type="checkbox" class="friendcheck" frndid="<?php echo $friend["uid"] ?>" id="friendcheck_<?php echo $friend["uid"] ?>" value="<?php echo $friend["uid"] ?>" />
					<div style="width:50px; height: 50px; display: inline;"><img src="https://graph.facebook.com/<?php echo $friend["uid"] ?>/picture" /></div> <?php echo $friend["name"]; ; ?>
				</li>
			<?php endforeach;?>
			</ul>
			<div style="text-align: right">
			<input type="Submit" value="Done" class='btn btn-primary' />
			</div>
			</form>
			</div>
	<script type="text/javascript" src="static/js/Trie.js"></script>
	<script type="text/javascript">
		function trim(s){ 
	  		return ( s || '' ).replace( /^\s+|\s+$/g, '' ); 
		}
		var _nameMap = <?php echo json_encode($this->data["friends"]["nameMap"]); ?>;
		var _names = <?php echo json_encode($this->data["friends"]["nameLst"]); ?>;
		var _midNames = Math.ceil(_names.length/2);
		var T = new Trie();
		var i;
		for(i = 0; i < _names.length; i++) {
		    T.insert(_names[i].toLowerCase());
		}
		      
		
		$(document).ready(function() {
			document.getElementById('hiddencss').disabled = true;
			$(".friend").click(function(e){
				var id = $(this).attr("frndid");
				var checkbox = "#friendcheck_" + id;
				var div = "#friend_" + id;
				if($(checkbox).is(":checked") && $(div).hasClass("selected")) {
					$(div).removeClass("selected");
					$(checkbox).removeAttr("checked");
				} else if(!$(checkbox).is(":checked") && !$(div).hasClass("selected")) {
					$(div).addClass("selected");
					$(checkbox).prop("checked", true);
				} else if($(checkbox).is(":checked")) {
					$(div).addClass("selected");
				} else {
					$(div).removeClass("selected");
					$(checkbox).removeAttr("checked");
				}
			});
			
			$("#input").keyup(function(e) {
			        e.stopPropagation();
		            var textInput,
		                suggestions,
		                html,
		                i;
		            textInput = trim($(this).val());
					$(".nothidden").addClass("hiddenfriend");
					$(".nothidden").removeClass("nothidden");
					if(textInput.length < 3) {
						document.getElementById('hiddencss').disabled = true;
						return;
					}
		            stateList = T.autoComplete(textInput.toLowerCase());
		
					document.getElementById('hiddencss').disabled = false;
		
		            for(i = 0; i < stateList.length; i++) {
						var _ids = _nameMap[stateList[i]];
						for(id in _ids) {
							$('#friend_' + _ids[id]).removeClass("hiddenfriend");
							$('#friend_' + _ids[id]).addClass("nothidden");
						}
		            }
		        });
		});
	</script>
<?php $this->showView("footer"); ?>