@php
    $position = get_field('position', get_the_ID());
    $post_date = get_the_time('U');
    $current_time = current_time('timestamp');
    $time_diff = human_time_diff($post_date, $current_time);
@endphp

<!-- SINGLE JOB OFFER CARD -->
<div class="bg-white shadow-md rounded-lg px-6 py-8 mb-4 w-full">
    <div class="w-full h-[220px]">
        @php
            the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover']);
        @endphp
    </div>
    <h2 class="text-xl font-bold text-gray-900 mt-4 mb-2"><?php echo the_title(); ?></h2>
    @if($position)
      <p class="text-gray-700 text-base mb-2">
        {{ $position }}
      </p>
    @endif
    <time class="block text-xs text-gray-500 mb-6" itemprop="dateCreated" datetime="{{ get_the_date('Y-m-d') }}">
        {{ sprintf(__('%s temu', 'text-domain'), $time_diff) }}
    </time>
    <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded transition-colors" href="{{ the_permalink() }}" title="{{ the_title() }}">
      Zobacz więcej
    </a>
</div>
