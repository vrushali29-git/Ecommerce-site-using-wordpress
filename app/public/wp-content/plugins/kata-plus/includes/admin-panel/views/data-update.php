<?php
// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$kata_options = get_option( 'kata_options' );
if ( isset( $kata_options['updates']['styler']['updated'] ) ) {
	wp_die(
		'Update process already has been done',
		'',
		array(
			'link_url'  => esc_url( admin_url( '/' ) ),
			'link_text' => esc_html__( 'back to dashboard', 'kata-plus' ),
		)
	);
}

?>

<div class="kata-admin kata-dashboard-page wrap about-wrap">
	<?php $this->header(); ?>
	<div class="kata-card kata-primary">
		<div class="kt-updt-wrap">

			<h2>Data Update Panel</h2>
			<p><strong>It is important to get a backup before starting the update process. We won’t be responsible for any potential data loss in the future.</strong></p>
			<p>First, get a backup with one of the recommended backup tools. below. Then click on Start Update Process.</p>
			<ul class="backup-plugins">
				<li><img src="https://ps.w.org/updraftplus/assets/icon-128x128.jpg"><a href="<?php echo esc_url( admin_url() ); ?>plugin-install.php?s=UpdraftPlus&tab=search&type=term" target="_blank">UpdraftPlus</a></li>
				<li><img src="https://ps.w.org/duplicator/assets/icon-128x128.png"><a href="<?php echo esc_url( admin_url() ); ?>plugin-install.php?s=Duplicator&tab=search&type=term" target="_blank">Duplicator</a></li>
				<li><img src="https://ps.w.org/backwpup/assets/icon-128x128.png"><a href="<?php echo esc_url( admin_url() ); ?>plugin-install.php?s=BackWPup&tab=search&type=term" target="_blank">BackWPup</a></li>
				<li><img src="https://ps.w.org/all-in-one-wp-migration/assets/icon-128x128.png"><a href="<?php echo esc_url( admin_url() ); ?>plugin-install.php?s=All%2520in%2520one%2520Migration&tab=search&type=term" target="_blank">All in one Migration</a></li>
			</ul>
			<div class="progress-container">
				<div class="progress-bar">0%</div>
			</div>
			<div class="result"></div>
			<div class="return-to-dashboard">
				<div class="backup-confirmation">
					<input type="checkbox" id="have_backup" name="have_backup">
					<label for="have_backup">I Got the Backup</label>
				</div>
				<a class="help-links backtowp-btn" href="<?php echo esc_url( admin_url() ); ?>" target="_blank"><i class="ti-arrow-left"></i> Back to Dashboard</a>
				<button class="update-btn start-progress" onclick="processIds()"><i class="ti-reload"></i> Start Update Process</button>
			</div>
		</div>
	</div>
</div>
<script>
	// Get references to the checkbox and the button
	const haveBackupCheckbox = document.getElementById('have_backup');
	const updateButton = document.querySelector('.start-progress');

	// Function to enable/disable the button based on checkbox state
	function updateButtonState() {
		if (haveBackupCheckbox.checked) {
			updateButton.disabled = false;
		} else {
			updateButton.disabled = true;
		}
	}

	// Add an event listener to the checkbox
	haveBackupCheckbox.addEventListener('change', updateButtonState);

	// Initially, call the function to set the button state based on checkbox state
	updateButtonState();

	// Function to send a POST request with an "action" parameter
	function sendPostRequest(url, action, data) {
		const formData = new FormData();
		formData.append('action', action);
		formData.append('data', JSON.stringify(data));

		const requestOptions = {
			method: 'POST',
			body: formData,
		};

		return fetch(url, requestOptions)
			.then(response => response.json());
	}

	// Function to send a GET request with "action" and "id" as query parameters
	function sendGetRequest(url, action, id) {
		const queryParams = new URLSearchParams();
		queryParams.append('new_to_update_id', id); // Assuming 'id' is the value to be sent
		queryParams.append('action', action);

		// Append the query parameters to the URL
		const requestUrl = `${url}?${queryParams.toString()}`;

		return fetch(requestUrl).then(response => response.json());
	}

	// Function to fetch the initial JSON array containing IDs
	function fetchInitialIds() {
		return sendPostRequest('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', 'kata_update_db_initial_ids', {});
	}

	// Function to fetch data for a specific ID
	function fetchDataForId(id) {
		return sendGetRequest('<?php echo esc_url( site_url() ); ?>', 'kata_update_db_fetch_data', id );
	}

	function updateUIWithSuccess(id, title) {
		const resultDiv = document.querySelector('.result');
		const successMessageContainer = document.createElement('div');
		successMessageContainer.classList.add('success-message-container');

		const successIcon = document.createElement('span');
		successIcon.classList.add('success-icon');
		successIcon.innerHTML = '&#10004;'; // Checkmark symbol

		const successMessage = document.createElement('span');
		successMessage.classList.add('success-message');
		successMessage.textContent = ` Task "${title}" completed successfully.`;

		successMessageContainer.appendChild(successIcon);
		successMessageContainer.appendChild(successMessage);
		resultDiv.appendChild(successMessageContainer);

		if ( id === 'regenerate_elementor_data' ) {
			window.location.href = '<?php echo esc_url( admin_url( 'admin.php?page=kata-plus-theme-activation' ) ); ?>';
		}
	}

	// Function to update the progress bar
	function updateProgressBar(current, total) {
		const progress = (current / total) * 100;
		const progressBar = document.querySelector('.progress-bar');
		progressBar.style.width = `${progress}%`;
		progressBar.textContent = `${progress.toFixed(1)}%`;
	}

	// Main function to orchestrate the process
	async function processIds() {
		try {
			const loading = document.querySelector('.update-btn');
			loading.classList.add('loading');
			const initialIds = await fetchInitialIds();
			const idsObject = initialIds['ids'];
			const totalIds = Object.keys(idsObject).length;
			let currentIdIndex = 0;

			for (const id in idsObject) {
				if (idsObject.hasOwnProperty(id)) {
					const title = idsObject[id];
					const data = await fetchDataForId(id);
					updateUIWithSuccess(id, title);

					// Update the progress bar
					currentIdIndex++;
					updateProgressBar(currentIdIndex, totalIds);
				}
			}
		} catch (error) {
			console.error('An error occurred:', error);
		}
	}
</script>
<?php
do_action( 'kata_plus_control_panel' );
