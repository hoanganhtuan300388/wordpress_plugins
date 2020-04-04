<?php
/*
 * Template Name: Member Template
 */
if ( have_posts() ) :
  while ( have_posts() ) : the_post();
    the_content();
  endwhile;
endif;
