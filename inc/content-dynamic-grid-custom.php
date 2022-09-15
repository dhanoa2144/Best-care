<?php
  // used in Grindstone theming

  global $post;
  $template_url = get_template_directory_uri();
  $this_post_id = get_the_ID();
  $this_page_id = get_the_ID();
  $sidebar = get_field('show_sidebar_on_page', $this_post_id);

 ?>

<?php //  build main terms,
      //  check what post type chosen to show

    $main_post_type = get_sub_field('grid_which_posts');    // grab main post type

    $posts_per_row = get_sub_field('grid_how_many'); // 3 or 4

    if ( get_sub_field('grid_which_posts') == 'news' ):
      $what_to_show = get_sub_field('filter_news_items');

    elseif ( get_sub_field('grid_which_posts') == 'bulletins' ):
      $what_to_show = get_sub_field('filter_bulletins_items');

    elseif ( get_sub_field('grid_which_posts') == 'qrg' ):
      $what_to_show = get_sub_field('filter_qrg_items');

    elseif ( get_sub_field('grid_which_posts') == 'events' ):
      $what_to_show = get_sub_field('filter_events_items');

    elseif ( get_sub_field('grid_which_posts') == 'microsites' ):
      $what_to_show = 'microsites';

    elseif ( get_sub_field('grid_which_posts') == 'profiles' ):
        $what_to_show = get_sub_field('filter_profiles_items');    
    endif;

    // make sure is array : $what_to_show
    if ($what_to_show) {
        // make sure is an array
         if (!is_array($what_to_show)) {
            $what_to_show = array($what_to_show);
         };
         // make sure all values are integers
         $what_to_show = array_map('intval', $what_to_show);
    } elseif (!$what_to_show) {
      //
    }

    // $category_to_show = esc_html( $subcategory->slug );     // aka subcategory

?>




<?php  // 1.  ARGS


// BEFORE LAUNCh. CHANGE ALL TO SHOW 8 PER PAGE .


  // ---------------
  // Check if FILTER !

  if (  ($main_post_type == 'news') && ( (isset($_POST['categories'])) || (isset($_GET['categories'])))
                                    || ( (isset($_POST['categories_audience'])) || (isset($_GET['categories_audience'])))
      )
  {

    $tax_query = array(
      'relation' => 'AND'
    );


      if ( isset( $_POST['categories'] ) ) {
        $category = $_POST['categories'] ;
      }
      elseif ( isset( $_GET['categories'] ) ) {
        $category = $_GET['categories'] ;
      }
    
      if (!empty($category  )){
          $cond = array(
            'taxonomy' => 'news_category',
            'field' => 'slug',
            'terms' => $category,
          );
          $tax_query[] = $cond;
      }


      if ( isset( $_POST['categories_audience'] ) ) {
        $term_audience = $_POST['categories_audience'] ;
      }
      elseif ( isset( $_GET['categories_audience'] ) ) {
        $term_audience = $_GET['categories_audience'] ;

      }
        if (!empty($term_audience))
          {
                $cond = array(
                  'taxonomy' => 'audience',
                  'field' => 'slug',
                  'terms' => $term_audience,
                );
                $tax_query[] = $cond;
          }

    $args = array(
      'post_type'=>'news',
      'post_status'=>'publish',
      'posts_per_page'=>8,
      'orderby' => 'date',
      'tax_query' => $tax_query
    );//echo '<pre>',print_r($args);
    ?>
    <script type="text/javascript">
    (function($){
      $(document).ready(function(){
        $('option.<?php echo $category; ?>').attr('selected', true);
        var headingadd = $('option.<?php echo $category; ?>').text();
        
        if(headingadd !== '')
        {
        $('#single-page-header h1').append(': ' + headingadd);
        }
        else{
          var newheadingadd = $('option.<?php echo $term_audience; ?>').text();
          $('#single-page-header h1').append(': ' + newheadingadd);
        }
        $('option.<?php echo $term_audience; ?>').attr('selected', true);
       
      });
    })(jQuery);
    </script>
    <?php

  }
  /*
    ** 
    Feedback query builder Starts
    **
  */
     
      elseif (  ($main_post_type == 'feedback') && ( (isset($_POST['feedback_category'])) || (isset($_GET['feedback_category'])))
        || ( (isset($_POST['feedback_audience'])) || (isset($_GET['feedback_audience'])))
      )  {

      $tax_query = array(
      'relation' => 'AND'
      );


      if ( isset( $_POST['feedback_category'] ) ) {
      $category = $_POST['feedback_category'] ;

      }
      elseif ( isset( $_GET['feedback_category'] ) ) {
      $category = $_GET['feedback_category'] ;

      }

      if (!empty($category  )){
      $cond = array(
      'taxonomy' => 'feedback_category',
      'field' => 'slug',
      'terms' => $category,
      );
      $tax_query[] = $cond;
      }


      if ( isset( $_POST['feedback_audience'] ) ) {
      $term_audience = $_POST['feedback_audience'] ;
      }
      elseif ( isset( $_GET['feedback_audience'] ) ) {
      $term_audience = $_GET['feedback_audience'] ;

      }
      if (!empty($term_audience)){
      $cond = array(
      'taxonomy' => 'audience',
      'field' => 'slug',
      'terms' => $term_audience,
      );
      $tax_query[] = $cond;
      }

      $args = array(
      'post_type'=>'feedback',
      'post_status'=>'publish',
      'posts_per_page'=>8,
      'orderby' => 'date',
      'tax_query' => $tax_query
      );
      
      //echo '<pre>',print_r($args);
      
      ?>
      <script type="text/javascript">
      (function($){
      $(document).ready(function(){
      $('option.<?php echo $category; ?>').attr('selected', true);
      var headingadd = $('option.<?php echo $category; ?>').text();

      if(headingadd !== '')
        {
        $('#single-page-header h1').append(': ' + headingadd);
        }
        else{
          var newheadingadd = $('option.<?php echo $term_audience; ?>').text();
          $('#single-page-header h1').append(': ' + newheadingadd);
        }
        $('option.<?php echo $term_audience; ?>').attr('selected', true);
      });
      })(jQuery);
      </script>
      <?php

      }
  /*
      ** 
      Feedback Query Builder Ends
      **
  */
  
/*
** 
      Projects Query Builder Starts
**
*/
elseif (  ($main_post_type == 'projects') && ( (isset($_POST['projects_category'])) || (isset($_GET['projects_category'])))
|| ( (isset($_POST['projects_audience'])) || (isset($_GET['projects_audience'])))
  )
{

    $tax_query = array(
      'relation' => 'AND'
      );


      if ( isset( $_POST['projects_category'] ) ) {
      $category = $_POST['projects_category'] ;

      }
      elseif ( isset( $_GET['projects_category'] ) ) {
      $category = $_GET['projects_category'] ;

      }

      if (!empty($category  )){
      $cond = array(
      'taxonomy' => 'projects_category',
      'field' => 'slug',
      'terms' => $category,
      );
      $tax_query[] = $cond;
      }


      if ( isset( $_POST['projects_audience'] ) ) {
      $term_audience = $_POST['projects_audience'] ;
      }
      elseif ( isset( $_GET['projects_audience'] ) ) {
      $term_audience = $_GET['projects_audience'] ;

      }
      if (!empty($term_audience)){
      $cond = array(
      'taxonomy' => 'audience',
      'field' => 'slug',
      'terms' => $term_audience,
      );
      $tax_query[] = $cond;
      }

      $args = array(
      'post_type'=>'projects',
      'post_status'=>'publish',
      'posts_per_page'=>8,
      'orderby' => 'date',
      'tax_query' => $tax_query
      );
      ?>

      <script type="text/javascript">
      (function($){
      $(document).ready(function(){
      $('option.<?php echo $category; ?>').attr('selected', true);
      var headingadd = $('option.<?php echo $category; ?>').text();

      if(headingadd !== '')
        {
        $('#single-page-header h1').append(': ' + headingadd);
        }
        else{
          var newheadingadd = $('option.<?php echo $term_audience; ?>').text();
          $('#single-page-header h1').append(': ' + newheadingadd);
        }
        $('option.<?php echo $term_audience; ?>').attr('selected', true);
      });
      })(jQuery);
      </script>
      <?php

  }

  
/*
**
Projects Query Builder Ends
**
*/



  /*
      **
      Training Query Builder Starts
      **
  */

  elseif (  ($main_post_type == 'training') && ( (isset($_POST['training_category'])) || (isset($_GET['training_category'])))
  || ( (isset($_POST['training_audience'])) || (isset($_GET['training_audience'])))
    )
  {

      $tax_query = array(
        'relation' => 'AND'
        );


        if ( isset( $_POST['training_category'] ) ) {
        $category = $_POST['training_category'] ;

        }
        elseif ( isset( $_GET['training_category'] ) ) {
        $category = $_GET['training_category'] ;

        }

        if (!empty($category  )){
        $cond = array(
        'taxonomy' => 'training_category',
        'field' => 'slug',
        'terms' => $category,
        );
        $tax_query[] = $cond;
        }


        if ( isset( $_POST['training_audience'] ) ) {
        $term_audience = $_POST['training_audience'] ;
        }
        elseif ( isset( $_GET['training_audience'] ) ) {
        $term_audience = $_GET['training_audience'] ;

        }
        if (!empty($term_audience)){
        $cond = array(
        'taxonomy' => 'audience',
        'field' => 'slug',
        'terms' => $term_audience,
        );
        $tax_query[] = $cond;
        }

        $args = array(
        'post_type'=>'training',
        'post_status'=>'publish',
        'posts_per_page'=>8,
        'orderby' => 'date',
        'tax_query' => $tax_query
        );
        ?>

        <script type="text/javascript">
        (function($){
        $(document).ready(function(){
        $('option.<?php echo $category; ?>').attr('selected', true);
        var headingadd = $('option.<?php echo $category; ?>').text();

        if(headingadd !== '')
          {
          $('#single-page-header h1').append(': ' + headingadd);
          }
          else{
            var newheadingadd = $('option.<?php echo $term_audience; ?>').text();
            $('#single-page-header h1').append(': ' + newheadingadd);
          }
          $('option.<?php echo $term_audience; ?>').attr('selected', true);
        });
        })(jQuery);
        </script>
        <?php

    }

  /*
      **
      Training Query Builder Ends
      **
  */


      elseif (  ( $main_post_type == 'bulletins' ) && ( ( isset($_POST['categories']) ) || ( isset($_GET['categories']) ) )  )  {
      if ( isset( $_POST['categories'] ) ) {
        $category = $_POST['categories'];
      } elseif ( isset( $_GET['categories'] ) ) {
        $category = $_GET['categories'];
      }

      $args = array(
        'post_type'=> 'bulletins',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order'   => 'DESC',
        'posts_per_page' => 8,
        'paged' => $paged ,
        'tax_query' => array(
          array(
            'taxonomy' => 'bulletin_category',
            'field' => 'slug',
            'terms' => $category,
            'operator' => 'AND',
          )
        )
      );
      ?>
      <script type="text/javascript">
      (function($){
        $(document).ready(function(){
          $('option.<?php echo $category; ?>').attr('selected', true);
          var headingadd = $('option.<?php echo $category; ?>').text();
          $('#single-page-header h1').append(': ' + headingadd);
        });
      })(jQuery);
      </script>
      <?php

  } elseif (  ( $main_post_type == 'news' ) && ( !($what_to_show) )  )  {
     $args = array(
       'post_type'=> 'news',
       'post_status' => 'publish',
       'orderby' => 'date',
       'order'   => 'DESC',
       'posts_per_page' => 8,
       'paged' => $paged ,
     );

  } elseif (  ( $main_post_type == 'events' ) && ( ( isset($_POST['eventdate']) ) || ( isset($_GET['eventdate']) ) )  )  {

    // var_dump($_POST);

      if ( isset( $_POST['eventdate'] ) ) {
        $category = $_POST['eventdate'];
      } elseif ( isset( $_GET['eventdate'] ) ) {
        $category = $_GET['eventdate'];
      }

      echo '<script>console.log("Events Date")</script>';

      $today = date('Ymd');

      if ( $category == 'future' ) {
        $args = array(
        'post_type' => 'events',
        'meta_query' => array(
          array(
              'key'     => 'event_date',
              'compare' => '>=',
              'value'   => $today,
            ),
          )
        );
      }

      if ( $category == 'past' ) {
        $args = array(
        'post_type' => 'events',
        'meta_query' => array(
          array(
              'key'     => 'event_date',
              'compare' => '<=',
              'value'   => $today,
            ),
          )
        );
      }

      if ( $category == '' ) {
        $args = array(
        'post_type' => 'events',
        'meta_key'  => 'event_date',
        'orderby'   => 'meta_value_num',
        'order'     => 'DESC',
        'post_status' => 'publish',
        'posts_per_page' => 8,
        'paged' => $paged ,
        );
      }

      ?>

      <script type="text/javascript">
      (function($){
        $(document).ready(function(){
          $('option.<?php echo $category; ?>').attr('selected', true);
          var headingadd = $('option.<?php echo $category; ?>').text();
          $('#single-page-header h1').append(': ' + headingadd);
        });
      })(jQuery);
      </script>
      <?php

  } elseif (  ( $main_post_type == 'events' ) && ( ( isset($_POST['locations']) ) || ( isset($_GET['locations']) ) )  )  {

      //  var_dump($_POST);

      if ( isset( $_POST['locations'] ) ) {
        $category = $_POST['locations'];
      } elseif ( isset( $_GET['locations'] ) ) {
        $category = $_GET['locations'];
      }

      echo '<script>console.log("Events - Locations")</script>';

     $args = array(
       'post_type'=> 'events',
       'meta_key'  => 'event_date',
       'orderby'   => 'meta_value_num',
       'order'     => 'DESC',
       'post_status' => 'publish',
       'posts_per_page' => 8,
       'paged' => $paged ,
       'tax_query' => array(
         array(
           'taxonomy' => 'event_location',
           'field' => 'slug',
           'terms' => $category,
           'operator' => 'AND',
         )
       )
     );
     ?>

     <script type="text/javascript">
     (function($){
       $(document).ready(function(){
         $('option.<?php echo $category; ?>').attr('selected', true);
         var headingadd = $('option.<?php echo $category; ?>').text();
         $('#single-page-header h1').append(': ' + headingadd);
       });
     })(jQuery);
     </script>
     <?php

  } elseif (  ( $main_post_type == 'events' ) && ( !($what_to_show) )  )  { // regular events
     $args = array(
       'post_type'=> 'events',
       'meta_key'  => 'event_date',
       'orderby'   => 'meta_value_num',
       'order'     => 'DESC',
       'post_status' => 'publish',
       'posts_per_page' => 8,
       'paged' => $paged ,
     );

 } elseif ( ($main_post_type == 'news') && (($what_to_show)) )  {
    $args = array(
      'post_type'=> 'news',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order'   => 'DESC',
      'posts_per_page' => 8,
      'paged' => $paged ,
      'tax_query' => array(
        array(
          'taxonomy' => 'news_category',
          'field' => 'ID',
          'terms' => $what_to_show
        )
      )
    );

  } 
  elseif ( ($main_post_type == 'profiles') ) {


  }
   elseif ( ($main_post_type == 'bulletins') && (!$what_to_show) ) {
   $args = array(
     'post_type'=> 'bulletins',
     'post_status' => 'publish',
     'posts_per_page' => 8,
     'orderby' => 'date',
     'order'   => 'DESC',
     'paged' => $paged ,
   );

 } elseif ( ($main_post_type == 'bulletins') && ($what_to_show) ) {
  $args = array(
    'post_type'=> 'bulletins',
    'post_status' => 'publish',
    'posts_per_page' => 8,
    'orderby' => 'date',
    'order'   => 'DESC',
    'paged' => $paged ,
    'tax_query' => array(
      array(
        'taxonomy' => 'bulletin_category',
        'field' => 'ID',
        'terms' => $what_to_show
      )
    )
  );

}  elseif ( ($main_post_type == 'qrg') && (!$what_to_show) ) {
   $args = array(
     'post_type'=> 'qrg',
     'post_status' => 'publish',
     'orderby' => 'date',
     'order'   => 'DESC',
     'posts_per_page' => 8,
     'paged' => $paged ,
   );

 }  elseif (  ( $main_post_type == 'qrg' ) && ( ( isset($_POST['search']) ) || ( isset($_GET['search']) ) )  ) {
    if ( isset( $_POST['search'] ) ) {
     $searchterm = $_POST['search'];
    } elseif ( isset( $_GET['search'] ) ) {
     $searchterm = $_GET['search'];
    }

    echo '<script>console.log("Search qRG")</script>';

    $args = array(
      'post_type'=> 'qrg',
      'post_status' => 'publish',
      'posts_per_page' => 8,
      'paged' => $paged,
      's' => $searchterm,
    );

} elseif ( ($main_post_type == 'qrg') && ($what_to_show) ) {
   $args = array(
     'post_type'=> 'qrg',
     'post_status' => 'publish',
     'posts_per_page' => 8,
     'paged' => $paged ,
     'tax_query' => array(
       array(
         'taxonomy' => 'qrg_group',
         'terms' => $what_to_show
       )
     )
   );

 } elseif ($main_post_type == 'microsites') {

   // see bottom of page
   // line 558

 } 
 
 /*
    **
      Feedback Default Query
    **
  */

 elseif ( ($main_post_type == 'feedback') ) {
  $args = array(
    'post_type'=> 'feedback',     // <-- CPT = feedback
    'post_status' => 'publish',
    'orderby' => 'date',
    'order'   => 'DESC',
    'posts_per_page' => 8,
    'paged' => $paged ,
  );

}

/*
    **
    Training Default Query
    **
*/

elseif ( ($main_post_type == 'training') && (!$what_to_show) ) {
  
  $args = array(
    'post_type'=> 'training',     // <-- CPT = TRAINING
    'post_status' => 'publish',
    'orderby' => 'date',
    'order'   => 'DESC',
    'posts_per_page' => 8,
    'paged' => $paged ,
  );

} 

/*
    **
    Projects Default Query
    **
*/

elseif ( ($main_post_type == 'projects') ) {
  $args = array(
    'post_type'=> 'projects',     // <-- CPT = PROJECTS
    'post_status' => 'publish',
    'orderby' => 'date',
    'order'   => 'DESC',
    'posts_per_page' => 8,
    'paged' => $paged ,
  );

} 

 else 
 {
   $args = array(
     'post_type'=> 'news',
     'post_status' => 'publish',
     'posts_per_page' => 1,
     'paged' => $paged ,
   );
  }

//print_r($args);

$result = new WP_Query($args);

?>

<?php // var_dump($category); ?>

<?php 
   /*
        **
        ** 
            Tab Menu Starts
        **
        **
    */
if ( get_sub_field('show_tab_menu_') ) :
    if( have_rows('tab_menus') ):
  ?>
      <div class="row" style="padding-bottom:30px;">
            <div class="col-12 col-sm-12">
          <?php 
           while( have_rows('tab_menus') ) : the_row();
                $menu_text = get_sub_field('tab_menu_name');
                $menu_link = get_sub_field('tab_menu_link');
                if( $menu_link ): ?>
                  <a class="btn default tab_menu" href="<?php echo esc_url( $menu_link ); ?>"><?php echo $menu_text;?></a>
              <?php endif;
            endwhile;
          ?>
              <hr style="padding:0;margin: 28px 0 0px 0px;"/>
            </div>
          </div>

      <?php
    endif;
endif;?>

 <?php 
    /*
        **
        ** 
          Tab Menu Ends
        **
        **
    */?>    

     
<?php if ( !get_sub_field('grid_filters_hide') ) : ?>

  <div class="row" style="">

    <?php	
      /*
        **
        ** 
          News Filters Starts
        **
        **
      */
    
    if ( $main_post_type=='news' ) { ?>
    
    <div class="col-lg-9">
      <h2 class="filter-head">Filter by</h2>
     
         <form method="get" action="<?php the_permalink($this_page_id); ?>">

         <!-- Select Wrapper Div Starts-->
          <div class="select_wrapper" style="background: #fff; ">
            <select id="the_services_location" name="categories"  onchange="this.form.submit();">
              <option value="0" class="category">Category</option>
              <option class="categoryall" value="0">Show All</option>
              <?php $the_categories = '';
                    $terms = get_terms(
                              array(
                                      'taxonomy'   => 'news_category', // Category Taxonomy
                                      'hide_empty' => true,
                                  )
                              );

                      if ( ! empty( $terms ) && is_array( $terms ) ) {
                        foreach ( $terms as $term ) {
                          $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                        }
                      }
                    echo $the_categories;
                ?>
              </select>
              </div><!-- Select Wrapper Div Ends-->


          <div class="select-audience"><!-- Audience Div Starts-->

          <!-- Second dropdown added for second taxonomy filter (Audience) -->       

            <select id="categories_audience" name="categories_audience"  onchange="this.form.submit();">
            <option value="0">All Staff & Volunteers</option>
              <?php $the_categories = '';
                    $terms = get_terms(
                              array(
                                      'taxonomy'   => 'audience', // Audience Taxonomy
                                      'hide_empty' => true,
                                      'orderby' => 'name',
                                      'order' => 'DESC' 
                                  )
                              );

                      if ( ! empty( $terms ) && is_array( $terms ) ) {
                        foreach ( $terms as $term ) {
                          $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                        }
                      }
                    echo $the_categories;
                ?>
              </select>
          </div><!-- Audience Div Ends-->
        </form>
                    </div><!-- col-9-->


                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-5">
          <!-- Ajax News Search--> 
          <div class="ajax-search align-right alignright">
            <div style="padding-right:20px;">
            <div class="ajax_news search">
                <input type="text" id="searchnews" class="searchnews" name="searchnews" onchange="" placeholder="Search News">
              </div>
              </div>
          </div>
          <!-- Ajax Search-->
                    </div><!-- col-lg-3-->
        <!-- Script for Ajax News Search -->
        <script>
            (function($){
              $(document).ready(function(){
                $('#searchnews').keyup(function(){
                    var searchterm = this.value.toLowerCase();

                    if(searchterm == "") {
                      $('.lbc-card-single, .news-item-listing').show();
                    }
                    else {
                      $('.news-item-listing').each(function(){
                        var itemlisting = $(this).text().toLowerCase();
                        $(this).toggle(itemlisting.indexOf(searchterm) !== -1);

                            $('.grid-card-single').each(function(){
                              let the_number_of_services_visible = $(this).children('.grid-card').length;
                              if(the_number_of_services_visible == 0){
                                $(this).hide();
                              }
                            });

                      });
                    }

                  });

                
              }); // close DOCUMENT.READY

              })(jQuery);
        
          </script>
    <?php } 
/*
  ** 
    **
    News Filters Ends  
    **  
  **
*/

/*
  ** 
     **
      Feedback Filters Starts
     **
  **
*/

    elseif($main_post_type=='feedback' ){ ?>
    <div class="col-lg-9">
     <h2 class="filter-head">Filter by</h2>
     <form method="get" action="<?php the_permalink($this_page_id); ?>">

      <div class="select_wrapper" style="background: #fff; "><!-- select_wrapper div starts-->

        <select id="the_services_location" name="feedback_category"  onchange="this.form.submit();">
          <option value="0">Category</option>
          <option class="" value="">Show All</option>
          <?php $the_categories = '';
                $terms = get_terms(
                          array(
                                  'taxonomy'   => 'feedback_category', //Category Taxonomy
                                  'hide_empty' => true,
                              )
                          );

                  if ( ! empty( $terms ) && is_array( $terms ) ) {
                    foreach ( $terms as $term ) {
                      $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                    }
                  }
                echo $the_categories;
            ?>
          </select>
          </div><!-- select_wrapper div ends-->


      <div class="select-audience"><!-- Audience Div Starts-->

      <!-- Below dropdown added for second taxonomy filter (Audience)-->   

        <select id="feedback_audience" name="feedback_audience"  onchange="this.form.submit();">
        <option value="0">All Staff & Volunteers</option>
          <?php $the_categories = '';
                $terms = get_terms(
                          array(
                                  'taxonomy'   => 'audience', //Audience Taxonmy
                                  'hide_empty' => true,
                                  'orderby' => 'name',
                                  'order' => 'DESC' 
                              )
                          );

                  if ( ! empty( $terms ) && is_array( $terms ) ) {
                    foreach ( $terms as $term ) {
                      $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                    }
                  }
                echo $the_categories;
            ?>
          </select>
      </div><!-- Audience Div Ends-->
    </form>
  </div>  
   <?php }
/*
  ** 
     **
      Feedback Filters Ends
     **
  **
*/

/*
** 
**
Project Filters Starts
**
**
*/

/*
** 
**
Project Filters Ends
**
**
*/
elseif($main_post_type=='projects' )
    { ?>
<div class="col-lg-9">
      <h2 class="filter-head">Filter by</h2>
     <form method="get" action="<?php the_permalink($this_page_id); ?>">

      <div class="select_wrapper" style="background: #fff; "><!-- select_wrapper div starts-->

        <select id="the_services_location" name="projects_category"  onchange="this.form.submit();">
          <option value="0">Category</option>
          <option class="" value="">Show All</option>
          <?php $the_categories = '';
                $terms = get_terms(
                          array(
                                  'taxonomy'   => 'projects_category', //Category Taxonomy
                                  'hide_empty' => true,
                              )
                          );

                  if ( ! empty( $terms ) && is_array( $terms ) ) {
                    foreach ( $terms as $term ) {
                      $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                    }
                  }
                echo $the_categories;
            ?>
          </select>
          </div><!-- select_wrapper div ends-->


      <div class="select-audience"><!-- Audience Div Starts-->

      <!-- Below dropdown added for second taxonomy filter (Audience)-->   

        <select id="projects_audience" name="projects_audience"  onchange="this.form.submit();">
        <option value="0">All Staff & Volunteers</option>
          <?php $the_categories = '';
                $terms = get_terms(
                          array(
                                  'taxonomy'   => 'audience', //Audience Taxonmy
                                  'hide_empty' => true,
                                  'orderby' => 'name',
                                  'order' => 'DESC' 
                              )
                          );

                  if ( ! empty( $terms ) && is_array( $terms ) ) {
                    foreach ( $terms as $term ) {
                      $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                    }
                  }
                echo $the_categories;
            ?>
          </select>
      </div><!-- Audience Div Ends-->
    </form>
  </div>  
<?php }

/*
  ** 
     **
      Training Filters Starts
     **
  **
*/
    elseif($main_post_type=='training' )
    { ?>
    <div class="col-lg-9">
      <h2 class="filter-head">Filter by</h2>
     <form method="get" action="<?php the_permalink($this_page_id); ?>">

      <div class="select_wrapper" style="background: #fff; "><!-- select_wrapper div starts-->

        <select id="the_services_location" name="training_category"  onchange="this.form.submit();">
          <option value="0">Category</option>
          <option class="" value="">Show All</option>
          <?php $the_categories = '';
                $terms = get_terms(
                          array(
                                  'taxonomy'   => 'training_category', //Category Taxonomy
                                  'hide_empty' => true,
                              )
                          );

                  if ( ! empty( $terms ) && is_array( $terms ) ) {
                    foreach ( $terms as $term ) {
                      $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                    }
                  }
                echo $the_categories;
            ?>
          </select>
          </div><!-- select_wrapper div ends-->


      <div class="select-audience"><!-- Audience Div Starts-->

      <!-- Below dropdown added for second taxonomy filter (Audience)-->   

        <select id="feedback_audience" name="training_audience"  onchange="this.form.submit();">
        <option value="0">All Staff & Volunteers</option>
          <?php $the_categories = '';
                $terms = get_terms(
                          array(
                                  'taxonomy'   => 'audience', //Audience Taxonmy
                                  'hide_empty' => true,
                                  'orderby' => 'name',
                                  'order' => 'DESC' 
                              )
                          );

                  if ( ! empty( $terms ) && is_array( $terms ) ) {
                    foreach ( $terms as $term ) {
                      $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                    }
                  }
                echo $the_categories;
            ?>
          </select>
      </div><!-- Audience Div Ends-->
    </form>
  </div>  

<?php
    }
/*
  ** 
     **
      Training Filters Ends
     **
  **
*/

   elseif ( $main_post_type=='bulletins' ) { ?>
        <h2 class="filter-head">Filter by</h2>
        <div class="select_wrapper" style="background: #fff; ">
          <form method="post" action="<?php the_permalink($this_page_id); ?>">
            <select id="the_services_location" name="categories"  onchange="this.form.submit();">
              <option value="">Category</option>
              <option class="" value="">Show All</option>
              <?php $the_categories = '';
   						     $terms = get_terms(
           				           array(
           	                        'taxonomy'   => 'bulletin_category',
           			                     'hide_empty' => true,
           	                     )
                             );

       							if ( ! empty( $terms ) && is_array( $terms ) ) {
       								foreach ( $terms as $term ) {
       									$the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
       								}
       							}
     							echo $the_categories;
   						?>
             </select>
         </form>
        </div>
    <?php } elseif ( $main_post_type=='qrg' ) { ?>
      <div class="row">
        <div class="col-12 col-sm-8">
          <h2 class="filter-head">Filter by</h2>
          <div class="select_wrapper" style="background: #fff; ">
            <form method="post" action="<?php the_permalink($this_page_id); ?>">
              <select id="the_services_location" name="categories"  onchange="this.form.submit();">
                <option value="">Category</option>
                <option class="" value="">Show All</option>
                <?php $the_categories = '';
                     $terms = get_terms(
                               array(
                                      'taxonomy'   => 'qrg_group',
                                       'hide_empty' => true,
                                   )
                               );

                      if ( ! empty( $terms ) && is_array( $terms ) ) {
                        foreach ( $terms as $term ) {
                          $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                        }
                      }
                    echo $the_categories;
                ?>
               </select>
           </form>
          </div>
        </div>
          <div class="col-12 col-sm-4 align-right alignright">
            <div style="padding-right:20px;">
            <h2 class="filter-head">Sort by</h2>
            <div class="select_wrapper" style="background: #fff; width:280px !important;">
              <form method="post" action="<?php the_permalink($this_page_id); ?>">
                <input type="text" id="search" name="search" onchange="this.form.submit();">
             </form>
             </div>
             </div>
          </div>
      </div>


   <?php } elseif ( $main_post_type=='events' ) { ?>
     <div style="padding-left:20px;">
     <div class="row">
       <div class="col-12 col-sm-6">
         <h2 class="filter-head">Filter by</h2>
         <div class="select_wrapper" style="background: #fff; ">
           <form method="post" action="<?php the_permalink($this_page_id); ?>">
             <select id="the_services_location" name="locations" onchange="this.form.submit();">
               <option value="">Location</option>
               <option class="" value="">Show All</option>
               <?php $the_categories = '';
                    $terms = get_terms(
                              array(
                                     'taxonomy'   => 'event_location',
                                      'hide_empty' => true,
                                  )
                              );
                     if ( ! empty( $terms ) && is_array( $terms ) ) {
                       foreach ( $terms as $term ) {
                         $the_categories = '<option class="'.$term->slug.'" name="'.$term->slug.'" value="'.$term->slug.'">'.$term->name.'</option>'.$the_categories;
                       }
                     }
                   echo $the_categories;
               ?>
              </select>
          </form>
         </div>
       </div>
       <div class="col-12 col-sm-5">
         <div style="">
         <h2 class="filter-head">Sort by</h2>
         <div class="select_wrapper" style="background: #fff; width:280px !important;">
           <form method="post" action="<?php the_permalink($this_page_id); ?>">
             <select id="the_event_date" name="eventdate" onchange="this.form.submit();">
               <option value="">Date</option>
               <option class="" value="">Show All</option>
               <option class="future" name="future" value="future">Upcoming Events</option>
               <option class="past" name="past" value="past">Past Events</option>
              </select>
          </form>
          </div>
          </div>
       </div>
    </div>
    </div>
    <?php } ?>
</div>
<?php endif; ?>



<div class="row grid-custom-top <?php if ( !get_sub_field('use_separator') ) : ?>no-separator<?php endif; ?>" style="clear:both;">
  <?php if ( get_sub_field('heading') ) : ?>
    <div class="grid-heading col-12 <?php if ( get_sub_field('blurb_text') ) { ?>col-md-3<?php } ?>">
        <h3><?php the_sub_field('heading'); ?></h3>
    </div>
  <?php endif; ?>
  <?php if ( get_sub_field('blurb_text') ) : ?>
    <div class="grid-blurb col-12  <?php if ( get_sub_field('heading') ) { ?>col-md-9<?php } else { ?>col-md-12<?php } ?>">
        <p><?php the_sub_field('blurb_text'); ?></p>
    </div>
  <?php endif; ?>
</div>





<?php if ($result->have_posts()) : ?>

  <div class="section  no-gutters row grid-custom grid-custom-<?php echo $main_post_type; ?>">
      <?php while ( $result->have_posts() ) : $result->the_post(); ?>
        <?php if ( $posts_per_row == '3' ) { ?>
          <div class="grid-card-wrap news-item-listing col-12 col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
              <?php require get_template_directory() . '/inc/content-dynamic-single-grid-item.php'; ?>
          </div>
        <?php } elseif ( $posts_per_row == '4' ) { ?>
          <div class="grid-card-wrap news-item-listing col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
            <?php require get_template_directory() . '/inc/content-dynamic-single-grid-item.php'; ?>
          </div>
        <?php } elseif ( $posts_per_row == '5' ) { ?>
        <div class="grid-card-wrap news-item-listing col-12 col-xs-12 col-sm-6 col-md-6 col-lg col-xl">
          <?php require get_template_directory() . '/inc/content-dynamic-single-grid-item.php'; ?>
        </div>
      <?php } else { ?>
        <div class="grid-card-wrap news-item-listing col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
          <?php require get_template_directory() . '/inc/content-dynamic-single-grid-item.php'; ?>
        </div>
      <?php } ?>
      <?php endwhile; ?>
  </div>

  <div class="pagination">
      <?php
          echo paginate_links( array(
              'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
              'total'        => $result->max_num_pages,
              'current'      => max( 1, get_query_var( 'paged' ) ),
              'format'       => '?paged=%#%',
              'show_all'     => false,
              'type'         => 'plain',
              'end_size'     => 2,
              'mid_size'     => 1,
              'prev_next'    => true,
              'prev_text'    => sprintf( '<i></i> %1$s', __( '<<', 'text-domain' ) ),
              'next_text'    => sprintf( '%1$s <i></i>', __( '>>', 'text-domain' ) ),
              'add_args'     => false,
              'add_fragment' => '',
          ) );
      ?>
  </div>

  <div class="grid-lower-btn align-right">
    <?php if (get_sub_field('optional_button_check')) {
            if ( get_sub_field('optional_button') ) {
            $grid_link = get_sub_field('optional_button');
              ?>
              <div class="btn-link">
                <a href="<?php echo $grid_link['url']; ?>"><?php echo $grid_link['title']; ?> &nbsp; <i class="far fa-long-arrow-right"></i></a>
              </div>
    <?php }
    }?>
  </div>

<?php endif; ?>





<?php if ( $main_post_type == 'microsites' ) {
  //
  // This section was added as a new request, after the main development.
  //
  ?>

  <div class="section-microsites row grid-custom grid-custom-<?php echo $main_post_type; ?>" style="background:transparent;">
  <?php

  $terms = get_terms([
    'taxonomy' => 'departments',
    'hide_empty' => false,
    'number'    => 999,
  ]);

  foreach($terms as $term) {

      $term_id = $term->term_id;

      $thumb_title = $term->name;
      $thumb_site = get_field('thumbnail', $term->taxonomy . '_' . $term_id);
      $thumb_img = $thumb_site['url'];
      $thumb_url = get_field('website_url', $term->taxonomy . '_' . $term_id);
      $thumb_catcolor = get_field('cat_color', $term->taxonomy . '_' . $term_id);
      ?>

      <?php if ( $posts_per_row == '3' ) { ?>
        <div class="grid-card-wrap col-12 col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
      <?php } elseif ( $posts_per_row == '4' ) { ?>
        <div class="grid-card-wrap col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
      <?php } elseif ( $posts_per_row == '5' ) { ?>
        <div class="grid-card-wrap col-12 col-xs-12 col-sm-6 col-md-6 col-lg col-xl">
      <?php } ?>

        <a class="microsite-icon dept-id-<?php echo $term_id; ?>" target="_blank" href="<?php echo get_field('website_url', $term->taxonomy . '_' . $term_id); ?>" title="<?php echo $term->name ?>" alt="<?php echo $term->name ?>" style="background-image:url('<?php echo $thumb_site['url']; ?>');"></a>

      </div>

  <?php } ?>

  </div>
<?php } ?>


<?php if ( $main_post_type == 'profiles' ) { ?>


  <?php  // This section was added as a new request, after the main development.

  $dept_list = get_categories('taxonomy=department&type=profiles');

  foreach ($dept_list as $dept_item) {
    wp_reset_query();
    echo '<a class="btn std" href="#dept-'. $dept_item->slug .'">'. $dept_item->name  .'</a> &nbsp; ';
  }

  echo '<hr/ style="margin-top:20px;">';

  // 2

  $custom_terms = get_terms('department');

  foreach($custom_terms as $custom_term) {

      wp_reset_query();

      $args = array('post_type' => 'profiles',
          'tax_query' => array(
              array(
                  'taxonomy' => 'department',
                  'field' => 'slug',
                  'terms' => $custom_term->slug,
                  'posts_per_page' => 999,
              ),
          ),
       );

       $loop = new WP_Query($args);
       if( $loop->have_posts() ) {

          echo '<h2 style="padding:30px 0;" id="dept-'. $custom_term->slug .'">'.$custom_term->name.'</h2>';
          echo '<div class="row no-gutters">';

          while($loop->have_posts()) : $loop->the_post();
          $featured_img_url1 = get_the_post_thumbnail_url(get_the_ID(),'medium');
          ?>

          <?php if ( $posts_per_row == '3' ) { ?>
            <div class="grid-card-wrap col-12 col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">

              <a href="<?php the_permalink(); ?>" class="profile-grid-item grid-card row no-gutters" style="display:flex; margin-bottom:20px; min-height:150px;">
                <div class="col-4" style="background-image:url('<?php echo $featured_img_url1; ?>');background-size:cover;background-position:center center;">
                  &nbsp;
                </div>
                <div class="col-8" style="padding:20px;">
                  <h5><?php the_title(); ?></h5>
                  <div class="secondary_accent_colour heading1" style="font-size:13px;">
                    <?php the_field('excerpt'); ?>
                  </div>
                </div>
              </a>

            </div>
          <?php } elseif ( $posts_per_row == '4' ) { ?>
            <div class="grid-card-wrap col-12 col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">

              <a href="<?php the_permalink(); ?>" class="profile-grid-item grid-card row no-gutters" style="display:flex; margin-bottom:20px; min-height:150px;">
                <div class="col-4" style="background-image:url('<?php echo $featured_img_url1; ?>');background-size:cover;background-position:center center;">
                  &nbsp;
                </div>
                <div class="col-8" style="padding:20px;">
                  <h5><?php the_title(); ?></h5>
                  <div class="secondary_accent_colour heading1" style="font-size:13px;">
                    <?php the_field('excerpt'); ?>
                  </div>
                </div>
              </a>

            </div>
          <?php } // close Per Row ?>

          <?php endwhile; // close while Have Posts in Loop

          echo '</div>';
       } // close
   }
   wp_reset_query();
  ?>



<?php } ?>



<?php wp_reset_postdata(); ?>
