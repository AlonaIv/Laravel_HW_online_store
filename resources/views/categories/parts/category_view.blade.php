@if ($category)
        <a href="{{ route('categories.show', $category) }}"
       class="text-muted btn btn-outline-dark">
        {{ __($category?->name ?? '') }}
    </a>
@endif
