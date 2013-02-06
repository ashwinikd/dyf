<?php $this->showView("header")?>
<div class="hero-unit" style="background-color: #F8E0F7">
	      <div class="jumbotron">
			<h3 class="muted"><img src="/static/images/banner.png" /></h3>
			<?php if($this->data["loggedIn"]): ?>
				<a class="btn btn-large btn-success" href="<?php echo $this->data["logoutUrl"]; ?>">Sign out of Facebook</a>
			<?php else: ?>
	        	<a href="<?php echo $this->data["loginUrl"]; ?>"><img src="/static/images/fblogin.png" /></a>
			<?php endif;?>
	      </div>
</div>
	      <hr>
<?php $this->showView("footer"); ?>