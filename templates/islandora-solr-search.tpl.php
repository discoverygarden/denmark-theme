
<?php

/**
* @file islandora-solr-search.tpl.php
* Islandora solr search primary results template file
*
* Variables available:
* - $variables: all array elements of $variables can be used as a variable. e.g. $base_url equals $variables['base_url']
* - $base_url: The base url of the current website. eg: http://example.com/drupal .
* - $user: The user object.
*
* - $results: Primary profile results array
*/
?>

<?php if (empty($results)): ?>
  <p class="no-results"><?php print t('Sorry, but your search returned no results.'); ?></p>
<?php else: ?>
  <div class="islandora-solr-search-results islandora_solr_results">
  <?php
    $header = array('', t('Conference'), t('Author'), t('Title'), t('Video'), t('Slides'), t('Paper'));
    $rows = array();
    foreach ($results AS $result) {
      if (!empty($result['mxe_video_ms']['value'])) {
        $video = l(theme_image('sites/all/modules/dtu/images/video_32.png'), $result['mxe_video_ms']['value'], array('html' => TRUE));
      }
      else {
        $video = '';
      }

      if (!empty($result['mxe_slides_ms']['value'])) {
        $slides = l(theme_image('sites/all/modules/dtu/images/Crystal_Clear_mimetype_pdf_32.png'), $result['mxe_slides_ms']['value'], array('html' => TRUE));
      }
      else {
        $slides = '';
      }

      if (!empty($result['mxe_paper_ms']['value'])) {
        $paper = l(theme_image('sites/all/modules/dtu/images/Crystal_Clear_mimetype_document_32.png'), $result['mxe_paper_ms']['value'], array('html' => TRUE));
      }
      else {
        $paper = '';
      }

      // This isn't ideal, need to examine what's happening in solr
      $authors = explode(',', $result['mxe_author_ms']['value']);
      
      if (!empty($authors[0]) && !empty($authors[1])) {
        $main_author = check_plain($authors[0]) . ', ' . $authors[1];
        array_shift($authors);
        array_shift($authors);
      }
      else {
       $main_author = '';
      }

      $authors_list = '';
      for ($i = 0; $i < count($authors); $i = $i + 2) {
        if ($i !== 0) {
          $authors_list .= ';<br/>';
        }
        if (!empty($authors[$i]) && !empty($authors[($i+1)])) {
          $authors_list .= trim($authors[$i]) . ', ' . trim($authors[($i+1)]);
        }
      }

      $rows[] = array('<span class="jquery-toggle toggle-solr-row toggle-plus">&nbsp;&nbsp;&nbsp; </span>', check_plain($result['mxe_conference_ms']['value']), $main_author, l($result['mxe_title_ms']['value'], 'fedora/repository/' . $result['PID']['value']), $video, $slides, $paper );
      
      $abstract = '<div class="solr-abstract-wrapper">';

      if (!empty($result['mxe_abstract_ms']['value'])) {
        $abstract_text = check_plain($result['mxe_abstract_ms']['value']);
        $abstract .= '<span class="abstract">Abstract:</span><span class="description"> ' . $abstract_text . '</span>';
      }
         
      if (!empty($result['mxe_session_ms']['value']) || !empty($result['mxe_track_ms']['value'])) {
        $abstract .= '<div class="solr-track-session-wrapper">';
        if (!empty($result['mxe_track_ms']['value'])) {
          $abstract .= '<span class="abstract">Track: </span><span class="track">' . check_plain($result['mxe_track_ms']['value']) . '</span>';
        }

        if (!empty($result['mxe_session_ms']['value'])) {
          $abstract .= '<span class="abstract">Track: </span><span class="session">' . check_plain($result['mxe_session_ms']['value']) . '</span>';
        }
        $abstract .= '</div>';
      }
       
      $abstract .= '</div>';

      $rows[] = array('data' => array('', '', $authors_list, $abstract, '', '', ''), 'class' => 'toggleable-solr-row hidden');
      
    }

    print theme_table($header, $rows);
  ?>
  </div>
<?php endif;
