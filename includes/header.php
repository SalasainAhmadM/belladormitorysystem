<?php if ($_SESSION['id']) { ?>
	<div class="brand clearfix">
		<a href="logout.php" class="btn btn-danger mobile-logout">Logout</a>

		<a href="#" class="logo" style="">Bella Dormitory Management System</a>
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<ul class="ts-profile-nav">
			<li class="ts-account">
				<a href="#"><img src="img/ts-avatar.png" class="ts-avatar hidden-side" alt=""> Account <i
						class="fa fa-angle-down hidden-side"></i></a>
				<ul>
					<li><a href="my-profile.php">My Account</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>

	</div>

	<!-- CSS to Show Logout Button Only in Mobile View -->
	<style>
		.logo {
			font-size: 16px;
		}

		.mobile-logout {
			display: none;
			position: absolute;
			left: 15px;
			top: 50%;
			transform: translateY(-50%);
			padding: 8px 15px;
			font-size: 14px;
		}

		@media (max-width: 768px) {
			.mobile-logout {
				display: block;
			}

			.logo {
				display: block;
				text-align: center;
				width: 100%;
				position: absolute;
				left: 50%;
				transform: translateX(-50%);
				font-size: 12px;
			}


		}
	</style>

	<?php
} else { ?>
	<div class="brand clearfix">
		<a href="#" class="logo" style="font-size:16px;">Bella Dormitory Management System</a>
		<span class="menu-btn"><i class="fa fa-bars"></i></span>

	</div>
<?php } ?>