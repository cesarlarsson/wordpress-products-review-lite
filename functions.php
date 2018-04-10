<?php 
function srpr_show_product_review( $id = '', $visual = 'full' ) {
	global $post;
	 if ( (is_null( $post ) && $id == '') || post_password_required( $post ) ) {
		return false;
	}
	if ( $id == '' ) {
		$id = $post->ID;
	}

	$taxonomy_objects = get_object_taxonomies( 'post', 'objects' );

	$taxonomy = 'categorias_de_producto';
	$terms = get_the_terms( get_the_ID(), 'categorias_de_producto' );
	$termArray = [];
	if ( $terms && !is_wp_error( $terms ) ) :
		foreach ( $terms as $term ) { 
			array_push( $termArray, '<a href="'.get_term_link($term->slug, $taxonomy).'">'.$term->name.'</a>');
			} 
	endif;

	$srpr_review_stored_meta = get_post_meta( $id );

	$srpr_author_meta =get_the_author_meta( $id );
	$post_categories = wp_get_post_categories( $id );

	$my_date = the_date('', '<h2>', '</h2>', FALSE);

   if(isset( $srpr_review_stored_meta['product-name'][0]) && $srpr_review_stored_meta['rating_value'][0]){
	//$return_string .="<h2>".$srpr_review_stored_meta['product-name'][0]."</h2>";
	wp_enqueue_script( 'srwp-rateyo', plugins_url( 'scripts/jquery.rateyo.min.js', __FILE__ ), array( 'jquery' ), '20150204', true );
	wp_enqueue_script( 'srwp-fe', plugins_url( 'front-end-scripts/srpr-script.js', __FILE__ ), array( 'jquery' ), '20150204', true );
	wp_enqueue_style( 'jquery-style-rateyo', plugins_url( 'css/jquery.rateyo.min.css', __FILE__ ) );
	$return_string .="<div class='rateproduct' data-rateyo-rating='".$srpr_review_stored_meta['rating_value'][0]."' data-rateyo-read-only='true'></div>";


	$taxonomiesList = "<a href='http://smartrabbit.test/category/resenas/' rel='category tag'>Reseñas</a>";
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	//comments
	ob_start();

	
	if ( ! post_password_required() && comments_open() ) { 
		 comments_popup_link( __( '<i class="icon-comment-alt"></i> 0 Comentarios', 'dw-minion' ), __( '<i class="icon-comment-alt"></i> 1 Comentario', 'dw-minion' ), __( '<i class="icon-comment-alt"></i> % Comentarios', 'dw-minion' ) );
		//$comments = '<span class="comments-link">'..'</span>';
	 }
	 $comments = ob_get_clean();
	$return_string .="<div class='entry-meta'>".sprintf( __( '<span class="byline">Por <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span></span>', 'dw-minion' ),
	esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
	esc_attr( sprintf( __( 'View all posts by %s', 'dw-minion' ), get_the_author() ) ),
	esc_html( get_the_author() )
)."<span class='cat-links'> en ".implode (", ", $termArray)."</span><span class='sep'><span class='post-format'>
	<i class='icon-file-text'></i></span></span>".	sprintf( __( '<span class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><i class="icon-calendar-empty"></i> %3$s</a></span>', 'dw-minion' ),
	esc_url( get_permalink() ),
	esc_attr( get_the_time() ),
	$time_string
)."<span class='comments-link'>".$comments."</span></div>";
		$content = str_replace(']]>', ']]&gt;', wpautop($srpr_review_stored_meta['product_information'][0]));
	
	$more_link_text = __( '<span class="btn btn-small">Continuar Leyendo</span>', 'dw-minion' );



	if ( preg_match( '/<!--more(.*?)?-->/', $content, $matches ) && !is_single() ) {
		$content = explode( $matches[0], $content, 2 );
		if ( ! empty( $matches[1] ) && ! empty( $more_link_text ) )
			$more_link_text = strip_tags( wp_kses_no_null( trim( $matches[1] ) ) );

		$has_teaser = true;
	} else {
		$content = array( $content );
	}

	$output = '';

	$output .= $content[0];
	if( !is_single()) {
		$output .= apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-{$post->ID}\" class=\"more-link\">$more_link_text</a>", $more_link_text );
	}
	$output = force_balance_tags( $output );

	$return_string .="<div>".$output ."</div>";
	


   }
	/*if ( isset( $cwp_review_stored_meta['cwp_meta_box_check'][0] ) && $cwp_review_stored_meta['cwp_meta_box_check'][0] == 'Yes' ) {
		wp_enqueue_style( 'cwp-pac-frontpage-stylesheet', WPPR_URL . '/css/frontpage.css', array(), WPPR_LITE_VERSION );
		wp_enqueue_script( 'pie-chart', WPPR_URL . '/javascript/pie-chart.js', array( 'jquery' ), WPPR_LITE_VERSION, true );
		wp_enqueue_script( 'cwp-pac-main-script', WPPR_URL . '/javascript/main.js', array(
			'jquery',
			'pie-chart',
		), WPPR_LITE_VERSION, true );
		$cwp_price = get_post_meta( $id, 'cwp_rev_price', true );
		$p_string  = $cwp_price;
		$p_name    = apply_filters( 'wppr_review_product_name', $id );
		if ( $p_string != '' ) {
			// Added by Ash/Upwork
			$cwp_price = do_shortcode( $cwp_price );
			// Added by Ash/Upwork
			$p_price    = preg_replace( '/[^0-9.,]/', '', $cwp_price );
			$p_currency = preg_replace( '/[0-9.,]/', '', $cwp_price );
			// Added by Ash/Upwork
			$p_disable = apply_filters( 'wppr_disable_price_richsnippet', false );
			// Added by Ash/Upwork
			if ( ! $p_disable ) {
				$p_string = '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span itemprop="priceCurrency">' . $p_currency . '</span><span itemprop="price">' . $p_price . '</span></span>';
			}
		}
		$product_image = do_shortcode( get_post_meta( $id, 'cwp_rev_product_image', true ) );
		$imgurl        = do_shortcode( get_post_meta( $id, 'cwp_image_link', true ) );
		$lightbox      = '';
		$feat_image    = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
		if ( ! empty( $product_image ) ) {
			$product_image_cropped = wppr_get_image_id( $id, $product_image );
		} else {
			$product_image_cropped = wppr_get_image_id( $id );
			$product_image         = $feat_image;
		}
		if ( $imgurl == 'image' ) {
			// no means no disabled
			if ( cwppos( 'cwppos_lighbox' ) == 'no' ) {
				$lightbox = 'data-lightbox="' . $product_image . '"';
				wp_enqueue_script( 'img-lightbox', WPPR_URL . '/javascript/lightbox.min.js', array(), WPPR_LITE_VERSION, array() );
				wp_enqueue_style( 'img-lightbox-css', WPPR_URL . '/css/lightbox.css', array(), WPPR_LITE_VERSION );
			}
		} else {
			$product_image = do_shortcode( get_post_meta( $id, 'cwp_product_affiliate_link', true ) );
		}
		$rating    = cwppos_calc_overall_rating( $id );
		$divrating = $rating['overall'] / 10;
		for ( $i = 1; $i <= cwppos( 'cwppos_option_nr' ); $i ++ ) {
			${'option' . $i . '_content'} = do_shortcode( get_post_meta( $id, 'option_' . $i . '_content', true ) );
			if ( empty( ${'option' . $i . '_content'} ) ) {
				${'option' . $i . '_content'} = __( 'Default Feature ' . $i, 'cwppos' );
			}
		}
		$commentNr = get_comments_number( $id ) + 1;
		if ( $visual == 'full' ) {
			$return_string .= '<section id="review-statistics"  class="article-section" itemscope itemtype="http://schema.org/Product">
                                <div class="review-wrap-up  cwpr_clearfix" >
                                    <div class="cwpr-review-top cwpr_clearfix">
                                        <span itemprop="name">' . $p_name . '</span>

                                        <span class="cwp-item-price cwp-item">' . $p_string . '</span>
                                    </div><!-- end .cwpr-review-top -->
                                    <div class="review-wu-left">
                                        <div class="rev-wu-image">
    		                        <a href="' . $product_image . '" ' . $lightbox . '  rel="nofollow" target="_blank"><img itemprop="image" src="' . $product_image_cropped . '" alt="' . do_shortcode( get_post_meta( $id, 'cwp_rev_product_name', true ) ) . '" class="photo photo-wrapup wppr-product-image"  /></a>
                                    </div><!-- end .rev-wu-image -->
                                    <div class="review-wu-grade">';
		}
		if ( $visual == 'full' || $visual == 'yes' ) {
			$extra_class = $visual == 'yes' ? 'cwp-chart-embed' : '';
			$return_string .= '<div class="cwp-review-chart ' . $extra_class . '">
                                    <meta itemprop="datePublished" datetime="' . get_the_time( 'Y-m-d', $id ) . '">';
			if ( cwppos( 'cwppos_infl_userreview' ) != 0 && $commentNr > 1 ) {
				$return_string .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="cwp-review-percentage" data-percent="';
				$return_string .= $rating['overall'] . '"><span itemprop="ratingValue" class="cwp-review-rating">' . $divrating . '</span><meta itemprop="bestRating" content = "10"/>
                         <meta itemprop="ratingCount" content="' . $commentNr . '"> </div>';

			} else {
				$return_string .= '<span itemscope itemtype="http://schema.org/Review"><span itemprop="author" itemscope itemtype="http://schema.org/Person"  >
                                             <meta itemprop="name"  content="' . get_the_author() . '"/>
                                        </span><span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Product"><meta itemprop="name" content="' . do_shortcode( get_post_meta( $id, 'cwp_rev_product_name', true ) ) . '"/></span><div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="cwp-review-percentage" data-percent="';
				$return_string .= $rating['overall'] . '"><span itemprop="ratingValue" class="cwp-review-rating">' . $divrating . '</span> <meta itemprop="bestRating" content="10">  </div></span>';
			}
			$return_string .= '</div><!-- end .chart -->';
		}
		if ( $visual == 'full' ) {
			$return_string .= '</div><!-- end .review-wu-grade -->
                                <div class="review-wu-bars">';
			for ( $i = 1; $i <= cwppos( 'cwppos_option_nr' ); $i ++ ) {
				if ( ! empty( ${'option' . $i . '_content'} ) && isset( $rating[ 'option' . $i ] ) && ( ! empty( $rating[ 'option' . $i ] ) || $rating[ 'option' . $i ] === '0' ) && strtoupper( ${'option' . $i . '_content'} ) != 'DEFAULT FEATURE ' . $i ) {
					$return_string .= '<div class="rev-option" data-value=' . $rating[ 'option' . $i ] . '>
                                                <div class="cwpr_clearfix">
                                                    ' . apply_filters( 'wppr_option_name_html', $id, ${'option' . $i . '_content'} ) . '
                                                    <span>' . round( $rating[ 'option' . $i ] / 10 ) . '/10</span>
                                                </div>
                                                <ul class="cwpr_clearfix"></ul>
                                            </div>';
				}
			}
			$return_string .= '</div><!-- end .review-wu-bars -->
                                </div><!-- end .review-wu-left -->
                                <div class="review-wu-right">
                                    <div class="pros">';
		}
		for ( $i = 1; $i <= cwppos( 'cwppos_option_nr' ); $i ++ ) {
			${'pro_option_' . $i} = do_shortcode( get_post_meta( $id, 'cwp_option_' . $i . '_pro', true ) );
			if ( empty( ${'pro_option_' . $i} ) ) {
				${'pro_option_' . $i} = '';
			}
		}
		for ( $i = 1; $i <= cwppos( 'cwppos_option_nr' ); $i ++ ) {
			${'cons_option_' . $i} = do_shortcode( get_post_meta( $id, 'cwp_option_' . $i . '_cons', true ) );
			if ( empty( ${'cons_option_' . $i} ) ) {
				${'cons_option_' . $i} = '';
			}
		}
		if ( $visual == 'full' ) {
			$return_string .= apply_filters( 'wppr_review_pros_text', $id, __( cwppos( 'cwppos_pros_text' ), 'cwppos' ) ) . ' <ul>';
			for ( $i = 1; $i <= cwppos( 'cwppos_option_nr' ); $i ++ ) {
				if ( ! empty( ${'pro_option_' . $i} ) ) {
					$return_string .= '   <li>' . ${'pro_option_' . $i} . '</li>';
				}
			}
			$return_string .= '     </ul>
                                    </div><!-- end .pros -->
                                    <div class="cons">';
			$return_string .= apply_filters( 'wppr_review_cons_text', $id, __( cwppos( 'cwppos_cons_text' ), 'cwppos' ) ) . ' <ul>';
			for ( $i = 1; $i <= cwppos( 'cwppos_option_nr' ); $i ++ ) {
				if ( ! empty( ${'cons_option_' . $i} ) ) {
					$return_string .= '   <li>' . ${'cons_option_' . $i} . '</li>';
				}
			}
			$return_string .= '
                                        </ul>
                                    </div>
                                </div><!-- end .review-wu-right -->
                                </div><!-- end .review-wrap-up -->
                            </section><!-- end #review-statistics -->';
		}
		if ( cwppos( 'cwppos_show_poweredby' ) == 'yes' && ! class_exists( 'CWP_PR_PRO_Core' ) ) {
			$return_string .= '<div style="font-size:12px;width:100%;float:right"><p style="float:right;">Powered by <a href="http://wordpress.org/plugins/wp-product-review/" target="_blank" rel="nofollow" > WP Product Review</a></p></div>';
		}
		$affiliate_text  = do_shortcode( get_post_meta( $id, 'cwp_product_affiliate_text', true ) );
		$affiliate_link  = do_shortcode( get_post_meta( $id, 'cwp_product_affiliate_link', true ) );
		$affiliate_text2 = do_shortcode( get_post_meta( $id, 'cwp_product_affiliate_text2', true ) );
		$affiliate_link2 = do_shortcode( get_post_meta( $id, 'cwp_product_affiliate_link2', true ) );
		if ( ! empty( $affiliate_text2 ) && ! empty( $affiliate_link2 ) ) {
			$bclass = 'affiliate-button2 affiliate-button';
		} else {
			$bclass = 'affiliate-button';
		}
		if ( $visual == 'full' && ! empty( $affiliate_text ) && ! empty( $affiliate_link ) ) {
			$return_string .= '<div class="' . $bclass . '">
                                        <a href="' . $affiliate_link . '" rel="nofollow" target="_blank"><span>' . $affiliate_text . '</span> </a>
                                    </div><!-- end .affiliate-button -->';
		}
		if ( $visual == 'full' && ! empty( $affiliate_text2 ) && ! empty( $affiliate_link2 ) ) {
			$return_string .= '<div class="affiliate-button affiliate-button2">
                                        <a href="' . $affiliate_link2 . '" rel="nofollow" target="_blank"><span>' . $affiliate_text2 . '</span> </a>
                                    </div><!-- end .affiliate-button -->';
		}
		if ( $visual == 'no' ) {
			$return_string = round( $divrating );
		}
	}
*/
	return $return_string; 
}