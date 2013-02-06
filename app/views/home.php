<?php $this->showView("header")?>
<div class="hero-unit">
	      <div class="jumbotron">
			<h3 class="muted"><img src="/static/images/logo.png" /></h3>
	        <h1 style="font-size: 42px;">Find your Valentine in three easy steps</h1>
	        <p class="lead">Connect with facebook<br/> Select friends you are interested in<br/>Get notified of mutual interests</p>
			<?php if($this->data["loggedIn"]): ?>
				<a class="btn btn-large btn-success" href="<?php echo $this->data["logoutUrl"]; ?>">Sign out of Facebook</a>
			<?php else: ?>
	        	<a href="<?php echo $this->data["loginUrl"]; ?>"><img src="/static/images/fblogin.png" /></a>
			<?php endif;?>
	      </div>
</div>
	      <hr>
<?php $this->showView("footer"); ?>