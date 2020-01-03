<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

<div class="wrap">
	<div class="row">
		<div class="col-md-12">
			<h1>IG Scrapper</h1>
			<?php if (isset($_POST['saveConfig'])) : ?>
				<?php if (update_configuration()) : ?>
					<div class="alert alert-success">
						<p>Success Update Configuration</p>
					</div>
				<?php endif ?>
			<?php endif ?>

			<?php if (isset($_POST['scrape'])) : ?>
				<?php if (process_scrape()) : ?>
					<div class="alert alert-success">
						<p>Success scrape data to the new.</p>
					</div>
				<?php else : ?>
					<div class="alert alert-success">
						<p>Oops. There's something wrong with the apps.</p>
					</div>
				<?php endif ?>
			<?php endif ?>

			<div class="panel panel-default">
				<div class="panel-heading">Configuration</div>
				<div class="panel-body">
					<form action="" method="POST">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Account Type</label>
									<select name="ig_type" id="ig_type" class="form-control">
										<?php $types = ['public', 'private'];
										$type = get_option('ig_type', ''); ?>

										<?php foreach ($types as $key => $value) : ?>
											<?php if ($type !== '' && $type === $value) : ?>
												<option value="<?= $value; ?>" selected><?= ucwords($value); ?></option>
											<?php else : ?>
												<option value="<?= $value; ?>"><?= ucwords($value); ?></option>
											<?php endif ?>
										<?php endforeach ?>
									</select>
								</div>
								<div class="form-group">
									<label>Account Username</label>
									<input type="text" name="ig_username" class="form-control" placeholder="username" value="<?= get_option('ig_username', ''); ?>" required>
									<small>Put the username without <code>@</code></small>
								</div>
								<div class="form-group accPass">
									<label>Account Password</label>
									<input type="password" name="ig_password" class="form-control" placeholder="Your IG Password" value="<?= get_option('ig_password', ''); ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Fetch Interval </label>
									<select name="ig_interval" class="form-control">
										<?php
										$intr = ['daily', 'weekly', 'monthly'];
										$int_val = get_option('ig_interval', '');
										?>
										<?php foreach ($intr as $key => $value) : ?>
											<?php if ($int_val !== '' && $int_val === $value) : ?>
												<option value="<?= $value; ?>" selected><?= ucwords($value); ?></option>
											<?php else : ?>
												<option value="<?= $value; ?>"><?= ucwords($value); ?></option>
											<?php endif ?>

										<?php endforeach ?>
									</select>
								</div>
								<div class="form-group">
									<label>Limit Fetch</label>
									<input type="number" class="form-control" name="ig_limit" value="<?= get_option('ig_limit', 10); ?>" max="12">
									<small>Limit of fetch (Right now just reach at 12)</small>
								</div>
							</div>
							<div class="col-md-12">
								<button type="submit" name="saveConfig" class="btn btn-primary btn-sm">Save Configuration</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">How to use</div>
				<div class="panel-body">
					<form action="" method="post">
						<button type="submit" name="scrape" class="btn btn-success btn-xs">Fetch data</button>
					</form>
					<p>
						Last Update : <b><i><?= get_option('last_updates_ig'); ?></i></b>
						<ol>
							<li>Put the instagram username (public) and password (private)</li>
							<li>Save the configuration for each changed</li>
							<li>To call the data, use this function in your code <code>serve_ig_scrape()</code> and do the loop for each data.</li>
						</ol>
					</p>
				</div>
			</div>
		</div>

	</div>
</div>
<?php if (get_option('ig_type', '') !== 'private') : ?>
	<script>
		$(document).ready(function() {
			$('.accPass').hide();
		});
	</script>
<?php endif ?>


<script>
	$('#ig_type').change(function(event) {
		var types = $(this).val();
		if (types === 'private') {
			$('.accPass').show(1000);
		} else {
			$('.accPass').hide();
		}
	});
</script>