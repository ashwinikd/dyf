<?php $this->showView("header"); ?>
<div class="hero-unit" style="background-color: #F8E0F7">
		<h2 class="jumbotron">Following People Want to Date you</h2>
		<ul id="friendlist">
		<?php foreach($this->data["dates"] as $friend): ?>
			<?php $friend = json_decode($friend, true); ?>
			<li class="friend" id="friend_<?php echo $friend["uid"] ?>"  frndid="<?php echo $friend["uid"] ?>">
				<div style="width:50px; height: 50px; display: inline;"><img src="https://graph.facebook.com/<?php echo $friend["uid"] ?>/picture" /></div> <?php echo $friend["name"]; ; ?>
			</li>
		<?php endforeach;?>
		</ul>
		</div>
<?php $this->showView("footer"); ?>