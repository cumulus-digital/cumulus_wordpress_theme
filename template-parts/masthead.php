<?php

namespace CumulusTheme;

?>
<header class="masthead <?php BodyClasses::has( 'post_header_image' ) || \is_front_page() ? 'trans' : 'solid'; ?>">

	<?php \get_template_part( 'template-parts/masthead', 'inner' ); ?>

</header>