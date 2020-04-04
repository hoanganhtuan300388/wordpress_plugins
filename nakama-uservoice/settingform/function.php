<?php
class uservoiceCrSet {

    public static function getPatternNoPosttype( $post_id ) {
        $numberCountPost = 0;
        $countPost = ( array ) wp_count_posts( 'setting_uservoice', '' );

        if( $countPost ) {
            $numberCountPost = $countPost['publish'] + $countPost['draft'] + $countPost['future'] + $countPost['trash'] + $countPost['pending'] + $countPost['private'] + $countPost['inherit'];
        }

        $pattern_no_post_type = get_post_meta( $post_id, 'pattern_no_post_type', true );

        if( $pattern_no_post_type ) {
            return $pattern_no_post_type;
        } else {
            return $numberCountPost + 1;
        }
    }

}