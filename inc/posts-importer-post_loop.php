<?php
$types = ['post','posts', 'pages', 'news', 'events', 'bulletins'];
$site = $args['site'];
?>

<form class="post-importer-form" action="#" method="post" id="post-importer-form">
    <div class="posts">
        <table id="posts-import">
        <?php foreach($types as $t) : ?>
          <?php
            if ( ( $t == 'news' ) || ( $t == 'events' ) || ( $t == 'bulletins' ) || ( $t == 'pages' ) || ( $t == 'posts' ) ) {
          ?>
            <?php $request = @file_get_contents('http://' . $site . '/wp-json/wp/v2/' . $t . '?per_page=100'); ?>
            <?php if($request) : ?>
                  <?php $json_decoded_request = json_decode( $request ); ?>
                  <tr class="heading heading-<?php echo $t; ?>">
                    <td><?php echo $t; ?></td>
                  </tr>
                  <tbody class="imported-posts-group group-<?php echo $t;?>">
                      <tr class="post theader" style="border:0; display:none;">
                          <td style="border:1px solid;font-size:11px;font-weight:bold;padding:10px 0;" width="150" align="center">
                            External
                          </td>
                          <td style="border:1px solid;font-size:11px;font-weight:bold;padding:10px 0;" width="150" align="center">
                            Internal
                          </td>
                          <td style="padding-left:40px;font-size:10.5px;">
                            <i>External = Link away to the Original URL when shown in Grid. <BR />Internal = Show as full post in this site.</i>
                          </td>
                      </tr>
                      <?php foreach( $json_decoded_request as $apipost ) : ?>
                      <tr class="post  <?php echo $t; ?>" id="<?php echo $apipost->id; ?>" data_type="<?php echo $t; ?>">
                          <td width="80px" align="center" style="display:none;">
                            <input class="select external post-<?php echo $apipost->id; ?>" imported="external" type="checkbox" id="post-<?php echo $apipost->id; ?>" name="post-<?php echo $apipost->id; ?>">
                          </td>
                          <td td width="150px" align="center">
                            <input class="select is_imported internal post-<?php echo $apipost->id; ?>" imported="internal" type="checkbox" id="post-imported-<?php echo $apipost->id; ?>" name="post-imported-<?php echo $apipost->id; ?>">
                          </td>
                          <td width="400px">
                            <label id="post-name-<?php echo $apipost->id; ?>"><?php echo $apipost->title->rendered; ?></label>
                          </td>
                      </tr>
                    <?php endforeach; ?>
                    <tr style="border:0;">
                      <td style="padding:;padding-left:20px;font-size:12px;">
                        <i>Internal or External setting can be changed in the sidebar settings, on post edit screen.</i>
                      </td>
                    </tr>
                  </tbody>
            <?php endif; ?>
          <?php } ?>
        <?php endforeach; ?>
        </table>
        <input type="button" value="Pull Posts" id="post-importer-submit" class="btn" />
    </div>
</form>
