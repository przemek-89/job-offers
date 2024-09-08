jQuery(document).ready(function($) {
  function loadOffers(categoryId, page, perPage) {
      $.ajax({
          url: ajax_params.ajax_url,
          type: 'POST',
          dataType: 'json',
          data: {
              action: 'filter_posts',
              category: categoryId,
              paged: page,
              perPage: perPage
          },
          success: function(response) {
              if (response.success) {
                  if (page === 1) {
                      $('.job-offers__wrapper').html(response.data.posts.join(''));
                  } else {
                      $('.job-offers__wrapper').append(response.data.posts.join(''));
                  }

                  if (response.data.has_more_posts) {
                      $('button[loadmore-offers]')
                        .show()
                        .data('page', page + 1) 
                        .data('category', categoryId);
                  } else {
                      $('button[loadmore-offers]').hide();
                  }
              } else {
                  $('.job-offers__wrapper').html('<p>No posts found for this category.</p>');
                  $('button[loadmore-offers]').hide();
              }
          },
          error: function(xhr, status, error) {
              console.error('Error loading posts:', error);
          }
      });
  }

  $('#job-categories a[data-category]').on('click', function(e) {
      e.preventDefault();
      var categoryId = $(this).data('category');
      var perPage = $('button[loadmore-offers]').data('perpage');

      $('#job-categories a').removeClass('active');
      $(this).addClass('active');

      loadOffers(categoryId, 1, perPage);
  });

  $('body').on('click', 'button[loadmore-offers]', function(e) {
      e.preventDefault();

      var categoryId = $(this).data('category');
      var page = $(this).data('page');
      var perPage = $(this).data('perpage');

      loadOffers(categoryId, page, perPage);
  });

  loadOffers($('button[loadmore-offers]').data('category'), 1, $('button[loadmore-offers]').data('perpage'));
});