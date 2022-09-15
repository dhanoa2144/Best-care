jQuery( function ( $ ) {
    'use strict';



    $(window).load(function() { // on window initial load
      $('.post-importer-form').insertAfter('.acf-settings-wrap');

      $('.post-importer-form').prepend('<div class="importer-instructions"><div class="title">Select Posts:</div><div class="para">Please select which posts to import below. They will be marked as <b>draft</b>, with the title "Imported".<Br/>Pulled Posts can be marked <B>External</b> to link back to its original URL, via the Sidebar on the edit Post screen.</div></div>');

      //$("table#posts-import").delegate("tr.heading", "click", function() {
      //  $(this).parent().next().fadeToggle();
      //});

      $(":checkbox").change(function(){
        var testvar = $(":checkbox:checked").length;
        console.log(testvar);
        var thisstatus = $(this).attr('imported');
        console.log(thisstatus);
        console.log(thisstatus);
        console.log(thisstatus);
        //if ( $(":checkbox:checked").length == 1 ) {
        //  if ( $(":checkbox").attr('imported','internal') )
        //  $(':checkbox:not(:checked)').prop('disabled', true);
        //}
      });

    });





    $(document).ready(function(){ // on window ready after


      $("table#posts-import").delegate("tr.heading", "click", function() {
        $(this).parent().next().fadeToggle();
      });


      $(":checkbox").change(function(){
        var testvar = $(":checkbox:checked").length;
        console.log(testvar);
        var thisstatus = $(this).attr('imported');
        console.log(thisstatus);
        console.log(thisstatus);
        console.log(thisstatus);
        //if ( $(":checkbox:checked").length == 1 ) {
        //  if ( $(":checkbox").attr('imported','internal') )
        //  $(':checkbox:not(:checked)').prop('disabled', true);
        //}
      });


      // fire again on ready
      $('.post-importer-form').insertAfter('.acf-settings-wrap');

    });



    $('#post-importer-submit').on('click', function(e) {
        e.preventDefault;

        $('#post-importer-submit').addClass('loading');
        $('tr.heading').off('click');
        $('input').prop('disabled', true);

        let posts = $('.post-importer-form .posts .post');


        posts.each(function() {
            var post = $(this);
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }

            var site = vars['site'],
                id = post.attr('id'),
                type = post.attr('data_type'),
                check = post.find('.select'),
                is_imported = post.find('.is_imported');

            var ext_or_int = is_imported;

            if(is_imported.is(':checked')) {
                is_imported = 'internal';
            } else {
                is_imported = 'external';
            }

            if (check.is(':checked')) {
                var data = {
                    action: 'gc_pull_posts',
                    id: id,
                    site: site,
                    type: type,
                    is_imported: is_imported,
                };
                $.post(ajaxurl, data, function(response) {
                    console.log(response);
                    console.log('updating:');
                    $('#post-importer-submit').addClass('loading');
                    console.log(is_imported);
                    console.log(site);
                    console.log(id);
                    console.log(type);
                    console.log('---,');
                })
                .success(function() {
                    post.addClass('complete');
                    $('#post-importer-submit').removeClass('loading');
                    console.log('completed!');
                    console.log(is_imported);
                    console.log(site);
                    console.log(id);
                    console.log(type);
                    console.log('---');
                }).fail(function() {
                    post.addClass('failed:');
                    console.log('failed!');
                    $('#post-importer-submit').removeClass('loading');
                    console.log(is_imported);
                    console.log(site);
                    console.log(id);
                    console.log(type);
                    console.log('---');
                });
            } else {
                post.hide();
            }


        });

    });
});
