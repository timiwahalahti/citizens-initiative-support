<?php
/**
 * Plugin Name: Citizens' initiative support
 * Plugin URI: http://kansalaisaloite.fi
 * Description: Adds a widget that displays number of support statements in defined Finnish citizens' initiative. Plugin is not service by Finnish Ministry of Justice.
 * Version: 1.0
 * Author: Timi Wahalahti
 * Author URI: http://wahalahti.fi
 */

/**
 * Register widget and load textdomain
 */
function kacount_load() {
	register_widget( 'kacount' );
}
add_action( 'widgets_init', 'kacount_load' );

$plugin_dir = basename( dirname( __FILE__ ) ) ."/languages";
load_plugin_textdomain( 'kansalaisaloite', null, $plugin_dir );

/**
 * Widget class.
 */
class kacount extends WP_Widget {

	/**
	 * Widget setup
	 */
	function kacount() {
		/* Widget settings */
		$widget_ops = array( 'classname' => 'kacount', 'description' => __('Displays amount of support statements in defined Finnish citizens initiative', 'kansalaisaloite') );

		/* Widget control settings */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'kacount-widget' );

		/* Create the widget */
		$this->WP_Widget( 'kacount-widget', __('Kansalaisaloite', 'kansalaisaloite'), $widget_ops, $control_ops );
	}

	/**
	 * Display the widget on the screen
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Variables from the widget settings */
		$title = apply_filters('widget_title', $instance['title'] );
		$initiative = $instance['initiative'];

		/* Check if initiative numer is set and show error message if not */
		if (!$initiative) {
			echo $before_widget; // defined by theme
			echo $before_title . __('Kansalaisaloite', 'kansalaisaloite') . $after_title;
			echo "<div class='kawidget'><p>" .__('Aloitteen numeroa ei ole määritelty', 'kansalaisaloite'). ".</p></div>";
			echo $after_widget; // defined by theme
		}
		elseif ($data = json_decode(file_get_contents("https://www.kansalaisaloite.fi/api/v1/initiatives/".$initiative))) {
			/* Determine if initiative will start soon */
			if ($data->{'startDate'} > date("Y-m-d")) {
				$starting = true;
			}

			/* Determine if initiative is closed */
			if ($data->{'endDate'} < strtotime("-1 month") && $data->{'totalSupportCount'} < "50") {
				$closed = true;
			}
			elseif (date("Y-m-d") > $data->{'endDate'} || $data->{'state'} == "CANCELED") {
				$closed = true;
			}

			/* Determine if initiative has been sent to parliament */
			if ($data->{'state'} == "DONE") {
				$parliament = true;
			}

			/* Determine and make title */
			if (!empty($title)) {
				$title = $title;
			}
			else {
				$title = apply_filters('widget_title', $data->{'name'}->{$instance['lang']});
				if (!$title) {
					$title = apply_filters('widget_title', $data->{'name'}->{'fi'});
				}
			}

			/* Make urk to initiative */
			if ($instance['lang'] == "sv") {
				$initiative_url = "https://www.kansalaisaloite.fi/" .$instance['lang']. "/initiativ/" .$initiative;
			}
			else {
				$initiative_url = "https://www.kansalaisaloite.fi/fi/aloite/" .$initiative;
			}

			/* Select initiative data what we need */
			$support_count = number_format($data->{'totalSupportCount'}, 0, ',', ' ');
			$end_date = date("j.n.Y", strtotime($data->{'endDate'}));
			$start_date = date("j.n.Y", strtotime($data->{'startDate'}));

			/* Finally, render widget */
			echo $before_widget; // defined by theme
			echo $before_title . $title . $after_title;
			echo "<div class='kawidget'>";
			echo "<p>";
			if (isset($starting)) {
				printf( __( 'Kansalaisaloite on tarkastettu ja kannatusilmoitusten keräys alkaa %s' ), $start_date, 'kansalaisaloite');
				echo ".</p>";
			}
			else {
				printf( __( 'Kansalaisaloite on kerännyt %s kannatusilmoitusta' ), $support_count, 'kansalaisaloite');
				echo ".</p>";
				if (isset($closed)) {
					echo "<p>" .__('Keräysaika on umpeutunut','kansalaisaloite'). ".</p>";
				}
				elseif (isset($parliament)) {
					echo "<p>" .__('Aloite on lähetetty eduskuntaan','kansalaisaloite'). ".</p>";
				}
				else {
					echo "<p>";
					printf( __( 'Aloitteen on kerättävä 50 000 kannatusilmoitusta %s mennessä jotta se pääsee eduskunnan käsiteltäväksi' ), $end_date, 'kansalaisaloite');
					echo ".</p>";
					echo "<p><a href='" .$initiative_url. "' target='_blank'>" .__('Allekirjoita aloite','kansalaisaloite'). "</a></p>";
				}
			}
			echo "</div>";
			echo $after_widget; // defined by theme
		}
		else {
			echo $before_widget; // defined by theme
			echo $before_title . __('Kansalaisaloite', 'kansalaisaloite') . $after_title;
			echo "<div class='kawidget'><p>";
			printf( __( 'Kansalaisaloite.fi palvelun rajapinnassa on häiriö tai aloitetta numero %s ei ole' ), $initiative, 'kansalaisaloite');
			echo ".</p></div>";
			echo $after_widget; // defined by theme
		}
	}

	/**
	 * Update widget settings
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip to remove HTML */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['initiative'] = strip_tags( $new_instance['initiative'] );
		$instance['lang'] = $new_instance['lang'];

		return $instance;
	}

	/**
	 * Display widget settings controls on the widget panel
	 */
	function form( $instance ) {

		/* Set up some default settings */
		$defaults = array( 'title' => '', 'initiative' => '', 'lang' => 'fi' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget settings -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'kansalaisaloite'); ?>:</label><br>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'initiative' ); ?>"><?php _e('Law initiative number', 'kansalaisaloite'); ?>:</label><br>
			<input class="widefat" id="<?php echo $this->get_field_id( 'initiative' ); ?>" name="<?php echo $this->get_field_name( 'initiative' ); ?>" value="<?php echo $instance['initiative']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'lang' ); ?>"><?php _e('Language', 'kansalaisaloite'); ?>:<br><small><?php _e('Used when getting initiative title and link to kansalaisaloite.fi', 'kansalaisaloite'); ?>.</small></label><br>
			<?php _e('Finnish', 'kansalaisaloite'); ?> <input type="radio" name="<?php echo $this->get_field_name( 'lang' ); ?>" value="fi"<?php checked( 'fi' == $instance['lang'] ); ?> /><br>
			<?php _e('Swedish', 'kansalaisaloite'); ?> <input type="radio" name="<?php echo $this->get_field_name( 'lang' ); ?>" value="sv"<?php checked( 'sv' == $instance['lang'] ); ?> />
		</p>
	<?php
	}
}

?>