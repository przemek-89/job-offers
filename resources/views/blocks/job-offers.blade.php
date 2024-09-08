@php
  $perPage = 5;

  $args = array(
    'taxonomy' => 'job-category',
    'orderby' => 'name',
    'order'   => 'ASC'
  );
  $categories = get_categories($args);

  $first_category = isset($categories[0]) ? $categories[0]->term_id : 0;

  $the_query = new WP_Query([
    'post_type' => 'job',
    'posts_per_page' => $perPage,
    'paged' => 1,
    'tax_query' => array(
      array(
        'taxonomy' => 'job-category',
        'field' => 'term_id',
        'terms' => $first_category,
      )
    )
  ]);
@endphp

<section class="{{ $block->classes }}" @if(isset($block->block->anchor)) id="{{ $block->block->anchor }}" @endif>
  <div class="container mx-auto mb-8 px-4">

    <!-- JOB CATEGORIES -->
    <div class="w-full mt-4 mb-6">
      <p class="text-md font-bold mb-2">
        {{ __('Kategorie ofert pracy:', 'custom') }}
      </p>
      <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul id="job-categories" class="flex flex-wrap -mb-px">
          @foreach ($categories as $index => $category)
            <li class="me-2">
              <a class="inline-block p-4 border-b-2 border-transparent rounded-t-lg transition-colors hover:text-gray-600 hover:border-gray-300 {{ $index === 0 ? 'active' : '' }}" href="#" data-category="{{ $category->term_id }}">
                {{ $category->name }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>

    <!-- JOB OFFERS -->
    <div class="job-offers__wrapper w-full mb-4 flex flex-wrap md:grid md:gap-6 md:grid-cols-2">
      @if ($the_query->have_posts())
        @while ($the_query->have_posts())
          @php $the_query->the_post(); @endphp
          @include('components.job-card')
        @endwhile
      @else
        <p>{{ __('Sorry, no posts matched your criteria.', 'custom') }}</p>
      @endif
      @php wp_reset_postdata(); @endphp
    </div>

    <!-- LOAD MORE BUTTON -->
    <div class="w-full text-center mb-8">
      <button type="button" loadmore-offers data-perPage="{{ $perPage }}" data-page="1" data-category="{{ $first_category }}" class="mx-auto bg-gray-700 text-white hover:bg-gray-950 py-2 px-4 rounded transition-colors" style="display:none;">
        {{ __('Zobacz więcej ofert', 'custom') }}
      </button>
    </div>
  </div>
</section>
