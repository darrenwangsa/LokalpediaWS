<header>
		<div id="header">
			<div class="container">
				<div class="row">
					<!-- LOGO -->
					<div class="col-md-3">
						<div class="header-logo">
							<a href="index.php" class="logo">
								<img src="./img/logoLokalpedia.png" alt="Lokalpedia">
							</a>
						</div>
					</div>

					<!-- SEARCH BAR -->
					<div class="col-md-6">
						<div class="header-search">
							<form action="search.php" method="post">
								<select class="input-select" name="category">
									<option value="0">All Categories</option>
									<option value="1">Teams</option>
									<option value="2">Players & Staff</option>
									<option value="3">Competition</option>
								</select>
								<input class="input" name="search" placeholder="Search here">
								<button class="search-btn" type="submit">Search</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>