<?php
/**
 * Admin View: Atlas Status setting
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$table_exists = ZPAtlas_DB::table_exists();
$installing = get_option( 'zp_atlas_db_installing' );
$pending_msg = get_option( 'zp_atlas_db_pending' );
$status = __( 'Fehler', 'zp-atlas' );
$class = 'atlas-error';
$checkmark = '';

if ( $pending_msg ) {
	$status = $pending_msg;
} else {
	$status = ( $installing ? zpa_string( 'installing' ) : __( 'keiner', 'zp-atlas' ) );
}

if ( ! $table_exists ) {
	$status = __( 'keiner', 'zp-atlas' );
} else {
	if ( 'db' !== zpatlas_option() ) {
		$status = __( 'Nicht in Gebrauch', 'zp-atlas' );
	} else {

		if ( ! $installing && ! $pending_msg ) {

		    // check if table installation is complete
			if ( ZPAtlas_DB::use_db() ) {
		    	$status = zpa_string( 'active' );
		    	$class = 'success';
		    	$checkmark = ' &#x2713; &nbsp; ';
			}

		}

	}
}

// Show installer only if the db has not been installed and a custom one is not being used, and it's not currently installing.

if ( ! ZPAtlas_DB::is_installed() && ! ZPAtlas_DB::is_separate_db() && ! $installing ) {
	?>
	<div id="zp-atlas-installer">
		<p><?php echo __( 'Führe den Atlas Installer aus, um Deinen Atlas in Deiner WordPress-Datenbank zu erstellen.', 'zp-atlas' ); ?>
			<strong><?php printf( __( 'Überspringe dies, um eine <a href="%s" target="_blank" rel="noopener">separate Datenbank</a> zu verwenden.', 'zp-atlas' ), 'https://n3rds.work/docs/zodiacpress-atlas-separate-datenbank/' ); ?></strong></p>
		<p><button id="zp-atlas-install" class="button-primary"><?php _e( 'Führe das Atlas-Installationsprogramm aus', 'zp-atlas' ); ?></button></p>
	</div>
<?php } elseif(get_option('zp_atlas_db_try_again')) {
	?><button id="zp-atlas-try-again" class="button-secondary"><?php _e( 'Versuche erneut, Atlas zu installieren', 'zp-atlas' ); ?></button><br><br><?php
} ?>
<div id="zp-atlas-status" class="stuffbox">
	<div class="inside">
		<h2><?php _e( 'Atlas Status', 'zp-atlas' ); ?></h2>
		<table class="widefat">

			<tr>
				<td><label><?php _e( 'Status', 'zp-atlas' ); ?></label></td>
				<td>
					<span class="zp-<?php echo $class; ?>"> <?php echo $checkmark; ?>
						<?php echo $status; ?>
					</span>
				</td>
			</tr>

			<tr>
				<td><label><?php _e( 'Stadtaufzeichnungen zählen', 'zp-atlas' ); ?></label></td>
				<td id="zp-atlas-status-rows">
					<?php 
					if ( $table_exists && ! $installing ) {
						echo number_format( ZPAtlas_DB::row_count() );
					}
					?>
				</td>
			</tr>

			<tr>
				<td><label><?php _e( 'Größe der Datenbanktabelle', 'zp-atlas' ); ?></label></td>
				<td id="zp-atlas-status-size">
					<?php
					if ( $table_exists && ! $installing ) {
						echo ( $size = zpatlas_get_size() ) ? ( number_format( $size / 1048576, 1 ) . ' MB' ) : $size;
					}
					?>
				</td>
			</tr>

			<tr>
				<td><label><?php _e( 'Primärschlüssel der Datenbanktabelle', 'zp-atlas' ); ?></label></td>
				<td id="zp-atlas-status-key">
					<?php 
					if ( $table_exists && ! $installing ) {

						echo ZPAtlas_DB::key_exists( 'PRIMARY' ) ? __( 'in Ordnung', 'zp-atlas' ) : __( 'fehlt', 'zp-atlas' );

					}
					?>
				</td>
			</tr>

			<tr>
				<td><label><?php _e( 'Datenbanktabellenindex', 'zp-atlas' ); ?></label></td>
				<td id="zp-atlas-status-index">
					<?php 
					if ( $table_exists && ! $installing ) {

						echo ZPAtlas_DB::key_exists( 'ix_name_country' ) ? __( 'in Ordnung', 'zp-atlas' ) : __( 'fehlt', 'zp-atlas' );

					}
					?>
				</td>
			</tr>

		</table>

	</div>
</div>