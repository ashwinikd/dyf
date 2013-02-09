<?php $this->showView("header")?>
<div class="hero-unit">
	      <div class="jumbotron">
	      		<h3 style="margin-bottom: 45px;">Find True Love on this Valentine's day ... anonymously!!!</h3>
				<h3 class="muted"><img src="/static/images/banner.png" /></h3>
			<?php if($this->data["loggedIn"]): ?>	
					<a href="/application" class="btn btn-inverse">Choose Your Friends</a>
			<?php else: ?>
	        	<a href="#" onclick="window.top.location = '<?php echo $this->data["loginUrl"]; ?>';"><img src="/static/images/fblogin.png" /></a>
			<?php endif;?>
	      </div>
</div>
	      <hr>
<?php $this->showView("footer"); ?>